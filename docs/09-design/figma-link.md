# Дастури пайвастшавӣ ва содирот аз Figma Desktop (`docs/09-design/figma-link.md`)

**Номи расмии файли Figma:** `Biyomuz Academy — Design System & Prototype`
**Аккаунти корбар:** `zuhriddingafforov7@gmail.com`
**Ҷузвдони плагин:** `C:\Users\ZUHRIDDIN\Desktop\kurskhoi-online\tools\figma-biyomuz-plugin\`

---

## 1. Пайвандҳои воқеии Figma

- **Пайванди воқеии файл дар абр (Cloud Figma File):**
  [https://www.figma.com/design/mCeS0di8nFuNacgINQp4xg/Biyomuz-Academy-%E2%80%94-Design-System---Prototype?node-id=3-43](https://www.figma.com/design/mCeS0di8nFuNacgINQp4xg/Biyomuz-Academy-%E2%80%94-Design-System---Prototype?node-id=3-43)
  *(Ҳолат: `ТАСДИҚШУДА` — файл дар абри Figma фаъол аст, 4 саҳифа, 8 Wireframe, UI-kit ва 8 Desktop + 1 Mobile Mockup мавҷуданд)*

- **Пайванди прототипи интерактивӣ (`Present ▶`):**
  [https://www.figma.com/proto/mCeS0di8nFuNacgINQp4xg/Biyomuz-Academy-%E2%80%94-Design-System---Prototype?node-id=10-7108&starting-point-node-id=10%3A7108](https://www.figma.com/proto/mCeS0di8nFuNacgINQp4xg/Biyomuz-Academy-%E2%80%94-Design-System---Prototype?node-id=10-7108&starting-point-node-id=10%3A7108)
  *(Ҳолат: `ТАСДИҚШУДА` — 1 Flow Starting Point дар `/ (Саҳифаи асосӣ)` ва 93 гузариши мутақобила сабт шудаанд)*

- **Скриншотҳои саҳифаҳои Figma (`docs/09-design/screenshots/`):**
  Дар ҷузвдони [`screenshots/`](screenshots/) 5 скриншоти воқеӣ бевосита аз худи Figma тавассути MCP гирифта ва сабт шудаанд:
  - `01-flow.png` (User Flow бо китобхонаи шаклҳо)
  - `02-wireframes.png` (8 экрани Wireframe бо сеткаи 12-сутуна)
  - `03-ui-kit.png` (14 Paint Styles, 5 Text Styles Noto Sans, Button 3×3, Input 4 ҳолат, Accessibility)
  - `04-mockups.png` (8 Desktop Mockup + 1 Mobile + 2 Overlay)
  - `05-mobile.png` (Макетҳои мобилӣ)

---

## 2. Қадамҳои дастии корбар дар Figma Desktop

1. **Сохтани файли нав ё интихоби файли асосӣ:**
   Дар **Figma Desktop**: `Drafts → New design file` (тугмаи `Design`) → номашро **`Biyomuz Academy — Design System & Prototype`** гузоред.
2. **Иҷрои плагин:**
   Клики рост дар майдони корӣ → **`Plugins → Development → Biyomuz Academy UI Generator`** → бо интихоби **`Тоҷикӣ (Интерфейси асосӣ)`** тугмаи **`Сохтани дизайни Biyomuz Academy`**-ро пахш кунед.
   *(Боварӣ ҳосил кунед, ки 4 саҳифа ба забони тоҷикӣ сохта шуданд: `01 Flow`, `02 Wireframes`, `03 UI-kit`, `04 Mockups`).*
3. **Санҷиши шрифт, `CourseCard` ва дастрасӣ (`Accessibility`) дар Figma:**
   - Дар `03 UI-kit` санҷед, ки сатри санҷишии кириллитса (`ё, й, ъ, щ, Й` + `ғ, ӣ, қ, ӯ, ҳ, ҷ`) дар `Noto Sans` бе хато намоиш дода мешавад ва намунаи `CourseCard` бо матни дароз ба поён васеъ мегардад.
   - Плагини **`Contrast`** ё **`Stark`**-ро кушода, 3 ҷуфти асосиро санҷед (`#FFFFFF` дар рӯйи `#00875A`, `#64748B` дар рӯйи `#FFFFFF`, `#1E293B` дар рӯйи `#FFC107`).
4. **Санҷиши прототип (`Present ▶`):**
   Дар саҳифаи `04 Mockups` тугмаи **`Present (▶)`**-ро пахш карда, сенарияро аз `/ (Саҳифаи асосӣ)` → `/courses` → `/courses/{course}` → `← Бозгашт` → `/student/assignments/{assignment}` → `Overlay · Вазифа супорида шуд` то охир гузаред.
5. **Содироти воқеии PNG (`Export 1x`):**
   Дар саҳифаи `04 Mockups` фреймҳои экранҳоро интихоб карда, **`Ctrl+Shift+E`** (`PNG 1x`)-ро пахш кунед ва ба `C:\Users\ZUHRIDDIN\Desktop\kurskhoi-online\docs\09-design\screens\` бо ин номҳо содир намоед:
   `home.png`, `login.png`, `courses.png`, `course-details.png`, `student-dashboard.png`, `assignment.png`, `author-dashboard.png`, `admin-courses.png`, `courses-mobile.png` *(ва ихтиёрӣ 2 Overlay)*.
6. **Кушодани дастрасии истинод:**
   Тугмаи кабуди **`Share`** → **`Anyone with the link`** → **`can view`** → **`Copy link`**, сипас истинодро ба [`ui-kit.md`](ui-kit.md) ва ҳамин файл ворид кунед.
