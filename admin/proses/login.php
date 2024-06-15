<?php
session_start();
include '../../koneksi/koneksi.php';

$username = $_POST['user'];
$password = $_POST['pass'];

// Cek user
$result = mysqli_query($conn, "SELECT * FROM ADMIN WHERE username = '$username'");
$row = mysqli_fetch_assoc($result);

if ($row) {
    $storedPassword = $row['password'];

    if (password_verify($password, $storedPassword)) {
        $_SESSION["admin"] = true;
        header('Location: ../halaman_utama.php');
    } else {
        echo "<script>
            alert('USERNAME/PASSWORD SALAH');
            window.location = '../index.php';
            </script>";
    }
} else {
    echo "<script>
        alert('USERNAME/PASSWORD SALAH');
        window.location = '../index.php';
        </script>";
}

mysqli_close($conn);
?>
