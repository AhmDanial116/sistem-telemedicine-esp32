<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> | Telemedicine System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/medical-ui.css') ?>" rel="stylesheet">

    <style>
        .assign-page {
            min-height: 100vh;
        }

        .assign-wrapper {
            max-width: 920px;
            margin: 0 auto;
        }

        .assign-hero {
            position: relative;
            overflow: hidden;
            color: #fff;
            background: linear-gradient(135deg, #0f766e 0%, #0d9488 52%, #2563eb 100%);
            border-radius: 24px;
            box-shadow: 0 18px 45px rgba(15, 118, 110, .20);
        }

        .assign-hero::before {
            position: absolute;
            top: -95px;
            right: -70px;
            width: 250px;
            height: 250px;
            content: '';
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
        }

        .assign-hero::after {
            position: absolute;
            right: 150px;
            bottom: -130px;
            width: 215px;
            height: 215px;
            content: '';
            background: rgba(255, 255, 255, .06);
            border-radius: 50%;
        }

        .assign-hero-content {
            position: relative;
            z-index: 1;
        }

        .assign-device-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .assign-device-item {
            min-width: 0;
            padding: 15px;
            background: #f8fafc;
            border: 1px solid #eef2f7;
            border-radius: 15px;
        }

        .assign-device-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            color: #0f766e;
            background: #ccfbf1;
            border-radius: 12px;
        }

        .assign-device-label {
            color: #64748b;
            font-size: .72rem;
            font-weight: 600;
        }

        .assign-device-value {
            margin-top: 4px;
            color: #0f172a;
            font-size: .86rem;
            font-weight: 750;
            word-break: break-word;
        }

        .assign-note {
            padding: 13px 15px;
            color: #075985;
            background: #e0f2fe;
            border: 1px solid #bae6fd;
            border-radius: 13px;
            font-size: .78rem;
            line-height: 1.55;
        }

        .assign-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        @media (max-width: 767.98px) {
            .assign-wrapper {
                max-width: 100%;
            }

            .assign-device-grid {
                grid-template-columns: 1fr;
            }

            .assign-hero {
                border-radius: 18px;
            }

            .assign-actions {
                flex-direction: column-reverse;
            }

            .assign-actions .btn {
                width: 100%;
            }

            .medical-user-info {
                display: none;
            }
        }

        @media (max-width: 575.98px) {
            .medical-navbar .container {
                gap: 10px;
            }

            .medical-brand-subtitle {
                display: none;
            }

            .medical-brand-icon {
                width: 38px;
                height: 38px;
                border-radius: 12px;
            }

            .assign-hero .p-4 {
                padding: 22px !important;
            }

            .assign-hero-title {
                font-size: 1.35rem;
            }

            .assign-main {
                padding-top: 24px !important;
                padding-bottom: 32px !important;
            }
        }
    </style>
</head>

<body class="medical-app assign-page">

    <nav class="navbar medical-navbar">
        <div class="container py-2">
            <a href="<?= site_url('admin/dashboard') ?>" class="medical-brand">
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
                        <?= esc($adminName ?? 'Admin') ?>
                    </div>
                    <div class="medical-user-role">
                        <?= esc($adminCode ?? '-') ?> · Administrator Sistem
                    </div>
                </div>

                <a href="<?= site_url('logout') ?>"
                    class="btn btn-medical-danger btn-sm px-3"
                    title="Keluar">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="d-none d-sm-inline ms-1">Keluar</span>
                </a>
            </div>
        </div>
    </nav>

    <main class="container py-4 py-lg-5 assign-main">
        <div class="assign-wrapper">

            <div class="mb-3">
                <a href="<?= site_url('admin/dashboard#devicesSection') ?>"
                    class="btn btn-medical-soft btn-sm px-3">
                    <i class="bi bi-arrow-left me-1"></i>
                    Kembali ke Perangkat
                </a>
            </div>

            <section class="assign-hero mb-4">
                <div class="assign-hero-content p-4 p-lg-5">
                    <div class="row align-items-center g-4">
                        <div class="col">
                            <div class="medical-hero-eyebrow">
                                Manajemen Perangkat
                            </div>
                            <h1 class="medical-hero-title assign-hero-title mb-2">
                                Assign Perangkat ke Pasien
                            </h1>
                            <p class="medical-hero-description">
                                Hubungkan perangkat ESP32 yang terdaftar
                                dengan pasien yang akan menggunakannya.
                            </p>
                        </div>

                        <div class="col-auto d-none d-md-block">
                            <div class="medical-hero-illustration">
                                <i class="bi bi-cpu-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <?php if (session()->getFlashdata('assignment_error')): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= esc(session()->getFlashdata('assignment_error')) ?>
                </div>
            <?php endif; ?>

            <section class="medical-card">
                <div class="medical-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="medical-stat-icon warning">
                            <i class="bi bi-cpu-fill"></i>
                        </div>

                        <div>
                            <div class="medical-section-title">
                                Informasi Perangkat
                            </div>
                            <p class="medical-section-description">
                                Periksa kembali perangkat sebelum melakukan assign ke pasien.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="medical-card-body">

                    <div class="assign-device-grid mb-4">

                        <div class="assign-device-item">
                            <div class="assign-device-icon">
                                <i class="bi bi-upc-scan"></i>
                            </div>
                            <div class="assign-device-label">Device ID</div>
                            <div class="assign-device-value medical-code">
                                <?= esc($device['device_id']) ?>
                            </div>
                        </div>

                        <div class="assign-device-item">
                            <div class="assign-device-icon">
                                <i class="bi bi-device-hdd"></i>
                            </div>
                            <div class="assign-device-label">Nama Perangkat</div>
                            <div class="assign-device-value">
                                <?= esc($device['device_name'] ?? '-') ?>
                            </div>
                        </div>

                        <div class="assign-device-item">
                            <div class="assign-device-icon">
                                <i class="bi bi-code-square"></i>
                            </div>
                            <div class="assign-device-label">Versi Firmware</div>
                            <div class="assign-device-value">
                                <?= esc($device['firmware_version'] ?? '-') ?>
                            </div>
                        </div>

                    </div>

                    <div class="assign-note mb-4">
                        <div class="d-flex gap-2">
                            <i class="bi bi-info-circle-fill mt-1"></i>
                            <div>
                                Jika pasien yang dipilih sudah memiliki perangkat aktif,
                                sistem akan memindahkan assignment ke perangkat ini
                                sesuai alur transfer perangkat yang telah diterapkan.
                            </div>
                        </div>
                    </div>

                    <form action="<?= site_url('admin/device/' . $device['id'] . '/assign') ?>"
                        method="post">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label for="patientId" class="form-label">
                                Pilih Pasien
                            </label>

                            <select name="patient_id"
                                id="patientId"
                                class="form-select"
                                required>
                                <option value="">Pilih pasien</option>

                                <?php foreach ($patients as $patient): ?>
                                    <option value="<?= (int) $patient['id'] ?>"
                                        <?= old('patient_id') == $patient['id']
                                            ? 'selected'
                                            : '' ?>>
                                        <?= esc($patient['patient_code']) ?>
                                        —
                                        <?= esc($patient['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <div class="form-text">
                                Pilih pasien yang akan menggunakan perangkat ini.
                            </div>
                        </div>

                        <div class="assign-actions">
                            <a href="<?= site_url('admin/dashboard#devicesSection') ?>"
                                class="btn btn-light px-4">
                                Batal
                            </a>

                            <button type="submit"
                                class="btn btn-medical-primary px-4">
                                <i class="bi bi-person-check-fill me-1"></i>
                                Simpan Assign
                            </button>
                        </div>
                    </form>

                </div>
            </section>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');

            if (!form) {
                return;
            }

            form.addEventListener('submit', function() {
                const button = form.querySelector('button[type="submit"]');

                if (!button) {
                    return;
                }

                button.disabled = true;
                button.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>' +
                    'Menyimpan...';
            });
        });
    </script>

</body>

</html>