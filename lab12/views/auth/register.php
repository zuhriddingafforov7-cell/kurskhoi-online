<?php
/**
 * Саҳифаи бақайдгирии донишҷӯ дар Biyomuz Academy.
 *
 * @var \App\Core\View $this
 * @var list<array<string, mixed>> $categories
 */
$categories = $categories ?? [];
?>
<nav aria-label="Роҳнамои навигатсия" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="<?= $this->url('/') ?>" class="text-decoration-none">Асосӣ</a></li>
        <li class="breadcrumb-item active" aria-current="page">Бақайдгирӣ</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-12 col-md-9 col-lg-7">
        <div class="card shadow-sm p-4">
            <div class="border-bottom pb-3 mb-4">
                <span class="badge bg-success-subtle text-success border mb-2">Ҳисоби донишҷӯ</span>
                <h1 class="h2 mb-1">Бақайдгирӣ дар Biyomuz Academy</h1>
                <p class="text-muted text-small mb-0">
                    Барои дастрасӣ ба курсҳои ройгон ва супоридани вазифаҳо маълумоти худро ворид кунед.
                </p>
            </div>

            <form id="registration-form" method="post" action="<?= $this->url('/register') ?>" novalidate>
                <div class="mb-3">
                    <label for="reg-full-name" class="form-label">Номи пурра <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="reg-full-name" name="full_name"
                           placeholder="Масалан: Фарҳод Раҳимов"
                           required minlength="3" maxlength="80"
                           aria-describedby="reg-full-name-error">
                    <div class="invalid-feedback" id="reg-full-name-error"></div>
                </div>

                <div class="mb-3">
                    <label for="reg-email" class="form-label">Почтаи электронӣ <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="reg-email" name="email"
                           placeholder="student@biyomuz.tj"
                           required maxlength="100"
                           aria-describedby="reg-email-error">
                    <div class="invalid-feedback" id="reg-email-error"></div>
                </div>

                <div class="mb-3">
                    <label for="reg-category" class="form-label">Самти асосии омӯзиш <span class="text-danger">*</span></label>
                    <select class="form-select" id="reg-category" name="category"
                            required aria-describedby="reg-category-error">
                        <option value="">Самти омӯзишро интихоб кунед...</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $this->e($category['slug']) ?>">
                                <?= $this->e($category['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback" id="reg-category-error"></div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-sm-6">
                        <label for="reg-password" class="form-label">Рамз (Пароль) <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="reg-password" name="password"
                               placeholder="Ҳадди ақал 8 аломат"
                               required minlength="8" maxlength="64"
                               aria-describedby="reg-password-error">
                        <div class="invalid-feedback" id="reg-password-error"></div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <label for="reg-password-confirmation" class="form-label">Такрори рамз <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="reg-password-confirmation" name="password_confirmation"
                               placeholder="Рамзро такрор кунед"
                               required minlength="8" maxlength="64"
                               aria-describedby="reg-password-confirmation-error">
                        <div class="invalid-feedback" id="reg-password-confirmation-error"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="reg-terms" name="terms"
                               required aria-describedby="reg-terms-error">
                        <label class="form-check-label text-small" for="reg-terms">
                            Бо қоидаҳои истифодаи платформаи Biyomuz Academy розӣ ҳастам <span class="text-danger">*</span>
                        </label>
                        <div class="invalid-feedback" id="reg-terms-error"></div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">Бақайдгирӣ</button>
                    <a href="<?= $this->url('/courses') ?>" class="btn btn-outline-secondary">Бозгашт ба каталоги курсҳо</a>
                </div>
            </form>

            <div id="registration-success" class="alert alert-success d-none mt-3 mb-0" role="status" aria-live="polite"></div>
        </div>
    </div>
</div>
