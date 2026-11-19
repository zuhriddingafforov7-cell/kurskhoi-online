# Прототипи кликшаванда дар `04 Mockups` (`docs/09-design/prototype.md`)

Гузаришҳои интерактивии прототип (`reactions`, аниматсия: `DISSOLVE · 200ms · EASE_OUT`) дар коди плагини [`code.js`](file:///C:/Users/ZUHRIDDIN/Desktop/kurskhoi-online/tools/figma-biyomuz-plugin/code.js) барои саҳифаи **`04 Mockups`** танзим шудаанд.

> **Пайванди мустақими санҷиш дар Present Mode:**
> [Biyomuz Academy Interactive Prototype (Present ▶)](https://www.figma.com/proto/mCeS0di8nFuNacgINQp4xg/Biyomuz-Academy-%E2%80%94-Design-System---Prototype?node-id=10-7108&starting-point-node-id=10%3A7108)
> Танзимоти реаксияҳо дар саҳифаи `04 Mockups` бо 93 reaction (`Navigate`, `Back`, `Close`, `Open overlay`) пурра фаъоланд. Барои санҷиши амалӣ тугмаи **`Present (▶)`**-ро пахш намуда, занҷири гузаришҳоро санҷед.

## 1. Нуқтаи оғоз (`Flow Starting Point`)
1. **`Роҳи корбар дар Biyomuz Academy (US-02 / US-03)`** — ягона нуқтаи оғози прототипи асосӣ дар **`04 Mockups`**, ки аз фрейми **`/ (Саҳифаи асосӣ)`** сар мешавад. (Ду сенарияи пурраи корбар — **Донишҷӯ** ва **Муаллиф/Маъмур** — дар саҳифаи **`01 Flow`** алоҳида нигоҳ дошта шудаанд.)

---

## 2. Гузаришҳои пешбинишуда (беш аз 5 гузариш + `Back` + `Overlay`)

| № | Экран / Унсури интерактивӣ | Триггер ва амал (`Figma Action`) | Ҳадаф / Натиҷа |
|---|---|---|---|
| 1 | `/ (Саҳифаи асосӣ)` → Тугмаи **`Каталоги курсҳо →`** | `On click → Navigate to` | `/courses` |
| 2 | `/ (Саҳифаи асосӣ)` → Тугмаи **`Бақайдгирӣ`** / **`Ворид шудан`** | `On click → Navigate to` | `/login` |
| 3 | `/login` → Тугмаи **`Ворид шудан`** | `On click → Navigate to` | `/student/dashboard` |
| 4 | Сарсаҳифа (дар ҳамаи экранҳо) → **`Курсҳо`** / **`Кабинети муаллиф`** / **`Идоракунии система`** | `On click → Navigate to` | `/courses`, `/author/dashboard`, `/admin/courses` |
| 5 | `/courses` → Корти **`Кор бо Laravel`** | `On click → Navigate to` | `/courses/{course}` |
| 6 | `/courses/{course}` → Тугмаи **`← Бозгашт`** | `On click → Back` | Бозгашт ба экрани қаблӣ |
| 7 | `/courses/{course}` → Тугмаи **`Ба курс номнавис шудан`** | `On click → Navigate to` | `/student/dashboard` |
| 8 | `/courses/{course}` → Тугмаи **`Супоридани вазифа`** | `On click → Navigate to` | `/student/assignments/{assignment}` |
| 9 | `/student/dashboard` → **`Супоридани вазифа`** | `On click → Navigate to` | `/student/assignments/{assignment}` |
| 10 | `/student/assignments/{assignment}` → **`Супоридани вазифа`** | `On click → Open overlay` (`Top center`, `Close when clicking outside`) | `Overlay · Вазифа супорида шуд` |
| 11 | `Overlay · Вазифа супорида шуд` → **`Бозгашт ба кабинет`** | `On click → Navigate to` | `/student/dashboard` |
| 12 | `Overlay · Вазифа супорида шуд` → **`×` / `Пӯшидан`** | `On click → Close overlay` | Бастани огоҳинома |
| 13 | `/author/dashboard` → **`+ Сохтани курси нав`** | `On click → Navigate to` | `/admin/courses` |
| 14 | `/admin/courses` → Тугмаи **`Рад кардан`** (`Danger`) | `On click → Open overlay` (`Center`, `Close when clicking outside`) | `Overlay · Рад кардани курс` |
| 15 | `/admin/courses` → Тугмаи **`Тасдиқ кардан`** | `On click → Navigate to` | `/courses` |
| 16 | `/admin/courses` → Тугмаи **`← Бозгашт`** | `On click → Back` | Бозгашт ба экрани қаблӣ |
| 17 | `/courses — мобилӣ (390×844)` → Корти **`Кор бо Laravel`** | `On click → Navigate to` | `/courses/{course}` |

Барои санҷиши воқеӣ: дар **Figma Desktop** саҳифаи **`04 Mockups`**-ро кушода, тугмаи **`Present (▶)`**-ро пахш кунед ва сенарияро аз `/ (Саҳифаи асосӣ)` → `/courses` → `/courses/{course}` → `/student/assignments/{assignment}` то кушодани `Overlay · Вазифа супорида шуд` пурра гузаред.
