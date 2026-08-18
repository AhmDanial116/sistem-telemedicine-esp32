<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>
        Hasil Pemeriksaan
    </title>

    <style>
        @page {
            margin: 28px 32px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #1f2937;
            font-family: "DejaVu Sans", sans-serif;
            font-size: 10px;
            line-height: 1.45;
        }

        .header-table {
            width: 100%;
            margin-bottom: 18px;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
        }

        .brand {
            color: #0f766e;
            font-size: 19px;
            font-weight: bold;
        }

        .document-title {
            margin-top: 3px;
            color: #111827;
            font-size: 14px;
            font-weight: bold;
        }

        .document-code {
            text-align: right;
            color: #6b7280;
            font-size: 9px;
        }

        .divider {
            height: 3px;
            margin-bottom: 17px;
            background: #0f766e;
        }

        .section {
            margin-bottom: 16px;
        }

        .section-title {
            margin-bottom: 8px;
            padding: 7px 9px;
            color: #ffffff;
            background: #0f766e;
            font-size: 11px;
            font-weight: bold;
        }

        .information-table {
            width: 100%;
            border-collapse: collapse;
        }

        .information-table td {
            padding: 4px 5px;
            vertical-align: top;
        }

        .information-label {
            width: 115px;
            color: #6b7280;
        }

        .result-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 7px 0;
        }

        .result-card {
            width: 25%;
            padding: 13px 7px;
            text-align: center;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
        }

        .result-value {
            color: #111827;
            font-size: 18px;
            font-weight: bold;
        }

        .result-label {
            margin-top: 2px;
            font-size: 9px;
            font-weight: bold;
        }

        .result-unit {
            color: #6b7280;
            font-size: 8px;
        }

        .status-valid {
            color: #166534;
            font-weight: bold;
        }

        .status-warning {
            color: #92400e;
            font-weight: bold;
        }

        .status-invalid {
            color: #991b1b;
            font-weight: bold;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
        }

        .history-table th {
            padding: 6px 5px;
            color: #ffffff;
            background: #374151;
            text-align: left;
            font-size: 8px;
        }

        .history-table td {
            padding: 6px 5px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 8px;
        }

        .history-table tr:nth-child(even) {
            background: #f9fafb;
        }

        .medical-note {
            padding: 11px;
            background: #f8fafc;
            border-left: 4px solid #2563eb;
        }

        .note-label {
            margin-bottom: 2px;
            font-weight: bold;
        }

        .note-text {
            margin-bottom: 8px;
        }

        .footer {
            margin-top: 22px;
            padding-top: 10px;
            border-top: 1px solid #d1d5db;
            color: #6b7280;
            font-size: 8px;
        }

        .signature-table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 50%;
            vertical-align: top;
        }

        .signature {
            text-align: center;
        }

        .signature-space {
            height: 48px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    <?php
    $genderLabel = match ($patient['gender'] ?? null) {
        'male' => 'Laki-laki',
        'female' => 'Perempuan',
        default => '-',
    };

    $age = '-';

    if (! empty($patient['birth_date'])) {
        try {
            $birthDate =
                new DateTime(
                    $patient['birth_date']
                );

            $today =
                new DateTime();

            $age =
                $birthDate
                ->diff($today)
                ->y
                . ' tahun';
        } catch (Throwable) {
            $age = '-';
        }
    }

    $qualityStatus =
        $latestMeasurement['quality_status']
        ?? '-';

    $statusClass = match ($qualityStatus) {
        'valid' => 'status-valid',
        'warning' => 'status-warning',
        'invalid' => 'status-invalid',
        default => '',
    };

    $statusLabel = match ($qualityStatus) {
        'valid' => 'Valid',
        'warning' => 'Perlu Perhatian',
        'invalid' => 'Tidak Valid',
        default => '-',
    };
    ?>

    <table class="header-table">
        <tr>
            <td>
                <div class="brand">
                    TELEMEDICINE
                </div>

                <div class="document-title">
                    HASIL PEMERIKSAAN TEKANAN DARAH
                    DAN DENYUT NADI
                </div>
            </td>

            <td class="document-code">
                Kode Pasien:
                <?= esc($patient['patient_code']) ?>
                <br>

                Dicetak:
                <?= esc($printedAt) ?>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="section">
        <div class="section-title">
            A. IDENTITAS PASIEN
        </div>

        <table class="information-table">
            <tr>
                <td class="information-label">
                    Nama pasien
                </td>

                <td>
                    :
                    <strong>
                        <?= esc($patient['patient_name']) ?>
                    </strong>
                </td>

                <td class="information-label">
                    Kode pasien
                </td>

                <td>
                    :
                    <?= esc($patient['patient_code']) ?>
                </td>
            </tr>

            <tr>
                <td class="information-label">
                    Tanggal lahir
                </td>

                <td>
                    :
                    <?= esc($patient['birth_date'] ?? '-') ?>
                </td>

                <td class="information-label">
                    Usia
                </td>

                <td>
                    :
                    <?= esc($age) ?>
                </td>
            </tr>

            <tr>
                <td class="information-label">
                    Jenis kelamin
                </td>

                <td>
                    :
                    <?= esc($genderLabel) ?>
                </td>

                <td class="information-label">
                    Nomor telepon
                </td>

                <td>
                    :
                    <?= esc($patient['phone'] ?? '-') ?>
                </td>
            </tr>

            <tr>
                <td class="information-label">
                    Alamat
                </td>

                <td colspan="3">
                    :
                    <?= esc($patient['address'] ?? '-') ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">
            B. TENAGA MEDIS PENANGGUNG JAWAB
        </div>

        <table class="information-table">
            <tr>
                <td class="information-label">
                    Nama dokter
                </td>

                <td>
                    :
                    <strong>
                        <?= esc($doctor['name']) ?>
                    </strong>
                </td>

                <td class="information-label">
                    Kode dokter
                </td>

                <td>
                    :
                    <?= esc($doctor['doctor_code']) ?>
                </td>
            </tr>

            <tr>
                <td class="information-label">
                    Spesialisasi
                </td>

                <td colspan="3">
                    :
                    <?= esc(
                        $doctor['specialization']
                            ?? '-'
                    ) ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">
            C. HASIL PEMERIKSAAN TERBARU
        </div>

        <?php if ($latestMeasurement === null): ?>
            <div class="medical-note">
                Belum tersedia data pengukuran pasien.
            </div>
        <?php else: ?>
            <table class="result-table">
                <tr>
                    <td class="result-card">
                        <div class="result-value">
                            <?= esc(
                                number_format(
                                    (float) $latestMeasurement['systolic'],
                                    2
                                )
                            ) ?>
                        </div>

                        <div class="result-label">
                            SISTOLIK
                        </div>

                        <div class="result-unit">
                            mmHg
                        </div>
                    </td>

                    <td class="result-card">
                        <div class="result-value">
                            <?= esc(
                                number_format(
                                    (float) $latestMeasurement['diastolic'],
                                    2
                                )
                            ) ?>
                        </div>

                        <div class="result-label">
                            DIASTOLIK
                        </div>

                        <div class="result-unit">
                            mmHg
                        </div>
                    </td>

                    <td class="result-card">
                        <div class="result-value">
                            <?= esc(
                                number_format(
                                    (float) $latestMeasurement['map'],
                                    2
                                )
                            ) ?>
                        </div>

                        <div class="result-label">
                            MAP
                        </div>

                        <div class="result-unit">
                            mmHg
                        </div>
                    </td>

                    <td class="result-card">
                        <div class="result-value">
                            <?= esc(
                                (int) $latestMeasurement['bpm']
                            ) ?>
                        </div>

                        <div class="result-label">
                            DENYUT NADI
                        </div>

                        <div class="result-unit">
                            BPM
                        </div>
                    </td>
                </tr>
            </table>

            <table class="information-table" style="margin-top: 10px;">
                <tr>
                    <td class="information-label">
                        Waktu pemeriksaan
                    </td>

                    <td>
                        :
                        <?= esc(
                            $latestMeasurement['measured_at']
                        ) ?>
                    </td>

                    <td class="information-label">
                        Kualitas hasil
                    </td>

                    <td>
                        :
                        <span class="<?= esc($statusClass) ?>">
                            <?= esc($statusLabel) ?>
                        </span>
                    </td>
                </tr>

                <tr>
                    <td class="information-label">
                        Jumlah denyut
                    </td>

                    <td>
                        :
                        <?= esc(
                            $latestMeasurement['beat_count']
                                ?? '-'
                        ) ?>
                    </td>

                    <td class="information-label">
                        Validitas sistem
                    </td>

                    <td>
                        :
                        <?= (int) (
                            $latestMeasurement['is_valid']
                            ?? 0
                        ) === 1
                            ? 'Valid'
                            : 'Tidak valid'
                        ?>
                    </td>
                </tr>
            </table>
        <?php endif; ?>
    </div>

    <div class="section">
        <div class="section-title">
            D. PENILAIAN DAN REKOMENDASI DOKTER
        </div>

        <?php if ($latestMedicalNote === null): ?>
            <div class="medical-note">
                Belum ada penilaian atau rekomendasi dokter
                untuk pasien ini.
            </div>
        <?php else: ?>
            <div class="medical-note">
                <div class="note-label">
                    <?= esc(
                        $latestMedicalNote['title']
                            ?? 'Catatan Dokter'
                    ) ?>
                </div>

                <div class="note-label">
                    Penilaian:
                </div>

                <div class="note-text">
                    <?= nl2br(
                        esc(
                            $latestMedicalNote['assessment']
                                ?? '-'
                        )
                    ) ?>
                </div>

                <div class="note-label">
                    Rekomendasi:
                </div>

                <div class="note-text">
                    <?= nl2br(
                        esc(
                            $latestMedicalNote['recommendation']
                                ?? '-'
                        )
                    ) ?>
                </div>

                <div>
                    Prioritas:
                    <strong>
                        <?= esc(
                            ucfirst(
                                $latestMedicalNote['priority']
                                    ?? 'normal'
                            )
                        ) ?>
                    </strong>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="section">
        <div class="section-title">
            E. RIWAYAT PENGUKURAN
        </div>

        <?php if ($history === []): ?>
            <div class="medical-note">
                Belum tersedia riwayat pengukuran.
            </div>
        <?php else: ?>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Waktu</th>
                        <th>SYS</th>
                        <th>DIA</th>
                        <th>MAP</th>
                        <th>BPM</th>
                        <th>Beat</th>
                        <th>Kualitas</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach (
                        $history as $index => $measurement
                    ): ?>
                        <?php
                        $rowStatus = match ($measurement['quality_status']
                            ?? null) {
                            'valid' => 'Valid',
                            'warning' => 'Perlu Perhatian',
                            'invalid' => 'Tidak Valid',
                            default => '-',
                        };
                        ?>

                        <tr>
                            <td>
                                <?= $index + 1 ?>
                            </td>

                            <td>
                                <?= esc(
                                    $measurement['measured_at']
                                ) ?>
                            </td>

                            <td>
                                <?= esc(
                                    number_format(
                                        (float) $measurement['systolic'],
                                        2
                                    )
                                ) ?>
                            </td>

                            <td>
                                <?= esc(
                                    number_format(
                                        (float) $measurement['diastolic'],
                                        2
                                    )
                                ) ?>
                            </td>

                            <td>
                                <?= esc(
                                    number_format(
                                        (float) $measurement['map'],
                                        2
                                    )
                                ) ?>
                            </td>

                            <td>
                                <?= esc(
                                    (int) $measurement['bpm']
                                ) ?>
                            </td>

                            <td>
                                <?= esc(
                                    $measurement['beat_count']
                                        ?? '-'
                                ) ?>
                            </td>

                            <td>
                                <?= esc($rowStatus) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <table class="signature-table">
        <tr>
            <td>
                <div class="footer">
                    Dokumen ini dihasilkan secara otomatis oleh
                    Sistem Telemedicine Pemantauan Tekanan Darah
                    dan Denyut Nadi.
                    <br><br>

                    Hasil pengukuran merupakan data pemantauan
                    dan tidak menggantikan pemeriksaan, diagnosis,
                    atau penanganan langsung oleh tenaga medis.
                </div>
            </td>

            <td class="signature">
                Dokter Penanggung Jawab

                <div class="signature-space"></div>

                <div class="signature-name">
                    <?= esc($doctor['name']) ?>
                </div>

                <div>
                    <?= esc(
                        $doctor['specialization']
                            ?? 'Dokter'
                    ) ?>
                </div>
            </td>
        </tr>
    </table>

</body>

</html>