<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use App\Models\MedicalNoteModel;
use CodeIgniter\HTTP\RedirectResponse;
use App\Models\ConsultationRequestModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\NotificationModel;

class DoctorController extends BaseController
{

    protected ConsultationRequestModel $consultationRequestModel;

    protected MedicalNoteModel $medicalNoteModel;

    public function __construct()
    {
        $this->medicalNoteModel =
            new MedicalNoteModel();
        $this->consultationRequestModel =
            new ConsultationRequestModel();
    }

    /**
     * Dashboard utama dokter.
     */
    public function dashboard(): string
    {
        $doctorId = (int) session()->get('doctor_id');

        //--------------------------------------------------
        // NOTIFIKASI DOKTER
        //--------------------------------------------------

        $db = db_connect();

        $notifications = $db
            ->table('notifications')
            ->where('recipient_role', 'doctor')
            ->where('recipient_id', $doctorId)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        $unreadNotificationCount = $db
            ->table('notifications')
            ->where('recipient_role', 'doctor')
            ->where('recipient_id', $doctorId)
            ->where('is_read', 0)
            ->countAllResults();

        if ($doctorId <= 0) {
            return redirect()
                ->to('/')
                ->with(
                    'identification_error',
                    'Sesi dokter tidak valid.'
                )
                ->send();
        }

        $db = db_connect();

        /*
         * Mengambil seluruh pasien yang sedang ditangani
         * oleh dokter beserta perangkat dan hasil pengukuran
         * terbaru masing-masing pasien.
         */
        $patients = $db
            ->table('doctor_assignments da')
            ->select([
                'p.id AS patient_id',
                'p.patient_code',
                'p.name AS patient_name',
                'p.gender',
                'p.birth_date',
                'p.phone',
                'p.status AS patient_status',

                'd.id AS device_record_id',
                'd.device_id',
                'd.device_name',
                'd.firmware_version',
                'd.status AS device_status',
                'd.last_ip_address',
                'd.last_seen_at',

                'm.id AS measurement_id',
                'm.systolic',
                'm.diastolic',
                'm.map',
                'm.bpm',
                'm.beat_count',
                'm.quality_status',
                'm.is_valid',
                'm.measured_at',
            ])
            ->join(
                'patients p',
                'p.id = da.patient_id',
                'inner'
            )
            ->join(
                'devices d',
                'd.patient_id = p.id '
                    . 'AND d.status = "active"',
                'left'
            )
            ->join(
                'measurements m',
                'm.id = ('
                    . 'SELECT m2.id '
                    . 'FROM measurements m2 '
                    . 'WHERE m2.patient_id = p.id '
                    . 'ORDER BY m2.measured_at DESC, m2.id DESC '
                    . 'LIMIT 1'
                    . ')',
                'left',
                false
            )
            ->where('da.doctor_id', $doctorId)
            ->where('da.status', 'active')
            ->orderBy('p.name', 'ASC')
            ->get()
            ->getResultArray();



        $nowTimestamp = time();

        foreach ($patients as &$patient) {
            $patient['connection_status'] =
                'never_connected';

            if (
                ! empty($patient['last_seen_at'])
            ) {
                $lastSeenTimestamp =
                    strtotime(
                        $patient['last_seen_at']
                    );

                if ($lastSeenTimestamp !== false) {
                    $patient['connection_status'] =
                        (
                            $nowTimestamp
                            - $lastSeenTimestamp
                        ) <= 120
                        ? 'online'
                        : 'offline';
                }
            }
        }

        unset($patient);
        /*
         * Ringkasan dashboard.
         */
        $totalPatients = count($patients);

        $warningPatients = 0;
        $activeDevices = 0;

        foreach ($patients as $patient) {
            if (
                ($patient['quality_status'] ?? null)
                === 'warning'
            ) {
                $warningPatients++;
            }

            if (
                (
                    $patient['connection_status'] ?? null
                ) === 'online'
            ) {
                $activeDevices++;
            }
        }

        $todayMeasurements = $db
            ->table('measurements m')
            ->join(
                'doctor_assignments da',
                'da.patient_id = m.patient_id '
                    . 'AND da.doctor_id = '
                    . $db->escape($doctorId)
                    . ' AND da.status = "active"',
                'inner'
            )
            ->where(
                'DATE(m.measured_at)',
                date('Y-m-d')
            )
            ->countAllResults();

        return view(
            'doctor/dashboard',
            [
                'title' => 'Dashboard Dokter',

                'doctorName' =>
                session()->get('user_name'),

                'doctorCode' =>
                session()->get('doctor_code'),

                'specialization' =>
                session()->get('specialization'),

                'patients' => $patients,

                'summary' => [
                    'total_patients' =>
                    $totalPatients,

                    'today_measurements' =>
                    $todayMeasurements,

                    'warning_patients' =>
                    $warningPatients,

                    'active_devices' =>
                    $activeDevices,
                ],

                'notifications' =>
                $notifications,

                'unreadNotificationCount' =>
                $unreadNotificationCount,
            ]
        );
    }

    /**
     * Menampilkan seluruh permintaan konsultasi
     * yang ditujukan kepada dokter login.
     */
    public function consultations(): string
    {
        $doctorId =
            (int) session()->get('doctor_id');

        if ($doctorId <= 0) {
            return redirect()
                ->to('/')
                ->with(
                    'identification_error',
                    'Sesi dokter tidak valid.'
                )
                ->send();
        }

        $consultations =
            $this->consultationRequestModel
            ->getByDoctorId(
                $doctorId,
                100
            );

        $summary = [
            'pending' => 0,
            'accepted' => 0,
            'rescheduled' => 0,
            'completed' => 0,
        ];

        foreach ($consultations as $consultation) {
            $status =
                $consultation['status']
                ?? '';

            if (array_key_exists($status, $summary)) {
                $summary[$status]++;
            }
        }

        return view(
            'doctor/consultations',
            [
                'title' =>
                'Permintaan Konsultasi',

                'doctorName' =>
                session()->get('user_name'),

                'doctorCode' =>
                session()->get('doctor_code'),

                'consultations' =>
                $consultations,

                'summary' =>
                $summary,
            ]
        );
    }

    /**
     * Memproses keputusan dokter terhadap
     * permintaan konsultasi pasien.
     */
    public function updateConsultation(
        int $consultationId
    ): RedirectResponse {
        $doctorId =
            (int) session()->get('doctor_id');

        if ($doctorId <= 0) {
            return redirect()
                ->to('/')
                ->with(
                    'identification_error',
                    'Sesi dokter tidak valid.'
                );
        }

        $consultation =
            $this->consultationRequestModel
            ->where('id', $consultationId)
            ->where('doctor_id', $doctorId)
            ->first();

        if ($consultation === null) {
            return redirect()
                ->to('/doctor/consultations')
                ->with(
                    'consultation_error',
                    'Permintaan konsultasi tidak ditemukan.'
                );
        }

        $action = trim(
            (string) $this->request
                ->getPost('action')
        );

        $allowedActions = [
            'accepted',
            'rejected',
            'rescheduled',
            'completed',
            'cancelled',
        ];

        if (! in_array(
            $action,
            $allowedActions,
            true
        )) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'consultation_error',
                    'Tindakan konsultasi tidak valid.'
                );
        }

        $scheduledDate = trim(
            (string) $this->request
                ->getPost('scheduled_date')
        );

        $scheduledTime = trim(
            (string) $this->request
                ->getPost('scheduled_time')
        );

        $meetingLink = trim(
            (string) $this->request
                ->getPost('meeting_link')
        );

        $doctorResponse = trim(
            (string) $this->request
                ->getPost('doctor_response')
        );

        $scheduledAt = null;

        if (
            $scheduledDate !== ''
            && $scheduledTime !== ''
        ) {
            $scheduledAt =
                $scheduledDate
                . ' '
                . $scheduledTime
                . ':00';
        }

        /*
     * Accepted dan rescheduled wajib mempunyai
     * jadwal final.
     */
        if (
            in_array(
                $action,
                ['accepted', 'rescheduled'],
                true
            )
            && $scheduledAt === null
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'consultation_error',
                    'Tanggal dan waktu konsultasi wajib diisi.'
                );
        }

        /*
     * Untuk Google Meet dan Zoom, link pertemuan
     * wajib diisi ketika request diterima.
     */
        if (
            in_array(
                $action,
                ['accepted', 'rescheduled'],
                true
            )
            && in_array(
                $consultation['preferred_platform'],
                ['google_meet', 'zoom'],
                true
            )
            && $meetingLink === ''
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'consultation_error',
                    'Link pertemuan wajib diisi untuk Google Meet atau Zoom.'
                );
        }

        if (
            $action === 'rejected'
            && $doctorResponse === ''
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'consultation_error',
                    'Alasan penolakan wajib diisi.'
                );
        }

        $updateData = [
            'status' =>
            $action,

            'doctor_response' =>
            $doctorResponse !== ''
                ? $doctorResponse
                : null,

            'responded_at' =>
            date('Y-m-d H:i:s'),
        ];

        if (
            in_array(
                $action,
                ['accepted', 'rescheduled'],
                true
            )
        ) {
            $updateData['scheduled_at'] =
                $scheduledAt;

            $updateData['meeting_link'] =
                $meetingLink !== ''
                ? $meetingLink
                : null;

            $updateData['completed_at'] =
                null;
        }

        if ($action === 'rejected') {
            $updateData['scheduled_at'] =
                null;

            $updateData['meeting_link'] =
                null;

            $updateData['completed_at'] =
                null;
        }

        if ($action === 'completed') {
            $updateData['completed_at'] =
                date('Y-m-d H:i:s');
        }

        if ($action === 'cancelled') {
            $updateData['completed_at'] =
                null;
        }

        $updated =
            $this->consultationRequestModel
            ->update(
                $consultationId,
                $updateData
            );

        if ($updated === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'consultation_error',
                    implode(
                        ' ',
                        $this->consultationRequestModel
                            ->errors()
                    )
                );
        }

        return redirect()
            ->to('/doctor/consultations')
            ->with(
                'consultation_success',
                'Status konsultasi berhasil diperbarui.'
            );
    }

    /**
     * Detail pasien yang ditangani dokter.
     */
    public function patientDetail(
        int $patientId
    ): string {
        $doctorId =
            (int) session()->get('doctor_id');

        $db = db_connect();

        /*
         * Pastikan pasien memang sedang ditangani
         * oleh dokter yang sedang login.
         */
        $assignment = $db
            ->table('doctor_assignments')
            ->where('doctor_id', $doctorId)
            ->where('patient_id', $patientId)
            ->where('status', 'active')
            ->get()
            ->getRowArray();

        if ($assignment === null) {
            throw PageNotFoundException::forPageNotFound(
                'Pasien tidak ditemukan atau tidak ditangani oleh dokter ini.'
            );
        }

        $patient = $db
            ->table('patients p')
            ->select([
                'p.*',
                'd.id AS device_record_id',
                'd.device_id',
                'd.device_name',
                'd.firmware_version',
                'd.status AS device_status',
                'd.last_ip_address',
                'd.last_seen_at',
            ])
            ->join(
                'devices d',
                'd.patient_id = p.id',
                'left'
            )
            ->where('p.id', $patientId)
            ->get()
            ->getRowArray();

        if ($patient === null) {
            throw PageNotFoundException::forPageNotFound(
                'Data pasien tidak ditemukan.'
            );
        }

        $latestMeasurement = $db
            ->table('measurements')
            ->where('patient_id', $patientId)
            ->orderBy('measured_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->get(1)
            ->getRowArray();

        $history = $db
            ->table('measurements')
            ->where('patient_id', $patientId)
            ->orderBy('measured_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->get(30)
            ->getResultArray();

        $medicalNotes =
            $this->medicalNoteModel
            ->getByPatientId(
                $patientId,
                20
            );

        return view(
            'doctor/patient_detail',
            [
                'title' => 'Detail Pasien',

                'doctorName' =>
                session()->get('user_name'),

                'patient' =>
                $patient,

                'latestMeasurement' =>
                $latestMeasurement,

                'history' =>
                $history,

                'medicalNotes' =>
                $medicalNotes,
            ]
        );
    }

    public function storeMedicalNote(
        int $patientId
    ): RedirectResponse {
        $doctorId =
            (int) session()->get('doctor_id');

        if ($doctorId <= 0) {
            return redirect()
                ->to('/')
                ->with(
                    'identification_error',
                    'Sesi dokter tidak valid.'
                );
        }

        $db = db_connect();

        //--------------------------------------------------
        // Pastikan pasien memang ditangani dokter
        //--------------------------------------------------

        $assignment = $db
            ->table('doctor_assignments')
            ->where('doctor_id', $doctorId)
            ->where('patient_id', $patientId)
            ->where('status', 'active')
            ->get()
            ->getRowArray();

        if ($assignment === null) {
            return redirect()
                ->to('/doctor/dashboard')
                ->with(
                    'note_error',
                    'Pasien tidak ditangani oleh dokter ini.'
                );
        }

        //--------------------------------------------------
        // Ambil input
        //--------------------------------------------------

        $measurementIdRaw =
            trim(
                (string) $this->request
                    ->getPost('measurement_id')
            );

        $measurementId =
            $measurementIdRaw !== ''
            ? (int) $measurementIdRaw
            : null;

        $title =
            trim(
                (string) $this->request
                    ->getPost('title')
            );

        $assessment =
            trim(
                (string) $this->request
                    ->getPost('assessment')
            );

        $recommendation =
            trim(
                (string) $this->request
                    ->getPost('recommendation')
            );

        $priority =
            trim(
                (string) $this->request
                    ->getPost('priority')
            );

        //--------------------------------------------------
        // Validasi measurement jika dipilih
        //--------------------------------------------------

        if ($measurementId !== null) {
            $measurement = $db
                ->table('measurements')
                ->where('id', $measurementId)
                ->where('patient_id', $patientId)
                ->get()
                ->getRowArray();

            if ($measurement === null) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'note_error',
                        'Hasil pengukuran yang dipilih tidak valid.'
                    );
            }
        }

        //--------------------------------------------------
        // Validasi isi catatan
        //--------------------------------------------------

        if ($recommendation === '') {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'note_error',
                    'Rekomendasi atau arahan wajib diisi.'
                );
        }

        if (
            ! in_array(
                $priority,
                [
                    'normal',
                    'attention',
                    'urgent',
                ],
                true
            )
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'note_error',
                    'Prioritas catatan tidak valid.'
                );
        }

        //--------------------------------------------------
        // Simpan catatan
        //--------------------------------------------------

        $saved =
            $this->medicalNoteModel
            ->insert([
                'doctor_id' =>
                $doctorId,

                'patient_id' =>
                $patientId,

                'measurement_id' =>
                $measurementId,

                'title' =>
                $title !== ''
                    ? $title
                    : null,

                'assessment' =>
                $assessment !== ''
                    ? $assessment
                    : null,

                'recommendation' =>
                $recommendation,

                'priority' =>
                $priority,

                'is_read_by_patient' =>
                0,

                'read_at' =>
                null,
            ]);

        if ($saved === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'note_error',
                    implode(
                        ' ',
                        $this->medicalNoteModel
                            ->errors()
                    )
                );
        }

        return redirect()
            ->to(
                '/doctor/patient/'
                    . $patientId
            )
            ->with(
                'note_success',
                'Catatan dan arahan berhasil disimpan.'
            );
    }

    public function openNotification(
        int $notificationId
    ): RedirectResponse {
        $doctorId =
            (int) session()->get('doctor_id');

        if ($doctorId <= 0) {
            return redirect()
                ->to('/')
                ->with(
                    'identification_error',
                    'Sesi dokter tidak valid.'
                );
        }

        $db = db_connect();

        $notification = $db
            ->table('notifications')
            ->where('id', $notificationId)
            ->where('recipient_role', 'doctor')
            ->where('recipient_id', $doctorId)
            ->get()
            ->getRowArray();

        if ($notification === null) {
            return redirect()
                ->to('/doctor/dashboard')
                ->with(
                    'access_error',
                    'Notifikasi tidak ditemukan.'
                );
        }

        if ((int) $notification['is_read'] === 0) {
            $db
                ->table('notifications')
                ->where('id', $notificationId)
                ->update([
                    'is_read' => 1,
                    'read_at' => date('Y-m-d H:i:s'),
                ]);
        }

        $actionUrl =
            trim(
                (string) (
                    $notification['action_url']
                    ?? ''
                )
            );

        if ($actionUrl === '') {
            return redirect()
                ->to('/doctor/dashboard');
        }

        return redirect()
            ->to($actionUrl);
    }

    public function exportPatientPdf(
        int $patientId
    ): ResponseInterface {
        $doctorId =
            (int) session()->get('doctor_id');

        if ($doctorId <= 0) {
            return redirect()
                ->to('/')
                ->with(
                    'identification_error',
                    'Sesi dokter tidak valid.'
                );
        }

        $db = db_connect();

        //--------------------------------------------------
        // Pastikan pasien ditangani dokter ini
        //--------------------------------------------------

        $assignment = $db
            ->table('doctor_assignments da')
            ->select([
                'da.id AS assignment_id',
                'da.patient_id',
                'da.doctor_id',
                'da.assigned_at',

                'd.doctor_code',
                'd.name AS doctor_name',
                'd.specialization',

                'p.patient_code',
                'p.name AS patient_name',
                'p.birth_date',
                'p.gender',
                'p.phone',
                'p.address',
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
            ->where('da.doctor_id', $doctorId)
            ->where('da.patient_id', $patientId)
            ->where('da.status', 'active')
            ->where('p.status', 'active')
            ->get()
            ->getRowArray();

        if ($assignment === null) {
            return redirect()
                ->to('/doctor/dashboard')
                ->with(
                    'access_error',
                    'Pasien tidak sedang ditangani oleh dokter ini.'
                );
        }

        //--------------------------------------------------
        // Hasil pengukuran terbaru
        //--------------------------------------------------

        $latestMeasurement = $db
            ->table('measurements')
            ->where('patient_id', $patientId)
            ->orderBy('measured_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->get(1)
            ->getRowArray();

        //--------------------------------------------------
        // Riwayat pengukuran, maksimal 30 data
        //--------------------------------------------------

        $history = $db
            ->table('measurements')
            ->where('patient_id', $patientId)
            ->orderBy('measured_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->get(30)
            ->getResultArray();

        //--------------------------------------------------
        // Catatan dokter terbaru
        //--------------------------------------------------

        $latestMedicalNote = $db
            ->table('medical_notes')
            ->where('doctor_id', $doctorId)
            ->where('patient_id', $patientId)
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->get(1)
            ->getRowArray();

        //--------------------------------------------------
        // Buat HTML PDF dari view
        //--------------------------------------------------

        $html = view(
            'doctor/patient_report_pdf',
            [
                'patient' =>
                $assignment,

                'doctor' => [
                    'doctor_code' =>
                    $assignment['doctor_code'],

                    'name' =>
                    $assignment['doctor_name'],

                    'specialization' =>
                    $assignment['specialization'],
                ],

                'latestMeasurement' =>
                $latestMeasurement,

                'history' =>
                $history,

                'latestMedicalNote' =>
                $latestMedicalNote,

                'printedAt' =>
                date('Y-m-d H:i:s'),
            ]
        );

        //--------------------------------------------------
        // Konfigurasi Dompdf
        //--------------------------------------------------

        $options = new Options();

        $options->set(
            'defaultFont',
            'DejaVu Sans'
        );

        $options->set(
            'isRemoteEnabled',
            false
        );

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml(
            $html,
            'UTF-8'
        );

        $dompdf->setPaper(
            'A4',
            'portrait'
        );

        $dompdf->render();

        //--------------------------------------------------
        // Nama file
        //--------------------------------------------------

        $patientCode =
            preg_replace(
                '/[^A-Za-z0-9_-]/',
                '',
                (string) $assignment['patient_code']
            );

        $filename =
            'hasil-pemeriksaan-'
            . $patientCode
            . '-'
            . date('Ymd-His')
            . '.pdf';

        //--------------------------------------------------
        // Tampilkan PDF di browser
        //--------------------------------------------------

        return $this->response
            ->setHeader(
                'Content-Type',
                'application/pdf'
            )
            ->setHeader(
                'Content-Disposition',
                'inline; filename="'
                    . $filename
                    . '"'
            )
            ->setBody(
                $dompdf->output()
            );
    }
}
