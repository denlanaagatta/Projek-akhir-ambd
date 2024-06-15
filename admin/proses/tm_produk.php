<?php
include '../../koneksi/koneksi.php';

// Generate kode BOM
$kode = mysqli_query($conn, "SELECT kode_produk FROM produk ORDER BY kode_produk DESC");
$data = mysqli_fetch_assoc($kode);
// Menerima data dari form
$kode = $_POST['kode'];
$nm_produk = $_POST['nama'];
$harga = $_POST['harga'];
$desk = $_POST['desk'];
$nama_gambar = $_FILES['files']['name'];
$size_gambar = $_FILES['files']['size'];
$tmp_file = $_FILES['files']['tmp_name'];
$eror = $_FILES['files']['error'];
$type = $_FILES['files']['type'];

// Validasi harga produk
if ($harga <= 0) {
    echo "
    <script>
    alert(' Tolong masukan jumlah harga yang benar');
    window.location = '../tm_produk.php';
    </script>
    ";
    die;
}

// BOM
$kd_material = isset($_POST['material']) ? $_POST['material'] : [];
$keb = isset($_POST['keb']) ? $_POST['keb'] : [];

// Validasi gambar
if ($eror === 4) {
    echo "
    <script>
    alert('TIDAK ADA GAMBAR YANG DIPILIH');
    window.location = '../tm_produk.php';
    </script>
    ";
    die;
}

$ekstensiGambar = array('jpg', 'jpeg', 'png');
$ekstensiGambarValid = explode(".", $nama_gambar);
$ekstensiGambarValid = strtolower(end($ekstensiGambarValid));

if (!in_array($ekstensiGambarValid, $ekstensiGambar)) {
    echo "
    <script>
    alert('EKSTENSI GAMBAR HARUS JPG, JPEG, PNG');
    window.location = '../tm_produk.php';
    </script>
    ";
    die;
}

if ($size_gambar > 1000000) {
    echo "
    <script>
    alert('UKURAN GAMBAR TERLALU BESAR');
    window.location = '../tm_produk.php';
    </script>
    ";
    die;
}

$namaGambarBaru = uniqid();
$namaGambarBaru .= ".";
$namaGambarBaru .= $ekstensiGambarValid;

if (move_uploaded_file($tmp_file, "../../image/produk/".$namaGambarBaru)) {
    $result = mysqli_query($conn, "INSERT INTO produk VALUES('$kode', '$nm_produk', '$namaGambarBaru', '$desk', '$harga')");

    $filter = array_filter($kd_material);
    $jml = count($filter);
    for ($no = 0; $no < $jml; $no++) {
        mysqli_query($conn, "INSERT INTO bom_produk VALUES('$format', '$kd_material[$no]', '$kode', '$nm_produk', '$keb[$no]')");
    }

    if ($result) {
        echo "
        <script>
        alert('PRODUK BERHASIL DITAMBAHKAN');
        window.location = '../m_produk.php';
        </script>
        ";
    } else {
        echo "
        <script>
        alert('PRODUK GAGAL DITAMBAHKAN');
        window.location = '../tm_produk.php';
        </script>
        ";
    }
} else {
    echo "
    <script>
    alert('GAGAL MENGUPLOAD GAMBAR');
    window.location = '../tm_produk.php';
    </script>
    ";
}
?>
