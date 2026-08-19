<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        <?= esc($title) ?> | Telemedicine System
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <link
        href="<?= base_url('assets/css/medical-ui.css') ?>"
        rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --background: #f4f7fb;
            --card-radius: 18px;
        }

        body {
            min-height: 100vh;
            background: var(--background);
            color: #1f2937;
        }

        .navbar-system {
            background: #ffffff;
            box-shadow:
                0 2px 12px rgba(15, 23, 42, 0.07);
        }

        .brand-icon {
            color: var(--primary);
        }

        .dashboard-card {
            border: 0;
            border-radius: var(--card-radius);
            box-shadow:
                0 8px 25px rgba(15, 23, 42, 0.07);
        }

        .result-value {
            font-size: 2rem;
            font-weight: 800;
        }

        .result-unit {
            color: #6b7280;
            font-size: 0.85rem;
        }

        .measurement-panel {
            background:
                linear-gradient(135deg,
                    #2563eb,
                    #0f766e);
            color: #ffffff;
        }

        .btn-measurement {
            min-height: 52px;
            border-radius: 13px;
            font-weight: 700;
        }

        .status-box {
            display: none;
            border-radius: 14px;
        }

        .status-step {
            font-weight: 700;
        }

        .device-badge {
            background: #e0e7ff;
            color: #3730a3;
        }

        .table thead th {
            color: #6b7280;
            font-size: 0.85rem;
            font-weight: 700;
            border-bottom-width: 1px;
        }

        .patient-hero {
            position: relative;
            overflow: hidden;
            min-height: 100%;
            color: #ffffff;
            background:
                linear-gradient(145deg,
                    #0f766e 0%,
                    #0d9488 48%,
                    #2563eb 100%);
            border: 0;
            border-radius: 24px;
            box-shadow:
                0 18px 45px rgba(15, 118, 110, 0.20);
        }

        .patient-hero::before {
            position: absolute;
            top: -85px;
            right: -75px;
            width: 240px;
            height: 240px;
            content: '';
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .patient-hero::after {
            position: absolute;
            right: 80px;
            bottom: -130px;
            width: 210px;
            height: 210px;
            content: '';
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
        }

        .patient-hero-content {
            position: relative;
            z-index: 1;
        }

        .patient-device-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 11px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 650;
        }

        .patient-measurement-button {
            min-height: 52px;
            color: #0f766e;
            background: #ffffff;
            border: 0;
            border-radius: 13px;
            font-weight: 750;
            box-shadow:
                0 12px 25px rgba(15, 23, 42, 0.14);
        }

        .patient-measurement-button:hover,
        .patient-measurement-button:focus {
            color: #ffffff;
            background: #14b8a6;
            transform: translateY(-2px);
        }

        .patient-status-box {
            color: #0f172a;
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(255, 255, 255, 0.50);
            border-radius: 14px;
        }

        .patient-result-card {
            position: relative;
            overflow: hidden;
            height: 100%;
            padding: 18px 14px;
            text-align: center;
            background: #f8fafc;
            border: 1px solid #eef2f7;
            border-radius: 17px;
            transition:
                transform 180ms ease,
                box-shadow 180ms ease,
                border-color 180ms ease;
        }

        .patient-result-card:hover {
            transform: translateY(-3px);
            border-color: rgba(20, 184, 166, 0.28);
            box-shadow:
                0 12px 28px rgba(15, 23, 42, 0.08);
        }

        .patient-result-icon {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 9px;
            border-radius: 12px;
            font-size: 1rem;
        }

        .patient-result-icon.sys {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .patient-result-icon.dia {
            color: #7e22ce;
            background: #f3e8ff;
        }

        .patient-result-icon.map {
            color: #0f766e;
            background: #ccfbf1;
        }

        .patient-result-icon.bpm {
            color: #be123c;
            background: #ffe4e6;
        }

        .patient-result-value {
            color: #0f172a;
            font-size: 1.95rem;
            font-weight: 800;
            line-height: 1;
        }

        .patient-result-label {
            margin-top: 8px;
            color: #334155;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .patient-result-unit {
            margin-top: 3px;
            color: #64748b;
            font-size: 0.70rem;
        }

        .patient-section-heading {
            color: #0f172a;
            font-size: 1rem;
            font-weight: 800;
        }

        .patient-section-description {
            color: #64748b;
            font-size: 0.76rem;
        }

        .patient-note-card {
            overflow: hidden;
            margin-top: 24px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 5px solid #0f766e;
            border-radius: 18px;
            box-shadow:
                0 10px 28px rgba(15, 23, 42, 0.06);
        }

        .patient-note-header {
            padding: 18px 20px;
            background:
                linear-gradient(90deg,
                    #f0fdfa,
                    #ffffff);
            border-bottom: 1px solid #f1f5f9;
        }

        .patient-note-body {
            padding: 20px;
        }

        .patient-note-meta {
            color: #64748b;
            font-size: 0.76rem;
        }

        .patient-note-section {
            padding: 14px 16px;
            background: #f8fafc;
            border-radius: 13px;
        }

        .patient-note-section+.patient-note-section {
            margin-top: 12px;
        }

        .patient-note-label {
            margin-bottom: 6px;
            color: #0f766e;
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        @media (max-width: 767.98px) {
            .result-value {
                font-size: 1.6rem;
            }
        }
    </style>
</head>

<body class="medical-app">
    <nav class="navbar medical-navbar">
        <div class="container py-2">

            <a
                href="<?= site_url('patient/dashboard') ?>"
                class="medical-brand">
                <span class="medical-brand-icon">
                    <i class="bi bi-heart-pulse-fill"></i>
                </span>

                <span class="medical-brand-text">
                    Telemedicine

                    <span class="medical-brand-subtitle">
                        IoT Health Monitoring System
                    </span>
                </span>
            </a>

            <div class="ms-auto d-flex align-items-center gap-3">

                <a
                    href="<?= site_url('patient/notes') ?>"
                    class="notification-button text-decoration-none"
                    aria-label="Catatan dokter"
                    title="Catatan Dokter">
                    <i class="bi bi-journal-medical"></i>

                    <?php if (($unreadCount ?? 0) > 0): ?>
                        <span class="notification-count">
                            <?= (int) min($unreadCount, 99) ?>
                        </span>
                    <?php endif; ?>
                </a>

                <div class="medical-user-info text-end">
                    <div class="medical-user-name">
                        <?= esc($patientName ?? 'Pasien') ?>
                    </div>

                    <div class="medical-user-role">
                        <?= esc($patientCode ?? '-') ?>
                        · Pasien
                    </div>
                </div>

                <a
                    href="<?= site_url('logout') ?>"
                    class="btn btn-medical-danger btn-sm px-3">
                    <i class="bi bi-box-arrow-right me-1"></i>
                    Keluar
                </a>

            </div>
        </div>
    </nav>

    <main class="container py-4 py-lg-5">
        <?php if (
            session()->getFlashdata('access_error')
        ): ?>
            <div class="alert alert-warning">
                <?= esc(
                    session()->getFlashdata(
                        'access_error'
                    )
                ) ?>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- PANEL PENGUKURAN -->
            <div class="col-12 col-lg-5">
                <section class="patient-hero h-100">
                    <div class="patient-hero-content p-4 p-lg-5">

                        <div class="d-flex justify-content-between align-items-start gap-3">

                            <div>
                                <div class="medical-hero-eyebrow">
                                    Dashboard Pasien
                                </div>

                                <h1 class="h3 fw-bold mb-2">
                                    Halo,
                                    <?= esc($patientName ?? 'Pasien') ?>
                                </h1>

                                <p class="mb-0 opacity-75">
                                    Lakukan pengukuran tekanan darah dan
                                    denyut nadi menggunakan perangkat yang
                                    telah terhubung dengan akun Anda.
                                </p>
                            </div>

                            <div class="medical-hero-illustration fs-1">
                                <i class="bi bi-heart-pulse-fill"></i>
                            </div>

                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-4">

                            <span class="patient-device-chip">
                                <i class="bi bi-person-vcard"></i>
                                <?= esc($patientCode ?? '-') ?>
                            </span>

                            <span class="patient-device-chip">
                                <i class="bi bi-cpu"></i>
                                <?= esc($deviceId ?? '-') ?>
                            </span>

                            <span class="patient-device-chip">
                                <i class="bi bi-shield-check"></i>
                                Monitoring Aktif
                            </span>

                        </div>

                        <hr class="border-light opacity-25 my-4">

                        <div class="mb-3">
                            <div class="fw-bold fs-5">
                                Mulai Pengukuran
                            </div>

                            <div class="small opacity-75 mt-1">
                                Pilih jenis pengukuran yang ingin dilakukan.
                                Ikuti petunjuk sensor sesuai pilihan pengukuran.
                            </div>
                        </div>

                        <form id="measurementForm">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label
                                    for="measurementType"
                                    class="form-label fw-semibold text-white">
                                    Jenis Pengukuran
                                </label>

                                <select
                                    id="measurementType"
                                    name="measurement_type"
                                    class="form-select"
                                    required>
                                    <option value="both" selected>
                                        Denyut Nadi + Tekanan Darah
                                    </option>
                                    <option value="heart_rate">
                                        Denyut Nadi Saja
                                    </option>
                                    <option value="blood_pressure">
                                        Tekanan Darah Saja
                                    </option>
                                </select>

                                <div class="small opacity-75 mt-2">
                                    Pengukuran dapat dilakukan secara terpisah
                                    atau keduanya dalam satu permintaan.
                                </div>
                            </div>

                            <button
                                type="submit"
                                id="startMeasurementButton"
                                class="btn patient-measurement-button w-100">
                                <i class="bi bi-play-circle-fill me-1"></i>
                                Mulai Pengukuran
                            </button>
                        </form>

                        <div
                            id="measurementStatusBox"
                            class="status-box patient-status-box mt-3 p-3">
                            <div class="d-flex align-items-center">
                                <div
                                    id="statusSpinner"
                                    class="spinner-border spinner-border-sm me-2"
                                    role="status"></div>

                                <div>
                                    <div
                                        id="measurementStatusTitle"
                                        class="status-step">
                                        Menyiapkan pengukuran
                                    </div>

                                    <small
                                        id="measurementStatusDescription"
                                        class="text-muted">
                                        Mohon tunggu.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div
                            id="measurementError"
                            class="alert alert-danger mt-3 mb-0 d-none"></div>

                    </div>
                </section>
            </div>

            <!-- HASIL TERBARU -->
            <div class="col-12 col-lg-7">
                <section class="medical-card h-100">
                    <div class="medical-card-body">

                        <div class="d-flex justify-content-between align-items-start gap-3 mb-4">

                            <div>
                                <div class="patient-section-heading">
                                    Hasil Pengukuran Terbaru
                                </div>

                                <div
                                    id="latestMeasuredAt"
                                    class="patient-section-description mt-1">
                                    <?php if ($latestMeasurement): ?>
                                        <?= esc(
                                            $latestMeasurement['measured_at']
                                        ) ?>
                                    <?php else: ?>
                                        Belum ada pengukuran
                                    <?php endif; ?>
                                </div>
                            </div>

                            <span
                                id="qualityBadge"
                                class="medical-badge
                    <?php if (
                        $latestMeasurement
                        && $latestMeasurement['quality_status'] === 'valid'
                    ): ?>
                        medical-badge-success
                    <?php elseif (
                        $latestMeasurement
                        && $latestMeasurement['quality_status'] === 'warning'
                    ): ?>
                        medical-badge-warning
                    <?php elseif (
                        $latestMeasurement
                        && $latestMeasurement['quality_status'] === 'invalid'
                    ): ?>
                        medical-badge-danger
                    <?php else: ?>
                        medical-badge-neutral
                    <?php endif; ?>">
                                <?php
                                $qualityLabel = match ($latestMeasurement['quality_status']
                                    ?? null) {
                                    'valid' => 'Valid',
                                    'warning' => 'Perlu Perhatian',
                                    'invalid' => 'Tidak Valid',
                                    default => 'Belum Ada',
                                };
                                ?>

                                <?= esc($qualityLabel) ?>
                            </span>

                        </div>

                        <div class="row g-3">

                            <!-- SYS -->
                            <div class="col-6 col-md-3">
                                <div class="patient-result-card">
                                    <div class="patient-result-icon sys">
                                        <i class="bi bi-speedometer2"></i>
                                    </div>

                                    <div
                                        id="latestSystolic"
                                        class="patient-result-value">
                                        <?= $latestMeasurement
                                            ? esc($latestMeasurement['systolic'])
                                            : '-'
                                        ?>
                                    </div>

                                    <div class="patient-result-label">
                                        Sistolik
                                    </div>

                                    <div class="patient-result-unit">
                                        mmHg
                                    </div>
                                </div>
                            </div>

                            <!-- DIA -->
                            <div class="col-6 col-md-3">
                                <div class="patient-result-card">
                                    <div class="patient-result-icon dia">
                                        <i class="bi bi-droplet-half"></i>
                                    </div>

                                    <div
                                        id="latestDiastolic"
                                        class="patient-result-value">
                                        <?= $latestMeasurement
                                            ? esc($latestMeasurement['diastolic'])
                                            : '-'
                                        ?>
                                    </div>

                                    <div class="patient-result-label">
                                        Diastolik
                                    </div>

                                    <div class="patient-result-unit">
                                        mmHg
                                    </div>
                                </div>
                            </div>

                            <!-- MAP -->
                            <div class="col-6 col-md-3">
                                <div class="patient-result-card">
                                    <div class="patient-result-icon map">
                                        <i class="bi bi-graph-up-arrow"></i>
                                    </div>

                                    <div
                                        id="latestMap"
                                        class="patient-result-value">
                                        <?= $latestMeasurement
                                            ? esc($latestMeasurement['map'])
                                            : '-'
                                        ?>
                                    </div>

                                    <div class="patient-result-label">
                                        MAP
                                    </div>

                                    <div class="patient-result-unit">
                                        mmHg
                                    </div>
                                </div>
                            </div>

                            <!-- BPM -->
                            <div class="col-6 col-md-3">
                                <div class="patient-result-card">
                                    <div class="patient-result-icon bpm">
                                        <i class="bi bi-heart-pulse-fill"></i>
                                    </div>

                                    <div
                                        id="latestBpm"
                                        class="patient-result-value">
                                        <?= $latestMeasurement
                                            ? esc($latestMeasurement['bpm'])
                                            : '-'
                                        ?>
                                    </div>

                                    <div class="patient-result-label">
                                        Denyut Nadi
                                    </div>

                                    <div class="patient-result-unit">
                                        BPM
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="alert alert-info mt-4 mb-0">
                            <div class="d-flex gap-2">
                                <i class="bi bi-info-circle-fill mt-1"></i>

                                <div>
                                    <strong>Petunjuk hasil</strong>

                                    <div class="small mt-1">
                                        Hasil pengukuran digunakan untuk pemantauan.
                                        Perhatikan catatan dokter apabila status
                                        menunjukkan perlu perhatian.
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>
            </div>

            <!-- RIWAYAT -->
            <div class="col-12">
                <section class="medical-card">
                    <div class="medical-card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                            <div>
                                <div class="medical-section-title">
                                    Riwayat Pengukuran
                                </div>

                                <p class="medical-section-description">
                                    Menampilkan hasil tekanan darah dan denyut nadi terbaru.
                                </p>
                            </div>

                            <span class="medical-badge medical-badge-info">
                                <?= count($history) ?> data
                            </span>
                        </div>

                        <div class="medical-table-wrapper table-responsive">
                            <table class="table medical-table align-middle">
                                <thead>
                                    <tr>
                                        <th>Waktu</th>
                                        <th>SYS</th>
                                        <th>DIA</th>
                                        <th>MAP</th>
                                        <th>BPM</th>
                                        <th>Kualitas</th>
                                    </tr>
                                </thead>

                                <tbody id="historyTableBody">
                                    <?php if ($history === []): ?>
                                        <tr>
                                            <td colspan="6">
                                                <div class="medical-empty-state py-4">
                                                    <div class="medical-empty-icon">
                                                        <i class="bi bi-activity"></i>
                                                    </div>

                                                    <div class="medical-empty-title">
                                                        Belum ada riwayat
                                                    </div>

                                                    <p class="medical-empty-description">
                                                        Silakan lakukan pengukuran pertama Anda.
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($history as $item): ?>
                                            <tr>
                                                <td>
                                                    <?= esc(
                                                        $item['measured_at']
                                                    ) ?>
                                                </td>

                                                <td>
                                                    <?= esc(
                                                        $item['systolic']
                                                    ) ?>
                                                </td>

                                                <td>
                                                    <?= esc(
                                                        $item['diastolic']
                                                    ) ?>
                                                </td>

                                                <td>
                                                    <?= esc(
                                                        $item['map']
                                                    ) ?>
                                                </td>

                                                <td>
                                                    <?= esc(
                                                        $item['bpm']
                                                    ) ?>
                                                </td>

                                                <td>
                                                    <?php if (
                                                        $item['quality_status'] === 'valid'
                                                    ): ?>
                                                        <span class="medical-badge medical-badge-success">
                                                            Valid
                                                        </span>

                                                    <?php elseif (
                                                        $item['quality_status'] === 'warning'
                                                    ): ?>
                                                        <span class="medical-badge medical-badge-warning">
                                                            Perlu Perhatian
                                                        </span>

                                                    <?php else: ?>
                                                        <span class="medical-badge medical-badge-danger">
                                                            Tidak Valid
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($latestNote): ?>

                            <section class="patient-note-card">

                                <div class="patient-note-header">
                                    <div class="d-flex justify-content-between align-items-center gap-3">

                                        <div class="d-flex align-items-center gap-3">
                                            <div class="medical-stat-icon primary">
                                                <i class="bi bi-journal-medical"></i>
                                            </div>

                                            <div>
                                                <div class="fw-bold">
                                                    Catatan Terbaru Dokter
                                                </div>

                                                <div class="patient-note-meta mt-1">
                                                    <?= esc($latestNote['doctor_name']) ?>
                                                    ·
                                                    <?= date(
                                                        'd M Y H:i',
                                                        strtotime(
                                                            $latestNote['created_at']
                                                        )
                                                    ) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if (
                                            ! $latestNote['is_read_by_patient']
                                        ): ?>
                                            <span class="medical-badge medical-badge-danger">
                                                Baru
                                            </span>
                                        <?php endif; ?>

                                    </div>
                                </div>

                                <div class="patient-note-body">

                                    <h3 class="h6 fw-bold mb-3">
                                        <?= esc($latestNote['title']) ?>
                                    </h3>

                                    <div class="patient-note-section">
                                        <div class="patient-note-label">
                                            Penilaian
                                        </div>

                                        <div>
                                            <?= nl2br(
                                                esc(
                                                    $latestNote['assessment']
                                                )
                                            ) ?>
                                        </div>
                                    </div>

                                    <div class="patient-note-section">
                                        <div class="patient-note-label">
                                            Rekomendasi
                                        </div>

                                        <div>
                                            <?= nl2br(
                                                esc(
                                                    $latestNote['recommendation']
                                                )
                                            ) ?>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <a
                                            href="<?= site_url('patient/notes') ?>"
                                            class="btn btn-medical-soft">
                                            <i class="bi bi-journal-medical me-1"></i>
                                            Lihat Semua Catatan
                                        </a>
                                    </div>

                                </div>
                            </section>

                        <?php endif; ?>
                        <section class="medical-card mt-4">
                            <div class="medical-card-body">
                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                                    <div>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="medical-stat-icon blue">
                                                <i class="bi bi-camera-video-fill"></i>
                                            </div>

                                            <div>
                                                <div class="medical-section-title">
                                                    Konsultasi Daring
                                                </div>

                                                <p class="medical-section-description">
                                                    Ajukan jadwal pertemuan dengan dokter
                                                    yang sedang menangani Anda.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (! empty($doctorAssignment)): ?>
                                        <span class="medical-badge medical-badge-info">
                                            <?= esc(
                                                $doctorAssignment['doctor_name']
                                            ) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php if (
                                    session()->getFlashdata(
                                        'consultation_success'
                                    )
                                ): ?>
                                    <div
                                        class="alert alert-success alert-dismissible fade show"
                                        role="alert">
                                        <?= esc(
                                            session()->getFlashdata(
                                                'consultation_success'
                                            )
                                        ) ?>

                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="alert"
                                            aria-label="Tutup"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if (
                                    session()->getFlashdata(
                                        'consultation_error'
                                    )
                                ): ?>
                                    <div class="alert alert-danger">
                                        <?= esc(
                                            session()->getFlashdata(
                                                'consultation_error'
                                            )
                                        ) ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (empty($doctorAssignment)): ?>
                                    <div class="alert alert-warning mb-0">
                                        Belum ada dokter aktif yang menangani Anda.
                                    </div>
                                <?php else: ?>
                                    <form
                                        action="<?= site_url(
                                                    'patient/consultation/request'
                                                ) ?>"
                                        method="post">
                                        <?= csrf_field() ?>

                                        <div class="row g-3">
                                            <div class="col-12 col-md-4">
                                                <label
                                                    for="preferred_platform"
                                                    class="form-label fw-semibold">
                                                    Platform Pilihan
                                                </label>

                                                <select
                                                    class="form-select"
                                                    id="preferred_platform"
                                                    name="preferred_platform"
                                                    required>
                                                    <option value="">
                                                        Pilih platform
                                                    </option>

                                                    <option
                                                        value="google_meet"
                                                        <?= old(
                                                            'preferred_platform'
                                                        ) === 'google_meet'
                                                            ? 'selected'
                                                            : ''
                                                        ?>>
                                                        Google Meet
                                                    </option>

                                                    <option
                                                        value="zoom"
                                                        <?= old(
                                                            'preferred_platform'
                                                        ) === 'zoom'
                                                            ? 'selected'
                                                            : ''
                                                        ?>>
                                                        Zoom
                                                    </option>

                                                    <option
                                                        value="whatsapp"
                                                        <?= old(
                                                            'preferred_platform'
                                                        ) === 'whatsapp'
                                                            ? 'selected'
                                                            : ''
                                                        ?>>
                                                        WhatsApp
                                                    </option>

                                                    <option
                                                        value="other"
                                                        <?= old(
                                                            'preferred_platform'
                                                        ) === 'other'
                                                            ? 'selected'
                                                            : ''
                                                        ?>>
                                                        Lainnya
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-6 col-md-4">
                                                <label
                                                    for="preferred_date"
                                                    class="form-label fw-semibold">
                                                    Tanggal Pilihan
                                                </label>

                                                <input
                                                    type="date"
                                                    class="form-control"
                                                    id="preferred_date"
                                                    name="preferred_date"
                                                    value="<?= esc(
                                                                old('preferred_date')
                                                            ) ?>"
                                                    min="<?= date('Y-m-d') ?>">
                                            </div>

                                            <div class="col-6 col-md-4">
                                                <label
                                                    for="preferred_time"
                                                    class="form-label fw-semibold">
                                                    Waktu Pilihan
                                                </label>

                                                <input
                                                    type="time"
                                                    class="form-control"
                                                    id="preferred_time"
                                                    name="preferred_time"
                                                    value="<?= esc(
                                                                old('preferred_time')
                                                            ) ?>">
                                            </div>

                                            <div class="col-12">
                                                <label
                                                    for="message"
                                                    class="form-label fw-semibold">
                                                    Pesan atau Alasan Konsultasi
                                                </label>

                                                <textarea
                                                    class="form-control"
                                                    id="message"
                                                    name="message"
                                                    rows="4"
                                                    placeholder="Jelaskan hal yang ingin dikonsultasikan kepada dokter."
                                                    required><?= esc(
                                                                    old('message')
                                                                ) ?></textarea>
                                            </div>

                                            <div class="col-12 text-end">
                                                <button
                                                    type="submit"
                                                    class="btn btn-medical-primary px-4">
                                                    <i class="bi bi-camera-video me-1"></i>
                                                    Kirim Permintaan
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </section>
                        <section class="medical-card mt-4">
                            <div class="medical-card-body">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div>
                                        <div class="medical-section-title">
                                            Riwayat Permintaan Konsultasi
                                        </div>

                                        <p class="medical-section-description">
                                            Status dan tanggapan dokter untuk permintaan konsultasi Anda.
                                        </p>
                                    </div>

                                    <span class="medical-badge medical-badge-neutral">
                                        <?= count(
                                            $consultationRequests ?? []
                                        ) ?> request
                                    </span>
                                </div>

                                <?php if (
                                    empty($consultationRequests)
                                ): ?>
                                    <div class="medical-empty-state py-4">
                                        <div class="medical-empty-icon">
                                            <i class="bi bi-camera-video-off"></i>
                                        </div>

                                        <div class="medical-empty-title">
                                            Belum ada permintaan konsultasi
                                        </div>

                                        <p class="medical-empty-description">
                                            Permintaan yang Anda kirim akan muncul di sini.
                                        </p>
                                    </div>
                                <?php else: ?>
                                    <div class="medical-table-wrapper table-responsive">
                                        <table class="table medical-table align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Dibuat</th>
                                                    <th>Dokter</th>
                                                    <th>Platform</th>
                                                    <th>Preferensi</th>
                                                    <th>Status</th>
                                                    <th>Jadwal</th>
                                                    <th>Respons</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <?php foreach (
                                                    $consultationRequests as $request
                                                ): ?>
                                                    <?php
                                                    $statusClass = match ($request['status']) {
                                                        'accepted' =>
                                                        'medical-badge-success',

                                                        'rejected' =>
                                                        'medical-badge-danger',

                                                        'rescheduled' =>
                                                        'medical-badge-warning',

                                                        'completed' =>
                                                        'medical-badge-info',

                                                        'cancelled' =>
                                                        'medical-badge-neutral',

                                                        default =>
                                                        'medical-badge-info',
                                                    };

                                                    $statusLabel = match ($request['status']) {
                                                        'accepted' =>
                                                        'Diterima',

                                                        'rejected' =>
                                                        'Ditolak',

                                                        'rescheduled' =>
                                                        'Dijadwalkan Ulang',

                                                        'completed' =>
                                                        'Selesai',

                                                        'cancelled' =>
                                                        'Dibatalkan',

                                                        default =>
                                                        'Menunggu',
                                                    };

                                                    $platformLabel = match ($request['preferred_platform']) {
                                                        'google_meet' =>
                                                        'Google Meet',

                                                        'zoom' =>
                                                        'Zoom',

                                                        'whatsapp' =>
                                                        'WhatsApp',

                                                        default =>
                                                        'Lainnya',
                                                    };
                                                    ?>

                                                    <tr>
                                                        <td>
                                                            <?= esc(
                                                                $request['created_at']
                                                            ) ?>
                                                        </td>

                                                        <td>
                                                            <div class="fw-semibold">
                                                                <?= esc(
                                                                    $request['doctor_name']
                                                                ) ?>
                                                            </div>

                                                            <small class="text-muted">
                                                                <?= esc(
                                                                    $request['doctor_code']
                                                                ) ?>
                                                            </small>
                                                        </td>

                                                        <td>
                                                            <?= esc($platformLabel) ?>
                                                        </td>

                                                        <td>
                                                            <?= esc(
                                                                $request['preferred_date'] ?? '-'
                                                            ) ?>

                                                            <?= ! empty($request['preferred_time'])
                                                                ? esc(
                                                                    ' '
                                                                        . $request['preferred_time']
                                                                )
                                                                : ''
                                                            ?>
                                                        </td>

                                                        <td>
                                                            <span
                                                                class="medical-badge <?= esc(
                                                                                            $statusClass
                                                                                        ) ?>">
                                                                <?= esc($statusLabel) ?>
                                                            </span>
                                                        </td>

                                                        <td>
                                                            <?= esc(
                                                                $request['scheduled_at'] ?? '-'
                                                            ) ?>

                                                            <?php if (
                                                                ! empty($request['meeting_link'])
                                                            ): ?>
                                                                <div class="mt-1">
                                                                    <a
                                                                        href="<?= esc(
                                                                                    $request['meeting_link']
                                                                                ) ?>"
                                                                        target="_blank"
                                                                        rel="noopener noreferrer"
                                                                        class="btn btn-medical-soft btn-sm mt-1">
                                                                        <i class="bi bi-box-arrow-up-right me-1"></i>
                                                                        Buka Pertemuan
                                                                    </a>
                                                                </div>
                                                            <?php endif; ?>
                                                        </td>

                                                        <td>
                                                            <?= nl2br(
                                                                esc(
                                                                    $request['doctor_response'] ?? '-'
                                                                )
                                                            ) ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
                    </div>
            </div>
            </section>
        </div>
    </main>

    <script>
        const startUrl =
            <?= json_encode(
                site_url(
                    'patient/measurement/start'
                )
            ) ?>;

        const statusBaseUrl =
            <?= json_encode(
                site_url(
                    'patient/measurement/status'
                )
            ) ?>;

        const form =
            document.getElementById(
                'measurementForm'
            );

        const startButton =
            document.getElementById(
                'startMeasurementButton'
            );

        const measurementType =
            document.getElementById(
                'measurementType'
            );

        const statusBox =
            document.getElementById(
                'measurementStatusBox'
            );

        const statusTitle =
            document.getElementById(
                'measurementStatusTitle'
            );

        const statusDescription =
            document.getElementById(
                'measurementStatusDescription'
            );

        const statusSpinner =
            document.getElementById(
                'statusSpinner'
            );

        const errorBox =
            document.getElementById(
                'measurementError'
            );

        let currentRequestId =
            <?= $activeRequest
                ? (int) $activeRequest['id']
                : 'null'
            ?>;

        let pollingTimer = null;

        const statusContent = {
            pending: {
                title: 'Menunggu perangkat',
                description: 'Permintaan sedang menunggu ESP32.'
            },

            acknowledged: {
                title: 'Perangkat terhubung',
                description: 'ESP32 telah menerima permintaan.'
            },

            measuring_bpm: {
                title: 'Mengukur denyut nadi',
                description: 'Tutupi LED dan fotodioda dengan bantalan jari.'
            },

            inflating: {
                title: 'Mengembangkan manset',
                description: 'Tetap diam dan jangan menggerakkan lengan.'
            },

            recording: {
                title: 'Merekam tekanan darah',
                description: 'Manset sedang melakukan deflasi dan merekam data.'
            },

            processing: {
                title: 'Memproses hasil',
                description: 'Sistem menghitung SYS, MAP, dan DIA.'
            },

            completed: {
                title: 'Pengukuran selesai',
                description: 'Hasil berhasil disimpan.'
            },

            failed: {
                title: 'Pengukuran gagal',
                description: 'Silakan periksa posisi jari atau manset.'
            },

            expired: {
                title: 'Permintaan kedaluwarsa',
                description: 'Silakan mulai pengukuran kembali.'
            },

            cancelled: {
                title: 'Pengukuran dibatalkan',
                description: 'Permintaan telah dibatalkan.'
            }
        };

        function showError(message) {
            errorBox.textContent = message;
            errorBox.classList.remove('d-none');
        }

        function clearError() {
            errorBox.textContent = '';
            errorBox.classList.add('d-none');
        }

        function updateCsrf(csrf) {
            if (!csrf) {
                return;
            }

            const csrfInput =
                form.querySelector(
                    'input[type="hidden"]'
                );

            if (csrfInput) {
                csrfInput.name = csrf.token;
                csrfInput.value = csrf.hash;
            }
        }

        function updateStatusDisplay(status) {
            const content =
                statusContent[status] ?? {
                    title: status,
                    description: 'Status pengukuran diperbarui.'
                };

            statusBox.style.display = 'block';
            statusTitle.textContent = content.title;
            statusDescription.textContent =
                content.description;
        }

        function updateLatestMeasurement(measurement) {
            if (!measurement) {
                return;
            }

            document.getElementById(
                    'latestSystolic'
                ).textContent =
                measurement.systolic;

            document.getElementById(
                    'latestDiastolic'
                ).textContent =
                measurement.diastolic;

            document.getElementById(
                    'latestMap'
                ).textContent =
                measurement.map;

            document.getElementById(
                    'latestBpm'
                ).textContent =
                measurement.bpm;

            document.getElementById(
                    'latestMeasuredAt'
                ).textContent =
                measurement.measured_at;

            const qualityBadge =
                document.getElementById(
                    'qualityBadge'
                );

            const qualityStatus =
                String(
                    measurement.quality_status ??
                    ''
                );

            qualityBadge.className =
                'medical-badge';

            if (qualityStatus === 'valid') {
                qualityBadge.textContent =
                    'Valid';

                qualityBadge.classList.add(
                    'medical-badge-success'
                );
            } else if (qualityStatus === 'warning') {
                qualityBadge.textContent =
                    'Perlu Perhatian';

                qualityBadge.classList.add(
                    'medical-badge-warning'
                );
            } else if (qualityStatus === 'invalid') {
                qualityBadge.textContent =
                    'Tidak Valid';

                qualityBadge.classList.add(
                    'medical-badge-danger'
                );
            } else {
                qualityBadge.textContent =
                    'Belum Ada';

                qualityBadge.classList.add(
                    'medical-badge-neutral'
                );
            }
        }

        async function pollMeasurementStatus() {
            if (!currentRequestId) {
                return;
            }

            try {
                const response = await fetch(
                    `${statusBaseUrl}/${currentRequestId}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );

                const result =
                    await response.json();

                if (!result.success) {
                    throw new Error(result.message);
                }

                updateCsrf(result.csrf);

                const request =
                    result.data.request;

                updateStatusDisplay(
                    request.status
                );

                if (
                    request.status === 'completed'
                ) {
                    clearInterval(pollingTimer);
                    pollingTimer = null;

                    statusSpinner.classList.add(
                        'd-none'
                    );

                    startButton.disabled = false;
                    measurementType.disabled = false;

                    updateLatestMeasurement(
                        result.data.measurement
                    );

                    currentRequestId = null;

                    setTimeout(
                        function() {
                            window.location.reload();
                        },
                        1800
                    );

                    return;
                }

                if (
                    [
                        'failed',
                        'expired',
                        'cancelled'
                    ].includes(request.status)
                ) {
                    clearInterval(pollingTimer);
                    pollingTimer = null;

                    statusSpinner.classList.add(
                        'd-none'
                    );

                    startButton.disabled = false;
                    measurementType.disabled = false;

                    currentRequestId = null;

                    showError(
                        request.error_message ??
                        'Pengukuran tidak berhasil.'
                    );
                }
            } catch (error) {
                clearInterval(pollingTimer);
                pollingTimer = null;

                startButton.disabled = false;
                measurementType.disabled = false;

                showError(error.message);
            }
        }

        function beginPolling() {
            if (pollingTimer) {
                clearInterval(pollingTimer);
            }

            pollMeasurementStatus();

            pollingTimer = setInterval(
                pollMeasurementStatus,
                2000
            );
        }

        form.addEventListener(
            'submit',
            async function(event) {
                event.preventDefault();

                clearError();

                startButton.disabled = true;
                measurementType.disabled = true;

                statusSpinner.classList.remove(
                    'd-none'
                );

                updateStatusDisplay('pending');

                try {
                    const formData =
                        new FormData(form);

                    const response = await fetch(
                        startUrl, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json'
                            }
                        }
                    );

                    const result =
                        await response.json();

                    updateCsrf(result.csrf);

                    if (!result.success) {
                        throw new Error(
                            result.message
                        );
                    }

                    currentRequestId =
                        result.data
                        .request
                        .request_id;

                    beginPolling();
                } catch (error) {
                    startButton.disabled = false;
                    measurementType.disabled = false;

                    statusSpinner.classList.add(
                        'd-none'
                    );

                    showError(error.message);
                }
            }
        );

        /*
         * Jika dashboard dibuka ketika request masih aktif,
         * lanjutkan polling otomatis.
         */
        if (currentRequestId) {
            startButton.disabled = true;
            measurementType.disabled = true;
            updateStatusDisplay('pending');
            beginPolling();
        }
    </script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>