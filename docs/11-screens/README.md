# Ҳисоботи санҷиши интерактивӣ ва скриншотҳои JavaScript (Лабораторияи №11)

Ин ҳуҷҷат натиҷаҳои санҷиши функсионалӣ, бехатарӣ ва дастрасии қисми интерактивии платформаи **Biyomuz Academy**-ро дар доираи Лабораторияи №11 («FRONTEND (2): JavaScript — валидация, живой поиск, модальное окно») дар бар мегирад.

---

## 1. Сана ва муҳити санҷиш

- **Санаи санҷиш:** `2026-11-23`
- **PHP Runtime:** `PHP 8.5.10` (`C:\Users\ZUHRIDDIN\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.5_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe`)
- **Сервери санҷиши маҳаллӣ (порт барои санҷиши муваққатӣ):** `http://127.0.0.1:8001` (`php -S 127.0.0.1:8001 -t public`)
- **Сервери фаъоли лоиҳа:** `http://127.0.0.1:8000` (Laravel + Watchdog + Tailscale Funnel бетағйир фаъол нигоҳ дошта шуд)
- **Браузер:** Google Chrome + Chrome DevTools Protocol (CDP)

### Саҳифаҳо ва endpoint-ҳои санҷидашуда

| № | Қисм | Масири файл | Суроғаи HTTP | Вазифа |
|---|---|---|---|---|
| 1 | Заглушкаи муваққатии API | `public/api/courses/index.php` | `http://127.0.0.1:8001/api/courses?q=...` | JSON API (10 курси `seed.sql` + 1 сабти XSS) |
| 2 | Модули ES JavaScript | `public/assets/js/app.js` | `http://127.0.0.1:8001/assets/js/app.js` | Валидатсия, ҷустуҷӯи зинда, модалка (5 қисм) |
| 3 | Бақайдгирии донишҷӯ | `public/static/register.html` | `http://127.0.0.1:8001/static/register.html` | Формаи бақайдгирӣ (`novalidate`, 6 майдон) |
| 4 | Каталоги курсҳо | `public/static/courses.html` | `http://127.0.0.1:8001/static/courses.html` | Ҷустуҷӯи зинда (`#course-search`, `debounce 300ms`, `AbortController`) |
| 5 | Идоракунии курсҳои муаллиф | `public/static/author-courses.html` | `http://127.0.0.1:8001/static/author-courses.html` | Модалкаи тасдиқи несткунӣ (`#confirm-delete-modal`, `method="post"`) |

---

## 2. Натиҷаҳои санҷиши QA (Chrome CDP + DevTools)

| Бахш | Тафсилоти санҷиш | Натиҷа |
|---|---|---|
| **Валидатсияи форма** | Ирсоли формаи холӣ → 6 хатогии русӣ дар зери майдонҳо (`aria-invalid="true"`, `aria-describedby`), фокус ба майдони якум (`#reg-full-name`). Санҷиши Email (`typeMismatch`), `minlength`, мувофиқати рамзҳо (`setCustomValidity`), тозашавии фаврӣ пас аз ислоҳ. Маълумот ба БД навишта нашуд. | **PASS** |
| **Заглушкаи API** | `php -l` (0 хато). `q=""` → 11 сабт; `q="php"` → 2 сабт; `q="асосҳои"` (кириллитса бо `mb_strtolower(..., 'UTF-8')`) → 4 сабт; `q="notfound999"` → `[]`. | **PASS** |
| **Ҷустуҷӯи зинда (Live Search)** | Воридкунии босуръати `p` → `ph` → `php` дар зарфи 90 мс: тавассути `debounce(300)` танҳо **1 дархости HTTP** (`/api/courses?q=php`, `200 OK`, `application/json`) ирсол гардид. Бекоркунии дархости кӯҳна бо `AbortController` ва санҷиши `response.ok` (паёми хатогӣ ҳангоми HTTP 500) тасдиқ шуд. | **PASS** |
| **Муҳофизат аз XSS** | Барои сабти `<img src=x onerror="alert('XSS')"> Тести Biyomuz`: `0` равзанаи `alert()`, `0` теги сохташудаи `<img>` дар DOM — тамоми сатр тавассути `textContent` ҳамчун матни одӣ нишон дода шуд. | **PASS** |
| **Модалкаи тасдиқ** | Пахши «Нест кардан» (ҳатто баъди навсозии зиндаи ҷадвал тавассути делегиратсия) сарлавҳаи «Курсро нест мекунед?» ва номи курсро дар `#confirm-delete-name` бо забони тоҷикӣ нишон дод; `method="post"` таъмин шуд; `ESC` ва тугмаи «Бекор кардан» модалро пӯшонида, фокусро ба тугма баргардонданд; ҳангоми `submit` тугма `disabled = true` шуд (бе нест кардани курси воқеӣ аз БД). | **PASS** |
| **Консоли браузер (Console)** | 0 хатогии ғайричашмдошти JS / CORS / ES Module дар ҳамаи саҳифаҳои ЛР №10 ва ЛР №11. | **PASS** |

---

## 3. Феҳристи 4 скриншоти воқеии браузер (`docs/11-screens/`)

1. **`01-registration-validation-errors.png`** — Саҳифаи `register.html` пас аз санҷиши майдонҳои холӣ, Email-и нодуруст ва номувофиқатии рамзҳо (`setCustomValidity`): намоиши синфи `.is-invalid` ва паёмҳои хатогӣ ба забони русӣ дар зери майдонҳо.
2. **`02-course-search-network.png`** — Саҳифаи `courses.html` (бо натиҷаи тоҷикии «Курсҳои ёфтшуда: 2» ва ду корти курс) дар канори панели воқеии **Chrome DevTools → Network → Fetch/XHR**: дархости ягонаи `courses?q=php` пас аз `debounce` (300 мс), статуси `200`, `1.1 kB`, ва ҷавоби JSON дар табақаи `Response`.
3. **`03-xss-safe-text.png`** — Намоиши сабти санҷишии XSS (`<img src=x onerror="alert('XSS')"> Тести Biyomuz`) дар каталоги `courses.html` ва ҷадвали он тавассути `textContent` ҳамчун матни одӣ (бе иҷрои HTML ва JS).
4. **`04-delete-confirmation-modal.png`** — Саҳифаи `author-courses.html` пас аз ҷустуҷӯи зинда бо равзанаи кушодаи модалии `#confirm-delete-modal` ва намоиши номи курси интихобшуда.
