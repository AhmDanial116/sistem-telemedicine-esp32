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
        href="<?= base_url(
                    'assets/css/medical-ui.css'
                ) ?>"
        rel="stylesheet">

    <style>
        :root {
            --primary: #7c3aed;
            --primary-dark: #6d28d9;
            --blue: #2563eb;
            --teal: #0f766e;
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
                    #7c3aed,
                    #2563eb);
        }

        .summary-card {
            height: 100%;
            border: 0;
            border-radius: var(--card-radius);
            box-shadow:
                0 8px 25px rgba(15, 23, 42, 0.07);
        }

        .summary-icon {
            width: 52px;
            height: 52px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            font-size: 1.45rem;
        }

        .summary-value {
            margin-top: 16px;
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1;
        }

        .summary-label {
            margin-top: 8px;
            color: var(--muted);
            font-size: 0.88rem;
        }

        .summary-detail {
            margin-top: 5px;
            color: var(--muted);
            font-size: 0.76rem;
        }

        .icon-doctor {
            color: #2563eb;
            background: #dbeafe;
        }

        .icon-patient {
            color: #0f766e;
            background: #ccfbf1;
        }

        .icon-device {
            color: #7c3aed;
            background: #ede9fe;
        }

        .icon-assignment {
            color: #b45309;
            background: #fef3c7;
        }

        .section-nav {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 5px;
        }

        .section-nav .btn {
            white-space: nowrap;
            border-radius: 11px;
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

        .code-text {
            font-family: monospace;
            font-size: 0.86rem;
        }

        .entity-avatar {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 13px;
            color: #ffffff;
            font-weight: 800;
        }

        .doctor-avatar {
            background:
                linear-gradient(135deg,
                    #2563eb,
                    #60a5fa);
        }

        .patient-avatar {
            background:
                linear-gradient(135deg,
                    #0f766e,
                    #2dd4bf);
        }

        .status-active {
            color: #166534;
            background: #dcfce7;
        }

        .status-inactive {
            color: #991b1b;
            background: #fee2e2;
        }

        .status-maintenance {
            color: #92400e;
            background: #fef3c7;
        }

        .status-replaced {
            color: #475569;
            background: #e2e8f0;
        }

        .assignment-active {
            color: #166534;
            background: #dcfce7;
        }

        .assignment-ended {
            color: #475569;
            background: #e2e8f0;
        }

        .activity-item {
            padding: 14px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .activity-item:last-child {
            border-bottom: 0;
        }

        .activity-icon {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 12px;
            color: var(--primary);
            background: #ede9fe;
        }

        .empty-state {
            padding: 45px 20px;
            text-align: center;
            color: var(--muted);
        }

        .empty-state i {
            display: block;
            margin-bottom: 12px;
            color: #cbd5e1;
            font-size: 2.8rem;
        }

        @media (max-width: 767.98px) {
            .summary-value {
                font-size: 1.55rem;
            }

            .admin-information {
                display: none;
            }
        }

        /* =====================================================
           RESPONSIVE ADMIN DASHBOARD
           ===================================================== */

        html {
            scroll-padding-top: 92px;
        }

        body {
            overflow-x: hidden;
        }

        .admin-dashboard-navbar .container {
            min-width: 0;
        }

        .admin-dashboard-navbar .medical-brand,
        .admin-dashboard-navbar .ms-auto {
            min-width: 0;
        }

        .admin-dashboard-navbar .medical-user-info {
            max-width: 260px;
        }

        .admin-dashboard-navbar .medical-user-name,
        .admin-dashboard-navbar .medical-user-role {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-quick-access {
            display: flex;
            align-items: center;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 4px;
            scrollbar-width: thin;
            -webkit-overflow-scrolling: touch;
        }

        .admin-quick-access>* {
            flex: 0 0 auto;
        }

        .admin-table-scroll {
            width: 100%;
            overflow-x: auto;
            border-radius: 14px;
            -webkit-overflow-scrolling: touch;
        }

        .admin-table-scroll .table {
            min-width: 860px;
            margin-bottom: 0;
        }

        #devicesSection .admin-table-scroll .table {
            min-width: 1120px;
        }

        #assignmentsSection .admin-table-scroll .table {
            min-width: 920px;
        }

        .admin-section-header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
        }

        .admin-section-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }

        .admin-assign-panel {
            border: 1px solid #e2e8f0 !important;
            background: #ffffff !important;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
        }

        .admin-assign-submit {
            min-width: 190px;
        }

        .activity-item {
            min-width: 0;
        }

        .activity-item .flex-grow-1 {
            min-width: 0;
        }

        .activity-item .fw-semibold,
        .activity-item .text-muted {
            overflow-wrap: anywhere;
        }

        @media (max-width: 991.98px) {
            .medical-hero-title {
                font-size: clamp(1.75rem, 5vw, 2.35rem);
            }

            .medical-hero-description {
                max-width: 100%;
            }

            .admin-dashboard-main {
                padding-top: 28px !important;
                padding-bottom: 36px !important;
            }

            .admin-section-card .card-body,
            .admin-section-card .medical-card-body {
                padding: 22px !important;
            }
        }

        @media (max-width: 767.98px) {
            .admin-dashboard-navbar .container {
                gap: 10px;
            }

            .admin-dashboard-navbar .medical-brand-subtitle,
            .admin-dashboard-navbar .medical-user-info {
                display: none;
            }

            .admin-dashboard-navbar .medical-brand-text {
                font-size: 0.92rem;
            }

            .admin-dashboard-navbar .medical-brand-icon {
                width: 40px;
                height: 40px;
                border-radius: 12px;
            }

            .admin-dashboard-navbar .btn-medical-danger {
                width: 40px;
                height: 40px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0 !important;
                border-radius: 12px;
            }

            .admin-dashboard-navbar .btn-medical-danger i {
                margin-right: 0 !important;
            }

            .admin-dashboard-navbar .logout-label {
                display: none;
            }

            .medical-hero {
                border-radius: 20px;
            }

            .medical-hero>div {
                padding: 28px 22px !important;
            }

            .medical-hero-title {
                margin-bottom: 12px;
                font-size: 1.8rem;
                line-height: 1.15;
            }

            .medical-hero-description {
                font-size: 0.86rem;
                line-height: 1.65;
            }

            .medical-hero .badge {
                max-width: 100%;
                white-space: normal;
                text-align: left;
            }

            .medical-stat-card {
                padding: 18px !important;
            }

            .medical-stat-value {
                font-size: 1.65rem;
            }

            .medical-stat-label {
                font-size: 0.82rem;
            }

            .medical-stat-detail {
                font-size: 0.7rem;
            }

            .admin-quick-access-label {
                position: sticky;
                left: 0;
                z-index: 2;
                padding-right: 10px;
                background: #ffffff;
            }

            .admin-section-header {
                align-items: flex-start;
            }

            .admin-section-actions {
                width: 100%;
                justify-content: flex-start;
            }

            .admin-section-actions .btn {
                flex: 1 1 auto;
            }

            .admin-assign-submit {
                width: 100%;
                min-width: 0;
            }

            .admin-section-card .card-body,
            .admin-section-card .medical-card-body {
                padding: 18px !important;
            }

            .modal-dialog {
                margin: 12px;
            }

            .modal-content {
                border-radius: 18px !important;
            }

            .modal-footer {
                flex-wrap: nowrap;
            }

            .modal-footer .btn {
                flex: 1 1 0;
            }
        }

        @media (max-width: 575.98px) {
            .admin-dashboard-main {
                width: 100%;
                padding-right: 14px;
                padding-left: 14px;
            }

            .admin-summary-column {
                width: 100%;
            }

            .medical-stat-card {
                min-height: 150px;
            }

            .medical-stat-top {
                gap: 8px;
            }

            .medical-stat-icon {
                width: 42px;
                height: 42px;
                border-radius: 13px;
            }

            .medical-badge {
                max-width: 56%;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .admin-quick-access {
                margin-right: -18px;
                padding-right: 18px;
            }

            .admin-quick-access .btn {
                min-height: 38px;
            }

            .admin-section-title {
                font-size: 1rem;
            }

            .admin-section-description {
                font-size: 0.73rem;
            }

            .admin-table-scroll {
                margin-right: -18px;
                width: calc(100% + 18px);
                border-radius: 12px 0 0 12px;
            }

            .activity-item {
                align-items: flex-start;
            }

            .activity-icon {
                width: 36px;
                height: 36px;
            }

            .alert {
                font-size: 0.78rem;
            }
        }

        @media (max-width: 390px) {
            .admin-dashboard-navbar .medical-brand-text {
                display: none;
            }

            .medical-hero-title {
                font-size: 1.55rem;
            }

            .medical-hero .d-flex.flex-wrap {
                display: grid !important;
                grid-template-columns: 1fr;
            }

            .medical-hero .badge {
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>
</head>

<body class="medical-app">

    <nav class="navbar medical-navbar admin-dashboard-navbar">
        <div class="container py-2">

            <a
                href="<?= site_url('admin/dashboard') ?>"
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
                        <?= esc($adminName ?? 'Administrator') ?>
                    </div>

                    <div class="medical-user-role">
                        <?= esc($adminCode ?? '-') ?>
                        · Administrator Sistem
                    </div>
                </div>

                <a
                    href="<?= site_url('logout') ?>"
                    class="btn btn-medical-danger btn-sm px-3">
                    <i class="bi bi-box-arrow-right me-1"></i>
                    <span class="logout-label">Keluar</span>
                </a>

            </div>
        </div>
    </nav>

    <main class="container py-4 py-lg-5 admin-dashboard-main">

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
                    session()->getFlashdata('success')
                ) ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"></button>
            </div>
        <?php endif; ?>

        <!-- HEADER -->
        <section class="medical-hero mb-4">
            <div class="p-4 p-lg-5">

                <div class="row align-items-center g-4">

                    <div class="col">
                        <div class="medical-hero-content">

                            <div class="medical-hero-eyebrow">
                                Administrator Dashboard
                            </div>

                            <h1 class="medical-hero-title">
                                Selamat datang,
                                <?= esc($adminName ?? 'Administrator') ?>
                            </h1>

                            <p class="medical-hero-description">
                                Kelola data dokter, pasien, perangkat ESP32,
                                serta penugasan layanan kesehatan dalam satu
                                sistem monitoring terintegrasi.
                            </p>

                            <div class="d-flex flex-wrap gap-2 mt-4">

                                <span class="badge rounded-pill bg-white bg-opacity-10 border border-white border-opacity-25 px-3 py-2">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <?= date('d F Y') ?>
                                </span>

                                <span class="badge rounded-pill bg-white bg-opacity-10 border border-white border-opacity-25 px-3 py-2">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Sistem Aktif
                                </span>

                            </div>

                        </div>
                    </div>

                    <div class="col-auto d-none d-lg-block">
                        <div class="medical-hero-illustration">
                            <i class="bi bi-hospital"></i>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- RINGKASAN -->
        <section class="row g-3 mb-4">

            <!-- DOKTER -->
            <div class="col-6 col-xl-3 admin-summary-column">
                <div class="medical-card medical-card-hover medical-stat-card">

                    <div class="medical-stat-top">
                        <div class="medical-stat-icon blue">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>

                        <span class="medical-badge medical-badge-info">
                            Dokter
                        </span>
                    </div>

                    <div class="medical-stat-value">
                        <?= (int) ($summary['total_doctors'] ?? 0) ?>
                    </div>

                    <div class="medical-stat-label">
                        Dokter Terdaftar
                    </div>

                    <div class="medical-stat-detail">
                        <strong class="text-success">
                            <?= (int) ($summary['active_doctors'] ?? 0) ?>
                        </strong>
                        dokter sedang aktif
                    </div>

                </div>
            </div>

            <!-- PASIEN -->
            <div class="col-6 col-xl-3 admin-summary-column">
                <div class="medical-card medical-card-hover medical-stat-card">

                    <div class="medical-stat-top">
                        <div class="medical-stat-icon primary">
                            <i class="bi bi-people-fill"></i>
                        </div>

                        <span class="medical-badge medical-badge-success">
                            Pasien
                        </span>
                    </div>

                    <div class="medical-stat-value">
                        <?= (int) ($summary['total_patients'] ?? 0) ?>
                    </div>

                    <div class="medical-stat-label">
                        Pasien Terdaftar
                    </div>

                    <div class="medical-stat-detail">
                        <strong class="text-success">
                            <?= (int) ($summary['active_patients'] ?? 0) ?>
                        </strong>
                        pasien sedang aktif
                    </div>

                </div>
            </div>

            <!-- PERANGKAT -->
            <div class="col-6 col-xl-3 admin-summary-column">
                <div class="medical-card medical-card-hover medical-stat-card">

                    <div class="medical-stat-top">
                        <div class="medical-stat-icon warning">
                            <i class="bi bi-cpu-fill"></i>
                        </div>

                        <span class="medical-badge medical-badge-warning">
                            ESP32
                        </span>
                    </div>

                    <div class="medical-stat-value">
                        <?= (int) ($summary['total_devices'] ?? 0) ?>
                    </div>

                    <div class="medical-stat-label">
                        Perangkat Terdaftar
                    </div>

                    <div class="medical-stat-detail">
                        <strong class="text-success">
                            <?= (int) ($summary['online_devices'] ?? 0) ?>
                            online
                        </strong>

                        <span class="mx-1">·</span>

                        <strong class="text-danger">
                            <?= (int) ($summary['offline_devices'] ?? 0) ?>
                            offline
                        </strong>
                    </div>

                </div>
            </div>

            <!-- PENUGASAN -->
            <div class="col-6 col-xl-3 admin-summary-column">
                <div class="medical-card medical-card-hover medical-stat-card">

                    <div class="medical-stat-top">
                        <div class="medical-stat-icon success">
                            <i class="bi bi-diagram-3-fill"></i>
                        </div>

                        <span class="medical-badge medical-badge-success">
                            Aktif
                        </span>
                    </div>

                    <div class="medical-stat-value">
                        <?= (int) ($summary['active_assignments'] ?? 0) ?>
                    </div>

                    <div class="medical-stat-label">
                        Penugasan Aktif
                    </div>

                    <div class="medical-stat-detail">
                        Relasi aktif antara dokter dan pasien
                    </div>

                </div>
            </div>

        </section>

        <!-- NAVIGASI SECTION -->
        <section class="medical-card mb-4">
            <div class="medical-card-body py-3">

                <div class="admin-quick-access">

                    <span class="small fw-bold text-muted me-2 admin-quick-access-label">
                        AKSES CEPAT
                    </span>

                    <a
                        href="#doctorsSection"
                        class="btn btn-medical-soft btn-sm px-3">
                        <i class="bi bi-person-badge me-1"></i>
                        Dokter
                    </a>

                    <a
                        href="#patientsSection"
                        class="btn btn-medical-soft btn-sm px-3">
                        <i class="bi bi-people me-1"></i>
                        Pasien
                    </a>

                    <a
                        href="#devicesSection"
                        class="btn btn-medical-soft btn-sm px-3">
                        <i class="bi bi-cpu me-1"></i>
                        Perangkat
                    </a>

                    <a
                        href="#assignmentsSection"
                        class="btn btn-medical-soft btn-sm px-3">
                        <i class="bi bi-diagram-3 me-1"></i>
                        Penugasan
                    </a>

                    <a
                        href="#activitySection"
                        class="btn btn-medical-soft btn-sm px-3">
                        <i class="bi bi-clock-history me-1"></i>
                        Aktivitas
                    </a>

                </div>
            </div>
        </section>

        <?php if (
            session()->getFlashdata(
                'assignment_success'
            )
        ): ?>
            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert">
                <?= esc(
                    session()->getFlashdata(
                        'assignment_success'
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
                'assignment_error'
            )
        ): ?>
            <div class="alert alert-danger">
                <?= esc(
                    session()->getFlashdata(
                        'assignment_error'
                    )
                ) ?>
            </div>
        <?php endif; ?>

        <div class="border rounded-4 p-3 p-md-4 mb-4 bg-light admin-assign-panel">
            <h3 class="h6 fw-bold mb-1">
                Assign atau Transfer Pasien
            </h3>

            <div class="text-muted small mb-3">
                Pilih pasien dan dokter tujuan. Jika pasien sudah
                memiliki dokter aktif, sistem akan melakukan transfer.
            </div>

            <form
                action="<?= site_url(
                            'admin/assignment/save'
                        ) ?>"
                method="post">
                <?= csrf_field() ?>

                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label
                            for="assignmentPatient"
                            class="form-label fw-semibold">
                            Pasien
                        </label>

                        <select
                            class="form-select"
                            id="assignmentPatient"
                            name="patient_id"
                            required>
                            <option value="">
                                Pilih pasien
                            </option>

                            <?php foreach ($patients as $patient): ?>
                                <?php if (
                                    $patient['status'] === 'active'
                                ): ?>
                                    <option
                                        value="<?= esc(
                                                    $patient['id']
                                                ) ?>"
                                        <?= old('patient_id')
                                            == $patient['id']
                                            ? 'selected'
                                            : ''
                                        ?>>
                                        <?= esc(
                                            $patient['patient_code']
                                        ) ?>
                                        —
                                        <?= esc(
                                            $patient['name']
                                        ) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label
                            for="assignmentDoctor"
                            class="form-label fw-semibold">
                            Dokter Tujuan
                        </label>

                        <select
                            class="form-select"
                            id="assignmentDoctor"
                            name="doctor_id"
                            required>
                            <option value="">
                                Pilih dokter
                            </option>

                            <?php foreach ($doctors as $doctor): ?>
                                <?php if (
                                    $doctor['status'] === 'active'
                                ): ?>
                                    <option
                                        value="<?= esc(
                                                    $doctor['id']
                                                ) ?>"
                                        <?= old('doctor_id')
                                            == $doctor['id']
                                            ? 'selected'
                                            : ''
                                        ?>>
                                        <?= esc(
                                            $doctor['doctor_code']
                                        ) ?>
                                        —
                                        <?= esc(
                                            $doctor['name']
                                        ) ?>
                                        <?php if (
                                            ! empty($doctor['specialization'])
                                        ): ?>
                                            —
                                            <?= esc(
                                                $doctor['specialization']
                                            ) ?>
                                        <?php endif; ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-4">
                        <label
                            for="transferReason"
                            class="form-label fw-semibold">
                            Alasan Transfer
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="transferReason"
                            name="transfer_reason"
                            value="<?= esc(
                                        old('transfer_reason')
                                    ) ?>"
                            maxlength="255"
                            placeholder="Wajib jika pasien dipindahkan">
                    </div>

                    <div class="col-12 text-end">
                        <button
                            type="submit"
                            class="btn btn-medical-primary px-4 admin-assign-submit">
                            <i class="bi bi-diagram-3 me-1"></i>
                            Simpan Penugasan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- DOKTER -->
        <section
            id="doctorsSection"
            class="card dashboard-card mb-4 admin-section-card">
            <?php if (
                session()->getFlashdata('doctor_success')
            ): ?>
                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert">
                    <?= esc(
                        session()->getFlashdata('doctor_success')
                    ) ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"></button>
                </div>
            <?php endif; ?>

            <?php if (
                session()->getFlashdata('doctor_error')
            ): ?>
                <div class="alert alert-danger">
                    <?= esc(
                        session()->getFlashdata('doctor_error')
                    ) ?>
                </div>
            <?php endif; ?>
            <div class="card-body p-4">
                <div class="admin-section-header mb-4">
                    <div>
                        <h2 class="h5 fw-bold mb-1 admin-section-title">
                            Data Dokter
                        </h2>

                        <div class="text-muted small admin-section-description">
                            Daftar seluruh dokter yang terdaftar.
                        </div>
                    </div>

                    <div class="admin-section-actions">
                        <button
                            type="button"
                            class="btn btn-medical-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#createDoctorModal">
                            <i class="bi bi-person-plus me-1"></i>
                            Tambah Dokter
                        </button>

                        <span class="badge rounded-pill text-bg-light border">
                            <?= count($doctors) ?> dokter
                        </span>
                    </div>
                </div>

                <?php if ($doctors === []): ?>
                    <div class="empty-state">
                        <i class="bi bi-person-x"></i>
                        Belum ada data dokter.
                    </div>
                <?php else: ?>
                    <div class="table-responsive admin-table-scroll">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Dokter</th>
                                    <th>Spesialisasi</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Lokasi Praktik</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($doctors as $doctor): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="entity-avatar doctor-avatar">
                                                    <?= esc(
                                                        strtoupper(
                                                            substr(
                                                                $doctor['name'],
                                                                0,
                                                                1
                                                            )
                                                        )
                                                    ) ?>
                                                </div>

                                                <div>
                                                    <div class="fw-semibold">
                                                        <?= esc(
                                                            $doctor['name']
                                                        ) ?>
                                                    </div>

                                                    <small class="text-muted code-text">
                                                        <?= esc(
                                                            $doctor['doctor_code']
                                                        ) ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <?= esc(
                                                $doctor['specialization'] ?? '-'
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= esc(
                                                $doctor['phone'] ?? '-'
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= esc(
                                                $doctor['email'] ?? '-'
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= esc(
                                                $doctor['practice_location'] ?? '-'
                                            ) ?>
                                        </td>

                                        <td>
                                            <span
                                                class="badge rounded-pill
                                        <?= $doctor['status'] === 'active'
                                            ? 'status-active'
                                            : 'status-inactive'
                                        ?>">
                                                <?= $doctor['status'] === 'active'
                                                    ? 'Aktif'
                                                    : 'Tidak Aktif'
                                                ?>
                                            </span>
                                        </td>

                                        <td class="text-end">
                                            <div class="d-inline-flex flex-wrap justify-content-end gap-1">
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-primary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editDoctorModal<?= (int) $doctor['id'] ?>">
                                                    <i class="bi bi-pencil me-1"></i>
                                                    Edit
                                                </button>

                                                <form
                                                    action="<?= site_url(
                                                                'admin/doctor/'
                                                                    . $doctor['id']
                                                                    . '/toggle-status'
                                                            ) ?>"
                                                    method="post"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin mengubah status dokter ini?');">
                                                    <?= csrf_field() ?>

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm <?= $doctor['status'] === 'active'
                                                                                ? 'btn-outline-danger'
                                                                                : 'btn-outline-success'
                                                                            ?>">
                                                        <i class="bi <?= $doctor['status'] === 'active'
                                                                            ? 'bi-person-x'
                                                                            : 'bi-person-check'
                                                                        ?> me-1"></i>

                                                        <?= $doctor['status'] === 'active'
                                                            ? 'Nonaktifkan'
                                                            : 'Aktifkan'
                                                        ?>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- PASIEN -->
        <section
            id="patientsSection"
            class="card dashboard-card mb-4 admin-section-card">
            <?php if (session()->getFlashdata('patient_success')): ?>
                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert">

                    <?= esc(
                        session()->getFlashdata('patient_success')
                    ) ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('patient_error')): ?>
                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert">

                    <?= esc(
                        session()->getFlashdata('patient_error')
                    ) ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Tutup"></button>
                </div>
            <?php endif; ?>
            <div class="card-body p-4">
                <div class="admin-section-header mb-4">
                    <div>
                        <h2 class="h5 fw-bold mb-1 admin-section-title">
                            Data Pasien
                        </h2>

                        <div class="text-muted small admin-section-description">
                            Daftar seluruh pasien yang terdaftar.
                        </div>
                    </div>

                    <div class="admin-section-actions">
                        <button
                            type="button"
                            class="btn btn-medical-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#createPatientModal">
                            <i class="bi bi-person-plus me-1"></i>
                            Tambah Pasien
                        </button>

                        <span class="badge rounded-pill text-bg-light border">
                            <?= count($patients) ?> pasien
                        </span>
                    </div>
                </div>

                <?php if ($patients === []): ?>
                    <div class="empty-state">
                        <i class="bi bi-person-x"></i>
                        Belum ada data pasien.
                    </div>
                <?php else: ?>
                    <div class="table-responsive admin-table-scroll">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Pasien</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Telepon</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($patients as $patient): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="entity-avatar patient-avatar">
                                                    <?= esc(
                                                        strtoupper(
                                                            substr(
                                                                $patient['name'],
                                                                0,
                                                                1
                                                            )
                                                        )
                                                    ) ?>
                                                </div>

                                                <div>
                                                    <div class="fw-semibold">
                                                        <?= esc(
                                                            $patient['name']
                                                        ) ?>
                                                    </div>

                                                    <small class="text-muted code-text">
                                                        <?= esc(
                                                            $patient['patient_code']
                                                        ) ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <?= esc(
                                                $patient['birth_date'] ?? '-'
                                            ) ?>
                                        </td>

                                        <td>
                                            <?php
                                            $genderLabel = match ($patient['gender'] ?? null) {
                                                'male' => 'Laki-laki',
                                                'female' => 'Perempuan',
                                                default => '-',
                                            };
                                            ?>

                                            <?= esc($genderLabel) ?>
                                        </td>

                                        <td>
                                            <?= esc(
                                                $patient['phone'] ?? '-'
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= esc(
                                                $patient['address'] ?? '-'
                                            ) ?>
                                        </td>

                                        <td>
                                            <span
                                                class="badge rounded-pill
                                        <?= $patient['status'] === 'active'
                                            ? 'status-active'
                                            : 'status-inactive'
                                        ?>">
                                                <?= $patient['status'] === 'active'
                                                    ? 'Aktif'
                                                    : 'Tidak Aktif'
                                                ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- PERANGKAT -->
        <section
            id="devicesSection"
            class="card dashboard-card mb-4 admin-section-card">
            <div class="card-body p-4">
                <div class="admin-section-header mb-4">
                    <div>
                        <h2 class="h5 fw-bold mb-1 admin-section-title">
                            Data Perangkat
                        </h2>

                        <div class="text-muted small admin-section-description">
                            Perangkat ESP32 yang terdaftar pada sistem.
                        </div>
                    </div>

                    <span class="badge rounded-pill text-bg-light border">
                        <?= count($devices) ?> perangkat
                    </span>
                </div>

                <?php if ($devices === []): ?>
                    <div class="empty-state">
                        <i class="bi bi-cpu"></i>
                        Belum ada perangkat.
                    </div>
                <?php else: ?>
                    <div class="table-responsive admin-table-scroll">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Device ID</th>
                                    <th>Nama Perangkat</th>
                                    <th>Pasien</th>
                                    <th>Firmware</th>
                                    <th>IP Terakhir</th>
                                    <th>Terakhir Terhubung</th>
                                    <th>Koneksi</th>
                                    <th>Status Sistem</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($devices as $device): ?>
                                    <?php
                                    $deviceStatus =
                                        $device['status']
                                        ?? 'inactive';

                                    $deviceClass = match ($deviceStatus) {
                                        'active' =>
                                        'status-active',

                                        'maintenance' =>
                                        'status-maintenance',

                                        'replaced' =>
                                        'status-replaced',

                                        default =>
                                        'status-inactive',
                                    };

                                    $deviceLabel = match ($deviceStatus) {
                                        'active' => 'Aktif',
                                        'maintenance' => 'Perawatan',
                                        'replaced' => 'Diganti',
                                        default => 'Tidak Aktif',
                                    };

                                    $connectionStatus =
                                        $device['connection_status']
                                        ?? 'never_connected';

                                    $connectionClass =
                                        match ($connectionStatus) {
                                            'online' =>
                                            'text-bg-success',

                                            'offline' =>
                                            'text-bg-danger',

                                            default =>
                                            'text-bg-secondary',
                                        };

                                    $connectionLabel =
                                        match ($connectionStatus) {
                                            'online' =>
                                            'Online',

                                            'offline' =>
                                            'Offline',

                                            default =>
                                            'Belum Terhubung',
                                        };
                                    ?>

                                    <tr>
                                        <td class="code-text fw-semibold">
                                            <?= esc(
                                                $device['device_id']
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= esc(
                                                $device['device_name'] ?? '-'
                                            ) ?>
                                        </td>

                                        <td>
                                            <?php if (
                                                ! empty($device['patient_name'])
                                            ): ?>
                                                <div class="fw-semibold">
                                                    <?= esc(
                                                        $device['patient_name']
                                                    ) ?>
                                                </div>

                                                <small class="text-muted">
                                                    <?= esc(
                                                        $device['patient_code']
                                                    ) ?>
                                                </small>
                                            <?php else: ?>

                                                <div class="d-flex flex-column gap-1">

                                                    <span class="badge bg-warning text-dark">
                                                        BARU
                                                    </span>

                                                    <span class="fw-semibold text-warning">
                                                        Belum diassign
                                                    </span>

                                                </div>

                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?= esc(
                                                $device['firmware_version'] ?? '-'
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= esc(
                                                $device['last_ip_address'] ?? '-'
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= esc(
                                                $device['last_seen_at']
                                                    ?? '-'
                                            ) ?>

                                            <?php if (
                                                $device['seconds_since_seen'] !== null
                                            ): ?>
                                                <div class="text-muted small admin-section-description">
                                                    <?php
                                                    $seconds =
                                                        (int) $device['seconds_since_seen'];

                                                    if ($seconds < 60) {
                                                        echo esc(
                                                            $seconds
                                                                . ' detik lalu'
                                                        );
                                                    } elseif ($seconds < 3600) {
                                                        echo esc(
                                                            floor($seconds / 60)
                                                                . ' menit lalu'
                                                        );
                                                    } else {
                                                        echo esc(
                                                            floor($seconds / 3600)
                                                                . ' jam lalu'
                                                        );
                                                    }
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <span
                                                class="badge rounded-pill <?= esc(
                                                                                $connectionClass
                                                                            ) ?>">
                                                <?php if (
                                                    $connectionStatus === 'online'
                                                ): ?>
                                                    <i class="bi bi-wifi me-1"></i>
                                                <?php elseif (
                                                    $connectionStatus === 'offline'
                                                ): ?>
                                                    <i class="bi bi-wifi-off me-1"></i>
                                                <?php else: ?>
                                                    <i class="bi bi-question-circle me-1"></i>
                                                <?php endif; ?>

                                                <?= esc($connectionLabel) ?>
                                            </span>
                                        </td>

                                        <td>
                                            <span
                                                class="badge rounded-pill <?= esc(
                                                                                $deviceClass
                                                                            ) ?>">
                                                <?= esc($deviceLabel) ?>
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <?php if (empty($device['patient_id'])): ?>
                                                <a
                                                    href="<?= site_url(
                                                                'admin/device/'
                                                                    . $device['id']
                                                                    . '/assign'
                                                            ) ?>"
                                                    class="btn btn-medical-primary btn-sm">
                                                    <i class="bi bi-person-plus me-1"></i>
                                                    Assign
                                                </a>
                                            <?php else: ?>
                                                <span class="badge rounded-pill text-bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Assigned
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

        <!-- PENUGASAN -->
        <section
            id="assignmentsSection"
            class="card dashboard-card mb-4 admin-section-card">
            <div class="card-body p-4">
                <div class="admin-section-header mb-4">
                    <div>
                        <h2 class="h5 fw-bold mb-1 admin-section-title">
                            Penugasan Dokter dan Pasien
                        </h2>

                        <div class="text-muted small admin-section-description">
                            Riwayat dan status penugasan dokter.
                        </div>
                    </div>

                    <span class="badge rounded-pill text-bg-light border">
                        <?= count($assignments) ?> penugasan
                    </span>
                </div>

                <?php if ($assignments === []): ?>
                    <div class="empty-state">
                        <i class="bi bi-diagram-3"></i>
                        Belum ada penugasan.
                    </div>
                <?php else: ?>
                    <div class="table-responsive admin-table-scroll">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Dokter</th>
                                    <th>Pasien</th>
                                    <th>Ditugaskan</th>
                                    <th>Berakhir</th>
                                    <th>Status</th>
                                    <th>Alasan Transfer</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach (
                                    $assignments as $assignment
                                ): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">
                                                <?= esc(
                                                    $assignment['doctor_name']
                                                ) ?>
                                            </div>

                                            <small class="text-muted">
                                                <?= esc(
                                                    $assignment['doctor_code']
                                                ) ?>
                                                <?php if (
                                                    ! empty($assignment['specialization'])
                                                ): ?>
                                                    ·
                                                    <?= esc(
                                                        $assignment['specialization']
                                                    ) ?>
                                                <?php endif; ?>
                                            </small>
                                        </td>

                                        <td>
                                            <div class="fw-semibold">
                                                <?= esc(
                                                    $assignment['patient_name']
                                                ) ?>
                                            </div>

                                            <small class="text-muted">
                                                <?= esc(
                                                    $assignment['patient_code']
                                                ) ?>
                                            </small>
                                        </td>

                                        <td>
                                            <?= esc(
                                                $assignment['assigned_at']
                                            ) ?>
                                        </td>

                                        <td>
                                            <?= esc(
                                                $assignment['ended_at'] ?? '-'
                                            ) ?>
                                        </td>

                                        <td>
                                            <span
                                                class="badge rounded-pill
                                        <?= $assignment['status'] === 'active'
                                            ? 'assignment-active'
                                            : 'assignment-ended'
                                        ?>">
                                                <?= $assignment['status'] === 'active'
                                                    ? 'Aktif'
                                                    : 'Berakhir'
                                                ?>
                                            </span>
                                        </td>

                                        <td>
                                            <?= esc(
                                                $assignment['transfer_reason'] ?? '-'
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

        <!-- ACTIVITY LOG -->
        <section
            id="activitySection"
            class="card dashboard-card admin-section-card">
            <div class="card-body p-4">
                <div class="admin-section-header mb-3">
                    <div>
                        <h2 class="h5 fw-bold mb-1 admin-section-title">
                            Aktivitas Terbaru
                        </h2>

                        <div class="text-muted small admin-section-description">
                            Maksimal 20 aktivitas sistem terbaru.
                        </div>
                    </div>

                    <span class="badge rounded-pill text-bg-light border">
                        <?= count($recentActivities) ?> aktivitas
                    </span>
                </div>

                <?php if ($recentActivities === []): ?>
                    <div class="empty-state">
                        <i class="bi bi-clock-history"></i>
                        Belum ada aktivitas tercatat.
                    </div>
                <?php else: ?>
                    <?php foreach (
                        $recentActivities as $activity
                    ): ?>
                        <div class="activity-item d-flex gap-3">
                            <div class="activity-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>

                            <div class="flex-grow-1">
                                <div class="fw-semibold">
                                    <?= esc(
                                        $activity['action']
                                    ) ?>
                                </div>

                                <div class="text-muted small admin-section-description">
                                    <?= esc(
                                        $activity['description'] ?? '-'
                                    ) ?>
                                </div>

                                <div class="text-muted small mt-1">
                                    <?= esc(
                                        ucfirst(
                                            $activity['actor_role']
                                        )
                                    ) ?>
                                    ·
                                    <?= esc(
                                        $activity['created_at']
                                    ) ?>

                                    <?php if (
                                        ! empty($activity['ip_address'])
                                    ): ?>
                                        · IP
                                        <?= esc(
                                            $activity['ip_address']
                                        ) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- MODAL TAMBAH PASIEN -->
        <div
            class="modal fade"
            id="createPatientModal"
            tabindex="-1"
            aria-labelledby="createPatientModalLabel"
            aria-hidden="true">

            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 rounded-4">

                    <form
                        action="<?= site_url('admin/patient/store') ?>"
                        method="post">

                        <?= csrf_field() ?>

                        <div class="modal-header border-0">
                            <h2
                                class="modal-title fs-5 fw-bold"
                                id="createPatientModalLabel">
                                Tambah Pasien
                            </h2>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>

                        <div class="modal-body pt-0">
                            <div class="row g-3">

                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold">
                                        Kode Pasien
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control text-uppercase"
                                        name="patient_code"
                                        maxlength="30"
                                        placeholder="PAS002"
                                        required>
                                </div>

                                <div class="col-12 col-md-8">
                                    <label class="form-label fw-semibold">
                                        Nama Pasien
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        name="name"
                                        maxlength="100"
                                        placeholder="Nama lengkap pasien"
                                        required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">
                                        Tanggal Lahir
                                    </label>

                                    <input
                                        type="date"
                                        class="form-control"
                                        name="birth_date">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">
                                        Jenis Kelamin
                                    </label>

                                    <select
                                        class="form-select"
                                        name="gender">

                                        <option value="">
                                            Pilih jenis kelamin
                                        </option>

                                        <option value="male">
                                            Laki-laki
                                        </option>

                                        <option value="female">
                                            Perempuan
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        Nomor Telepon
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        name="phone"
                                        maxlength="20"
                                        placeholder="08xxxxxxxxxx">
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        Alamat
                                    </label>

                                    <textarea
                                        class="form-control"
                                        name="address"
                                        rows="3"
                                        placeholder="Alamat pasien"></textarea>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer border-0">
                            <button
                                type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal">
                                Batal
                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>
                                Simpan Pasien
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- MODAL TAMBAH DOKTER -->
        <div
            class="modal fade"
            id="createDoctorModal"
            tabindex="-1"
            aria-labelledby="createDoctorModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 rounded-4">
                    <form
                        action="<?= site_url('admin/doctor/store') ?>"
                        method="post">
                        <?= csrf_field() ?>

                        <div class="modal-header border-0">
                            <h2
                                class="modal-title fs-5 fw-bold"
                                id="createDoctorModalLabel">
                                Tambah Dokter
                            </h2>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>

                        <div class="modal-body pt-0">
                            <div class="row g-3">
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-semibold">
                                        Kode Dokter
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control text-uppercase"
                                        name="doctor_code"
                                        maxlength="30"
                                        placeholder="DOK003"
                                        required>
                                </div>

                                <div class="col-12 col-md-8">
                                    <label class="form-label fw-semibold">
                                        Nama Dokter
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        name="name"
                                        maxlength="100"
                                        placeholder="dr. Nama Dokter"
                                        required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">
                                        Spesialisasi
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        name="specialization"
                                        maxlength="100"
                                        placeholder="Dokter Umum">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">
                                        Nomor Telepon
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        name="phone"
                                        maxlength="20">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">
                                        Email
                                    </label>

                                    <input
                                        type="email"
                                        class="form-control"
                                        name="email"
                                        maxlength="100">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">
                                        Lokasi Praktik
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        name="practice_location"
                                        maxlength="150">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer border-0">
                            <button
                                type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal">
                                Batal
                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>
                                Simpan Dokter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- MODAL EDIT DOKTER -->
        <?php foreach ($doctors as $doctor): ?>
            <div
                class="modal fade"
                id="editDoctorModal<?= (int) $doctor['id'] ?>"
                tabindex="-1"
                aria-labelledby="editDoctorModalLabel<?= (int) $doctor['id'] ?>"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4">
                        <form
                            action="<?= site_url(
                                        'admin/doctor/'
                                            . $doctor['id']
                                            . '/update'
                                    ) ?>"
                            method="post">
                            <?= csrf_field() ?>

                            <div class="modal-header border-0">
                                <h2
                                    class="modal-title fs-5 fw-bold"
                                    id="editDoctorModalLabel<?= (int) $doctor['id'] ?>">
                                    Edit Dokter
                                </h2>

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>

                            <div class="modal-body pt-0">
                                <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label fw-semibold">
                                            Kode Dokter
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control text-uppercase"
                                            name="doctor_code"
                                            value="<?= esc($doctor['doctor_code']) ?>"
                                            maxlength="30"
                                            required>
                                    </div>

                                    <div class="col-12 col-md-8">
                                        <label class="form-label fw-semibold">
                                            Nama Dokter
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            name="name"
                                            value="<?= esc($doctor['name']) ?>"
                                            maxlength="100"
                                            required>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">
                                            Spesialisasi
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            name="specialization"
                                            value="<?= esc($doctor['specialization'] ?? '') ?>"
                                            maxlength="100">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">
                                            Nomor Telepon
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            name="phone"
                                            value="<?= esc($doctor['phone'] ?? '') ?>"
                                            maxlength="20">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">
                                            Email
                                        </label>

                                        <input
                                            type="email"
                                            class="form-control"
                                            name="email"
                                            value="<?= esc($doctor['email'] ?? '') ?>"
                                            maxlength="100">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">
                                            Lokasi Praktik
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            name="practice_location"
                                            value="<?= esc($doctor['practice_location'] ?? '') ?>"
                                            maxlength="150">
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer border-0">
                                <button
                                    type="button"
                                    class="btn btn-light"
                                    data-bs-dismiss="modal">
                                    Batal
                                </button>

                                <button
                                    type="submit"
                                    class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>