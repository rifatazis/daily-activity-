<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PIN Login</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .card {
            background: #fff;
            padding: 30px;
            width: 100%;
            max-width: 360px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #ddd;
            outline: none;
            margin-bottom: 15px;
            text-align: center;
            letter-spacing: 4px;
        }

        input[type="password"]:focus {
            border-color: #6366f1;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #6366f1;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #4f46e5;
        }

        .subtitle {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Daily Activity</h2>
    <p class="subtitle">Masuk menggunakan PIN</p>

    <?php if($errors->any()): ?>
        <div class="error"><?php echo e($errors->first()); ?></div>
    <?php endif; ?>

    <?php if(\App\Models\User::count() === 0): ?>
        <form method="POST" action="/pin/setup">
            <?php echo csrf_field(); ?>
            <input type="password" name="pin" placeholder="Buat PIN (4 digit)" maxlength="4">
            <button>Simpan PIN</button>
        </form>
    <?php else: ?>
        <form method="POST" action="/pin">
            <?php echo csrf_field(); ?>
            <input type="password" name="pin" placeholder="Masukkan PIN" maxlength="4">
            <button>Masuk</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
<?php /**PATH C:\laragon\www\daily-activity\resources\views/auth/pin.blade.php ENDPATH**/ ?>