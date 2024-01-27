<?php
// Lakukan pengecekan koneksi ke database dan set session jika belum ada
if (!isset($_SESSION)) {
    session_start();
}


// Inisialisasi variabel $id_dokter
$id_dokter = null;

// Lakukan query untuk mendapatkan data dokter berdasarkan NIP yang sudah login
if (isset($_SESSION['nip'])) {
    $nip = $_SESSION['nip'];
    $query = "SELECT id FROM dokter WHERE nip = '$nip'";
    $result = $mysqli->query($query);

    if (!$result) {
        die("Query error: " . $mysqli->error);
    }

    // Ambil data dokter
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Tetapkan nilai $id_dokter jika data dokter ditemukan
        $id_dokter = $row['id'];
    } else {
        echo "Data dokter tidak ditemukan";
        exit();
    }
}

// Proses form jika data dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // Periksa apakah $id_dokter memiliki nilai sebelum menjalankan query
    if ($id_dokter !== null) {
        // Query untuk menyimpan jadwal periksa ke dalam tabel database
        $query = "INSERT INTO jadwal_periksa (id_dokter, hari, jam_mulai, jam_selesai) VALUES ('$id_dokter', '$hari', '$jam_mulai', '$jam_selesai')";

        if ($mysqli->query($query)) {
            echo '<script>alert("Jadwal periksa berhasil disimpan.");</script>';
            echo "<script> 
            document.location='berandaDokter.php?page=atur_jadwal';
            </script>";
        } else {
            echo '<script>alert("Error: ' . $query . '\n' . $mysqli->error . '");</script>';
        }
    } else {
        echo "ID Dokter tidak tersedia.";
    }
}
?>




<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<section>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Atur Jadwal Periksa</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="mb-3">
                                <label for="hari" class="form-label">Hari</label>
                                <select class="form-control" name="hari">
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jam_mulai" class="form-label">Jam Mulai</label>
                                <input type="time" class="form-control" name="jam_mulai" required>
                            </div>
                            <div class="mb-3">
                                <label for="jam_selesai" class="form-label">Jam Selesai</label>
                                <input type="time" class="form-control" name="jam_selesai" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                        <table class="table table-hover">
                            <!--thead atau baris judul-->
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Hari</th>
                                    <th scope="col">Jam Mulai</th>
                                    <th scope="col">Jam Selesai</th>
                                    <th scope="col">Aktif/Nonaktif</th>
                                    <th scope="col">Simpan</th>

                                </tr>
                            </thead>
                            <!--tbody berisi isi tabel sesuai dengan judul atau head-->
                            <tbody>
                                <?php
                                // Lakukan query untuk mengambil jadwal periksa dokter yang sedang login
                                $query_jadwal = "SELECT * FROM jadwal_periksa WHERE id_dokter = '$id_dokter'";
                                $result_jadwal = $mysqli->query($query_jadwal);

                                if (!$result_jadwal) {
                                    die("Query error: " . $mysqli->error);
                                }

                                if ($result_jadwal->num_rows > 0) {
                                    $count = 1;
                                    while ($row_jadwal = $result_jadwal->fetch_assoc()) {
                                        echo '<tr>';
                                        echo '<th scope="row">' . $count . '</th>';
                                        echo '<td>' . $row_jadwal['hari'] . '</td>';
                                        echo '<td>' . $row_jadwal['jam_mulai'] . '</td>';
                                        echo '<td>' . $row_jadwal['jam_selesai'] . '</td>';
                                        echo '<td>';
                                        echo '<form method="post" action="update_status.php">';
                                        echo '<input type="hidden" name="id" value="' . $row_jadwal['id'] . '">';
                                        echo '<input type="checkbox" id="aktif_' . $row_jadwal['id'] . '" name="status_jadwal" value="Y" ' . ($row_jadwal['status_jadwal'] == 'Y' ? 'checked' : '') . '> Aktif';
                                        echo '<input type="checkbox" id="nonaktif_' . $row_jadwal['id'] . '" name="status_jadwal" value="N" ' . ($row_jadwal['status_jadwal'] == 'N' ? 'checked' : '') . '> Nonaktif';
                                        echo '</td>';
                                        echo '<td>';
                                        echo '<button type="submit" name="update_status" class="btn btn-primary btn-sm">Simpan</button>';
                                        echo '</form>';
                                        echo '</td>';
                                        echo '</tr>';
                                        $count++;
                                    }
                                } else {
                                    echo '<tr><td colspan="5">Tidak ada jadwal periksa yang tersedia</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const rowId = this.id.split('_')[1]; // Mendapatkan ID baris
                checkboxes.forEach(cb => {
                    if (cb !== this && cb.id.split('_')[1] === rowId) {
                        cb.checked = false;
                    }
                });
            });
        });
    });
</script>

