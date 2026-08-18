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
            --primary: #0f766e;
            --primary-dark: #115e59;
            --background: #f4f7fb;
            --text: #1f2937;
            --muted: #6b7280;
            --card-radius: 18px;
        }

        body {
            min-height: 100vh;
            margin: 0;
            color: var(--text);
            background: var(--background);
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

        .page-header {
            color: #ffffff;
            background:
                linear-gradient(135deg,
                    #0f766e,
                    #2563eb);
        }

        .summary-card {
            height: 100%;
            border: 0;
            border-radius: var(--card-radius);
            box-shadow:
                0 8px 25px rgba(15, 23, 42, 0.07);
        }

        .summary-value {
            font-size: 1.8rem;
            font-weight: 800;
        }

        .summary-label {
            color: var(--muted);
            font-size: 0.86rem;
        }

        .consultation-card {
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            background: #ffffff;
        }

        .information-box {
            border-radius: 13px;
            background: #f8fafc;
        }

        .information-label {
            color: var(--muted);
            font-size: 0.78rem;
        }

        .information-value {
            font-weight: 700;
        }

        .btn-back {
            color: var(--primary);
            border-color: var(--primary);
        }

        .btn-back:hover {
            color: #ffffff;
            background: var(--primary);
            border-color: var(--primary);
        }

        .empty-state {
            padding: 55px 20px;
            text-align: center;
            color: var(--muted);
        }

        .consultation-hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 11px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 650;
        }

        .consultation-list-card {
            overflow: hidden;
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.75);
            border-radius: 20px;
            box-shadow:
                0 12px 32px rgba(15, 23, 42, 0.07);
        }

        .consultation-list-header {
            padding: 21px 22px;
            border-bottom: 1px solid #f1f5f9;
        }

        .consultation-list-body {
            padding: 22px;
        }

        .consultation-card-modern {
            overflow: hidden;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            box-shadow:
                0 10px 26px rgba(15, 23, 42, 0.05);
            transition:
                transform 180ms ease,
                box-shadow 180ms ease,
                border-color 180ms ease;
        }

        .consultation-card-modern:hover {
            transform: translateY(-3px);
            border-color: rgba(20, 184, 166, 0.30);
            box-shadow:
                0 18px 42px rgba(15, 23, 42, 0.10);
        }

        .consultation-card-header {
            padding: 18px 20px;
            background:
                linear-gradient(90deg,
                    #f8fafc,
                    #ffffff);
            border-bottom: 1px solid #f1f5f9;
        }

        .consultation-card-body {
            padding: 20px;
        }

        .consultation-patient-avatar {
            width: 46px;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #ffffff;
            background:
                linear-gradient(135deg,
                    #0f766e,
                    #2563eb);
            border-radius: 14px;
            font-size: 0.95rem;
            font-weight: 800;
            box-shadow:
                0 8px 20px rgba(15, 118, 110, 0.18);
        }

        .consultation-info-box {
            height: 100%;
            padding: 14px;
            background: #f8fafc;
            border: 1px solid #eef2f7;
            border-radius: 14px;
        }

        .consultation-info-icon {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            color: #0f766e;
            background: #ccfbf1;
            border-radius: 11px;
        }

        .consultation-info-label {
            color: #64748b;
            font-size: 0.70rem;
        }

        .consultation-info-value {
            margin-top: 3px;
            color: #0f172a;
            font-size: 0.82rem;
            font-weight: 750;
            word-break: break-word;
        }

        .consultation-message-box {
            padding: 15px 17px;
            margin-bottom: 20px;
            color: #334155;
            background: #f0fdfa;
            border: 1px solid #ccfbf1;
            border-left: 4px solid #0f766e;
            border-radius: 14px;
        }

        .consultation-message-label {
            margin-bottom: 6px;
            color: #0f766e;
            font-size: 0.70rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .consultation-form-panel {
            padding: 18px;
            background: #f8fafc;
            border: 1px solid #eef2f7;
            border-radius: 16px;
        }

        .consultation-final-box {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 15px 17px;
            margin-top: 18px;
            color: #075985;
            background: #e0f2fe;
            border: 1px solid #bae6fd;
            border-radius: 14px;
        }

        @media (max-width: 575.98px) {

            .consultation-card-header,
            .consultation-card-body,
            .consultation-list-header,
            .consultation-list-body {
                padding: 16px;
            }
        }

        /* ==================================================
           RESPONSIVE POLISHING
           ================================================== */

        .consultation-card-body>.col-6,
        .consultation-card-body>.col-md-3 {
            width: 100%;
        }

        .consultation-card-body>.col-6+.col-6,
        .consultation-card-body>.col-md-3+.col-md-3 {
            margin-top: 0;
        }

        .consultation-info-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }

        .consultation-form-panel .form-control,
        .consultation-form-panel .form-select {
            min-width: 0;
        }

        @media (max-width: 991.98px) {
            .consultation-info-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .medical-hero .p-4,
            .medical-hero .p-lg-5 {
                padding: 28px !important;
            }

            .medical-hero-title {
                font-size: 1.9rem;
            }

            .medical-hero-description {
                max-width: 100%;
            }

            .consultation-list-card {
                border-radius: 18px;
            }

            .consultation-final-box {
                align-items: flex-start;
            }
        }

        @media (max-width: 767.98px) {
            .medical-navbar .container {
                gap: 10px;
            }

            .medical-user-info {
                display: none;
            }

            .medical-navbar .btn-medical-danger {
                width: 40px;
                height: 40px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0 !important;
                border-radius: 12px;
                font-size: 0;
            }

            .medical-navbar .btn-medical-danger i {
                margin: 0 !important;
                font-size: 1rem;
            }

            .medical-hero {
                border-radius: 18px;
            }

            .medical-hero .p-4,
            .medical-hero .p-lg-5 {
                padding: 24px !important;
            }

            .medical-hero-title {
                font-size: 1.6rem;
                line-height: 1.2;
            }

            .medical-hero-description {
                font-size: .82rem;
                line-height: 1.55;
            }

            .consultation-hero-chip {
                padding: 6px 9px;
                font-size: .66rem;
            }

            .consultation-list-header,
            .consultation-list-body {
                padding: 16px;
            }

            .consultation-card-header,
            .consultation-card-body {
                padding: 16px;
            }

            .consultation-patient-avatar {
                width: 42px;
                height: 42px;
                border-radius: 13px;
            }

            .consultation-info-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .consultation-form-panel {
                padding: 15px;
            }

            .consultation-form-panel .row>[class*="col-"] {
                width: 100%;
            }

            .consultation-form-panel .text-end {
                text-align: stretch !important;
            }

            .consultation-form-panel button[type="submit"] {
                width: 100%;
            }

            .consultation-final-box {
                flex-direction: column;
            }

            .consultation-final-box .btn {
                width: 100%;
            }
        }

        @media (max-width: 575.98px) {
            .medical-brand-subtitle {
                display: none;
            }

            .medical-brand-icon {
                width: 38px;
                height: 38px;
                border-radius: 12px;
            }

            main.container {
                padding-top: 24px !important;
                padding-bottom: 32px !important;
            }

            .medical-hero .p-4,
            .medical-hero .p-lg-5 {
                padding: 20px !important;
            }

            .medical-hero-title {
                font-size: 1.45rem;
            }

            .consultation-info-grid {
                grid-template-columns: 1fr;
            }

            .consultation-card-header .d-flex,
            .consultation-list-body>.d-flex {
                align-items: flex-start !important;
            }

            .consultation-card-header .medical-badge {
                align-self: flex-start;
            }

            .consultation-message-box {
                padding: 13px 14px;
            }

            .medical-stat-card {
                padding: 15px;
            }

            .medical-stat-value {
                font-size: 1.45rem;
            }

            .medical-stat-label {
                font-size: .78rem;
            }

            .medical-stat-detail {
                font-size: .68rem;
            }
        }
    </style>
</head>

<body class="medical-app">

    <nav class="navbar medical-navbar">
        <div class="container py-2">

            <a
                href="<?= site_url('doctor/dashboard') ?>"
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

                <div class="medical-user-info text-end">
                    <div class="medical-user-name">
                        <?= esc($doctorName ?? 'Dokter') ?>
                    </div>

                    <div class="medical-user-role">
                        <?= esc($doctorCode ?? '-') ?>
                        · Tenaga Medis
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

        <div class="mb-3">
            <a
                href="<?= site_url('doctor/dashboard') ?>"
                class="btn btn-medical-soft btn-sm px-3">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali ke Dashboard
            </a>
        </div>

        <section class="medical-hero mb-4">
            <div class="p-4 p-lg-5">

                <div class="row align-items-center g-4">

                    <div class="col">
                        <div class="medical-hero-content">

                            <div class="medical-hero-eyebrow">
                                Layanan Konsultasi Daring
                            </div>

                            <h1 class="medical-hero-title">
                                Permintaan Konsultasi
                            </h1>

                            <p class="medical-hero-description">
                                Tinjau permintaan pasien, tentukan jadwal,
                                berikan respons, dan kelola tautan pertemuan
                                secara terpusat.
                            </p>

                            <div class="d-flex flex-wrap gap-2 mt-4">

                                <span class="consultation-hero-chip">
                                    <i class="bi bi-google"></i>
                                    Google Meet
                                </span>

                                <span class="consultation-hero-chip">
                                    <i class="bi bi-camera-video"></i>
                                    Zoom
                                </span>

                                <span class="consultation-hero-chip">
                                    <i class="bi bi-whatsapp"></i>
                                    WhatsApp
                                </span>

                                <span class="consultation-hero-chip">
                                    <i class="bi bi-inbox"></i>
                                    <?= count($consultations) ?>
                                    permintaan
                                </span>

                            </div>

                        </div>
                    </div>

                    <div class="col-auto d-none d-lg-block">
                        <div class="medical-hero-illustration">
                            <i class="bi bi-camera-video-fill"></i>
                        </div>
                    </div>

                </div>
            </div>
        </section>

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

        <section class="row g-3 mb-4">

            <div class="col-6 col-xl-3">
                <div class="medical-card medical-card-hover medical-stat-card">

                    <div class="medical-stat-top">
                        <div class="medical-stat-icon warning">
                            <i class="bi bi-hourglass-split"></i>
                        </div>

                        <span class="medical-badge medical-badge-warning">
                            Pending
                        </span>
                    </div>

                    <div class="medical-stat-value">
                        <?= (int) ($summary['pending'] ?? 0) ?>
                    </div>

                    <div class="medical-stat-label">
                        Menunggu Respons
                    </div>

                    <div class="medical-stat-detail">
                        Permintaan yang belum diproses
                    </div>

                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="medical-card medical-card-hover medical-stat-card">

                    <div class="medical-stat-top">
                        <div class="medical-stat-icon success">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>

                        <span class="medical-badge medical-badge-success">
                            Diterima
                        </span>
                    </div>

                    <div class="medical-stat-value">
                        <?= (int) ($summary['accepted'] ?? 0) ?>
                    </div>

                    <div class="medical-stat-label">
                        Konsultasi Diterima
                    </div>

                    <div class="medical-stat-detail">
                        Pertemuan yang telah disetujui
                    </div>

                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="medical-card medical-card-hover medical-stat-card">

                    <div class="medical-stat-top">
                        <div class="medical-stat-icon blue">
                            <i class="bi bi-calendar2-week-fill"></i>
                        </div>

                        <span class="medical-badge medical-badge-info">
                            Jadwal Ulang
                        </span>
                    </div>

                    <div class="medical-stat-value">
                        <?= (int) ($summary['rescheduled'] ?? 0) ?>
                    </div>

                    <div class="medical-stat-label">
                        Dijadwalkan Ulang
                    </div>

                    <div class="medical-stat-detail">
                        Jadwal yang mengalami perubahan
                    </div>

                </div>
            </div>

            <div class="col-6 col-xl-3">
                <div class="medical-card medical-card-hover medical-stat-card">

                    <div class="medical-stat-top">
                        <div class="medical-stat-icon primary">
                            <i class="bi bi-check2-circle"></i>
                        </div>

                        <span class="medical-badge medical-badge-success">
                            Selesai
                        </span>
                    </div>

                    <div class="medical-stat-value">
                        <?= (int) ($summary['completed'] ?? 0) ?>
                    </div>

                    <div class="medical-stat-label">
                        Konsultasi Selesai
                    </div>

                    <div class="medical-stat-detail">
                        Pertemuan yang telah dituntaskan
                    </div>

                </div>
            </div>

        </section>

        <section class="consultation-list-card">

            <div class="consultation-list-header">
            </div>

            <div class="consultation-list-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div class="d-flex align-items-center gap-3">

                        <div class="medical-stat-icon blue">
                            <i class="bi bi-inbox-fill"></i>
                        </div>

                        <div>
                            <div class="medical-section-title">
                                Daftar Permintaan
                            </div>

                            <p class="medical-section-description">
                                Permintaan terbaru ditampilkan pada urutan paling atas.
                            </p>
                        </div>

                    </div>

                    <span class="medical-badge medical-badge-neutral">
                        <?= count($consultations) ?>
                        permintaan
                    </span>

                </div>

                <?php if ($consultations === []): ?>
                    <div class="medical-empty-state">

                        <div class="medical-empty-icon">
                            <i class="bi bi-camera-video-off"></i>
                        </div>

                        <div class="medical-empty-title">
                            Belum ada permintaan konsultasi
                        </div>

                        <p class="medical-empty-description">
                            Permintaan yang dikirim pasien akan tampil di halaman ini.
                        </p>

                    </div>
                <?php else: ?>
                    <div class="d-grid gap-4">
                        <?php foreach (
                            $consultations as $consultation
                        ): ?>
                            <?php
                            $platformLabel = match ($consultation['preferred_platform']) {
                                'google_meet' =>
                                'Google Meet',

                                'zoom' =>
                                'Zoom',

                                'whatsapp' =>
                                'WhatsApp',

                                default =>
                                'Lainnya',
                            };

                            $statusClass = match ($consultation['status']) {
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
                                'medical-badge-warning',
                            };

                            $statusLabel = match ($consultation['status']) {
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

                            $scheduledDate = '';

                            $scheduledTime = '';

                            if (
                                ! empty($consultation['scheduled_at'])
                            ) {
                                $scheduledTimestamp =
                                    strtotime(
                                        $consultation['scheduled_at']
                                    );

                                $scheduledDate =
                                    date(
                                        'Y-m-d',
                                        $scheduledTimestamp
                                    );

                                $scheduledTime =
                                    date(
                                        'H:i',
                                        $scheduledTimestamp
                                    );
                            }
                            ?>

                            <article class="consultation-card-modern">

                                <div class="consultation-card-header">
                                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">

                                        <div class="d-flex align-items-center gap-3">

                                            <div class="consultation-patient-avatar">
                                                <?= esc(
                                                    strtoupper(
                                                        substr(
                                                            $consultation['patient_name'],
                                                            0,
                                                            1
                                                        )
                                                    )
                                                ) ?>
                                            </div>

                                            <div>
                                                <h3 class="h6 fw-bold mb-1">
                                                    <?= esc(
                                                        $consultation['patient_name']
                                                    ) ?>
                                                </h3>

                                                <div class="medical-note-meta">
                                                    <?= esc(
                                                        $consultation['patient_code']
                                                    ) ?>
                                                    ·
                                                    Dibuat
                                                    <?= esc(
                                                        $consultation['created_at']
                                                    ) ?>
                                                </div>
                                            </div>

                                        </div>

                                        <span class="medical-badge <?= esc($statusClass) ?>">
                                            <?= esc($statusLabel) ?>
                                        </span>

                                    </div>

                                </div>

                                <div class="consultation-card-body">

                                    <div class="consultation-info-grid">

                                        <div class="col-6 col-md-3">
                                            <div class="consultation-info-box">

                                                <div class="consultation-info-icon">
                                                    <i class="bi bi-camera-video"></i>
                                                </div>

                                                <div class="consultation-info-label">
                                                    Platform
                                                </div>

                                                <div class="consultation-info-value">
                                                    <?= esc($platformLabel) ?>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-6 col-md-3">
                                            <div class="consultation-info-box">

                                                <div class="consultation-info-icon">
                                                    <i class="bi bi-calendar3"></i>
                                                </div>

                                                <div class="consultation-info-label">
                                                    Tanggal Pilihan
                                                </div>

                                                <div class="consultation-info-value">
                                                    <?= esc(
                                                        $consultation['preferred_date']
                                                            ?? '-'
                                                    ) ?>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-6 col-md-3">
                                            <div class="consultation-info-box">

                                                <div class="consultation-info-icon">
                                                    <i class="bi bi-clock"></i>
                                                </div>

                                                <div class="consultation-info-label">
                                                    Waktu Pilihan
                                                </div>

                                                <div class="consultation-info-value">
                                                    <?= esc(
                                                        $consultation['preferred_time']
                                                            ?? '-'
                                                    ) ?>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-6 col-md-3">
                                            <div class="consultation-info-box">

                                                <div class="consultation-info-icon">
                                                    <i class="bi bi-telephone"></i>
                                                </div>

                                                <div class="consultation-info-label">
                                                    Telepon Pasien
                                                </div>

                                                <div class="consultation-info-value">
                                                    <?= esc(
                                                        $consultation['patient_phone']
                                                            ?? '-'
                                                    ) ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="consultation-message-box">

                                    <div class="consultation-message-label">
                                        Pesan Pasien
                                    </div>

                                    <div>
                                        <?= nl2br(
                                            esc(
                                                $consultation['message']
                                            )
                                        ) ?>
                                    </div>

                                </div>

                                <div class="consultation-form-panel">

                                    <form
                                        action="<?= site_url(
                                                    'doctor/consultation/'
                                                        . $consultation['id']
                                                        . '/update'
                                                ) ?>"
                                        method="post">
                                        <?= csrf_field() ?>

                                        <div class="row g-3">
                                            <div class="col-12 col-md-4">
                                                <label
                                                    class="form-label fw-semibold">
                                                    Keputusan
                                                </label>

                                                <select
                                                    class="form-select"
                                                    name="action"
                                                    required>
                                                    <option value="">
                                                        Pilih tindakan
                                                    </option>

                                                    <option value="accepted">
                                                        Terima
                                                    </option>

                                                    <option value="rescheduled">
                                                        Jadwalkan Ulang
                                                    </option>

                                                    <option value="rejected">
                                                        Tolak
                                                    </option>

                                                    <option value="completed">
                                                        Tandai Selesai
                                                    </option>

                                                    <option value="cancelled">
                                                        Batalkan
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-6 col-md-4">
                                                <label
                                                    class="form-label fw-semibold">
                                                    Tanggal Final
                                                </label>

                                                <input
                                                    type="date"
                                                    class="form-control"
                                                    name="scheduled_date"
                                                    value="<?= esc(
                                                                $scheduledDate
                                                            ) ?>"
                                                    min="<?= date(
                                                                'Y-m-d'
                                                            ) ?>">
                                            </div>

                                            <div class="col-6 col-md-4">
                                                <label
                                                    class="form-label fw-semibold">
                                                    Waktu Final
                                                </label>

                                                <input
                                                    type="time"
                                                    class="form-control"
                                                    name="scheduled_time"
                                                    value="<?= esc(
                                                                $scheduledTime
                                                            ) ?>">
                                            </div>

                                            <div class="col-12">
                                                <label
                                                    class="form-label fw-semibold">
                                                    Link Pertemuan
                                                </label>

                                                <input
                                                    type="url"
                                                    class="form-control"
                                                    name="meeting_link"
                                                    value="<?= esc(
                                                                $consultation['meeting_link'] ?? ''
                                                            ) ?>"
                                                    placeholder="https://meet.google.com/... atau https://zoom.us/...">
                                            </div>

                                            <div class="col-12">
                                                <label
                                                    class="form-label fw-semibold">
                                                    Respons Dokter
                                                </label>

                                                <textarea
                                                    class="form-control"
                                                    name="doctor_response"
                                                    rows="3"
                                                    placeholder="Tuliskan konfirmasi, alasan penolakan, atau arahan konsultasi."><?= esc(
                                                                                                                                        $consultation['doctor_response'] ?? ''
                                                                                                                                    ) ?></textarea>
                                            </div>

                                            <div class="col-12 text-end">
                                                <button
                                                    type="submit"
                                                    class="btn btn-medical-primary px-4">
                                                    <i class="bi bi-save me-1"></i>
                                                    Simpan Keputusan
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <?php if (
                                    ! empty($consultation['scheduled_at'])
                                ): ?>
                                    <hr>

                                    <div class="consultation-final-box">

                                        <div>
                                            <div class="small">
                                                Jadwal final
                                            </div>

                                            <div class="fw-bold mt-1">
                                                <i class="bi bi-calendar-check me-1"></i>

                                                <?= esc(
                                                    $consultation['scheduled_at']
                                                ) ?>
                                            </div>
                                        </div>

                                        <?php if (
                                            ! empty($consultation['meeting_link'])
                                        ): ?>
                                            <a
                                                href="<?= esc(
                                                            $consultation['meeting_link']
                                                        ) ?>"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn btn-medical-primary btn-sm px-3">
                                                <i class="bi bi-camera-video-fill me-1"></i>
                                                Buka Pertemuan
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>