
<?php
if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari formulir
    $idDaftarPoli = $_POST['id_daftar_poli'];
    $catatan = $_POST['catatan'];
    $biayaPeriksa = $_POST['biaya_periksa'];
    $idObat = $_POST['id_obat'];

    // Simpan data periksa ke tabel periksa
    $tambahPeriksa = mysqli_query($mysqli, "INSERT INTO periksa (id_daftar_poli, catatan, biaya_periksa) 
                                           VALUES ('$idDaftarPoli', '$catatan', '$biayaPeriksa')");

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
                document.location='berandaDokter.php';
              </script>";
    } else {
        // Tampilkan pesan error jika gagal menyimpan data
        echo "<script> 
                alert('Error: " . mysqli_error($mysqli) . "');
              </script>";
    }
}
?>

<!-- Formulir Periksa -->
<form method="POST" action="">
    <!-- Pilihan Daftar Pasien yang akan diperiksa -->
    <label for="id_daftar_poli">Pilih Daftar Pasien:</label>
    <select name="id_daftar_poli" id="id_daftar_poli" required>
    <?php
    $id_dokter = $_SESSION['id'];
    $hari_dipilih = "Selasa"; // Ganti ini sesuai dengan hari yang dipilih oleh pasien

    // Kueri untuk mengambil daftar poli yang terkait dengan dokter dan hari yang dipilih
    $resultDaftarPoli = mysqli_query($mysqli, "SELECT dp.id, dp.tanggal, dp.no_antrian, p.nama AS nama_pasien
                                               FROM daftar_poli dp
                                               JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
                                               JOIN pasien p ON dp.id_pasien = p.id
                                               WHERE jp.id_dokter = '$id_dokter' AND jp.hari = '$hari_dipilih'");

    while ($dataDaftarPoli = mysqli_fetch_assoc($resultDaftarPoli)) {
        echo "<option value='" . $dataDaftarPoli['id'] . "'>" . $dataDaftarPoli['tanggal'] . " - Antrian " . $dataDaftarPoli['no_antrian'] . " - " . $dataDaftarPoli['nama_pasien'] . "</option>";
    }
    ?>
    </select>

    <!-- Input Catatan Kesehatan -->
    <label for="catatan">Catatan Kesehatan:</label>
    <textarea name="catatan" id="catatan" rows="4" required></textarea>

    <!-- Input Biaya Periksa -->
    <label for="biaya_periksa">Biaya Periksa:</label>
    <input type="number" name="biaya_periksa" id="biaya_periksa" required>

    <!-- Pilihan Obat -->
    <label for="id_obat">Pilih Obat:</label>
    <select name="id_obat[]" id="id_obat" multiple required>
        <!-- Isi opsi dari database, contoh: -->
        <?php
        $resultObat = mysqli_query($mysqli, "SELECT id, nama_obat FROM obat");
        while ($dataObat = mysqli_fetch_assoc($resultObat)) {
            echo "<option value='" . $dataObat['id'] . "'>" . $dataObat['nama_obat'] . "</option>";
        }
        ?>
    </select>

    <!-- Tombol Submit -->
    <button type="submit">Periksa</button>
</form>
