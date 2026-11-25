<?php
/**
 * Қисми поёнии саҳифа (Footer).
 *
 * @var \App\Core\View $this
 */
?>
<footer class="app-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded fw-bold text-white"
                          style="width: 32px; height: 32px; background-color: #00875A; font-size: 0.875rem;"
                          aria-hidden="true">BA</span>
                    <span class="h5 mb-0 text-white">Biyomuz Academy</span>
                </div>
                <p class="text-small text-white-50 mb-2">
                    Платформаи миллии онлайн-омӯзиши барномасозӣ ва технологияҳои веб бо забони тоҷикӣ.
                </p>
                <p class="text-small text-white-50 mb-0">
                    <em>«Имрӯз биёмӯз, фардо муваффақ шав!»</em>
                </p>
            </div>

            <div class="col-6 col-md-2">
                <h3 class="h5 text-white">Пайвандҳо</h3>
                <ul class="list-unstyled text-small mb-0">
                    <li class="mb-2"><a href="<?= $this->url('/') ?>">Асосӣ</a></li>
                    <li class="mb-2"><a href="<?= $this->url('/courses') ?>">Каталоги курсҳо</a></li>
                    <li class="mb-2"><a href="<?= $this->url('/categories') ?>">Категорияҳо</a></li>
                    <li class="mb-2"><a href="<?= $this->url('/login') ?>">Ворид шудан</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-3">
                <h3 class="h5 text-white">Ихтисосҳо</h3>
                <ul class="list-unstyled text-small mb-0">
                    <li class="mb-2"><a href="<?= $this->url('/courses?category_id=1') ?>">Барномасозӣ</a></li>
                    <li class="mb-2"><a href="<?= $this->url('/courses?category_id=2') ?>">Веб-дизайн</a></li>
                    <li class="mb-2"><a href="<?= $this->url('/courses?category_id=3') ?>">Пойгоҳи додаҳо</a></li>
                    <li class="mb-2"><a href="<?= $this->url('/courses?category_id=4') ?>">Амнияти иттилоотӣ</a></li>
                </ul>
            </div>

            <div class="col-12 col-md-3">
                <h3 class="h5 text-white">Тамос ва маълумот</h3>
                <address class="text-small text-white-50 not-italic mb-2">
                    Ҷумҳурии Тоҷикистон, ш. Душанбе<br>
                    Почта: <a class="text-white-50 text-decoration-underline" href="mailto:admin@biyomuz.tj">admin@biyomuz.tj</a>
                </address>
                <div class="d-flex align-items-center gap-2 mt-2">
                    <span class="role-badge">Забон: Тоҷикӣ</span>
                </div>
            </div>
        </div>

        <div class="app-footer-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
            <p class="mb-0 text-white-50">
                &copy; <?= $this->e(date('Y')) ?> Biyomuz Academy. Ҳамаи ҳуқуқҳо маҳфузанд.
            </p>
            <p class="mb-0 text-white-50">
                Зерлоиҳаи MVC (Лабораторияи №12) · Додаҳои муваққатӣ
            </p>
        </div>
    </div>
</footer>
