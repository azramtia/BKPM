<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard - SI Akademik</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }

        .container {
            max-width: 1200px;
            margin: 50px auto;
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-top: 0;
            font-size: 36px;
            color: #333;
        }

        p {
            color: #555;
            font-size: 17px;
        }

        .alert {
            background: #d1e7dd;
            color: #0f5132;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 16px;
        }

        .menu {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            background: #333;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

        .btn:hover {
            background: #555;
        }

        .logout {
            background: #b02a37;
        }

        .logout:hover {
            background: #8c1f2a;
        }

    </style>

</head>

<body>

<div class="container">

    <?php if (isset($_SESSION['flash'])): ?>

        <div class="alert">

            <?= htmlspecialchars($_SESSION['flash']) ?>

        </div>

        <?php unset($_SESSION['flash']); ?>

    <?php endif; ?>


    <h1>Dashboard</h1>

    <p>
        Selamat datang di Sistem Informasi Akademik.
    </p>


    <?php if (isset($_SESSION['user'])): ?>

        <p>
            Anda login sebagai:
            <strong>
                <?= htmlspecialchars($_SESSION['user']) ?>
            </strong>
        </p>

    <?php endif; ?>


    <div class="menu">

        <a
            href="/bkpm/si-akademik7/public/mahasiswa"
            class="btn">

            Data Mahasiswa

        </a>


        <a
            href="/bkpm/si-akademik7/public/logout"
            class="btn logout">

            Logout

        </a>

    </div>

</div>

</body>

</html>