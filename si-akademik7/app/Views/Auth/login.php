<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$flash = $_SESSION['flash'] ?? null;
$error = $_SESSION['error'] ?? null;

unset($_SESSION['flash']);
unset($_SESSION['error']);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login - SI Akademik</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card">

                <div class="card-header">
                    <h4 class="mb-0">Login</h4>
                </div>

                <div class="card-body">

                    <?php if ($flash): ?>

                        <div
                            class="alert alert-success alert-dismissible fade show"
                            role="alert">

                            <?= htmlspecialchars($flash) ?>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert">
                            </button>

                        </div>

                    <?php endif; ?>


                    <?php if ($error): ?>

                        <div
                            class="alert alert-danger"
                            role="alert">

                            <?= htmlspecialchars($error) ?>

                        </div>

                    <?php endif; ?>


                    <form
                        method="POST"
                        action="/bkpm/si-akademik7/public/login/process">

                        <div class="mb-3">

                            <label class="form-label">
                                Username
                            </label>

                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                required>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required>

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            Login

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>