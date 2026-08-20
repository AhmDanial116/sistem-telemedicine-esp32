<?php

namespace App\Controllers;

use App\Models\MeasurementModel;
use App\Services\TelegramService;
use CodeIgniter\HTTP\ResponseInterface;

class TelegramTestController extends BaseController
{
    private MeasurementModel $measurementModel;
    private TelegramService $telegramService;

    public function __construct()
    {
        $this->measurementModel =
            new MeasurementModel();

        $this->telegramService =
            new TelegramService();
    }

    /**
     * Halaman test dasar.
     */
    public function index(): ResponseInterface
    {
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Endpoint test Telegram aktif.',
            'tests' => [
                '/telegram-test/heart-rate',
                '/telegram-test/blood-pressure',
                '/telegram-test/both',
            ],
        ]);
    }

    /**
     * Test:
     * Heart Rate = 110 BPM
     *
     * Nilai masih valid secara teknis,
     * tetapi masuk kategori perlu perhatian.
     */
    public function heartRate(): ResponseInterface
    {
        $bpm = 110;

        $quality =
            $this->measurementModel
            ->evaluateQuality(
                null,
                null,
                null,
                $bpm,
                null,
                'heart_rate'
            );

        if (
            $quality['quality_status']
            !== 'warning'
        ) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' =>
                    'Data dummy tidak menghasilkan warning.',
                    'quality' => $quality,
                ]);
        }

        $message =
            "⚠️ HASIL PENGUKURAN PERLU PERHATIAN\n\n"
            . "Pasien: DATA TEST\n"
            . "Kode: TEST001\n"
            . "Jenis pengukuran: Denyut Nadi\n\n"
            . "• Denyut nadi: {$bpm} BPM\n\n"
            . "Silakan tinjau hasil pada sistem Telemedicine.";

        $sent =
            $this->telegramService
            ->sendMessage($message);

        return $this->response->setJSON([
            'success' => $sent,
            'measurement_type' =>
            'heart_rate',
            'dummy_value' => [
                'bpm' => $bpm,
            ],
            'quality' => $quality,
            'telegram_sent' => $sent,
        ]);
    }

    /**
     * Test:
     * SYS 150 / DIA 95 / MAP 110
     *
     * Valid secara teknis,
     * tetapi perlu perhatian.
     */
    public function bloodPressure(): ResponseInterface
    {
        $systolic = 150.0;
        $map = 110.0;
        $diastolic = 95.0;
        $beatCount = 30;

        $quality =
            $this->measurementModel
            ->evaluateQuality(
                $systolic,
                $map,
                $diastolic,
                null,
                $beatCount,
                'blood_pressure'
            );

        if (
            $quality['quality_status']
            !== 'warning'
        ) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' =>
                    'Data dummy tidak menghasilkan warning.',
                    'quality' => $quality,
                ]);
        }

        $message =
            "⚠️ HASIL PENGUKURAN PERLU PERHATIAN\n\n"
            . "Pasien: DATA TEST\n"
            . "Kode: TEST001\n"
            . "Jenis pengukuran: Tekanan Darah\n\n"
            . "• Tekanan darah: "
            . number_format(
                $systolic,
                2,
                '.',
                ''
            )
            . "/"
            . number_format(
                $diastolic,
                2,
                '.',
                ''
            )
            . " mmHg"
            . " (MAP "
            . number_format(
                $map,
                2,
                '.',
                ''
            )
            . " mmHg)\n\n"
            . "Silakan tinjau hasil pada sistem Telemedicine.";

        $sent =
            $this->telegramService
            ->sendMessage($message);

        return $this->response->setJSON([
            'success' => $sent,
            'measurement_type' =>
            'blood_pressure',
            'dummy_value' => [
                'systolic' =>
                $systolic,
                'map' =>
                $map,
                'diastolic' =>
                $diastolic,
            ],
            'quality' => $quality,
            'telegram_sent' => $sent,
        ]);
    }

    /**
     * Test BOTH:
     *
     * BP normal
     * BPM warning
     *
     * Harus hanya mengirim BPM.
     */
    public function both(): ResponseInterface
    {
        $systolic = 120.0;
        $map = 90.0;
        $diastolic = 80.0;
        $bpm = 110;
        $beatCount = 30;

        $quality =
            $this->measurementModel
            ->evaluateQuality(
                $systolic,
                $map,
                $diastolic,
                $bpm,
                $beatCount,
                'both'
            );

        if (
            $quality['quality_status']
            !== 'warning'
        ) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' =>
                    'Data dummy tidak menghasilkan warning.',
                    'quality' => $quality,
                ]);
        }

        $parts = [];

        if (
            $quality['blood_pressure_warning'] ?? false
        ) {
            $parts[] =
                "• Tekanan darah: "
                . $systolic
                . "/"
                . $diastolic
                . " mmHg"
                . " (MAP "
                . $map
                . " mmHg)";
        }

        if (
            $quality['heart_rate_warning'] ?? false
        ) {
            $parts[] =
                "• Denyut nadi: "
                . $bpm
                . " BPM";
        }

        $message =
            "⚠️ HASIL PENGUKURAN PERLU PERHATIAN\n\n"
            . "Pasien: DATA TEST\n"
            . "Kode: TEST001\n"
            . "Jenis pengukuran: Keduanya\n\n"
            . implode(
                "\n",
                $parts
            )
            . "\n\n"
            . "Silakan tinjau hasil pada sistem Telemedicine.";

        $sent =
            $this->telegramService
            ->sendMessage($message);

        return $this->response->setJSON([
            'success' => $sent,
            'measurement_type' =>
            'both',
            'dummy_value' => [
                'systolic' =>
                $systolic,
                'map' =>
                $map,
                'diastolic' =>
                $diastolic,
                'bpm' =>
                $bpm,
            ],
            'quality' => $quality,
            'telegram_sent' => $sent,
        ]);
    }
}
