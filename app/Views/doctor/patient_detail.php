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

        .patient-header {
            color: #ffffff;
            background:
                linear-gradient(135deg,
                    #0f766e,
                    #2563eb);
        }

        .patient-avatar {
            width: 72px;
            height: 72px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 22px;
            color: #0f766e;
            background: rgba(255, 255, 255, 0.9);
            font-size: 1.8rem;
            font-weight: 800;
        }

        .result-box {
            height: 100%;
            padding: 18px;
            text-align: center;
            border-radius: 16px;
            background: #f8fafc;
        }

        .result-value {
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1.15;
        }

        .result-label {
            margin-top: 5px;
            font-weight: 700;
        }

        .result-unit {
            color: var(--muted);
            font-size: 0.8rem;
        }

        .information-label {
            color: var(--muted);
            font-size: 0.82rem;
        }

        .information-value {
            font-weight: 600;
        }

        .device-id {
            font-family: monospace;
        }

        .chart-container {
            position: relative;
            height: 360px;
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
            padding: 45px 20px;
            text-align: center;
            color: var(--muted);
        }

        .patient-profile-hero {
            position: relative;
            overflow: hidden;
            color: #ffffff;
            background:
                linear-gradient(135deg,
                    #0f766e 0%,
                    #0d9488 50%,
                    #2563eb 100%);
            border: 0;
            border-radius: 24px;
            box-shadow:
                0 18px 45px rgba(15, 118, 110, 0.20);
        }

        .patient-profile-hero::before {
            position: absolute;
            top: -100px;
            right: -75px;
            width: 270px;
            height: 270px;
            content: '';
            background:
                rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .patient-profile-hero::after {
            position: absolute;
            right: 160px;
            bottom: -135px;
            width: 220px;
            height: 220px;
            content: '';
            background:
                rgba(255, 255, 255, 0.06);
            border-radius: 50%;
        }

        .patient-profile-content {
            position: relative;
            z-index: 1;
        }

        .patient-profile-avatar {
            width: 78px;
            height: 78px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #0f766e;
            background:
                rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(255, 255, 255, 0.45);
            border-radius: 23px;
            font-size: 1.85rem;
            font-weight: 800;
            box-shadow:
                0 12px 28px rgba(15, 23, 42, 0.15);
        }

        .patient-profile-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 11px;
            color: #ffffff;
            background:
                rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 650;
        }

        .patient-information-list {
            display: grid;
            gap: 0;
        }

        .patient-information-item {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            padding: 13px 0;
            border-bottom:
                1px solid #f1f5f9;
        }

        .patient-information-item:first-child {
            padding-top: 0;
        }

        .patient-information-item:last-child {
            padding-bottom: 0;
            border-bottom: 0;
        }

        .patient-information-icon {
            width: 37px;
            height: 37px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #0f766e;
            background: #ccfbf1;
            border-radius: 12px;
        }

        .patient-information-label {
            color: #64748b;
            font-size: 0.72rem;
        }

        .patient-information-value {
            margin-top: 2px;
            color: #0f172a;
            font-size: 0.84rem;
            font-weight: 700;
            word-break: break-word;
        }

        .doctor-result-card {
            position: relative;
            overflow: hidden;
            height: 100%;
            padding: 16px 10px;
            text-align: center;
            background: #f8fafc;
            border: 1px solid #eef2f7;
            border-radius: 16px;
        }

        .doctor-result-icon {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            border-radius: 11px;
            font-size: 0.9rem;
        }

        .doctor-result-icon.sys {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .doctor-result-icon.dia {
            color: #7e22ce;
            background: #f3e8ff;
        }

        .doctor-result-icon.map {
            color: #0f766e;
            background: #ccfbf1;
        }

        .doctor-result-icon.bpm {
            color: #be123c;
            background: #ffe4e6;
        }

        .doctor-result-value {
            color: #0f172a;
            font-size: 1.65rem;
            font-weight: 800;
            line-height: 1;
        }

        .doctor-result-label {
            margin-top: 7px;
            color: #334155;
            font-size: 0.73rem;
            font-weight: 750;
        }

        .doctor-result-unit {
            margin-top: 2px;
            color: #64748b;
            font-size: 0.65rem;
        }

        .patient-action-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .medical-chart-card {
            overflow: hidden;
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.75);
            border-radius: 20px;
            box-shadow:
                0 12px 32px rgba(15, 23, 42, 0.07);
        }

        .medical-chart-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 21px 22px;
            border-bottom: 1px solid #f1f5f9;
        }

        .medical-chart-body {
            padding: 22px;
        }

        .medical-chart-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .medical-chart-legend-item {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px 10px;
            color: #475569;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 650;
        }

        .medical-chart-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .medical-chart-dot.sys {
            background: #2563eb;
        }

        .medical-chart-dot.dia {
            background: #7c3aed;
        }

        .medical-chart-dot.map {
            background: #0f766e;
        }

        .medical-chart-dot.bpm {
            background: #e11d48;
        }

        .medical-history-value {
            color: #0f172a;
            font-size: 0.84rem;
            font-weight: 750;
        }

        .medical-history-unit {
            display: block;
            margin-top: 1px;
            color: #94a3b8;
            font-size: 0.64rem;
        }

        .medical-time-cell {
            min-width: 145px;
        }

        .medical-time-main {
            color: #0f172a;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .medical-history-row-warning {
            background: rgba(254, 243, 199, 0.22);
        }

        .medical-history-row-invalid {
            background: rgba(254, 226, 226, 0.22);
        }

        .medical-note-form {
            overflow: hidden;
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.75);
            border-radius: 20px;
            box-shadow:
                0 12px 32px rgba(15, 23, 42, 0.07);
        }

        .medical-note-form-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 21px 22px;
            border-bottom: 1px solid #f1f5f9;
        }

        .medical-note-form-body {
            padding: 22px;
        }

        .medical-note-tip {
            padding: 14px 16px;
            color: #075985;
            background: #e0f2fe;
            border: 1px solid #bae6fd;
            border-radius: 14px;
            font-size: 0.78rem;
            line-height: 1.55;
        }

        .priority-option-grid {
            display: grid;
            grid-template-columns:
                repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .priority-option {
            position: relative;
        }

        .priority-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .priority-option label {
            width: 100%;
            min-height: 78px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin: 0;
            cursor: pointer;
            color: #475569;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            font-size: 0.76rem;
            font-weight: 700;
            transition:
                transform 180ms ease,
                border-color 180ms ease,
                background 180ms ease,
                box-shadow 180ms ease;
        }

        .priority-option label:hover {
            transform: translateY(-2px);
        }

        .priority-option input:checked+label {
            box-shadow:
                0 8px 20px rgba(15, 23, 42, 0.08);
        }

        .priority-option.normal input:checked+label {
            color: #166534;
            background: #dcfce7;
            border-color: #86efac;
        }

        .priority-option.attention input:checked+label {
            color: #92400e;
            background: #fef3c7;
            border-color: #fde68a;
        }

        .priority-option.urgent input:checked+label {
            color: #991b1b;
            background: #fee2e2;
            border-color: #fecaca;
        }

        .medical-note-timeline {
            position: relative;
            display: grid;
            gap: 18px;
        }

        .medical-note-timeline::before {
            position: absolute;
            top: 8px;
            bottom: 8px;
            left: 20px;
            width: 2px;
            content: '';
            background: #e2e8f0;
        }

        .medical-note-timeline-item {
            position: relative;
            padding-left: 58px;
        }

        .medical-note-timeline-marker {
            position: absolute;
            top: 4px;
            left: 8px;
            z-index: 1;
            width: 26px;
            height: 26px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            border: 4px solid #ffffff;
            border-radius: 50%;
            box-shadow:
                0 4px 12px rgba(15, 23, 42, 0.12);
        }

        .medical-note-timeline-marker.normal {
            background: #16a34a;
        }

        .medical-note-timeline-marker.attention {
            background: #d97706;
        }

        .medical-note-timeline-marker.urgent {
            background: #dc2626;
        }

        .medical-note-entry {
            overflow: hidden;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            box-shadow:
                0 10px 26px rgba(15, 23, 42, 0.05);
        }

        .medical-note-entry-header {
            padding: 17px 18px;
            background:
                linear-gradient(90deg,
                    #f8fafc,
                    #ffffff);
            border-bottom: 1px solid #f1f5f9;
        }

        .medical-note-entry-body {
            padding: 18px;
        }

        .medical-note-meta {
            color: #64748b;
            font-size: 0.72rem;
        }

        .medical-note-related {
            padding: 13px 14px;
            margin-bottom: 14px;
            color: #334155;
            background: #f8fafc;
            border: 1px solid #eef2f7;
            border-radius: 13px;
        }

        .medical-note-content-block {
            padding: 13px 14px;
            background: #f8fafc;
            border-radius: 13px;
        }

        .medical-note-content-block+.medical-note-content-block {
            margin-top: 12px;
        }

        .medical-note-content-label {
            margin-bottom: 6px;
            color: #0f766e;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        @media (max-width: 575.98px) {
            .priority-option-grid {
                grid-template-columns: 1fr;
            }

            .medical-note-timeline::before {
                display: none;
            }

            .medical-note-timeline-item {
                padding-left: 0;
            }

            .medical-note-timeline-marker {
                display: none;
            }
        }

        @media (max-width: 767.98px) {
            .patient-avatar {
                width: 58px;
                height: 58px;
                border-radius: 18px;
                font-size: 1.4rem;
            }

            .result-value {
                font-size: 1.55rem;
            }

            .chart-container {
                height: 300px;
            }

            .doctor-information {
                display: none;
            }
        }

        /* ==================================================
           RESPONSIVE POLISHING
           DETAIL PASIEN DOKTER
           ================================================== */

        @media (max-width: 1199.98px) {
            .medical-chart-header {
                align-items: flex-start;
            }

            .medical-chart-legend {
                justify-content: flex-start;
            }
        }

        @media (max-width: 991.98px) {
            .patient-profile-hero {
                border-radius: 20px;
            }

            .patient-profile-content {
                padding: 30px !important;
            }

            .patient-profile-avatar {
                width: 68px;
                height: 68px;
                border-radius: 20px;
                font-size: 1.6rem;
            }

            .medical-hero-title {
                font-size: 1.9rem;
            }

            .medical-hero-description {
                max-width: 100%;
            }

            .chart-container {
                height: 320px;
            }

            .medical-chart-header,
            .medical-chart-body,
            .medical-card-header,
            .medical-card-body,
            .medical-note-form-header,
            .medical-note-form-body {
                padding-left: 18px;
                padding-right: 18px;
            }
        }

        @media (max-width: 767.98px) {
            .medical-navbar .container {
                gap: 10px;
            }

            .doctor-information {
                display: none !important;
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

            main.container {
                padding-top: 24px !important;
                padding-bottom: 36px !important;
            }

            .patient-action-bar {
                align-items: stretch;
            }

            .patient-action-bar .btn {
                flex: 1 1 100%;
                width: 100%;
            }

            .patient-profile-content {
                padding: 24px !important;
            }

            .patient-profile-hero .d-flex.flex-column.flex-md-row {
                align-items: flex-start !important;
            }

            .patient-profile-avatar {
                width: 60px;
                height: 60px;
                border-radius: 18px;
                font-size: 1.45rem;
            }

            .medical-hero-title {
                font-size: 1.65rem;
                line-height: 1.2;
            }

            .medical-hero-description {
                font-size: .82rem;
                line-height: 1.55;
            }

            .patient-profile-chip {
                padding: 6px 9px;
                font-size: .66rem;
            }

            .patient-information-item {
                gap: 11px;
                padding: 11px 0;
            }

            .patient-information-icon {
                width: 34px;
                height: 34px;
                border-radius: 11px;
            }

            .doctor-result-card {
                padding: 14px 8px;
            }

            .doctor-result-value {
                font-size: 1.5rem;
            }

            .medical-chart-header {
                flex-direction: column;
                align-items: stretch;
            }

            .medical-chart-header>.d-flex.flex-column {
                align-items: flex-start !important;
            }

            .medical-chart-legend {
                width: 100%;
            }

            .medical-chart-legend-item {
                flex: 1 1 calc(50% - 8px);
                justify-content: center;
            }

            .chart-container {
                height: 280px;
                min-width: 0;
            }

            .medical-table-wrapper {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .medical-table {
                min-width: 760px;
            }

            .medical-time-cell {
                position: sticky;
                left: 0;
                z-index: 2;
                background: #ffffff;
                box-shadow: 6px 0 12px rgba(15, 23, 42, 0.03);
            }

            tr.medical-history-row-warning .medical-time-cell {
                background: #fffbeb;
            }

            tr.medical-history-row-invalid .medical-time-cell {
                background: #fef2f2;
            }

            .medical-note-form-header {
                align-items: flex-start;
            }

            .medical-note-form-header>.medical-badge {
                align-self: flex-start;
            }

            .priority-option-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .priority-option label {
                min-height: 68px;
                padding: 8px 4px;
                font-size: .7rem;
            }

            .medical-note-form form .row>.col-12.col-lg-6 {
                width: 100%;
            }

            .medical-note-form form .col-12:last-child .d-flex {
                flex-direction: column-reverse;
            }

            .medical-note-form form .col-12:last-child .btn {
                width: 100%;
            }

            .medical-note-entry-header .d-flex.flex-wrap.justify-content-between {
                flex-direction: column;
            }

            .medical-note-entry-header .d-flex.flex-wrap.gap-2 {
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

            .patient-profile-hero {
                border-radius: 18px;
            }

            .patient-profile-content {
                padding: 20px !important;
            }

            .patient-profile-avatar {
                width: 54px;
                height: 54px;
                border-radius: 16px;
                font-size: 1.3rem;
            }

            .medical-hero-title {
                font-size: 1.45rem;
            }

            .patient-profile-chip {
                max-width: 100%;
                overflow-wrap: anywhere;
            }

            .medical-card-body,
            .medical-card-header,
            .medical-chart-header,
            .medical-chart-body,
            .medical-note-form-header,
            .medical-note-form-body {
                padding: 15px !important;
            }

            .medical-stat-icon {
                width: 38px;
                height: 38px;
            }

            .doctor-result-value {
                font-size: 1.35rem;
            }

            .doctor-result-label {
                font-size: .68rem;
            }

            .doctor-result-unit {
                font-size: .6rem;
            }

            .medical-chart-legend-item {
                flex: 1 1 100%;
            }

            .chart-container {
                height: 250px;
            }

            .priority-option-grid {
                grid-template-columns: 1fr;
            }

            .medical-note-tip {
                padding: 12px 13px;
                font-size: .73rem;
            }

            .medical-note-timeline::before,
            .medical-note-timeline-marker {
                display: none;
            }

            .medical-note-timeline-item {
                padding-left: 0;
            }

            .medical-note-entry {
                border-radius: 15px;
            }

            .medical-note-entry-header,
            .medical-note-entry-body {
                padding: 14px;
            }

            .medical-note-related,
            .medical-note-content-block {
                padding: 11px 12px;
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

                <div class="medical-user-info doctor-information text-end">
                    <div class="medical-user-name">
                        <?= esc($doctorName ?? 'Dokter') ?>
                    </div>

                    <div class="medical-user-role">
                        Tenaga Medis
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

        <div class="patient-action-bar mb-3">

            <a
                href="<?= site_url('doctor/dashboard') ?>"
                class="btn btn-medical-soft btn-sm px-3">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali ke Dashboard
            </a>

            <a
                href="<?= site_url(
                            'doctor/patient/'
                                . $patient['id']
                                . '/report/pdf'
                        ) ?>"
                target="_blank"
                class="btn btn-medical-primary btn-sm px-3">
                <i class="bi bi-file-earmark-pdf me-1"></i>
                Cetak Hasil Pemeriksaan
            </a>

        </div>

        <?php
        $patientName =
            (string) ($patient['name'] ?? 'Pasien');

        $initial =
            strtoupper(
                substr($patientName, 0, 1)
            );

        $age = null;

        if (! empty($patient['birth_date'])) {
            try {
                $birthDate =
                    new DateTime($patient['birth_date']);

                $today =
                    new DateTime();

                $age =
                    $today->diff($birthDate)->y;
            } catch (Throwable $exception) {
                $age = null;
            }
        }

        $genderLabel = match ($patient['gender'] ?? null) {
            'male' => 'Laki-laki',
            'female' => 'Perempuan',
            default => '-',
        };

        $deviceStatus =
            $patient['device_status']
            ?? 'inactive';

        $connectionStatus = 'offline';

        if (! empty($patient['last_seen_at'])) {
            $lastSeenTimestamp =
                strtotime(
                    $patient['last_seen_at']
                );

            if (
                $lastSeenTimestamp !== false
                && (
                    time() - $lastSeenTimestamp
                ) <= 120
            ) {
                $connectionStatus = 'online';
            }
        }
        ?>

        <!-- PROFIL PASIEN -->
        <section class="patient-profile-hero mb-4">
            <div class="patient-profile-content p-4 p-lg-5">

                <div class="d-flex flex-column flex-md-row align-items-md-center gap-4">

                    <div class="patient-profile-avatar">
                        <?= esc($initial) ?>
                    </div>

                    <div class="flex-grow-1">

                        <div class="medical-hero-eyebrow">
                            Detail Monitoring Pasien
                        </div>

                        <h1 class="medical-hero-title mb-2">
                            <?= esc($patientName) ?>
                        </h1>

                        <p class="medical-hero-description">
                            Pantau identitas, perangkat, hasil pengukuran,
                            grafik kesehatan, dan catatan medis pasien.
                        </p>

                        <div class="d-flex flex-wrap gap-2 mt-4">

                            <span class="patient-profile-chip">
                                <i class="bi bi-person-vcard"></i>
                                <?= esc(
                                    $patient['patient_code']
                                        ?? '-'
                                ) ?>
                            </span>

                            <span class="patient-profile-chip">
                                <i class="bi bi-gender-ambiguous"></i>
                                <?= esc($genderLabel) ?>
                            </span>

                            <?php if ($age !== null): ?>
                                <span class="patient-profile-chip">
                                    <i class="bi bi-calendar3"></i>
                                    <?= esc($age) ?> tahun
                                </span>
                            <?php endif; ?>

                            <span class="patient-profile-chip">
                                <i class="bi bi-cpu"></i>
                                <?= esc(
                                    $patient['device_id']
                                        ?? 'Belum ada perangkat'
                                ) ?>
                            </span>

                            <span class="patient-profile-chip">
                                <i class="bi <?= $connectionStatus === 'online'
                                                    ? 'bi-wifi'
                                                    : 'bi-wifi-off'
                                                ?>"></i>

                                <?= $connectionStatus === 'online'
                                    ? 'Perangkat Online'
                                    : 'Perangkat Offline'
                                ?>
                            </span>

                        </div>

                    </div>

                    <div class="text-md-end">

                        <div class="small opacity-75 mb-2">
                            Status Sistem Perangkat
                        </div>

                        <?php if ($deviceStatus === 'active'): ?>
                            <span class="medical-badge medical-badge-success">
                                Aktif
                            </span>

                        <?php elseif (
                            $deviceStatus === 'maintenance'
                        ): ?>
                            <span class="medical-badge medical-badge-warning">
                                Maintenance
                            </span>

                        <?php else: ?>
                            <span class="medical-badge medical-badge-neutral">
                                Tidak Aktif
                            </span>
                        <?php endif; ?>

                    </div>

                </div>
            </div>
        </section>

        <div class="row g-4 mb-4">

            <!-- INFORMASI PASIEN -->
            <div class="col-12 col-lg-4">
                <section class="medical-card h-100">
                    <div class="medical-card-body">

                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="medical-stat-icon primary">
                                <i class="bi bi-person-lines-fill"></i>
                            </div>

                            <div>
                                <div class="medical-section-title">
                                    Informasi Pasien
                                </div>

                                <p class="medical-section-description">
                                    Identitas dan informasi kontak pasien.
                                </p>
                            </div>
                        </div>

                        <div class="patient-information-list">

                            <div class="patient-information-item">
                                <div class="patient-information-icon">
                                    <i class="bi bi-person-vcard"></i>
                                </div>

                                <div>
                                    <div class="patient-information-label">
                                        Kode Pasien
                                    </div>

                                    <div class="patient-information-value">
                                        <?= esc(
                                            $patient['patient_code']
                                                ?? '-'
                                        ) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="patient-information-item">
                                <div class="patient-information-icon">
                                    <i class="bi bi-calendar-event"></i>
                                </div>

                                <div>
                                    <div class="patient-information-label">
                                        Tanggal Lahir
                                    </div>

                                    <div class="patient-information-value">
                                        <?= esc(
                                            $patient['birth_date']
                                                ?? '-'
                                        ) ?>

                                        <?php if ($age !== null): ?>
                                            · <?= esc($age) ?> tahun
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="patient-information-item">
                                <div class="patient-information-icon">
                                    <i class="bi bi-gender-ambiguous"></i>
                                </div>

                                <div>
                                    <div class="patient-information-label">
                                        Jenis Kelamin
                                    </div>

                                    <div class="patient-information-value">
                                        <?= esc($genderLabel) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="patient-information-item">
                                <div class="patient-information-icon">
                                    <i class="bi bi-telephone"></i>
                                </div>

                                <div>
                                    <div class="patient-information-label">
                                        Nomor Telepon
                                    </div>

                                    <div class="patient-information-value">
                                        <?= esc(
                                            $patient['phone']
                                                ?? '-'
                                        ) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="patient-information-item">
                                <div class="patient-information-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>

                                <div>
                                    <div class="patient-information-label">
                                        Alamat
                                    </div>

                                    <div class="patient-information-value">
                                        <?= esc(
                                            $patient['address']
                                                ?? '-'
                                        ) ?>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </section>
            </div>

            <!-- INFORMASI PERANGKAT -->
            <div class="col-12 col-lg-4">
                <section class="medical-card h-100">
                    <div class="medical-card-body">

                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="medical-stat-icon warning">
                                <i class="bi bi-cpu-fill"></i>
                            </div>

                            <div>
                                <div class="medical-section-title">
                                    Informasi Perangkat
                                </div>

                                <p class="medical-section-description">
                                    Identitas dan koneksi perangkat ESP32.
                                </p>
                            </div>
                        </div>

                        <div class="patient-information-list">

                            <div class="patient-information-item">
                                <div class="patient-information-icon">
                                    <i class="bi bi-upc-scan"></i>
                                </div>

                                <div>
                                    <div class="patient-information-label">
                                        Device ID
                                    </div>

                                    <div class="patient-information-value medical-code">
                                        <?= esc(
                                            $patient['device_id']
                                                ?? '-'
                                        ) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="patient-information-item">
                                <div class="patient-information-icon">
                                    <i class="bi bi-device-hdd"></i>
                                </div>

                                <div>
                                    <div class="patient-information-label">
                                        Nama Perangkat
                                    </div>

                                    <div class="patient-information-value">
                                        <?= esc(
                                            $patient['device_name']
                                                ?? '-'
                                        ) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="patient-information-item">
                                <div class="patient-information-icon">
                                    <i class="bi bi-code-square"></i>
                                </div>

                                <div>
                                    <div class="patient-information-label">
                                        Versi Firmware
                                    </div>

                                    <div class="patient-information-value">
                                        <?= esc(
                                            $patient['firmware_version']
                                                ?? '-'
                                        ) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="patient-information-item">
                                <div class="patient-information-icon">
                                    <i class="bi bi-router"></i>
                                </div>

                                <div>
                                    <div class="patient-information-label">
                                        IP Terakhir
                                    </div>

                                    <div class="patient-information-value medical-code">
                                        <?= esc(
                                            $patient['last_ip_address']
                                                ?? '-'
                                        ) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="patient-information-item">
                                <div class="patient-information-icon">
                                    <i class="bi bi-clock-history"></i>
                                </div>

                                <div>
                                    <div class="patient-information-label">
                                        Terakhir Terhubung
                                    </div>

                                    <div class="patient-information-value">
                                        <?= esc(
                                            $patient['last_seen_at']
                                                ?? 'Belum tersedia'
                                        ) ?>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </section>
            </div>

            <!-- STATUS TERBARU -->
            <div class="col-12 col-lg-4">
                <section class="medical-card h-100">
                    <div class="medical-card-body">

                        <?php
                        $latestQuality =
                            $latestMeasurement['quality_status']
                            ?? null;
                        ?>

                        <div class="d-flex justify-content-between align-items-start gap-3 mb-4">

                            <div>
                                <div class="medical-section-title">
                                    Hasil Terbaru
                                </div>

                                <p class="medical-section-description mt-1">
                                    <?= esc(
                                        $latestMeasurement['measured_at']
                                            ?? 'Belum ada pengukuran'
                                    ) ?>
                                </p>
                            </div>

                            <?php if ($latestQuality === 'valid'): ?>
                                <span class="medical-badge medical-badge-success">
                                    Valid
                                </span>

                            <?php elseif (
                                $latestQuality === 'warning'
                            ): ?>
                                <span class="medical-badge medical-badge-warning">
                                    Perlu Perhatian
                                </span>

                            <?php elseif (
                                $latestQuality === 'invalid'
                            ): ?>
                                <span class="medical-badge medical-badge-danger">
                                    Tidak Valid
                                </span>

                            <?php else: ?>
                                <span class="medical-badge medical-badge-neutral">
                                    Belum Ada
                                </span>
                            <?php endif; ?>

                        </div>

                        <?php if ($latestMeasurement): ?>

                            <div class="row g-3">

                                <div class="col-6">
                                    <div class="doctor-result-card">
                                        <div class="doctor-result-icon sys">
                                            <i class="bi bi-speedometer2"></i>
                                        </div>

                                        <div class="doctor-result-value">
                                            <?= esc(
                                                $latestMeasurement['systolic']
                                            ) ?>
                                        </div>

                                        <div class="doctor-result-label">
                                            Sistolik
                                        </div>

                                        <div class="doctor-result-unit">
                                            mmHg
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="doctor-result-card">
                                        <div class="doctor-result-icon dia">
                                            <i class="bi bi-droplet-half"></i>
                                        </div>

                                        <div class="doctor-result-value">
                                            <?= esc(
                                                $latestMeasurement['diastolic']
                                            ) ?>
                                        </div>

                                        <div class="doctor-result-label">
                                            Diastolik
                                        </div>

                                        <div class="doctor-result-unit">
                                            mmHg
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="doctor-result-card">
                                        <div class="doctor-result-icon map">
                                            <i class="bi bi-graph-up-arrow"></i>
                                        </div>

                                        <div class="doctor-result-value">
                                            <?= esc(
                                                $latestMeasurement['map']
                                            ) ?>
                                        </div>

                                        <div class="doctor-result-label">
                                            MAP
                                        </div>

                                        <div class="doctor-result-unit">
                                            mmHg
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="doctor-result-card">
                                        <div class="doctor-result-icon bpm">
                                            <i class="bi bi-heart-pulse-fill"></i>
                                        </div>

                                        <div class="doctor-result-value">
                                            <?= esc(
                                                $latestMeasurement['bpm']
                                            ) ?>
                                        </div>

                                        <div class="doctor-result-label">
                                            Denyut Nadi
                                        </div>

                                        <div class="doctor-result-unit">
                                            BPM
                                        </div>
                                    </div>
                                </div>

                            </div>

                        <?php else: ?>

                            <div class="medical-empty-state py-4">
                                <div class="medical-empty-icon">
                                    <i class="bi bi-activity"></i>
                                </div>

                                <div class="medical-empty-title">
                                    Belum ada pengukuran
                                </div>

                                <p class="medical-empty-description">
                                    Hasil terbaru akan muncul setelah
                                    pasien melakukan pengukuran.
                                </p>
                            </div>

                        <?php endif; ?>

                    </div>
                </section>
            </div>

            <!-- GRAFIK -->
            <section class="medical-chart-card mb-4">

                <div class="medical-chart-header">

                    <div class="d-flex align-items-center gap-3">

                        <div class="medical-stat-icon primary">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>

                        <div>
                            <div class="medical-section-title">
                                Grafik Riwayat Pengukuran
                            </div>

                            <p class="medical-section-description">
                                Perubahan tekanan darah dan denyut nadi
                                dari maksimal 30 pengukuran terakhir.
                            </p>
                        </div>

                    </div>

                    <div class="d-flex flex-column align-items-md-end gap-2">

                        <span class="medical-badge medical-badge-info">
                            <?= count($history) ?> data
                        </span>

                        <?php if ($history !== []): ?>
                            <div class="medical-chart-legend">

                                <span class="medical-chart-legend-item">
                                    <span class="medical-chart-dot sys"></span>
                                    Sistolik
                                </span>

                                <span class="medical-chart-legend-item">
                                    <span class="medical-chart-dot dia"></span>
                                    Diastolik
                                </span>

                                <span class="medical-chart-legend-item">
                                    <span class="medical-chart-dot map"></span>
                                    MAP
                                </span>

                                <span class="medical-chart-legend-item">
                                    <span class="medical-chart-dot bpm"></span>
                                    BPM
                                </span>

                            </div>
                        <?php endif; ?>

                    </div>

                </div>

                <div class="medical-chart-body">

                    <?php if ($history === []): ?>

                        <div class="medical-empty-state">

                            <div class="medical-empty-icon">
                                <i class="bi bi-graph-up"></i>
                            </div>

                            <div class="medical-empty-title">
                                Belum ada data grafik
                            </div>

                            <p class="medical-empty-description">
                                Grafik akan muncul setelah pasien melakukan
                                pengukuran.
                            </p>

                        </div>

                    <?php else: ?>

                        <div class="chart-container">
                            <canvas id="measurementChart"></canvas>
                        </div>

                    <?php endif; ?>

                </div>

            </section>

            <!-- RIWAYAT -->
            <section class="medical-card">

                <div class="medical-card-header">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                        <div class="d-flex align-items-center gap-3">

                            <div class="medical-stat-icon blue">
                                <i class="bi bi-clock-history"></i>
                            </div>

                            <div>
                                <div class="medical-section-title">
                                    Riwayat Pengukuran
                                </div>

                                <p class="medical-section-description">
                                    Data terbaru ditampilkan pada urutan paling atas.
                                </p>
                            </div>

                        </div>

                        <span class="medical-badge medical-badge-neutral">
                            <?= count($history) ?> pengukuran
                        </span>

                    </div>

                </div>

                <div class="medical-card-body">

                    <?php if ($history === []): ?>

                        <div class="medical-empty-state">

                            <div class="medical-empty-icon">
                                <i class="bi bi-activity"></i>
                            </div>

                            <div class="medical-empty-title">
                                Belum ada riwayat pengukuran
                            </div>

                            <p class="medical-empty-description">
                                Hasil pengukuran pasien akan ditampilkan di sini.
                            </p>

                        </div>

                    <?php else: ?>

                        <div class="medical-table-wrapper table-responsive">

                            <table class="table medical-table align-middle">

                                <thead>
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Sistolik</th>
                                        <th>Diastolik</th>
                                        <th>MAP</th>
                                        <th>Denyut Nadi</th>
                                        <th>Jumlah Beat</th>
                                        <th>Kualitas</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php foreach ($history as $item): ?>

                                        <?php
                                        $rowClass = match ($item['quality_status']
                                            ?? null) {
                                            'warning' =>
                                            'medical-history-row-warning',

                                            'invalid' =>
                                            'medical-history-row-invalid',

                                            default =>
                                            '',
                                        };
                                        ?>

                                        <tr class="<?= esc($rowClass) ?>">

                                            <td class="medical-time-cell">
                                                <div class="medical-time-main">
                                                    <?= esc(
                                                        $item['measured_at']
                                                    ) ?>
                                                </div>
                                            </td>

                                            <td>
                                                <span class="medical-history-value">
                                                    <?= esc(
                                                        $item['systolic']
                                                    ) ?>
                                                </span>

                                                <span class="medical-history-unit">
                                                    mmHg
                                                </span>
                                            </td>

                                            <td>
                                                <span class="medical-history-value">
                                                    <?= esc(
                                                        $item['diastolic']
                                                    ) ?>
                                                </span>

                                                <span class="medical-history-unit">
                                                    mmHg
                                                </span>
                                            </td>

                                            <td>
                                                <span class="medical-history-value">
                                                    <?= esc($item['map']) ?>
                                                </span>

                                                <span class="medical-history-unit">
                                                    mmHg
                                                </span>
                                            </td>

                                            <td>
                                                <span class="medical-history-value">
                                                    <?= esc(
                                                        $item['bpm']
                                                            ?? '-'
                                                    ) ?>
                                                </span>

                                                <span class="medical-history-unit">
                                                    BPM
                                                </span>
                                            </td>

                                            <td>
                                                <span class="medical-history-value">
                                                    <?= esc(
                                                        $item['beat_count']
                                                            ?? '-'
                                                    ) ?>
                                                </span>
                                            </td>

                                            <td>

                                                <?php if (
                                                    $item['quality_status']
                                                    === 'valid'
                                                ): ?>

                                                    <span class="medical-badge medical-badge-success">
                                                        Valid
                                                    </span>

                                                <?php elseif (
                                                    $item['quality_status']
                                                    === 'warning'
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

                                </tbody>

                            </table>

                        </div>

                    <?php endif; ?>

                </div>

            </section>

            <!-- CATATAN DAN ARAHAN DOKTER -->
            <section class="medical-note-form mt-4">

                <div class="medical-note-form-header">

                    <div class="d-flex align-items-center gap-3">

                        <div class="medical-stat-icon primary">
                            <i class="bi bi-journal-plus"></i>
                        </div>

                        <div>
                            <div class="medical-section-title">
                                Catatan dan Arahan Dokter
                            </div>

                            <p class="medical-section-description">
                                Berikan penilaian dan rekomendasi berdasarkan
                                hasil pemantauan pasien.
                            </p>
                        </div>

                    </div>

                    <span class="medical-badge medical-badge-info">
                        Rekam Medis
                    </span>

                </div>

                <div class="medical-note-form-body">

                    <?php if (
                        session()->getFlashdata('note_success')
                    ): ?>
                        <div
                            class="alert alert-success alert-dismissible fade show"
                            role="alert">
                            <?= esc(
                                session()->getFlashdata(
                                    'note_success'
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
                        session()->getFlashdata('note_error')
                    ): ?>
                        <div class="alert alert-danger">
                            <?= esc(
                                session()->getFlashdata(
                                    'note_error'
                                )
                            ) ?>
                        </div>
                    <?php endif; ?>

                    <div class="medical-note-tip mb-4">
                        <div class="d-flex gap-2">
                            <i class="bi bi-info-circle-fill mt-1"></i>

                            <div>
                                Catatan dapat dihubungkan ke satu hasil pengukuran
                                tertentu atau digunakan sebagai catatan umum pasien.
                                Pastikan rekomendasi ditulis dengan jelas dan mudah
                                dipahami pasien.
                            </div>
                        </div>
                    </div>

                    <form
                        action="<?= site_url(
                                    'doctor/patient/'
                                        . $patient['id']
                                        . '/note'
                                ) ?>"
                        method="post">
                        <?= csrf_field() ?>

                        <div class="row g-4">

                            <div class="col-12 col-lg-7">
                                <label
                                    for="measurement_id"
                                    class="form-label">
                                    Hasil Pengukuran Terkait
                                </label>

                                <select
                                    class="form-select"
                                    id="measurement_id"
                                    name="measurement_id">
                                    <option value="">
                                        Catatan umum pasien
                                    </option>

                                    <?php foreach (
                                        $history as $measurement
                                    ): ?>
                                        <option
                                            value="<?= esc(
                                                        $measurement['id']
                                                    ) ?>"
                                            <?= old('measurement_id')
                                                == $measurement['id']
                                                ? 'selected'
                                                : ''
                                            ?>>
                                            <?= esc(
                                                $measurement['measured_at']
                                            ) ?>
                                            —
                                            SYS <?= esc(
                                                    $measurement['systolic']
                                                ) ?>
                                            /
                                            DIA <?= esc(
                                                    $measurement['diastolic']
                                                ) ?>
                                            —
                                            BPM <?= esc(
                                                    $measurement['bpm']
                                                        ?? '-'
                                                ) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <div class="form-text">
                                    Kosongkan untuk membuat catatan umum pasien.
                                </div>
                            </div>

                            <div class="col-12 col-lg-5">

                                <label class="form-label">
                                    Prioritas Catatan
                                </label>

                                <?php
                                $selectedPriority =
                                    old(
                                        'priority',
                                        'normal'
                                    );
                                ?>

                                <div class="priority-option-grid">

                                    <div class="priority-option normal">
                                        <input
                                            type="radio"
                                            id="priorityNormal"
                                            name="priority"
                                            value="normal"
                                            <?= $selectedPriority === 'normal'
                                                ? 'checked'
                                                : ''
                                            ?>>

                                        <label for="priorityNormal">
                                            <i class="bi bi-check-circle-fill fs-5"></i>
                                            Normal
                                        </label>
                                    </div>

                                    <div class="priority-option attention">
                                        <input
                                            type="radio"
                                            id="priorityAttention"
                                            name="priority"
                                            value="attention"
                                            <?= $selectedPriority === 'attention'
                                                ? 'checked'
                                                : ''
                                            ?>>

                                        <label for="priorityAttention">
                                            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                                            Perhatian
                                        </label>
                                    </div>

                                    <div class="priority-option urgent">
                                        <input
                                            type="radio"
                                            id="priorityUrgent"
                                            name="priority"
                                            value="urgent"
                                            <?= $selectedPriority === 'urgent'
                                                ? 'checked'
                                                : ''
                                            ?>>

                                        <label for="priorityUrgent">
                                            <i class="bi bi-exclamation-octagon-fill fs-5"></i>
                                            Mendesak
                                        </label>
                                    </div>

                                </div>
                            </div>

                            <div class="col-12">
                                <label
                                    for="title"
                                    class="form-label">
                                    Judul Catatan
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="title"
                                    name="title"
                                    value="<?= esc(
                                                old('title')
                                            ) ?>"
                                    maxlength="150"
                                    placeholder="Contoh: Evaluasi tekanan darah terbaru">
                            </div>

                            <div class="col-12 col-lg-6">
                                <label
                                    for="assessment"
                                    class="form-label">
                                    Penilaian
                                </label>

                                <textarea
                                    class="form-control"
                                    id="assessment"
                                    name="assessment"
                                    rows="7"
                                    placeholder="Tuliskan penilaian dokter terhadap kondisi atau hasil pengukuran pasien."><?= esc(
                                                                                                                                old('assessment')
                                                                                                                            ) ?></textarea>
                            </div>

                            <div class="col-12 col-lg-6">
                                <label
                                    for="recommendation"
                                    class="form-label">
                                    Rekomendasi atau Arahan
                                </label>

                                <textarea
                                    class="form-control"
                                    id="recommendation"
                                    name="recommendation"
                                    rows="7"
                                    placeholder="Tuliskan arahan yang perlu dilakukan pasien."
                                    required><?= esc(
                                                    old('recommendation')
                                                ) ?></textarea>
                            </div>

                            <div class="col-12">
                                <div class="d-flex flex-wrap justify-content-end gap-2">

                                    <button
                                        type="reset"
                                        class="btn btn-light px-4">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>
                                        Reset
                                    </button>

                                    <button
                                        type="submit"
                                        class="btn btn-medical-primary px-4">
                                        <i class="bi bi-save me-1"></i>
                                        Simpan Catatan
                                    </button>

                                </div>
                            </div>

                        </div>
                    </form>

                </div>

            </section>

            <!-- RIWAYAT CATATAN DOKTER -->
            <section class="medical-card mt-4">

                <div class="medical-card-header">

                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                        <div class="d-flex align-items-center gap-3">

                            <div class="medical-stat-icon blue">
                                <i class="bi bi-journals"></i>
                            </div>

                            <div>
                                <div class="medical-section-title">
                                    Riwayat Catatan Dokter
                                </div>

                                <p class="medical-section-description">
                                    Catatan terbaru ditampilkan pada urutan paling atas.
                                </p>
                            </div>

                        </div>

                        <span class="medical-badge medical-badge-neutral">
                            <?= count(
                                $medicalNotes ?? []
                            ) ?>
                            catatan
                        </span>

                    </div>

                </div>

                <div class="medical-card-body">

                    <?php if (empty($medicalNotes)): ?>

                        <div class="medical-empty-state">

                            <div class="medical-empty-icon">
                                <i class="bi bi-journal-medical"></i>
                            </div>

                            <div class="medical-empty-title">
                                Belum ada catatan dokter
                            </div>

                            <p class="medical-empty-description">
                                Catatan yang tersimpan akan ditampilkan di sini.
                            </p>

                        </div>

                    <?php else: ?>

                        <div class="medical-note-timeline">

                            <?php foreach (
                                $medicalNotes as $note
                            ): ?>

                                <?php
                                $priorityTheme = match ($note['priority']) {
                                    'urgent' =>
                                    'urgent',

                                    'attention' =>
                                    'attention',

                                    default =>
                                    'normal',
                                };

                                $priorityBadge = match ($note['priority']) {
                                    'urgent' =>
                                    'medical-badge-danger',

                                    'attention' =>
                                    'medical-badge-warning',

                                    default =>
                                    'medical-badge-success',
                                };

                                $priorityLabel = match ($note['priority']) {
                                    'urgent' =>
                                    'Mendesak',

                                    'attention' =>
                                    'Perlu Perhatian',

                                    default =>
                                    'Normal',
                                };
                                ?>

                                <div class="medical-note-timeline-item">

                                    <div
                                        class="medical-note-timeline-marker <?= esc(
                                                                                $priorityTheme
                                                                            ) ?>">
                                        <i class="bi bi-journal-medical"></i>
                                    </div>

                                    <article class="medical-note-entry">

                                        <div class="medical-note-entry-header">

                                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">

                                                <div>
                                                    <h3 class="h6 fw-bold mb-1">
                                                        <?= esc(
                                                            $note['title']
                                                                ?: 'Catatan Dokter'
                                                        ) ?>
                                                    </h3>

                                                    <div class="medical-note-meta">
                                                        <?= esc(
                                                            $note['doctor_name']
                                                        ) ?>
                                                        ·
                                                        <?= esc(
                                                            $note['created_at']
                                                        ) ?>
                                                    </div>
                                                </div>

                                                <div class="d-flex flex-wrap gap-2">

                                                    <span
                                                        class="medical-badge <?= esc(
                                                                                    $priorityBadge
                                                                                ) ?>">
                                                        <?= esc(
                                                            $priorityLabel
                                                        ) ?>
                                                    </span>

                                                    <?php if (
                                                        (int) $note['is_read_by_patient']
                                                        === 1
                                                    ): ?>
                                                        <span class="medical-badge medical-badge-info">
                                                            Sudah Dibaca
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="medical-badge medical-badge-neutral">
                                                            Belum Dibaca
                                                        </span>
                                                    <?php endif; ?>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="medical-note-entry-body">

                                            <?php if (
                                                ! empty($note['measurement_id'])
                                            ): ?>
                                                <div class="medical-note-related">

                                                    <div class="small text-muted mb-1">
                                                        Hasil pengukuran terkait
                                                    </div>

                                                    <div class="fw-bold">
                                                        SYS
                                                        <?= esc(
                                                            $note['systolic']
                                                        ) ?>

                                                        /
                                                        DIA
                                                        <?= esc(
                                                            $note['diastolic']
                                                        ) ?>

                                                        · MAP
                                                        <?= esc(
                                                            $note['map']
                                                        ) ?>

                                                        · BPM
                                                        <?= esc(
                                                            $note['bpm']
                                                                ?? '-'
                                                        ) ?>
                                                    </div>

                                                    <div class="medical-note-meta mt-1">
                                                        <?= esc(
                                                            $note['measured_at']
                                                        ) ?>
                                                    </div>

                                                </div>
                                            <?php endif; ?>

                                            <?php if (
                                                ! empty($note['assessment'])
                                            ): ?>
                                                <div class="medical-note-content-block">

                                                    <div class="medical-note-content-label">
                                                        Penilaian
                                                    </div>

                                                    <div>
                                                        <?= nl2br(
                                                            esc(
                                                                $note['assessment']
                                                            )
                                                        ) ?>
                                                    </div>

                                                </div>
                                            <?php endif; ?>

                                            <div class="medical-note-content-block">

                                                <div class="medical-note-content-label">
                                                    Rekomendasi
                                                </div>

                                                <div>
                                                    <?= nl2br(
                                                        esc(
                                                            $note['recommendation']
                                                        )
                                                    ) ?>
                                                </div>

                                            </div>

                                        </div>

                                    </article>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>

                </div>

            </section>

    </main>

    <?php if ($history !== []): ?>
        <?php
        /*
     * Controller mengirim data terbaru terlebih dahulu.
     * Grafik membutuhkan urutan lama ke baru.
     */
        $chartHistory =
            array_reverse($history);

        $chartLabels =
            array_map(
                static fn(array $item): string =>
                (string) $item['measured_at'],
                $chartHistory
            );

        $chartSystolic =
            array_map(
                static fn(array $item): float =>
                (float) $item['systolic'],
                $chartHistory
            );

        $chartDiastolic =
            array_map(
                static fn(array $item): float =>
                (float) $item['diastolic'],
                $chartHistory
            );

        $chartMap =
            array_map(
                static fn(array $item): float =>
                (float) $item['map'],
                $chartHistory
            );

        $chartBpm =
            array_map(
                static fn(array $item): ?int =>
                $item['bpm'] !== null
                    ? (int) $item['bpm']
                    : null,
                $chartHistory
            );
        ?>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const chartElement =
                document.getElementById(
                    'measurementChart'
                );

            const chartLabels =
                <?= json_encode(
                    $chartLabels,
                    JSON_UNESCAPED_UNICODE
                        | JSON_UNESCAPED_SLASHES
                ) ?>;

            const chartSystolic =
                <?= json_encode($chartSystolic) ?>;

            const chartDiastolic =
                <?= json_encode($chartDiastolic) ?>;

            const chartMap =
                <?= json_encode($chartMap) ?>;

            const chartBpm =
                <?= json_encode($chartBpm) ?>;

            new Chart(
                chartElement, {
                    type: 'line',

                    data: {
                        labels: chartLabels,

                        datasets: [{
                                label: 'Sistolik',
                                data: chartSystolic,
                                borderColor: '#2563eb',
                                backgroundColor: 'rgba(37, 99, 235, 0.10)',
                                pointBackgroundColor: '#2563eb',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                borderWidth: 3,
                                tension: 0.35,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                yAxisID: 'pressureAxis'
                            },
                            {
                                label: 'Diastolik',
                                data: chartDiastolic,
                                borderColor: '#7c3aed',
                                backgroundColor: 'rgba(124, 58, 237, 0.10)',
                                pointBackgroundColor: '#7c3aed',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                borderWidth: 3,
                                tension: 0.35,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                yAxisID: 'pressureAxis'
                            },
                            {
                                label: 'MAP',
                                data: chartMap,
                                borderColor: '#0f766e',
                                backgroundColor: 'rgba(15, 118, 110, 0.10)',
                                pointBackgroundColor: '#0f766e',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                borderWidth: 2,
                                tension: 0.35,
                                pointRadius: 3,
                                pointHoverRadius: 5,
                                yAxisID: 'pressureAxis'
                            },
                            {
                                label: 'BPM',
                                data: chartBpm,
                                borderColor: '#e11d48',
                                backgroundColor: 'rgba(225, 29, 72, 0.08)',
                                pointBackgroundColor: '#e11d48',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                borderWidth: 2,
                                tension: 0.35,
                                pointRadius: 3,
                                pointHoverRadius: 5,
                                borderDash: [6, 4],
                                yAxisID: 'bpmAxis'
                            }
                        ]
                    },

                    options: {
                        responsive: true,
                        maintainAspectRatio: false,

                        interaction: {
                            mode: 'index',
                            intersect: false
                        },

                        plugins: {
                            legend: {
                                display: false
                            },

                            tooltip: {
                                backgroundColor: '#0f172a',
                                titleColor: '#ffffff',
                                bodyColor: '#e2e8f0',
                                padding: 12,
                                cornerRadius: 10,
                                displayColors: true,
                                callbacks: {
                                    label: function(context) {
                                        const label =
                                            context.dataset.label ?? '';

                                        const unit =
                                            label === 'BPM' ?
                                            ' BPM' :
                                            ' mmHg';

                                        return (
                                            label +
                                            ': ' +
                                            context.parsed.y +
                                            unit
                                        );
                                    }
                                }
                            }
                        },

                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },

                                border: {
                                    display: false
                                },

                                ticks: {
                                    color: '#64748b',
                                    maxRotation: 45,
                                    minRotation: 0,
                                    autoSkip: true,
                                    maxTicksLimit: 8,
                                    font: {
                                        size: 10
                                    }
                                }
                            },

                            pressureAxis: {
                                type: 'linear',
                                position: 'left',

                                grid: {
                                    color: 'rgba(148, 163, 184, 0.16)'
                                },

                                border: {
                                    display: false
                                },

                                ticks: {
                                    color: '#64748b',
                                    font: {
                                        size: 10
                                    }
                                },

                                title: {
                                    display: true,
                                    text: 'Tekanan (mmHg)',
                                    color: '#475569',
                                    font: {
                                        size: 11,
                                        weight: '600'
                                    }
                                }
                            },

                            bpmAxis: {
                                type: 'linear',
                                position: 'right',

                                grid: {
                                    drawOnChartArea: false
                                },

                                border: {
                                    display: false
                                },

                                ticks: {
                                    color: '#64748b',
                                    font: {
                                        size: 10
                                    }
                                },

                                title: {
                                    display: true,
                                    text: 'Denyut Nadi (BPM)',
                                    color: '#475569',
                                    font: {
                                        size: 11,
                                        weight: '600'
                                    }
                                }
                            }
                        }
                    }
                }
            );
        </script>
    <?php endif; ?>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html> 