<?php
/**
 * Макети умумии саҳифаҳои Biyomuz Academy (Лабораторияи №12).
 *
 * @var \App\Core\View $this
 * @var string $content
 */
$active = $active ?? '';
$pageTitle = $title ?? 'Саҳифаи асосӣ';
?>
<!doctype html>
<html lang="tg">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->e($pageTitle) ?> — Biyomuz Academy</title>
    <meta name="description" content="Платформаи миллии курсҳои онлайни барномасозӣ, веб-дизайн ва технологияҳои иттилоотӣ бо забони тоҷикӣ.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link href="<?= $this->url('/assets/css/app.css') ?>" rel="stylesheet">
</head>
<body>
<?= $this->partial('nav', ['active' => $active]) ?>

<main class="container py-4 flex-grow-1">
    <?= $content ?>
</main>

<?= $this->partial('footer') ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script type="module" src="<?= $this->url('/assets/js/app.js') ?>"></script>
</body>
</html>
