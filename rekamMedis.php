<?php
// Koneksi ke database
if (!isset($_SESSION)) {
    session_start();
}

// Fungsi untuk menghasilkan nomor RM

if (isset($_POST['cari'])) {
    $nama = $_POST['nama'];
    
    // Query untuk mencari no_rm berdasarkan nama
    $query = "SELECT * FROM pasien WHERE nama LIKE '%$nama%'";
    $result = $mysqli->query($query);

    // Periksa apakah ada hasil
    if ($result->num_rows > 0) {
        // Ambil hasil query
        $row = $result->fetch_assoc();
        $noRM = $row['no_rm'];
        
        // Tampilkan data pasien dalam modal
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    var modal = document.getElementById('myModal');
                    var span = document.getElementsByClassName('close')[0];
                    
                    modal.style.display = 'block';
                    
                    // Isi data pasien ke dalam modal
                    var modalContent = document.getElementById('modalContent');
                    modalContent.innerHTML = '<p>Nama: ' + '".$row['nama']."' + '</p>' +                
                                             '<p>No. RM: ' + '".$row['no_rm']."' + '</p>';
                    
                    // Ketika pengguna menekan 'x', tutup modal
                    span.onclick = function() {
                        modal.style.display = 'none';
                    }
                    
                    // Ketika pengguna mengklik di luar modal, tutup modal
                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.style.display = 'none';
                        }
                    }
                });
              </script>";

    } else {
        echo '
            <div class="max-w-fit mt-10">
                <div id="alert-1" class="flex items-center p-4 mb-4 text-blue-800 rounded-lg bg-blue-50" role="alert">
                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div class="ms-3 text-sm font-normal">
                        Tidak ditemukan nomor RM untuk '. '<span class="font-bold">' . $nama . '</span>' . '
                    </div>
                </div>                
            </div>
        ';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Nomor RM</title>
    <style>
        /* Style untuk modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        
        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        
        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body class="font-popins">
    <section class="py-[2rem]">
        <div class="p-10 max-w-fit shadow-md">
            <h2 class="font-semibold mb-8">Cari Nomor RM berdasarkan Nama</h2>
            <form id="form" method="POST" action="" class="flex flex-col gap-5">
                <div class="flex flex-col gap-2">
                    <label for="nama">Nama:</label>
                    <input class="border border-neutral-200 rounded-md px-3 py-2" type="text" name="nama" id="nama" placeholder="input nama" required>
                </div>
                <button type="submit" name="cari" class="bg-blue-700 py-2 text-white rounded-md hover:bg-blue-800 font-bold uppercase">Cari</button>
            </form>
        </div>
    </section>

    <!-- Modal untuk menampilkan data pasien -->
    <div id="myModal" class="modal">
        <div class="modal-content" id="modalContent">
            <!-- Data pasien akan ditampilkan di sini -->
        </div>
    </div>

    

    <!-- Modal toggle -->
    <!-- <button data-modal-target="rm" data-modal-toggle="rm" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
    Toggle modal
    </button> -->

    <!-- Main modal -->
    <div id="rm" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Data Rekam Medis
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="rm">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <p class="text-base leading-relaxed text-gray-500">
                        Nama: <?php echo $nama ?>
                    </p>
                    <p class="text-base leading-relaxed text-gray-500">
                        Alamat: 
                    </p>
                    <p class="text-base leading-relaxed text-gray-500">
                        No-KTP: 
                    </p>
                    <p class="text-base leading-relaxed text-gray-500">
                        No-HP: 
                    </p>
                    <p class="text-base leading-relaxed text-gray-500">
                        No-RM: 
                    </p>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Tutup modal ketika pengguna menekan tombol 'x'
        var span = document.getElementsByClassName('close')[0];
        span.onclick = function() {
            var modal = document.getElementById('myModal');
            modal.style.display = 'none';
        }
    </script>
</body>
</html>
