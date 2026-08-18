<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class RoleFilter implements FilterInterface
{
    /**
     * Dijalankan sebelum controller.
     *
     * Contoh:
     * filter => role:patient
     * filter => role:doctor
     * filter => role:admin
     */
    public function before(
        RequestInterface $request,
        $arguments = null
    ) {
        $session = Services::session();

        //--------------------------------------------------
        // Belum teridentifikasi
        //--------------------------------------------------

        if (
            ! $session->get('identified')
            || ! $session->has('role')
        ) {
            return redirect()
                ->to('/')
                ->with(
                    'identification_error',
                    'Silakan lakukan identifikasi terlebih dahulu.'
                );
        }

        //--------------------------------------------------
        // Ambil role yang diizinkan dari route
        //--------------------------------------------------

        $allowedRoles = is_array($arguments)
            ? $arguments
            : [];

        /*
         * Jika filter dipasang tanpa argumen,
         * cukup pastikan pengguna sudah teridentifikasi.
         */
        if ($allowedRoles === []) {
            return null;
        }

        //--------------------------------------------------
        // Cek role pengguna
        //--------------------------------------------------

        $currentRole = (string) $session->get('role');

        if (! in_array($currentRole, $allowedRoles, true)) {
            return $this->redirectByRole(
                $currentRole
            )->with(
                'access_error',
                'Anda tidak memiliki akses ke halaman tersebut.'
            );
        }

        return null;
    }

    /**
     * Tidak membutuhkan proses setelah controller.
     */
    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        return null;
    }

    /**
     * Arahkan pengguna kembali ke dashboard miliknya.
     */
    private function redirectByRole(
        string $role
    ) {
        return match ($role) {
            'patient' => redirect()
                ->to('/patient/dashboard'),

            'doctor' => redirect()
                ->to('/doctor/dashboard'),

            'admin' => redirect()
                ->to('/admin/dashboard'),

            default => redirect()
                ->to('/'),
        };
    }
}
