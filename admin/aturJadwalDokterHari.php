<?php
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST['simpanData'])) {
    $valid_days = ["Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"];
    $id_dokter = $_SESSION['id'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $status_jadwal = isset($_POST['status_jadwal']) ? $_POST['status_jadwal'] : 0;

    if (!in_array($hari, $valid_days)) {
        echo "<script> 
            alert('Invalid day value.');
            document.location='berandaDokter.php?page=aturJadwalDokter';
        </script>";
        exit;
    }

    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $mysqli->prepare("UPDATE jadwal_periksa SET id_dokter=?, hari=?, jam_mulai=?, jam_selesai=?, status_jadwal=? WHERE id=?");
        $stmt->bind_param("isssii", $id_dokter, $hari, $jam_mulai, $jam_selesai, $status_jadwal, $id);

        if ($stmt->execute()) {
            // Disable other schedules on the same day if the current schedule is active
            if ($status_jadwal == 0) {
                disableOtherSchedulesOnSameDay($id_dokter, $hari, $id);
            }

            echo "
                <script> 
                    alert('Berhasil mengubah data.');
                    document.location='berandaDokter.php?page=aturJadwalDokter';
                </script>
            ";
        } else {
            // Handle error
        }

        $stmt->close();
    } else {
        $stmt = $mysqli->prepare("INSERT INTO jadwal_periksa (id_dokter, hari, jam_mulai, jam_selesai, status_jadwal) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $id_dokter, $hari, $jam_mulai, $jam_selesai, $status_jadwal);

        if ($stmt->execute()) {
            // Disable other schedules on the same day if the current schedule is active
            if ($status_jadwal == 0) {
                disableOtherSchedulesOnSameDay($id_dokter, $hari, $stmt->insert_id);
            }

            echo "
                <script> 
                    alert('Berhasil menambah data.');
                    document.location='berandaDokter.php?page=aturJadwalDokter';
                </script>
            ";
        } else {
            // Handle error
        }

        $stmt->close();
    }
}

if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'hapus') {
        $stmt = $mysqli->prepare("DELETE FROM jadwal_periksa WHERE id = ?");
        $stmt->bind_param("i", $_GET['id']);

        if ($stmt->execute()) {
            echo "
                <script> 
                    alert('Berhasil menghapus data.');
                    document.location='berandaDokter.php?page=aturJadwalDokter';
                </script>
            ";
        } else {
            echo "
                <script> 
                    alert('Gagal menghapus data: " . mysqli_error($mysqli) . "');
                    document.location='berandaDokter.php?page=aturJadwalDokter';
                </script>
            ";
        }

        $stmt->close();
    }
}

function disableOtherSchedulesOnSameDay($id_dokter, $hari, $currentScheduleId) {
    global $mysqli;

    // Nonaktifkan semua jadwal pada hari yang sama, kecuali yang sedang diupdate
    $stmt = $mysqli->prepare("UPDATE jadwal_periksa SET status_jadwal = CASE WHEN id = ? THEN 0 ELSE 1 END WHERE id_dokter = ? AND hari = ?");
    $stmt->bind_param("iis", $currentScheduleId, $id_dokter, $hari);
    $stmt->execute();
    $stmt->close();
}
?>
<!-- ... Bagian HTML yang sudah ada ... -->

<!-- ... Bagian HTML yang sudah ada ... -->


<main id="aturJadwalDokter-page">
    <div class="container" style="margin-top: 5.5rem;">
        <div class="row justify-content-center">
            <h2 class="ps-0 text-center">Jadwal Praktik Saya</h2>
            <!-- <div class="d-flex justify-content-end pe-0">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahDokter">
                    <i class="fa-regular fa-plus"></i> Tambah
                </button>
            </div> -->
            <div class="card">
                <div class="card-header text-center fw-bold" style="font-size: 1.5rem;">Jadwal</div>
                <div class="card-body ms-lg-5 me-lg-5 justify-content-center">
                    <form action="" method="POST" onsubmit="return(validate());">
                        <?php
                        $id_dokter = '';
                        $hari = '';
                        $jam_mulai = '';
                        $jam_selesai = '';
                        $status_jadwal = '';
                        if (isset($_GET['id'])) {
                            $get = mysqli_query($mysqli, "SELECT * FROM jadwal_periksa 
                                    WHERE id='" . $_GET['id'] . "'");
                            while ($row = mysqli_fetch_array($get)) {
                                $id_dokter = $row['id_dokter'];
                                $hari = $row['hari'];
                                $jam_mulai = $row['jam_mulai'];
                                $jam_selesai = $row['jam_selesai'];
                                $status_jadwal = $row['status_jadwal'];
                            }
                        ?>
                            <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                        <?php
                        }
                        ?>
                        <div class="dropdown mb-3 w-50">
                            <label for="id_dokter">Dokter <span class="text-danger">*</span></label>
                            <select disabled class="form-select" name="id_dokter" aria-label="id_dokter">
                                <option value="" selected>Pilih Dokter...</option>
                                <?php
                                    $id_dokter = $_SESSION['id'];
        
                                    $result = mysqli_query($mysqli, "SELECT * FROM dokter WHERE id");
        
                                    while($data = mysqli_fetch_assoc($result)) {
                                        $selected = ($data['id'] == $id_dokter) ? 'selected' : ''; // If the doctor id matches the session id, mark it as selected
                                        echo "<option $selected value='" . $data['id'] . "'>" . $data['nama'] . "</option>";
                                    }
                                ?>
                                
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="dropdown mb-3">
                                    <label for="hari">Hari <span class="text-danger">*</span></label>
                                    <select class="form-select" name="hari" aria-label="hari">
                                        <option value="" selected>Pilih Hari...</option>
                                        <?php
                                            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                            foreach ($days as $day) {
                                                $selected = ($day == $hari) ? 'selected' : '';
                                                echo "<option value='$day' $selected>$day</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="dropdown mb-3">
                                    <label for="status_jadwal">Status Jadwal <span class="text-danger">*</span></label>
                                    <select class="form-select" name="status_jadwal" aria-label="status_jadwal">
                                        <option value="" selected>Pilih Status...</option>
                                        <?php
                                            $statuses = ['0' => 'Aktif', '1' => 'Nonaktif'];
                                            foreach ($statuses as $status => $statusName) {
                                                $selected = ($status == $status_jadwal) ? 'selected' : '';
                                                echo "<option value='$status' $selected>$statusName</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="jam_mulai">Jam Mulai <span class="text-danger">*</span></label>
                                    <input type="time" name="jam_mulai" class="form-control" required value="<?php echo $jam_mulai ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="jam_selesai">Jam Selesai <span class="text-danger">*</span></label>
                                    <input type="time" name="jam_selesai" class="form-control" required value="<?php echo $jam_selesai ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-2">
                            <button type="submit" name="simpanData" class="btn btn-primary">Simpan</button>
                        </div>
        
                    </form>
                </div>
            </div>
            <div class="container">
            </div>

            <div class="table-responsive mt-3 px-0">
                <table class="table text-center">
                    <thead class="table-primary">
                        <tr>
                            <th valign="middle">No</th>
                            <th valign="middle">Nama Dokter</th>
                            <th valign="middle">Hari</th>
                            <th valign="middle" style="width: 25%;" colspan="2">Waktu</th>
                            <th valign="middle">Status Jadwal</th>
                            <!-- <th valign="middle">Jam Selesai</th> -->
                            <th valign="middle" style="width: 0.5%;" colspan="2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $id_dokter = $_SESSION['id'];

                            $result = mysqli_query($mysqli, "SELECT dokter.nama, jadwal_periksa.id, jadwal_periksa.hari, jadwal_periksa.jam_mulai, jadwal_periksa.jam_selesai, jadwal_periksa.status_jadwal 
                            FROM dokter 
                            JOIN jadwal_periksa ON dokter.id = jadwal_periksa.id_dokter 
                            WHERE dokter.id = $id_dokter");
                            $no = 1;
                            while ($data = mysqli_fetch_array($result)) :
                            ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $data['nama'] ?></td>
                                    <td><?php echo $data['hari'] ?></td>
                                    <td><?php echo $data['jam_mulai'] ?> WIB</td>
                                    <td><?php echo $data['jam_selesai'] ?> WIB</td>
                                    <td>
                                        <?php 
                                            echo ($data['status_jadwal'] == 0) 
                                            ? '<span class="bg-success badge text-white border rounded px-4 py-2 mb-0">Aktif</span>' 
                                            : '<span class="bg-danger badge text-white border rounded px-3 py-2 mb-0">Nonaktif</span>'; 
                                        ?>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-warning text-white btn-edit" href="berandaDokter.php?page=aturJadwalDokter&id=<?php echo $data['id'] ?>">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="berandaDokter.php?page=aturJadwalDokter&id=<?php echo $data['id'] ?>&aksi=hapus" class="btn btn-sm btn-danger text-white">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- <script>
        const btnEdit = document.querySelector('.btn-edit');
        const dropdownStatus = document.querySelector('select[name="status_jadwal"]');

        btnEdit.addEventListener('click', function(e) {
            e.preventDefault();
            // console.log('test');
            dropdownStatus.disabled = false;
        });
    </script> -->
</main>