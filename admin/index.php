<?php

if (!isset($_SESSION)) {
  session_start();
}

// include_once($_SERVER['DOCUMENT_ROOT'] . '/rsp/config/koneksi.php');
include_once('./koneksi.php');

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>System Poliklinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css"  rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
    </style>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> -->
   

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        popins: ["Poppins", "sans-serif"]
                    },
                    backgroundImage: {
                        'jumbotron': "url('https://flowbite.s3.amazonaws.com/docs/jumbotron/hero-pattern-dark.svg')"
                    },
                    screens: {
                        '2xl': '1420px',
                    },
                },
                container: {
                    padding: {
                        DEFAULT: '1rem',
                        sm: '2rem',
                        lg: '4rem',
                        xl: '5rem',
                        '2xl': '6rem',
                    },
                },
            },
        }
    </script>
  </head>
  <body>

    <!-- Navbar -->
    <nav class="flex items-center shadow-md h-20 bg-white">
        <div class="container flex mx-auto justify-between">
            <div class="flex gap-20">
                <h1 class="text-emerald-800 md:text-2xl font-bold border">Sistem Poliklinik</h1>

                <ul class="flex flex-row gap-5 items-center">
                    
                </ul>
            </div>
            <!-- <h1 class="text-white font-semibold border">Logout</h1> -->

            <!-- Link -->
            <div class="flex gap-20">
                <ul class="flex flex-row gap-5 items-center">
                    <li>
                            <a href="index.php" class="font-semibold">Home</a>
                        </li>
        
                        <li>
                            <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" class="text-neutral-800 border-2 border-emerald-600 hover:text-white hover:bg-emerald-600 font-medium rounded-full text-sm px-4 py-2 text-center inline-flex items-center" 
                            type="button">
                                Menu 
                                <svg class="w-2.5 h-2.5 ms-3 hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                </svg>
                            </button>
                        </li>
                    <?php
                        if (isset($_SESSION['username'])) {
                            // Jika pengguna sudah login, tampilkan tombol "Logout"
                        ?>
                            <li class="flex">
                                <img src="../img/profile.png" alt="" class="w-10 h-10">
                                <button id="akun" data-dropdown-toggle="dropdown-akun" class="text-neutral-800 font-bold rounded-full text-sm px-2 text-center inline-flex items-center" 
                                type="button">
                                    <?php echo $_SESSION['username'] ?>
                                    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                    </svg>
                                </button>
                            </li>
                        <?php
                            } else {
                                // Jika pengguna belum login, tampilkan tombol "Login" dan "Register"
                            ?>
                                <li>
                                    <a href="index.php?page=loginUser">Login</a>
                                </li>
                                <li>
                                    <a href="index.php?page=registerUser">Register</a>
                                </li>
                        <?php
                        }
                    ?>
                </ul>
            </div>
        </div>
    </nav>


    <main role="main" class="">
    <?php
    if (isset($_GET['page'])) {
        include($_GET['page'] . ".php");
    } else {
        // echo "<br><h2>Selamat Datang di Sistem Informasi Poliklinik";
        echo "
            <section class='relative bg-emerald-800 bg-jumbotron'>
                <div class='py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 z-10 relative'>
            ";

        if (isset($_SESSION['username'])) {
            //jika sudah login tampilkan username
            //jika sudah login tampilkan username
            echo '
            <a href="#" class="inline-flex justify-between items-center py-1 px-1 pe-4 mb-7 text-sm rounded-full bg-blue-900 text-blue-300 hover:bg-blue-800">
                <span class="text-xs bg-blue-600 rounded-full text-white px-4 py-1.5 me-3">Good</span> <span class="text-sm font-medium">Terimakasih sudah login</span> 
                <svg class="w-2.5 h-2.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
            </a>
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl lg:text-6xl text-white">Selamat Datang di Sistem Informasi Poliklinik, <span class="text-indigo-500">'
            . $_SESSION['username'] . '
            </span></h1>
            <p class="mb-8 text-lg font-normal lg:text-xl sm:px-16 lg:px-48 text-gray-200">Here at Flowbite we focus on markets where technology, innovation, and capital can unlock long-term value and drive economic growth.</p>
            </div></section>
            <div class="grid grid-cols-3 gap-5 container mx-auto mt-10">
                <div class="border border-neutral-300 p-10 rounded-md">
                    <img src="../img/obat.png" alt="Obat Image">
                    <h4>Obat</h4>
                    <p>Informasi: 100 data</p>
                </div>
                <div class="border border-neutral-300 p-10 rounded-md">
                    <img src="../img/obat.png" alt="Obat Image">
                    <h4>Obat</h4>
                    <p>Informasi: 100 data</p>
                </div>
                <div class="border border-neutral-300 p-10 rounded-md">
                    <img src="../img/obat.png" alt="Obat Image">
                    <h4>Obat</h4>
                    <p>Informasi: 100 data</p>
                </div>

            </div>
            ';
    ?>

    
        
    <?php
        } else {
            // echo "</h2><hr>Silakan Login untuk menggunakan sistem. Jika belum memiliki akun silakan Register terlebih dahulu.";
            echo '
                <a href="#" class="inline-flex justify-between items-center py-1 px-1 pe-4 mb-7 text-sm rounded-full bg-yellow-900 text-yellow-300 hover:bg-yellow-800">
                    <span class="text-xs bg-yellow-600 rounded-full text-white px-4 py-1.5 me-3">Warn</span> <span class="text-sm font-medium">Anda belum login</span> 
                    <svg class="w-2.5 h-2.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                </a>
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl lg:text-6xl text-white">Selamat Datang di Sistem Informasi Poliklinik</h1>
                <p class="mb-8 text-lg font-normal lg:text-xl sm:px-16 lg:px-48 text-gray-200">Jika belum memiliki akun login dulu!</p>
                </div></section>';
        }

        
    }
    ?>

    </main>

    <!-- Dropdown menu -->
    <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
          <li>
            <a href="index.php?page=poli" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Poli</a>
          </li>
          <li>
            <a href="index.php?page=obat" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Obat</a>
          </li>
          <li>
            <a href="index.php?page=dokter" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Dokter</a>
          </li>
          <li>
            <a href="index.php?page=aturJadwalDokter" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Jadwal Dokter</a>
          </li>
    </div>
    
    <div id="dropdown-akun" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="akun">
          <li>
            <a href="Logout.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Logout</a>
          </li>
        </ul>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script> -->
  </body>
</html>