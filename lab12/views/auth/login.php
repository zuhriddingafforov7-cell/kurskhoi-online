<?php
/**
 * Саҳифаи воридшавӣ ба платформа (дар ЛР №12 танҳо намоиш; коркарди POST дар ЛР №14).
 *
 * @var \App\Core\View $this
 */
?>
<nav aria-label="Роҳнамои навигатсия" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?= $this->url('/') ?>" class="text-decoration-none">Асосӣ</a></li>
        <li class="breadcrumb-item active" aria-current="page">Воридшавӣ</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-5">
        <div class="card shadow-sm p-4">
            <div class="border-bottom pb-3 mb-4">
                <span class="badge bg-success-subtle text-success border mb-2">Аутентификатсия</span>
                <h1 class="h3 mb-1">Воридшавӣ ба Biyomuz Academy</h1>
                <p class="text-muted text-small mb-0">
                    Барои идомаи омӯзиш почтаи электронӣ ва пароли худро ворид намоед.
                </p>
            </div>

            <form method="post" action="<?= $this->url('/login') ?>">
                <div class="mb-3">
                    <label for="login-email" class="form-label">Почтаи электронӣ <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="login-email" name="email"
                           placeholder="student@biyomuz.tj" required autocomplete="email">
                </div>

                <div class="mb-4">
                    <label for="login-password" class="form-label">Рамз (Пароль) <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="login-password" name="password"
                           placeholder="Ҳадди ақал 8 аломат" required autocomplete="current-password">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">Ворид шудан</button>
                    <a href="<?= $this->url('/register') ?>" class="btn btn-outline-secondary">Ҳисоб надоред? Бақайдгирӣ</a>
                </div>
            </form>
        </div>
    </div>
</div>
