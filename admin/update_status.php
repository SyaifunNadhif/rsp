<?php
// Lakukan pengecekan koneksi ke database dan set session jika belum ada
if (!isset($_SESSION)) {
    session_start();
}

// Sisipkan file koneksi.php
include_once("../koneksi.php");

// Pastikan form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    // Ambil nilai id dan status_jadwal dari form
    $id = $_POST['id'];
    $status_jadwal = $_POST['status_jadwal'];

    // Query untuk mengupdate status_jadwal di tabel jadwal_periksa
    $query = "UPDATE jadwal_periksa SET status_jadwal = '$status_jadwal' WHERE id = '$id'";

    if ($mysqli->query($query)) {
        echo '<script>alert("Status jadwal berhasil diperbarui.");</script>';
    } else {
        echo '<script>alert("Error: ' . $query . '\n' . $mysqli->error . '");</script>';
    }
}
// Redirect ke halaman sebelumnya (mungkin halaman atur_jadwal.php)
header("Location: atur_jadwal.php");
exit();
?>