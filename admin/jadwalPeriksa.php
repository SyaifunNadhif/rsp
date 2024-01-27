<?php
if (!isset($_SESSION)) {
    session_start();
}


if (isset($_POST['simpan'])) {
    if (isset($_POST['id'])) {
        $ubah = mysqli_query($mysqli, "UPDATE jadwal_periksa SET 
                                            hari = '" . $_POST['hari'] . "',
                                            jam_mulai = '" . $_POST['jam_mulai'] . "',
                                            harga = '" . $_POST['harga'] . "',
                                            
                                            WHERE
                                            id = '" . $_POST['id'] . "'");
    } else {
        $tambah = mysqli_query($mysqli, "INSERT INTO jadwal_periksa (hari, jam_mulai, harga) 
                                            VALUES (
                                                '" . $_POST['hari'] . "',
                                                '" . $_POST['jam_mulai'] . "',
                                                '" . $_POST['harga'] . "'
                                            )");
    }
    echo "<script> 
                document.location='berandaDokter.php?page=atur_jadwal';
                </script>";

}
if ($_GET['aksi'] == 'hapus') {
    $hapus = mysqli_query($mysqli, "DELETE FROM dokter WHERE id = '" . $_GET['id'] . "'");
}

echo "<script> 
            document.location='index.php?page=dokter';
            </script>";

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $newStatus = ($_POST['status'] == 'ya') ? 'tidak' : 'ya';

    $updateQuery = "UPDATE jadwal_periksa SET aktif = '$newStatus' WHERE id = '$id'";
    $ubah = mysqli_query($mysqli, $updateQuery);

    if ($ubah) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}

?>
<section class="container mt-10 mx-auto">
    <h2 class="font-bold text-2xl">Jadwal Dokter</h2>
    <br>
    <div class="">
        <!--Form Input Data-->
    
        <form class="form w-1/4 flex flex-col gap-3" method="POST" action="" name="myForm" onsubmit="return(validate());">
            <!-- Kode php untuk menghubungkan form dengan database -->

            <input type="hidden" name="id">
            <input type="hidden" name="id_dokter" value="<?php $_SESSION['id'] ?>">


            <div class="flex flex-col gap-2">
                <label for="inputHari" class="form-label fw-bold">
                    Hari
                </label>
                <div>
                    <input type="text" class="form-control w-full border border-neutral-300 rounded-md" name="hari" id="inputHari" placeholder="Hari" >
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label for="inputJamMulai" class="form-label fw-bold">
                    Jam Mulai
                </label>
                <div>
                    <input type="text" class="form-control w-full border border-neutral-300 rounded-md" name="jam_mulai" id="inputJamMulai" placeholder="Jam Mulai" >
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label for="inputJamSelesai" class="form-label fw-bold">
                    Jam Selesai
                </label>
                <div>
                    <input type="text" class="form-control w-full border border-neutral-300 rounded-md" name="jam_selesai" id="inputJamSelesai" placeholder="Jam Selesai" >
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label for="inputAktif" class="form-label fw-bold">
                    Aktif
                </label>
                <div>
                    <input type="text" class="form-control w-full border border-neutral-300 rounded-md" name="aktif" id="inputAktif" placeholder="Aktif" >
                </div>
            </div>
            <div class="row mt-3">
                <div class=col>
                    <button type="submit" class="bg-blue-700 hover:bg-blue-800 uppercase text-white py-2 w-full font-bold rounded-lg" name="simpan">Simpan</button>
                </div>
            </div>
        </form>
        <br>
        <br>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama Dokter
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Hari
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jam Mulai
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Jam Selesai
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Aktif
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
                    $result = mysqli_query($mysqli, "SELECT jadwal_periksa.*, dokter.nama
                                                    FROM jadwal_periksa
                                                    INNER JOIN dokter ON jadwal_periksa.id_dokter = dokter.id");

                    $no = 1;
                    while ($data = mysqli_fetch_array($result)) {
                    ?>
                        <tr>
                            <th scope="row"><?php echo $no++ ?></th>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"><?php echo $data['nama'] ?></td>
                            <td class="px-6 py-4 text-center"><?php echo $data['hari'] ?></td>
                            <td class="px-6 py-4 text-center"><?php echo $data['jam_mulai'] ?></td>
                            <td class="px-6 py-4 text-center"><?php echo $data['jam_selesai'] ?></td>
                            <td class="px-6 py-4 text-center">
                                <?php
                                if ($data['aktif'] == 'ya') {
                                    echo '<span class="text-green-600">Aktif</span>';
                                } else {
                                    echo '<span class="text-red-600">Nonaktif</span>';
                                }
                                ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a class="font-medium text-blue-600 hover:underline" href="index.php?page=jadwalPeriksa&id=<?php echo $data['id'] ?>&aksi=status">
                                    <?php
                                    if ($data['aktif'] == 'ya') {
                                        echo 'Nonaktif';
                                    } else {
                                        echo 'Aktif';
                                    }
                                    ?>
                                </a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>
            <br><br>
        </div>
</section>