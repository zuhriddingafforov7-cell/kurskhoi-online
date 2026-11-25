<?php
/**
 * Саҳифаи умумии хатогиҳои HTTP (403, 404, 405, 500).
 *
 * @var \App\Core\View $this
 */
$code = (int) ($code ?? 404);
$titleText = (string) ($title ?? 'Саҳифа ёфт нашуд');
$messageText = (string) ($message ?? 'Суроғаи дархостшуда ёфт нашуд.');
?>
<section class="row justify-content-center py-4" aria-labelledby="error-title">
    <div class="col-12 col-md-9 col-lg-7">
        <div class="card shadow-sm p-4 p-md-5 text-center">
            <div class="mb-3">
                <span class="badge bg-success-subtle text-success border px-3 py-2 fs-6">
                    HTTP <?= $this->e($code) ?>
                </span>
            </div>
            <h1 id="error-title" class="h2 mb-3"><?= $this->e($titleText) ?></h1>
            <p class="text-muted mb-4"><?= $this->e($messageText) ?></p>
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <a class="btn btn-primary" href="<?= $this->url('/') ?>">Ба саҳифаи асосӣ</a>
                <a class="btn btn-outline-secondary" href="<?= $this->url('/courses') ?>">Каталоги курсҳо</a>
            </div>
        </div>
    </div>
</section>
