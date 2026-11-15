<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Response;

class CertificateController extends Controller
{
    /**
     * Зеркашии PDF сертификат.
     */
    public function download(Certificate $certificate): Response
    {
        $user       = auth()->user();
        $enrollment = $certificate->enrollment;

        // Санҷиши моликияти сертификат
        if ($enrollment->user_id !== $user->id) {
            abort(403);
        }

        $enrollment->load(['course.author', 'user']);

        // Агар dompdf насб нашуда бошад, HTML ҷавоб бидиҳ
        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return response()->view('certificates.pdf', [
                'certificate' => $certificate,
                'enrollment'  => $enrollment,
            ]);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('certificates.pdf', [
            'certificate' => $certificate,
            'enrollment'  => $enrollment,
        ])->setPaper('a4', 'landscape');

        $filename = 'certificate-' . $certificate->certificate_code . '.pdf';

        return $pdf->download($filename);
    }
}
