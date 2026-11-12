-- =====================================================================
-- Лоиҳа: Biyomuz Academy (Платформаи курсҳои онлайн)
-- Лабораторияи №7: Физическая модель базы данных
-- Файл: database/schema.sql
-- СУБД: MySQL 8.0+ / MariaDB 10.4+
-- Кодировка: utf8mb4, Collation: utf8mb4_unicode_ci, Муҳаррик: InnoDB
-- =====================================================================

DROP DATABASE IF EXISTS kurskhoi_online;
CREATE DATABASE kurskhoi_online
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE kurskhoi_online;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- 1. Ҷадвали `users` (Истифодабарандагон ва нақшҳо)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  email_verified_at TIMESTAMP NULL DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(255) NOT NULL DEFAULT 'student',
  remember_token VARCHAR(100) NULL DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT uq_users_email UNIQUE (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 2. Ҷадвали `categories` (Бахшҳои таълимии курсҳо)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  description TEXT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT uq_categories_slug UNIQUE (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 3. Ҷадвали `courses` (Курсҳои таълимӣ)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS courses;
CREATE TABLE courses (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  description TEXT NULL,
  author_id BIGINT UNSIGNED NOT NULL,
  category_id BIGINT UNSIGNED NULL DEFAULT NULL,
  status VARCHAR(255) NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_courses_author FOREIGN KEY (author_id)
    REFERENCES users (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT fk_courses_category FOREIGN KEY (category_id)
    REFERENCES categories (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
  INDEX idx_courses_title (title),
  INDEX idx_courses_status_category (status, category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 4. Ҷадвали `modules` (Модулҳои таълимии курс)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS modules;
CREATE TABLE modules (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  course_id BIGINT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT NULL,
  order_index INT UNSIGNED NOT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_modules_course FOREIGN KEY (course_id)
    REFERENCES courses (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT chk_modules_order CHECK (order_index >= 1),
  INDEX idx_modules_course (course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 5. Ҷадвали `lessons` (Дарсҳои таълимӣ дар дохили модул)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS lessons;
CREATE TABLE lessons (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  module_id BIGINT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  content_type VARCHAR(255) NOT NULL DEFAULT 'text',
  body_text LONGTEXT NULL,
  video_url VARCHAR(255) NULL,
  file_path VARCHAR(255) NULL,
  order_index INT UNSIGNED NOT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_lessons_module FOREIGN KEY (module_id)
    REFERENCES modules (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT chk_lessons_type CHECK (content_type IN ('text', 'video', 'file')),
  CONSTRAINT chk_lessons_order CHECK (order_index >= 1),
  INDEX idx_lessons_module (module_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 6. Ҷадвали `enrollments` (Номнависии донишҷӯён ба курс — робитаи M:N)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS enrollments;
CREATE TABLE enrollments (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  course_id BIGINT UNSIGNED NOT NULL,
  enrolled_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  status VARCHAR(255) NOT NULL DEFAULT 'active',
  completed_at DATETIME NULL DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_enrollments_user FOREIGN KEY (user_id)
    REFERENCES users (id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_enrollments_course FOREIGN KEY (course_id)
    REFERENCES courses (id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT uq_enrollments_user_course UNIQUE (user_id, course_id),
  CONSTRAINT chk_enrollments_status CHECK (status IN ('active', 'completed', 'cancelled')),
  INDEX idx_enrollments_user (user_id),
  INDEX idx_enrollments_course (course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 7. Ҷадвали `assignments` (Супоришҳои амалии дарс — 1:1 ихтиёрӣ)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS assignments;
CREATE TABLE assignments (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  lesson_id BIGINT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT NULL,
  due_date DATETIME NULL DEFAULT NULL,
  max_score SMALLINT UNSIGNED NOT NULL DEFAULT 100,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_assignments_lesson FOREIGN KEY (lesson_id)
    REFERENCES lessons (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT uq_assignments_lesson UNIQUE (lesson_id),
  CONSTRAINT chk_assignments_max_score CHECK (max_score > 0 AND max_score <= 100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 8. Ҷадвали `assignment_submissions` (Ҷавобҳои ирсолшуда ва баҳогузорӣ)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS assignment_submissions;
CREATE TABLE assignment_submissions (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  assignment_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  submission_text TEXT NULL,
  file_path VARCHAR(255) NULL,
  score SMALLINT UNSIGNED NULL DEFAULT NULL,
  feedback TEXT NULL,
  status VARCHAR(255) NOT NULL DEFAULT 'submitted',
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_submissions_assignment FOREIGN KEY (assignment_id)
    REFERENCES assignments (id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_submissions_user FOREIGN KEY (user_id)
    REFERENCES users (id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT uq_submissions_assignment_user UNIQUE (assignment_id, user_id),
  CONSTRAINT chk_submissions_score CHECK (score IS NULL OR (score >= 0 AND score <= 100)),
  CONSTRAINT chk_submissions_status CHECK (status IN ('submitted', 'approved', 'rejected')),
  INDEX idx_submissions_assignment (assignment_id),
  INDEX idx_submissions_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 9. Ҷадвали `certificates` (Шаҳодатномаҳои хатми курс — 1:1 бо enrollment)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS certificates;
CREATE TABLE certificates (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  enrollment_id BIGINT UNSIGNED NOT NULL,
  certificate_code VARCHAR(50) NOT NULL,
  issue_date DATE NOT NULL,
  pdf_path VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_certificates_enrollment FOREIGN KEY (enrollment_id)
    REFERENCES enrollments (id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT uq_certificates_enrollment UNIQUE (enrollment_id),
  CONSTRAINT uq_certificates_code UNIQUE (certificate_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- 10. Ҷадвали `lesson_progress` (Пешрафти омӯзиши дарсҳо — FR-05)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS lesson_progress;
CREATE TABLE lesson_progress (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  lesson_id BIGINT UNSIGNED NOT NULL,
  is_completed BOOLEAN NOT NULL DEFAULT 0,
  completed_at DATETIME NULL DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_progress_user FOREIGN KEY (user_id)
    REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_progress_lesson FOREIGN KEY (lesson_id)
    REFERENCES lessons (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT uq_lesson_progress_user_lesson UNIQUE (user_id, lesson_id),
  INDEX idx_progress_user (user_id),
  INDEX idx_progress_lesson (lesson_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
