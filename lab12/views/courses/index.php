<?php
/**
 * Каталоги курсҳои таълимӣ бо филтрҳо ва кортҳои курсҳо.
 *
 * @var \App\Core\View $this
 * @var list<array<string, mixed>> $courses
 * @var list<array<string, mixed>> $categories
 * @var array{q: string, category_id: string, status: string} $filters
 */
$searchQuery = (string) ($filters['q'] ?? '');
$selectedCategory = (string) ($filters['category_id'] ?? '');
$selectedStatus = (string) ($filters['status'] ?? '');
$statusLabels = $this->labels('course_status');
?>
<nav aria-label="Роҳнамои навигатсия" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?= $this->url('/') ?>" class="text-decoration-none">Асосӣ</a></li>
        <li class="breadcrumb-item active" aria-current="page">Курсҳо</li>
    </ol>
</nav>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-2 border-bottom">
    <div>
        <h1 class="h2 mb-1">Каталоги курсҳои таълимӣ</h1>
        <p class="text-muted text-small mb-0">
            Курсҳои касбии барномасозӣ, веб-дизайн ва пойгоҳи додаҳо бо забони тоҷикӣ
        </p>
    </div>
    <div>
        <span class="badge bg-success-subtle text-success border px-3 py-2">
            <strong><?= $this->e(count($courses)) ?></strong> курс ёфт шуд
        </span>
    </div>
</div>

<div class="row g-4">
    <aside class="col-12 col-md-4 col-lg-3" aria-label="Филтрҳои ҷустуҷӯ ва гурӯҳбандӣ">
        <div class="filter-card">
            <h2 class="h5 mb-3 border-bottom pb-2">Филтри курсҳо</h2>

            <form method="get" action="<?= $this->url('/courses') ?>">
                <div class="mb-3">
                    <label for="catalog-search" class="form-label fw-semibold">Ҷустуҷӯи курс</label>
                    <input type="search" id="catalog-search" name="q" class="form-control"
                           value="<?= $this->e($searchQuery) ?>"
                           placeholder="Номи курс ё муаллиф...">
                </div>

                <div class="mb-3">
                    <label for="category-select" class="form-label fw-semibold">Категория</label>
                    <select id="category-select" name="category_id" class="form-select">
                        <option value="">Ҳамаи категорияҳо</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= (int) $cat['id'] ?>" <?= $selectedCategory === (string) $cat['id'] ? 'selected' : '' ?>>
                                <?= $this->e($cat['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="status-select" class="form-label fw-semibold">Ҳолати курс</label>
                    <select id="status-select" name="status" class="form-select">
                        <option value="">Ҳамаи ҳолатҳо</option>
                        <?php foreach ($statusLabels as $key => $label): ?>
                            <option value="<?= $this->e($key) ?>" <?= $selectedStatus === $key ? 'selected' : '' ?>>
                                <?= $this->e($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-2">Филтр кардан</button>
                <a href="<?= $this->url('/courses') ?>" class="btn btn-outline-secondary w-100">Тоза кардан</a>
            </form>
        </div>
    </aside>

    <section class="col-12 col-md-8 col-lg-9" aria-labelledby="catalog-heading">
        <h2 id="catalog-heading" class="visually-hidden">Рӯйхати курсҳои ёфтшуда</h2>

        <?php if ($searchQuery !== ''): ?>
            <div class="alert alert-light border d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <span>Натиҷаи ҷустуҷӯ барои: <strong>«<?= $this->e($searchQuery) ?>»</strong></span>
                <a class="text-small text-decoration-none" href="<?= $this->url('/courses') ?>">Бекор кардан</a>
            </div>
        <?php endif; ?>

        <?php if ($courses === []): ?>
            <div class="card p-4 text-center shadow-sm">
                <h3 class="h5 mb-2">Ягон курс ёфт нашуд</h3>
                <p class="text-muted text-small mb-3">Параметрҳои ҷустуҷӯ ё филтрро тағйир диҳед.</p>
                <div>
                    <a class="btn btn-primary btn-sm" href="<?= $this->url('/courses') ?>">Намоиши ҳамаи курсҳо</a>
                </div>
            </div>
        <?php else: ?>
            <div id="courses-list">
                <?php foreach ($courses as $course): ?>
                    <?= $this->partial('course-card', ['course' => $course]) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>
