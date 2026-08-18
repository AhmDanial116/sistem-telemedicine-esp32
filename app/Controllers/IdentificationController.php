<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\DeviceModel;
use App\Models\DoctorModel;
use CodeIgniter\HTTP\RedirectResponse;

class IdentificationController extends BaseController
{
    protected DeviceModel $deviceModel;

    protected DoctorModel $doctorModel;

    protected AdminModel $adminModel;

    public function __construct()
    {
        $this->deviceModel = new DeviceModel();

        $this->doctorModel = new DoctorModel();

        $this->adminModel = new AdminModel();
    }

    /**
     * Halaman identifikasi utama.
     */
    public function index(): string
    {
        /*
         * Pengguna yang sudah teridentifikasi langsung
         * diarahkan ke dashboard sesuai rolenya.
         */
        $role = session()->get('role');

        if ($role === 'patient') {
            return redirect()
                ->to('/patient/dashboard')
                ->send();
        }

        if ($role === 'doctor') {
            return redirect()
                ->to('/doctor/dashboard')
                ->send();
        }

        if ($role === 'admin') {
            return redirect()
                ->to('/admin/dashboard')
                ->send();
        }

        return view('identification/index', [
            'title' => 'Identifikasi Pengguna',
        ]);
    }

    /**
     * Identifikasi pasien menggunakan MAC/Device ID ESP32.
     */
    public function patient(): RedirectResponse
    {
        $deviceId = trim(
            (string) $this->request->getPost(
                'device_id'
            )
        );

        if ($deviceId === '') {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'patient_error',
                    'Device ID wajib diisi.'
                );
        }

        $normalizedDeviceId =
            $this->deviceModel
            ->normalizeDeviceId($deviceId);

        if (
            strlen($normalizedDeviceId) !== 12
            || ! ctype_alnum($normalizedDeviceId)
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'patient_error',
                    'Device ID harus terdiri dari 12 karakter huruf atau angka.'
                );
        }

        $device =
            $this->deviceModel
            ->getActiveDeviceWithPatient(
                $normalizedDeviceId
            );

        if ($device === null) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'patient_error',
                    'Device ID tidak ditemukan, tidak aktif, atau belum terhubung dengan pasien.'
                );
        }

        session()->regenerate();

        session()->set([
            'identified' => true,
            'role' => 'patient',

            'user_id' =>
            (int) $device['patient_id'],

            'patient_id' =>
            (int) $device['patient_id'],

            'patient_code' =>
            $device['patient_code'],

            'user_name' =>
            $device['patient_name'],

            'device_record_id' =>
            (int) $device['id'],

            'device_id' =>
            $device['device_id'],
        ]);

        return redirect()
            ->to('/patient/dashboard')
            ->with(
                'success',
                'Identifikasi pasien berhasil.'
            );
    }

    /**
     * Identifikasi dokter menggunakan doctor_code.
     */
    public function doctor(): RedirectResponse
    {
        $doctorCode = trim(
            (string) $this->request->getPost(
                'doctor_code'
            )
        );

        if ($doctorCode === '') {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'doctor_error',
                    'ID dokter wajib diisi.'
                );
        }

        $doctor =
            $this->doctorModel
            ->findActiveByDoctorCode(
                $doctorCode
            );

        if ($doctor === null) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'doctor_error',
                    'ID dokter tidak ditemukan atau tidak aktif.'
                );
        }

        session()->regenerate();

        session()->set([
            'identified' => true,
            'role' => 'doctor',

            'user_id' =>
            (int) $doctor['id'],

            'doctor_id' =>
            (int) $doctor['id'],

            'doctor_code' =>
            $doctor['doctor_code'],

            'user_name' =>
            $doctor['name'],

            'specialization' =>
            $doctor['specialization'],
        ]);

        return redirect()
            ->to('/doctor/dashboard')
            ->with(
                'success',
                'Identifikasi dokter berhasil.'
            );
    }

    /**
     * Identifikasi admin menggunakan admin_code.
     */
    public function admin(): RedirectResponse
    {
        $adminCode = trim(
            (string) $this->request->getPost(
                'admin_code'
            )
        );

        if ($adminCode === '') {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'admin_error',
                    'ID admin wajib diisi.'
                );
        }

        $admin =
            $this->adminModel
            ->findActiveByAdminCode(
                $adminCode
            );

        if ($admin === null) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'admin_error',
                    'ID admin tidak ditemukan atau tidak aktif.'
                );
        }

        session()->regenerate();

        session()->set([
            'identified' => true,
            'role' => 'admin',

            'user_id' =>
            (int) $admin['id'],

            'admin_id' =>
            (int) $admin['id'],

            'admin_code' =>
            $admin['admin_code'],

            'user_name' =>
            $admin['name'],
        ]);

        return redirect()
            ->to('/admin/dashboard')
            ->with(
                'success',
                'Identifikasi admin berhasil.'
            );
    }

    /**
     * Hapus sesi identifikasi.
     */
    public function logout(): RedirectResponse
    {
        session()->destroy();

        return redirect()
            ->to('/')
            ->with(
                'success',
                'Anda berhasil keluar dari sistem.'
            );
    }
}
