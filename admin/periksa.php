<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['id'])) {
    // Jika pengguna sudah login, tampilkan tombol "Logout"
    header("Location: index.php?page=loginDokter");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari formulir
    $idDaftarPoli = $_POST['id_daftar_poli'];
    $catatan = $_POST['catatan'];
    $biayaPeriksa = $_POST['biaya_periksa'];
    $idObat = $_POST['id_obat'];

    // Simpan data periksa ke tabel periksa
    $tambahPeriksa = mysqli_query($mysqli, "INSERT INTO periksa (id_daftar_poli, tanggal_periksa, catatan, biaya_periksa) 
                                           VALUES ('$idDaftarPoli', NOW(), '$catatan', '$biayaPeriksa')");

    if ($tambahPeriksa) {
        // Ambil ID periksa yang baru saja ditambahkan
        $idPeriksa = mysqli_insert_id($mysqli);

        // Simpan data detail periksa ke tabel detail_periksa
        foreach ($idObat as $obat) {
            mysqli_query($mysqli, "INSERT INTO detail_periksa (id_periksa, id_obat) 
                                   VALUES ('$idPeriksa', '$obat')");
        }

        // Redirect atau tampilkan pesan sukses
        echo "<script> 
                alert('Pemeriksaan berhasil ditambahkan.');
                document.location='berandaDokter.php?page=periksa';
              </script>";
    } else {
        // Tampilkan pesan error jika gagal menyimpan data
        echo "<script> 
                alert('Error: " . mysqli_error($mysqli) . "');
              </script>";
    }
}
?>

<section class="container mx-auto mt-10">
    <h2 class="font-bold text-2xl">Periksa</h2>
    <br>
    <div class="">
        <!--Form Input Data-->
    
    
        <form class="form flex flex-col gap-3 w-1/4" method="POST" action="" name="myForm" onsubmit="return(validate());">
            <!-- Kode php untuk menghubungkan form dengan database -->
            <?php
            $id_dokter = $_SESSION['id'];
            $hari_dipilih = "Senin";
            $nama_pasien = '';
            $antrian = '';
            $biaya_periksa = 150000;
            if (isset($_GET['id'])) {
                $ambil = mysqli_query($mysqli, "SELECT dp.id, dp.keluhan, dp.no_antrian, p.nama AS nama_pasien
                                                FROM daftar_poli dp
                                                JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
                                                JOIN pasien p ON dp.id_pasien = p.id
                                                WHERE jp.id_dokter = '$id_dokter' AND jp.hari = '$hari_dipilih'");
                while ($data = mysqli_fetch_array($ambil)) {
                    $nama_pasien = $data['nama_pasien'];
                    $antrian = $data['no_antrian'];

                }
            
            ?>
            <input type="hidden" name="id_daftar_poli" value="<?php echo $_GET['id'] ?>">
            <?php
            }
            ?>
            <div class="flex flex-col gap-2">
                <label for="inputNama" class="form-label fw-bold">
                    Nama Pasien - Antrian
                </label>
                <div>
                    <input type="text" class="form-control w-full border border-neutral-300 rounded-md" name="nama" id="inputNama" placeholder="Nama" value="<?php echo $nama_pasien , " - Antrian no: " , $antrian ?>" readonly>
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label for="catatan" class="form-label fw-bold">
                    Catatan Kesehatan
                </label>
                <div>
                    <input type="text" class="form-control w-full border border-neutral-300 rounded-md" name="catatan" id="catatan" placeholder="Catatan">
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label for="biaya_periksa" class="form-label fw-bold">
                    Biaya Periksa
                </label>
                <div>
                    <input type="number" class="form-control w-full border border-neutral-300 rounded-md" name="biaya_periksa" id="biaya_periksa" placeholder="Biaya Periksa" readonly>
                </div>
            </div>

            <!-- Tambahkan input tersembunyi untuk id_obat -->
           

            <div class="flex flex-col gap-2">
                <label for="selectObat">
                    Obat
                </label>
                <select class="py-2 px-3 border border-neutral-300 rounded-md" name="id_obat[]" id="selectObat">
                    <option value="">-- Rekomendasi Obat --</option>
                    <?php
                    $result = mysqli_query($mysqli, "SELECT * FROM obat");
                    while ($data = mysqli_fetch_array($result)) {
                        $id_obat_option = $data['id'];
                        $nama_obat = $data['nama_obat'];
                        $harga_obat = $data['harga'];
                        $selected = ($id_obat_option == $id_obat) ? 'selected' : ''; // menetapkan 'selected' jika nilainya sama dengan $id_obat
                    ?>
                        <option name='id_obat' value="<?php echo $id_obat_option ?>" data-harga="<?php echo $harga_obat ?>" <?php echo $selected ?>><?php echo "Obat " . $nama_obat ?></option>
                    <?php } ?>
                </select>
            </div>
    
            <div class="row mt-3">
                <div class=col>
                    <button type="submit" class="bg-blue-700 hover:bg-blue-800 uppercase text-white py-2 w-full font-bold rounded-lg" name="submit">Periksa</button>
                </div>
            </div>
            <script>
                function selectObat(idObat, obatName) {
                    document.getElementById('selectedOabt').textContent = "Oabt " + obatName;
                    document.getElementById('selectedOabt').dataset.poliId = idObat; // Menambahkan data-poli-id ke tombol
                    document.getElementById('inputIdObat').value = idObat; // Menetapkan nilai id_obat ke input tersembunyi
                }
                
            </script>

            <script>
            // Mendapatkan elemen-elemen yang dibutuhkan
            var biayaPeriksaInput = document.getElementById('biaya_periksa');
            var selectObat = document.getElementById('selectObat');

            // Menambahkan event listener untuk menghitung total biaya saat dropdown obat berubah
            selectObat.addEventListener('change', function() {
                // Mendapatkan harga obat dari atribut data-harga
                var hargaObat = parseFloat(selectObat.options[selectObat.selectedIndex].getAttribute('data-harga')) || 0;

                // Menghitung total biaya
                var totalBiaya = hargaObat + 150000;

                // Memasukkan nilai total biaya ke input biaya_periksa
                biayaPeriksaInput.value = totalBiaya;
            });
            </script>
        </form>
        <br>
        <br>
        
        <!-- TABLE -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama 
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Keluhan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            No Antrian
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Kode PHP untuk menampilkan semua isi dari tabel urut-->
                    <?php
                    $id_dokter = $_SESSION['id'];
                    $hari_dipilih = "Senin";
                    // $hari_dipilih = date('l');
                    $no = 1; 

                    // Kueri untuk mengambil daftar poli yang terkait dengan dokter dan hari yang dipilih
                    $resultDaftarPoli = mysqli_query($mysqli, "SELECT dp.id, dp.keluhan, dp.no_antrian, p.nama AS nama_pasien
                                                            FROM daftar_poli dp
                                                            JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
                                                            JOIN pasien p ON dp.id_pasien = p.id
                                                            WHERE jp.id_dokter = '$id_dokter' AND jp.hari = '$hari_dipilih'");

                    while ($data = mysqli_fetch_assoc($resultDaftarPoli)) {
                    ?>
                        <tr>
                            <th scope="row"><?php echo $no++ ?></th>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center"><?php echo $data['nama_pasien'] ?></td>
                            <td class="px-6 py-4 text-center"><?php echo $data['keluhan'] ?></td>
                            <td class="px-6 py-4 text-center"><?php echo $data['no_antrian'] ?></td>
                            <td class="px-6 py-4 text-center">
                                <a class="font-medium text-blue-600 hover:underline" href="berandaDokter.php?page=riwayatPasien&id=<?php echo $data['id'] ?>">Riwayat Pasien</a>
                                |
                                <a class="font-medium text-red-600 hover:underline" href="berandaDokter.php?page=periksa&id=<?php echo $data['id'] ?>">Periksa</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
</section>    

<br>
<br>
