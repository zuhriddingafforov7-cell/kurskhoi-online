<?php
/**
 * Корти курс дар саҳифаи асосӣ ва каталоги курсҳо.
 *
 * @var \App\Core\View $this
 * @var array<string, mixed> $course
 */
$allowedStatuses = ['published', 'pending', 'draft', 'rejected'];
$rawStatus = (string) ($course['status'] ?? 'draft');
$statusClass = in_array($rawStatus, $allowedStatuses, true) ? $rawStatus : 'draft';
$courseUrl = $this->url('/courses/' . (int) ($course['id'] ?? 0));
?>
<article class="course-card">
    <div class="course-thumb" aria-hidden="true">
        <?= $this->e($this->initials((string) ($course['title'] ?? 'БА'))) ?>
    </div>
    <div class="course-body">
        <h3 class="course-title">
            <a href="<?= $this->e($courseUrl) ?>"><?= $this->e($course['title'] ?? '') ?></a>
        </h3>
        <div class="course-meta">
            <span><strong>Муаллиф:</strong> <?= $this->e($course['author_name'] ?? 'Номаълум') ?></span>
            <span>&bull;</span>
            <span><strong>Категория:</strong> <?= $this->e($course['category_title'] ?? 'Умумӣ') ?></span>
            <span>&bull;</span>
            <span><?= (int) ($course['lessons_count'] ?? 0) ?> дарс</span>
            <span>&bull;</span>
            <span><?= $this->e($this->date($course['created_at'] ?? null)) ?></span>
        </div>
        <p class="course-desc">
            <?= $this->e($course['description'] ?? '') ?>
        </p>
    </div>
    <div class="course-actions">
        <span class="badge-status status-<?= $this->e($statusClass) ?>">
            <?= $this->e($this->label('course_status', $statusClass)) ?>
        </span>
        <a class="btn btn-primary btn-sm" href="<?= $this->e($courseUrl) ?>">Курсро дидан</a>
    </div>
</article>
