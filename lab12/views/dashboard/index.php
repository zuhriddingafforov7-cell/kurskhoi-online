<?php
/**
 * Саҳифаи асосии Biyomuz Academy (Dashboard / Home).
 *
 * @var \App\Core\View $this
 * @var array<string, int> $stats
 * @var list<array<string, mixed>> $categories
 * @var list<array<string, mixed>> $courses
 */
?>
<section class="hero-section" aria-labelledby="hero-title">
    <div class="row align-items-center">
        <div class="col-12 col-lg-8">
            <div class="hero-badge">
                <span>Платформаи миллии онлайн-омӯзиш бо забони тоҷикӣ</span>
            </div>
            <h1 id="hero-title" class="hero-title">
                Имрӯз биёмӯз, фардо муваффақ шав!
            </h1>
            <p class="hero-slogan">
                Донишҳои замонавии барномасозӣ, таҳияи веб ва амнияти иттилоотиро аз устодони касбӣ аз сифр то сатҳи коршинос омӯзед.
            </p>
            <div class="d-flex flex-wrap gap-3">
                <a class="btn btn-primary btn-lg" href="<?= $this->url('/courses') ?>">
                    <span>Каталоги курсҳо</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
                <a class="btn btn-outline-light btn-lg" href="<?= $this->url('/categories') ?>">
                    <span>Категорияҳо</span>
                </a>
            </div>
        </div>
        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <div class="card p-3 text-start bg-white text-dark shadow-sm">
                <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                    <span class="badge bg-success">Фаъол</span>
                    <span class="text-small text-muted">Меъмории MVC (PHP 8)</span>
                </div>
                <h2 class="h5 mb-2">Қабули нави донишҷӯён</h2>
                <p class="text-small text-muted mb-3">Ба курсҳои амалии барномасозӣ ва технологияҳои веб ҳамроҳ шавед.</p>
                <a class="btn btn-outline-primary btn-sm w-100" href="<?= $this->url('/register') ?>">Бақайдгирии ройгон</a>
            </div>
        </div>
    </div>
</section>

<section id="platform-stats" class="mb-5" aria-labelledby="stats-title">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h2 id="stats-title" class="h3 mb-0">Нишондиҳандаҳои Biyomuz Academy</h2>
        <span class="text-muted text-small">Додаҳои муваққатӣ аз контроллер</span>
    </div>

    <div class="row g-3">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-number"><?= $this->e($stats['courses_count'] ?? 0) ?></div>
                <div class="stat-label">Курсҳои нашршуда</div>
                <p class="text-small text-muted mt-2 mb-0">Барномаҳои мукаммали амалӣ бо вазифаҳо</p>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-number"><?= $this->e($stats['categories_count'] ?? 0) ?></div>
                <div class="stat-label">Категорияи ихтисосӣ</div>
                <p class="text-small text-muted mt-2 mb-0">Барномасозӣ, дизайн, база ва амният</p>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-number"><?= $this->e($stats['modules_count'] ?? 0) ?></div>
                <div class="stat-label">Модули таълимӣ</div>
                <p class="text-small text-muted mt-2 mb-0">Дарсҳои назариявӣ ва супоришҳои амалӣ</p>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-number"><?= $this->e($stats['authors_count'] ?? 0) ?></div>
                <div class="stat-label">Муаллими ботаҷриба</div>
                <p class="text-small text-muted mt-2 mb-0">Устодони соҳаи технологияҳои иттилоотӣ</p>
            </div>
        </div>
    </div>
</section>

<section id="categories" class="mb-5" aria-labelledby="categories-title">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h2 id="categories-title" class="h3 mb-0">Категорияҳои таълимӣ</h2>
        <a class="text-primary text-decoration-none fw-semibold text-small" href="<?= $this->url('/categories') ?>">
            Ҳамаи категорияҳо &rarr;
        </a>
    </div>

    <div class="row g-3">
        <?php foreach ($categories as $category): ?>
            <div class="col-12 col-sm-6 col-lg-3">
                <a class="category-card" href="<?= $this->url('/courses?category_id=' . (int) $category['id']) ?>">
                    <div class="category-icon" aria-hidden="true">
                        <?= $this->e($this->initials((string) $category['title'])) ?>
                    </div>
                    <div>
                        <h3 class="h6 mb-1"><?= $this->e($category['title']) ?></h3>
                        <span class="text-small text-muted"><?= (int) ($category['courses_count'] ?? 0) ?> курс</span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section id="featured" class="mb-4" aria-labelledby="featured-title">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h2 id="featured-title" class="h3 mb-1">Курсҳои машҳури платформа</h2>
            <p class="text-muted text-small mb-0">Курсҳои серталаб бо барномаи замонавӣ ва маводи амалӣ</p>
        </div>
        <a class="btn btn-outline-primary" href="<?= $this->url('/courses') ?>">Ба каталог &rarr;</a>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <?php foreach ($courses as $course): ?>
                <?= $this->partial('course-card', ['course' => $course]) ?>
            <?php endforeach; ?>
        </div>

        <aside class="col-12 col-lg-4" aria-label="Маълумоти иловагӣ оид ба платформа">
            <div class="card p-3 mb-4 shadow-sm">
                <h3 class="h5 border-bottom pb-2 mb-3">Хусусиятҳои платформа</h3>
                <dl class="details">
                    <dt>Формати дарсҳо</dt>
                    <dd>Онлайн ва видеоӣ</dd>
                    <dt>Забони тадрис</dt>
                    <dd>Тоҷикӣ (адабӣ)</dd>
                    <dt>Санҷиши вазифа</dt>
                    <dd>Муаллими ихтисосӣ</dd>
                    <dt>Шаҳодатнома</dt>
                    <dd>Бо рамзи тасдиқӣ</dd>
                </dl>
            </div>

            <div class="card p-3 shadow-sm bg-light">
                <h3 class="h6 mb-2">Чаро Biyomuz Academy?</h3>
                <ul class="text-small text-muted ps-3 mb-3">
                    <li class="mb-1">Барномаҳои амалии мутобиқ ба бозори меҳнат</li>
                    <li class="mb-1">Сохтори пайдарпайи «Курс &rarr; Модул &rarr; Дарс»</li>
                    <li class="mb-1">Санҷиши супоришҳо аз ҷониби муаллиф</li>
                </ul>
                <a class="btn btn-outline-primary btn-sm" href="<?= $this->url('/courses') ?>">Ҳамаи курсҳоро бубинед</a>
            </div>
        </aside>
    </div>
</section>
