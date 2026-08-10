<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | SIMPEG-ASSET</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0d6efd 0%, #eef4ff 100%);
            display: grid;
            place-items: center;
        }

        .card {
            width: min(420px, 92vw);
            border: 0;
            border-radius: 24px;
            box-shadow: 0 24px 80px rgba(15, 23, 42, .18);
        }
    </style>
</head>

<body>
    <div class="card p-4">
        <h1 class="h3 mb-1">Masuk</h1>
        <p class="text-muted">Login awal untuk admin dan pengguna internal.</p>
        <form method="post" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button class="btn btn-primary w-100" type="submit">Login</button>
        </form>
    </div>
</body>

</html>
<?php /**PATH D:\BakesBangPol\KesbangPol\resources\views/auth/login.blade.php ENDPATH**/ ?>