<?php
$conn = mysqli_connect("152.67.198.76", "220441100118", "Pw@22118", "DB220441100118");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
echo "Koneksi berhasil";
?>
