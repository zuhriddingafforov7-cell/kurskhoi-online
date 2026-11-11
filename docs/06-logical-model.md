# Лабораторияи №6. Модели мантиқии пойгоҳи додаҳо — Biyomuz Academy

**Лоиҳа:** Biyomuz Academy — «Коркарди веблоиҳаи платформаи курсҳои онлайн».
**Пойгоҳи додаҳо:** `kurskhoi_online` (MariaDB 10.4 / MySQL).
**Файлҳои ҳамроҳ:** `06-er-diagram.puml`, `06-er-diagram.png`.

> **Эзоҳ дар бораи эътимоднокӣ.** Ин ҳуҷҷат аз рӯйи талаботи омӯзгор ва талаботи мавзӯи кори курсӣ таҳия шудааст. Мигратсияҳои воқеии Laravel, `docs/05-structure/class.puml` ва ҷадвалҳои MariaDB ҳангоми таҳияи ин нусха **санҷида нашудаанд** (дар ин муҳит дастрас набуданд). Бандҳои мувофиқ бо рамзи ⚠ қайд шудаанд; онҳоро пеш аз супоридан бояд дар лоиҳаи маҳаллӣ санҷид (боби 11).

---

## 1. Мақсад ва тавсифи модели мантиқӣ

**Мақсад** — сохтани модели мантиқии додаҳо: ER-диаграмма бо калидҳои ибтидоӣ (PK) ва хориҷӣ (FK), навъҳои муносибатҳо, ҳатмӣ будани атрибутҳо ва нормализатсия то 3НФ.

| Сатҳ | Савол | Натиҷа | Аз СУБД вобаста? |
|---|---|---|---|
| Консептуалӣ | Кадом мафҳумҳо ва алоқаҳо ҳастанд? | Диаграммаи синфҳо (Lab 5) | Не |
| **Мантиқӣ** | Кадом ҷадвал, сутун ва калидҳо лозиманд? | ER-диаграмма, ҷадвалҳои нормализатсияшуда (Lab 6) | Аз навъи СУБД (реляционӣ), на аз маҳсулот |
| Физикӣ | Дар MariaDB/MySQL чӣ гуна нигоҳ дошта мешавад? | Навъҳо, индексҳо, DDL/мигратсия (Lab 7) | Бале |

Қоидаҳои гузариш:

- **Entity → Table** (сущность → ҷадвал), **Attribute → Column**, **Instance → Row**.
- **PK** — сутуни суррогатии `id`, ки сатрро якмаъно муайян мекунад. **FK** — сутуне, ки ба PK-и ҷадвали дигар ишора мекунад (ё `NULL`, агар алоқа ихтиёрӣ бошад).
- **Class Diagram → ER:** синф → ҷадвал (шакли ҷамъ, `snake_case`), хусусият → сутун, алоқаи 1:N → FK дар тарафи «бисёр», алоқаи M:N → ҷадвали миёнарав, enum → ENUM-и сутун.

### 1.1. Мутобиқати синфҳо ва ҷадвалҳо

| Синф (Lab 5) | Ҷадвали модели мантиқӣ | Вазифаи мувофиқ дар рӯйхати омӯзгор |
|---|---|---|
| User | `users` | `users` |
| Category | `categories` | (ҷадвали иловагӣ) |
| Course | `courses` | `courses` |
| Module | `modules` | `modules` |
| Lesson | `lessons` | `lessons` |
| Enrollment | `enrollments` | `enrollments` |
| Assignment | `assignments` | `assignments` |
| AssignmentSubmission | `assignment_submissions` | **`submissions`** |
| LessonProgress | `lesson_progress` | **`progress`** |
| Certificate | `certificates` | (ҷадвали иловагӣ) |

**Номувофиқии номҳо (ошкоро):** омӯзгор дар талаботи умумӣ `submissions` ва `progress`-ро овардааст. Дар Biyomuz Academy номҳои мукаммалтар истифода мешаванд, зеро онҳо ба синфҳои `AssignmentSubmission` ва `LessonProgress`-и Lab 5 мувофиқанд (қоидаи Class → Table). Яъне **`assignment_submissions` вазифаи `submissions`-ро** ва **`lesson_progress` вазифаи `progress`-ро** иҷро мекунад. Ҷадвалҳои хизматии Laravel (`cache`, `jobs`, `sessions`, `migrations`, `password_reset_tokens`) ба 10 ҷадвали соҳавӣ дохил намешаванд.

---

## 2. Ҷадвали ғайримеъёрии (UNF)

Ҷадвали `student_courses_flat` — ҳисоботи Excel, ки маъмурият ба таври дастӣ нигоҳ медорад (маълумот сохта аст, ба базаи воқеӣ тааллуқ надорад). **10 сутун, 3 сатр.**

| student_id | student_name | contacts | course_ids | course_titles | category_names | author_names | author_emails | enrolled_dates | enrollment_statuses |
|---|---|---|---|---|---|---|---|---|---|
| 101 | Фарид Каримов | farid@example.test, 900000001 | 1, 2 | PHP барои навомӯзон, Laravel: асосҳо | Барномасозӣ, Барномасозӣ | Аҳмад Исмоилов, Аҳмад Исмоилов | ahmad@example.test, ahmad@example.test | 2026-09-01, 2026-09-05 | active, active |
| 102 | Мадина Саидова | madina@example.test | 3 | HTML ва CSS | Веб-дизайн | Баҳром Назаров | bahrom@example.test | 2026-09-02 | completed |
| 103 | Шаҳбоз Раҳимов | shahboz@example.test, 900000003, 900000004 | 2, 4 | Laravel: асосҳо, MySQL барои ҳама | Барномасозӣ, Пойгоҳи додаҳо | Аҳмад Исмоилов, Баҳром Назаров | ahmad@example.test, bahrom@example.test | 2026-09-03, 2026-09-07 | active, active |

Идентификаторҳо: `course_id` 1 — «PHP барои навомӯзон» (категория 1 «Барномасозӣ», муаллиф 21 Аҳмад Исмоилов); 2 — «Laravel: асосҳо» (категория 1, муаллиф 21); 3 — «HTML ва CSS» (категория 2 «Веб-дизайн», муаллиф 22 Баҳром Назаров); 4 — «MySQL барои ҳама» (категория 3 «Пойгоҳи додаҳо», муаллиф 22).

**Мушкилоти ин ҷадвал:** дар як ячейка рӯйхати арзишҳо (курсҳо, телефонҳо, санаҳо); маълумоти курс, категория ва муаллиф дар ҳар сатр такрор мешавад (зиёдатии маълумот); ном ва почтаи муаллиф дар як ҷо ба шунаванда пайваст шудааст.

---

## 3. Нормализатсия

### 3.1. 1НФ

**Таъриф.** Ҷадвал дар 1НФ аст, агар ҳар ячейка як арзиши атомарӣ дорад, гурӯҳҳои такроршаванда нест ва PK мавҷуд аст.

**Вайроншавӣ:** (1) `contacts`, `course_ids`, `course_titles`, `category_names`, `author_names`, `author_emails`, `enrolled_dates`, `enrollment_statuses` — рӯйхат дар як ячейка; (2) `contacts` якҷоя почта ва телефонро дорад; (3) PK набуд.

**Ислоҳ:** ҳар номнависӣ дар сатри алоҳида; `contacts` ба `student_email` ва `student_phone` ҷудо шуд; ҳар сутун як арзиш дорад. Барои шунаванда ёфтани як телефони асосӣ кифоя дониста шуд (шунавандаи 103 ду телефон дошт, дуюмаш партофта шуд — лоиҳа танҳо як тамос талаб мекунад).

**Вобастагиҳои функсионалӣ (1НФ):** калиди таркибӣ `(student_id, course_id)`.

**Ҷадвали натиҷа (1НФ):** `student_courses_1nf`

| student_id | student_name | student_email | student_phone | course_id | course_title | category_id | category_name | author_id | author_name | author_email | enrolled_at | enrollment_status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|
| 101 | Фарид Каримов | farid@example.test | 900000001 | 1 | PHP барои навомӯзон | 1 | Барномасозӣ | 21 | Аҳмад Исмоилов | ahmad@example.test | 2026-09-01 | active |
| 101 | Фарид Каримов | farid@example.test | 900000001 | 2 | Laravel: асосҳо | 1 | Барномасозӣ | 21 | Аҳмад Исмоилов | ahmad@example.test | 2026-09-05 | active |
| 102 | Мадина Саидова | madina@example.test | NULL | 3 | HTML ва CSS | 2 | Веб-дизайн | 22 | Баҳром Назаров | bahrom@example.test | 2026-09-02 | completed |
| 103 | Шаҳбоз Раҳимов | shahboz@example.test | 900000003 | 2 | Laravel: асосҳо | 1 | Барномасозӣ | 21 | Аҳмад Исмоилов | ahmad@example.test | 2026-09-03 | active |
| 103 | Шаҳбоз Раҳимов | shahboz@example.test | 900000003 | 4 | MySQL барои ҳама | 3 | Пойгоҳи додаҳо | 22 | Баҳром Назаров | bahrom@example.test | 2026-09-07 | active |

Мушкили боқимонда: маълумоти шунаванда дар ҳар сатр, маълумоти курс дар ҳар сатр такрор мешавад.

### 3.2. 2НФ

**Таъриф.** 2НФ = 1НФ + ҳар атрибути ғайрикалидӣ аз **тамоми** калид вобаста аст, на аз қисми он (вайронкунӣ танҳо дар калиди таркибӣ имконпазир аст).

**Вобастагиҳои функсионалӣ:**

- `student_id → student_name, student_email, student_phone` — **вобастагии қисмӣ**
- `course_id → course_title, category_id, category_name, author_id, author_name, author_email` — **вобастагии қисмӣ**
- `(student_id, course_id) → enrolled_at, enrollment_status` — вобастагии пурра

**Вайроншавӣ:** вобастагиҳои қисмӣ боис мешаванд, ки номи шунавандаи 101 ду бор, маълумоти курси 2 ду бор нигоҳ дошта шавад.

**Ислоҳ:** се ҷадвал:

`students_2nf (student_id PK, student_name, student_email, student_phone)`

| student_id | student_name | student_email | student_phone |
|---|---|---|---|
| 101 | Фарид Каримов | farid@example.test | 900000001 |
| 102 | Мадина Саидова | madina@example.test | NULL |
| 103 | Шаҳбоз Раҳимов | shahboz@example.test | 900000003 |

`courses_2nf (course_id PK, course_title, category_id, category_name, author_id, author_name, author_email)`

| course_id | course_title | category_id | category_name | author_id | author_name | author_email |
|---|---|---|---|---|---|---|
| 1 | PHP барои навомӯзон | 1 | Барномасозӣ | 21 | Аҳмад Исмоилов | ahmad@example.test |
| 2 | Laravel: асосҳо | 1 | Барномасозӣ | 21 | Аҳмад Исмоилов | ahmad@example.test |
| 3 | HTML ва CSS | 2 | Веб-дизайн | 22 | Баҳром Назаров | bahrom@example.test |
| 4 | MySQL барои ҳама | 3 | Пойгоҳи додаҳо | 22 | Баҳром Назаров | bahrom@example.test |

`enrollments_2nf (student_id, course_id, enrolled_at, enrollment_status; PK (student_id, course_id))`

| student_id | course_id | enrolled_at | enrollment_status |
|---|---|---|---|
| 101 | 1 | 2026-09-01 | active |
| 101 | 2 | 2026-09-05 | active |
| 102 | 3 | 2026-09-02 | completed |
| 103 | 2 | 2026-09-03 | active |
| 103 | 4 | 2026-09-07 | active |

**Калиди таркибӣ ва суррогатӣ.** Дар модели ниҳоӣ `enrollments` PK-и суррогатии `id` дорад (ба Laravel мувофиқ; `certificates.enrollment_id` ба як сатр ишора мекунад). Калиди таркибии `(student_id, course_id)` аз байн намеравад — ҳамчун маҳдудияти **`UNIQUE(user_id, course_id)`** нигоҳ дошта мешавад ва номнависии такрориро пешгирӣ мекунад.

### 3.3. 3НФ

**Таъриф.** 3НФ = 2НФ + ягон атрибути ғайрикалидӣ аз атрибути ғайрикалидии дигар вобаста нест (вобастагии транзитивӣ нест). «Ҳар атрибут аз калид, аз тамоми калид ва аз ҳеҷ чиз ғайр аз калид».

**Вобастагиҳои транзитивӣ дар `courses_2nf`:**

1. `course_id → category_id → category_name` — номи категория аз категория вобаста аст, на аз курс.
2. `course_id → author_id → author_name, author_email` — маълумоти муаллиф аз муаллиф вобаста аст, на аз курс.

(Дар `students_2nf` вобастагии транзитивӣ нест.)

**Ислоҳ:**

- `categories (id PK, name, slug)` — категорияҳо;
- `users (id PK, name, email, role, …)` — шунавандагон **ва** муаллифон дар як ҷадвал, бо сутуни `role` (ҳам шунаванда, ҳам муаллиф — як мафҳуми «корбар»);
- `courses (id PK, author_id FK, category_id FK, title, …)` — танҳо ишора ба муаллиф ва категория;
- `enrollments (id PK, user_id FK, course_id FK, …)` — номнависӣ.

**Се аномалия дар ҷадвали ибтидоӣ ва ҳалли онҳо:**

| Аномалия | Мисол дар `student_courses_flat` | Дар модели 3НФ |
|---|---|---|
| Воридкунӣ | Категорияи нави «Дизайн» ё муаллифи нав сохта шуд, аммо курс ва шунаванда ҳанӯз нест — ҷойи сабт нест (сатр = номнависӣ). | `INSERT INTO categories …` — мустақил. |
| Навсозӣ | Номи категорияи «Барномасозӣ» дар ҳар сатри ҳамаи шунавандагон тағйир дода мешавад; агар як сатр фаромӯш шавад, ду категорияи гуногун пайдо мешавад. | `UPDATE categories SET name … WHERE id = 1` — як сатр. |
| Ҳазф | Шунавандаи 102 ягона шунавандаи курси 3 аст. Агар сатраш ҳазф шавад, маълумот дар бораи курси «HTML ва CSS», категория ва муаллифи он гум мешавад. | Ҳазфи `enrollments` курс ва муаллифро нест намекунад. |

**Далел, ки модели ниҳоӣ дар 3НФ аст.** Дар ҳар як аз 10 ҷадвал PK суррогатии содда (`id`) аст, бинобар ин вобастагии қисмӣ ғайриимкон аст (2НФ). Ҳар сутуни ғайрикалидӣ мустақиман аз `id`-и ҷадвали худ вобаста аст: номи категория танҳо дар `categories`, маълумоти муаллиф танҳо дар `users`, маълумоти курс танҳо дар `courses`; дигар ҷадвалҳо танҳо FK доранд (3НФ). Арзишҳои ҳисобшаванда (фоизи пешрафт, шумораи дарсҳо) нигоҳ дошта намешаванд. Калидҳои номзади иловагӣ (`users.email`, `courses.slug`, `certificates.certificate_code`) дар ҷадвали худ ҳастанд ва ба сутунҳои ғайрикалидӣ вобастагии транзитивӣ намедиҳанд.

**Эзоҳ дар бораи `student_phone`:** телефон дар 1НФ/2НФ барои пурра нишон додани таҳлил истифода шуд, аммо ба модели ниҳоӣ **дохил нашудааст** — синфи `User`-и Lab 5 ва талаботи лоиҳа онро талаб намекунанд ⚠ (санҷидани `class.puml`). Агар лозим шавад, сутуни `users.phone VARCHAR(20) NULL` илова мешавад.

---

## 4. Тавсифи ҳамаи 10 ҷадвал

> ⚠ Номҳо ва навъҳои сутунҳо аз рӯйи талаботи лоиҳа ва қоидаҳои Laravel/MariaDB интихоб шудаанд; бо мигратсияҳои воқеӣ санҷида нашудаанд. `BIGINT UNSIGNED` — навъи `id()` ва `foreignId()`-и Laravel.

### 4.1. users — корбарон (шунаванда, муаллиф, маъмур)

| Атрибут | Тип | Калид | NULL | Тавсиф |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | PK | нест | идентификатори корбар |
| name | VARCHAR(255) | | нест | ному насаб |
| email | VARCHAR(255) | UNIQUE | нест | почта (логин) |
| email_verified_at | TIMESTAMP | | ҳаст | санаи тасдиқи почта |
| password | VARCHAR(255) | | нест | ҳеши парол |
| role | ENUM('student','author','admin') | | нест | нақш; пешфарз `student` |
| remember_token | VARCHAR(100) | | ҳаст | токени «маро дар ёд дор» |
| created_at | TIMESTAMP | | ҳаст | санаи сохтан |
| updated_at | TIMESTAMP | | ҳаст | санаи навсозӣ |

### 4.2. categories — категорияҳои курс

| Атрибут | Тип | Калид | NULL | Тавсиф |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | PK | нест | идентификатор |
| name | VARCHAR(100) | | нест | номи категория |
| slug | VARCHAR(120) | UNIQUE | нест | номи ягонаи URL |
| description | TEXT | | ҳаст | тавсиф |
| created_at | TIMESTAMP | | ҳаст | санаи сохтан |
| updated_at | TIMESTAMP | | ҳаст | санаи навсозӣ |

### 4.3. courses — курсҳо

| Атрибут | Тип | Калид | NULL | Тавсиф |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | PK | нест | идентификатор |
| author_id | BIGINT UNSIGNED | FK → users.id | нест | муаллифи курс |
| category_id | BIGINT UNSIGNED | FK → categories.id | ҳаст | категория (метавонад холӣ бошад) |
| title | VARCHAR(200) | | нест | сарлавҳа |
| slug | VARCHAR(220) | UNIQUE | нест | номи ягонаи URL |
| description | TEXT | | нест | тавсифи курс |
| thumbnail_url | VARCHAR(255) | | ҳаст | муқова (роҳ ё URL) |
| status | ENUM('draft','published','archived') | | нест | ҳолат; пешфарз `draft` |
| created_at | TIMESTAMP | | ҳаст | санаи сохтан |
| updated_at | TIMESTAMP | | ҳаст | санаи навсозӣ |

### 4.4. modules — модулҳои курс

| Атрибут | Тип | Калид | NULL | Тавсиф |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | PK | нест | идентификатор |
| course_id | BIGINT UNSIGNED | FK → courses.id | нест | курси модул |
| title | VARCHAR(200) | | нест | сарлавҳа |
| description | TEXT | | ҳаст | тавсиф |
| order_index | INT UNSIGNED | | нест | тартиби модул дар курс (≥ 1) |
| created_at | TIMESTAMP | | ҳаст | санаи сохтан |
| updated_at | TIMESTAMP | | ҳаст | санаи навсозӣ |

### 4.5. lessons — дарсҳо

| Атрибут | Тип | Калид | NULL | Тавсиф |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | PK | нест | идентификатор |
| module_id | BIGINT UNSIGNED | FK → modules.id | нест | модули дарс |
| title | VARCHAR(200) | | нест | сарлавҳа |
| content_type | ENUM('text','video','file') | | нест | намуди мундариҷа |
| body_text | LONGTEXT | | ҳаст | матни дарс (барои `text`) |
| video_url | VARCHAR(255) | | ҳаст | пайванди видео (барои `video`) |
| file_path | VARCHAR(255) | | ҳаст | роҳи файл (барои `file`) |
| order_index | INT UNSIGNED | | нест | тартиби дарс дар модул (≥ 1) |
| created_at | TIMESTAMP | | ҳаст | санаи сохтан |
| updated_at | TIMESTAMP | | ҳаст | санаи навсозӣ |

### 4.6. enrollments — номнависӣ ба курс

| Атрибут | Тип | Калид | NULL | Тавсиф |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | PK | нест | идентификатор |
| user_id | BIGINT UNSIGNED | FK → users.id | нест | шунаванда |
| course_id | BIGINT UNSIGNED | FK → courses.id | нест | курс |
| enrolled_at | DATETIME | | нест | санаи номнависӣ |
| status | ENUM('active','completed','cancelled') | | нест | ҳолат; пешфарз `active` |
| completed_at | DATETIME | | ҳаст | санаи хатм (холӣ то хатм) |
| created_at | TIMESTAMP | | ҳаст | санаи сохтан |
| updated_at | TIMESTAMP | | ҳаст | санаи навсозӣ |

Илова: `UNIQUE(user_id, course_id)`.

### 4.7. assignments — супоришҳо

| Атрибут | Тип | Калид | NULL | Тавсиф |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | PK | нест | идентификатор |
| lesson_id | BIGINT UNSIGNED | FK → lessons.id, UNIQUE | нест | дарс (ҳар дарс ≤ 1 супориш) |
| title | VARCHAR(200) | | нест | сарлавҳа |
| description | TEXT | | ҳаст | шарти супориш |
| due_date | DATETIME | | ҳаст | мӯҳлат |
| max_score | SMALLINT UNSIGNED | | нест | баҳои ҳадди аксар (1–100) |
| created_at | TIMESTAMP | | ҳаст | санаи сохтан |
| updated_at | TIMESTAMP | | ҳаст | санаи навсозӣ |

### 4.7a. assignment_submissions — ҷавобҳо ба супориш (вазифаи `submissions`)

| Атрибут | Тип | Калид | NULL | Тавсиф |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | PK | нест | идентификатор |
| assignment_id | BIGINT UNSIGNED | FK → assignments.id | нест | супориш |
| user_id | BIGINT UNSIGNED | FK → users.id | нест | шунавандаи супоранда |
| submission_text | TEXT | | ҳаст | матни ҷавоб |
| file_path | VARCHAR(255) | | ҳаст | файли боргузоришуда |
| score | SMALLINT UNSIGNED | | ҳаст | баҳо (0–100; холӣ то санҷиш) |
| feedback | TEXT | | ҳаст | шарҳи муаллиф |
| status | ENUM('submitted','graded','returned') | | нест | ҳолат; пешфарз `submitted` |
| created_at | TIMESTAMP | | ҳаст | санаи боргузорӣ |
| updated_at | TIMESTAMP | | ҳаст | санаи навсозӣ |

Илова: `UNIQUE(assignment_id, user_id)` — як ҷавоб барои як супориш; такроран супоридан сатри ҳамонро навсоз мекунад (`returned` → `submitted`).

### 4.8. certificates — сертификатҳо

| Атрибут | Тип | Калид | NULL | Тавсиф |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | PK | нест | идентификатор |
| enrollment_id | BIGINT UNSIGNED | FK → enrollments.id, UNIQUE | нест | номнависии хатмшуда |
| certificate_code | VARCHAR(50) | UNIQUE | нест | рақами ягонаи сертификат |
| issue_date | DATE | | нест | санаи додан |
| pdf_path | VARCHAR(255) | | нест | роҳи файли PDF |
| created_at | TIMESTAMP | | ҳаст | санаи сохтан |
| updated_at | TIMESTAMP | | ҳаст | санаи навсозӣ |

### 4.9. lesson_progress — пешрафти дарсҳо (вазифаи `progress`)

| Атрибут | Тип | Калид | NULL | Тавсиф |
|---|---|---|---|---|
| id | BIGINT UNSIGNED | PK | нест | идентификатор |
| user_id | BIGINT UNSIGNED | FK → users.id | нест | шунаванда |
| lesson_id | BIGINT UNSIGNED | FK → lessons.id | нест | дарс |
| is_completed | BOOLEAN (TINYINT(1)) | | нест | дарс анҷом ёфтааст; пешфарз 0 |
| completed_at | DATETIME | | ҳаст | санаи анҷом |
| created_at | TIMESTAMP | | ҳаст | санаи сохтан |
| updated_at | TIMESTAMP | | ҳаст | санаи навсозӣ |

Илова: `UNIQUE(user_id, lesson_id)`.

---

## 5. Ҷадвали ҷамъбастии 10 ҷадвал

| Ҷадвал | Вазифа | PK | FK | UNIQUE |
|---|---|---|---|---|
| users | корбарон ва нақшҳо | id | — | email |
| categories | категорияҳо | id | — | slug |
| courses | курсҳо | id | author_id, category_id | slug |
| modules | модулҳои курс | id | course_id | — |
| lessons | дарсҳо | id | module_id | — |
| enrollments | номнависӣ (ҷадвали миёнарав users↔courses) | id | user_id, course_id | (user_id, course_id) |
| assignments | супоришҳо | id | lesson_id | lesson_id |
| assignment_submissions | ҷавобҳо ва баҳо | id | assignment_id, user_id | (assignment_id, user_id) |
| certificates | сертификатҳои PDF | id | enrollment_id | enrollment_id, certificate_code |
| lesson_progress | анҷоми дарсҳо | id | user_id, lesson_id | (user_id, lesson_id) |

**Ҷамъ: 10 PK, 12 FK, 9 UNIQUE, 4 CHECK.**

---

## 6. Муносибатҳои байни ҷадвалҳо

| Ҷадвалҳои алоқаманд | Навъ | FK | Ҳатмӣ/ихтиёрӣ | Тавсиф |
|---|---|---|---|---|
| users — courses | 1:N | courses.author_id | ҳатмӣ | муаллиф якчанд курс дорад |
| categories — courses | 1:N | courses.category_id | ихтиёрӣ (NULL) | категория якчанд курс дорад |
| courses — modules | 1:N | modules.course_id | ҳатмӣ | курс якчанд модул дорад |
| modules — lessons | 1:N | lessons.module_id | ҳатмӣ | модул якчанд дарс дорад |
| users — enrollments | 1:N | enrollments.user_id | ҳатмӣ | шунаванда ба якчанд курс сабт мешавад |
| courses — enrollments | 1:N | enrollments.course_id | ҳатмӣ | ба курс якчанд шунаванда сабт мешавад |
| lessons — assignments | 1:1 (ихтиёрӣ) | assignments.lesson_id (UNIQUE) | супориш ҳатман дарс дорад; дарс супориш надошта метавонад | `lessons ||--o| assignments` |
| assignments — assignment_submissions | 1:N | assignment_submissions.assignment_id | ҳатмӣ | як супориш якчанд ҷавоб (аз шунавандагони гуногун) |
| users — assignment_submissions | 1:N | assignment_submissions.user_id | ҳатмӣ | шунаванда якчанд супориш месупорад |
| users — lesson_progress | 1:N | lesson_progress.user_id | ҳатмӣ | пешрафти ҳар корбар |
| lessons — lesson_progress | 1:N | lesson_progress.lesson_id | ҳатмӣ | дарс барои якчанд корбар анҷом мешавад |
| enrollments — certificates | 1:1 (ихтиёрӣ) | certificates.enrollment_id (UNIQUE) | сертификат ҳатман номнависӣ дорад; номнависӣ сертификат надошта метавонад | `enrollments ||--o| certificates` |

**M:N.** Муносибати «шунаванда ↔ курс» мустақим нақш нашудааст, балки тавассути ҷадвали миёнарав `enrollments`: `users 1:N enrollments` ва `courses 1:N enrollments`, яъне `users M:N courses`. Ба ҳамин монанд `lesson_progress` (users M:N lessons) ва `assignment_submissions` (users M:N assignments) ҷадвалҳои миёнараванд, ки атрибутҳои худро доранд.

**Шумора:** 12 алоқа — 10 алоқаи 1:N, 2 алоқаи 1:1 (ихтиёрӣ), 1 M:N-и асосӣ тавассути `enrollments`.

---

## 7. UNIQUE ва CHECK

### UNIQUE (9)

| № | Маҳдудият | Сабаб |
|---|---|---|
| 1 | `users.email` | як почта — як ҳисоб |
| 2 | `categories.slug` | URL-и ягона |
| 3 | `courses.slug` | URL-и ягона |
| 4 | `enrollments(user_id, course_id)` | номнависии такрорӣ мумкин нест |
| 5 | `assignments.lesson_id` | ҳар дарс ≤ 1 супориш (1:1) |
| 6 | `certificates.enrollment_id` | як номнависӣ — як сертификат |
| 7 | `certificates.certificate_code` | рақами ягонаи сертификат |
| 8 | `lesson_progress(user_id, lesson_id)` | як сабт барои як дарс ва корбар |
| 9 | `assignment_submissions(assignment_id, user_id)` | як сабти ҷавоб барои як супориш ва шунаванда |

### CHECK (4)

| № | Ифода | Сутун |
|---|---|---|
| 1 | `score IS NULL OR (score >= 0 AND score <= 100)` | `assignment_submissions.score` |
| 2 | `max_score > 0 AND max_score <= 100` | `assignments.max_score` |
| 3 | `order_index >= 1` | `modules.order_index` |
| 4 | `order_index >= 1` | `lessons.order_index` |

Тафтиши `score <= assignments.max_score` бо CHECK имконнопазир аст (ду ҷадвал) — дар сатҳи барнома иҷро мешавад. ⚠ MariaDB 10.4 CHECK-ро амалӣ мекунад (MySQL — аз 8.0.16).

---

## 8. Қоидаҳои ON DELETE

| № | FK | Ҷадвали истинодшаванда | ON DELETE | Шарҳи интихоб |
|---|---|---|---|---|
| 1 | courses.author_id | users | RESTRICT | муаллиферо, ки курс дорад, ҳазф кардан мумкин нест; курсҳо гум намешаванд |
| 2 | courses.category_id | categories | SET NULL | категория ҳазф шавад, курс мемонад (сутун ихтиёрӣ аст) |
| 3 | modules.course_id | courses | CASCADE | модул бе курс маъно надорад |
| 4 | lessons.module_id | modules | CASCADE | дарс бе модул маъно надорад |
| 5 | enrollments.user_id | users | RESTRICT | таърихи таҳсил ва сертификатҳо беасос нест намешаванд |
| 6 | enrollments.course_id | courses | RESTRICT | курси дорои номнависӣ ҳазф намешавад (ба ҷояш `status = archived`) |
| 7 | assignments.lesson_id | lessons | CASCADE | супориш қисми дарс аст; агар ҷавоб бошад, RESTRICT-и №8 ҳазфро манъ мекунад |
| 8 | assignment_submissions.assignment_id | assignments | RESTRICT | баҳоҳои таърихӣ беасос ҳазф намешаванд |
| 9 | assignment_submissions.user_id | users | RESTRICT | баҳоҳои таърихӣ нигоҳ дошта мешаванд |
| 10 | lesson_progress.user_id | users | CASCADE | пешрафт маълумоти ёрирасон аст ва бе корбар маъно надорад |
| 11 | lesson_progress.lesson_id | lessons | CASCADE | пешрафт бе дарс маъно надорад |
| 12 | certificates.enrollment_id | enrollments | RESTRICT | сертификати додашуда ҳуҷҷат аст ва беасос ҳазф намешавад |

Шумора: 12 FK = 12 қоида (RESTRICT — 6, CASCADE — 5, SET NULL — 1).

---

## 9. Талаботи Progress ва Certificate

### Progress

Фоизи пешрафт **нигоҳ дошта намешавад**, балки ҳисоб карда мешавад:

```
progress % = (дарсҳои анҷомёфта / ҳамаи дарсҳои курс) × 100
```

Дарсҳои анҷомёфта — шумораи сатрҳои `lesson_progress` бо `is_completed = 1` барои корбар ва дарсҳои ҳамон курс (`lessons → modules → courses`); ҳамаи дарсҳо — шумораи `lessons` дар модулҳои курс. `lesson_progress` анҷоми ҳар дарсро барои ҳар корбар нигоҳ медорад.

**Қоидаи кушодани дарси навбатӣ** (дарс танҳо пас аз анҷоми қаблӣ дастрас мешавад) бо FK ифода намешавад ва дар **мантиқи барнома** санҷида мешавад: тартиб бо `modules.order_index`, сипас `lessons.order_index`.

### Certificate PDF

`certificates` рақами ягона (`certificate_code`), санаи додан (`issue_date`) ва роҳи файли PDF (`pdf_path`)-ро нигоҳ медорад. Санҷиши анҷоми ҳамаи дарсҳои курс ва тавлиди PDF дар сатҳи барнома иҷро мешавад; пас аз он `enrollments.status = completed`, `completed_at` пур мешавад ва сатри `certificates` сохта мешавад.

---

## 10. Хулоса

Дар лабораторияи №6 модели мантиқии пойгоҳи додаҳои Biyomuz Academy таҳия шуд. Аз ҷадвали ғайримеъёрии `student_courses_flat` (10 сутун, 3 сатр) нормализатсия тавассути 1НФ (атомарӣ кардани арзишҳо), 2НФ (ҷудо кардани вобастагиҳои қисмӣ аз калиди `(student_id, course_id)`) ва 3НФ (ҷудо кардани ду вобастагии транзитивӣ: `course_id → category_id → category_name` ва `course_id → author_id → author_name, author_email`) иҷро шуд. Модели ниҳоӣ 10 ҷадвали соҳавӣ, 10 PK, 12 FK, 9 UNIQUE ва 4 CHECK дорад; ҳар FK қоидаи ON DELETE-и асоснок дорад. Муносибати M:N-и шунаванда ва курс тавассути `enrollments` амалӣ шудааст. Талаботҳои Progress ва Certificate бо `lesson_progress` ва `certificates` пӯшонида шудаанд, қоидаҳои мантиқии барнома (кушодани дарси навбатӣ, тавлиди PDF) алоҳида нишон дода шудаанд. ER-диаграмма бо нотацияи Crow's Foot ҳамаи 10 ҷадвал ва 12 FK-ро нишон медиҳад.

---

## 11. Камбудиҳо ва чизҳое, ки бояд дар лоиҳаи маҳаллӣ санҷида шаванд

- ⚠ Мигратсияҳои воқеӣ, `app/Models`, `class.puml` (Lab 5), MariaDB (`SHOW TABLES`) ва Git дар ин нусха санҷида нашудаанд. Номҳо, навъҳо ва қиматҳои ENUM бояд бо мигратсияҳо муқоиса шаванд.
- ⚠ Муқоисаи `class.puml` бо ER: тавре дар §1.1 нишон шудааст, танҳо аз рӯйи рӯйхати синфҳои талаботи лаборатория; хусусиятҳои синфҳо санҷида нашудаанд.
- ER-диаграмма: дар тарҳбандии ниҳоӣ (`users` дар марказ) ҳамаи 12 хат бе бархӯрд ва бе гузариш аз болои ҷадвалҳо аз назар гузаронида шуд (санҷиши визуалӣ). Ин санҷиш танҳо барои PNG-и ҳозира аст; ҳангоми тағйири `.puml` бояд такрор шавад.
