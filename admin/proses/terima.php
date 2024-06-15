<?php 
include '../../koneksi/koneksi.php';
$inv = $_GET['inv'];

$result = mysqli_query($conn, "SELECT * FROM pesanan WHERE invoice = '$inv'");
var_dump($inv);
while($row = mysqli_fetch_assoc($result)){
	$kodep = $row['kode_produk'];
	$kodebk = 'M' . substr($kodep, 1, 4);

	$inventory = mysqli_query($conn, "SELECT * FROM inventory WHERE kode_bk = '$kodebk'");
	$r_inv = mysqli_fetch_assoc($inventory);
	
	$qtyorder = $row['qty'];
	$inven = $r_inv['qty'];
	$hasil = $inven - $qtyorder;

	$update_inventory = mysqli_query($conn, "UPDATE inventory SET qty = '$hasil' WHERE kode_bk = '$kodebk'");

	if($update_inventory){
		$update_pesanan = mysqli_query($conn, "UPDATE pesanan SET terima = '1', status = '0' WHERE invoice = '$inv'");
		if($update_pesanan) {
			echo "
			<script>
			alert('PESANAN BERHASIL DITERIMA, BAHAN BAKU TELAH DIKURANGKAN');
			window.location = '../produksi.php';
			</script>
			";
		} else {
			echo "
			<script>
			alert('Gagal memperbarui status pesanan.');
			window.location = '../produksi.php';
			</script>
			";
		}
	} else {
		echo "
		<script>
		alert('Gagal mengupdate inventori.');
		window.location = '../produksi.php';
		</script>
		";
	}
}
?>
