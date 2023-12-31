<?php
if (!isset($_SESSION)) {
    session_start();
}



if (isset($_POST['simpan'])) {
    if (isset($_POST['id'])) {

        

        $tambah = mysqli_query($mysqli, "INSERT INTO pasien (id_pasien, id_jadwal, keluhan, antrian) 
                                            VALUES (
                                                '" . $_POST['id_pasien'] . "',
                                                '" . $_POST['id_jadwal'] . "',
                                                '" . $_POST['keluhan'] . "',
                                                '" . $_POST['antrian'] . "'
                                            )");
    }
    echo "<script> 
                document.location='index.php?page=pasienLama';
                </script>";
}

?>
<h2 class="mt-5">Daftar Poli</h2>
<br>
<div class="container">
    <!--Form Input Data-->

    <form class="form row" method="POST" action="" name="myForm" onsubmit="return(validate());">

            <input type="hidden" name="id">
        <div class="row">
            <label for="inputid_pasien" class="form-label fw-bold">
                No. RM
            </label>
            <div>
                <input type="text" class="form-control" name="id_pasien" id="inputid_pasien" placeholder="No RM">
        </div>
        </div>
        <div class="row mt-1">
            <label for="inputid_jadwal" class="form-label fw-bold">
                Jadwal
            </label>
            <div>
                <!-- <textarea name="" id="" cols="30" rows="10"></textarea> -->
                <input type="text" class="form-control" name="id_jadwal" id="inputid_jadwal" placeholder="Jadwal">
            </div>
        </div>
        <div class="row mt-1">
            <label for="inputNIK" class="form-label fw-bold">
                Keluhan
            </label>
            <div>
                <!-- <textarea name="" id="" cols="30" rows="10"></textarea> -->
                <input type="text" class="form-control" name="keluhan" id="inputNIK" placeholder="Keluhan">
            </div>

        </div>
    
        <div class="row mt-3">
            <div class=col>
                <button type="submit" class="btn btn-primary rounded-pill px-3 mt-auto" name="simpan">Simpan</button>
            </div>
        </div>
    </form>