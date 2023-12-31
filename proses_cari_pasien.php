<?php
include_once 'config/koneksi.php';

// Koneksi ke database

if (isset($_GET['id_pasien'])) {
    $idPasien = $_GET['id_pasien'];

    // Query untuk mencari data pasien berdasarkan No. RM atau NIK
    $query = "SELECT * FROM pasien WHERE no_rm = '$idPasien' OR no_ktp = '$idPasien'";
    $result = mysqli_query($mysqli, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Ambil hasil query
        $row = mysqli_fetch_assoc($result);

        // Tampilkan data pasien dalam modal
        echo '<p>Nama: ' . $row['nama'] . '</p>' .
             '<p>Alamat: ' . $row['alamat'] . '</p>' .
             '<p>No. KTP: ' . $row['no_ktp'] . '</p>' .
             '<p>No. HP: ' . $row['no_hp'] . '</p>' .
             '<p>No. RM: ' . $row['no_rm'] . '</p>';
    } else {
        echo 'Tidak ditemukan data pasien';
    }
}
