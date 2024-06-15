<?php 
include 'header.php';
// Panggil stored procedure GetProductByCode
$kode_produk = 'PROD001'; // Ganti dengan kode produk yang ingin Anda cari

$stmt = $conn->prepare("CALL GetProductByCode(?, @p_nama_produk, @p_harga, @p_deskripsi)");
$stmt->bind_param("s", $kode_produk);
$stmt->execute();
$stmt->close();

// Ambil hasil dari parameter OUT
$result_out = $conn->query("SELECT @p_nama_produk AS nama_produk, @p_harga AS harga, @p_deskripsi AS deskripsi");
$row_out = $result_out->fetch_assoc();

$p_nama_produk = $row_out['nama_produk'];
$p_harga = $row_out['harga'];
$p_deskripsi = $row_out['deskripsi'];

// Ambil data produk dari tabel
$result = mysqli_query($conn, "SELECT * FROM resultView");
$no = 1;
?>

<div class="container">
	<h2 style=" width: 100%; border-bottom: 4px solid gray"><b>Data Produk</b></h2>
		<table class="table table-striped">
			<thead>
				<tr>
					<th scope="col">Kode Produk</th>
					<th scope="col">Nama Produk</th>
					<th scope="col">Image</th>
					<th scope="col">Harga</th>
					<th scope="col">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$result = mysqli_query($conn, "SELECT * FROM resultView");
				$no =1;
				while ($row = mysqli_fetch_assoc($result)) {
				?>
					<tr>
						<td><?= $row['kode_produk']; ?></td>
						<td><?= $row['nama'];  ?></td>
						<td><img src="../image/produk/<?= $row['image']; ?>" width="100"></td>
						<td>Rp.<?= number_format($row['harga']); ?></td>
						<td>
							<a href="edit_produk.php?kode=<?= $row['kode_produk']; ?>" class="btn btn-warning"><i class="glyphicon glyphicon-edit"></i> </a>
							<a href="proses/del_produk.php?kode=<?= $row['kode_produk']; ?>" class="btn btn-danger" onclick="return confirm('Yakin Ingin Menghapus Data ?')"><i class="glyphicon glyphicon-trash"></i> </a>
							<!-- <a href="bom.php?kode=<?= $row['kode_produk']; ?>" type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-eye-open"></i> Lihat BOM</a> -->
						</td>
					</tr>
				<?php
					}
				?>
				</tbody>
			</table>
		<a href="tm_produk.php" class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i> Tambah Produk</a>
	</div>
	<!-- Button trigger modal -->

	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>