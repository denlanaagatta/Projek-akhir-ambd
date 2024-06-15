<?php 
session_start();
include '../koneksi/koneksi.php';

// Escape input pengguna untuk mencegah SQL Injection
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['pass']);

// Jalankan query untuk mendapatkan data pengguna berdasarkan username
$query = "SELECT * FROM customer WHERE username = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Periksa apakah pengguna ditemukan
if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    // Verifikasi password
    if (password_verify($password, $row['password'])) {
        // Password cocok, buat sesi dan arahkan ke halaman utama
        $_SESSION['user'] = $row['nama'];
        $_SESSION['kd_cs'] = $row['kode_customer'];
        header('Location: ../index.php');
        exit();
    } else {
        // Password tidak cocok
        echo "<script>alert('Username atau password salah'); window.location='../user_login.php';</script>";
        exit();
    }
} else {
    // Username tidak ditemukan
    echo "<script>alert('Username atau password salah'); window.location='../user_login.php';</script>";
    exit();
}

// Tutup statement dan koneksi
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
