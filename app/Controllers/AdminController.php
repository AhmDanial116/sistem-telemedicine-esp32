<?php

namespace App\Controllers;

use App\Models\ActivityLogModel;
use CodeIgniter\HTTP\RedirectResponse;
use Throwable;

class AdminController extends BaseController
{
    protected ActivityLogModel $activityLogModel;

    public function __construct()
    {
        $this->activityLogModel =
            new ActivityLogModel();
    }

    public function dashboard(): string
    {
        $adminId =
            (int) session()->get('admin_id');

        if ($adminId <= 0) {
            return redirect()
                ->to('/')
                ->with(
                    'identification_error',
                    'Sesi admin tidak valid.'
                )
                ->send();
        }

        $db = db_connect();

        $doctors = $db
            ->table('doctors')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        $patients = $db
            ->table('patients')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        $devices = $db
            ->table('devices d')
            ->select([
                'd.*',
                'p.patient_code',
                'p.name AS patient_name',
            ])
            ->join(
                'patients p',
                'p.id = d.patient_id',
                'left'
            )
            ->orderBy('d.created_at', 'DESC')
            ->get()
            ->getResultArray();

        //--------------------------------------------------
        // Status koneksi perangkat
        //--------------------------------------------------

        $nowTimestamp = time();

        foreach ($devices as &$device) {
            $lastSeenAt =
                $device['last_seen_at']
                ?? null;

            $device['connection_status'] =
                'never_connected';

            $device['seconds_since_seen'] =
                null;

            if (! empty($lastSeenAt)) {
                $lastSeenTimestamp =
                    strtotime($lastSeenAt);

                if ($lastSeenTimestamp !== false) {
                    $secondsSinceSeen =
                        max(
                            0,
                            $nowTimestamp
                                - $lastSeenTimestamp
                        );

                    $device['seconds_since_seen'] =
                        $secondsSinceSeen;

                    $device['connection_status'] =
                        $secondsSinceSeen <= 120
                        ? 'online'
                        : 'offline';
                }
            }
        }

        unset($device);

        $assignments = $db
            ->table('doctor_assignments da')
            ->select([
                'da.id AS assignment_id',
                'da.doctor_id',
                'da.patient_id',
                'da.assigned_by',
                'da.assigned_at',
                'da.ended_at',
                'da.status',
                'da.transfer_reason',

                'd.doctor_code',
                'd.name AS doctor_name',
                'd.specialization',

                'p.patient_code',
                'p.name AS patient_name',
            ])
            ->join(
                'doctors d',
                'd.id = da.doctor_id',
                'inner'
            )
            ->join(
                'patients p',
                'p.id = da.patient_id',
                'inner'
            )
            ->orderBy('da.assigned_at', 'DESC')
            ->get()
            ->getResultArray();

        $recentActivities = $db
            ->table('activity_logs')
            ->orderBy('created_at', 'DESC')
            ->get(20)
            ->getResultArray();

        $summary = [
            'total_doctors' =>
            count($doctors),

            'active_doctors' =>
            count(
                array_filter(
                    $doctors,
                    static fn(array $doctor): bool =>
                    $doctor['status'] === 'active'
                )
            ),

            'total_patients' =>
            count($patients),

            'active_patients' =>
            count(
                array_filter(
                    $patients,
                    static fn(array $patient): bool =>
                    $patient['status'] === 'active'
                )
            ),

            'total_devices' =>
            count($devices),

            'active_devices' =>
            count(
                array_filter(
                    $devices,
                    static fn(array $device): bool =>
                    $device['status'] === 'active'
                )
            ),

            'active_assignments' =>
            count(
                array_filter(
                    $assignments,
                    static fn(array $assignment): bool =>
                    $assignment['status'] === 'active'
                )
            ),

            'online_devices' =>
            count(
                array_filter(
                    $devices,
                    static fn(array $device): bool => (
                        $device['connection_status'] ?? null
                    ) === 'online'
                )
            ),

            'offline_devices' =>
            count(
                array_filter(
                    $devices,
                    static fn(array $device): bool => (
                        $device['connection_status'] ?? null
                    ) === 'offline'
                )
            ),
        ];

        return view(
            'admin/dashboard',
            [
                'title' =>
                'Dashboard Admin',

                'adminName' =>
                session()->get('user_name'),

                'adminCode' =>
                session()->get('admin_code'),

                'summary' =>
                $summary,

                'doctors' =>
                $doctors,

                'patients' =>
                $patients,

                'devices' =>
                $devices,

                'assignments' =>
                $assignments,

                'recentActivities' =>
                $recentActivities,
            ]
        );
    }

    public function assignDoctor(): RedirectResponse
    {
        $adminId =
            (int) session()->get('admin_id');

        $patientId =
            (int) $this->request->getPost('patient_id');

        $doctorId =
            (int) $this->request->getPost('doctor_id');

        $reason =
            trim(
                (string) $this->request
                    ->getPost('transfer_reason')
            );

        if (
            $adminId <= 0
            || $patientId <= 0
            || $doctorId <= 0
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'assignment_error',
                    'Data penugasan tidak lengkap.'
                );
        }

        $db = db_connect();

        $patient = $db
            ->table('patients')
            ->where('id', $patientId)
            ->where('status', 'active')
            ->get()
            ->getRowArray();

        if ($patient === null) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'assignment_error',
                    'Pasien tidak ditemukan atau tidak aktif.'
                );
        }

        $doctor = $db
            ->table('doctors')
            ->where('id', $doctorId)
            ->where('status', 'active')
            ->get()
            ->getRowArray();

        if ($doctor === null) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'assignment_error',
                    'Dokter tidak ditemukan atau tidak aktif.'
                );
        }

        $activeAssignment = $db
            ->table('doctor_assignments')
            ->where('patient_id', $patientId)
            ->where('status', 'active')
            ->get()
            ->getRowArray();

        if (
            $activeAssignment !== null
            && (int) $activeAssignment['doctor_id'] === $doctorId
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'assignment_error',
                    'Pasien sudah ditangani oleh dokter tersebut.'
                );
        }

        /*
     * Kalau pasien sudah memiliki dokter,
     * alasan transfer wajib diisi.
     */
        if (
            $activeAssignment !== null
            && $reason === ''
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'assignment_error',
                    'Alasan transfer wajib diisi ketika memindahkan pasien.'
                );
        }

        $db->transStart();

        try {
            /*
         * Akhiri penugasan lama jika ada.
         */
            if ($activeAssignment !== null) {
                $db
                    ->table('doctor_assignments')
                    ->where(
                        'id',
                        (int) $activeAssignment['id']
                    )
                    ->update([
                        'status' => 'ended',
                        'ended_at' => date('Y-m-d H:i:s'),
                        'transfer_reason' => $reason,
                    ]);
            }

            /*
         * Buat penugasan baru.
         */
            $db
                ->table('doctor_assignments')
                ->insert([
                    'doctor_id' => $doctorId,
                    'patient_id' => $patientId,
                    'assigned_by' => $adminId,
                    'assigned_at' => date('Y-m-d H:i:s'),
                    'ended_at' => null,
                    'status' => 'active',
                    'transfer_reason' => null,
                ]);

            $newAssignmentId =
                (int) $db->insertID();

            /*
         * Catat aktivitas admin.
         */
            $action =
                $activeAssignment === null
                ? 'assign_doctor'
                : 'transfer_patient';

            $description =
                $activeAssignment === null
                ? sprintf(
                    'Admin menugaskan dokter %s kepada pasien %s.',
                    $doctor['name'],
                    $patient['name']
                )
                : sprintf(
                    'Admin memindahkan pasien %s ke dokter %s. Alasan: %s',
                    $patient['name'],
                    $doctor['name'],
                    $reason
                );

            $this->activityLogModel
                ->record(
                    'admin',
                    $adminId,
                    $action,
                    $description,
                    'doctor_assignment',
                    $newAssignmentId,
                    $this->request
                        ->getIPAddress()
                );

            $db->transComplete();
        } catch (Throwable $exception) {
            $db->transRollback();

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'assignment_error',
                    ENVIRONMENT === 'development'
                        ? $exception->getMessage()
                        : 'Terjadi kesalahan saat menyimpan penugasan.'
                );
        }

        if ($db->transStatus() === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'assignment_error',
                    'Penugasan gagal disimpan.'
                );
        }

        return redirect()
            ->to('/admin/dashboard#assignmentsSection')
            ->with(
                'assignment_success',
                $activeAssignment === null
                    ? 'Dokter berhasil ditugaskan kepada pasien.'
                    : 'Pasien berhasil dipindahkan ke dokter baru.'
            );
    }

    public function storeDoctor(): RedirectResponse
    {
        $adminId = (int) session()->get('admin_id');

        $doctorCode = strtoupper(
            trim((string) $this->request->getPost('doctor_code'))
        );

        $name = trim(
            (string) $this->request->getPost('name')
        );

        $specialization = trim(
            (string) $this->request->getPost('specialization')
        );

        $phone = trim(
            (string) $this->request->getPost('phone')
        );

        $email = trim(
            (string) $this->request->getPost('email')
        );

        $practiceLocation = trim(
            (string) $this->request->getPost('practice_location')
        );

        if ($adminId <= 0) {
            return redirect()
                ->to('/')
                ->with(
                    'identification_error',
                    'Sesi admin tidak valid.'
                );
        }

        if ($doctorCode === '' || $name === '') {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'doctor_error',
                    'Kode dokter dan nama dokter wajib diisi.'
                );
        }

        if (
            strlen($doctorCode) > 30
            || strlen($name) > 100
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'doctor_error',
                    'Kode atau nama dokter melebihi batas karakter.'
                );
        }

        if (
            $email !== ''
            && ! filter_var($email, FILTER_VALIDATE_EMAIL)
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'doctor_error',
                    'Format email dokter tidak valid.'
                );
        }

        $db = db_connect();

        $existingDoctor = $db
            ->table('doctors')
            ->groupStart()
            ->where('doctor_code', $doctorCode)
            ->orGroupStart()
            ->where('email', $email)
            ->where('email IS NOT NULL', null, false)
            ->groupEnd()
            ->groupEnd()
            ->get()
            ->getRowArray();

        /*
     * Jika email kosong, cukup cek doctor_code.
     */
        if ($email === '') {
            $existingDoctor = $db
                ->table('doctors')
                ->where('doctor_code', $doctorCode)
                ->get()
                ->getRowArray();
        }

        if ($existingDoctor !== null) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'doctor_error',
                    'Kode dokter atau email sudah terdaftar.'
                );
        }

        $db
            ->table('doctors')
            ->insert([
                'doctor_code' => $doctorCode,
                'name' => $name,
                'specialization' =>
                $specialization !== ''
                    ? $specialization
                    : null,
                'phone' =>
                $phone !== ''
                    ? $phone
                    : null,
                'email' =>
                $email !== ''
                    ? $email
                    : null,
                'practice_location' =>
                $practiceLocation !== ''
                    ? $practiceLocation
                    : null,
                'status' => 'active',
            ]);

        $doctorId = (int) $db->insertID();

        if ($doctorId <= 0) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'doctor_error',
                    'Data dokter gagal disimpan.'
                );
        }

        $this->activityLogModel->record(
            'admin',
            $adminId,
            'create_doctor',
            sprintf(
                'Admin menambahkan dokter %s (%s).',
                $name,
                $doctorCode
            ),
            'doctor',
            $doctorId,
            $this->request->getIPAddress()
        );

        return redirect()
            ->to('/admin/dashboard#doctorsSection')
            ->with(
                'doctor_success',
                'Data dokter berhasil ditambahkan.'
            );
    }

    public function updateDoctor(
        int $doctorId
    ): RedirectResponse {
        $adminId = (int) session()->get('admin_id');

        $db = db_connect();

        $doctor = $db
            ->table('doctors')
            ->where('id', $doctorId)
            ->get()
            ->getRowArray();

        if ($doctor === null) {
            return redirect()
                ->to('/admin/dashboard#doctorsSection')
                ->with(
                    'doctor_error',
                    'Data dokter tidak ditemukan.'
                );
        }

        $doctorCode = strtoupper(
            trim((string) $this->request->getPost('doctor_code'))
        );

        $name = trim(
            (string) $this->request->getPost('name')
        );

        $specialization = trim(
            (string) $this->request->getPost('specialization')
        );

        $phone = trim(
            (string) $this->request->getPost('phone')
        );

        $email = trim(
            (string) $this->request->getPost('email')
        );

        $practiceLocation = trim(
            (string) $this->request->getPost('practice_location')
        );

        if ($doctorCode === '' || $name === '') {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'doctor_error',
                    'Kode dokter dan nama dokter wajib diisi.'
                );
        }

        if (
            $email !== ''
            && ! filter_var($email, FILTER_VALIDATE_EMAIL)
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'doctor_error',
                    'Format email dokter tidak valid.'
                );
        }

        $duplicate = $db
            ->table('doctors')
            ->where('id !=', $doctorId)
            ->groupStart()
            ->where('doctor_code', $doctorCode);

        if ($email !== '') {
            $duplicate->orWhere('email', $email);
        }

        $duplicate = $duplicate
            ->groupEnd()
            ->get()
            ->getRowArray();

        if ($duplicate !== null) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'doctor_error',
                    'Kode dokter atau email telah digunakan dokter lain.'
                );
        }

        $db
            ->table('doctors')
            ->where('id', $doctorId)
            ->update([
                'doctor_code' => $doctorCode,
                'name' => $name,
                'specialization' =>
                $specialization !== ''
                    ? $specialization
                    : null,
                'phone' =>
                $phone !== ''
                    ? $phone
                    : null,
                'email' =>
                $email !== ''
                    ? $email
                    : null,
                'practice_location' =>
                $practiceLocation !== ''
                    ? $practiceLocation
                    : null,
            ]);

        $this->activityLogModel->record(
            'admin',
            $adminId,
            'update_doctor',
            sprintf(
                'Admin memperbarui data dokter %s (%s).',
                $name,
                $doctorCode
            ),
            'doctor',
            $doctorId,
            $this->request->getIPAddress()
        );

        return redirect()
            ->to('/admin/dashboard#doctorsSection')
            ->with(
                'doctor_success',
                'Data dokter berhasil diperbarui.'
            );
    }

    public function toggleDoctorStatus(
        int $doctorId
    ): RedirectResponse {
        $adminId = (int) session()->get('admin_id');

        $db = db_connect();

        $doctor = $db
            ->table('doctors')
            ->where('id', $doctorId)
            ->get()
            ->getRowArray();

        if ($doctor === null) {
            return redirect()
                ->to('/admin/dashboard#doctorsSection')
                ->with(
                    'doctor_error',
                    'Data dokter tidak ditemukan.'
                );
        }

        $newStatus =
            $doctor['status'] === 'active'
            ? 'inactive'
            : 'active';

        /*
     * Dokter tidak boleh dinonaktifkan jika masih
     * memiliki pasien aktif.
     */
        if ($newStatus === 'inactive') {
            $activeAssignmentCount = $db
                ->table('doctor_assignments')
                ->where('doctor_id', $doctorId)
                ->where('status', 'active')
                ->countAllResults();

            if ($activeAssignmentCount > 0) {
                return redirect()
                    ->to('/admin/dashboard#doctorsSection')
                    ->with(
                        'doctor_error',
                        'Dokter masih memiliki pasien aktif. Transfer atau akhiri penugasannya terlebih dahulu.'
                    );
            }
        }

        $db
            ->table('doctors')
            ->where('id', $doctorId)
            ->update([
                'status' => $newStatus,
            ]);

        $this->activityLogModel->record(
            'admin',
            $adminId,
            'toggle_doctor_status',
            sprintf(
                'Admin mengubah status dokter %s menjadi %s.',
                $doctor['name'],
                $newStatus
            ),
            'doctor',
            $doctorId,
            $this->request->getIPAddress()
        );

        return redirect()
            ->to('/admin/dashboard#doctorsSection')
            ->with(
                'doctor_success',
                $newStatus === 'active'
                    ? 'Dokter berhasil diaktifkan.'
                    : 'Dokter berhasil dinonaktifkan.'
            );
    }

    public function assignDeviceForm(
        int $deviceId
    ): string|RedirectResponse {
        $adminId =
            (int) session()->get('admin_id');

        if ($adminId <= 0) {
            return redirect()
                ->to('/')
                ->with(
                    'identification_error',
                    'Sesi admin tidak valid.'
                );
        }

        $db = db_connect();

        $device = $db
            ->table('devices')
            ->where('id', $deviceId)
            ->get()
            ->getRowArray();

        if ($device === null) {
            return redirect()
                ->to('/admin/dashboard#devicesSection')
                ->with(
                    'assignment_error',
                    'Perangkat tidak ditemukan.'
                );
        }

        if (! empty($device['patient_id'])) {
            return redirect()
                ->to('/admin/dashboard#devicesSection')
                ->with(
                    'assignment_error',
                    'Perangkat sudah terhubung ke pasien.'
                );
        }

        $patients = $db
            ->table('patients')
            ->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        return view(
            'admin/assign_device',
            [
                'title' =>
                'Assign Perangkat',

                'adminName' =>
                session()->get('user_name'),

                'adminCode' =>
                session()->get('admin_code'),

                'device' =>
                $device,

                'patients' =>
                $patients,
            ]
        );
    }

    public function assignDevice(
        int $deviceId
    ): RedirectResponse {
        $adminId =
            (int) session()->get('admin_id');

        $patientId =
            (int) $this->request
                ->getPost('patient_id');

        if (
            $adminId <= 0
            || $deviceId <= 0
            || $patientId <= 0
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'assignment_error',
                    'Data assign perangkat tidak lengkap.'
                );
        }

        $db = db_connect();

        $device = $db
            ->table('devices')
            ->where('id', $deviceId)
            ->get()
            ->getRowArray();

        if ($device === null) {
            return redirect()
                ->to('/admin/dashboard#devicesSection')
                ->with(
                    'assignment_error',
                    'Perangkat tidak ditemukan.'
                );
        }

        if (! empty($device['patient_id'])) {
            return redirect()
                ->to('/admin/dashboard#devicesSection')
                ->with(
                    'assignment_error',
                    'Perangkat sudah terhubung ke pasien lain.'
                );
        }

        $patient = $db
            ->table('patients')
            ->where('id', $patientId)
            ->where('status', 'active')
            ->get()
            ->getRowArray();

        if ($patient === null) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'assignment_error',
                    'Pasien tidak ditemukan atau tidak aktif.'
                );
        }

        $existingDevice = $db
            ->table('devices')
            ->where('patient_id', $patientId)
            ->where('status', 'active')
            ->get()
            ->getRowArray();

        if ($existingDevice !== null) {

            $db
                ->table('devices')
                ->where('id', $existingDevice['id'])
                ->update([
                    'patient_id' => null,
                    'status'     => 'inactive',
                ]);

            $this->activityLogModel->record(
                'admin',
                $adminId,
                'device_transfer',
                sprintf(
                    'Perangkat %s dilepas dari pasien %s.',
                    $existingDevice['device_id'],
                    $patient['name']
                ),
                'device',
                $existingDevice['id'],
                $this->request->getIPAddress()
            );
        }

        $db
            ->table('devices')
            ->where('id', $deviceId)
            ->update([
                'patient_id' =>
                $patientId,

                'status' =>
                'active',
            ]);

        $this->activityLogModel->record(
            'admin',
            $adminId,
            'assign_device',
            sprintf(
                'Admin menghubungkan perangkat %s kepada pasien %s.',
                $device['device_id'],
                $patient['name']
            ),
            'device',
            $deviceId,
            $this->request->getIPAddress()
        );

        return redirect()
            ->to('/admin/dashboard#devicesSection')
            ->with(
                'assignment_success',
                'Perangkat berhasil dihubungkan kepada pasien.'
            );
    }
}
