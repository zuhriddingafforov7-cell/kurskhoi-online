<?php
/**
 * Саҳифаи категорияҳои таълимии платформа.
 *
 * @var \App\Core\View $this
 * @var list<array<string, mixed>> $categories
 */
?>
<nav aria-label="Роҳнамои навигатсия" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?= $this->url('/') ?>" class="text-decoration-none">Асосӣ</a></li>
        <li class="breadcrumb-item active" aria-current="page">Категорияҳо</li>
    </ol>
</nav>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h2 mb-1">Категорияҳои таълимӣ</h1>
        <p class="text-muted text-small mb-0">
            Самтҳои асосии таҳсил ва омӯзиш дар платформаи Biyomuz Academy
        </p>
    </div>
    <div>
        <a class="btn btn-outline-primary btn-sm" href="<?= $this->url('/courses') ?>">Ҳамаи курсҳо &rarr;</a>
    </div>
</div>

<div class="row g-4">
    <?php foreach ($categories as $category): ?>
        <div class="col-12 col-md-6">
            <div class="card h-100 shadow-sm p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="category-icon" aria-hidden="true">
                                <?= $this->e($this->initials((string) $category['title'])) ?>
                            </div>
                            <div>
                                <h2 class="h5 mb-0"><?= $this->e($category['title']) ?></h2>
                                <span class="text-small text-muted font-monospace"><?= $this->e($category['slug']) ?></span>
                            </div>
                        </div>
                        <span class="badge bg-success-subtle text-success border px-3 py-2">
                            <?= (int) ($category['courses_count'] ?? 0) ?> курс
                        </span>
                    </div>

                    <p class="text-muted text-small mb-3">
                        <?= $this->e($category['description'] ?? '') ?>
                    </p>
                </div>

                <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                    <span class="text-small text-muted">
                        Сана: <?= $this->e($this->date($category['created_at'] ?? null)) ?>
                    </span>
                    <a class="btn btn-primary btn-sm"
                       href="<?= $this->url('/courses?category_id=' . (int) $category['id']) ?>">
                        Дидани курсҳо &rarr;
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
