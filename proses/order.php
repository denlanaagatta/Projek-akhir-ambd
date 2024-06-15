<?php
include '../koneksi/koneksi.php';

// Ambil data dari form checkout
$kd_cs = $_POST['kode_cs'];
$nama = $_POST['nama'];
$prov = $_POST['prov'];
$kota = $_POST['kota'];
$alamat = $_POST['almt'];
$kopos = $_POST['kopos'];

// Query untuk mendapatkan invoice terbaru
$kode = mysqli_query($conn, "SELECT invoice FROM pesanan ORDER BY invoice DESC");
$data = mysqli_fetch_assoc($kode);
$num = substr($data['invoice'], 3, 4);
$add = (int) $num + 1;
if (strlen($add) == 1) {
    $format = "INV000" . $add;
} else if (strlen($add) == 2) {
    $format = "INV00" . $add;
} else if (strlen($add) == 3) {
    $format = "INV0" . $add;
} else {
    $format = "INV" . $add;
}

// Ambil data dari keranjang untuk user yang sedang bertransaksi
$keranjang = mysqli_query($conn, "SELECT * FROM keranjang WHERE kode_customer = '$kd_cs'");

while ($row = mysqli_fetch_assoc($keranjang)) {
    $kd_produk = $row['kode_produk'];
    $nama_produk = $row['nama_produk'];
    $qty = $row['qty'];
    $harga = $row['harga'];
    $status = "Pesanan Baru";
    
    // Simpan data pesanan ke dalam tabel pesanan
    $order = mysqli_query($conn, "INSERT INTO pesanan (invoice, kode_customer, kode_produk, nama_produk, qty, harga, `status`, tanggal, provinsi, kota, alamat, kode_pos, terima, tolak, cek)
                    VALUES ('$format', '$kd_cs', '$kd_produk', '$nama_produk', '$qty', '$harga', '$status', CURRENT_DATE, '$prov', '$kota', '$alamat', '$kopos', '0', '0', '1')");

    // Update atau kurangi stok produk dari inventaris
    mysqli_query($conn, "UPDATE inventory SET qty = qty - $qty WHERE kode_bk = '$kd_produk'");
}

// Hapus semua barang dari keranjang setelah checkout
$del_keranjang = mysqli_query($conn, "DELETE FROM keranjang WHERE kode_customer = '$kd_cs'");

if ($del_keranjang) {
    // Redirect atau berikan feedback sukses kepada user
    header("location: ../selesai.php");
    exit();
} else {
    // Handle error jika ada masalah saat menghapus keranjang
    echo "Gagal menghapus keranjang setelah checkout.";
}

?>
