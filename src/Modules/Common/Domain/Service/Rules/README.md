# Подсистема бизнес‑правил (Rules Engine)

## 📌 Назначение

Подсистема **Business Rules** служит для вынесения сложной и разнородной бизнес‑логики в независимые, переиспользуемые и тестируемые единицы — **правила (Rules)**.

Основные задачи:

- убрать громоздкие if/else из сервисов;
- отделить бизнес‑логику от инфраструктуры (ORM, DTO, HTTP);
- дать возможность легко наращивать функционал;
- применять **цепочки правил** (Rule Chain) для одной бизнес‑операции;
- облегчить покрытие логики unit‑тестами.

---

## 📂 Архитектура

### Основные элементы

| Компонент | Назначение                                                |
|----------|-----------------------------------------------------------|
| **RuleInterface** | Контракт для всех правил в системе                        |
| **RuleContext** | Универсальный контейнер данных, доступный для всех правил |
| **AbstractRule** | Базовый класс с полезными хелперами(если нужны)           |
| **Правила (Rules)** | Классы, реализующие конкретные бизнес‑ограничения         |
| **RuleChain** | Цепочка правил для одной операции / Регистрация доступных правил  |

---

## 🧠 Почему используется контекст

Разные правила требуют разный набор данных:

- тариф и пользователь,
- регион, юр/физ статус,
- текущие режимы услуг,
- финансовый период,
- тип операции и т. д.

Передавать жестко типизированные параметры невозможно — правила должны быть универсальными.

**RuleContext** решает эту задачу:

```php
$context = new RuleContext(
    $user,
    $finPeriod,
    $newTariff,
    $ipAddress
)
```

---

## 🧩 RuleInterface

```php
interface RuleInterface
{
    public function check(RuleContext $context): void;
}
```

### 📌 check()
Проверяет условие и бросает исключение `ImportantBusinessException`, если правило нарушено.

---

## 🧱 AbstractRule

Базовый класс облегчает создание правил:

- хелперы при необходимости,
- предоставляет доступ к логеру, логирование успешных/неуспешных правил при необходимости.

---

## 🔗 Цепочки правил (Rule Chains)

Одна бизнес‑операция может состоять из набора ограничений:

Например, смена тарифа:

- пользователь должен существовать;
- тариф должен быть доступен региону;
- тариф не должен совпадать с текущим;
- нет аренды устройств;
- нет условий, запрещающих этот тип тарифа.

Цепочка и вызов:

```php
$this->addServiceModeRuleChain->checkAll(new AddServiceModeContext(
    userId: $master->getId(),
    actionId: $webAction->getId(),
    finPeriod: $finPeriod,
    mode: $mode,
    jurStatus: $user->isJuridical(),
    region: $user->getRegion(),
    modeUnitCount: $optionsUserServModeDto->getCountUnits(),
));
```

---

## 🏗 Как добавлять новое правило

1. Создать класс в `src/Modules/Common/Domain/Rules/{Доменная область}`
2. Создать контекст и при необходимости(если такого типа переменной не существовало) ContextInterface в `src/Modules/Common/Domain/Rules/ContextInterface`

```php
interface HasActionId
{
    public function getActionId(): int;
}

class ModeAllowedForRegionContext implements HasActionId, HasProdServMode, HasRegion, HasUserId
{
    public function __construct(
        protected int $userId,
        protected int $actionId,
        protected ProdServMode $mode,
        protected Region $region,
    )
    {}

    public function getActionId(): int
    {
        return $this->actionId;
    }

    public function getMode(): ProdServMode
    {
        return $this->mode;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
```

Контекст интерфейс содержит только 1 метод получения указанной переменной.

3. Наследоваться от Rule и реализовать интерфейс RuleInterface. В методе мы описываем то, какие интерфейсы мы ожидаем от контекста
`(!$context instanceof HasActionId || !$context instanceof HasProdServMode)`. 
Также, именно тут проверяется бизнес логика. При отличии ожидаемого результата выбрасывается исключение.

```php
class ModeAllowedForRegionRule extends Rule
{

    public function __construct(protected ProdServModeRepository $prodServModeRepository){}

    public function check(object $context): void
    {

        if (!$context instanceof HasActionId || !$context instanceof HasProdServMode || !$context instanceof HasRegion || !$context instanceof HasUserId)
            throw new \LogicException('Wrong context passed to ModeAllowedForRegionRule');

        $groupsAndRegionIds = [
            1 => 'velikij_novgorod_tariffs',
            2 => 'cherepevets_tariffs',
            3 => 'chelyzbinsk_tariffs',
            4 => 'yaroslavl_tariffs'
        ];

        $mode = $context->getMode();
        $region = $context->getRegion();

        $isAvailable = $this->prodServModeRepository->isAvailableForRegionByCode($mode, $groupsAndRegionIds[$region->getId()]);

        if (!$isAvailable) {
            throw new ImportantBusinessException(
                $context->getUserId(),
                $context->getActionId(),
                "Режим {$mode->getName()}({$mode->getId()}) не доступен в регионе {$region->getName()}");
        }
    }
}
```

3. Как вызывать:

```php
$this->modeAllowedForRegionRule->check(
            new ModeAllowedForRegionContext(
                $master->getId(),
                $webAction->getId(),
                $mode,
                $user->getRegion()
            )
        );
```

---

## 🧱 Как создавать цепочки правил

```php
$this->addServiceModeRuleChain->checkAll(new AddServiceModeContext(
    userId: $master->getId(),
    actionId: $webAction->getId(),
    finPeriod: $finPeriod,
    mode: $mode,
    jurStatus: $user->isJuridical(),
    region: $user->getRegion(),
    modeUnitCount: $optionsUserServModeDto->getCountUnits(),
));
```

---

## ⚙️ Пример использования

---

## 🎯 Сильные стороны архитектуры

- ❗ правила изолированы и легко тестируются
- 🔄 порядок правил можно менять, включать, отключать в конфигурации
- 🧩 легко расширять список доступных правил
- 🔍 сервисы очищены от тяжелой логики
- 🛡 единая точка ошибок — исключение бизнес‑правила

---

## 📝 Итог

Подсистема бизнес‑правил:

- уменьшает связность сервисов,
- делает логику прозрачной,
- поддерживает сложные сценарии с десятками условий,
- облегчает тестирование,
- позволяет масштабировать систему без «разрастания» методов.

Она формирует **стабильный слой доменной логики**, независимый от API, БД и инфраструктуры — как и требует хороший DDD.
