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

    <style>
        @import url(
            'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap'
        );

        :root {
            --primary: #0f766e;
            --primary-dark: #115e59;
            --secondary: #2563eb;
            --secondary-dark: #1d4ed8;

            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;

            --background: #f4f8fb;
            --surface: #ffffff;
            --surface-soft: #f8fafc;

            --text: #0f172a;
            --text-soft: #334155;
            --muted: #64748b;
            --border: #e2e8f0;

            --shadow-sm:
                0 4px 16px rgba(15, 23, 42, 0.06);

            --shadow-md:
                0 18px 45px rgba(15, 23, 42, 0.10);

            --shadow-lg:
                0 28px 70px rgba(15, 23, 42, 0.14);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
            margin: 0;
            color: var(--text);
            background: var(--background);
            font-family:
                'Inter',
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                'Segoe UI',
                sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .identification-page {
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            padding: 28px 0 0;
            background:
                radial-gradient(
                    circle at 8% 10%,
                    rgba(20, 184, 166, 0.14),
                    transparent 25%
                ),
                radial-gradient(
                    circle at 92% 15%,
                    rgba(37, 99, 235, 0.14),
                    transparent 28%
                ),
                linear-gradient(
                    180deg,
                    #f8fbff 0%,
                    #f1f7fa 100%
                );
        }

        .identification-page::before,
        .identification-page::after {
            position: absolute;
            z-index: 0;
            content: '';
            border-radius: 50%;
            pointer-events: none;
        }

        .identification-page::before {
            top: -160px;
            right: -140px;
            width: 520px;
            height: 520px;
            background:
                rgba(37, 99, 235, 0.06);
        }

        .identification-page::after {
            bottom: 90px;
            left: -180px;
            width: 430px;
            height: 430px;
            background:
                rgba(15, 118, 110, 0.06);
        }

        .page-content {
            position: relative;
            z-index: 1;
        }

        /* ==========================================
           TOP BRAND
           ========================================== */

        .top-brand {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 28px;
        }

        .brand-link {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            color: var(--text);
            text-decoration: none;
        }

        .brand-link:hover {
            color: var(--primary);
        }

        .brand-icon {
            width: 46px;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #ffffff;
            background:
                linear-gradient(
                    135deg,
                    var(--primary),
                    #14b8a6
                );
            border-radius: 15px;
            font-size: 1.25rem;
            box-shadow:
                0 10px 25px rgba(15, 118, 110, 0.22);
        }

        .brand-name {
            color: var(--text);
            font-size: 1rem;
            font-weight: 800;
            line-height: 1.1;
        }

        .brand-subtitle {
            display: block;
            margin-top: 3px;
            color: var(--muted);
            font-size: 0.67rem;
            font-weight: 500;
        }

        .version-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 12px;
            color: var(--primary);
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(15, 118, 110, 0.13);
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
            box-shadow: var(--shadow-sm);
        }

        /* ==========================================
           HERO
           ========================================== */

        .landing-hero {
            position: relative;
            overflow: hidden;
            margin-bottom: 32px;
            color: #ffffff;
            background:
                linear-gradient(
                    135deg,
                    #0f766e 0%,
                    #0d9488 48%,
                    #2563eb 100%
                );
            border-radius: 28px;
            box-shadow:
                0 25px 60px rgba(15, 118, 110, 0.22);
        }

        .landing-hero::before {
            position: absolute;
            top: -120px;
            right: -80px;
            width: 320px;
            height: 320px;
            content: '';
            background:
                rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .landing-hero::after {
            position: absolute;
            right: 180px;
            bottom: -180px;
            width: 300px;
            height: 300px;
            content: '';
            background:
                rgba(255, 255, 255, 0.06);
            border-radius: 50%;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            padding: 48px;
        }

        .hero-eyebrow {
            margin-bottom: 10px;
            opacity: 0.82;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.10em;
            text-transform: uppercase;
        }

        .hero-title {
            max-width: 720px;
            margin-bottom: 14px;
            font-size: clamp(2rem, 4vw, 3.4rem);
            font-weight: 800;
            line-height: 1.08;
            letter-spacing: -0.045em;
        }

        .hero-description {
            max-width: 680px;
            margin: 0;
            opacity: 0.84;
            font-size: 0.95rem;
            line-height: 1.75;
        }

        .hero-features {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin-top: 25px;
        }

        .hero-feature {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 12px;
            color: #ffffff;
            background:
                rgba(255, 255, 255, 0.12);
            border:
                1px solid rgba(255, 255, 255, 0.20);
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 650;
            backdrop-filter: blur(8px);
        }

        .hero-visual {
            position: relative;
            z-index: 1;
            min-width: 250px;
            text-align: center;
        }

        .hero-visual-main {
            width: 155px;
            height: 155px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.90);
            background:
                rgba(255, 255, 255, 0.10);
            border:
                1px solid rgba(255, 255, 255, 0.16);
            border-radius: 42px;
            font-size: 4.5rem;
            transform: rotate(4deg);
            backdrop-filter: blur(10px);
        }

        .hero-pulse {
            position: absolute;
            right: 22px;
            bottom: -12px;
            padding: 10px 14px;
            color: var(--primary);
            background: #ffffff;
            border-radius: 14px;
            box-shadow: var(--shadow-md);
            font-size: 0.72rem;
            font-weight: 800;
        }

        /* ==========================================
           SECTION TITLE
           ========================================== */

        .access-heading {
            margin-bottom: 22px;
            text-align: center;
        }

        .access-heading-label {
            color: var(--primary);
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.10em;
            text-transform: uppercase;
        }

        .access-heading-title {
            margin: 6px 0 7px;
            color: var(--text);
            font-size: 1.5rem;
            font-weight: 800;
        }

        .access-heading-description {
            margin: 0;
            color: var(--muted);
            font-size: 0.82rem;
        }

        /* ==========================================
           ROLE CARDS
           ========================================== */

        .role-card {
            position: relative;
            overflow: hidden;
            height: 100%;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(226, 232, 240, 0.78);
            border-radius: 24px;
            box-shadow: var(--shadow-md);
            transition:
                transform 180ms ease,
                box-shadow 180ms ease,
                border-color 180ms ease;
        }

        .role-card:hover {
            transform: translateY(-5px);
            border-color:
                rgba(20, 184, 166, 0.30);
            box-shadow: var(--shadow-lg);
        }

        .role-card::before {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            content: '';
        }

        .role-card.patient::before {
            background:
                linear-gradient(
                    90deg,
                    #2563eb,
                    #60a5fa
                );
        }

        .role-card.doctor::before {
            background:
                linear-gradient(
                    90deg,
                    #0f766e,
                    #2dd4bf
                );
        }

        .role-card-inner {
            padding: 26px;
        }

        .role-card-top {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .role-icon {
            width: 58px;
            height: 58px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #ffffff;
            border-radius: 17px;
            font-size: 1.55rem;
            box-shadow:
                0 10px 24px rgba(15, 23, 42, 0.12);
        }

        .patient-icon {
            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #60a5fa
                );
        }

        .doctor-icon {
            background:
                linear-gradient(
                    135deg,
                    #0f766e,
                    #2dd4bf
                );
        }

        .role-label {
            margin: 0;
            color: var(--text);
            font-size: 1.15rem;
            font-weight: 800;
        }

        .role-description {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 0.76rem;
            line-height: 1.5;
        }

        .role-feature-list {
            display: grid;
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
            gap: 9px;
            padding: 14px;
            margin-bottom: 20px;
            background: var(--surface-soft);
            border: 1px solid #eef2f7;
            border-radius: 15px;
        }

        .role-feature-item {
            display: flex;
            align-items: center;
            gap: 7px;
            color: var(--text-soft);
            font-size: 0.70rem;
            font-weight: 600;
        }

        .role-feature-item i {
            color: var(--primary);
        }

        .role-divider {
            height: 1px;
            margin: 18px 0;
            background: #eef2f7;
        }

        .form-label {
            margin-bottom: 7px;
            color: var(--text-soft);
            font-size: 0.77rem;
            font-weight: 700;
        }

        .input-group-text {
            color: var(--primary);
            background: #f0fdfa;
            border-color: var(--border);
            border-radius: 12px 0 0 12px;
        }

        .form-control {
            min-height: 48px;
            color: var(--text);
            border-color: var(--border);
            border-radius: 12px;
            font-size: 0.82rem;
        }

        .input-group .form-control {
            border-left: 0;
            border-radius: 0 12px 12px 0;
        }

        .form-control:focus {
            border-color: #14b8a6;
            box-shadow:
                0 0 0 4px rgba(20, 184, 166, 0.12);
        }

        .form-text {
            color: var(--muted);
            font-size: 0.67rem;
        }

        .btn-role {
            min-height: 49px;
            border: 0;
            border-radius: 12px;
            font-size: 0.80rem;
            font-weight: 750;
            transition:
                transform 180ms ease,
                box-shadow 180ms ease;
        }

        .btn-role:hover {
            transform: translateY(-2px);
        }

        .btn-patient {
            color: #ffffff;
            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #3b82f6
                );
            box-shadow:
                0 10px 24px rgba(37, 99, 235, 0.22);
        }

        .btn-patient:hover,
        .btn-patient:focus {
            color: #ffffff;
            background:
                linear-gradient(
                    135deg,
                    #1d4ed8,
                    #2563eb
                );
            box-shadow:
                0 14px 30px rgba(37, 99, 235, 0.28);
        }

        .btn-doctor {
            color: #ffffff;
            background:
                linear-gradient(
                    135deg,
                    #0f766e,
                    #14b8a6
                );
            box-shadow:
                0 10px 24px rgba(15, 118, 110, 0.22);
        }

        .btn-doctor:hover,
        .btn-doctor:focus {
            color: #ffffff;
            background:
                linear-gradient(
                    135deg,
                    #115e59,
                    #0f766e
                );
            box-shadow:
                0 14px 30px rgba(15, 118, 110, 0.28);
        }

        /* ==========================================
           ADMIN ACCESS
           ========================================== */

        .admin-access {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 17px 19px;
            margin-top: 24px;
            background:
                rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(226, 232, 240, 0.80);
            border-radius: 18px;
            box-shadow: var(--shadow-sm);
        }

        .admin-access-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .admin-access-icon {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #7c3aed;
            background: #f3e8ff;
            border-radius: 13px;
        }

        .admin-access-title {
            color: var(--text);
            font-size: 0.82rem;
            font-weight: 750;
        }

        .admin-access-description {
            margin-top: 2px;
            color: var(--muted);
            font-size: 0.68rem;
        }

        .btn-admin {
            color: #7c3aed;
            background: #f3e8ff;
            border: 1px solid transparent;
            border-radius: 11px;
            font-size: 0.76rem;
            font-weight: 700;
        }

        .btn-admin:hover {
            color: #ffffff;
            background: #7c3aed;
        }

        /* ==========================================
           ALERT & MODAL
           ========================================== */

        .alert {
            border: 0;
            border-radius: 14px;
            font-size: 0.78rem;
        }

        .modal-content {
            overflow: hidden;
            border: 0;
            border-radius: 22px;
            box-shadow: var(--shadow-lg);
        }

        .admin-modal-hero {
            padding: 26px;
            color: #ffffff;
            background:
                linear-gradient(
                    135deg,
                    #5b21b6,
                    #7c3aed
                );
        }

        .admin-modal-icon {
            width: 52px;
            height: 52px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            color: #7c3aed;
            background: #ffffff;
            border-radius: 16px;
            font-size: 1.3rem;
        }

        /* ==========================================
           FOOTER
           ========================================== */

        .system-footer {
            position: relative;
            z-index: 1;
            margin-top: 42px;
            padding: 24px 0 28px;
            color: var(--muted);
            border-top:
                1px solid rgba(203, 213, 225, 0.65);
            text-align: center;
        }

        .footer-title {
            color: var(--text-soft);
            font-size: 0.74rem;
            font-weight: 750;
        }

        .footer-meta {
            margin-top: 5px;
            font-size: 0.65rem;
        }

        /* ==========================================
           RESPONSIVE
           ========================================== */

        @media (max-width: 991.98px) {
            .hero-content {
                padding: 36px;
            }

            .hero-visual {
                display: none;
            }
        }

        @media (max-width: 767.98px) {
            .identification-page {
                padding-top: 18px;
            }

            .top-brand {
                margin-bottom: 18px;
            }

            .version-badge {
                display: none;
            }

            .landing-hero {
                border-radius: 22px;
            }

            .hero-content {
                padding: 28px 24px;
            }

            .hero-title {
                font-size: 2rem;
            }

            .role-card-inner {
                padding: 21px;
            }

            .role-feature-list {
                grid-template-columns: 1fr;
            }

            .admin-access {
                align-items: stretch;
            }

            .btn-admin {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<section class="identification-page">

    <div class="container page-content">

        <!-- BRAND -->
        <div class="top-brand">

            <a
                href="<?= site_url('/') ?>"
                class="brand-link"
            >
                <span class="brand-icon">
                    <i class="bi bi-heart-pulse-fill"></i>
                </span>

                <span>
                    <span class="brand-name">
                        Telemedicine
                    </span>

                    <span class="brand-subtitle">
                        IoT Health Monitoring System
                    </span>
                </span>
            </a>

            <span class="version-badge">
                <i class="bi bi-shield-check"></i>
                Sistem Monitoring Aktif
            </span>

        </div>

        <!-- HERO -->
        <section class="landing-hero">

            <div class="row align-items-center g-0">

                <div class="col">
                    <div class="hero-content">

                        <div class="hero-eyebrow">
                            Sistem Monitoring Kesehatan Berbasis IoT
                        </div>

                        <h1 class="hero-title">
                            Pantau tekanan darah dan denyut nadi
                            secara terintegrasi
                        </h1>

                        <p class="hero-description">
                            Sistem telemedicine berbasis ESP32 untuk
                            membantu pasien melakukan pemantauan mandiri
                            serta memudahkan dokter meninjau hasil
                            pengukuran, memberikan arahan, dan mencetak
                            laporan pemeriksaan.
                        </p>

                        <div class="hero-features">

                            <span class="hero-feature">
                                <i class="bi bi-wifi"></i>
                                Monitoring Real-time
                            </span>

                            <span class="hero-feature">
                                <i class="bi bi-cpu"></i>
                                Integrasi ESP32
                            </span>

                            <span class="hero-feature">
                                <i class="bi bi-graph-up-arrow"></i>
                                Grafik Riwayat
                            </span>

                            <span class="hero-feature">
                                <i class="bi bi-file-earmark-pdf"></i>
                                Laporan PDF
                            </span>

                        </div>

                    </div>
                </div>

                <div class="col-auto d-none d-lg-block">
                    <div class="hero-visual">

                        <div class="hero-visual-main">
                            <i class="bi bi-heart-pulse-fill"></i>
                        </div>

                        <div class="hero-pulse">
                            <i class="bi bi-activity me-1"></i>
                            Health Monitoring
                        </div>

                    </div>
                </div>

            </div>

        </section>

        <?php if (
            session()->getFlashdata('success')
        ): ?>
            <div
                class="alert alert-success alert-dismissible fade show"
                role="alert"
            >
                <?= esc(
                    session()->getFlashdata('success')
                ) ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Tutup"
                ></button>
            </div>
        <?php endif; ?>

        <!-- JUDUL AKSES -->
        <div class="access-heading">

            <div class="access-heading-label">
                Pilih Akses Sistem
            </div>

            <h2 class="access-heading-title">
                Masuk sesuai peran Anda
            </h2>

            <p class="access-heading-description">
                Gunakan kode identifikasi yang telah terdaftar pada sistem.
            </p>

        </div>

        <div class="row g-4 justify-content-center">

            <!-- PASIEN -->
            <div class="col-12 col-lg-6">

                <section class="role-card patient">

                    <div class="role-card-inner">

                        <div class="role-card-top">

                            <div class="role-icon patient-icon">
                                <i class="bi bi-person-heart"></i>
                            </div>

                            <div>
                                <h2 class="role-label">
                                    Akses Pasien
                                </h2>

                                <p class="role-description">
                                    Masuk menggunakan Device ID perangkat
                                    ESP32 yang telah dihubungkan ke akun pasien.
                                </p>
                            </div>

                        </div>

                        <div class="role-feature-list">

                            <div class="role-feature-item">
                                <i class="bi bi-check-circle-fill"></i>
                                Mulai pengukuran
                            </div>

                            <div class="role-feature-item">
                                <i class="bi bi-check-circle-fill"></i>
                                Hasil terbaru
                            </div>

                            <div class="role-feature-item">
                                <i class="bi bi-check-circle-fill"></i>
                                Riwayat kesehatan
                            </div>

                            <div class="role-feature-item">
                                <i class="bi bi-check-circle-fill"></i>
                                Catatan dokter
                            </div>

                        </div>

                        <?php if (
                            session()->getFlashdata(
                                'patient_error'
                            )
                        ): ?>
                            <div class="alert alert-danger">
                                <?= esc(
                                    session()->getFlashdata(
                                        'patient_error'
                                    )
                                ) ?>
                            </div>
                        <?php endif; ?>

                        <div class="role-divider"></div>

                        <form
                            action="<?= site_url(
                                'identify/patient'
                            ) ?>"
                            method="post"
                        >
                            <?= csrf_field() ?>

                            <div class="mb-3">

                                <label
                                    for="device_id"
                                    class="form-label"
                                >
                                    Device ID ESP32
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-cpu"></i>
                                    </span>

                                    <input
                                        type="text"
                                        class="form-control text-uppercase"
                                        id="device_id"
                                        name="device_id"
                                        value="<?= esc(
                                            old('device_id')
                                        ) ?>"
                                        placeholder="Contoh: 841FE82BEFBC"
                                        maxlength="17"
                                        autocomplete="off"
                                        required
                                    >

                                </div>

                                <div class="form-text">
                                    Tanda titik dua atau strip akan
                                    dihapus otomatis oleh sistem.
                                </div>

                            </div>

                            <button
                                type="submit"
                                class="btn btn-role btn-patient w-100"
                            >
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                Masuk sebagai Pasien
                            </button>

                        </form>

                    </div>

                </section>

            </div>

            <!-- DOKTER -->
            <div class="col-12 col-lg-6">

                <section class="role-card doctor">

                    <div class="role-card-inner">

                        <div class="role-card-top">

                            <div class="role-icon doctor-icon">
                                <i class="bi bi-person-badge-fill"></i>
                            </div>

                            <div>
                                <h2 class="role-label">
                                    Akses Dokter
                                </h2>

                                <p class="role-description">
                                    Masuk menggunakan ID dokter yang aktif
                                    untuk memantau pasien yang sedang ditangani.
                                </p>
                            </div>

                        </div>

                        <div class="role-feature-list">

                            <div class="role-feature-item">
                                <i class="bi bi-check-circle-fill"></i>
                                Monitoring pasien
                            </div>

                            <div class="role-feature-item">
                                <i class="bi bi-check-circle-fill"></i>
                                Notifikasi warning
                            </div>

                            <div class="role-feature-item">
                                <i class="bi bi-check-circle-fill"></i>
                                Catatan medis
                            </div>

                            <div class="role-feature-item">
                                <i class="bi bi-check-circle-fill"></i>
                                Laporan PDF
                            </div>

                        </div>

                        <?php if (
                            session()->getFlashdata(
                                'doctor_error'
                            )
                        ): ?>
                            <div class="alert alert-danger">
                                <?= esc(
                                    session()->getFlashdata(
                                        'doctor_error'
                                    )
                                ) ?>
                            </div>
                        <?php endif; ?>

                        <div class="role-divider"></div>

                        <form
                            action="<?= site_url(
                                'identify/doctor'
                            ) ?>"
                            method="post"
                        >
                            <?= csrf_field() ?>

                            <div class="mb-3">

                                <label
                                    for="doctor_code"
                                    class="form-label"
                                >
                                    ID Dokter
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-person-vcard"></i>
                                    </span>

                                    <input
                                        type="text"
                                        class="form-control text-uppercase"
                                        id="doctor_code"
                                        name="doctor_code"
                                        value="<?= esc(
                                            old('doctor_code')
                                        ) ?>"
                                        placeholder="Contoh: DOK001"
                                        maxlength="30"
                                        autocomplete="off"
                                        required
                                    >

                                </div>

                                <div class="form-text">
                                    Gunakan ID dokter yang terdaftar
                                    dan masih berstatus aktif.
                                </div>

                            </div>

                            <button
                                type="submit"
                                class="btn btn-role btn-doctor w-100"
                            >
                                <i class="bi bi-box-arrow-in-right me-1"></i>
                                Masuk sebagai Dokter
                            </button>

                        </form>

                    </div>

                </section>

            </div>

        </div>

        <!-- ADMIN -->
        <div class="admin-access">

            <div class="admin-access-info">

                <div class="admin-access-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>

                <div>
                    <div class="admin-access-title">
                        Akses Administrator Sistem
                    </div>

                    <div class="admin-access-description">
                        Kelola dokter, pasien, perangkat, dan penugasan.
                    </div>
                </div>

            </div>

            <button
                type="button"
                class="btn btn-admin px-4"
                data-bs-toggle="modal"
                data-bs-target="#adminModal"
            >
                <i class="bi bi-shield-lock me-1"></i>
                Masuk sebagai Admin
            </button>

        </div>

    </div>

    <footer class="system-footer">

        <div class="container">

            <div class="footer-title">
                Telemedicine — IoT Health Monitoring System
            </div>

            <div class="footer-meta">
                ESP32 · CodeIgniter 4 · Version 1.0
                <span class="mx-1">·</span>
                © 2026
            </div>

        </div>

    </footer>

</section>

<!-- MODAL ADMIN -->
<div
    class="modal fade"
    id="adminModal"
    tabindex="-1"
    aria-labelledby="adminModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="admin-modal-hero">

                <div class="d-flex justify-content-between align-items-start">

                    <div>

                        <div class="admin-modal-icon">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>

                        <h2
                            class="h5 fw-bold mb-1"
                            id="adminModalLabel"
                        >
                            Identifikasi Administrator
                        </h2>

                        <p class="small mb-0 opacity-75">
                            Masukkan ID administrator yang aktif.
                        </p>

                    </div>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"
                    ></button>

                </div>

            </div>

            <form
                action="<?= site_url(
                    'identify/admin'
                ) ?>"
                method="post"
            >
                <?= csrf_field() ?>

                <div class="modal-body p-4">

                    <?php if (
                        session()->getFlashdata(
                            'admin_error'
                        )
                    ): ?>
                        <div class="alert alert-danger">
                            <?= esc(
                                session()->getFlashdata(
                                    'admin_error'
                                )
                            ) ?>
                        </div>
                    <?php endif; ?>

                    <label
                        for="admin_code"
                        class="form-label"
                    >
                        ID Administrator
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-person-lock"></i>
                        </span>

                        <input
                            type="text"
                            class="form-control text-uppercase"
                            id="admin_code"
                            name="admin_code"
                            value="<?= esc(
                                old('admin_code')
                            ) ?>"
                            placeholder="Contoh: ADM001"
                            maxlength="30"
                            autocomplete="off"
                            required
                        >

                    </div>

                </div>

                <div class="modal-footer px-4 pb-4 pt-0 border-0">

                    <button
                        type="button"
                        class="btn btn-light px-4"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary px-4"
                    >
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        Masuk
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            const forms =
                document.querySelectorAll('form');

            forms.forEach(function (form) {
                form.addEventListener(
                    'submit',
                    function () {
                        const button =
                            form.querySelector(
                                'button[type="submit"]'
                            );

                        if (!button) {
                            return;
                        }

                        button.disabled = true;

                        button.innerHTML =
                            '<span class="spinner-border '
                            + 'spinner-border-sm me-2" '
                            + 'aria-hidden="true"></span>'
                            + 'Memproses...';
                    }
                );
            });
        }
    );
</script>

<?php if (
    session()->getFlashdata('admin_error')
): ?>
    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function () {
                const modalElement =
                    document.getElementById(
                        'adminModal'
                    );

                const adminModal =
                    new bootstrap.Modal(
                        modalElement
                    );

                adminModal.show();
            }
        );
    </script>
<?php endif; ?>

</body>

</html>