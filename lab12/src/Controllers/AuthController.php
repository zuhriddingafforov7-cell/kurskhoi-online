<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * Контроллери саҳифаҳои воридшавӣ ва бақайдгирӣ (танҳо намоиш дар ЛР №12).
 */
final class AuthController extends Controller
{
    /**
     * GET /login — Намоиши шакли воридшавӣ ба платформа.
     */
    public function showLogin(): void
    {
        $this->render('auth/login', [
            'title'  => 'Воридшавӣ',
            'active' => 'login',
        ]);
    }

    /**
     * GET /register — Намоиши шакли бақайдгирии донишҷӯи нав.
     */
    public function showRegister(): void
    {
        $this->render('auth/register', [
            'title'      => 'Бақайдгирӣ',
            'active'     => 'register',
            'categories' => array_values(CategoryController::DEMO_CATEGORIES),
        ]);
    }
}
