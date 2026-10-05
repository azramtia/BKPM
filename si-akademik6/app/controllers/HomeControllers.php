<?php

class HomeController
{
    public function index()
    {
        ?>

        <!DOCTYPE html>
        <html lang="id">

        <head>

            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <title>Si-Akademik</title>

            <link
                href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                rel="stylesheet"
            >

        </head>

        <body class="bg-light">

            <!-- Navbar -->
            <nav class="navbar navbar-dark bg-primary">

                <div class="container">

                    <span class="navbar-brand mb-0 h1">
                        Si-Akademik
                    </span>

                </div>

            </nav>


            <!-- Isi Halaman -->
            <div class="container">

                <div class="row justify-content-center">

                    <div class="col-md-8">

                        <div class="card shadow-sm mt-5">

                            <div class="card-body text-center p-5">

                                <h1 class="display-5 fw-bold">
                                    Selamat Datang di Si-Akademik
                                </h1>

                                <p class="text-muted mt-3">
                                    Sistem Informasi Akademik
                                </p>

                                <p class="mt-3">
                                    Silakan login untuk mengakses
                                    dashboard dan data mahasiswa.
                                </p>

                                <a
                                    href="/si-akademik6/publik/login"
                                    class="btn btn-primary px-4 mt-2"
                                >
                                    Login
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </body>

        </html>

        <?php
    }
}