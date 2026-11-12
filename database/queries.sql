-- =====================================================================
-- Лоиҳа: Biyomuz Academy (Платформаи курсҳои онлайн)
-- Лабораторияи №7: Дархостҳои санҷишии пойгоҳи додаҳо
-- Файл: database/queries.sql
-- СУБД: MariaDB 10.4+ / MySQL 8.0+
-- Кодировка: utf8mb4
-- =====================================================================

USE kurskhoi_online;
SET NAMES utf8mb4;

-- ---------------------------------------------------------------------
-- ДАРХОСТИ 1: JOIN-и 3 ҷадвал (Истифодабарандагон, Номнависӣ ва Курсҳо)
-- Мақсад: Баровардани рӯйхати расмии донишҷӯёни ба курсҳо номнависшуда бо маълумоти муфассал
-- Талаботи функсионалӣ аз docs/03-short-spec.md: FR-04 (Номнавис шудани донишҷӯ ба курс) ва FR-01
-- Натиҷаи интизоршаванда: Рӯйхати сабтҳо бо номи донишҷӯ, почта, сарлавҳаи курс, бахш ва ҳолати таҳсил
-- ---------------------------------------------------------------------
SELECT 
    e.id AS enrollment_id,
    u.name AS student_name,
    u.email AS student_email,
    c.title AS course_title,
    cat.title AS category_title,
    e.status AS enrollment_status,
    e.enrolled_at
FROM enrollments e
JOIN users u ON u.id = e.user_id
JOIN courses c ON c.id = e.course_id
LEFT JOIN categories cat ON cat.id = c.category_id
ORDER BY e.enrolled_at DESC;

-- ---------------------------------------------------------------------
-- ДАРХОСТИ 2: Ҷустуҷӯ бо оператори LIKE ва матни кириллӣ
-- Мақсад: Ҷустуҷӯи сареҳи курсҳо аз рӯйи калимаи калидии «Асос» дар сарлавҳа
-- Талаботи функсионалӣ аз docs/03-short-spec.md: FR-01 (Феҳристи курсҳо бо ҷустуҷӯ ва категорияҳо)
-- Натиҷаи интизоршаванда: Ҳамаи курсҳое, ки сарлавҳаашон бо калимаи «Асос» шурӯъ мешавад
-- ---------------------------------------------------------------------
SELECT 
    c.id,
    c.title,
    c.status,
    u.name AS author_name,
    cat.title AS category_name
FROM courses c
JOIN users u ON u.id = c.author_id
LEFT JOIN categories cat ON cat.id = c.category_id
WHERE c.title LIKE 'Асос%'
ORDER BY c.title ASC;

-- ---------------------------------------------------------------------
-- ДАРХОСТИ 3: Гурӯҳбандӣ (GROUP BY + COUNT) — Омори курсҳои нашршуда
-- Мақсад: Ҳисоб кардани теъдоди курсҳои тасдиқшуда ва нашршуда дар ҳар як бахш
-- Талаботи функсионалӣ аз docs/03-short-spec.md: FR-01 (Феҳристи курсҳо) ва FR-07 (Идоракунии курсҳо)
-- Натиҷаи интизоршаванда: Номи бахш ва шумораи дақиқи курсҳои фаъол ба тартиби камшавӣ
-- ---------------------------------------------------------------------
SELECT 
    cat.id AS category_id,
    cat.title AS category_title,
    COUNT(c.id) AS published_courses_count
FROM categories cat
JOIN courses c ON c.category_id = cat.id
WHERE c.status = 'published'
GROUP BY cat.id, cat.title
ORDER BY published_courses_count DESC;

-- ---------------------------------------------------------------------
-- ДАРХОСТИ 4: Пайвастшавии беруна (LEFT JOIN) — Ҳамаи бахшҳо, ҳатто бе курс
-- Мақсад: Намоиши пурраи каталоги бахшҳо, аз ҷумла бахшҳои нави бекурс («Амнияти иттилоотӣ»)
-- Талаботи функсионалӣ аз docs/03-short-spec.md: FR-01 (Категорияҳои платформа)
-- Натиҷаи интизоршаванда: 4 бахш, ки барои бахши бекурс шумораи 0 нишон дода мешавад
-- ---------------------------------------------------------------------
SELECT 
    cat.id AS category_id,
    cat.title AS category_title,
    cat.slug,
    COUNT(c.id) AS total_courses
FROM categories cat
LEFT JOIN courses c ON c.category_id = cat.id
GROUP BY cat.id, cat.title, cat.slug
ORDER BY total_courses DESC, cat.title ASC;

-- ---------------------------------------------------------------------
-- ДАРХОСТИ 5: Филтри бисёршарта (Мураккаб) аз рӯйи Ҳолат ва Категория
-- Мақсад: Интихоби курсҳои фаъоли нашршуда дар бахши «Барномасозӣ» (category_id = 1)
-- Талаботи функсионалӣ аз docs/03-short-spec.md: FR-01 ва FR-09 (Модератсия ва ҳолатҳои курс)
-- Натиҷаи интизоршаванда: Курсҳои дорои status='published' ва category_id=1
-- ---------------------------------------------------------------------
SELECT 
    c.id,
    c.title,
    c.status,
    u.name AS author_name,
    c.created_at
FROM courses c
JOIN users u ON u.id = c.author_id
WHERE c.status = 'published' 
  AND c.category_id = 1
ORDER BY c.id ASC;

-- ---------------------------------------------------------------------
-- ДАРХОСТИ 6: Саҳифабандии натиҷаҳо (LIMIT ва OFFSET)
-- Мақсад: Баровардани саҳифаи 2-юми феҳристи дарсҳо (пагинатсия: 4 дарс дар саҳифа)
-- Талаботи функсионалӣ аз docs/03-short-spec.md: FR-01 ва FR-07 (Саҳифабандии рӯйхати мавод)
-- Натиҷаи интизоршаванда: 4 сабти навбатӣ бо гузариш аз 4 сабти аввал (OFFSET 4)
-- ---------------------------------------------------------------------
SELECT 
    l.id AS lesson_id,
    l.title AS lesson_title,
    l.content_type,
    m.title AS module_title,
    l.order_index
FROM lessons l
JOIN modules m ON m.id = l.module_id
ORDER BY l.id ASC
LIMIT 4 OFFSET 4;

-- =====================================================================
-- ҚИСМИ ИЛОВАГӢ: САНҶИШИ ИНДЕКСҲО БО EXPLAIN ВА БУТУНИИ utf8mb4
-- =====================================================================

-- 7.1. Санҷиши истифодаи индекси ҷустуҷӯии `idx_courses_title`
EXPLAIN SELECT id, title, status FROM courses WHERE title LIKE 'Асос%';

-- 7.2. Санҷиши истифодаи индекси таркибии `idx_courses_status_category`
EXPLAIN SELECT id, title, status, category_id FROM courses WHERE status = 'published' AND category_id = 1;

-- 7.3. Санҷиши саҳеҳии кодировкаи utf8mb4 (ҳисоби дарозии рамзҳо бо байт ва аломат)
SELECT 
    title,
    CHAR_LENGTH(title) AS char_count,
    LENGTH(title) AS byte_count
FROM courses
WHERE id = 1;

-- 7.4. Санҷиши рамзгузории ҳарфҳои махсуси алифбои тоҷикӣ (Ғ, Ҷ, Ӯ, Ҳ, Ӣ, Қ)
SELECT 
    'Ғ' AS letter_gh, HEX('Ғ') AS hex_gh,
    'Ҷ' AS letter_j,  HEX('Ҷ') AS hex_j,
    'Ӯ' AS letter_u,  HEX('Ӯ') AS hex_u,
    'Ӣ' AS letter_i,  HEX('Ӣ') AS hex_i,
    'Қ' AS letter_q,  HEX('Қ') AS hex_q;
