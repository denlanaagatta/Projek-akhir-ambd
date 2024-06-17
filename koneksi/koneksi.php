<?php
$conn = mysqli_connect("root", "localhost", "", "toko_sovenir");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
echo "Koneksi berhasil";
?>
