<?php
if (!isset($_SESSION)) {
    session_start();
}

function generateNoRM($totalPasien) {
    $currentMonth = date('Ym');
    $nextNoRM = str_pad($totalPasien + 1, 3, '0', STR_PAD_LEFT);
    return $currentMonth . '-' . $nextNoRM;
}

if (isset($_POST['simpan'])) {
    if (isset($_POST['id'])) {

        // Ambil nilai total pasien dari hasil query
        $result = mysqli_query($mysqli, "SELECT COUNT(*) AS total_pasien FROM pasien");
        $row = mysqli_fetch_assoc($result);
        $totalPasien = $row['total_pasien'];

        $noRM = generateNoRM($totalPasien);

        $tambah = mysqli_query($mysqli, "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) 
                                            VALUES (
                                                '" . $_POST['nama'] . "',
                                                '" . $_POST['alamat'] . "',
                                                '" . $_POST['no_ktp'] . "',
                                                '" . $_POST['no_hp'] . "',
                                                '" . $noRM . "'
                                            )");
    }
    echo "<script> 
                document.location='index.php?page=pasienLama';
                </script>";
}

?>

<section class="py-[3rem]">
    <h2 class="font-bold text-2xl">Pasien Baru</h2>
    <br>
    <div class="">
        <!--Form Input Data-->
        
        <form class="form flex flex-col gap-4 w-60" method="POST" action="" name="myForm" onsubmit="return(validate());">
            
            <input type="hidden" name="id">
            <div class="flex flex-col gap-2">
                <label for="inputNama" class="form-label fw-bold">
                    Nama
                </label>
                <div>
                    <input type="text" class="border border-neutral-300 rounded-md w-full" required name="nama" id="inputNama" placeholder="Nama Lengkap">
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label for="inputAlamat" class="form-label fw-bold">
                    Alamat
                </label>
                <div>
                    <!-- <textarea name="" id="" cols="30" rows="10"></textarea> -->
                    <input type="text" class="border border-neutral-300 rounded-md w-full" required name="alamat" id="inputAlamat" placeholder="Alamat">
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label for="inputNIK" class="form-label fw-bold">
                    NIK
                </label>
                <div>
                    <!-- <textarea name="" id="" cols="30" rows="10"></textarea> -->
                    <input type="text" class="border border-neutral-300 rounded-md w-full" required name="no_ktp" id="inputNIK" placeholder="NIK">
                </div>
            </div>
            <div class="flex flex-col gap-2">
                <label for="inputTelepon" class="form-label fw-bold">
                    Telepon
                </label>
                <div>
                    <input type="text" class="border border-neutral-300 rounded-md w-full" required name="no_hp" id="inputTelepon" placeholder="Telepon">                    
                </div>
            </div>
            
        <div class="mt-3">
            <div class=col>
                <button type="submit" class="text-white font-bold uppercase w-full bg-blue-700 hover:bg-blue-800 py-3 rounded-lg" name="simpan">Simpan</button>
            </div>
        </div>
    </form>
</section>