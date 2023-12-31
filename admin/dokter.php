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
    // update
    if (isset($_POST['id'])) {
        $nip_value = mysqli_real_escape_string($mysqli, $_POST['nip']);

        $ubah = mysqli_query($mysqli, "UPDATE dokter SET 
                                        nama = '" . mysqli_real_escape_string($mysqli, $_POST['nama']) . "',
                                        alamat = '" . mysqli_real_escape_string($mysqli, $_POST['alamat']) . "',
                                        no_hp = '" . mysqli_real_escape_string($mysqli, $_POST['no_hp']) . "',
                                        id_poli = '" . mysqli_real_escape_string($mysqli, $_POST['id_poli']) . "',
                                        nip = '" . $nip_value . "'
                                        WHERE
                                        id = '" . mysqli_real_escape_string($mysqli, $_POST['id']) . "'");
    } else {
    // create
        $tambah = mysqli_query($mysqli, "INSERT INTO dokter (nama, alamat, no_hp, id_poli, nip) 
                                            VALUES (
                                                '" . $_POST['nama'] . "',
                                                '" . $_POST['alamat'] . "',
                                                '" . $_POST['no_hp'] . "',
                                                '" . $_POST['id_poli'] . "',
                                                '" . $_POST['nip'] . "'
                                            )");
    }
    echo "<script> 
                document.location='index.php?page=dokter';
                </script>";
}
if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'hapus') {
        $hapus = mysqli_query($mysqli, "DELETE FROM dokter WHERE id = '" . $_GET['id'] . "'");
    }

    echo "<script> 
                document.location='index.php?page=dokter';
                </script>";
}
?>
<section class="container mx-auto mt-10">
    <h2 class="font-bold text-2xl">Dokter</h2>
    <br>
    <div class="">
        <!--Form Input Data-->
    
        <form class="form flex flex-col gap-3 w-1/4" method="POST" action="" name="myForm" onsubmit="return(validate());">
            <!-- Kode php untuk menghubungkan form dengan database -->
            <?php
            $nama = '';
            $alamat = '';
            $no_hp = '';
            $id_poli;
            $nip= '';
            $nama_poli= '';
            if (isset($_GET['id'])) {
                $ambil = mysqli_query($mysqli, "SELECT dokter.*, poli.nama_poli 
                            FROM dokter 
                            LEFT JOIN poli ON dokter.id_poli = poli.id
                            WHERE dokter.id='" . $_GET['id'] . "'");
                while ($row = mysqli_fetch_array($ambil)) {
                    $nama = $row['nama'];
                    $alamat = $row['alamat'];
                    $no_hp = $row['no_hp'];
                    $id_poli = $row['id_poli'];
                    $nama_poli = $row['nama_poli'];
                    $nip = $row['nip'];
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
                    <input type="text" class="form-control w-full border border-neutral-300 rounded-md" name="nama" id="inputNama" placeholder="Nama" value="<?php echo $nama ?>">
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label for="inputAlamat" class="form-label fw-bold">
                    Alamat
                </label>
                <div>
                    <input type="text" class="form-control w-full border border-neutral-300 rounded-md" name="alamat" id="inputAlamat" placeholder="alamat" value="<?php echo $alamat ?>">
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label for="inputNoHp" class="form-label fw-bold">
                    Telepon
                </label>
                <div>
                    <input type="text" class="form-control w-full border border-neutral-300 rounded-md" name="no_hp" id="inputNoHp" placeholder="Telepon" value="<?php echo $no_hp ?>">
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label for="inputNip" class="form-label fw-bold">
                    NIP
                </label>
                <div>
                    <input type="text" class="form-control w-full border border-neutral-300 rounded-md" name="nip" id="inputNip" placeholder="NIP" value="<?php echo $nip ?>">
                </div>
            </div>
    
            <!-- Tambahkan input tersembunyi untuk id_poli -->
            <input type="hidden" name="id_poli" id="inputIdPoli" value="<?php echo $id_poli; ?>">

            <div class="flex flex-col gap-2">
                <label for="selectPoli">
                    Poli
                </label>
                <select class="py-2 px-3 border border-neutral-300 rounded-md" name="id_poli" id="selectPoli">
                    <option value="">-- Pilih Poli --</option>
                    <?php
                    $result = mysqli_query($mysqli, "SELECT * FROM poli");
                    while ($data = mysqli_fetch_array($result)) {
                        $id_poli_option = $data['id'];
                        $nama_poli = $data['nama_poli'];
                        $selected = ($id_poli_option == $id_poli) ? 'selected' : ''; // menetapkan 'selected' jika nilainya sama dengan $id_poli
                    ?>
                        <option value="<?php echo $id_poli_option ?>" <?php echo $selected ?>><?php echo "Poli " . $nama_poli ?></option>
                    <?php } ?>
                </select>
            </div>
    
            <div class="row mt-3">
                <div class=col>
                    <button type="submit" class="bg-blue-700 hover:bg-blue-800 uppercase text-white py-2 w-full font-bold rounded-lg" name="simpan">Simpan</button>
                </div>
            </div>
            <script>
                function selectPoli(idPoli, poliName) {
                    document.getElementById('selectedPoli').textContent = "Poli " + poliName;
                    document.getElementById('selectedPoli').dataset.poliId = idPoli; // Menambahkan data-poli-id ke tombol
                    document.getElementById('inputIdPoli').value = idPoli; // Menetapkan nilai id_poli ke input tersembunyi
                }
                
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
                            #
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nama 
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Alamat 
                        </th>
                        <th scope="col" class="px-6 py-3">
                            No HP
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Poli
                        </th>
                        <th scope="col" class="px-6 py-3">
                            NIP
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Kode PHP untuk menampilkan semua isi dari tabel urut-->
                    <?php
                    $result = mysqli_query($mysqli, "SELECT dokter.*, poli.nama_poli 
                    FROM dokter 
                    LEFT JOIN poli ON dokter.id_poli = poli.id");
                    $no = 1;
                    while ($data = mysqli_fetch_array($result)) {
                    ?>
                        <tr>
                            <th scope="row"><?php echo $no++ ?></th>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"><?php echo $data['nama'] ?></td>
                            <td class="px-6 py-4 text-left"><?php echo $data['alamat'] ?></td>
                            <td class="px-6 py-4 text-left"><?php echo $data['no_hp'] ?></td>
                            <td class="px-6 py-4 text-left"><?php echo $data['nama_poli'] ?></td>
                            <td class="px-6 py-4 text-left"><?php echo $data['nip'] ?></td>
                            <td class="px-6 py-4 text-center">
                                <a class="font-medium text-blue-600 hover:underline" href="index.php?page=dokter&id=<?php echo $data['id'] ?>">Ubah</a>
                                <a class="font-medium text-red-600 hover:underline" href="index.php?page=dokter&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
</section>    
