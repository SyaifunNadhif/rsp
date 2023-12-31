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
        $ubah = mysqli_query($mysqli, "UPDATE obat SET 
                                            nama_obat = '" . $_POST['nama_obat'] . "',
                                            kemasan = '" . $_POST['kemasan'] . "',
                                            harga = '" . $_POST['harga'] . "'
                                            WHERE
                                            id = '" . $_POST['id'] . "'");
    } else {
        $tambah = mysqli_query($mysqli, "INSERT INTO obat (nama_obat, kemasan, harga) 
                                            VALUES (
                                                '" . $_POST['nama_obat'] . "',
                                                '" . $_POST['kemasan'] . "',
                                                '" . $_POST['harga'] . "'
                                            )");
    }
    echo "<script> 
                document.location='index.php?page=obat';
                </script>";
}
if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'hapus') {
        $hapus = mysqli_query($mysqli, "DELETE FROM obat WHERE id = '" . $_GET['id'] . "'");
    }

    echo "<script> 
                document.location='index.php?page=obat';
                </script>";
}
?>
<section class="container mt-10 mx-auto">
    <h2 class="font-bold text-2xl">Obat</h2>
    <br>
    <div class="">
        <!--Form Input Data-->
    
        <form class="form w-1/4 flex flex-col gap-3" method="POST" action="" name="myForm" onsubmit="return(validate());">
            <!-- Kode php untuk menghubungkan form dengan database -->
            <?php
            $nama_obat = '';
            $kemasan = '';
            $harga = '';
            if (isset($_GET['id'])) {
                $ambil = mysqli_query($mysqli, "SELECT * FROM obat 
                        WHERE id='" . $_GET['id'] . "'");
                while ($row = mysqli_fetch_array($ambil)) {
                    $nama_obat = $row['nama_obat'];
                    $kemasan = $row['kemasan'];
                    $harga = $row['harga'];
                }
            ?>
                <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
            <?php
            }
            ?>
            <div class="flex flex-col gap-2">
                <label for="inputNama" class="form-label fw-bold">
                    Nama
                </label>
                <div>
                    <input type="text" class="form-control w-full border border-neutral-300 rounded-md" name="nama_obat" id="inputNama" placeholder="Nama" value="<?php echo $nama_obat ?>">
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label for="inputKemasan" class="form-label fw-bold">
                    Kemasan
                </label>
                <div>
                    <input type="text" class="form-control w-full border border-neutral-300 rounded-md" name="kemasan" id="inputKemasan" placeholder="Kemasan" value="<?php echo $kemasan ?>">
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label for="inputHarga" class="form-label fw-bold">
                    Harga
                </label>
                <div>
                    <input type="text" class="form-control w-full border border-neutral-300 rounded-md" name="harga" id="inputHarga" placeholder="Harga" value="<?php echo $harga ?>">
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
                            #
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama Obat
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Kemasan
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Harga
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Kode PHP untuk menampilkan semua isi dari tabel urut-->
                    <?php
                    $result = mysqli_query($mysqli, "SELECT * FROM obat");
                    $no = 1;
                    while ($data = mysqli_fetch_array($result)) {
                    ?>
                        <tr>
                            <th scope="row"><?php echo $no++ ?></th>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"><?php echo $data['nama_obat'] ?></td>
                            <td class="px-6 py-4 text-center"><?php echo $data['kemasan'] ?></td>
                            <td class="px-6 py-4 text-center"><?php echo $data['harga'] ?></td>
                            <td class="px-6 py-4 text-center">
                                <a class="font-medium text-blue-600 hover:underline" href="index.php?page=obat&id=<?php echo $data['id'] ?>">Ubah</a>
                                <a class="font-medium text-red-600 hover:underline" href="index.php?page=obat&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
</section>