# Billing Hub (Symfony, DDD-модульность)

Этот репозиторий содержит backend для **Личного кабинета** и связанных доменных операций (тарифы, услуги/режимы, заморозка, платежи и т.д.) на базе Symfony.
Код организован **модульно** (папка `src/Modules`) и использует набор доменных паттернов: репозитории + сущности (Doctrine), rule-chain для бизнес‑валидаций, orchestration (UseCase/Workflow) для многошаговых сценариев.

---

## Быстрый обзор

### Модули

```
src/Modules
├── Common        # ядро: доменные сущности, репозитории, правила, политики, инфраструктура
├── UserCabinet   # HTTP API ЛК (контроллеры v1), сервисы, use-case'ы ЛК, listener'ы
└── Console       # консольные команды и use-case'ы для CLI-операций
```

### Основные «строительные блоки»

- **Domain Entities**: `Common/Domain/Entity/*` — Doctrine‑сущности, часто отражающие legacy‑таблицы.
- **Repositories**: `Common/Domain/Repository/*` — доступ к данным (по одной репо на сущность/таблицу).
- **Rules / RuleChain**: `Common/Domain/Rules/*` — движок бизнес‑правил, собираемых в цепочки.  
  См. `Common/Domain/Rules/README.md`.
- **Workflows**: `Common/Domain/Workflow/*` — многошаговые сценарии (например, смена тарифа/режима).
- **UseCase (в модуле ЛК и Console)**:
  - `UserCabinet/UseCase/*` — сценарии, инициируемые HTTP.
  - `Console/UseCase/*` — сценарии, инициируемые CLI.
- **Infrastructure**: `Common/Infrastructure/*` — авторизация/сессия, логирование, исключения, трейты и т.п.
- **HTTP слой**: `UserCabinet/Controllers/APIv1/*` — REST‑контроллеры v1 + OpenAPI‑атрибуты.

---

## Как проходит запрос (в текущей архитектуре)

Типовая цепочка выглядит так:

`HTTP Controller` → `Service модуля (Lk*Service)` → `UseCase (UserCabinet/UseCase)` → `RuleChain` → `Workflow/Domain services` → `Repositories/Entities` → `Response`

Практически:
1. Контроллер получает `Request`, валидирует DTO (Symfony Validator) и берёт `uid` из `UserSessionService`.
2. Модульный сервис (`LkPaymentsService`, `LkFreezeService`, …) оркестрирует транзакции/логирование.
3. UseCase выполняет конкретный бизнес‑сценарий.
4. RuleChain проверяет ограничения (баланс, финпериод, статусы и т.п.).
5. Workflow выполняет многошаговую доменную операцию (создать web_action, поменять тариф, добавить режим, записать историю и т.д.).
6. Возвращается DTO/массив для API.

---

## Где что лежит

### UserCabinet (API)
- `Controllers/APIv1/*Controller.php` — endpoints (Login, Payments, Freeze, Tariff, ServService, UserProfile).
- `EventListener/*` — слушатели для авторизации/обработки ошибок/формирования ответа:
  - `AuthListener.php` использует паттерн `Controller::authenticate(): bool`  
    (у контроллеров, где нужна авторизация, метод переопределяется и возвращает `true`).
- `Service/Lk*Service.php` — прикладные сервисы ЛК.
- `UseCase/*` — сценарии ЛК (freeze/break/tariff/service mode).

### Common (ядро)
- `Domain/Entity/*` и `Domain/Repository/*` — модель данных и доступ к ней.
- `Domain/Rules/*` — бизнес‑валидации (см. README внутри Rules).
- `Domain/Workflow/*` — оркестрация доменных операций (сейчас: тарифы и режимы).
- `Domain/Model/*` — «модели/проекции» (например, ContractModel/CustomerModel), которые не всегда = Doctrine Entity.
- `Domain/Aggregates/*` — составные объекты (сейчас это скорее «composite DTO», см. раздел рекомендаций).
- `Infrastructure/*` — логер, auth/session, исключения и т.п.

### Console
- `Console/Command/*` — команды Symfony Console (в т.ч. тестовые).
- `Console/UseCase/*` — use-case'ы CLI.

---

## Процесс добавления нового функционала

Ниже — **рекомендуемый** и максимально повторяемый процесс, который соответствует текущему стилю кода.

### Шаги

1. **Определить сценарий**
   - Это новый endpoint? Новая команда? Фоновая операция?
   - Что является входом (DTO/Request) и выходом (DTO/Response)?

2. **Сформировать UseCase**
   - Создать класс в `UserCabinet/UseCase/<Domain>/...UseCase.php` (или `Console/UseCase/...`).
   - Вынести туда «сценарий»: проверки → вызовы workflow/domain services → формирование результата.

3. **Добавить/расширить RuleChain**
   - Если есть бизнес‑ограничения — добавьте правило в `Common/Domain/Rules/Definitions/...`.
   - Подключите его в нужную цепочку в `Common/Domain/Rules/Chains/...`.
   - Используйте `SOFT/HARD` режимы осознанно (SOFT — вернуть fail, HARD — бизнес‑исключение).

4. **Если операция многошаговая — оформить orchestration**
   - Если это **чистая доменная операция** (несколько репозиториев, истории, web_action, логирование) —
     либо добавьте/расширьте `Workflow` в `Common/Domain/Workflow/...`,
     либо (рекомендуется) создайте `ApplicationService`/`UseCase` в Common (см. рекомендации ниже).

5. **Добавить endpoint в Controller**
   - Создать/расширить `UserCabinet/Controllers/APIv1/*Controller.php`.
   - Добавить OpenAPI атрибуты, валидировать вход через DTO и `Controller::validate()`.

6. **Добавить DTO**
   - Request DTO: `UserCabinet/Service/Dto/Request/*` (или по текущей структуре, если уже есть).
   - Response DTO: `UserCabinet/Service/Dto/Response/*`.

7. **Тесты**
   - Минимум: функциональный тест на endpoint + happy path.
   - Отдельно: тесты на rule‑цепочки (если есть сложная валидация).

---

## Пример: добавляем endpoint “История платежей”

Допустим, хотим добавить `GET /api/v1/payments/history?limit=20&offset=0`.

### 1) DTO фильтра
Используем уже существующий `FilterDto` (в проекте он встречается как `Common/Domain/Service/Dto/Request/FilterDto`),
или создаём свой `PaymentsHistoryFilterDto` (если нужно специализированное поведение).

### 2) UseCase
Создаём `src/Modules/UserCabinet/UseCase/Payments/GetPaymentsHistoryUseCase.php`:

- вход: `uid`, `limit`, `offset`
- действия: репозиторий пополнений/списаний → маппинг в DTO → возврат

### 3) Controller
В `PaymentsController` добавляем метод:

- проверка авторизации (контроллер уже `authenticate(): true`)
- чтение query params → DTO → валидация → вызов `LkPaymentsService->getHistory($uid, $filterDto)`  
  или напрямую use-case (лучше: через сервис модуля, чтобы централизовать транзакции/логирование)

### 4) Сервис модуля
В `LkPaymentsService` добавляем метод `getHistory(...)` и делегируем в use-case.

### 5) (Опционально) правила
Если есть ограничения (например, лимит не более 100) — добавляем правило `LimitMustBeInRangeRule`,
и цепочку `GetPaymentsHistoryRuleChain`.

---

## Рекомендации по доработке и “что лишнее”

Ниже — наблюдения **по фактическому коду в `src/Modules`** и предложения, которые упростят поддержку.

### 1) Workflow: оставить или заменить?

**Сейчас** `Common/Domain/Workflow/*` — это, по сути, **Application Service / UseCase**, потому что:
- workflow знает про репозитории, логирование, иногда про сессию (`UserSessionService`);
- orchestrates много шагов и side‑effects;
- часто это не «domain object», а сценарий.

✅ Когда workflow оправдан:
- реально многошаговый процесс (несколько сущностей/таблиц, история, web_action, логирование, транзакции);
- это переиспользуемая оркестрация для разных входов (API, консоль, админка).

⚠️ Когда workflow мешает:
- workflow становится «god class»;
- внутри появляются привязки к HTTP/сессии (лучше передавать `actorId`/`userId` параметром, а не тянуть сессию).

**Рекомендация**:
- либо **переименовать** `Workflow` → `ApplicationService` (или перенести в `Common/Application/*`),
- либо оставить `Workflow`, но:
  - убрать зависимости на `UserSessionService` из Common (передавать явно),
  - стандартизировать сигнатуры (input DTO → output DTO),
  - держать domain services/правила отдельно.

### 2) Aggregates: сейчас это “composite DTO”, а не DDD-агрегаты

`Common/Domain/Aggregates/*` (например `ContractAggregate`) — это просто контейнер из `ContractModel + CustomerModel`.
Это **не aggregate root**, там нет инвариантов/поведения, только данные.

Варианты:
- ✅ Если это read‑model/композиция для удобства — переименовать в `ReadModel`/`View`/`Composite`.
- ✅ Если хотите DDD‑агрегаты — сделать их **root** с поведением и границами консистентности
  (и тогда репозитории должны работать с root, а не с кучей сущностей отдельно).

### 3) Entity vs Model: важно зафиксировать правила

В `Common/Domain/Entity/*` много сущностей (legacy‑таблицы), а в `Common/Domain/Model/*` есть отдельные модели.
Это нормально, но важно закрепить:
- **Entity** — Doctrine, хранится в БД.
- **Model** — проекция/композиция/бизнес‑модель, не обязана быть Doctrine‑сущностью.

**Рекомендация**: добавить раздел в README “Что такое Entity, Model, DTO и где они живут”, чтобы новые разработчики не путались.

### 4) Проблема “частично отсутствующего слоя Application”

В коде встречаются импорты вида `App\Modules\Common\Application\UseCase\...`, но соответствующих классов в `src/Modules/Common` нет.
Это сигнал одного из двух:
- слой **планировался**, но не перенесён в репозиторий;
- часть кода устарела/рефакторинг не завершён.

**Рекомендация**:
- либо добавить `src/Modules/Common/Application/UseCase/...`,
- либо убрать/заменить импорты на фактические реализации (иначе будет путаница и автолоад ошибки).

### 5) Авторизация через `Controller::authenticate()`

Паттерн рабочий и удобный, но стоит:
- документировать (что именно проверяется в `AuthListener`, какие заголовки/куки ожидаются),
- сделать единый базовый контроллер v1 и явно указывать `authenticate(): true` для защищённых контроллеров.

### 6) Репозитории “по таблице” и уровень абстракции

Сейчас репо много и они, вероятно, тонкие (CRUD + 1‑2 запроса).
Это нормально для legacy‑DB, но важно следить:
- чтобы бизнес‑логика не расползалась в репозитории;
- чтобы в use-case/workflow не превращалось в “скрипт из 20 репозиториев”.

**Рекомендация**:
- для сложных выборок добавить Query‑объекты / Read‑repositories,
- а для доменных операций — сервисы с чёткими методами (например `TariffService::changeNextTariff(...)` уже есть).

---

## Соглашения по неймингу и стилю

- `Lk*Service` — сервисы модуля ЛК (boundary для контроллеров).
- `*UseCase` — 1 сценарий = 1 класс.
- `Rule` — одно атомарное правило.
- `RuleChain` — список правил для сценария.
- `Workflow` (если остаётся) — оркестрация side‑effects, но без привязки к HTTP/Session.

---

## Навигация по коду

- Правила: `src/Modules/Common/Domain/Rules/README.md`
- Контроллеры v1: `src/Modules/UserCabinet/Controllers/APIv1/`
- UseCase ЛК: `src/Modules/UserCabinet/UseCase/`
- Workflows: `src/Modules/Common/Domain/Workflow/`

---

## TODO (рекомендуемый backlog)

1. Добавить/восстановить слой `Common/Application/*` или привести импорты к текущей структуре.
2. Решить судьбу `Workflow` (оставить как есть / переименовать / перенести).
3. Переименовать `Aggregates` → `ReadModels` (или сделать настоящие aggregate roots).
4. Описать контракт авторизации (что именно ожидает `AuthListener`).
5. Добавить минимум функциональных тестов на ключевые сценарии (freeze/tariff/service-mode).
