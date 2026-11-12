# Скриншотҳои Лабораторияи №7: Физическая модель базы данных

Ин папка барои нигоҳдории скриншотҳои тасдиқкунандаи иҷрои Лабораторияи №7 дар СУБД MySQL/MariaDB ва phpMyAdmin пешбинӣ шудааст.

---

## Рӯйхати 6 скриншоти зарурӣ тибқи талаботи омӯзгор

| № | Номи файл | Мазмун ва Ҳадаф | Роҳи гирифтан |
|:---:|:---|:---|:---|
| 1 | `01_phpmyadmin_tables.png` | Намоиши ҳамаи 10 ҷадвали соҳавӣ дар phpMyAdmin бо кодировкаи `utf8mb4_unicode_ci` ва муҳаррики `InnoDB`. | Дар браузер кушоед: `http://localhost/phpmyadmin` → Пойгоҳи `kurskhoi_online` → Рӯйхати ҷадвалҳо. |
| 2 | `02_seed_output.png` | Натиҷаи бомуваффақияти иҷрои `seed.sql` дар консол ё phpMyAdmin. | `mysql -u root --default-character-set=utf8mb4 kurskhoi_online < database/seed.sql` |
| 3 | `03_queries_output.png` | Натиҷаи иҷрои 6 дархости асосии `queries.sql` бо ҷадвалҳои зебои ASCII. | `mysql -u root --default-character-set=utf8mb4 --table kurskhoi_online < database/queries.sql` |
| 4 | `04_phpmyadmin_designer.png` | Схемаи визуалии робитаҳои байни 10 ҷадвал дар реҷаи «Дизайнер» (Designer)-и phpMyAdmin. | `http://localhost/phpmyadmin` → Пойгоҳи `kurskhoi_online` → Менюи «Ещё» / «More» → «Дизайнер» (Designer). |
| 5 | `05_cyrillic_utf8mb4.png` | Исботи саҳеҳии кодировкаи utf8mb4: намоиши матнҳои тоҷикӣ/кириллӣ бе аломати савол `????`. | Дархости санҷиши дарозии байтҳо ва ҳарфҳои тоҷикӣ (Ғ, Ҷ, Ӯ, Ҳ, Ӣ, Қ). |
| 6 | `06_explain_indices.png` | Натиҷаи дархости `EXPLAIN` бо нишон додани истифодаи индексҳои `idx_courses_title` ва `idx_courses_status_category`. | Иҷрои дархостҳои EXPLAIN аз бахши охири `database/queries.sql`. |

---

## Дастури зуд барои гирифтани скриншотҳои phpMyAdmin

1. Дар панели **XAMPP Control Panel** боварӣ ҳосил кунед, ки модули **Apache** ва **MySQL** сабз мебошанд (фаъоланд).
2. Браузерро кушода, ба суроғаи зер гузаред:
   ```
   http://localhost/phpmyadmin
   ```
3. Аз сутуни чап пойгоҳи додаҳои **`kurskhoi_online`**-ро интихоб кунед.
4. **Барои скриншоти 1 (`01_phpmyadmin_tables.png`):**
   - Дар саҳифаи асосии база шумо рӯйхати ҳамаи ҷадвалҳоро (бо `InnoDB` ва `utf8mb4_unicode_ci`) мебинед.
   - Бо пахши тугмаҳои `Win + Shift + S` аз ин саҳифа скриншот гирифта, бо номи `01_phpmyadmin_tables.png` дар ҳамин папка сабт кунед.
5. **Барои скриншоти 4 (`04_phpmyadmin_designer.png`):**
   - Дар панели болоии phpMyAdmin ба бахши **«Ещё» (More)** гузаред ва тугмаи **«Дизайнер» (Designer)**-ро пахш намоед.
   - phpMyAdmin харитаи визуалии робитаҳоро мекашад. Аз ин схема скриншот гирифта, бо номи `04_phpmyadmin_designer.png` захира намоед.
