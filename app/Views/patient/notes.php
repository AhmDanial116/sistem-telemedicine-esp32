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
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
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
                    #2563eb,
                    #0f766e);
        }

        .note-card {
            border: 1px solid #e5e7eb;
            border-left-width: 5px;
            border-radius: 16px;
            background: #ffffff;
        }

        .note-normal {
            border-left-color: #16a34a;
        }

        .note-attention {
            border-left-color: #f59e0b;
        }

        .note-urgent {
            border-left-color: #dc2626;
        }

        .measurement-box {
            border-radius: 13px;
            background: #f8fafc;
        }

        .measurement-item {
            text-align: center;
        }

        .measurement-value {
            font-size: 1.15rem;
            font-weight: 800;
        }

        .measurement-label {
            color: var(--muted);
            font-size: 0.75rem;
        }

        .content-box {
            padding: 16px;
            border-radius: 13px;
            background: #f8fafc;
        }

        .content-label {
            margin-bottom: 6px;
            font-weight: 700;
        }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: var(--muted);
        }

        .empty-state i {
            display: block;
            margin-bottom: 15px;
            color: #cbd5e1;
            font-size: 3.5rem;
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

        @media (max-width: 767.98px) {
            .patient-information {
                display: none;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand navbar-system">
        <div class="container py-2">
            <a
                class="navbar-brand fw-bold"
                href="<?= site_url('patient/dashboard') ?>">
                <i class="bi bi-heart-pulse-fill brand-icon me-1"></i>
                Telemedicine
            </a>

            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="patient-information text-end">
                    <div class="fw-semibold">
                        <?= esc($patientName ?? 'Pasien') ?>
                    </div>

                    <small class="text-muted">
                        <?= esc($patientCode ?? '-') ?>
                    </small>
                </div>

                <a
                    href="<?= site_url('logout') ?>"
                    class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i>
                    Keluar
                </a>
            </div>
        </div>
    </nav>

    <main class="container py-4">

        <div class="mb-3">
            <a
                href="<?= site_url('patient/dashboard') ?>"
                class="btn btn-back btn-sm">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali ke Dashboard
            </a>
        </div>

        <section class="card dashboard-card page-header mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="small opacity-75 mb-1">
                            Informasi Medis
                        </div>

                        <h1 class="h3 fw-bold mb-2">
                            Catatan dan Arahan Dokter
                        </h1>

                        <p class="mb-0 opacity-75">
                            Baca penilaian dan rekomendasi dokter
                            berdasarkan hasil pengukuran Anda.
                        </p>
                    </div>

                    <div class="col-auto d-none d-md-block">
                        <i class="bi bi-journal-medical display-3 opacity-75"></i>
                    </div>
                </div>
            </div>
        </section>

        <section class="card dashboard-card">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                    <div>
                        <h2 class="h5 fw-bold mb-1">
                            Riwayat Catatan
                        </h2>

                        <div class="text-muted small">
                            Catatan terbaru ditampilkan paling atas.
                        </div>
                    </div>

                    <span class="badge rounded-pill text-bg-light border">
                        <?= count($medicalNotes) ?> catatan
                    </span>
                </div>

                <?php if ($medicalNotes === []): ?>
                    <div class="empty-state">
                        <i class="bi bi-journal-x"></i>

                        <h3 class="h5 fw-bold">
                            Belum ada catatan dokter
                        </h3>

                        <p class="mb-0">
                            Catatan dan arahan dokter akan tampil
                            pada halaman ini.
                        </p>
                    </div>
                <?php else: ?>
                    <div class="d-grid gap-4">
                        <?php foreach ($medicalNotes as $note): ?>
                            <?php
                            $priority =
                                $note['priority']
                                ?? 'normal';

                            $noteClass = match ($priority) {
                                'urgent' =>
                                'note-urgent',

                                'attention' =>
                                'note-attention',

                                default =>
                                'note-normal',
                            };

                            $priorityBadge = match ($priority) {
                                'urgent' =>
                                'text-bg-danger',

                                'attention' =>
                                'text-bg-warning',

                                default =>
                                'text-bg-success',
                            };

                            $priorityLabel = match ($priority) {
                                'urgent' =>
                                'Mendesak',

                                'attention' =>
                                'Perlu Perhatian',

                                default =>
                                'Normal',
                            };
                            ?>

                            <article
                                class="note-card <?= esc($noteClass) ?> p-3 p-md-4">
                                <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
                                    <div>
                                        <h3 class="h5 fw-bold mb-1">
                                            <?= esc(
                                                $note['title']
                                                    ?: 'Catatan Dokter'
                                            ) ?>
                                        </h3>

                                        <div class="text-muted small">
                                            <i class="bi bi-person-badge me-1"></i>

                                            <?= esc(
                                                $note['doctor_name']
                                                    ?? 'Dokter'
                                            ) ?>

                                            <?php if (
                                                ! empty($note['doctor_code'])
                                            ): ?>
                                                ·
                                                <?= esc(
                                                    $note['doctor_code']
                                                ) ?>
                                            <?php endif; ?>

                                            ·
                                            <?= esc(
                                                $note['created_at']
                                            ) ?>
                                        </div>
                                    </div>

                                    <div>
                                        <span
                                            class="badge <?= esc(
                                                                $priorityBadge
                                                            ) ?>">
                                            <?= esc($priorityLabel) ?>
                                        </span>

                                        <span class="badge text-bg-success">
                                            <i class="bi bi-check2 me-1"></i>
                                            Sudah Dibaca
                                        </span>
                                    </div>
                                </div>

                                <?php if (
                                    ! empty($note['measurement_id'])
                                ): ?>
                                    <div class="measurement-box p-3 mb-3">
                                        <div class="small text-muted mb-3">
                                            Hasil pengukuran terkait
                                            ·
                                            <?= esc(
                                                $note['measured_at']
                                                    ?? '-'
                                            ) ?>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-6 col-md-3">
                                                <div class="measurement-item">
                                                    <div class="measurement-value">
                                                        <?= esc(
                                                            $note['systolic']
                                                                ?? '-'
                                                        ) ?>
                                                    </div>

                                                    <div class="measurement-label">
                                                        SYS (mmHg)
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6 col-md-3">
                                                <div class="measurement-item">
                                                    <div class="measurement-value">
                                                        <?= esc(
                                                            $note['diastolic']
                                                                ?? '-'
                                                        ) ?>
                                                    </div>

                                                    <div class="measurement-label">
                                                        DIA (mmHg)
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6 col-md-3">
                                                <div class="measurement-item">
                                                    <div class="measurement-value">
                                                        <?= esc(
                                                            $note['map']
                                                                ?? '-'
                                                        ) ?>
                                                    </div>

                                                    <div class="measurement-label">
                                                        MAP (mmHg)
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6 col-md-3">
                                                <div class="measurement-item">
                                                    <div class="measurement-value">
                                                        <?= esc(
                                                            $note['bpm']
                                                                ?? '-'
                                                        ) ?>
                                                    </div>

                                                    <div class="measurement-label">
                                                        BPM
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if (
                                    ! empty($note['assessment'])
                                ): ?>
                                    <div class="content-box mb-3">
                                        <div class="content-label">
                                            <i class="bi bi-clipboard2-pulse me-1"></i>
                                            Penilaian Dokter
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

                                <div class="content-box">
                                    <div class="content-label">
                                        <i class="bi bi-chat-left-heart me-1"></i>
                                        Rekomendasi dan Arahan
                                    </div>

                                    <div>
                                        <?= nl2br(
                                            esc(
                                                $note['recommendation']
                                            )
                                        ) ?>
                                    </div>
                                </div>
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