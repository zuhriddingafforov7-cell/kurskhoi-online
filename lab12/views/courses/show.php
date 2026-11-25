<?php
/**
 * Саҳифаи муфассали як курс (силлабус, модулҳо, мушаххасот ва муаллиф).
 *
 * @var \App\Core\View $this
 * @var array<string, mixed> $course
 * @var list<array<string, mixed>> $modules
 */
$allowedStatuses = ['published', 'pending', 'draft', 'rejected'];
$rawStatus = (string) ($course['status'] ?? 'draft');
$statusClass = in_array($rawStatus, $allowedStatuses, true) ? $rawStatus : 'draft';
$lessonTypeLabels = [
    'video' => 'Видеодарс',
    'text'  => 'Матн ва код',
    'file'  => 'Маводи амалӣ',
];
?>
<nav aria-label="Роҳнамои навигатсия" class="mb-3">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?= $this->url('/') ?>" class="text-decoration-none">Асосӣ</a></li>
            <li class="breadcrumb-item"><a href="<?= $this->url('/courses') ?>" class="text-decoration-none">Курсҳо</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $this->e($course['title']) ?></li>
        </ol>
        <a href="<?= $this->url('/courses') ?>" class="btn btn-sm btn-outline-secondary">
            <span aria-hidden="true">&larr;</span> Бозгашт ба каталог
        </a>
    </div>
</nav>

<section class="hero-section mb-4" aria-labelledby="course-hero-title">
    <div class="row align-items-center">
        <div class="col-12 col-lg-8">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                <span class="badge bg-light text-dark border"><?= $this->e($course['category_title']) ?></span>
                <span class="badge-status status-<?= $this->e($statusClass) ?>">
                    <?= $this->e($this->label('course_status', $statusClass)) ?>
                </span>
            </div>
            <h1 id="course-hero-title" class="hero-title">
                <?= $this->e($course['title']) ?>
            </h1>
            <p class="hero-slogan">
                <?= $this->e($course['description']) ?>
            </p>
            <div class="d-flex flex-wrap align-items-center gap-3 text-white-50 text-small">
                <span><strong>Муаллиф:</strong> <?= $this->e($course['author_name']) ?></span>
                <span>&bull;</span>
                <span><strong>Ҳаҷм:</strong> <?= (int) $course['modules_count'] ?> модул &middot; <?= (int) $course['lessons_count'] ?> дарс</span>
                <span>&bull;</span>
                <span><strong>Донишҷӯён:</strong> <?= (int) ($course['enrollments_count'] ?? 0) ?></span>
            </div>
        </div>
        <div class="col-12 col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a class="btn btn-primary btn-lg" href="<?= $this->url('/register') ?>">
                Ба курс номнавис шудан &rarr;
            </a>
        </div>
    </div>
</section>

<div class="row g-4">
    <article class="col-12 col-lg-8" aria-labelledby="course-content-title">
        <h2 id="course-content-title" class="visually-hidden">Мундариҷаи курс</h2>

        <section class="card p-4 mb-4 shadow-sm" aria-labelledby="overview-title">
            <h2 id="overview-title" class="h4 mb-3 border-bottom pb-2">Дар бораи курс</h2>
            <p class="text-muted mb-0">
                <?= $this->e($course['description']) ?>
            </p>
        </section>

        <section class="mb-4" aria-labelledby="syllabus-title">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 id="syllabus-title" class="h4 mb-0">Барномаи таълимии курс</h2>
                <span class="text-muted text-small"><?= (int) $course['modules_count'] ?> модул &middot; <?= (int) $course['lessons_count'] ?> дарс</span>
            </div>

            <?php foreach ($modules as $module): ?>
                <div class="syllabus-module">
                    <div class="syllabus-header">
                        <div>
                            <span class="badge bg-success me-2">Модули <?= (int) $module['order_index'] ?></span>
                            <span><?= $this->e($module['title']) ?></span>
                        </div>
                        <span class="text-muted text-small"><?= $this->e(count($module['lessons'] ?? [])) ?> дарс</span>
                    </div>
                    <?php if (!empty($module['description'])): ?>
                        <div class="px-3 py-2 text-small text-muted border-bottom bg-white">
                            <?= $this->e($module['description']) ?>
                        </div>
                    <?php endif; ?>
                    <ul class="syllabus-list">
                        <?php foreach ($module['lessons'] ?? [] as $lesson): ?>
                            <?php $typeKey = (string) ($lesson['content_type'] ?? 'text'); ?>
                            <li class="syllabus-item">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-muted text-small"><?= (int) $module['order_index'] ?>.<?= (int) $lesson['order_index'] ?></span>
                                    <span><?= $this->e($lesson['title']) ?></span>
                                </div>
                                <span class="badge bg-light text-dark border text-small">
                                    <?= $this->e($lessonTypeLabels[$typeKey] ?? 'Дарс') ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </section>
    </article>

    <aside class="col-12 col-lg-4" aria-label="Мушаххасоти курс ва муаллиф">
        <div class="card p-3 mb-4 shadow-sm">
            <h2 class="h5 border-bottom pb-2 mb-3">Хусусиятҳои курс</h2>
            <dl class="details mb-4">
                <dt>Идентификатор</dt>
                <dd>#<?= (int) $course['id'] ?></dd>

                <dt>Муаллим</dt>
                <dd><?= $this->e($course['author_name']) ?></dd>

                <dt>Категория</dt>
                <dd><?= $this->e($course['category_title']) ?></dd>

                <dt>Ҳолати курс</dt>
                <dd><?= $this->e($this->label('course_status', $statusClass)) ?></dd>

                <dt>Ҳаҷми курс</dt>
                <dd><?= (int) $course['modules_count'] ?> модул &middot; <?= (int) $course['lessons_count'] ?> дарс</dd>

                <dt>Санаи эҷод</dt>
                <dd><?= $this->e($this->date($course['created_at'] ?? null)) ?></dd>

                <dt>Навсозии охирин</dt>
                <dd><?= $this->e($this->date($course['updated_at'] ?? null)) ?></dd>
            </dl>

            <div class="d-grid gap-2">
                <a class="btn btn-primary" href="<?= $this->url('/register') ?>">Ба курс номнавис шудан</a>
                <a class="btn btn-outline-secondary" href="<?= $this->url('/courses') ?>">Ҳамаи курсҳо</a>
            </div>
        </div>

        <div class="card p-3 shadow-sm">
            <h3 class="h6 border-bottom pb-2 mb-3">Муаллими курс</h3>
            <div class="d-flex align-items-center gap-3 mb-2">
                <span class="avatar" role="img" aria-label="Акси <?= $this->e($course['author_name']) ?>">
                    <?= $this->e($this->initials((string) $course['author_name'])) ?>
                </span>
                <div>
                    <h4 class="h6 mb-0"><?= $this->e($course['author_name']) ?></h4>
                    <span class="text-small text-muted"><?= $this->e($course['author_email'] ?? '') ?></span>
                </div>
            </div>
        </div>
    </aside>
</div>
