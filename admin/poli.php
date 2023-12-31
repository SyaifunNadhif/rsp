<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    // Jika pengguna sudah login, tampilkan tombol "Logout"
    header("Location: index.php?page=loginUser");
    exit;
}

if (isset($_POST['simpan'])) {
    if (isset($_POST['id'])) {
        $ubah = mysqli_query($mysqli, "UPDATE poli SET 
                                            nama_poli = '" . $_POST['nama_poli'] . "',
                                            ket = '" . $_POST['ket'] . "'
                                            WHERE
                                            id = '" . $_POST['id'] . "'");
    } else {
        $tambah = mysqli_query($mysqli, "INSERT INTO poli (nama_poli, ket) 
                                            VALUES (
                                                '" . $_POST['nama_poli'] . "',
                                                '" . $_POST['ket'] . "'
                                            )");
    }
    echo "<script> 
                document.location='index.php?page=poli';
                </script>";
}
if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'hapus') {
        $hapus = mysqli_query($mysqli, "DELETE FROM poli WHERE id = '" . $_GET['id'] . "'");
    }

    echo "<script> 
                document.location='index.php?page=poli';
                </script>";
}
?>
<section class="container mx-auto mt-10">

    <h2 class="font-bold text-2xl">Poli</h2>
    <br>
    <div>
        <!--Form Input Data-->

        <form class="form flex flex-col w-1/4 gap-3" method="POST" action="" name="myForm" onsubmit="return(validate());">
            <!-- Kode php untuk menghubungkan form dengan database -->
            <?php
                $nama_poli = '';
                $ket = '';
                if (isset($_GET['id'])) {
                    $ambil = mysqli_query($mysqli, "SELECT * FROM poli 
                            WHERE id='" . $_GET['id'] . "'");
                    while ($row = mysqli_fetch_array($ambil)) {
                        $nama_poli = $row['nama_poli'];
                        $ket = $row['ket'];
                    }
            ?>
                <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
            <?php
            }
            ?>
            <div class="flex flex-col gap-2">
                <label for="inputPoli" class="form-label fw-bold">
                    Poli
                </label>
                <div>
                    <input type="text" class="form-control w-full border border-neutral-300 rounded-md" name="nama_poli" id="inputPoli" placeholder="Poli" value="<?php echo $nama_poli ?>">
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label for="inputKet" class="form-label fw-bold">
                    Keterangan
                </label>
                <div>
                    <!-- <textarea name="" id="" cols="30" rows="10"></textarea> -->
                    <input type="text" class="form-control w-full border border-neutral-300 rounded-md" name="ket" id="inputKet" placeholder="Keterangan" value="<?php echo $ket ?>">
                </div>
            </div>
            <div class="row mt-3">
                <div class=col>
                    <button type="submit" class="bg-blue-700 hover:bg-blue-800 uppercase text-white w-full py-2 rounded-lg" name="simpan">Simpan</button>
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
                            #
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama Poli
                        </th>
                        <th scope="col" class="px-6 py-3 w-[800px]">
                            Keterangan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Kode PHP untuk menampilkan semua isi dari tabel urut-->
                    <?php
                    $result = mysqli_query($mysqli, "SELECT * FROM poli");
                    $no = 1;
                    while ($data = mysqli_fetch_array($result)) {
                    ?>
                        <tr>
                            <th scope="row"><?php echo $no++ ?></th>
                            <td class="px-6 py-4 text-left font-medium text-gray-900 whitespace-nowrap"><?php echo $data['nama_poli'] ?></td>
                            <td class="px-6 py-4 text-left"><?php echo $data['ket'] ?></td>
                            <td class="px-6 py-4 text-center">
                                <a class="font-medium text-blue-600 hover:underline" href="index.php?page=poli&id=<?php echo $data['id'] ?>">Ubah</a>
                                <a class="font-medium text-red-600 hover:underline" href="index.php?page=poli&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
</section>
