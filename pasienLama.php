<?php
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST['simpan'])) {
    if (isset($_POST['id'])) {

        // Mendapatkan ID Pasien berdasarkan No. RM / NIK
        $inputData = mysqli_real_escape_string($mysqli, $_POST['input_data']);
        $resultPasien = mysqli_query($mysqli, "SELECT id FROM pasien WHERE no_rm = '$inputData' OR no_ktp = '$inputData'");
        
        if ($resultPasien) {
            $dataPasien = mysqli_fetch_assoc($resultPasien);
            $idPasien = $dataPasien['id'];

            // Mendapatkan ID Jadwal dari dropdown yang dipilih
            $idJadwal = mysqli_real_escape_string($mysqli, $_POST['id_jadwal']);

            // Mendapatkan No. Antrian untuk jadwal tertentu
            $resultAntrian = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM daftar_poli WHERE id_jadwal = '$idJadwal'");
            $dataAntrian = mysqli_fetch_assoc($resultAntrian);
            $noAntrian = $dataAntrian['total'] + 1;

            // Insert data ke tabel daftar_poli
       

            $tambah = mysqli_query($mysqli, "INSERT INTO daftar_poli (id_pasien, id_jadwal, keluhan, no_antrian, tanggal) 
                VALUES (
                        '$idPasien',
                        '$idJadwal',
                        '" . mysqli_real_escape_string($mysqli, $_POST['keluhan']) . "',
                        '$noAntrian',
                        NOW()
                    )");
            
            if ($tambah) {
                echo "<script> 
                        alert('Data berhasil disimpan');
                        document.location='index.php?page=pasienLama';
                    </script>";
                    echo "ID Pasien: $idPasien<br>";
                    echo "ID Jadwal: $idJadwal<br>";
            } else {
                echo "<script> 
                        alert('Error: " . mysqli_error($mysqli) . "');
                        
                    </script>";
            }
        } else {
            echo "<script> 
                    alert('No. RM / NIK tidak ditemukan');
                </script>";
        }
    }
}
?>


<section class="mt-10">
    <h2 class="font-bold text-2xl">Daftar Poli</h2>
    <Script>
        echo "ID Pasien: $idPasien<br>";
        echo "ID Jadwal: $idJadwal<br>";
    
    </Script>
    <br>
    <div class="">
        <!-- Form Input Data -->
        <form class="form flex flex-col gap-4 w-1/2" method="POST" action="" name="myForm" onsubmit="return(validate());">
            <input type="hidden" name="id">

            <div class="">
                <input type="hidden" name="id_pasien" id="inputIdPasien" value="<?php echo $id_pasien; ?>">
                <div class="flex">
                    <div class="flex flex-col gap-2 w-full">
                        <label for="input_data" class="form-label fw-bold">
                            No. RM / NIK
                        </label>
                        <div class="flex gap-5">
                            <input type="text" class="w-full border border-neutral-300 rounded-md" name="input_data" id="input_data" placeholder="No RM / NIK">
                            <button type="button" class="bg-red-600 hover:bg-red-700 w-1/4 uppercase font-bold text-white rounded-md" onclick="cariPasien()">Cari</button>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="flex flex-col gap-2">
                <label for="inputid_jadwal" class="form-label fw-bold">
                    Jadwal
                </label>
                <div>
                    <!-- Dropdown untuk memilih jadwal -->
                    <select class="form-select px-3 py-3 border border-neutral-300 w-full rounded-md" name="id_jadwal" id="inputid_jadwal" required>
                        <?php
                        $resultJadwal = mysqli_query($mysqli, "SELECT jadwal.id, jadwal.hari, jadwal.jam_mulai, jadwal.jam_selesai, dokter.nama AS nama_dokter, poli.nama_poli
                        FROM jadwal_periksa jadwal
                        JOIN dokter ON jadwal.id_dokter = dokter.id
                        JOIN poli ON dokter.id_poli = poli.id
                        WHERE jadwal.status_jadwal = 'Y'");
                        while ($dataJadwal = mysqli_fetch_assoc($resultJadwal)) {
                            echo "<option value='" . $dataJadwal['id'] . "'>" . $dataJadwal['hari'] . " , " . $dataJadwal['jam_mulai'] . " - " . $dataJadwal['jam_selesai'] . " - Dr. " . $dataJadwal['nama_dokter'] . " - Poli " . $dataJadwal['nama_poli'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
    
            <div class="flex flex-col gap-2">
                <label for="inputNIK" class="form-label fw-bold">
                    Keluhan
                </label>
                <div>
                    <input type="text" class="form-control border border-neutral-300 w-full rounded-md" name="keluhan" id="inputNIK" placeholder="Keluhan">
                </div>
            </div>
    
            <div class="row mt-3">
                <div class=col>
                    <button type="submit" class="bg-blue-700 hover:bg-blue-800 uppercase font-bold text-white py-3 w-1/2 rounded-lg" name="simpan">Simpan</button>
                </div>
            </div>
        </form>
    </div>
    
        <!-- Modal untuk menampilkan data pasien -->
        <div id="myModal" class="modal">
            <div class="modal-content" id="modalContent">
                
    
            </div>
        </div>
        
    
        <script>
            // Fungsi untuk mencari data pasien
            function cariPasien() {
                var idPasien = document.getElementById('input_data').value;
    
                // Lakukan permintaan AJAX
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Tampilkan hasil pencarian pada modal
                        document.getElementById('modalContent').innerHTML = this.responseText;
                        var modal = document.getElementById('myModal');
                        modal.style.display = 'block';
                    }
                };
                xhttp.open('GET', 'proses_cari_pasien.php?id_pasien=' + idPasien, true);
                xhttp.send();
            }
    
            // Tutup modal ketika pengguna menekan tombol 'x'
            function closeModal() {
                var modal = document.getElementById('myModal');
                modal.style.display = 'none';
            }
    
            // Auto-close the modal after 3 seconds
            setTimeout(closeModal, 3000);
    
            // Close the modal when clicking outside of it
            window.onclick = function(event) {
                var modal = document.getElementById('myModal');
                if (event.target == modal) {
                    closeModal();
                }
            };
        </script>
    
    <style>
        /* Style for modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }
    
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%; /* Adjust the width as needed */
        }
    </style>
</section>

