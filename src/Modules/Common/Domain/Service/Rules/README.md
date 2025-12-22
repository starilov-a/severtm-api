## 1. Общий принцип (RuleChain → Rule → RuleResult)
Модуль `Rules` реализует простую, но расширяемую цепочку бизнес‑правил. Клиентский код передает единый контекст (`RuleContext`) в `RuleChain`. Цепочка поочередно вызывает `Rule::check()`, и каждое правило возвращает `RuleResult` (успех/ошибка + сообщение + meta‑данные). Первое неуспешное правило останавливает цепочку: для `SOFT`‑правил выполнение прекращается с `false`, для `HARD` выбрасывается бизнес‑исключение. В итоге логика использования выглядит так:

```
Context → RuleChain::checkAll()
             ↳ ChainRuleItem (Rule + режим)*
                         ↳ Rule::check(Context)
                                     ↳ RuleResult::ok()/fail()
```

## 2. Rule
- Базовый класс: `App\Modules\Common\Domain\Service\Rules\Rule` (реализует `RuleInterface`).
- Контракт: `public function check(object $context = null): RuleResult`.
- Каждое правило само валидирует тип контекста через `instanceof Has*`‑интерфейсов и выбрасывает `LogicException`, если контекст собран неправильно. Это позволяет обнаружить ошибки конфигурации раньше, чем бизнес‑ошибки.
- Результат: `RuleResult::ok()` при успехе, `RuleResult::fail('message', ['meta' => ...])` при нарушении. Сообщение из `fail()` попадет в лог или в текст бизнес‑исключения.
- Пример: `Definitions/ProdServModes/UnitsMustBePositiveRule` проверяет, что количество подключаемых единиц услуги > 0 и использует `HasModeUnitCount`.

## 3. RuleChain
- Реализация: `App\Modules\Common\Domain\Service\Rules\RuleChain` (+ интерфейс `RuleChainInterface`; текущая сигнатура в интерфейсе устарела, ориентируйтесь на реализацию `checkAll(object $context): bool`).
- Конструктор цепочки получает `LoggerService` и формирует массив `ChainRuleItem` (правило + режим выполнения + класс исключения).
- Контекст для цепочки обязан реализовать как минимум `HasWebAction` и `HasMaster`, чтобы можно было корректно залогировать ошибку/исключение.
- Алгоритм `checkAll()`:
  1. Итерирует элементы `$items`.
  2. Вызывает `Rule::check($context)`.
  3. При `RuleResult::ok()` просто продолжает.
  4. Если `RuleMode::SOFT`, пишет `businessLog` и возвращает `false`.
  5. Если `RuleMode::HARD`, выбрасывает `ImportantBusinessException` (или переданный класс) с сообщением правила.
- Цепочки наследуются от `RuleChain` (см. `Chains/CreateFreezeTaskRuleChain`, `Chains/AddServiceModeRuleChain`, `Chains/ShouldMakeWriteOffRuleChain`).

### ChainRuleItem и режимы
```php
new ChainRuleItem(
    rule: $userIsNotActivatedRule,
    mode: RuleMode::SOFT,        // HARD по умолчанию
    exceptionClass: SomeException::class // опционально
);
```
`RuleMode::SOFT` — нарушения логируются и дают `false`. `RuleMode::HARD` — бросается исключение, которое должен обработать вызывающий код или верхний слой.

## 4. RuleResult и вспомогательные сущности
- `RuleResult` — value object с тремя полями:
  - `bool $ok` — флаг успешности.
  - `?string $message` — человекочитаемое описание сбоя.
  - `array $meta` — произвольные данные для дальнейшей обработки (например, код ошибки, ID сущности).
- Дополнительно используются:
  - `RuleMode` (`HARD|SOFT`) — режим реакции цепочки на провал правила.
  - `ChainRuleItem` — связывает правило с режимом и специфичным исключением.
  - Сообщение и `meta` можно использовать для последующей локализации ответа или формирования DTO.

## 5. Контекст и Has* интерфейсы
- Все контексты лежат в `Contexts` и реализуют набор `Has*`‑интерфейсов из `Contexts/ContextInterfaces`.
- Каждый `Has*` описывает минимальный контракт на данные (например, `HasUser`, `HasFinPeriod`, `HasModeUnitCount`, `HasStartFreezeDate`).
- Рекомендованный подход:
  1. Создать специализированный контекст (например, `AddServiceModeContext`, `CreateFreezeTaskContext`, `ShouldMakeWriteOffContext`).
  2. Имплементировать только те интерфейсы, которые реально нужны правилам цепочки.
  3. В самих правилах проверять контекст через `instanceof` всех требуемых `Has*`. Это документирует зависимость и упрощает покрытие тестами.
- `RuleChain` также рассчитывает, что контекст реализует минимальный набор (`HasWebAction`, `HasMaster`) для корректного протоколирования.

## 6. Вызов Rule / RuleChain
### Отдельное правило
```php
$rule = new UnitsMustBePositiveRule();
$context = new AddServiceModeContext(...); // реализует HasModeUnitCount
$result = $rule->check($context);
if (!$result->ok) {
    // обработка сообщения, meta и т.д.
}
```

### Цепочка правил
Фрагмент из `App\Modules\Common\Domain\Service\WriteOffService::makeWriteOffForAddingMode()`:
```php
$context = new ShouldMakeWriteOffContext(
    webAction: $webAction,
    master: $master,
    user: $writeOffDto->getUser(),
    finPeriod: $writeOffDto->getServMode()->getFinPeriod(),
    userPayableType: $writeOffDto->getPayableType(),
    userServMode: $writeOffDto->getServMode(),
    refundFinPeriod: $writeOffDto->getRefundFinPeriod(),
    isAppliedWriteOff: $writeOffDto->isApplied(),
    isRealWriteOff: $writeOffDto->isReal()
);

if (!$this->shouldMakeWriteOffRuleChain->checkAll($context)) {
    // цепочка завершилась на мягком правиле → просто логируем и прерываем сценарий
}
```
Если цепочка проходит полностью, метод возвращает `true`, и бизнес‑процесс (создание платежей, задач, списаний) продолжается.

## 7. Дополнительные рекомендации
1. **Новые правила**: именуйте классы в терминах домена (`FreezeOnlyOncePerMonthRule`). В методе `check()` оставляйте небольшие блоки логики и при необходимости выносите тяжелые операции во внедренные сервисы.
2. **Новые цепочки**: наследуйтесь от `RuleChain`, собирайте `$this->items` в конструкторе, четко задавайте режим (`SOFT` для предупреждений, `HARD` для критических нарушений) и при необходимости прокидывайте свой класс исключения.
3. **Контекст**: если один и тот же контекст нужен нескольким цепочкам, делайте его максимально общим и не добавляйте в него лишние зависимости. Разбивайте на несколько контекстов, если требования конфликтуют.
4. **Логирование и аудит**: `RuleChain` автоматически пишет `BusinessLogDto` для мягких правил. Следите, чтобы `HasWebAction` и `HasMaster` всегда возвращали валидные идентификаторы — это попадает в журнал.
5. **Тестирование**: покрывайте правила юнит‑тестами, передавая минимальные реализации `Has*` (или анонимные классы). Для цепочек используйте мок‑правила, чтобы проверять реакцию на `SOFT/HARD`‑результаты.
6. **Исключения**: по умолчанию выбрасывается `ImportantBusinessException`. Если нужно вернуть специфичное сообщение/код ошибки, укажите свой `exceptionClass` в `ChainRuleItem`.
