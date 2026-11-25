<?php
/**
 * Қисми болоии навигатсия (Navbar).
 *
 * @var \App\Core\View $this
 */
$active = $active ?? '';
$items = [
    'dashboard'  => ['/', 'Асосӣ'],
    'courses'    => ['/courses', 'Курсҳо'],
    'categories' => ['/categories', 'Категорияҳо'],
];
?>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark app-navbar" aria-label="Сарлавҳаи асосии сомона">
        <div class="container">
            <a class="navbar-brand" href="<?= $this->url('/') ?>">
                <span class="d-inline-flex align-items-center justify-content-center rounded fw-bold text-white me-1"
                      style="width: 32px; height: 32px; background-color: #00875A; font-size: 0.875rem;"
                      aria-hidden="true">BA</span>
                <span>Biyomuz Academy</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                    aria-controls="mainNav" aria-expanded="false" aria-label="Кушодани менюи навигатсия">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php foreach ($items as $key => [$href, $text]): ?>
                        <li class="nav-item">
                            <a class="nav-link<?= $key === $active ? ' active' : '' ?>"
                               <?= $key === $active ? 'aria-current="page"' : '' ?>
                               href="<?= $this->url($href) ?>"><?= $this->e($text) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="role-badge">Тоҷикӣ</span>
                    <a class="btn btn-sm <?= $active === 'login' ? 'btn-light text-dark' : 'btn-outline-light' ?>"
                       href="<?= $this->url('/login') ?>">Ворид шудан</a>
                    <a class="btn btn-sm btn-primary"
                       href="<?= $this->url('/register') ?>">Бақайдгирӣ</a>
                </div>
            </div>
        </div>
    </nav>
</header>
