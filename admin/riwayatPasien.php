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
<section class="container mx-auto mt-10">

    <h3 class="font-bold text-2xl py-4">Riwayat Pasien - <?php echo $dataPasien['nama']; ?></h3>
    <p class="text-xl py-4">Alamat: <?php echo $dataPasien['alamat']; ?></p>
      <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
          <table class="w-full text-sm text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Catatan 
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Biaya Periksa
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tanggal Periksa
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Obat
                        </th>
                    </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1; 
                  while ($dataRiwayat = mysqli_fetch_assoc($queryRiwayat)) {
                    ?>
                        <tr>
                            <th scope="row"><?php echo $no++ ?></th>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center"><?= $dataRiwayat['catatan'] ?></td>
                            <td class="px-6 py-4 text-center"><?= $dataRiwayat['biaya_periksa'] ?></td>
                            <td class="px-6 py-4 text-center"><?= $dataRiwayat['tanggal_periksa']  ?></td>
                            <td class="px-6 py-4 text-center"><?= $dataRiwayat['nama_obat']  ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
          </table>
      </div>
      <a class="font-medium text-red-600 hover:underline" href="javascript:history.back()">Periksa</a>
</section> 

<?php
} else {
    // Redirect atau tampilkan pesan jika ID tidak valid
    echo "<script> 
            alert('ID Daftar Poli tidak valid.');
            document.location='berandaDokter.php';
          </script>";
}
?>       