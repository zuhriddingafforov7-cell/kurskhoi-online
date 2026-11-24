<?php

declare(strict_types=1);

namespace Src\Repositories;

use PDO;
use PDOException;
use Throwable;

/**
 * Репозиторияи номнависии донишҷӯён ва пешрафти аввалияи дарсҳо (PDO + Transaction).
 *
 * Як амалиёти ягона ду ҷадвалро (`enrollments` ва `lesson_progress`) дар дохили
 * транзаксияи PDO тағйир медиҳад (`beginTransaction` -> `commit` / `rollBack`).
 */
final class EnrollmentRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * Санҷидани он ки донишҷӯ аллакай ба курс номнавис шудааст ё не.
     */
    public function isEnrolled(int $userId, int $courseId): bool
    {
        if ($userId <= 0 || $courseId <= 0) {
            return false;
        }

        $stmt = $this->pdo->prepare(
            'SELECT 1 FROM enrollments WHERE user_id = :user_id AND course_id = :course_id LIMIT 1'
        );
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    /**
     * Ёфтани сабти номнависии донишҷӯ дар курси мушаххас.
     *
     * @return array<string, mixed>|null
     */
    public function findByUserAndCourse(int $userId, int $courseId): ?array
    {
        if ($userId <= 0 || $courseId <= 0) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            'SELECT id, user_id, course_id, enrolled_at, status, completed_at, created_at, updated_at
             FROM enrollments
             WHERE user_id = :user_id AND course_id = :course_id
             LIMIT 1'
        );
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /**
     * Гирифтани рӯйхати ID-ҳои дарсҳои курс аз рӯйи тартиби модул ва дарс.
     *
     * @return list<int>
     */
    public function getCourseLessonIds(int $courseId): array
    {
        if ($courseId <= 0) {
            return [];
        }

        $stmt = $this->pdo->prepare(
            'SELECT l.id
             FROM lessons l
             INNER JOIN modules m ON m.id = l.module_id
             WHERE m.course_id = :course_id
             ORDER BY m.order_index ASC, l.order_index ASC, l.id ASC'
        );
        $stmt->bindValue(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    /**
     * Хондани сабтҳои пешрафти донишҷӯ барои курс аз ҷадвали `lesson_progress`.
     *
     * @return list<array<string, mixed>>
     */
    public function getStudentCourseProgress(int $userId, int $courseId): array
    {
        if ($userId <= 0 || $courseId <= 0) {
            return [];
        }

        $stmt = $this->pdo->prepare(
            'SELECT lp.id, lp.user_id, lp.lesson_id, lp.is_completed, lp.completed_at, lp.created_at, lp.updated_at
             FROM lesson_progress lp
             INNER JOIN lessons l ON l.id = lp.lesson_id
             INNER JOIN modules m ON m.id = l.module_id
             WHERE lp.user_id = :user_id AND m.course_id = :course_id
             ORDER BY lp.lesson_id ASC'
        );
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':course_id', $courseId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Номнависии донишҷӯ ба курс ва сохтани пешрафти ибтидоӣ дар як транзаксияи PDO.
     *
     * Амалиёти 1: Сабти нав дар `enrollments`.
     * Амалиёти 2: Сабтҳои ибтидоӣ дар `lesson_progress` (`is_completed = 0`) барои дарсҳои курс.
     *
     * @param  list<int>|null  $overrideLessonIds  Рӯйхати ихтиёрии ID-и дарсҳо (аз ҷумла барои санҷиши хатогӣ/rollback).
     * @return array{already_enrolled:bool, enrollment_id:int, progress_created:int}
     *
     * @throws Throwable
     */
    public function enrollStudentWithProgress(int $userId, int $courseId, ?array $overrideLessonIds = null): array
    {
        $existing = $this->findByUserAndCourse($userId, $courseId);
        if ($existing !== null) {
            return [
                'already_enrolled' => true,
                'enrollment_id'    => (int) $existing['id'],
                'progress_created' => 0,
            ];
        }

        $lessonIds = $overrideLessonIds ?? $this->getCourseLessonIds($courseId);
        $existingLessonIds = $overrideLessonIds === null
            ? $this->getExistingProgressLessonIds($userId, $lessonIds)
            : [];

        $ownTransaction = !$this->pdo->inTransaction();
        if ($ownTransaction) {
            $this->pdo->beginTransaction();
        } else {
            // Барои мутобиқат бо транзаксияи берунии тестҳои RefreshDatabase
            $this->pdo->exec('SAVEPOINT sp_enroll_tx');
        }

        try {
            $now = date('Y-m-d H:i:s');

            // Амалиёти 1: Ворид кардани сабти номнависӣ дар ҷадвали `enrollments`
            $enrollStmt = $this->pdo->prepare(
                'INSERT INTO enrollments (user_id, course_id, enrolled_at, status, completed_at, created_at, updated_at)
                 VALUES (:user_id, :course_id, :enrolled_at, :status, NULL, :created_at, :updated_at)'
            );
            $enrollStmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $enrollStmt->bindValue(':course_id', $courseId, PDO::PARAM_INT);
            $enrollStmt->bindValue(':enrolled_at', $now, PDO::PARAM_STR);
            $enrollStmt->bindValue(':status', 'active', PDO::PARAM_STR);
            $enrollStmt->bindValue(':created_at', $now, PDO::PARAM_STR);
            $enrollStmt->bindValue(':updated_at', $now, PDO::PARAM_STR);
            $enrollStmt->execute();

            $enrollmentId = (int) $this->pdo->lastInsertId();

            // Амалиёти 2: Ворид кардани сабтҳои ибтидоии пешрафт дар ҷадвали `lesson_progress`
            $progressStmt = $this->pdo->prepare(
                'INSERT INTO lesson_progress (user_id, lesson_id, is_completed, completed_at, created_at, updated_at)
                 VALUES (:user_id, :lesson_id, :is_completed, NULL, :created_at, :updated_at)'
            );

            $progressCreated = 0;
            foreach ($lessonIds as $lessonId) {
                $lessonIdInt = (int) $lessonId;
                if ($overrideLessonIds === null && in_array($lessonIdInt, $existingLessonIds, true)) {
                    continue;
                }

                $progressStmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
                $progressStmt->bindValue(':lesson_id', $lessonIdInt, PDO::PARAM_INT);
                $progressStmt->bindValue(':is_completed', 0, PDO::PARAM_INT);
                $progressStmt->bindValue(':created_at', $now, PDO::PARAM_STR);
                $progressStmt->bindValue(':updated_at', $now, PDO::PARAM_STR);
                $progressStmt->execute();
                $progressCreated++;
            }

            if ($ownTransaction) {
                $this->pdo->commit();
            } else {
                $this->pdo->exec('RELEASE SAVEPOINT sp_enroll_tx');
            }

            return [
                'already_enrolled' => false,
                'enrollment_id'    => $enrollmentId,
                'progress_created' => $progressCreated,
            ];
        } catch (Throwable $e) {
            if ($ownTransaction && $this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            } elseif (!$ownTransaction) {
                $this->pdo->exec('ROLLBACK TO SAVEPOINT sp_enroll_tx');
                $this->pdo->exec('RELEASE SAVEPOINT sp_enroll_tx');
            }

            // Пешгирии хато ҳангоми пахши дубораи ҳамзамон (unique key дар enrollments)
            if ($e instanceof PDOException && $this->isDuplicateEnrollmentException($e)) {
                $raceExisting = $this->findByUserAndCourse($userId, $courseId);
                if ($raceExisting !== null) {
                    return [
                        'already_enrolled' => true,
                        'enrollment_id'    => (int) $raceExisting['id'],
                        'progress_created' => 0,
                    ];
                }
            }

            throw $e;
        }
    }

    /**
     * @param  list<int>  $lessonIds
     * @return list<int>
     */
    private function getExistingProgressLessonIds(int $userId, array $lessonIds): array
    {
        if ($lessonIds === []) {
            return [];
        }

        $placeholders = [];
        foreach ($lessonIds as $idx => $lessonId) {
            $placeholders[] = ':lid_' . $idx;
        }

        $sql = sprintf(
            'SELECT lesson_id FROM lesson_progress WHERE user_id = :user_id AND lesson_id IN (%s)',
            implode(', ', $placeholders)
        );
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        foreach ($lessonIds as $idx => $lessonId) {
            $stmt->bindValue(':lid_' . $idx, (int) $lessonId, PDO::PARAM_INT);
        }
        $stmt->execute();

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    private function isDuplicateEnrollmentException(PDOException $e): bool
    {
        $msg = $e->getMessage();

        return str_contains($msg, 'uq_enrollments_user_course')
            || str_contains($msg, 'enrollments.user_id, enrollments.course_id');
    }
}
