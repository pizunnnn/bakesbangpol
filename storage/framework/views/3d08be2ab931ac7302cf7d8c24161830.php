<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMPEG-ASSET</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #0d6efd 0%, #eaf2ff 100%);
            color: #0f172a;
        }

        .card {
            width: min(720px, 92vw);
            background: rgba(255, 255, 255, .92);
            border-radius: 28px;
            padding: 40px;
            box-shadow: 0 24px 80px rgba(15, 23, 42, .18);
            text-align: center;
        }

        h1 {
            margin: 0 0 12px;
            font-size: clamp(2rem, 5vw, 3.5rem);
        }

        p {
            line-height: 1.7;
            color: #334155;
        }

        .badge {
            display: inline-block;
            padding: 8px 14px;
            background: #dbeafe;
            color: #0b5ed7;
            border-radius: 999px;
            font-weight: 700;
        }

        .btn-row {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 24px;
        }

        .btn-row a,
        .btn-row button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: white;
            background: #0d6efd;
            padding: 12px 20px;
            border-radius: 14px;
            text-decoration: none;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .btn-row a:hover,
        .btn-row button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(13, 110, 253, .35);
        }

        .btn-row .btn-outline {
            background: transparent;
            color: #0d6efd;
            border: 2px solid #0d6efd;
        }

        .btn-row .btn-outline:hover {
            background: #0d6efd;
            color: white;
        }
    </style>
</head>

<body>
    <main class="card">
        <div class="badge">SIMPEG-ASSET</div>
        <h1>Sistem Informasi Kepegawaian, Aset</h1>
        <p>Kelola data pegawai, dan aset.</p>
        <div class="btn-row">
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('dashboard')); ?>"><i class="bi bi-speedometer2"></i> Masuk Dashboard</a>
            <?php endif; ?>
            <?php if(auth()->guard()->guest()): ?>
                <a href="<?php echo e(route('login')); ?>"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                <a href="<?php echo e(route('dashboard')); ?>" class="btn-outline"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>
<?php /**PATH D:\BakesBangPol\KesbangPol\resources\views/welcome.blade.php ENDPATH**/ ?>