====================================================================
Biyomuz Academy — Lab 9 Figma Plugin (Вебсайти воқеии тоҷикӣ + ЛР №9)
====================================================================

Папкаи плагин:
  C:\Users\ZUHRIDDIN\Desktop\kurskhoi-online\tools\figma-biyomuz-plugin

Файлҳо:
  - manifest.json — танзимоти расмии плагини Figma (dynamic-page)
  - code.js       — генератори 4 саҳифаи расмӣ (01 Flow, 02 Wireframes, 03 UI-kit, 04 Mockups)
  - ui.html       — равзана бо интихоби Тоҷикӣ (асосӣ) / Русский ва тугмаи «Сохтани дизайни Biyomuz Academy»

ДАСТУРИ ИҶРО ДАР FIGMA DESKTOP:
--------------------------------------------------------------------
1. Дар Figma Desktop файли нав созед:
   Drafts → New design file → номашро «Biyomuz Academy — Design System & Prototype» гузоред.
2. Меню: Plugins → Development → Biyomuz Academy UI Generator
   (агар лозим бошад: Import plugin from manifest... → manifest.json).
3. Боварӣ ҳосил кунед, ки забони «Тоҷикӣ (Интерфейси асосӣ)» интихоб шудааст ва тугмаи
   «Сохтани дизайни Biyomuz Academy»-ро пахш кунед.
4. Плагин ба таври худкор месозад:
   - 01 Flow       — 2 User Flow (Донишҷӯ + Муаллиф/Маъмур) бо маршрутҳои воқеии Laravel
   - 02 Wireframes — 8 экрани Desktop 1440×1024 (/, /login, /courses, /courses/{course}, /student/dashboard, /student/assignments/{assignment}, /author/dashboard, /admin/courses) дар Grayscale бо шабакаи 12×88 px, Type Center, Gutter 24 px
   - 03 UI-kit     — 14 Color Styles (Paint Styles), 5 Text Styles (танҳо Noto Sans бо санҷиши ё, й, ъ, щ, Й),
                     Button (3×3 + Label property), Input (4 ҳолат), CourseCard (Desktop/Mobile бо Auto Layout ва тугмаи «Курсро дидан»),
                     Accessibility ва муқоисаи локализатсияи Тоҷикӣ / Русский
   - 04 Mockups    — 8 экрани Desktop 1440×1024 + 1 экрани Mobile 390×844 (4 cols, 16px) + Footer + 2 Overlay + Прототип (1 Flow Starting Point дар «/ Саҳифаи асосӣ»)
5. Дар саҳифаи 04 Mockups баъди санҷиш:
   - Тугмаи Present (▶)-ро пахш карда, сенарияро аз «/ (Саҳифаи асосӣ)» то охир санҷед;
   - Фреймҳои макетҳоро ҷудо карда бо Ctrl+Shift+E (PNG 1x) ба docs\09-design\screens\ содирот кунед;
   - Share → «Anyone with the link — can view» → Copy link ва истинодро ба docs\09-design\ui-kit.md ворид кунед.
