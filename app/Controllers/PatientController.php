<?php

namespace App\Controllers;

use App\Models\ConsultationRequestModel;
use App\Models\DeviceModel;
use App\Models\MeasurementModel;
use App\Models\MeasurementRequestModel;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;


class PatientController extends BaseController
{

    protected ConsultationRequestModel $consultationRequestModel;

    protected DeviceModel $deviceModel;

    protected MeasurementModel $measurementModel;

    protected MeasurementRequestModel $requestModel;

    public function __construct()
    {
        $this->deviceModel =
            new DeviceModel();

        $this->measurementModel =
            new MeasurementModel();

        $this->requestModel =
            new MeasurementRequestModel();

        $this->consultationRequestModel =
            new ConsultationRequestModel();
    }

    /**
     * Dashboard utama pasien.
     */
    public function dashboard(): string
    {
        $patientId =
            (int) session()->get('patient_id');

        $deviceRecordId =
            (int) session()->get(
                'device_record_id'
            );

        $latestMeasurement =
            $this->measurementModel
            ->getLatestByPatientId(
                $patientId
            );

        $history =
            $this->measurementModel
            ->getHistoryByPatientId(
                $patientId,
                10
            );

        $medicalNoteModel =
            new \App\Models\MedicalNoteModel();

        $latestNote = $medicalNoteModel
            ->select([
                'medical_notes.*',
                'doctors.name AS doctor_name',
                'doctors.doctor_code',
            ])
            ->join(
                'doctors',
                'doctors.id = medical_notes.doctor_id',
                'inner'
            )
            ->where(
                'medical_notes.patient_id',
                $patientId
            )
            ->orderBy(
                'medical_notes.created_at',
                'DESC'
            )
            ->first();

        /*
 * Gunakan instance model baru agar query sebelumnya
 * tidak berpotensi terbawa ke perhitungan jumlah.
 */
        $unreadNoteModel =
            new \App\Models\MedicalNoteModel();

        $unreadCount = $unreadNoteModel
            ->where(
                'patient_id',
                $patientId
            )
            ->where(
                'is_read_by_patient',
                0
            )
            ->countAllResults();

        $doctorAssignment = db_connect()
            ->table('doctor_assignments da')
            ->select([
                'da.doctor_id',
                'doctors.name AS doctor_name',
                'doctors.doctor_code',
                'doctors.specialization',
            ])
            ->join(
                'doctors',
                'doctors.id = da.doctor_id',
                'inner'
            )
            ->where('da.patient_id', $patientId)
            ->where('da.status', 'active')
            ->orderBy('da.assigned_at', 'DESC')
            ->get()
            ->getRowArray();

        $consultationRequests =
            $this->consultationRequestModel
            ->getByPatientId(
                $patientId,
                10
            );

        /*
         * Bersihkan request lama yang sudah kedaluwarsa.
         */
        $this->requestModel
            ->expireOldRequests();

        $activeRequest =
            $this->requestModel
            ->getActiveRequestByDeviceRecordId(
                $deviceRecordId
            );

        $device =
            $this->deviceModel
            ->find($deviceRecordId);

        return view(
            'patient/dashboard',
            [
                'title' =>
                'Dashboard Pasien',

                'patientName' =>
                session()->get(
                    'user_name'
                ),

                'patientCode' =>
                session()->get(
                    'patient_code'
                ),

                'deviceId' =>
                session()->get(
                    'device_id'
                ),

                'device' =>
                $device,

                'latestMeasurement' =>
                $latestMeasurement,

                'history' =>
                $history,

                'activeRequest' =>
                $activeRequest,

                'latestNote' => $latestNote,

                'unreadCount' => $unreadCount,

                'doctorAssignment' =>
                $doctorAssignment,

                'consultationRequests' =>
                $consultationRequests,
            ]
        );
    }

    public function storeConsultationRequest()
    {
        $patientId =
            (int) session()->get('patient_id');

        if ($patientId <= 0) {
            return redirect()
                ->to('/')
                ->with(
                    'identification_error',
                    'Sesi pasien tidak valid.'
                );
        }

        $db = db_connect();

        $assignment = $db
            ->table('doctor_assignments')
            ->where('patient_id', $patientId)
            ->where('status', 'active')
            ->orderBy('assigned_at', 'DESC')
            ->get()
            ->getRowArray();

        if ($assignment === null) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'consultation_error',
                    'Belum ada dokter aktif yang menangani pasien ini.'
                );
        }

        $message = trim(
            (string) $this->request->getPost('message')
        );

        $platform = trim(
            (string) $this->request->getPost(
                'preferred_platform'
            )
        );

        $preferredDate = trim(
            (string) $this->request->getPost(
                'preferred_date'
            )
        );

        $preferredTime = trim(
            (string) $this->request->getPost(
                'preferred_time'
            )
        );

        if ($message === '') {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'consultation_error',
                    'Pesan atau alasan konsultasi wajib diisi.'
                );
        }

        if (
            ! in_array(
                $platform,
                [
                    'google_meet',
                    'zoom',
                    'whatsapp',
                    'other',
                ],
                true
            )
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'consultation_error',
                    'Platform konsultasi tidak valid.'
                );
        }

        /*
     * Cegah terlalu banyak request pending.
     */
        $activeRequest = $this->consultationRequestModel
            ->where('patient_id', $patientId)
            ->where('doctor_id', (int) $assignment['doctor_id'])
            ->whereIn(
                'status',
                [
                    'pending',
                    'accepted',
                    'rescheduled',
                ]
            )
            ->first();

        if ($activeRequest !== null) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'consultation_error',
                    'Masih ada permintaan konsultasi yang aktif.'
                );
        }

        $saved =
            $this->consultationRequestModel
            ->insert([
                'patient_id' =>
                $patientId,

                'doctor_id' =>
                (int) $assignment['doctor_id'],

                'message' =>
                $message,

                'preferred_platform' =>
                $platform,

                'preferred_date' =>
                $preferredDate !== ''
                    ? $preferredDate
                    : null,

                'preferred_time' =>
                $preferredTime !== ''
                    ? $preferredTime
                    : null,

                'scheduled_at' =>
                null,

                'status' =>
                'pending',

                'meeting_link' =>
                null,

                'doctor_response' =>
                null,

                'responded_at' =>
                null,

                'completed_at' =>
                null,
            ]);

        if ($saved === false) {
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
            ->to('/patient/dashboard')
            ->with(
                'consultation_success',
                'Permintaan konsultasi berhasil dikirim kepada dokter.'
            );
    }

    /**
     * POST /patient/measurement/start
     *
     * Membuat request baru untuk ESP32.
     */
    public function startMeasurement(): ResponseInterface
    {
        try {
            $patientId =
                (int) session()->get(
                    'patient_id'
                );

            $deviceRecordId =
                (int) session()->get(
                    'device_record_id'
                );

            if (
                $patientId <= 0
                || $deviceRecordId <= 0
            ) {
                return $this->jsonResponse(
                    false,
                    'Sesi pasien tidak valid.',
                    null,
                    null,
                    401
                );
            }

            $this->requestModel
                ->expireOldRequests();

            $device =
                $this->deviceModel
                ->find($deviceRecordId);

            if (
                $device === null
                || $device['status'] !== 'active'
            ) {
                return $this->jsonResponse(
                    false,
                    'Perangkat tidak ditemukan atau tidak aktif.',
                    null,
                    null,
                    404
                );
            }

            /*
             * Pastikan perangkat pada session masih
             * benar-benar milik pasien tersebut.
             */
            if (
                (int) $device['patient_id']
                !== $patientId
            ) {
                return $this->jsonResponse(
                    false,
                    'Perangkat tidak sesuai dengan pasien.',
                    null,
                    null,
                    403
                );
            }

            $result =
                $this->requestModel
                ->createRequest(
                    $patientId,
                    $deviceRecordId,
                    'patient',
                    $patientId,
                    5
                );

            if (! $result['success']) {
                return $this->jsonResponse(
                    false,
                    $result['message'],
                    [
                        'active_request' =>
                        $this->requestModel
                            ->getActiveRequestByDeviceRecordId(
                                $deviceRecordId
                            ),
                    ],
                    $result['errors'] ?? null,
                    409
                );
            }

            $request =
                $this->requestModel
                ->getRequestDetail(
                    (int) $result['request_id']
                );

            return $this->jsonResponse(
                true,
                'Permintaan pengukuran berhasil dibuat.',
                [
                    'request' => [
                        'request_id' =>
                        (int) $request['id'],

                        'request_code' =>
                        $request['request_code'],

                        'status' =>
                        $request['status'],

                        'requested_at' =>
                        $request['requested_at'],

                        'expired_at' =>
                        $request['expired_at'],
                    ],
                ],
                null,
                201
            );
        } catch (Throwable $exception) {
            return $this->jsonResponse(
                false,
                'Terjadi kesalahan saat membuat permintaan pengukuran.',
                null,
                ENVIRONMENT === 'development'
                    ? [
                        'exception' =>
                        $exception->getMessage(),
                    ]
                    : null,
                500
            );
        }
    }

    /**
     * GET /patient/measurement/status/{requestId}
     *
     * Dipanggil browser setiap beberapa detik.
     */
    public function measurementStatus(
        int $requestId
    ): ResponseInterface {
        $patientId =
            (int) session()->get(
                'patient_id'
            );

        $request =
            $this->requestModel
            ->getRequestDetail(
                $requestId
            );

        if ($request === null) {
            return $this->jsonResponse(
                false,
                'Request pengukuran tidak ditemukan.',
                null,
                null,
                404
            );
        }

        /*
         * Pasien hanya boleh melihat request miliknya.
         */
        if (
            (int) $request['patient_id']
            !== $patientId
        ) {
            return $this->jsonResponse(
                false,
                'Anda tidak memiliki akses ke request ini.',
                null,
                null,
                403
            );
        }

        $measurement = null;

        if ($request['status'] === 'completed') {
            $measurement =
                $this->measurementModel
                ->findByRequestId(
                    $requestId
                );
        }

        return $this->jsonResponse(
            true,
            'Status pengukuran berhasil diperoleh.',
            [
                'request' => [
                    'request_id' =>
                    (int) $request['id'],

                    'request_code' =>
                    $request['request_code'],

                    'status' =>
                    $request['status'],

                    'requested_at' =>
                    $request['requested_at'],

                    'acknowledged_at' =>
                    $request['acknowledged_at'],

                    'started_at' =>
                    $request['started_at'],

                    'completed_at' =>
                    $request['completed_at'],

                    'error_code' =>
                    $request['error_code'],

                    'error_message' =>
                    $request['error_message'],
                ],

                'measurement' =>
                $measurement,
            ]
        );
    }

    /**
     * GET /patient/measurement/latest
     */
    public function latestMeasurement(): ResponseInterface
    {
        $patientId =
            (int) session()->get(
                'patient_id'
            );

        $measurement =
            $this->measurementModel
            ->getLatestByPatientId(
                $patientId
            );

        return $this->jsonResponse(
            true,
            $measurement === null
                ? 'Belum ada data pengukuran.'
                : 'Hasil terbaru berhasil diperoleh.',
            [
                'measurement' =>
                $measurement,
            ]
        );
    }

    /**
     * Menampilkan seluruh catatan dokter milik pasien.
     *
     * Ketika halaman dibuka, seluruh catatan yang belum
     * dibaca akan ditandai sebagai sudah dibaca.
     */
    public function medicalNotes(): string
    {
        $patientId =
            (int) session()->get('patient_id');

        if ($patientId <= 0) {
            return redirect()
                ->to('/')
                ->with(
                    'identification_error',
                    'Sesi pasien tidak valid.'
                )
                ->send();
        }

        $medicalNoteModel =
            new \App\Models\MedicalNoteModel();

        /*
     * Ambil seluruh catatan sebelum diperbarui agar
     * informasi status awal masih dapat diketahui.
     */
        $medicalNotes =
            $medicalNoteModel
            ->getByPatientId(
                $patientId,
                100
            );

        /*
     * Tandai seluruh catatan yang belum dibaca.
     */
        $medicalNoteModel
            ->where(
                'patient_id',
                $patientId
            )
            ->where(
                'is_read_by_patient',
                0
            )
            ->set([
                'is_read_by_patient' => 1,
                'read_at' => date('Y-m-d H:i:s'),
            ])
            ->update();

        return view(
            'patient/notes',
            [
                'title' =>
                'Catatan Dokter',

                'patientName' =>
                session()->get('user_name'),

                'patientCode' =>
                session()->get('patient_code'),

                'medicalNotes' =>
                $medicalNotes,
            ]
        );
    }

    /**
     * Format JSON dashboard pasien.
     */
    private function jsonResponse(
        bool $success,
        string $message,
        mixed $data = null,
        mixed $errors = null,
        int $statusCode = 200
    ): ResponseInterface {
        return $this->response
            ->setStatusCode($statusCode)
            ->setJSON([
                'success' => $success,
                'message' => $message,
                'data' => $data,
                'errors' => $errors,

                /*
                 * Hash CSRF baru dikirim kembali agar
                 * tombol dapat digunakan lagi tanpa reload.
                 */
                'csrf' => [
                    'token' => csrf_token(),
                    'hash' => csrf_hash(),
                ],
            ]);
    }
}
