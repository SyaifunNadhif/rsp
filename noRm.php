<?php
if (!isset($_SESSION)) {
    session_start();
}



if (isset($_POST['cari'])) {
    $nama = $_POST['nama'];

    // Query untuk mencari no_rm berdasarkan nama
    $query = "SELECT no_rm FROM pasien WHERE nama LIKE '%$nama%'";
    $result = $mysqli->query($query);

    // Periksa apakah ada hasil
    if ($result->num_rows > 0) {
        // Ambil hasil query
        $row = $result->fetch_assoc();
        $noRM = $row['no_rm'];
        echo "Nomor RM untuk $nama adalah: $noRM";
    } else {
        echo "Tidak ditemukan nomor RM untuk $nama";
    }

}

?>
<h2 class="mt-5">Cek No Rekam Medis (NO.RM)</h2>
<br>
<div class="container">
    <!--Form Input Data-->

    <form class="form row" method="POST" action="" name="myForm" onsubmit="return(validate());">

            <input type="hidden" name="id">
        <div class="row">
            <label for="inputNama" class="form-label fw-bold">
                Nama Lengkap
            </label>
            <div>
                <input type="text" class="form-control" name="nama" id="inputNama" placeholder="Nama Lengkap">
            </div>
        </div>
    
        <div class="row mt-3">
            <div class=col>
                <button type="submit" class="btn btn-primary rounded-pill px-3 mt-auto" name="cari">Search</button>
            </div>
        </div>
    </form>

    
