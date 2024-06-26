<?php 
include 'header.php';

$kode = isset($_GET['kode']) ? $_GET['kode'] : '';

if (empty($kode)) {
    echo "Kode material tidak ada.";
    exit;
}

$stmt = $conn->prepare("CALL GetInventoryByCode(?, @p_nama_barang, @p_qty, @p_satuan)");
$stmt->bind_param("s", $kode);
$stmt->execute();

$result = $conn->query("SELECT @p_nama_barang AS nama_barang, @p_qty AS qty, @p_satuan AS satuan");
$row = $result->fetch_assoc();

$nama_barang = $row['nama_barang'];
$qty = $row['qty'];
$satuan = $row['satuan'];

$result->close();
$stmt->close();
?>

<div class="container">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Edit Inventori</b></h2>

    <form action="proses/edit_inv.php" method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Kode Material</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" disabled value="<?= $kode; ?>">
                    <input type="hidden" class="form-control" name="kd_material" value="<?= $kode; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Nama Material</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Masukkan Material" name="nama" value="<?= isset($nama_barang) ? $nama_barang : ''; ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Stok</label>
                    <input type="number" class="form-control" id="exampleInputEmail1" name="stok" value="<?= isset($qty) ? $qty : ''; ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="exampleInputEmail1">Satuan</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Contoh : Kg" name="satuan" value="<?= isset($satuan) ? $satuan : ''; ?>">
                    <p class="help-block">Hanya Masukkan Satuan saja : Kg atau gram</p>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-warning"><i class="glyphicon glyphicon-edit"></i> Edit</button>
        <a href="inventory.php" class="btn btn-danger">Batal</a>
    </form>
</div>
