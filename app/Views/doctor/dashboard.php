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
            --blue: #2563eb;
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

        .welcome-card {
            color: #ffffff;
            background:
                linear-gradient(135deg,
                    #0f766e,
                    #2563eb);
        }

        .summary-card {
            height: 100%;
            overflow: hidden;
            border: 0;
            border-radius: var(--card-radius);
            box-shadow:
                0 8px 25px rgba(15, 23, 42, 0.07);
        }

        .summary-icon {
            width: 50px;
            height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            font-size: 1.45rem;
        }

        .summary-value {
            font-size: 1.85rem;
            font-weight: 800;
            line-height: 1;
        }

        .summary-label {
            margin-top: 7px;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .icon-patient {
            color: #2563eb;
            background: #dbeafe;
        }

        .icon-measurement {
            color: #0f766e;
            background: #ccfbf1;
        }

        .icon-warning {
            color: #b45309;
            background: #fef3c7;
        }

        .icon-device {
            color: #7c3aed;
            background: #ede9fe;
        }

        .patient-avatar {
            width: 45px;
            height: 45px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 14px;
            color: #ffffff;
            font-weight: 800;
            background:
                linear-gradient(135deg,
                    #0f766e,
                    #2dd4bf);
        }

        .table thead th {
            color: var(--muted);
            font-size: 0.82rem;
            font-weight: 700;
            white-space: nowrap;
            border-bottom-width: 1px;
        }

        .table tbody td {
            vertical-align: middle;
        }

        .measurement-number {
            font-weight: 700;
        }

        .measurement-unit {
            color: var(--muted);
            font-size: 0.72rem;
        }

        .btn-detail {
            color: #ffffff;
            border: 0;
            border-radius: 10px;
            background: var(--primary);
        }

        .btn-detail:hover {
            color: #ffffff;
            background: var(--primary-dark);
        }

        .device-id {
            font-family: monospace;
            font-size: 0.84rem;
        }

        .empty-state {
            padding: 55px 20px;
            text-align: center;
            color: var(--muted);
        }

        .empty-state i {
            display: block;
            margin-bottom: 15px;
            font-size: 3rem;
            color: #cbd5e1;
        }

        .patient-status-active {
            color: #166534;
            background: #dcfce7;
        }

        .device-status-active {
            color: #166534;
            background: #dcfce7;
        }

        .device-status-inactive {
            color: #991b1b;
            background: #fee2e2;
        }

        .device-status-maintenance {
            color: #92400e;
            background: #fef3c7;
        }

        .notification-button {
            position: relative;
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            color: #374151;
            background: #ffffff;
        }

        .notification-button:hover,
        .notification-button:focus {
            color: var(--primary);
            border-color: #99f6e4;
            background: #f0fdfa;
        }

        .notification-count {
            position: absolute;
            top: -6px;
            right: -6px;
            min-width: 20px;
            height: 20px;
            padding: 0 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
            border-radius: 999px;
            color: #ffffff;
            background: #dc2626;
            font-size: 0.68rem;
            font-weight: 800;
        }

        .notification-menu {
            width: 390px;
            max-width: calc(100vw - 24px);
            max-height: 480px;
            overflow-y: auto;
            padding: 0;
            border: 0;
            border-radius: 16px;
            box-shadow:
                0 18px 45px rgba(15, 23, 42, 0.18);
        }

        .notification-header {
            position: sticky;
            top: 0;
            z-index: 2;
            padding: 16px;
            border-bottom: 1px solid #e5e7eb;
            background: #ffffff;
        }

        .notification-item {
            display: block;
            padding: 14px 16px;
            color: var(--text);
            text-decoration: none;
            border-bottom: 1px solid #f1f5f9;
        }

        .notification-item:hover {
            color: var(--text);
            background: #f8fafc;
        }

        .notification-item.unread {
            background: #f0fdfa;
        }

        .notification-item.unread:hover {
            background: #ccfbf1;
        }

        .notification-icon {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 12px;
        }

        .notification-icon-warning {
            color: #92400e;
            background: #fef3c7;
        }

        .notification-icon-danger {
            color: #991b1b;
            background: #fee2e2;
        }

        .notification-icon-info {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .notification-icon-success {
            color: #166534;
            background: #dcfce7;
        }

        .notification-title {
            font-size: 0.88rem;
            font-weight: 700;
        }

        .notification-message {
            margin-top: 3px;
            color: var(--muted);
            font-size: 0.8rem;
            line-height: 1.4;
        }

        .notification-time {
            margin-top: 6px;
            color: #9ca3af;
            font-size: 0.72rem;
        }

        .notification-empty {
            padding: 35px 18px;
            color: var(--muted);
            text-align: center;
        }

        @media (max-width: 767.98px) {
            .summary-value {
                font-size: 1.55rem;
            }

            .doctor-information {
                display: none;
            }
        }

        /* ==================================================
           RESPONSIVE DOCTOR DASHBOARD
           ================================================== */

        .doctor-dashboard-navbar .container {
            flex-wrap: nowrap;
        }

        .doctor-dashboard-actions {
            min-width: 0;
        }

        .doctor-patient-header-actions {
            min-width: 0;
        }

        .doctor-patient-table {
            min-width: 980px;
        }

        .doctor-patient-table th:first-child,
        .doctor-patient-table td:first-child {
            position: sticky;
            left: 0;
            z-index: 1;
            background: #ffffff;
        }

        .doctor-patient-table thead th:first-child {
            z-index: 2;
            background: var(--medical-surface-soft, #f8fafc);
        }

        .doctor-patient-table tbody tr:hover td:first-child {
            background: #f8fffe;
        }

        @media (max-width: 991.98px) {

            .medical-hero .p-4,
            .medical-hero .p-lg-5 {
                padding: 2rem !important;
            }

            .medical-hero-title {
                font-size: clamp(1.55rem, 5vw, 2rem);
            }

            .doctor-patient-header-actions {
                width: 100%;
                justify-content: space-between;
            }

            .doctor-patient-header-actions .btn {
                flex-shrink: 0;
            }
        }

        @media (max-width: 767.98px) {
            main.container {
                padding-top: 1.25rem !important;
                padding-bottom: 2rem !important;
            }

            .doctor-dashboard-navbar .container {
                gap: 0.65rem;
            }

            .doctor-dashboard-navbar .medical-brand {
                min-width: 0;
                flex: 1 1 auto;
            }

            .doctor-dashboard-navbar .medical-brand-text {
                min-width: 0;
            }

            .doctor-dashboard-navbar .medical-brand-subtitle {
                overflow: hidden;
                max-width: 185px;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .doctor-dashboard-actions {
                gap: 0.5rem !important;
                flex: 0 0 auto;
            }

            .notification-menu {
                position: fixed !important;
                top: 68px !important;
                right: 12px !important;
                left: 12px !important;
                width: auto;
                max-width: none;
                max-height: calc(100vh - 84px);
                margin: 0 !important;
                transform: none !important;
            }

            .medical-hero {
                margin-bottom: 1rem !important;
                border-radius: 18px;
            }

            .medical-hero .p-4,
            .medical-hero .p-lg-5 {
                padding: 1.4rem !important;
            }

            .medical-hero-description {
                font-size: 0.82rem;
                line-height: 1.55;
            }

            .medical-hero .badge {
                padding: 0.48rem 0.7rem !important;
                font-size: 0.68rem;
            }

            .doctor-summary {
                margin-bottom: 1rem !important;
            }

            .medical-stat-card {
                padding: 1rem;
            }

            .medical-stat-icon {
                width: 43px;
                height: 43px;
                border-radius: 13px;
                font-size: 1.1rem;
            }

            .medical-stat-value {
                margin-top: 0.9rem;
                font-size: 1.5rem;
            }

            .medical-stat-label {
                font-size: 0.78rem;
            }

            .medical-stat-detail {
                font-size: 0.67rem;
                line-height: 1.35;
            }

            .medical-badge {
                padding: 5px 8px;
                font-size: 0.62rem;
            }

            .medical-card-body {
                padding: 1rem;
            }

            .doctor-patient-header {
                align-items: stretch !important;
            }

            .doctor-patient-header-actions {
                display: grid !important;
                grid-template-columns: 1fr;
                gap: 0.65rem !important;
            }

            .doctor-patient-header-actions>* {
                width: 100%;
            }

            .doctor-patient-header-actions .medical-badge {
                justify-content: center;
                min-height: 36px;
            }

            .doctor-patient-header-actions .btn {
                min-height: 42px;
            }

            .medical-table-wrapper {
                border-radius: 12px;
            }

            .doctor-patient-table {
                min-width: 920px;
            }

            .doctor-patient-table th,
            .doctor-patient-table td {
                padding: 0.75rem !important;
            }
        }

        @media (max-width: 575.98px) {
            .doctor-dashboard-navbar .medical-brand-icon {
                width: 36px;
                height: 36px;
                border-radius: 11px;
            }

            .doctor-dashboard-navbar .medical-brand {
                gap: 8px;
            }

            .doctor-dashboard-navbar .medical-brand-subtitle {
                display: none;
            }

            .doctor-dashboard-navbar .medical-brand-text {
                font-size: 0.9rem;
            }

            .doctor-dashboard-navbar .notification-button {
                width: 36px;
                height: 36px;
                border-radius: 11px;
            }

            .doctor-dashboard-navbar .btn-logout {
                width: 36px;
                height: 36px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0 !important;
                border-radius: 11px;
            }

            .doctor-dashboard-navbar .btn-logout .logout-label {
                display: none;
            }

            .doctor-dashboard-navbar .btn-logout i {
                margin-right: 0 !important;
            }

            .medical-hero-eyebrow {
                font-size: 0.67rem;
            }

            .medical-hero-title {
                margin-bottom: 0.55rem;
                font-size: 1.35rem;
            }

            .doctor-summary>[class*="col-"] {
                width: 100%;
            }

            .medical-stat-card {
                display: grid;
                grid-template-columns: auto 1fr auto;
                align-items: center;
                gap: 0 0.9rem;
            }

            .medical-stat-top {
                display: contents;
            }

            .medical-stat-icon {
                grid-row: 1 / span 3;
            }

            .medical-stat-top .medical-badge {
                grid-column: 3;
                grid-row: 1;
            }

            .medical-stat-value {
                grid-column: 2;
                grid-row: 1;
                margin-top: 0;
            }

            .medical-stat-label {
                grid-column: 2 / 4;
                grid-row: 2;
                margin-top: 0.3rem;
            }

            .medical-stat-detail {
                grid-column: 2 / 4;
                grid-row: 3;
                margin-top: 0.25rem;
            }

            .medical-section-title {
                font-size: 0.95rem;
            }

            .medical-section-description {
                font-size: 0.72rem;
                line-height: 1.45;
            }
        }

        @media (max-width: 359.98px) {
            .doctor-dashboard-navbar .medical-brand-text {
                display: none;
            }

            .doctor-dashboard-navbar .container {
                justify-content: space-between;
            }

            .notification-menu {
                right: 8px !important;
                left: 8px !important;
            }
        }
    </style>
</head>

<body class="medical-app">

    <nav class="navbar medical-navbar doctor-dashboard-navbar">
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

            <div class="ms-auto d-flex align-items-center gap-3 doctor-dashboard-actions">

                <!-- NOTIFIKASI -->
                <div class="dropdown">
                    <button
                        type="button"
                        class="notification-button"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="outside"
                        aria-expanded="false"
                        aria-label="Notifikasi">
                        <i class="bi bi-bell-fill"></i>

                        <?php if (
                            ($unreadNotificationCount ?? 0) > 0
                        ): ?>
                            <span class="notification-count">
                                <?= (int) min(
                                    $unreadNotificationCount,
                                    99
                                ) ?>
                            </span>
                        <?php endif; ?>
                    </button>

                    <div
                        class="dropdown-menu dropdown-menu-end notification-menu">
                        <div class="notification-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold">
                                        Notifikasi
                                    </div>

                                    <small class="text-muted">
                                        <?= (int) (
                                            $unreadNotificationCount
                                            ?? 0
                                        ) ?>
                                        belum dibaca
                                    </small>
                                </div>

                                <i class="bi bi-bell text-muted"></i>
                            </div>
                        </div>

                        <?php if (empty($notifications)): ?>
                            <div class="notification-empty">
                                <i class="bi bi-bell-slash fs-2 d-block mb-2"></i>
                                Belum ada notifikasi.
                            </div>
                        <?php else: ?>

                            <?php foreach (
                                $notifications as $notification
                            ): ?>
                                <?php
                                $notificationType =
                                    $notification['type']
                                    ?? 'info';

                                $iconClass = match ($notificationType) {
                                    'warning' =>
                                    'notification-icon-warning',

                                    'danger' =>
                                    'notification-icon-danger',

                                    'success' =>
                                    'notification-icon-success',

                                    default =>
                                    'notification-icon-info',
                                };

                                $iconName = match ($notificationType) {
                                    'warning' =>
                                    'bi-exclamation-triangle-fill',

                                    'danger' =>
                                    'bi-exclamation-octagon-fill',

                                    'success' =>
                                    'bi-check-circle-fill',

                                    default =>
                                    'bi-info-circle-fill',
                                };

                                $createdTimestamp =
                                    strtotime(
                                        $notification['created_at']
                                    );

                                $secondsAgo =
                                    $createdTimestamp !== false
                                    ? max(
                                        0,
                                        time() - $createdTimestamp
                                    )
                                    : null;

                                if ($secondsAgo === null) {
                                    $timeLabel = '-';
                                } elseif ($secondsAgo < 60) {
                                    $timeLabel =
                                        $secondsAgo
                                        . ' detik lalu';
                                } elseif ($secondsAgo < 3600) {
                                    $timeLabel =
                                        floor($secondsAgo / 60)
                                        . ' menit lalu';
                                } elseif ($secondsAgo < 86400) {
                                    $timeLabel =
                                        floor($secondsAgo / 3600)
                                        . ' jam lalu';
                                } else {
                                    $timeLabel =
                                        floor($secondsAgo / 86400)
                                        . ' hari lalu';
                                }
                                ?>

                                <a
                                    href="<?= site_url(
                                                'doctor/notification/'
                                                    . $notification['id']
                                                    . '/open'
                                            ) ?>"
                                    class="notification-item
                                <?= (int) $notification['is_read'] === 0
                                    ? 'unread'
                                    : ''
                                ?>">
                                    <div class="d-flex gap-3">
                                        <div
                                            class="notification-icon <?= esc(
                                                                            $iconClass
                                                                        ) ?>">
                                            <i class="bi <?= esc(
                                                                $iconName
                                                            ) ?>"></i>
                                        </div>

                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-start gap-2">
                                                <div class="notification-title flex-grow-1">
                                                    <?= esc(
                                                        $notification['title']
                                                    ) ?>
                                                </div>

                                                <?php if (
                                                    (int) $notification['is_read'] === 0
                                                ): ?>
                                                    <span class="badge rounded-pill text-bg-danger">
                                                        Baru
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="notification-message">
                                                <?= esc(
                                                    $notification['message']
                                                ) ?>
                                            </div>

                                            <div class="notification-time">
                                                <i class="bi bi-clock me-1"></i>
                                                <?= esc($timeLabel) ?>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>

                        <?php endif; ?>
                    </div>
                </div>

                <!-- IDENTITAS DOKTER -->
                <div class="medical-user-info doctor-information text-end">
                    <div class="medical-user-name">
                        <?= esc($doctorName ?? 'Dokter') ?>
                    </div>

                    <div class="medical-user-role">
                        <?= esc($doctorCode ?? '-') ?>

                        <?php if (! empty($specialization)): ?>
                            · <?= esc($specialization) ?>
                        <?php endif; ?>
                    </div>
                </div>

                <a
                    href="<?= site_url('logout') ?>"
                    class="btn btn-medical-danger btn-sm px-3 btn-logout">
                    <i class="bi bi-box-arrow-right me-1"></i>
                    <span class="logout-label">Keluar</span>
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

        <?php if (
            session()->getFlashdata('success')
        ): ?>
            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert">
                <?= esc(
                    session()->getFlashdata(
                        'success'
                    )
                ) ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"></button>
            </div>
        <?php endif; ?>

        <!-- HERO DOKTER -->
        <section class="medical-hero mb-4">
            <div class="p-4 p-lg-5">

                <div class="row align-items-center g-4">

                    <div class="col">
                        <div class="medical-hero-content">

                            <div class="medical-hero-eyebrow">
                                Dashboard Tenaga Medis
                            </div>

                            <h1 class="medical-hero-title">
                                Selamat datang,
                                <?= esc($doctorName ?? 'Dokter') ?>
                            </h1>

                            <p class="medical-hero-description">
                                Pantau tekanan darah, denyut nadi,
                                status perangkat, dan kondisi pasien
                                yang sedang Anda tangani secara terintegrasi.
                            </p>

                            <div class="d-flex flex-wrap gap-2 mt-4">

                                <span
                                    class="badge rounded-pill bg-white bg-opacity-10
                            border border-white border-opacity-25 px-3 py-2">
                                    <i class="bi bi-person-vcard me-1"></i>
                                    <?= esc($doctorCode ?? '-') ?>
                                </span>

                                <?php if (! empty($specialization)): ?>
                                    <span
                                        class="badge rounded-pill bg-white bg-opacity-10
                                border border-white border-opacity-25 px-3 py-2">
                                        <i class="bi bi-hospital me-1"></i>
                                        <?= esc($specialization) ?>
                                    </span>
                                <?php endif; ?>

                                <span
                                    class="badge rounded-pill bg-white bg-opacity-10
                            border border-white border-opacity-25 px-3 py-2">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Akses Dokter Aktif
                                </span>

                            </div>

                        </div>
                    </div>

                    <div class="col-auto d-none d-lg-block">
                        <div class="medical-hero-illustration">
                            <i class="bi bi-person-heart"></i>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- SUMMARY -->
        <section class="row g-3 mb-4 doctor-summary">

            <!-- PASIEN -->
            <div class="col-6 col-xl-3">
                <div class="medical-card medical-card-hover medical-stat-card">

                    <div class="medical-stat-top">
                        <div class="medical-stat-icon blue">
                            <i class="bi bi-people-fill"></i>
                        </div>

                        <span class="medical-badge medical-badge-info">
                            Pasien
                        </span>
                    </div>

                    <div class="medical-stat-value">
                        <?= (int) (
                            $summary['total_patients']
                            ?? 0
                        ) ?>
                    </div>

                    <div class="medical-stat-label">
                        Pasien Ditangani
                    </div>

                    <div class="medical-stat-detail">
                        Penugasan pasien aktif saat ini
                    </div>

                </div>
            </div>

            <!-- PENGUKURAN -->
            <div class="col-6 col-xl-3">
                <div class="medical-card medical-card-hover medical-stat-card">

                    <div class="medical-stat-top">
                        <div class="medical-stat-icon primary">
                            <i class="bi bi-activity"></i>
                        </div>

                        <span class="medical-badge medical-badge-success">
                            Hari Ini
                        </span>
                    </div>

                    <div class="medical-stat-value">
                        <?= (int) (
                            $summary['today_measurements']
                            ?? 0
                        ) ?>
                    </div>

                    <div class="medical-stat-label">
                        Pengukuran Hari Ini
                    </div>

                    <div class="medical-stat-detail">
                        Hasil yang diterima sistem hari ini
                    </div>

                </div>
            </div>

            <!-- WARNING -->
            <div class="col-6 col-xl-3">
                <div class="medical-card medical-card-hover medical-stat-card">

                    <div class="medical-stat-top">
                        <div class="medical-stat-icon warning">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>

                        <span class="medical-badge medical-badge-warning">
                            Perhatian
                        </span>
                    </div>

                    <div class="medical-stat-value">
                        <?= (int) (
                            $summary['warning_patients']
                            ?? 0
                        ) ?>
                    </div>

                    <div class="medical-stat-label">
                        Hasil Perlu Perhatian
                    </div>

                    <div class="medical-stat-detail">
                        Pasien dengan hasil berstatus warning
                    </div>

                </div>
            </div>

            <!-- DEVICE -->
            <div class="col-6 col-xl-3">
                <div class="medical-card medical-card-hover medical-stat-card">

                    <div class="medical-stat-top">
                        <div class="medical-stat-icon success">
                            <i class="bi bi-cpu-fill"></i>
                        </div>

                        <span class="medical-badge medical-badge-success">
                            Online
                        </span>
                    </div>

                    <div class="medical-stat-value">
                        <?= (int) (
                            $summary['active_devices']
                            ?? 0
                        ) ?>
                    </div>

                    <div class="medical-stat-label">
                        Perangkat Online
                    </div>

                    <div class="medical-stat-detail">
                        ESP32 terhubung dalam dua menit terakhir
                    </div>

                </div>
            </div>

        </section>

        <!-- DAFTAR PASIEN -->
        <section class="medical-card">
            <div class="medical-card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 doctor-patient-header">
                    <div>
                        <div class="medical-section-title">
                            Daftar Pasien
                        </div>

                        <p class="medical-section-description">
                            Pasien dengan penugasan aktif yang dapat Anda pantau.
                        </p>
                    </div>

                    <div class="d-flex align-items-center gap-2 doctor-patient-header-actions">
                        <span class="badge rounded-pill text-bg-light border">
                            <?= count($patients) ?> pasien
                        </span>
                        <a
                            href="<?= site_url('doctor/consultations') ?>"
                            class="btn btn-medical-primary btn-sm px-3">
                            <i class="bi bi-camera-video me-1"></i>
                            Permintaan Konsultasi
                        </a>

                    </div>
                </div>

                <?php if ($patients === []): ?>
                    <div class="medical-empty-state">
                        <div class="medical-empty-icon">
                            <i class="bi bi-person-x"></i>
                        </div>

                        <div class="medical-empty-title">
                            Belum ada pasien
                        </div>

                        <p class="medical-empty-description">
                            Belum ada pasien aktif yang ditugaskan
                            kepada dokter ini.
                        </p>
                    </div>
                <?php else: ?>
                    <div class="medical-table-wrapper table-responsive">
                        <table class="table medical-table align-middle doctor-patient-table">
                            <thead>
                                <tr>
                                    <th>Pasien</th>
                                    <th>Perangkat</th>
                                    <th>SYS</th>
                                    <th>DIA</th>
                                    <th>MAP</th>
                                    <th>BPM</th>
                                    <th>Kualitas</th>
                                    <th>Pengukuran Terakhir</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($patients as $patient): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="patient-avatar">
                                                    <?= esc(
                                                        strtoupper(
                                                            substr(
                                                                $patient['patient_name'],
                                                                0,
                                                                1
                                                            )
                                                        )
                                                    ) ?>
                                                </div>

                                                <div>
                                                    <div class="fw-semibold">
                                                        <?= esc(
                                                            $patient['patient_name']
                                                        ) ?>
                                                    </div>

                                                    <small class="text-muted">
                                                        <?= esc(
                                                            $patient['patient_code']
                                                        ) ?>

                                                        <?php if (
                                                            ! empty($patient['gender'])
                                                        ): ?>
                                                            ·
                                                            <?= $patient['gender'] === 'male'
                                                                ? 'Laki-laki'
                                                                : 'Perempuan'
                                                            ?>
                                                        <?php endif; ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <?php if (
                                                ! empty($patient['device_id'])
                                            ): ?>
                                                <div class="device-id fw-semibold">
                                                    <?= esc(
                                                        $patient['device_id']
                                                    ) ?>
                                                </div>

                                                <?php
                                                $connectionStatus =
                                                    $patient['connection_status'] ?? 'never_connected';
                                                ?>

                                                <?php if (
                                                    $connectionStatus === 'online'
                                                ): ?>
                                                    <span class="medical-badge medical-badge-success">
                                                        <i class="bi bi-wifi me-1"></i>
                                                        Online
                                                    </span>
                                                <?php elseif (
                                                    $connectionStatus === 'offline'
                                                ): ?>
                                                    <span class="medical-badge medical-badge-danger">
                                                        <i class="bi bi-wifi-off me-1"></i>
                                                        Offline
                                                    </span>
                                                <?php else: ?>
                                                    <span class="medical-badge medical-badge-neutral">
                                                        Belum Terhubung
                                                    </span>
                                                <?php endif; ?>

                                            <?php else: ?>
                                                <span class="text-muted">
                                                    Belum terhubung
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if (
                                                $patient['systolic'] !== null
                                            ): ?>
                                                <div class="measurement-number">
                                                    <?= esc(
                                                        $patient['systolic']
                                                    ) ?>
                                                </div>

                                                <div class="measurement-unit">
                                                    mmHg
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if (
                                                $patient['diastolic'] !== null
                                            ): ?>
                                                <div class="measurement-number">
                                                    <?= esc(
                                                        $patient['diastolic']
                                                    ) ?>
                                                </div>

                                                <div class="measurement-unit">
                                                    mmHg
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if (
                                                $patient['map'] !== null
                                            ): ?>
                                                <div class="measurement-number">
                                                    <?= esc(
                                                        $patient['map']
                                                    ) ?>
                                                </div>

                                                <div class="measurement-unit">
                                                    mmHg
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if (
                                                $patient['bpm'] !== null
                                            ): ?>
                                                <div class="measurement-number">
                                                    <?= esc(
                                                        $patient['bpm']
                                                    ) ?>
                                                </div>

                                                <div class="measurement-unit">
                                                    BPM
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php
                                            $qualityStatus =
                                                $patient['quality_status'] ?? null;
                                            ?>

                                            <?php if ($qualityStatus === 'valid'): ?>
                                                <span class="medical-badge medical-badge-success">
                                                    Valid
                                                </span>

                                            <?php elseif ($qualityStatus === 'warning'): ?>
                                                <span class="medical-badge medical-badge-warning">
                                                    Perlu Perhatian
                                                </span>

                                            <?php elseif ($qualityStatus === 'invalid'): ?>
                                                <span class="medical-badge medical-badge-danger">
                                                    Tidak Valid
                                                </span>

                                            <?php else: ?>
                                                <span class="medical-badge medical-badge-neutral">
                                                    Belum Ada
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if (
                                                ! empty($patient['measured_at'])
                                            ): ?>
                                                <?= esc(
                                                    $patient['measured_at']
                                                ) ?>
                                            <?php else: ?>
                                                <span class="text-muted">
                                                    Belum pernah
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-end">
                                            <a
                                                href="<?= site_url(
                                                            'doctor/patient/'
                                                                . $patient['patient_id']
                                                        ) ?>"
                                                class="btn btn-medical-primary btn-sm px-3">
                                                <i class="bi bi-eye me-1"></i>
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </section>

    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>