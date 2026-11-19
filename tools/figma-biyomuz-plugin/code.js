// Biyomuz Academy · Кори лаборатории №9 («Дизайн в Figma»)
// Плагин 4 саҳифаи расмиро мувофиқи талаботи методичка ва сомонаи воқеии Laravel (Biyomuz Academy) месозад:
//   01 Flow       — 2 User Flow (Донишҷӯ + Муаллиф/Маъмур) бо маршрутҳои воқеии Laravel (routes/web.php)
//   02 Wireframes — 8 экрани Desktop 1440×1024 px дар Grayscale (шабакаи 12 × 88 px, Center, gutter 24 px)
//   03 UI-kit     — 14 Color Styles, 5 Text Styles (танҳо Noto Sans), Button, Input, CourseCard, Accessibility, Тоҷикӣ/Русӣ
//   04 Mockups    — 8 экрани Desktop + 1 Mobile (390×844) + Footer + 2 Overlay + Прототип (1 Flow Starting Point дар /)

figma.showUI(__html__, { width: 390, height: 450 });

figma.ui.onmessage = async function (msg) {
  if (!msg || msg.type !== 'build') return;
  try {
    var lang = msg.lang === 'ru' ? 'ru' : 'tg';
    await build(lang);
    figma.notify(lang === 'ru'
      ? 'Готово! Созданы 01 Flow, 02 Wireframes (8), 03 UI-kit (14 цветов, Noto Sans) и 04 Mockups с прототипом.'
      : 'Тайёр! Дар Figma 4 саҳифа (01 Flow, 02 Wireframes, 03 UI-kit, 04 Mockups) барои Biyomuz Academy сохта шуданд.', { timeout: 6000 });
  } catch (e) {
    console.error(e);
    figma.notify('Хатогӣ: ' + (e && e.message ? e.message : String(e)), { error: true, timeout: 15000 });
  }
  figma.closePlugin();
};

// ---------------------------------------------------------------- Маълумоти воқеии лоиҳа (тоҷикӣ — пешфарз, русӣ — варианти локализатсия)
var TEXT = {
  tg: {
    pages: ['01 Flow', '02 Wireframes', '03 UI-kit', '04 Mockups'],
    brand: 'Biyomuz Academy',
    slogan: 'Имрӯз биёмӯз, фардо муваффақ шав!',
    nav: [
      ['home', 'Саҳифаи асосӣ'],
      ['courses', 'Курсҳо'],
      ['studentDash', 'Кабинети донишҷӯ'],
      ['authorDash', 'Кабинети муаллиф'],
      ['adminCourses', 'Идоракунии система']
    ],
    routes: {
      home: '/ (Саҳифаи асосӣ)',
      login: '/login',
      courses: '/courses',
      course: '/courses/{course}',
      studentDash: '/student/dashboard',
      assignment: '/student/assignments/{assignment}',
      authorDash: '/author/dashboard',
      adminCourses: '/admin/courses',
      mobileCourses: '/courses — мобилӣ (390×844)'
    },
    flow: {
      title: '01 User Flow · Biyomuz Academy (мувофиқи User Stories ва routes/web.php)',
      f1Title: 'Сенарияи 1 (Донишҷӯ · US-01 / US-02 / US-03): Боздид, номнависӣ ба курс, омӯзиши дарсҳо ва супоридани вазифа',
      f1Steps: [
        ['Саҳифаи асосӣ', '/'],
        ['Каталоги курсҳо', '/courses'],
        ['Маълумоти курс', '/courses/{course}'],
        ['Ворид шудан / Бақайдгирӣ', '/login · /register'],
        ['[Ба курс номнавис шудан]', 'POST /student/courses/{id}/enroll'],
        ['Омӯзиши дарсҳо', '/student/lessons/{lesson}'],
        ['Супоридани вазифа', '/student/assignments/{id}'],
        ['[Ирсоли вазифа]', 'POST /student/assignments/{id}/submit'],
        ['Огоҳинома + Кабинет', '/student/dashboard (Overlay)']
      ],
      f2Title: 'Сенарияи 2 (Муаллиф ва Маъмур · US-06 / US-04 / US-05): Эҷоди курс, модулу дарсҳо, санҷиши вазифа ва тасдиқ',
      f2Steps: [
        ['Ворид шудан', '/login'],
        ['Кабинети муаллиф', '/author/dashboard'],
        ['Эҷод ва таҳрири курс', '/author/courses/create'],
        ['Модулҳо ва дарсҳо', '/author/courses/{id}/modules'],
        ['[Ирсол ба модератсия]', 'PATCH /author/courses/{id}/submit'],
        ['Санҷиши вазифа ва тасдиқ', '/admin/courses · /author/submissions'],
        ['[Тасдиқ / Рад]', 'PATCH /admin/courses/{id}/approve|reject'],
        ['Курс дар каталог', '/courses (Нашршуда)']
      ]
    },
    kit: {
      title: '03 UI-kit · Системаи дизайни Biyomuz Academy',
      sub: 'Палитраи воқеии сомона (resources/css/app.css) бо Bootstrap 5.3 (--bs-primary: #00875A) · Шрифти Noto Sans',
      colors: '1. Color Styles (14 Paint Styles + Variables барои рӯзи 10 / Bootstrap 5.3)',
      type: '2. Text Styles (5 услуби Noto Sans) ва санҷиши пурраи кириллитса',
      typeSamples: {
        'text/h1':            'Biyomuz Academy — Омӯзиши онлайн (Noto Sans)',
        'text/h2':            'Каталоги курсҳо ва идоракунии система',
        'text/body-semibold': 'Кор бо Laravel · Устод Аҳмад Раҳимов',
        'text/body':          'Ба курс номнавис шавед, дарсҳоро омӯзед ва шаҳодатномаи расмии PDF гиред.'
      },
      cyrTest: 'Санҷиши кириллитса (Noto Sans): ё, й, ъ, щ, Й, Щ · ғ, ӣ, қ, ӯ, ҳ, ҷ · Ғаффоров Зуҳриддин, Бахтиёр, Неъматов, щука, йогурт',
      comps: '3. Компонентҳо (Button бо Variants + Text Property, Input 4 ҳолат, CourseCard Desktop/Mobile бо Auto Layout)',
      longCardName: 'CourseCard · Санҷиши Auto Layout бо матни дароз',
      longTitle: 'Кор бо Laravel 13: Меъмории пурраи MVC, Eloquent ORM, навбатҳо ва нашр дар сервер',
      longDesc: 'Курси муфассали амалӣ барои барномасозон бо санҷиши супоришҳо аз ҷониби муаллиф, шаҳодатномаи PDF бо QR-код ва лоиҳаҳои воқеӣ дар MariaDB.',
      a11y: '4. Санҷиши дастрасӣ (Accessibility · WCAG 2.1 AA >= 4.5:1)',
      a11yRows: [
        ['Матни сафед (#FFFFFF) дар рӯйи color/primary (#00875A)', '4.55 : 1 · PASS (AA)', 'success'],
        ['Матни сафед (#FFFFFF) дар рӯйи color/primary-dark (#064E3B)', '9.72 : 1 · PASS (AAA)', 'success'],
        ['color/muted (#64748B) дар рӯйи сафед (#FFFFFF)', '4.76 : 1 · PASS (AA)', 'success'],
        ['Сафед дар рӯйи color/warning (#FFC107) — контрасти паст', '1.63 : 1 · FAIL → матни торик лозим', 'danger'],
        ['color/text (#1E293B) дар рӯйи color/warning (#FFC107)', '8.97 : 1 · PASS (AAA)', 'success']
      ],
      locTitle: '5. Варианти иловагии локализатсия (Тоҷикӣ / Русский · GET /locale/{locale})',
      locSub: 'Интерфейси асосӣ — тоҷикӣ; забони русӣ ҳамчун варианти иловагии локализатсия дар сарсаҳифа ва поёни саҳифа (Footer) дастрас аст.',
      locRows: [
        ['Саҳифаи асосӣ · Курсҳо · Дар бораи мо', 'Главная · Каталог курсов · О нас'],
        ['Ба курс номнавис шудан · Курсро дидан', 'Записаться на курс · Посмотреть курс'],
        ['Пешрафти омӯзиш · Шаҳодатнома', 'Учебный прогресс · Сертификат PDF'],
        ['Супоридани вазифа · Кабинети муаллиф', 'Отправка задания · Кабинет автора']
      ],
      gridSpec: 'Шабака: Desktop 1440 = 12 сутун × 88 px, Center, Gutter 24 px (контент 1320 px · .container) | Mobile 390 = 4 сутун Stretch, Margin 16 px, Gutter 16 px'
    },
    courses: [
      { code: 'АЛГ', title: 'Асосҳои барномасозӣ', meta: 'Устод Аҳмад Раҳимов · Барномасозӣ · 2 модул · 12 дарс · Ройгон', desc: 'Курси муқаддимавӣ барои шурӯъкунандагон оид ба мантиқи алгоритмҳо ва сохтори додаҳо.', status: 'Нашршуда', tone: 'success' },
      { code: 'PHP', title: 'Омӯзиши забони PHP', meta: 'Устод Аҳмад Раҳимов · Барномасозӣ · 2 модул · 16 дарс · Ройгон', desc: 'Курси мукаммали забони PHP 8.5 аз сифр то сатҳи пешрафтаи веб-барномасозӣ.', status: 'Нашршуда', tone: 'success' },
      { code: 'LAR', title: 'Кор бо Laravel', meta: 'Устод Аҳмад Раҳимов · Барномасозӣ · 2 модул · 18 дарс · Ройгон', desc: 'Таҳияи барномаҳои муосири веб бо истифодаи Laravel 13, Eloquent ва меъмории MVC.', status: 'Нашршуда', tone: 'success' },
      { code: 'UX',  title: 'Тарҳрезии вебсайт', meta: 'Шаҳром Саидов · Веб-дизайн · 1 модул · 10 дарс · Ройгон', desc: 'Асосҳои тарҳрезии графикӣ ва таҳияи интерфейсҳои корбарӣ (UI/UX) дар Figma ва веб.', status: 'Нашршуда', tone: 'success' },
      { code: 'SQL', title: 'Пойгоҳи додаҳои MySQL', meta: 'Синои Ҳасан · Пойгоҳи додаҳо · 2 модул · 12 дарс · Ройгон', desc: 'Тарҳрезии пойгоҳи додаҳо, нормализатсия то 3НФ ва дархостҳои мураккаби SQL дар MariaDB.', status: 'Нашршуда', tone: 'success' },
      { code: 'SEC', title: 'Амнияти веб', meta: 'Синои Ҳасан · Амнияти иттилоотӣ · 2 модул · 8 дарс · Ройгон', desc: 'Ҳимояи веб-барномаҳо аз ҳамлаҳои SQL Injection, XSS ва CSRF барои барномасозон.', status: 'Мунтазир', tone: 'warning' }
    ],
    ui: {
      langPill: '🇹🇯 Тоҷикӣ',
      mobLangToggle: '🇹🇯 · ☰',
      logoutBtn: 'Баромад',
      btnSave: 'Захира кардан',
      btnCancel: 'Бекор кардан',
      btnDelete: 'Нест кардан',
      inLabel: 'Почтаи электронӣ (E-mail)',
      inPh: 'farhod@example.tj',
      inHelp: 'Почтаи ҳангоми бақайдгирӣ воридшударо нависед',
      inErr: 'Андозаи файл аз 5 МБ зиёд аст (PDF, ZIP, DOCX)',
      cardViewBtn: 'Курсро дидан',
      heroBadge: '🎓 Платформаи миллии омӯзиши онлайн',
      heroTitle: 'Biyomuz Academy',
      heroSlogan: '«Имрӯз биёмӯз, фардо муваффақ шав!»',
      heroDesc: 'Курсҳои касбӣ аз беҳтарин муаллифон бо забони тоҷикӣ, дарсҳои пайдарпай, супоришҳои амалӣ ва шаҳодатномаи расмии хатм.',
      heroCatalogBtn: 'Каталоги курсҳо →',
      heroLoginBtn: 'Бақайдгирӣ',
      stats: [
        ['8', 'Курсҳои нашршуда'],
        ['4', 'Категорияҳо'],
        ['16', 'Донишҷӯён'],
        ['3', 'Шаҳодатномаҳои PDF']
      ],
      catChips: ['Барномасозӣ (7)', 'Веб-дизайн (2)', 'Пойгоҳи додаҳо (1)', 'Амнияти иттилоотӣ (0)'],
      popularTitle: 'Курсҳои машҳур',
      footerSlogan: '«Имрӯз биёмӯз, фардо муваффақ шав!»',
      footerDesc: 'Платформаи муосири миллии омӯзиши онлайн бо курсҳои касбӣ ва шаҳодатномаҳои расмии хатм.',
      footerLinksTitle: 'Пайвандҳои муфид',
      footerLinks: ['Каталоги курсҳо', 'Дар бораи мо', 'Тамос', 'Бақайдгирӣ'],
      footerContactTitle: 'Тамос',
      footerContacts: ['info@biyomuz.tj', 'ш. Хуҷанд / ш. Душанбе, Тоҷикистон', '24/7 Онлайн'],
      footerCopy: '© 2026 Biyomuz Academy. Ҳамаи ҳуқуқҳо маҳфузанд.',
      footerLangSwitch: 'Тоҷикӣ • Русский',
      loginTitle: 'Воридшавӣ ба система',
      loginSub: 'Барои дастрасӣ ба курсҳо ва маводи таълимӣ ворид шавед',
      emailLbl: 'Почтаи электронӣ', emailVal: 'farhod@example.tj',
      passLbl: 'Парол', passVal: '••••••••',
      passHelp: 'Ҳадди ақал 8 аломат',
      loginBtn: 'Ворид шудан',
      noAccount: 'Ҳисоб надоред? Бақайдгирӣ →',
      catTitle: 'Каталоги курсҳо',
      filtersTitle: 'Филтрҳо',
      catLbl: 'Категория', catVal: 'Ҳамаи категорияҳо (4)',
      lvlLbl: 'Сатҳ', lvlVal: 'Ҳамаи сатҳҳо',
      searchLbl: 'Ҷустуҷӯи курс', searchPh: 'Масалан, Laravel ё PHP',
      applyFilter: 'Ҷустуҷӯ',
      addCourse: '+ Курси нав',
      pageInfo: 'Натиҷа: 8 курси нашршуда · Саҳифаи 1 аз 2',
      backCatalog: '← Бозгашт',
      courseHeroMeta: 'Муаллиф: Устод Аҳмад Раҳимов · 3 донишҷӯ · 2 модул · 18 дарс',
      modulesTitle: 'Дарсҳо ва мундариҷаи курс (FR-05)',
      modulesList: [
        '1.1 · Муқаддима ба Laravel 13 ва Artisan (Анҷом дода шуд)',
        '1.2 · Маршрутизатсия ва Контроллерҳо (Дар ҳоли омӯзиш)',
        '2.1 · Мигратсияҳо ва сохтани ҷадвалҳо (Вазифаи амалӣ)'
      ],
      priceLbl: 'Арзиши курс: Ройгон',
      benefitsTxt: 'Дастрасии 24/7, санҷиши вазифаҳо аз ҷониби муаллиф ва шаҳодатномаи расмии PDF бо QR-код.',
      enrollBtn: 'Ба курс номнавис шудан',
      goAssign: 'Супоридани вазифа',
      myCoursesBtn: 'Курсҳои ман',
      dashRole: 'Донишҷӯ',
      dashTitle: 'Пешрафти омӯзиш · Фарҳод Раҳимов',
      dashSub: 'Дарсҳо, вазифаҳо ва шаҳодатномаи худро дар Biyomuz Academy пайгирӣ кунед.',
      dashKpis: [
        ['Курсҳои фаъол', '3', 'Аз 4 курси номнависшуда'],
        ['Курсҳои хатмшуда', '1', 'Бомуваффақият анҷомёфта'],
        ['Шаҳодатнома', '1', 'BIYO-2026-001 (PDF)']
      ],
      dashCoursesTitle: 'Омӯзиши дарсҳо (FR-04, FR-05, FR-10)',
      openAssignBtn: 'Супоридани вазифа',
      openCourseBtn: 'Идомаи дарс →',
      assignBadge: 'Вазифаи амалӣ · Баҳои максималӣ: 100 хол',
      assignTitle: 'Супоридани вазифа · Курси «Кор бо Laravel»',
      assignDesc: 'Контроллери CourseController бо усулҳои index ва show созед ва файли ҳалро (PDF, ZIP, DOC, DOCX то 5 МБ) боргузорӣ кунед.',
      commentLbl: 'Матни ҷавоби шумо',
      commentVal: 'Контроллери CourseController сохта шуда, маршрутҳо санҷида шуданд.',
      commentHelp: 'Шарҳи кӯтоҳ ё истинод ба лоиҳаро ворид кунед',
      fileLbl: 'Замима кардани файл (то 5 МБ)',
      fileVal: 'course-controller-rahimov.zip (1.2 МБ)',
      fileHelp: 'Форматҳои иҷозатдодашуда: PDF, ZIP, DOC, DOCX (то 5 МБ)',
      errSampleLbl: 'Санҷиши хатогии андозаи файл (NFR-06)',
      errSampleVal: 'large-archive.zip (7.8 МБ)',
      submitBtn: 'Супоридани вазифа',
      authorBadge: 'Кабинети муаллиф',
      authorTitle: 'Кабинети муаллиф · Устод Аҳмад Раҳимов',
      authorSub: 'Сохтан ва идораи курсҳо, модулҳо, дарсҳо ва санҷиши вазифаҳои донишҷӯён.',
      authorNewCourseBtn: '+ Сохтани курси нав',
      authorKpis: [
        ['Курсҳои ман', '5', '3 нашршуда · 1 лоиҳа · 1 мунтазир'],
        ['Донишҷӯён', '17', 'Номнависшуда дар курсҳо'],
        ['Вазифаҳо барои санҷиш', '3', 'Дар интизори баҳогузорӣ']
      ],
      authorActionsTitle: 'Амалҳои фаврӣ (FR-07, FR-08, FR-11)',
      authorSubmitAction: 'Ирсоли курс ба модератсия',
      authorGradeAction: 'Санҷиши вазифаҳои донишҷӯён',
      authorCoursesTitle: 'Курсҳои ман (Author CRUD)',
      adminBadge: 'Идоракунии система · Маъмур',
      modTitle: 'Идоракунии система ва модератсияи курсҳо',
      modSub: 'Маъмур: Ғаффоров Зуҳриддин · Тасдиқ ё ради курсҳо, категорияҳо ва корбарон (FR-09)',
      modTabs: ['Ҳама', 'Мунтазир', 'Нашршуда', 'Радшуда'],
      modActionPrompt: 'Курси «Амнияти веб» дар интизори қарори маъмур:',
      approveBtn: 'Тасдиқ кардан',
      rejectBtn: 'Рад кардан',
      ovSentFrameName: 'Overlay · Вазифа супорида шуд',
      ovSentTitle: 'Вазифа бомуваффақият супорида шуд',
      ovSentBody: 'Файли course-controller-rahimov.zip захира гардид (дар интизори санҷиш). Устод Аҳмад Раҳимов кори шуморо месанҷад.',
      ovBackDash: 'Бозгашт ба кабинет',
      ovCloseBtn: 'Пӯшидан',
      ovRejFrameName: 'Overlay · Рад кардани курс',
      ovRejTitle: 'Курси «Амнияти веб» рад карда шавад?',
      ovRejBody: 'Сабаби рад карданро барои муаллиф (Синои Ҳасан) ворид кунед:',
      ovRejLbl: 'Сабаби рад кардан',
      ovRejPh: 'Дар модули 2 вазифаи амалӣ мавҷуд нест',
      ovRejHelp: 'Сабаби рад кардан ҳатмӣ аст',
      ovConfirmRej: 'Тасдиқи рад кардан',
      flowStartName: 'Роҳи корбар дар Biyomuz Academy (US-02 / US-03)'
    }
  },
  ru: {
    pages: ['01 Flow', '02 Wireframes', '03 UI-kit', '04 Mockups'],
    brand: 'Biyomuz Academy',
    slogan: 'Имрӯз биёмӯз, фардо муваффақ шав!',
    nav: [
      ['home', 'Главная'],
      ['courses', 'Курсы'],
      ['studentDash', 'Кабинет студента'],
      ['authorDash', 'Кабинет автора'],
      ['adminCourses', 'Управление системой']
    ],
    routes: {
      home: '/ (Главная)',
      login: '/login',
      courses: '/courses',
      course: '/courses/{course}',
      studentDash: '/student/dashboard',
      assignment: '/student/assignments/{assignment}',
      authorDash: '/author/dashboard',
      adminCourses: '/admin/courses',
      mobileCourses: '/courses — mobile (390×844)'
    },
    flow: {
      title: '01 User Flow · Biyomuz Academy (из User Stories и routes/web.php)',
      f1Title: 'Сценарий 1 (Студент · US-01 / US-02 / US-03): Просмотр, запись на курс, уроки и отправка задания',
      f1Steps: [
        ['Главная страница', '/'],
        ['Каталог курсов', '/courses'],
        ['Страница курса', '/courses/{course}'],
        ['Вход / Регистрация', '/login · /register'],
        ['[Записаться на курс]', 'POST /student/courses/{id}/enroll'],
        ['Изучение уроков', '/student/lessons/{lesson}'],
        ['Отправка задания', '/student/assignments/{id}'],
        ['[Отправить задание]', 'POST /student/assignments/{id}/submit'],
        ['Уведомление + Кабинет', '/student/dashboard (Overlay)']
      ],
      f2Title: 'Сценарий 2 (Автор и Админ · US-06 / US-04 / US-05): Создание курса, модули и модерация',
      f2Steps: [
        ['Вход в систему', '/login'],
        ['Кабинет автора', '/author/dashboard'],
        ['Создание курса', '/author/courses/create'],
        ['Модули и уроки', '/author/courses/{id}/modules'],
        ['[Отправить на проверку]', 'PATCH /author/courses/{id}/submit'],
        ['Проверка и модерация', '/admin/courses · /author/submissions'],
        ['[Одобрить / Отклонить]', 'PATCH /admin/courses/{id}/approve|reject'],
        ['Курс в каталоге', '/courses (Опубликован)']
      ]
    },
    kit: {
      title: '03 UI-kit · Biyomuz Academy Design System',
      sub: 'Палитра согласована с Bootstrap 5.3 (--bs-primary: #00875A) · Шрифт Noto Sans',
      colors: '1. Color Styles (14 Paint Styles + Variables для дня 10 / Bootstrap 5.3)',
      type: '2. Text Styles (5 стилей Noto Sans) и проверка полной кириллицы',
      typeSamples: {
        'text/h1':            'Biyomuz Academy — Обучение онлайн (Noto Sans)',
        'text/h2':            'Каталог курсов и управление системой',
        'text/body-semibold': 'Кор бо Laravel · Устод Аҳмад Раҳимов',
        'text/body':          'Запишитесь на курс бесплатно, проходите уроки и получайте сертификат PDF.'
      },
      cyrTest: 'Проверка кириллицы (Noto Sans): ё, й, ъ, щ, Й, Щ · ғ, ӣ, қ, ӯ, ҳ, ҷ · Ғаффоров Зуҳриддин, Бахтиёр, Неъматов, щука, йогурт',
      comps: '3. Компоненты (Button Variants + Text Property, Input 4 состояния, CourseCard Auto Layout)',
      longCardName: 'CourseCard · Проверка Auto Layout с длинным текстом',
      longTitle: 'Кор бо Laravel 13: Полная архитектура MVC, Eloquent ORM, очереди и деплой на сервер',
      longDesc: 'Подробный практический курс для начинающих и продвинутых разработчиков с проверкой заданий преподавателем и PDF-сертификатом.',
      a11y: '4. Проверка доступности (Accessibility · WCAG 2.1 AA >= 4.5:1)',
      a11yRows: [
        ['Белый текст (#FFFFFF) на color/primary (#00875A)', '4.55 : 1 · PASS (AA)', 'success'],
        ['Белый текст (#FFFFFF) на color/primary-dark (#064E3B)', '9.72 : 1 · PASS (AAA)', 'success'],
        ['color/muted (#64748B) на белом фоне (#FFFFFF)', '4.76 : 1 · PASS (AA)', 'success'],
        ['Белый на color/warning (#FFC107) — низкий контраст', '1.63 : 1 · FAIL → нужен тёмный текст', 'danger'],
        ['color/text (#1E293B) на color/warning (#FFC107)', '8.97 : 1 · PASS (AAA)', 'success']
      ],
      locTitle: '5. Локализация (Тоҷикӣ / Русский · GET /locale/{locale})',
      locSub: 'Основной язык интерфейса — таджикский; русский язык доступен как дополнительная локализация.',
      locRows: [
        ['Саҳифаи асосӣ · Курсҳо · Дар бораи мо', 'Главная · Каталог курсов · О нас'],
        ['Ба курс номнавис шудан · Курсро дидан', 'Записаться на курс · Посмотреть курс'],
        ['Пешрафти омӯзиш · Шаҳодатнома', 'Учебный прогресс · Сертификат PDF'],
        ['Супоридани вазифа · Кабинети муаллиф', 'Отправка задания · Кабинет автора']
      ],
      gridSpec: 'Сетка: Desktop 1440 = 12 колонок × 88 px, Center, Gutter 24 px (контент 1320 px · .container) | Mobile 390 = 4 колонки Stretch, Margin 16 px, Gutter 16 px'
    },
    courses: [
      { code: 'АЛГ', title: 'Асосҳои барномасозӣ', meta: 'Устод Аҳмад Раҳимов · Программирование · 2 модуля · 12 уроков · Бесплатно', desc: 'Введение в алгоритмы, блок-схемы и базовые структуры данных.', status: 'Опубликован', tone: 'success' },
      { code: 'PHP', title: 'Омӯзиши забони PHP', meta: 'Устод Аҳмад Раҳимов · Программирование · 2 модуля · 16 уроков · Бесплатно', desc: 'Полный курс PHP 8.5 от синтаксиса до работы с сессиями.', status: 'Опубликован', tone: 'success' },
      { code: 'LAR', title: 'Кор бо Laravel', meta: 'Устод Аҳмад Раҳимов · Программирование · 2 модуля · 18 уроков · Бесплатно', desc: 'Разработка веб-приложений на Laravel 13, Eloquent ORM и MVC.', status: 'Опубликован', tone: 'success' },
      { code: 'UX',  title: 'Тарҳрезии вебсайт', meta: 'Шаҳром Саидов · Веб-дизайн · 1 модуль · 10 уроков · Бесплатно', desc: 'Принципы UI/UX дизайна, Auto Layout и прототипирование в Figma.', status: 'Опубликован', tone: 'success' },
      { code: 'SQL', title: 'Пойгоҳи додаҳои MySQL', meta: 'Синои Ҳасан · Базы данных · 2 модуля · 12 уроков · Бесплатно', desc: 'Проектирование БД до 3НФ и сложные запросы SQL в MariaDB.', status: 'Опубликован', tone: 'success' },
      { code: 'SEC', title: 'Амнияти веб', meta: 'Синои Ҳасан · Безопасность · 2 модуля · 8 уроков · Бесплатно', desc: 'Защита веб-приложений от SQL Injection, XSS и CSRF.', status: 'На проверке', tone: 'warning' }
    ],
    ui: {
      langPill: '🇷🇺 Русский',
      mobLangToggle: '🇷🇺 · ☰',
      logoutBtn: 'Выйти',
      btnSave: 'Сохранить',
      btnCancel: 'Отмена',
      btnDelete: 'Удалить',
      inLabel: 'Электронная почта (E-mail)',
      inPh: 'farhod@example.tj',
      inHelp: 'Введите e-mail, указанный при регистрации',
      inErr: 'Размер файла превышает 5 МБ (PDF, ZIP, DOCX)',
      cardViewBtn: 'Посмотреть курс',
      heroBadge: '🎓 Национальная платформа онлайн-обучения',
      heroTitle: 'Biyomuz Academy',
      heroSlogan: '«Имрӯз биёмӯз, фардо муваффақ шав!»',
      heroDesc: 'Профессиональные курсы от лучших преподавателей, пошаговые уроки, практические задания и официальный PDF-сертификат.',
      heroCatalogBtn: 'Каталог курсов →',
      heroLoginBtn: 'Регистрация',
      stats: [
        ['8', 'Опубликованных курсов'],
        ['4', 'Категории'],
        ['16', 'Студентов'],
        ['3', 'PDF-сертификата']
      ],
      catChips: ['Барномасозӣ (7)', 'Веб-дизайн (2)', 'Пойгоҳи додаҳо (1)', 'Амнияти иттилоотӣ (0)'],
      popularTitle: 'Популярные курсы',
      footerSlogan: '«Имрӯз биёмӯз, фардо муваффақ шав!»',
      footerDesc: 'Современная платформа онлайн-обучения с профессиональными курсами и официальными сертификатами.',
      footerLinksTitle: 'Полезные ссылки',
      footerLinks: ['Каталог курсов', 'О нас', 'Контакты', 'Регистрация'],
      footerContactTitle: 'Контакты',
      footerContacts: ['info@biyomuz.tj', 'г. Худжанд / г. Душанбе, Таджикистан', '24/7 Онлайн'],
      footerCopy: '© 2026 Biyomuz Academy. Все права защищены.',
      footerLangSwitch: 'Тоҷикӣ • Русский',
      loginTitle: 'Вход в Biyomuz Academy',
      loginSub: 'Введите учётные данные студента, автора или администратора',
      emailLbl: 'E-mail адрес', emailVal: 'farhod@example.tj',
      passLbl: 'Пароль', passVal: '••••••••',
      passHelp: 'Минимум 8 символов',
      loginBtn: 'Войти',
      noAccount: 'Нет аккаунта? Регистрация →',
      catTitle: 'Каталог курсов',
      filtersTitle: 'Фильтры',
      catLbl: 'Категория', catVal: 'Все категории (4)',
      lvlLbl: 'Уровень', lvlVal: 'Все уровни',
      searchLbl: 'Поиск курсов', searchPh: 'Например, Laravel или PHP',
      applyFilter: 'Поиск',
      addCourse: '+ Новый курс',
      pageInfo: 'Показано 8 опубликованных курсов · Страница 1 из 2',
      backCatalog: '← Назад',
      courseHeroMeta: 'Автор: Устод Аҳмад Раҳимов · 3 студента · 2 модуля · 18 уроков',
      modulesTitle: 'Уроки и содержание курса (FR-05)',
      modulesList: [
        '1.1 · Введение в Laravel 13 и Artisan (Завершён)',
        '1.2 · Маршрутизация и Контроллеры (В процессе)',
        '2.1 · Миграции и создание таблиц (Практическое задание)'
      ],
      priceLbl: 'Стоимость курса: Бесплатно',
      benefitsTxt: 'Доступ 24/7, проверка заданий преподавателем и официальный PDF-сертификат.',
      enrollBtn: 'Записаться на курс',
      goAssign: 'Перейти к заданию',
      myCoursesBtn: 'Мои курсы',
      dashRole: 'Студент',
      dashTitle: 'Учебный прогресс · Фарҳод Раҳимов',
      dashSub: 'Отслеживайте уроки, задания и сертификаты в Biyomuz Academy.',
      dashKpis: [
        ['Активные курсы', '3', 'Из 4 записанных курсов'],
        ['Завершено курсов', '1', 'Успешно пройдено'],
        ['Сертификат', '1', 'BIYO-2026-001 (PDF)']
      ],
      dashCoursesTitle: 'Изучение уроков (FR-04, FR-05, FR-10)',
      openAssignBtn: 'Отправить задание',
      openCourseBtn: 'Продолжить урок →',
      assignBadge: 'Практическое задание · Макс. оценка: 100 баллов',
      assignTitle: 'Отправка задания · Курс «Кор бо Laravel»',
      assignDesc: 'Создайте CourseController с методами index и show и прикрепите файл решения (PDF, ZIP, DOC, DOCX до 5 МБ).',
      commentLbl: 'Ваш ответ',
      commentVal: 'Контроллер CourseController создан, маршруты проверены.',
      commentHelp: 'Введите пояснение или ссылку на проект',
      fileLbl: 'Файл ответа (до 5 МБ)',
      fileVal: 'course-controller-rahimov.zip (1.2 МБ)',
      fileHelp: 'Допустимые форматы: PDF, ZIP, DOC, DOCX (до 5 МБ)',
      errSampleLbl: 'Пример ошибки размера файла (NFR-06)',
      errSampleVal: 'large-archive.zip (7.8 МБ)',
      submitBtn: 'Отправить задание',
      authorBadge: 'Кабинет автора',
      authorTitle: 'Кабинет автора · Устод Аҳмад Раҳимов',
      authorSub: 'Создание и управление курсами, модулями, уроками и проверка заданий студентов.',
      authorNewCourseBtn: '+ Создать курс',
      authorKpis: [
        ['Мои курсы', '5', '3 опубликовано · 1 черновик · 1 на проверке'],
        ['Студенты', '17', 'Записаны на курсы'],
        ['Задания на проверку', '3', 'Ожидают оценки']
      ],
      authorActionsTitle: 'Быстрые действия (FR-07, FR-08, FR-11)',
      authorSubmitAction: 'Отправить курс на модерацию',
      authorGradeAction: 'Проверка заданий студентов',
      authorCoursesTitle: 'Мои курсы (Author CRUD)',
      adminBadge: 'Управление системой · Администратор',
      modTitle: 'Управление системой и модерация курсов',
      modSub: 'Админ: Ғаффоров Зуҳриддин · Одобрение и отклонение курсов (FR-09)',
      modTabs: ['Все', 'Ожидает', 'Опубликованные', 'Отклонённые'],
      modActionPrompt: 'Курс «Амнияти веб» ожидает решения модератора:',
      approveBtn: 'Одобрить',
      rejectBtn: 'Отклонить',
      ovSentFrameName: 'Overlay · Задание отправлено',
      ovSentTitle: 'Задание отправлено на проверку',
      ovSentBody: 'Файл course-controller-rahimov.zip сохранён. Преподаватель Устод Аҳмад Раҳимов проверит работу.',
      ovBackDash: 'Вернуться в кабинет',
      ovCloseBtn: 'Закрыть',
      ovRejFrameName: 'Overlay · Отклонить курс',
      ovRejTitle: 'Отклонить курс «Амнияти веб»?',
      ovRejBody: 'Укажите причину отклонения для автора (Синои Ҳасан):',
      ovRejLbl: 'Причина отклонения',
      ovRejPh: 'Отсутствует практическое задание в модуле 2',
      ovRejHelp: 'Комментарий обязателен при отклонении',
      ovConfirmRej: 'Подтвердить отклонение',
      flowStartName: 'Пользовательский путь в Biyomuz Academy (US-02 / US-03)'
    }
  }
};

// ---------------------------------------------------------------- Color Palette (14 Styles aligned with resources/css/app.css + Bootstrap 5.3)
var COLOR_DEFS = [
  ['color/primary',       '#00875A', '--bs-primary',               'Тугмаҳои асосӣ, пайвандҳо, фокус ва бренди Biyomuz Academy'],
  ['color/primary-dark',  '#064E3B', '--bs-primary-text-emphasis', 'Заминаи баннери Hero, поёни саҳифа (Footer) ва сарлавҳаи торики бренд'],
  ['color/primary-hover', '#006B47', '--bs-btn-hover-bg',          'Ҳолати Hover-и тугмаи Primary (10% ториктар)'],
  ['color/primary-soft',  '#ECFDF5', '--bs-primary-bg-subtle',     'Заминаи сабуки нишонҳо, пайвандҳои фаъол ва барчаспҳо'],
  ['color/success',       '#198754', '--bs-success',               'Ҳолатҳои Нашршуда, Тасдиқ шуд ва Хатм шуд'],
  ['color/warning',       '#FFC107', '--bs-warning',               'Ҳолати Мунтазир (pending) — танҳо бо матни торики color/text'],
  ['color/danger',        '#DC3545', '--bs-danger',                'Хатогиҳои санҷиш, Радшуда ва тугмаҳои Рад кардан / Нест кардан'],
  ['color/danger-hover',  '#B02A37', '--bs-btn-danger-hover-bg',   'Ҳолати Hover-и тугмаи Danger'],
  ['color/text',          '#1E293B', '--bs-body-color',            'Матни асосӣ ва матн дар рӯйи color/warning'],
  ['color/muted',         '#64748B', '--bs-secondary',             'Матни ёрирасон, номи муаллиф ва метамаълумоти курс'],
  ['color/border',        '#DEE2E6', '--bs-border-color',          'Ҳошияи кортҳо, майдонҳои воридот ва ҷадвалҳо'],
  ['color/bg',            '#F8FAFC', '--bs-light',                 'Заминаи умумии саҳифаҳои Biyomuz Academy'],
  ['color/surface',       '#FFFFFF', '--bs-white',                 'Сатҳи кортҳо, равзанаҳо ва матни сафед дар рӯйи рангҳои торик'],
  ['color/surface-hover', '#F1F5F9', '--bs-gray-100',              'Ҳолати Hover-и тугмаи Secondary']
];

var HEX = {};
COLOR_DEFS.forEach(function (d) { HEX[d[0]] = d[1]; });
var GRAY = { dark: '#475569', mid: '#94A3B8', line: '#CBD5E1', light: '#E2E8F0', bg: '#F1F5F9', text: '#334155', hint: '#64748B', white: '#FFFFFF' };

var PS = {}, TS = {}, V = {}, FONT = null;
var C = { btn: {}, btnProps: null, input: {}, card: null };
var L;

function rgb(hex) { var n = parseInt(hex.slice(1), 16); return { r: ((n >> 16) & 255) / 255, g: ((n >> 8) & 255) / 255, b: (n & 255) / 255 }; }
function solid(hex) { return { type: 'SOLID', color: rgb(hex) }; }
function def(v, d) { return v === undefined ? d : v; }

async function resolveFonts() {
  var candidates = [
    { family: 'Noto Sans', regular: 'Regular', semibold: 'SemiBold', bold: 'Bold' },
    { family: 'Noto Sans', regular: 'Regular', semibold: 'Semi Bold', bold: 'Bold' }
  ];
  try {
    if (typeof figma.listAvailableFontsAsync === 'function') {
      var available = await figma.listAvailableFontsAsync();
      var notoStyles = [];
      for (var k = 0; k < available.length; k++) {
        if (available[k].fontName && available[k].fontName.family === 'Noto Sans') {
          notoStyles.push(available[k].fontName.style);
        }
      }
      if (notoStyles.length > 0) {
        var pickStyle = function (preferred) {
          for (var p = 0; p < preferred.length; p++) {
            if (notoStyles.indexOf(preferred[p]) !== -1) return preferred[p];
          }
          return null;
        };
        var reg = pickStyle(['Regular', 'Book', 'Normal']) || notoStyles[0];
        var semi = pickStyle(['SemiBold', 'Semi Bold', 'Medium', 'Bold']) || reg;
        var bold = pickStyle(['Bold', 'SemiBold', 'Semi Bold']) || semi;
        candidates.unshift({ family: 'Noto Sans', regular: reg, semibold: semi, bold: bold });
      }
    }
  } catch (e) {}
  for (var i = 0; i < candidates.length; i++) {
    var c = candidates[i];
    try {
      await figma.loadFontAsync({ family: c.family, style: c.regular });
      await figma.loadFontAsync({ family: c.family, style: c.semibold });
      await figma.loadFontAsync({ family: c.family, style: c.bold });
      return {
        family: c.family,
        Regular:  { family: c.family, style: c.regular },
        SemiBold: { family: c.family, style: c.semibold },
        Bold:     { family: c.family, style: c.bold }
      };
    } catch (e) {}
  }
  throw new Error('Шрифти Noto Sans дар Figma ёфт нашуд. Барои сохтани 5 Text Style танҳо шрифти Noto Sans лозим аст.');
}

async function applyFill(node, styleKey) {
  if (!styleKey) { node.fills = []; return; }
  var ps = PS[styleKey];
  if (ps) {
    try {
      if (node.setFillStyleIdAsync) await node.setFillStyleIdAsync(ps.id);
      else node.fillStyleId = ps.id;
      return;
    } catch (e) {}
  }
  node.fills = [solid(HEX[styleKey] || '#000000')];
}

async function applyStroke(node, styleKey, weight) {
  if (!styleKey) { node.strokes = []; return; }
  node.strokeWeight = def(weight, 1);
  node.strokeAlign = 'INSIDE';
  var ps = PS[styleKey];
  if (ps) {
    try {
      if (node.setStrokeStyleIdAsync) await node.setStrokeStyleIdAsync(ps.id);
      else node.strokeStyleId = ps.id;
      return;
    } catch (e) {}
  }
  node.strokes = [solid(HEX[styleKey] || '#CBD5E1')];
}

async function makeVariablesAndPaintStyles() {
  var existingPS = {};
  try {
    var curPS = figma.getLocalPaintStylesAsync ? await figma.getLocalPaintStylesAsync() : (figma.getLocalPaintStyles ? figma.getLocalPaintStyles() : []);
    curPS.forEach(function (s) { existingPS[s.name] = s; });
  } catch (e) {}

  COLOR_DEFS.forEach(function (d) {
    var ps = existingPS[d[0]] || figma.createPaintStyle();
    ps.name = d[0];
    ps.paints = [solid(d[1])];
    PS[d[0]] = ps;
  });

  try {
    var curCols = figma.variables.getLocalVariableCollectionsAsync ? await figma.variables.getLocalVariableCollectionsAsync() : [];
    var col = null;
    for (var i = 0; i < curCols.length; i++) { if (curCols[i].name === 'Biyomuz Academy') { col = curCols[i]; break; } }
    if (!col) col = figma.variables.createVariableCollection('Biyomuz Academy');
    var mode = col.modes[0].modeId;
    try { col.renameMode(mode, 'Light'); } catch (e) {}

    var existingVars = {};
    try {
      var allVars = figma.variables.getLocalVariablesAsync ? await figma.variables.getLocalVariablesAsync() : [];
      allVars.forEach(function (v) { if (v.variableCollectionId === col.id) existingVars[v.name] = v; });
    } catch (e) {}

    var mk = function (name, type) {
      if (existingVars[name]) return existingVars[name];
      try { return figma.variables.createVariable(name, col, type); }
      catch (e) { return figma.variables.createVariable(name, col.id, type); }
    };
    COLOR_DEFS.forEach(function (d) {
      var c = rgb(d[1]);
      var vr = mk(d[0], 'COLOR');
      vr.setValueForMode(mode, { r: c.r, g: c.g, b: c.b, a: 1 });
      V[d[0]] = vr;
    });
    [4, 8, 16, 24, 32, 48].forEach(function (sp) { mk('space/' + sp, 'FLOAT').setValueForMode(mode, sp); });
    [6, 8, 999].forEach(function (rd) { mk('radius/' + rd, 'FLOAT').setValueForMode(mode, rd); });
  } catch (e) { console.warn('Variables fallback:', e); }
}

var TEXT_STYLE_DEFS = [
  ['text/h1',            'SemiBold', 32, 40],
  ['text/h2',            'SemiBold', 24, 32],
  ['text/body-semibold', 'SemiBold', 16, 24],
  ['text/body',          'Regular',  16, 24],
  ['text/small',         'Regular',  14, 20]
];

async function makeTextStyles() {
  var existingTS = {};
  try {
    var curTS = figma.getLocalTextStylesAsync ? await figma.getLocalTextStylesAsync() : (figma.getLocalTextStyles ? figma.getLocalTextStyles() : []);
    curTS.forEach(function (s) { existingTS[s.name] = s; });
  } catch (e) {}

  TEXT_STYLE_DEFS.forEach(function (d) {
    var ts = existingTS[d[0]] || figma.createTextStyle();
    ts.name = d[0];
    ts.fontName = FONT[d[1]];
    ts.fontSize = d[2];
    ts.lineHeight = { value: d[3], unit: 'PIXELS' };
    TS[d[0]] = ts;
  });
}

// ---------------------------------------------------------------- Auto Layout Helper
async function al(name, dir, o) {
  o = o || {};
  var f = o.component ? figma.createComponent() : figma.createFrame();
  f.name = name;
  f.layoutMode = dir;
  f.itemSpacing = def(o.gap, 0);
  var p = def(o.pad, 0);
  if (typeof p === 'number') p = [p, p, p, p];
  else if (p.length === 2) p = [p[0], p[1], p[0], p[1]];
  f.paddingTop = p[0]; f.paddingRight = p[1]; f.paddingBottom = p[2]; f.paddingLeft = p[3];
  f.primaryAxisSizingMode = 'AUTO';
  f.counterAxisSizingMode = 'AUTO';
  f.counterAxisAlignItems = def(o.align, 'MIN');
  f.primaryAxisAlignItems = def(o.justify, 'MIN');
  if (o.radius !== undefined) f.cornerRadius = o.radius;
  f.clipsContent = !!o.clip;

  if (o.fillStyle) await applyFill(f, o.fillStyle);
  else if (o.fillHex) f.fills = [solid(o.fillHex)];
  else f.fills = [];

  if (o.strokeStyle) await applyStroke(f, o.strokeStyle, o.strokeWeight);
  else if (o.strokeHex) { f.strokes = [solid(o.strokeHex)]; f.strokeWeight = def(o.strokeWeight, 1); f.strokeAlign = 'INSIDE'; }
  if (o.dashed) f.dashPattern = [6, 4];

  if (o.w || o.h) {
    f.resize(def(o.w, 100), def(o.h, 100));
    if (o.w) { if (dir === 'HORIZONTAL') f.primaryAxisSizingMode = 'FIXED'; else f.counterAxisSizingMode = 'FIXED'; }
    if (o.h) { if (dir === 'VERTICAL') f.primaryAxisSizingMode = 'FIXED'; else f.counterAxisSizingMode = 'FIXED'; }
  }
  if (o.wrap) { f.layoutWrap = 'WRAP'; f.counterAxisSpacing = def(o.wrapGap, f.itemSpacing); }
  if (o.parent) o.parent.appendChild(f);
  if (o.fillW && f.parent && f.parent.layoutMode && f.parent.layoutMode !== 'NONE') {
    f.layoutSizingHorizontal = 'FILL';
  }
  return f;
}

function fill(n, horiz) {
  if (n && n.parent && n.parent.layoutMode && n.parent.layoutMode !== 'NONE' && horiz) {
    n.layoutSizingHorizontal = 'FILL';
  }
  return n;
}
function wrapText(t) {
  t.textAutoResize = 'HEIGHT';
  return fill(t, true);
}
function fixedText(t, w) {
  t.textAutoResize = 'HEIGHT';
  t.resize(w, Math.max(t.height, 1));
  return t;
}

async function T(str, textStyleKey, colorStyleKey, parent, name) {
  var t = figma.createText();
  var s = TS[textStyleKey];
  if (s) {
    if (t.setTextStyleIdAsync) await t.setTextStyleIdAsync(s.id);
    else t.textStyleId = s.id;
  } else {
    t.fontName = FONT.Regular; t.fontSize = 16;
  }
  t.characters = String(str);
  if (colorStyleKey) await applyFill(t, colorStyleKey);
  if (name) t.name = name;
  if (parent) parent.appendChild(t);
  return t;
}

function W(str, size, hex, parent, bold) {
  var t = figma.createText();
  t.fontName = bold ? FONT.SemiBold : FONT.Regular;
  t.characters = String(str);
  t.fontSize = size;
  t.fills = [solid(hex || GRAY.text)];
  if (parent) parent.appendChild(t);
  return t;
}

function rect(name, w, h, fillPaint, radius, parent) {
  var r = figma.createRectangle();
  r.name = name; r.resize(w, h); r.fills = [fillPaint];
  if (radius) r.cornerRadius = radius;
  if (parent) parent.appendChild(r);
  return r;
}

function setExportPng1x(frame) {
  try {
    frame.exportSettings = [{ format: 'PNG', constraint: { type: 'SCALE', value: 1 }, contentsOnly: true }];
  } catch (e) {}
}

function setGridDesktop(frame) {
  // 12 columns × 88 px, Center, Gutter 24 px => 12*88 + 11*24 = 1320 px (.container Bootstrap)
  frame.layoutGrids = [{
    pattern: 'COLUMNS', alignment: 'CENTER',
    sectionSize: 88, gutterSize: 24, count: 12,
    visible: true, color: { r: 0.86, g: 0.21, b: 0.27, a: 0.08 }
  }];
}

function setGridMobile(frame) {
  // 4 columns, Stretch, Margin 16 px, Gutter 16 px
  frame.layoutGrids = [{
    pattern: 'COLUMNS', alignment: 'STRETCH',
    offset: 16, gutterSize: 16, count: 4,
    visible: true, color: { r: 0.86, g: 0.21, b: 0.27, a: 0.08 }
  }];
}

function gridVariants(set, list, cols, gap) {
  var colW = 0, rowH = 0;
  list.forEach(function (n) { colW = Math.max(colW, n.width); rowH = Math.max(rowH, n.height); });
  list.forEach(function (n, i) { n.x = gap + (i % cols) * (colW + gap); n.y = gap + Math.floor(i / cols) * (rowH + gap); });
  set.resize(gap + cols * (colW + gap), gap + Math.ceil(list.length / cols) * (rowH + gap));
}

// ---------------------------------------------------------------- Page 01: User Flow
async function buildFlowPage(page) {
  var root = await al('User Flows · Biyomuz Academy', 'VERTICAL', { pad: 48, gap: 32, fillStyle: 'color/surface', parent: page });
  root.x = 0; root.y = 0;
  await T(L.flow.title, 'text/h1', 'color/text', root);

  var renderFlow = async function (title, steps, accentStyle) {
    var box = await al(title, 'VERTICAL', { pad: 24, gap: 20, radius: 12, fillStyle: 'color/bg', strokeStyle: 'color/border', parent: root });
    await T(title, 'text/h2', accentStyle, box);
    var row = await al('Flow Steps', 'HORIZONTAL', { gap: 12, align: 'CENTER', wrap: true, w: 1320, parent: box });
    for (var i = 0; i < steps.length; i++) {
      var st = steps[i], isAct = st[0].charAt(0) === '[';
      var node = await al(st[1], 'VERTICAL', {
        pad: [12, 16], gap: 4, radius: 8,
        fillStyle: isAct ? accentStyle : 'color/surface',
        strokeStyle: accentStyle, strokeWeight: isAct ? 0 : 1.5, parent: row
      });
      await T(st[0], 'text/body-semibold', isAct ? 'color/surface' : 'color/text', node);
      await T(st[1], 'text/small', isAct ? 'color/surface' : 'color/muted', node);
      if (i < steps.length - 1) {
        await T('→', 'text/h2', accentStyle, row, 'Arrow');
      }
    }
  };

  await renderFlow(L.flow.f1Title, L.flow.f1Steps, 'color/primary');
  await renderFlow(L.flow.f2Title, L.flow.f2Steps, 'color/success');
}

// ---------------------------------------------------------------- Page 03: UI-kit & Components
async function makeButtonSet(parent) {
  var defs = {
    Primary:   { def: 'color/primary', hov: 'color/primary-hover', txt: 'color/surface', stroke: null },
    Secondary: { def: 'color/surface', hov: 'color/surface-hover', txt: 'color/text',    stroke: 'color/border' },
    Danger:    { def: 'color/danger',  hov: 'color/danger-hover',  txt: 'color/surface', stroke: null }
  };
  var labels = { Primary: L.ui.btnSave, Secondary: L.ui.btnCancel, Danger: L.ui.btnDelete };
  var states = ['Default', 'Hover', 'Disabled'];
  var list = [];

  for (var tp in defs) {
    for (var i = 0; i < states.length; i++) {
      var st = states[i], d = defs[tp];
      var c = await al('Type=' + tp + ', State=' + st, 'HORIZONTAL', {
        component: true, pad: [10, 16], gap: 8, radius: 6, h: 44, align: 'CENTER', justify: 'CENTER',
        fillStyle: st === 'Hover' ? d.hov : d.def, strokeStyle: d.stroke
      });
      c.primaryAxisSizingMode = 'AUTO';
      c.counterAxisSizingMode = 'FIXED';
      c.resize(c.width, 44);
      try { c.minHeight = 44; } catch (e) {}
      await T(labels[tp], 'text/body', d.txt, c, 'Label');
      if (st === 'Disabled') c.opacity = 0.5;
      C.btn[tp + '/' + st] = c;
      list.push(c);
    }
  }
  var set = figma.combineAsVariants(list, parent);
  set.name = 'Button';
  gridVariants(set, list, 3, 24);
  try {
    var pk = set.addComponentProperty('Label', 'TEXT', L.ui.btnSave);
    C.btnProps = pk;
    list.forEach(function (comp) {
      var t = comp.findOne(function (n) { return n.type === 'TEXT' && n.name === 'Label'; });
      if (t) t.componentPropertyReferences = { characters: pk };
    });
  } catch (e) { console.warn('Button Label prop fallback:', e); }
}

async function makeInputSet(parent) {
  var states = ['Default', 'Focus', 'Error', 'Disabled'];
  var list = [];
  for (var i = 0; i < states.length; i++) {
    var st = states[i];
    var comp = await al('State=' + st, 'VERTICAL', { component: true, gap: 4, w: 320 });
    await T(L.ui.inLabel, 'text/small', 'color/text', comp, 'LabelText');

    var box = await al('Field', 'HORIZONTAL', {
      pad: [0, 12], gap: 8, radius: 6, h: 44, align: 'CENTER',
      fillStyle: st === 'Disabled' ? 'color/bg' : 'color/surface',
      strokeStyle: st === 'Focus' ? 'color/primary' : (st === 'Error' ? 'color/danger' : 'color/border'),
      strokeWeight: st === 'Focus' ? 2 : 1, parent: comp
    });
    fill(box, true);
    var txt = await T(st === 'Default' ? L.ui.inPh : L.ui.emailVal, 'text/body', st === 'Default' ? 'color/muted' : 'color/text', box, 'FieldText');
    fill(txt, true);

    var helper = st === 'Error' ? L.ui.inErr : L.ui.inHelp;
    var ht = await T(helper, 'text/small', st === 'Error' ? 'color/danger' : 'color/muted', comp, 'HelperText');
    wrapText(ht);

    if (st === 'Disabled') comp.opacity = 0.5;
    C.input[st] = comp;
    list.push(comp);
  }
  var set = figma.combineAsVariants(list, parent);
  set.name = 'Input';
  gridVariants(set, list, 2, 24);
}

async function makeCourseCard(parent) {
  var buildCardVariant = async function (sizeName, dir, w) {
    var cd = await al('Size=' + sizeName, dir, {
      component: true, pad: 16, gap: 16, radius: 8, w: w,
      align: dir === 'HORIZONTAL' ? 'CENTER' : 'MIN',
      fillStyle: 'color/surface', strokeStyle: 'color/border'
    });
    if (dir === 'HORIZONTAL') {
      var thumb = await al('Thumb', 'HORIZONTAL', { w: 64, h: 64, radius: 8, align: 'CENTER', justify: 'CENTER', fillStyle: 'color/primary', parent: cd });
      await T('LAR', 'text/body-semibold', 'color/surface', thumb, 'CodeText');
      var stack = await al('Details', 'VERTICAL', { gap: 4, parent: cd });
      fill(stack, true);
      wrapText(await T(L.courses[2].title, 'text/body-semibold', 'color/text', stack, 'CardTitle'));
      wrapText(await T(L.courses[2].meta, 'text/small', 'color/muted', stack, 'CardMeta'));
      wrapText(await T(L.courses[2].desc, 'text/small', 'color/text', stack, 'CardDesc'));

      var rightCol = await al('CardActions', 'VERTICAL', { gap: 8, align: 'MAX', parent: cd });
      var badge = await al('StatusBadge', 'HORIZONTAL', { pad: [4, 8], radius: 999, align: 'CENTER', fillStyle: 'color/success', parent: rightCol });
      await T(L.courses[2].status, 'text/small', 'color/surface', badge, 'CardStatus');
      makeBtnInst('Secondary', 'Default', L.ui.cardViewBtn, rightCol, 'CardActionBtn', false);
    } else {
      var top = await al('TopRow', 'HORIZONTAL', { gap: 12, align: 'CENTER', justify: 'SPACE_BETWEEN', parent: cd }); fill(top, true);
      var mthumb = await al('Thumb', 'HORIZONTAL', { w: 48, h: 48, radius: 8, align: 'CENTER', justify: 'CENTER', fillStyle: 'color/primary', parent: top });
      await T('LAR', 'text/body-semibold', 'color/surface', mthumb, 'CodeText');
      var mbadge = await al('StatusBadge', 'HORIZONTAL', { pad: [4, 8], radius: 999, align: 'CENTER', fillStyle: 'color/success', parent: top });
      await T(L.courses[2].status, 'text/small', 'color/surface', mbadge, 'CardStatus');
      var mstack = await al('Details', 'VERTICAL', { gap: 4, parent: cd }); fill(mstack, true);
      wrapText(await T(L.courses[2].title, 'text/body-semibold', 'color/text', mstack, 'CardTitle'));
      wrapText(await T(L.courses[2].meta, 'text/small', 'color/muted', mstack, 'CardMeta'));
      wrapText(await T(L.courses[2].desc, 'text/small', 'color/text', mstack, 'CardDesc'));
      makeBtnInst('Secondary', 'Default', L.ui.cardViewBtn, cd, 'CardActionBtn', true);
    }
    return cd;
  };

  var desktopCard = await buildCardVariant('Desktop', 'HORIZONTAL', 640);
  var mobileCard  = await buildCardVariant('Mobile',  'VERTICAL',   358);
  var set = figma.combineAsVariants([desktopCard, mobileCard], parent);
  set.name = 'CourseCard';
  gridVariants(set, [desktopCard, mobileCard], 2, 24);
  C.card = { Desktop: desktopCard, Mobile: mobileCard };
}

async function buildUiKitPage(page) {
  var root = await al('03 UI-kit · Biyomuz Academy', 'VERTICAL', { pad: 48, gap: 32, fillStyle: 'color/surface', parent: page });
  root.x = 0; root.y = 0;

  var hdr = await al('Header Banner', 'VERTICAL', { pad: 24, gap: 8, radius: 12, w: 1240, fillStyle: 'color/primary-dark', parent: root });
  await T(L.kit.title, 'text/h1', 'color/surface', hdr);
  await T(L.kit.sub + ' · ' + L.slogan, 'text/body', 'color/surface', hdr);
  await T(L.kit.gridSpec, 'text/small', 'color/surface', hdr);

  // 1. Color Styles
  var secCol = await al('Color Styles', 'VERTICAL', { gap: 16, parent: root });
  await T(L.kit.colors, 'text/h2', 'color/text', secCol);
  var swGrid = await al('Swatches Grid', 'HORIZONTAL', { gap: 16, wrap: true, w: 1240, parent: secCol });
  for (var i = 0; i < COLOR_DEFS.length; i++) {
    var cd = COLOR_DEFS[i];
    var sw = await al(cd[0], 'VERTICAL', { w: 290, radius: 8, strokeStyle: 'color/border', fillStyle: 'color/surface', clip: true, parent: swGrid });
    var chip = rect('Chip', 290, 56, solid(cd[1]), 0, sw);
    await applyFill(chip, cd[0]);
    fill(chip, true);
    var mt = await al('Meta', 'VERTICAL', { pad: 12, gap: 4, parent: sw }); fill(mt, true);
    await T(cd[0] + ' · ' + cd[1], 'text/body-semibold', 'color/text', mt);
    await T('Bootstrap: ' + cd[2], 'text/small', 'color/primary', mt);
    wrapText(await T(cd[3], 'text/small', 'color/muted', mt));
  }

  // 2. Text Styles
  var secTxt = await al('Typography', 'VERTICAL', { gap: 16, parent: root });
  await T(L.kit.type, 'text/h2', 'color/text', secTxt);
  var tBox = await al('Type Box', 'VERTICAL', { pad: 20, gap: 12, radius: 8, w: 1240, fillStyle: 'color/bg', strokeStyle: 'color/border', parent: secTxt });
  var samples = {
    'text/h1':            L.kit.typeSamples['text/h1'],
    'text/h2':            L.kit.typeSamples['text/h2'],
    'text/body-semibold': L.kit.typeSamples['text/body-semibold'],
    'text/body':          L.kit.typeSamples['text/body'],
    'text/small':         L.kit.cyrTest
  };
  for (i = 0; i < TEXT_STYLE_DEFS.length; i++) {
    var td = TEXT_STYLE_DEFS[i];
    var row = await al(td[0], 'HORIZONTAL', { gap: 24, align: 'CENTER', parent: tBox }); fill(row, true);
    fixedText(await T(td[0] + ' (' + td[2] + '/' + td[3] + ', ' + td[1] + ')', 'text/small', 'color/muted', row), 280);
    await T(samples[td[0]], td[0], 'color/text', row);
  }

  // 3. Components
  var secComp = await al('Components', 'VERTICAL', { gap: 24, parent: root });
  await T(L.kit.comps, 'text/h2', 'color/text', secComp);
  var compRow = await al('Button & Input', 'HORIZONTAL', { gap: 32, wrap: true, w: 1240, parent: secComp });
  await makeButtonSet(compRow);
  await makeInputSet(compRow);

  await makeCourseCard(secComp);
  // Long-text Auto Layout proof instance
  var longTest = C.card.Desktop.createInstance();
  longTest.name = L.kit.longCardName;
  await setCardData(longTest, { code: 'LAR', title: L.kit.longTitle, meta: L.courses[2].meta, desc: L.kit.longDesc, status: L.courses[2].status, tone: 'success' });
  secComp.appendChild(longTest);

  // 4. Accessibility
  var secA11y = await al('Accessibility', 'VERTICAL', { gap: 12, w: 1240, parent: root });
  await T(L.kit.a11y, 'text/h2', 'color/text', secA11y);
  for (i = 0; i < L.kit.a11yRows.length; i++) {
    var ar = L.kit.a11yRows[i];
    var arRow = await al('Contrast Pair', 'HORIZONTAL', { pad: [12, 16], gap: 16, radius: 8, align: 'CENTER', fillStyle: 'color/bg', strokeStyle: 'color/border', parent: secA11y });
    fill(arRow, true);
    fill(await T(ar[0], 'text/body', 'color/text', arRow), true);
    await T(ar[1], 'text/body-semibold', 'color/' + ar[2], arRow);
  }

  // 5. Localization Showcase (Тоҷикӣ / Русский)
  var secLoc = await al('Localization Showcase', 'VERTICAL', { gap: 12, w: 1240, parent: root });
  await T(L.kit.locTitle, 'text/h2', 'color/text', secLoc);
  await T(L.kit.locSub, 'text/body', 'color/muted', secLoc, null, true);
  for (i = 0; i < L.kit.locRows.length; i++) {
    var lr = L.kit.locRows[i];
    var lrRow = await al('Locale Pair', 'HORIZONTAL', { pad: [12, 16], gap: 24, radius: 8, align: 'CENTER', fillStyle: 'color/bg', strokeStyle: 'color/border', parent: secLoc });
    fill(lrRow, true);
    fill(await T('🇹🇯 ' + lr[0], 'text/body-semibold', 'color/primary-dark', lrRow), true);
    fill(await T('🇷🇺 ' + lr[1], 'text/body', 'color/muted', lrRow), true);
  }
}

// ---------------------------------------------------------------- Instance Helpers (only Component Instances + Styles!)
function setButtonText(inst, label, goName) {
  var applied = false;
  if (C.btnProps) {
    try { var o = {}; o[C.btnProps] = label; inst.setProperties(o); applied = true; } catch (e) {}
  }
  if (!applied) {
    var lt = inst.findOne(function (n) { return n.type === 'TEXT' && n.name === 'Label'; });
    if (lt) lt.characters = label;
  }
  if (goName) inst.name = goName;
  return inst;
}

function makeBtnInst(type, state, label, parent, goName, fillW) {
  var key = (type || 'Primary') + '/' + (state || 'Default');
  var base = C.btn[key] || C.btn['Primary/Default'];
  var inst = setButtonText(base.createInstance(), label, goName);
  if (parent) parent.appendChild(inst);
  if (fillW) fill(inst, true);
  return inst;
}

function makeInputInst(state, label, valText, helpText, parent, fillW) {
  var base = C.input[state || 'Default'] || C.input['Default'];
  var inst = base.createInstance();
  var lt = inst.findOne(function (n) { return n.type === 'TEXT' && n.name === 'LabelText'; });
  var ft = inst.findOne(function (n) { return n.type === 'TEXT' && n.name === 'FieldText'; });
  var ht = inst.findOne(function (n) { return n.type === 'TEXT' && n.name === 'HelperText'; });
  if (lt && label !== undefined) lt.characters = label;
  if (ft && valText !== undefined) ft.characters = valText;
  if (ht) {
    if (helpText) { ht.visible = true; ht.characters = helpText; }
    else { ht.visible = false; }
  }
  if (parent) parent.appendChild(inst);
  if (fillW) fill(inst, true);
  return inst;
}

async function setCardData(inst, c) {
  var setT = function (nm, val) {
    var n = inst.findOne(function (x) { return x.type === 'TEXT' && x.name === nm; });
    if (n && val !== undefined) n.characters = val;
  };
  setT('CodeText', c.code);
  setT('CardTitle', c.title);
  setT('CardMeta', c.meta);
  setT('CardDesc', c.desc);
  setT('CardStatus', c.status);
  if (c.tone === 'warning') {
    var b = inst.findOne(function (x) { return x.name === 'StatusBadge'; });
    var st = inst.findOne(function (x) { return x.type === 'TEXT' && x.name === 'CardStatus'; });
    if (b) await applyFill(b, 'color/warning');
    if (st) await applyFill(st, 'color/text');
  }
  return inst;
}

async function makeCardInst(c, parent, goName, fillW, isMob) {
  var base = (isMob && C.card.Mobile) ? C.card.Mobile : C.card.Desktop;
  var inst = await setCardData(base.createInstance(), c);
  if (goName) inst.name = goName;
  if (parent) parent.appendChild(inst);
  if (fillW) fill(inst, true);
  return inst;
}

// ---------------------------------------------------------------- Screen Builder (Shared Structure for Wireframes + Mockups)
function makeBuilder(mode) {
  var mock = mode === 'mock';
  var K = { mock: mock, mobile: false };

  K.screen = async function (name, w, h, page, x, y, isMob) {
    var f = await al(name, 'VERTICAL', {
      w: w, gap: 0, align: 'CENTER',
      fillStyle: mock ? 'color/bg' : null, fillHex: mock ? null : GRAY.bg, clip: false
    });
    try { f.minHeight = h; } catch (e) {}
    page.appendChild(f);
    f.x = x; f.y = y;
    if (isMob) setGridMobile(f); else setGridDesktop(f);
    if (mock) setExportPng1x(f);
    return f;
  };
  K.finish = function (f, exactH) {
    f.primaryAxisSizingMode = 'FIXED';
    f.resize(f.width, exactH);
    f.clipsContent = true;
    return f;
  };

  K.card = async function (name, parent, o) {
    o = o || {};
    var f = await al(name, o.dir || 'VERTICAL', {
      pad: def(o.pad, 16), gap: def(o.gap, 12), radius: def(o.radius, 8), w: o.w,
      fillStyle: mock ? (o.fillStyle || 'color/surface') : null, fillHex: mock ? null : (o.wireFill || o.fillHex || GRAY.white),
      strokeStyle: mock && !o.noStroke ? (o.strokeStyle || 'color/border') : null,
      strokeHex: mock ? null : (o.noStroke ? null : (o.wireStroke || GRAY.line)),
      parent: parent, fillW: o.fillW
    });
    return f;
  };

  K.badge = async function (label, parent, active) {
    var bgStyle = mock ? (active ? 'color/primary' : 'color/primary-soft') : null;
    var bgHex   = mock ? null : (active ? GRAY.dark : GRAY.light);
    var txtStyle = active ? 'color/surface' : (mock ? 'color/primary-dark' : 'color/text');
    var b = await al('BadgePill', 'HORIZONTAL', {
      pad: [8, 12], radius: 999, align: 'CENTER',
      fillStyle: bgStyle, fillHex: bgHex,
      strokeHex: mock ? null : GRAY.line,
      parent: parent
    });
    await K.txt(label, 'text/small', txtStyle, b);
    return b;
  };

  K.txt = async function (str, styleKey, colorKey, parent, name, wrap) {
    if (mock) {
      var t = await T(str, styleKey, colorKey || 'color/text', parent, name);
      if (wrap) wrapText(t);
      return t;
    }
    var sz = styleKey === 'text/h1' ? 32 : styleKey === 'text/h2' ? 24 : styleKey === 'text/small' ? 14 : 16;
    var bold = styleKey === 'text/h1' || styleKey === 'text/h2' || styleKey === 'text/body-semibold';
    var col = colorKey === 'color/surface' ? GRAY.white : colorKey === 'color/muted' ? GRAY.hint : GRAY.text;
    var tw = W(str, sz, col, parent, bold);
    if (name) tw.name = name;
    if (wrap) wrapText(tw);
    return tw;
  };

  K.btn = async function (type, label, parent, goName, fillW) {
    if (mock) return makeBtnInst(type, 'Default', label, parent, goName, fillW);
    var prim = type === 'Primary' || type === 'Danger';
    var b = await al(goName || 'WireBtn', 'HORIZONTAL', {
      pad: [10, 16], radius: 6, h: 44, align: 'CENTER', justify: 'CENTER',
      fillHex: prim ? GRAY.dark : GRAY.white, strokeHex: prim ? null : GRAY.line,
      parent: parent, fillW: fillW
    });
    b.counterAxisSizingMode = 'FIXED'; b.resize(b.width, 44);
    W(label, 16, prim ? GRAY.white : GRAY.text, b, false);
    return b;
  };

  K.input = async function (state, label, val, help, parent, fillW) {
    if (mock) return makeInputInst(state, label, val, help, parent, fillW);
    var f = await al('WireInput', 'VERTICAL', { gap: 4, w: 320, parent: parent, fillW: fillW });
    W(label, 14, GRAY.text, f);
    var box = await al('Field', 'HORIZONTAL', { pad: [0, 12], radius: 6, h: 44, align: 'CENTER', fillHex: GRAY.white, strokeHex: GRAY.line, parent: f });
    fill(box, true);
    W(val, 16, GRAY.hint, box);
    if (help) W(help, 14, GRAY.hint, f);
    return f;
  };

  K.courseCard = async function (c, parent, goName, fillW) {
    if (mock) return makeCardInst(c, parent, goName, fillW, K.mobile);
    var cd = await al(goName || 'WireCard', 'HORIZONTAL', { pad: 16, gap: 16, radius: 8, w: 640, align: 'CENTER', fillHex: GRAY.white, strokeHex: GRAY.line, parent: parent, fillW: fillW });
    var th = await al('Thumb', 'HORIZONTAL', { w: 64, h: 64, radius: 6, align: 'CENTER', justify: 'CENTER', fillHex: GRAY.light, parent: cd });
    W(c.code, 16, GRAY.dark, th, true);
    var st = await al('Stack', 'VERTICAL', { gap: 4, parent: cd }); fill(st, true);
    wrapText(W(c.title, 16, GRAY.text, st, true));
    wrapText(W(c.meta, 14, GRAY.hint, st));
    wrapText(W(c.desc, 14, GRAY.text, st));
    var right = await al('Right', 'VERTICAL', { gap: 8, align: 'MAX', parent: cd });
    var bdg = await al('Badge', 'HORIZONTAL', { pad: [4, 8], radius: 999, fillHex: GRAY.light, strokeHex: GRAY.line, parent: right });
    W(c.status, 14, GRAY.text, bdg);
    await K.btn('Secondary', L.ui.cardViewBtn, right, null, false);
    return cd;
  };

  K.header = async function (page, activeKey, f) {
    var nav = await al('Header', 'HORIZONTAL', {
      pad: K.mobile ? [12, 16] : [12, 60], gap: 16, align: 'CENTER', w: K.mobile ? 390 : 1440, h: 72,
      fillStyle: mock ? 'color/surface' : null, fillHex: mock ? null : GRAY.white,
      strokeStyle: mock ? 'color/border' : null, strokeHex: mock ? null : GRAY.line, parent: f
    });
    var br = await al('Go · home', 'HORIZONTAL', { gap: 8, align: 'CENTER', parent: nav });
    var bm = await al('Mark', 'HORIZONTAL', { w: 36, h: 36, radius: 8, align: 'CENTER', justify: 'CENTER', fillStyle: mock ? 'color/primary' : null, fillHex: mock ? null : GRAY.dark, parent: br });
    await K.txt('B', 'text/body-semibold', 'color/surface', bm);
    await K.txt(L.brand, 'text/body-semibold', 'color/text', br);

    if (K.mobile) {
      var sp = await al('Spacer', 'HORIZONTAL', { parent: nav }); fill(sp, true);
      await K.txt(L.ui.mobLangToggle, 'text/small', 'color/primary', nav);
      return nav;
    }

    var links = await al('Nav Links', 'HORIZONTAL', { gap: 16, align: 'CENTER', parent: nav });
    fill(links, true);
    for (var i = 0; i < L.nav.length; i++) {
      var ni = L.nav[i], isAct = ni[0] === activeKey;
      await K.txt(ni[1], isAct ? 'text/body-semibold' : 'text/body', isAct ? 'color/primary' : 'color/text', links, 'Go · ' + ni[0]);
    }
    var langBadge = await al('LangPill', 'HORIZONTAL', {
      pad: [8, 12], radius: 8, align: 'CENTER',
      fillStyle: mock ? 'color/bg' : null, fillHex: mock ? null : GRAY.light,
      strokeStyle: mock ? 'color/border' : null, strokeHex: mock ? null : GRAY.line,
      parent: nav
    });
    await K.txt(L.ui.langPill, 'text/small', 'color/text', langBadge);

    if (activeKey === 'home' || activeKey === 'login') {
      await K.btn('Primary', L.ui.loginBtn, nav, activeKey === 'home' ? 'Go · login' : 'Go · studentDash', false);
    } else {
      await K.btn('Secondary', L.ui.logoutBtn, nav, 'Go · login', false);
    }
    return nav;
  };

  K.footer = async function (f) {
    var ft = await al('Footer', 'VERTICAL', {
      pad: K.mobile ? [24, 16] : [24, 60], gap: 16, w: K.mobile ? 390 : 1440,
      fillStyle: mock ? 'color/primary-dark' : null, fillHex: mock ? null : GRAY.dark, parent: f
    });
    if (K.mobile) {
      await K.txt(L.brand + ' · ' + L.ui.footerSlogan, 'text/body-semibold', 'color/surface', ft, null, true);
      await K.txt(L.ui.footerLinks[0] + ' · ' + L.ui.footerLinks[1] + ' · ' + L.ui.footerLinks[2], 'text/small', 'color/surface', ft, 'Go · courses', true);
      await K.txt(L.ui.footerContacts[0] + ' · ' + L.ui.footerContacts[1], 'text/small', 'color/surface', ft, null, true);
      await K.txt(L.ui.footerCopy + ' · ' + L.ui.footerLangSwitch, 'text/small', 'color/surface', ft, null, true);
      return ft;
    }

    var cols = await al('Footer Columns', 'HORIZONTAL', { gap: 32, w: 1320, parent: ft });
    var col1 = await al('Footer Brand', 'VERTICAL', { gap: 8, w: 460, parent: cols });
    await K.txt(L.brand, 'text/body-semibold', 'color/surface', col1);
    await K.txt(L.ui.footerSlogan, 'text/small', 'color/surface', col1, null, true);
    await K.txt(L.ui.footerDesc, 'text/small', 'color/surface', col1, null, true);

    var col2 = await al('Footer Links', 'VERTICAL', { gap: 8, w: 390, parent: cols });
    await K.txt(L.ui.footerLinksTitle, 'text/body-semibold', 'color/surface', col2);
    await K.txt('→ ' + L.ui.footerLinks[0], 'text/small', 'color/surface', col2, 'Go · courses');
    await K.txt('→ ' + L.ui.footerLinks[1] + ' · ' + L.ui.footerLinks[3], 'text/small', 'color/surface', col2, 'Go · login');

    var col3 = await al('Footer Contact', 'VERTICAL', { gap: 8, w: 406, parent: cols });
    await K.txt(L.ui.footerContactTitle, 'text/body-semibold', 'color/surface', col3);
    await K.txt(L.ui.footerContacts[0] + ' · ' + L.ui.footerContacts[2], 'text/small', 'color/surface', col3);
    await K.txt(L.ui.footerContacts[1], 'text/small', 'color/surface', col3);

    var bot = await al('Footer Bottom', 'HORIZONTAL', { gap: 16, justify: 'SPACE_BETWEEN', w: 1320, parent: ft });
    await K.txt(L.ui.footerCopy, 'text/small', 'color/surface', bot);
    await K.txt(L.ui.footerLangSwitch, 'text/small', 'color/surface', bot);
    return ft;
  };

  K.container = async function (f) {
    return al('Container', 'VERTICAL', {
      pad: K.mobile ? [16, 16, 24, 16] : [24, 60, 24, 60],
      gap: 16, w: K.mobile ? 390 : 1440, parent: f
    });
  };

  return K;
}

// ---------------------------------------------------------------- Screens (8 Desktop + 1 Mobile + 2 Overlays)
async function buildHome(K, page, x, y) {
  var f = await K.screen(L.routes.home, 1440, 1024, page, x, y, false);
  await K.header(page, 'home', f);
  var c = await K.container(f);

  // Hero Section (from resources/views/welcome.blade.php)
  var hero = await K.card('Hero Section', c, {
    pad: 24, gap: 8, radius: 12, w: 1320,
    fillStyle: 'color/primary-dark', wireFill: GRAY.dark, noStroke: true
  });
  await K.txt(L.ui.heroBadge, 'text/small', 'color/surface', hero);
  await K.txt(L.ui.heroTitle, 'text/h1', 'color/surface', hero);
  await K.txt(L.ui.heroSlogan, 'text/h2', 'color/surface', hero);
  await K.txt(L.ui.heroDesc, 'text/body', 'color/surface', hero, null, true);
  var ctaRow = await al('Hero CTAs', 'HORIZONTAL', { gap: 12, parent: hero });
  await K.btn('Primary', L.ui.heroCatalogBtn, ctaRow, 'Go · courses', false);
  await K.btn('Secondary', L.ui.heroLoginBtn, ctaRow, 'Go · login', false);

  // 4 Platform Stats Row (from DB: 8 courses, 4 categories, 16 students, 3 PDFs)
  var statsRow = await al('Platform Stats', 'HORIZONTAL', { gap: 16, w: 1320, parent: c });
  for (var s = 0; s < L.ui.stats.length; s++) {
    var stCard = await K.card('Stat Card', statsRow, { pad: 16, gap: 4, w: 318 });
    fill(stCard, true);
    await K.txt(L.ui.stats[s][0], 'text/h1', 'color/primary', stCard);
    await K.txt(L.ui.stats[s][1], 'text/small', 'color/muted', stCard, null, true);
  }

  // Real DB Categories Row
  var catsRow = await al('Categories', 'HORIZONTAL', { gap: 8, wrap: true, w: 1320, parent: c });
  for (var k = 0; k < L.ui.catChips.length; k++) {
    var chip = await K.badge(L.ui.catChips[k], catsRow, false);
    chip.name = 'Go · courses';
  }

  // Popular Courses
  await K.txt(L.ui.popularTitle, 'text/h2', 'color/text', c);
  await K.courseCard(L.courses[0], c, 'Go · course', true);
  await K.courseCard(L.courses[2], c, 'Go · course', true);

  await K.footer(f);
  return K.finish(f, 1024);
}

async function buildLogin(K, page, x, y) {
  var f = await K.screen(L.routes.login, 1440, 1024, page, x, y, false);
  await K.header(page, 'login', f);
  var c = await K.container(f);
  c.counterAxisAlignItems = 'CENTER';
  var card = await K.card('Login Card', c, { pad: 32, gap: 16, w: 480 });
  await K.txt(L.ui.loginTitle, 'text/h1', 'color/text', card, null, true);
  await K.txt(L.ui.loginSub, 'text/body', 'color/muted', card, null, true);
  await K.input('Focus', L.ui.emailLbl, L.ui.emailVal, L.ui.inHelp, card, true);
  await K.input('Default', L.ui.passLbl, L.ui.passVal, L.ui.passHelp, card, true);
  await K.btn('Primary', L.ui.loginBtn, card, 'Go · studentDash', true);
  await K.txt(L.ui.noAccount, 'text/small', 'color/primary', card, 'Go · courses', true);
  return K.finish(f, 1024);
}

async function buildCourses(K, page, x, y) {
  var f = await K.screen(K.mobile ? L.routes.mobileCourses : L.routes.courses, K.mobile ? 390 : 1440, K.mobile ? 844 : 1024, page, x, y, K.mobile);
  await K.header(page, 'courses', f);
  var c = await K.container(f);

  var top = await al('Page Header', 'HORIZONTAL', { gap: 16, align: 'CENTER', w: K.mobile ? 358 : 1320, parent: c });
  fill(await K.txt(L.ui.catTitle, K.mobile ? 'text/h2' : 'text/h1', 'color/text', top), true);
  if (!K.mobile) await K.btn('Primary', L.ui.addCourse, top, 'Go · authorDash', false);

  if (K.mobile) {
    var mf = await K.card('Mobile Filters', c, { pad: 16, gap: 8, w: 358 });
    await K.input('Default', L.ui.searchLbl, L.ui.searchPh, '', mf, true);
    await K.input('Default', L.ui.catLbl, L.ui.catVal, '', mf, true);
    await K.btn('Primary', L.ui.applyFilter, mf, null, true);
    await K.courseCard(L.courses[0], c, null, true);
    await K.courseCard(L.courses[2], c, 'Go · course', true);
    await K.footer(f);
    return K.finish(f, 844);
  }

  // 3 cols filters (312px) + 24px gutter + 9 cols content (984px) = 1320px (.col-3 + .col-9)
  var cols = await al('Grid Row (.col-3 + .col-9)', 'HORIZONTAL', { gap: 24, w: 1320, parent: c });
  var left = await K.card('Filters (.col-3 · 312px)', cols, { pad: 16, gap: 16, w: 312 });
  await K.txt(L.ui.filtersTitle, 'text/h2', 'color/text', left);
  await K.input('Default', L.ui.catLbl, L.ui.catVal, '', left, true);
  await K.input('Default', L.ui.lvlLbl, L.ui.lvlVal, '', left, true);
  await K.btn('Primary', L.ui.applyFilter, left, null, true);

  var right = await al('Catalog (.col-9 · 984px)', 'VERTICAL', { gap: 16, w: 984, parent: cols });
  var sRow = await al('Search Row', 'HORIZONTAL', { gap: 16, align: 'MAX', w: 984, parent: right });
  await K.input('Focus', L.ui.searchLbl, L.ui.searchPh, '', sRow, true);
  await K.btn('Secondary', L.ui.applyFilter, sRow, null, false);

  var cnt = K.mock ? 4 : 3;
  for (var i = 0; i < cnt; i++) {
    await K.courseCard(L.courses[i], right, i === 2 ? 'Go · course' : null, true);
  }
  await K.txt(L.ui.pageInfo, 'text/small', 'color/muted', right);
  await K.footer(f);
  return K.finish(f, 1024);
}

async function buildCourseShow(K, page, x, y) {
  var f = await K.screen(L.routes.course, 1440, 1024, page, x, y, false);
  await K.header(page, 'courses', f);
  var c = await K.container(f);

  var topRow = await al('TopNav', 'HORIZONTAL', { gap: 16, align: 'CENTER', w: 1320, parent: c });
  await K.btn('Secondary', L.ui.backCatalog, topRow, 'Back · prev', false);
  fill(await K.txt(L.courses[2].title, 'text/h2', 'color/text', topRow), true);

  var cHero = await K.card('Course Header', c, {
    pad: 24, gap: 8, radius: 12, w: 1320,
    fillStyle: 'color/primary-dark', wireFill: GRAY.dark, noStroke: true
  });
  await K.txt(L.courses[2].title, 'text/h1', 'color/surface', cHero);
  await K.txt(L.courses[2].desc, 'text/body', 'color/surface', cHero, null, true);
  await K.txt(L.ui.courseHeroMeta, 'text/small', 'color/surface', cHero);

  var cols = await al('Course Row (.col-9 + .col-3)', 'HORIZONTAL', { gap: 24, w: 1320, parent: c });
  var main = await al('Main (.col-9 · 984px)', 'VERTICAL', { gap: 16, w: 984, parent: cols });
  await K.courseCard(L.courses[2], main, null, true);
  var modBox = await K.card('Curriculum Modules', main, { pad: 24, gap: 12, w: 984 });
  await K.txt(L.ui.modulesTitle, 'text/h2', 'color/text', modBox);
  await K.txt(L.ui.modulesList[0], 'text/body', 'color/text', modBox, null, true);
  await K.txt(L.ui.modulesList[1], 'text/body-semibold', 'color/primary', modBox, null, true);
  await K.txt(L.ui.modulesList[2], 'text/body', 'color/text', modBox, null, true);

  var side = await K.card('ActionPanel (.col-3 · 312px)', cols, { pad: 24, gap: 16, w: 312 });
  await K.txt(L.ui.priceLbl, 'text/h2', 'color/primary', side, null, true);
  await K.txt(L.ui.benefitsTxt, 'text/small', 'color/muted', side, null, true);
  await K.btn('Primary', L.ui.enrollBtn, side, 'Go · studentDash', true);
  await K.btn('Secondary', L.ui.goAssign, side, 'Go · assignment', true);
  return K.finish(f, 1024);
}

async function buildStudentDash(K, page, x, y) {
  var f = await K.screen(L.routes.studentDash, 1440, 1024, page, x, y, false);
  await K.header(page, 'studentDash', f);
  var c = await K.container(f);

  var head = await K.card('Student Summary', c, { dir: 'HORIZONTAL', pad: 24, gap: 24, w: 1320 });
  head.counterAxisAlignItems = 'CENTER';
  var info = await al('Info', 'VERTICAL', { gap: 8, parent: head }); fill(info, true);
  await K.badge(L.ui.dashRole, info, false);
  await K.txt(L.ui.dashTitle, 'text/h1', 'color/text', info);
  await K.txt(L.ui.dashSub, 'text/body', 'color/muted', info);
  await K.btn('Primary', L.ui.openAssignBtn, head, 'Go · assignment', false);

  var kpiRow = await al('Student KPIs', 'HORIZONTAL', { gap: 16, w: 1320, parent: c });
  for (var k = 0; k < L.ui.dashKpis.length; k++) {
    var kc = await K.card('KPI Card', kpiRow, { pad: 16, gap: 4, w: 429 });
    fill(kc, true);
    await K.txt(L.ui.dashKpis[k][0], 'text/small', 'color/muted', kc);
    await K.txt(L.ui.dashKpis[k][1], 'text/h1', 'color/primary', kc);
    await K.txt(L.ui.dashKpis[k][2], 'text/small', 'color/muted', kc);
  }

  await K.txt(L.ui.dashCoursesTitle, 'text/h2', 'color/text', c);
  for (var i = 0; i < 3; i++) {
    await K.courseCard(L.courses[i], c, 'Go · course', true);
  }
  return K.finish(f, 1024);
}

async function buildAssignmentSubmit(K, page, x, y) {
  var f = await K.screen(L.routes.assignment, 1440, 1024, page, x, y, false);
  await K.header(page, 'studentDash', f);
  var c = await K.container(f);

  var top = await al('TopRow', 'HORIZONTAL', { gap: 16, align: 'CENTER', w: 1320, parent: c });
  await K.btn('Secondary', L.ui.backCatalog, top, 'Back · prev', false);
  fill(await K.txt(L.ui.assignTitle, 'text/h1', 'color/text', top), true);

  var card = await K.card('Assignment Form (.col-9)', c, { pad: 24, gap: 16, w: 984 });
  await K.badge(L.ui.assignBadge, card, false);
  await K.txt(L.ui.assignDesc, 'text/body', 'color/text', card, null, true);
  await K.input('Focus', L.ui.commentLbl, L.ui.commentVal, L.ui.commentHelp, card, true);
  await K.input('Default', L.ui.fileLbl, L.ui.fileVal, L.ui.fileHelp, card, true);
  await K.input('Error', L.ui.errSampleLbl, L.ui.errSampleVal, L.ui.inErr, card, true);

  var act = await al('Actions', 'HORIZONTAL', { gap: 16, parent: card });
  await K.btn('Primary', L.ui.submitBtn, act, 'Overlay · ovSent', false);
  await K.btn('Secondary', L.ui.btnCancel, act, 'Go · studentDash', false);
  return K.finish(f, 1024);
}

async function buildAuthorDash(K, page, x, y) {
  var f = await K.screen(L.routes.authorDash, 1440, 1024, page, x, y, false);
  await K.header(page, 'authorDash', f);
  var c = await K.container(f);

  var head = await K.card('Author Summary', c, { dir: 'HORIZONTAL', pad: 24, gap: 24, w: 1320 });
  head.counterAxisAlignItems = 'CENTER';
  var hl = await al('Info', 'VERTICAL', { gap: 8, parent: head }); fill(hl, true);
  await K.badge(L.ui.authorBadge, hl, false);
  await K.txt(L.ui.authorTitle, 'text/h1', 'color/text', hl);
  await K.txt(L.ui.authorSub, 'text/body', 'color/muted', hl, null, true);
  await K.btn('Primary', L.ui.authorNewCourseBtn, head, 'Go · adminCourses', false);

  var kpiRow = await al('Author KPIs', 'HORIZONTAL', { gap: 16, w: 1320, parent: c });
  for (var k = 0; k < L.ui.authorKpis.length; k++) {
    var kc = await K.card('KPI Card', kpiRow, { pad: 16, gap: 4, w: 429 });
    fill(kc, true);
    await K.txt(L.ui.authorKpis[k][0], 'text/small', 'color/muted', kc);
    await K.txt(L.ui.authorKpis[k][1], 'text/h1', 'color/primary', kc);
    await K.txt(L.ui.authorKpis[k][2], 'text/small', 'color/muted', kc);
  }

  await K.txt(L.ui.authorActionsTitle, 'text/h2', 'color/text', c);
  var actRow = await al('Quick Actions', 'HORIZONTAL', { gap: 16, w: 1320, parent: c });
  var a1 = await K.card('Action 1', actRow, { dir: 'HORIZONTAL', pad: 16, gap: 16, w: 652 });
  a1.counterAxisAlignItems = 'CENTER'; fill(a1, true);
  fill(await K.txt(L.ui.authorSubmitAction, 'text/body-semibold', 'color/text', a1), true);
  await K.btn('Primary', L.ui.approveBtn, a1, 'Go · adminCourses', false);

  var a2 = await K.card('Action 2', actRow, { dir: 'HORIZONTAL', pad: 16, gap: 16, w: 652 });
  a2.counterAxisAlignItems = 'CENTER'; fill(a2, true);
  fill(await K.txt(L.ui.authorGradeAction, 'text/body-semibold', 'color/text', a2), true);
  await K.btn('Secondary', L.ui.openAssignBtn, a2, 'Go · assignment', false);

  await K.txt(L.ui.authorCoursesTitle, 'text/h2', 'color/text', c);
  await K.courseCard(L.courses[0], c, 'Go · course', true);
  await K.courseCard(L.courses[2], c, 'Go · course', true);
  return K.finish(f, 1024);
}

async function buildAdminCourses(K, page, x, y) {
  var f = await K.screen(L.routes.adminCourses, 1440, 1024, page, x, y, false);
  await K.header(page, 'adminCourses', f);
  var c = await K.container(f);

  var head = await al('Admin Header', 'HORIZONTAL', { gap: 16, align: 'CENTER', w: 1320, parent: c });
  var hl = await al('HL', 'VERTICAL', { gap: 8, parent: head }); fill(hl, true);
  await K.badge(L.ui.adminBadge, hl, false);
  await K.txt(L.ui.modTitle, 'text/h1', 'color/text', hl);
  await K.txt(L.ui.modSub, 'text/body', 'color/muted', hl);
  await K.btn('Secondary', L.ui.backCatalog, head, 'Back · prev', false);

  var tabs = await al('Status Tabs', 'HORIZONTAL', { gap: 8, w: 1320, parent: c });
  for (var t = 0; t < L.ui.modTabs.length; t++) {
    await K.badge(L.ui.modTabs[t], tabs, t === 0);
  }

  await K.courseCard(L.courses[5], c, null, true);
  var modBar = await K.card('Moderation Actions', c, { dir: 'HORIZONTAL', pad: 16, gap: 16, w: 1320 });
  modBar.counterAxisAlignItems = 'CENTER';
  fill(await K.txt(L.ui.modActionPrompt, 'text/body-semibold', 'color/text', modBar), true);
  await K.btn('Primary', L.ui.approveBtn, modBar, 'Go · courses', false);
  await K.btn('Danger', L.ui.rejectBtn, modBar, 'Overlay · ovReject', false);

  await K.courseCard(L.courses[2], c, 'Go · course', true);
  return K.finish(f, 1024);
}

async function buildOverlaySent(page, x, y) {
  var ov = await al(L.ui.ovSentFrameName, 'VERTICAL', {
    pad: 24, gap: 16, radius: 12, w: 520,
    fillStyle: 'color/surface', strokeStyle: 'color/success', strokeWeight: 2, parent: page
  });
  ov.x = x; ov.y = y;
  var hd = await al('Head', 'HORIZONTAL', { gap: 12, align: 'CENTER', parent: ov }); fill(hd, true);
  fill(await T(L.ui.ovSentTitle, 'text/h2', 'color/success', hd), true);
  await T('×', 'text/h2', 'color/muted', hd, 'Close · x');
  wrapText(await T(L.ui.ovSentBody, 'text/body', 'color/text', ov));
  var act = await al('Act', 'HORIZONTAL', { gap: 12, parent: ov });
  makeBtnInst('Primary', 'Default', L.ui.ovBackDash, act, 'Go · studentDash', false);
  makeBtnInst('Secondary', 'Default', L.ui.ovCloseBtn, act, 'Close · ok', false);
  try {
    ov.overlayPositionType = 'TOP_CENTER';
    ov.overlayBackground = { type: 'SOLID_COLOR', color: { r: 0.08, g: 0.11, b: 0.16, a: 0.45 } };
    ov.overlayBackgroundInteraction = 'CLOSE_ON_CLICK_OUTSIDE';
  } catch (e) {}
  setExportPng1x(ov);
  return ov;
}

async function buildOverlayReject(page, x, y) {
  var ov = await al(L.ui.ovRejFrameName, 'VERTICAL', {
    pad: 24, gap: 16, radius: 12, w: 520,
    fillStyle: 'color/surface', strokeStyle: 'color/danger', strokeWeight: 2, parent: page
  });
  ov.x = x; ov.y = y;
  var hd = await al('Head', 'HORIZONTAL', { gap: 12, align: 'CENTER', parent: ov }); fill(hd, true);
  fill(await T(L.ui.ovRejTitle, 'text/h2', 'color/danger', hd), true);
  await T('×', 'text/h2', 'color/muted', hd, 'Close · x');
  wrapText(await T(L.ui.ovRejBody, 'text/body', 'color/text', ov));
  makeInputInst('Focus', L.ui.ovRejLbl, L.ui.ovRejPh, L.ui.ovRejHelp, ov, true);
  var act = await al('Footer', 'HORIZONTAL', { gap: 12, justify: 'SPACE_BETWEEN', parent: ov }); fill(act, true);
  makeBtnInst('Secondary', 'Default', L.ui.btnCancel, act, 'Close · cancel', false);
  makeBtnInst('Danger', 'Default', L.ui.ovConfirmRej, act, 'Go · adminCourses', false);
  try {
    ov.overlayPositionType = 'CENTER';
    ov.overlayBackground = { type: 'SOLID_COLOR', color: { r: 0.08, g: 0.11, b: 0.16, a: 0.5 } };
    ov.overlayBackgroundInteraction = 'CLOSE_ON_CLICK_OUTSIDE';
  } catch (e) {}
  setExportPng1x(ov);
  return ov;
}

// ---------------------------------------------------------------- Interactive Prototype Reactions
async function linkMockupPrototype(F, page) {
  var ok = 0, fail = 0;
  var tr = { type: 'DISSOLVE', easing: { type: 'EASE_OUT' }, duration: 0.2 };
  var setRx = async function (node, action) {
    var rx = [{ trigger: { type: 'ON_CLICK' }, actions: [action] }];
    try {
      if (node.setReactionsAsync) await node.setReactionsAsync(rx);
      else node.reactions = rx;
      ok++;
    } catch (e) { fail++; console.warn('Reaction fail:', node.name, e); }
  };

  for (var key in F) {
    var nodes = F[key].findAll(function (n) { return /^(Go|Overlay|Back|Close) · /.test(n.name); });
    for (var i = 0; i < nodes.length; i++) {
      var parts = nodes[i].name.split(' · ');
      var kind = parts[0], targetKey = parts[1];
      if (kind === 'Close') { await setRx(nodes[i], { type: 'CLOSE' }); continue; }
      if (kind === 'Back') { await setRx(nodes[i], { type: 'BACK' }); continue; }
      var target = F[targetKey];
      if (!target || target === F[key]) continue;
      if (kind === 'Overlay') {
        await setRx(nodes[i], { type: 'NODE', destinationId: target.id, navigation: 'OVERLAY', transition: tr, preserveScrollPosition: false });
      } else {
        await setRx(nodes[i], { type: 'NODE', destinationId: target.id, navigation: 'NAVIGATE', transition: tr, preserveScrollPosition: false });
      }
    }
  }

  try {
    page.flowStartingPoints = [
      { nodeId: F.home.id, name: L.ui.flowStartName }
    ];
  } catch (e) {}
  console.log('Prototype links created:', ok, 'failed:', fail);
}

// ---------------------------------------------------------------- Page Setup (Idempotent 4 pages or Section fallback)
async function makeAreas() {
  var allPages = figma.root && figma.root.children ? figma.root.children : [figma.currentPage];
  var byName = {};
  for (var i = 0; i < allPages.length; i++) { byName[allPages[i].name] = allPages[i]; }

  var clearPage = async function (pg) {
    try { if (pg.loadAsync) await pg.loadAsync(); } catch (e) {}
    if (pg.children) {
      pg.children.slice().forEach(function (ch) { try { ch.remove(); } catch (e) {} });
    }
  };

  if (byName[L.pages[0]] && byName[L.pages[1]] && byName[L.pages[2]] && byName[L.pages[3]]) {
    var existingList = [byName[L.pages[0]], byName[L.pages[1]], byName[L.pages[2]], byName[L.pages[3]]];
    for (var eIdx = 0; eIdx < existingList.length; eIdx++) await clearPage(existingList[eIdx]);
    return { pages: true, list: existingList };
  }

  var first = figma.currentPage;
  await clearPage(first);
  first.name = L.pages[0];
  var created = [];
  try {
    for (var j = 1; j < L.pages.length; j++) {
      var pg = byName[L.pages[j]];
      if (pg && pg !== first) {
        await clearPage(pg);
        created.push(pg);
      } else {
        var np = figma.createPage();
        np.name = L.pages[j];
        created.push(np);
      }
    }
    return { pages: true, list: [first].concat(created) };
  } catch (e) {
    created.forEach(function (p) { try { if (!byName[p.name]) p.remove(); } catch (er) {} });
    first.name = 'Biyomuz Academy — Design System & Prototype';
    return {
      pages: false,
      list: L.pages.map(function (nm) { var s = figma.createSection(); s.name = nm; first.appendChild(s); return s; })
    };
  }
}

function fitSection(sct, y) {
  var ch = sct.children; if (!ch.length) return;
  var minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;
  ch.forEach(function (n) { minX = Math.min(minX, n.x); minY = Math.min(minY, n.y); maxX = Math.max(maxX, n.x + n.width); maxY = Math.max(maxY, n.y + n.height); });
  ch.forEach(function (n) { n.x = n.x - minX + 120; n.y = n.y - minY + 140; });
  sct.x = 0; sct.y = y;
  sct.resizeWithoutConstraints(maxX - minX + 240, maxY - minY + 260);
}

async function build(lang) {
  L = TEXT[lang];
  FONT = await resolveFonts();
  await makeVariablesAndPaintStyles();
  await makeTextStyles();

  var A = await makeAreas(), areas = A.list;

  // 01 Flow
  if (A.pages) await figma.setCurrentPageAsync(areas[0]);
  await buildFlowPage(areas[0]);

  // 03 UI-kit (create before Mockups so component masters & styles exist)
  if (A.pages) await figma.setCurrentPageAsync(areas[2]);
  await buildUiKitPage(areas[2]);

  // 02 Wireframes (8 Desktop frames 1440 × 1024 in Grayscale on 12-col Grid)
  if (A.pages) await figma.setCurrentPageAsync(areas[1]);
  var KW = makeBuilder('wire');
  var wfBuilders = [buildHome, buildLogin, buildCourses, buildCourseShow, buildStudentDash, buildAssignmentSubmit, buildAuthorDash, buildAdminCourses];
  for (var i = 0; i < wfBuilders.length; i++) {
    await wfBuilders[i](KW, areas[1], (i % 3) * 1560, Math.floor(i / 3) * 1480);
  }

  // 04 Mockups (8 Desktop 1440×1024 + 1 Mobile 390×844 + 2 Overlays + Interactive Prototype)
  if (A.pages) await figma.setCurrentPageAsync(areas[3]);
  var KM = makeBuilder('mock');
  var F = {};
  F.home         = await buildHome(KM, areas[3], 0, 0);
  F.login        = await buildLogin(KM, areas[3], 1560, 0);
  F.courses      = await buildCourses(KM, areas[3], 3120, 0);
  F.course       = await buildCourseShow(KM, areas[3], 0, 1480);
  F.studentDash  = await buildStudentDash(KM, areas[3], 1560, 1480);
  F.assignment   = await buildAssignmentSubmit(KM, areas[3], 3120, 1480);
  F.authorDash   = await buildAuthorDash(KM, areas[3], 0, 2800);
  F.adminCourses = await buildAdminCourses(KM, areas[3], 1560, 2800);

  KM.mobile = true;
  F.mobileCourses = await buildCourses(KM, areas[3], 4680, 0);
  KM.mobile = false;

  F.ovSent   = await buildOverlaySent(areas[3], 4680, 1360);
  F.ovReject = await buildOverlayReject(areas[3], 5280, 1360);

  await linkMockupPrototype(F, A.pages ? areas[3] : figma.currentPage);

  if (A.pages) {
    await figma.setCurrentPageAsync(areas[3]);
    figma.viewport.scrollAndZoomIntoView([F.home, F.login, F.courses]);
  } else {
    var yy = 0;
    areas.forEach(function (sct) { fitSection(sct, yy); yy += sct.height + 320; });
    figma.viewport.scrollAndZoomIntoView([areas[3]]);
  }
}
