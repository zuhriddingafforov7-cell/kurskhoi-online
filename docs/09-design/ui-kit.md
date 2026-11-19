# UI-kit ва Design System: Biyomuz Academy (`docs/09-design/ui-kit.md`)

## 1. Маълумоти лоиҳа ва пайвандҳои Figma
- **Номи лоиҳа:** Biyomuz Academy — платформаи курсҳои онлайн (*«Имрӯз биёмӯз, фардо муваффақ шав!»*)
- **Номи расмии файли Figma:** `Biyomuz Academy — Design System & Prototype`
- **Пайванди воқеии Figma (`Anyone with the link — can view`):**
  [Biyomuz Academy — Design System & Prototype](https://www.figma.com/design/mCeS0di8nFuNacgINQp4xg/Biyomuz-Academy-%E2%80%94-Design-System---Prototype?node-id=3-43)
  *(Файли тасдиқшудаи абрӣ: 4 саҳифа, 8 Wireframe, 14 Paint Style, 5 Text Style Noto Sans, 8 Desktop Mockup, 1 Mobile, 2 Overlay)*
- **Пайванди воқеии Prototype (`Present ▶`):**
  [Biyomuz Academy Prototype (Present Mode)](https://www.figma.com/proto/mCeS0di8nFuNacgINQp4xg/Biyomuz-Academy-%E2%80%94-Design-System---Prototype?node-id=10-7108&starting-point-node-id=10%3A7108)
  *(1 Flow Starting Point дар `/ (Саҳифаи асосӣ)` бо 93 reaction: 84 Navigate, 3 Back, 4 Close, 2 Overlay)*

---

## 2. Шабакаи тарроҳӣ (Desktop ва Mobile Grid) ва фосилаҳо
Шабакаи макетҳо мутобиқи **Bootstrap 5.3** тарҳрезӣ шудааст, то дар Кори лаборатории №10 бидуни тағйири таносуб ба HTML/CSS гузаронида шавад:
- **Desktop Grid (`1440 × 1024 px`):** `Layout Grid → Columns`, **12 сутун**, **Width `88 px`**, **Type `Center`**, **Gutter `24 px`**. Паҳноии умумии муҳтаво: `12 × 88 + 11 × 24 = 1320 px` (баробар ба `.container`-и Bootstrap 5.3 дар экранҳои `≥ 1400 px`; фосилаи канорҳо `60 px`). Тақсимоти сутунҳо дар саҳифаи каталог: `.col-3` (`312 px`) + `24 px` + `.col-9` (`984 px`).
- **Mobile Grid (`390 × 844 px`):** `Layout Grid → Columns`, **4 сутун**, **Type `Stretch`**, **Margin `16 px`**, **Gutter `16 px`** (паҳноии муҳтаво: `358 px`; филтрҳо дар болои рӯйхати курсҳо ҷойгир мешаванд).
- **Қадами фосилаҳо (`Spacing`):** `4 / 8 / 16 / 24 / 32 / 48 px` *(танҳо `Button` барои расидан ба баландии ҳатмии `44 px` фосилаи амудии `10 px` дорад: `10 + 24 + 10 = 44 px`)*.

---

## 3. Color Styles (14 Paint Style + Variables) ва Bootstrap 5.3
Ранги брендии **Biyomuz Academy** (`#00875A`, `#064E3B`, `#ECFDF5` — манбаъ: `resources/css/app.css` ва `welcome.blade.php`) аз ранги стандартии кабуди Bootstrap (`#0D6EFD`) фарқ мекунад. Аз ин сабаб дар Кори лаборатории №10 тағйирёбандаи **`--bs-primary`** ва рангҳои ёрирасони зер дар CSS аз нав муайян карда мешаванд:

| Услуби Figma (`Paint Style`) | Коди HEX | Тағйирёбандаи Bootstrap 5.3 (Рӯзи 10) | Вазифа дар интерфейс |
|---|---|---|---|
| `color/primary` | `#00875A` | **`--bs-primary`** *(дар рӯзи 10 иваз мешавад)* | Тугмаҳои асосӣ, пайвандҳо, фокус, бренди Biyomuz Academy |
| `color/primary-dark` | `#064E3B` | `--bs-primary-text-emphasis` | Заминаи баннери Hero, поёни саҳифа (`Footer`) ва сарлавҳаи курс |
| `color/primary-hover` | `#006B47` | `--bs-btn-hover-bg` | Ҳолати Hover-и тугмаи Primary (10% ториктар) |
| `color/primary-soft` | `#ECFDF5` | `--bs-primary-bg-subtle` | Заминаи сабуки нишонҳо, пайвандҳои фаъол ва барчаспҳо |
| `color/success` | `#198754` | `--bs-success` | Ҳолатҳои `Нашршуда`, `Тасдиқ шуд` ва `Хатм шуд` |
| `color/warning` | `#FFC107` | `--bs-warning` | Ҳолати `Мунтазир` (`pending`) — **бо матни торики `color/text`** |
| `color/danger` | `#DC3545` | `--bs-danger` | Хатогиҳои санҷиш, `Радшуда`, тугмаҳои `Рад кардан` / `Нест кардан` |
| `color/danger-hover` | `#B02A37` | `--bs-btn-danger-hover-bg` | Ҳолати Hover-и тугмаи Danger |
| `color/text` | `#1E293B` | `--bs-body-color` | Матни асосии саҳифа ва матн дар рӯйи `color/warning` |
| `color/muted` | `#64748B` | `--bs-secondary` | Матни ёрирасон, зерсарлавҳаҳо, номи муаллиф ва давомнокӣ |
| `color/border` | `#DEE2E6` | `--bs-border-color` | Ҳошияи кортҳо, майдонҳои воридот (`Input`) ва ҷадвалҳо |
| `color/bg` | `#F8FAFC` | `--bs-light` | Заминаи умумии саҳифаҳои Biyomuz Academy |
| `color/surface` | `#FFFFFF` | `--bs-white` | Сатҳи кортҳо, равзанаҳои модалӣ ва матни сафед дар рӯйи рангҳои торик |
| `color/surface-hover` | `#F1F5F9` | `--bs-gray-100` | Ҳолати Hover-и тугмаи `Secondary` |

---

## 4. Typography (`Text Styles`) ва Локализатсия (`Тоҷикӣ / Русский`)
Ҳамаи 5 Text Style танҳо бо шрифти **`Noto Sans`** сохта мешаванд (`Inter` дар Text Style-ҳои лаборатория истифода намешавад), ки алифбои кириллитсаи пурраи тоҷикӣ ва русиро дар як намуди хат дастгирӣ мекунад.

- **Матни санҷишии кириллитса дар `03 UI-kit` (`ё`, `й`, `ъ`, `щ`, `Й` + ҳарфҳои тоҷикӣ):**
  `Санҷиши кириллитса (Noto Sans): ё, й, ъ, щ, Й, Щ · ғ, ӣ, қ, ӯ, ҳ, ҷ · Ғаффоров Зуҳриддин, Бахтиёр, Неъматов, щука, йогурт`
- **Қайди расмӣ дар бораи забон (`Тоҷикӣ` ва талаботи «реалистичные данные на русском»):** Дар Қисми Б-и дастури омӯзгор талаби «реалистичные данные на русском» зикр шудааст, ки бо интерфейси пурра тоҷикии вебсайти воқеии **Biyomuz Academy** (`resources/views/`) ихтилофи шаклӣ дорад. Азбаски платформаи амалкунанда дорои маҳаллисозии дузабона (`GET /locale/{locale}`: `Тоҷикӣ / Русский`) мебошад, макетҳои асосӣ ба забони тоҷикӣ сохта шудаанд ва дар баробари ин дар сарсаҳифа (`🇹🇯 Тоҷикӣ`), поёни саҳифа (`Footer`: `Тоҷикӣ • Русский`), равзанаи плагин (`ui.html`) ва қисми 5-уми `03 UI-kit` варианти иловагии локализатсияи русӣ низ таъмин гардидааст.

| Text Style | Андоза / Баландии сатр | Вазн (`Weight`) | Мутобиқат дар CSS / Bootstrap |
|---|---|---|---|
| `text/h1` | `32 / 40 px` | `SemiBold` | `h1` (`2rem`, `fw-semibold`) |
| `text/h2` | `24 / 32 px` | `SemiBold` | `h2` (`1.5rem`, `fw-semibold`) |
| `text/body-semibold` | `16 / 24 px` | `SemiBold` | Сарлавҳаи кортҳо (`.card-title`) |
| `text/body` | `16 / 24 px` | `Regular` | Матни асосӣ (`body`, `1rem`) |
| `text/small` | `14 / 20 px` | `Regular` | Барчаспи (`Label`) формаҳо, нишонҳо, метамаълумот (`.small`) |

---

## 5. Components ва Variants (`Auto Layout`)

1. **`Button` (`ComponentSet` · 9 вариант):**
   - **Хосиятҳо (`Properties`):** `Type` (`Primary`, `Secondary`, `Danger`) × `State` (`Default`, `Hover`, `Disabled`, `opacity: 50%`) + **Text Property `Label`**.
   - **Auto Layout:** уфуқӣ (`HORIZONTAL`), фосилаи дарунӣ `padding: 10px 16px`, **баландӣ `44 px`** (`>= 44 px`), гардиши кунҷ `radius: 6 px`.
   - **Bootstrap 5.3:** `.btn .btn-primary`, `.btn .btn-outline-secondary`, `.btn .btn-danger`.
2. **`Input` (`ComponentSet` · 4 вариант):**
   - **Сохтор:** стеки амудӣ (`VERTICAL` Auto Layout, `gap: 4 px`): `Label` (`text/small`) → `Field` (`height: 44 px`, `radius: 6 px`, `padding: 12 px`, паҳноӣ `Fill container`) → `Helper / Error text` (`text/small`).
   - **Вариантҳо (`State`):** `Default`, `Focus` (ҳошияи `color/primary`, `2 px`), `Error` (ҳошия ва матни хатогии `color/danger`: *«Андозаи файл аз 5 МБ зиёд аст (PDF, ZIP, DOCX)»*), `Disabled`.
   - **Bootstrap 5.3:** `.form-label` + `.form-control` + `.is-invalid` / `.invalid-feedback`.
3. **`CourseCard` (`ComponentSet` · 2 вариант: `Desktop` ва `Mobile`):**
   - **`Size=Desktop` (`HORIZONTAL` Auto Layout, `gap: 16 px`, `padding: 16 px`, `radius: 8 px`, `fill: color/surface`, `stroke: color/border`):**
     - нишони `64 × 64 px` бо рамзи курс (`LAR`);
     - стеки амудии `Fill container` (`gap: 4 px`): Номи курс (`text/body-semibold`, `textAutoResize: HEIGHT`), Муаллиф · Категория · Давомнокӣ · Арзиш (`text/small`, `color/muted`, мас. *«Устод Аҳмад Раҳимов · Барномасозӣ · 2 модул · 18 дарс · Ройгон»*), Тавсифи кӯтоҳ (`text/small`, `color/text`);
     - сутуни рости амалиёт (`CardActions`): нишони статус (`Нашршуда` ё `Мунтазир` бо ранги мувофиқ + матн) ва тугмаи воқеии `Button (Secondary)` — **`Курсро дидан`**.
   - **`Size=Mobile` (`VERTICAL` Auto Layout, `358 px`):** нишони `48 × 48 px` ва нишони статус дар сатри боло, стеки матн дар марказ ва тугмаи пурраи **`Курсро дидан`** дар поён.
   - **Санҷиши васеъшавӣ:** Дар `03 UI-kit` намунаи (`Instance`) санҷишии `CourseCard · Санҷиши Auto Layout бо матни дароз` пешбинӣ шудааст, то бо Auto Layout ба поён васеъ гардад (санҷиши визуалӣ дар Figma Desktop пас аз иҷрои плагин тасдиқ мешавад).

---

## 6. Рӯйхати экранҳо (`8 Desktop + 1 Mobile + Footer + 2 Overlays`)

- **`01 Flow`**: 2 User Flow (`US-01/US-02/US-03 · Донишҷӯ` аз `/` то `Overlay` ва `US-06/US-04/US-05 · Муаллиф ва Маъмур`) бо маршрутҳои воқеии Laravel (`routes/web.php`).
- **`02 Wireframes` (`1440 × 1024 px`, 8 экран дар ранги хокистарӣ):** `/ (Саҳифаи асосӣ)`, `/login`, `/courses`, `/courses/{course}`, `/student/dashboard`, `/student/assignments/{assignment}`, `/author/dashboard`, `/admin/courses`.
- **`04 Mockups` (`1440 × 1024 px`, 8 Desktop + `390 × 844 px`, 1 Mobile + 2 Overlays):** ҳама бо Component Instances, поёни саҳифа (`Footer`), Figma Styles ва танзимоти содироти `PNG 1x`. (Ниг. [screens.md](screens.md) ва [prototype.md](prototype.md)).

---

## 7. Натиҷаи санҷиши Accessibility (`WCAG 2.1`)

| № | Ҷуфти рангҳо | Коэффитсиенти контраст (WCAG 2.1, ҳисоби математикӣ) | Ҳолат ва хулоса |
|---|---|---|---|
| 1 | Сафед (`#FFFFFF`) дар рӯйи `color/primary` (`#00875A`) | **`4.55 : 1`** | **PASS (AA, ҳисобӣ)** — ба талаботи матни одӣ (`>= 4.5:1`) ҷавобгӯ аст |
| 2 | Сафед (`#FFFFFF`) дар рӯйи `color/primary-dark` (`#064E3B`) | **`9.72 : 1`** | **PASS (AAA, ҳисобӣ)** — контрасти олӣ дар баннери Hero ва поёни саҳифа (`Footer`) |
| 3 | `color/muted` (`#64748B`) дар рӯйи сафед (`#FFFFFF`) | **`4.76 : 1`** | **PASS (AA, ҳисобӣ)** — барои матни ёрирасон ва нишонҳо (`>= 4.5:1`) мувофиқ аст |
| 4a | Сафед (`#FFFFFF`) дар рӯйи `color/warning` (`#FFC107`) | **`1.63 : 1`** | **FAIL** — контрасти кофӣ надорад |
| 4b | `color/text` (`#1E293B`) дар рӯйи `color/warning` (`#FFC107`) | **`8.97 : 1`** | **PASS (AAA, ҳисобӣ)** — дар нишони `Мунтазир` матни торик истифода шуд |

- Баландии тугмаҳои `Button` ва майдони `Input` (`Field`) дар коди плагин: **`44 px`** (`>= 44 px`).
- Ҳамаи майдонҳои `Input` дорои барчаспи (`LabelText`) намоён мебошанд.
- Статуси курс (`Нашршуда`, `Мунтазир`, `Радшуда`) бо **ранг ва калима** нишон дода шудааст.
- **Эзоҳи воқеӣ:** Рақамҳои боло бо формулаи WCAG дар Node.js ҳисоб шудаанд; санҷиши воқеӣ бо плагини **`Contrast` ё `Stark`** дар дохили Figma Desktop ҳанӯз анҷом нашудааст (`BLOCKED`).

---

## 8. Вазъи содироти PNG-и макетҳо (`docs/09-design/screens/`)

Дар ҷузвдони [screens/](screens/) **ҳамаи 9 файли воқеии PNG (8 Desktop + 1 Mobile) ва 2 Overlay бевосита аз Figma содир ва сабт шудаанд**:

| № | Файли PNG | Фрейми мувофиқ дар `04 Mockups` | Андоза (px) | Ҳолати воқеӣ дар диск |
|---|---|---|---|---|
| 1 | `home.png` | `/ (Саҳифаи асосӣ)` | `1440 × 1024` | **✅ Содир шуд (Figma)** |
| 2 | `login.png` | `/login` | `1440 × 1024` | **✅ Содир шуд (Figma)** |
| 3 | `courses.png` | `/courses` | `1440 × 1024` | **✅ Содир шуд (Figma)** |
| 4 | `course-details.png` | `/courses/{course}` | `1440 × 1024` | **✅ Содир шуд (Figma)** |
| 5 | `student-dashboard.png` | `/student/dashboard` | `1440 × 1024` | **✅ Содир шуд (Figma)** |
| 6 | `assignment.png` | `/student/assignments/{assignment}` | `1440 × 1024` | **✅ Содир шуд (Figma)** |
| 7 | `author-dashboard.png` | `/author/dashboard` | `1440 × 1024` | **✅ Содир шуд (Figma)** |
| 8 | `admin-courses.png` | `/admin/courses` | `1440 × 1024` | **✅ Содир шуд (Figma)** |
| 9 | `courses-mobile.png` | `/courses — мобилӣ (390×844)` | `390 × 844` | **✅ Содир шуд (Figma)** |
| 10 | `Overlay · Вазифа супорида шуд.png` | `Overlay · Вазифа супорида шуд` | `520 × 264` | **✅ Содир шуд (Figma)** |
| 11 | `Overlay · Рад кардани курс.png` | `Overlay · Рад кардани курс` | `520 × 348` | **✅ Содир шуд (Figma)** |
