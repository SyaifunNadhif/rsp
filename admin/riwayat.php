<?php
// Include your database connection file here
if (!isset($_SESSION)) {
  session_start();
}

?>
<section class="container mx-auto mt-10">

    <h3 class="font-bold text-2xl py-4">Riwayat Pasien</h3>

      <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
          <table class="w-full text-sm text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama Pasien 
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Keluhan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Catatan 
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Biaya Periksa
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Hari
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
                    $id_dokter = $_SESSION['id'];
                    $result = mysqli_query($mysqli, "
                                        SELECT daftar_poli.*, pasien.nama AS nama, jadwal_periksa.hari, periksa.tanggal_periksa, periksa.catatan, periksa.biaya_periksa, obat.nama_obat AS nama_obat
                                        FROM daftar_poli
                                        JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id 
                                        JOIN pasien ON daftar_poli.id_pasien = pasien.id
                                        LEFT JOIN periksa ON daftar_poli.id = periksa.id_daftar_poli
                                        LEFT JOIN detail_periksa ON periksa.id = detail_periksa.id_periksa
                                        LEFT JOIN obat ON detail_periksa.id_obat = obat.id
                                        WHERE jadwal_periksa.id_dokter = '$id_dokter' AND periksa.id_daftar_poli IS NOT NULL
                                    ");
                    $no = 1;
                    while ($data = mysqli_fetch_array($result)) :
                    ?>
                        <tr>
                            <th scope="row"><?php echo $no++ ?></th>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center"><?= $data['nama'] ?></td>
                            <td class="px-6 py-4 text-center"><?= $data['keluhan'] ?></td>
                            <td class="px-6 py-4 text-center"><?= $data['catatan']  ?></td>
                            <td class="px-6 py-4 text-center"><?= $data['biaya_periksa'] ?></td>
                            <td class="px-6 py-4 text-center"><?= $data['hari']  ?></td>
                            <td class="px-6 py-4 text-center"><?= $data['tanggal_periksa']  ?></td>
                            <td class="px-6 py-4 text-center"><?= $data['nama_obat']  ?></td>
                        </tr>
                    <?php
                    endwhile;
                    ?>
                </tbody>
          </table>
      </div>
</section> 


     