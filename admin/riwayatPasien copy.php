<?php
// Include your database connection file here
if (!isset($_SESSION)) {
  session_start();
}

// Ambil parameter ID dari URL
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_daftar_poli = $_GET['id'];

    // Query untuk mendapatkan data pasien berdasarkan ID Daftar Poli
    $queryPasien = mysqli_query($mysqli, "SELECT p.id, p.nama, p.alamat
                                          FROM pasien p
                                          JOIN daftar_poli dp ON p.id = dp.id_pasien
                                          WHERE dp.id = '$id_daftar_poli'");
    
    // Query untuk mendapatkan riwayat periksa pasien
    $queryRiwayat = mysqli_query($mysqli, "SELECT pr.catatan, pr.biaya_periksa, pr.tanggal_periksa, o.nama_obat
                                           FROM periksa pr
                                           LEFT JOIN detail_periksa dp ON pr.id = dp.id_periksa
                                           LEFT JOIN obat o ON dp.id_obat = o.id
                                           WHERE pr.id_daftar_poli = '$id_daftar_poli'");
    
    // Ambil data pasien
    $dataPasien = mysqli_fetch_assoc($queryPasien);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pasien - <?php echo $dataPasien['nama']; ?></title>
    <!-- Tambahkan link ke CSS atau framework CSS yang Anda gunakan -->
</head>
<body>

    <h2>Riwayat Pasien - <?php echo $dataPasien['nama']; ?></h2>
    <p>Alamat: <?php echo $dataPasien['alamat']; ?></p>

    <h3>Riwayat Periksa</h3>
    <table border="1">
        <tr>
            <th>Catatan</th>
            <th>Biaya Periksa</th>
            <th>Tanggal Periksa</th>
            <th>Obat</th>
        </tr>
        <?php
        while ($dataRiwayat = mysqli_fetch_assoc($queryRiwayat)) {
            echo "<tr>";
            echo "<td>" . $dataRiwayat['catatan'] . "</td>";
            echo "<td>" . $dataRiwayat['biaya_periksa'] . "</td>";
            echo "<td>" . $dataRiwayat['tanggal_periksa'] . "</td>";
            echo "<td>" . $dataRiwayat['nama_obat'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <!-- Tambahkan tombol atau link kembali ke halaman sebelumnya -->
    <a href="javascript:history.back()">Kembali</a>

    <!-- Tambahkan script JS atau link ke JS jika diperlukan -->

</body>
</html>

<?php
} else {
    // Redirect atau tampilkan pesan jika ID tidak valid
    echo "<script> 
            alert('ID Daftar Poli tidak valid.');
            document.location='berandaDokter.php';
          </script>";
}
?>
