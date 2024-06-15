
USE toko_souvenir;

DELIMITER $$

CREATE PROCEDURE GetInventoryByCode (
    IN p_kode_bk VARCHAR(50),
    OUT p_nama_barang VARCHAR(200),
    OUT p_qty INT,
    OUT p_satuan VARCHAR(100)
)
BEGIN
    SELECT kode_bk, nama, qty, satuan
    INTO p_kode_bk, p_nama_barang, p_qty, p_satuan
    FROM inventory 
    WHERE kode_bk = p_kode_bk;
END$$


DELIMITER ;





DELIMITER //

CREATE PROCEDURE GetProductByCode (
    IN p_kode VARCHAR(255),
    OUT p_nama_produk VARCHAR(255),
    OUT p_harga DECIMAL(10, 2),
    OUT p_deskripsi TEXT
)
BEGIN
    SELECT nama, harga, deskripsi
    INTO p_nama_produk, p_harga, p_deskripsi
    FROM produk
    WHERE kode_produk = p_kode;
END //

DELIMITER ;









DELIMITER //

CREATE PROCEDURE DeleteCustomer(IN p_kode_customer VARCHAR(100))
BEGIN
    DELETE FROM customer WHERE kode_customer = p_kode_customer;
END //

DELIMITER ;

DELIMITER //
CREATE PROCEDURE ProsesPesanan()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE inv_id INT;
    DECLARE cur CURSOR FOR SELECT id_order, invoice, terima, tolak FROM pesanan WHERE cek = 1;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO inv_id;

        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Lakukan pengecekan dan update berdasarkan kondisi
        UPDATE pesanan SET
            terima = CASE
                        WHEN terima = 0 AND tolak = 0 THEN 1
                        ELSE terima
                    END,
            tolak = CASE
                       WHEN terima = 0 AND tolak = 0 THEN 0
                       ELSE tolak
                   END
        WHERE id_order = inv_id;

    END LOOP;

    CLOSE cur;
END //
DELIMITER ;






DELIMITER $$

CREATE PROCEDURE HitungPesananBulanan(IN startDate DATE, IN endDate DATE)
BEGIN
    SELECT 
        DATE_FORMAT(tanggal, '%Y-%m') AS bulan,
        SUM(qty) AS total
    FROM 
        pesanan
    WHERE 
        tanggal BETWEEN startDate AND endDate
    GROUP BY 
        DATE_FORMAT(tanggal, '%Y-%m');
END$$

DELIMITER ;

SELECT * FROM pesanan;


CALL HitungPesananBulanan;

CREATE VIEW resultView AS
SELECT p.kode_produk, p.nama, p.image, p.harga
FROM produk p;

CREATE TABLE ADMIN (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(200) NOT NULL,
  `password` TEXT NOT NULL
);

INSERT INTO ADMIN (id, username, `password`) VALUES
(1, 'admin', '$2y$10$AIy0X1Ep6alaHDTofiChGeqq7k/d1Kc8vKQf1JZo0mKrzkkj6M626');

CREATE TABLE bom_produk (
  kode_bom VARCHAR(100) NOT NULL,
  kode_bk VARCHAR(100) NOT NULL,
  kode_produk VARCHAR(100) NOT NULL,
  nama_produk VARCHAR(200) NOT NULL,
  kebutuhan VARCHAR(200) NOT NULL
);

CREATE TABLE customer (
  kode_customer VARCHAR(100) NOT NULL PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  username VARCHAR(100) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  telp VARCHAR(200) NOT NULL
);
DROP TABLE customer;
CREATE TABLE inventory (
  kode_bk VARCHAR(100) NOT NULL PRIMARY KEY,
  nama VARCHAR(200) NOT NULL,
  qty VARCHAR(200) NOT NULL,
  satuan VARCHAR(200) NOT NULL,
  harga INT(11) NOT NULL,
  tanggal DATE NOT NULL
);

CREATE TABLE keranjang (
  id_keranjang INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  kode_customer VARCHAR(100) NOT NULL,
  kode_produk VARCHAR(100) NOT NULL,
  nama_produk VARCHAR(100) NOT NULL,
  qty INT(11) NOT NULL,
  harga INT(11) NOT NULL
);

CREATE TABLE produk (
  kode_produk VARCHAR(100) NOT NULL PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  image TEXT NOT NULL,
  deskripsi TEXT NOT NULL,
  harga INT(11) NOT NULL
);

CREATE TABLE pesanan (
  id_order INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  invoice VARCHAR(200) NOT NULL,
  kode_customer VARCHAR(200) NOT NULL,
  kode_produk VARCHAR(200) NOT NULL,
  nama_produk VARCHAR(200) NOT NULL,
  qty INT(11) NOT NULL,
  harga INT(11) NOT NULL,
  `status` VARCHAR(200) NOT NULL,
  tanggal DATE NOT NULL,
  provinsi VARCHAR(200) NOT NULL,
  kota VARCHAR(200) NOT NULL,
  alamat VARCHAR(200) NOT NULL,
  kode_pos VARCHAR(200) NOT NULL,
  terima VARCHAR(200) NOT NULL,
  tolak VARCHAR(200) NOT NULL,
  cek INT(11) NOT NULL
);

CREATE TABLE report_cancel (
  id_report_cancel INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_order INT(11) NOT NULL,
  kode_produk VARCHAR(100) NOT NULL,
  jumlah VARCHAR(100) NOT NULL,
  tanggal DATE NOT NULL,
  FOREIGN KEY (id_order) REFERENCES pesanan(id_order),
  FOREIGN KEY (kode_produk) REFERENCES produk(kode_produk)
);

CREATE TABLE report_omset (
  id_report_omset INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_order INT(11) NOT NULL,
  jumlah INT(11) NOT NULL,
  total_omset INT(11) NOT NULL,
  tanggal DATE NOT NULL,
  FOREIGN KEY (id_order) REFERENCES pesanan(id_order)
);

CREATE TABLE report_penjualan (
  id_report_sell INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  kode_produk VARCHAR(100) NOT NULL,
  nama_produk VARCHAR(100) NOT NULL,
  jumlah_terjual INT(11) NOT NULL,
  tanggal DATE NOT NULL,
  FOREIGN KEY (kode_produk) REFERENCES produk(kode_produk)
);

CREATE TABLE report_pesanan (
  id_report_pesanan INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  kode_produk VARCHAR(100) NOT NULL,
  nama_produk VARCHAR(100) NOT NULL,
  qty INT(11) NOT NULL,
  tanggal DATE NOT NULL,
  FOREIGN KEY (kode_produk) REFERENCES produk(kode_produk)
);

CREATE TABLE report_profit (
  id_report_profit INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_order INT(11) NOT NULL,
  kode_produk VARCHAR(100) NOT NULL,
  jumlah VARCHAR(11) NOT NULL,
  total_profit VARCHAR(11) NOT NULL,
  tanggal DATE NOT NULL,
  FOREIGN KEY (kode_produk) REFERENCES produk(kode_produk),
  FOREIGN KEY (id_order) REFERENCES pesanan(id_order)
);

CREATE VIEW result1 AS
SELECT DISTINCT invoice
FROM pesanan
WHERE terima = 0 AND tolak = 0;

CREATE VIEW result2 AS
SELECT DISTINCT invoice
FROM pesanan
WHERE tolak = 1;

CREATE VIEW result3 AS
SELECT DISTINCT invoice
FROM pesanan
WHERE terima = 1;

CREATE VIEW v_inventory AS
SELECT kode_bk, nama, qty, satuan, harga
FROM inventory
ORDER BY kode_bk ASC;


CREATE VIEW resultView AS
SELECT p.kode_produk, p.nama, p.image, p.harga
FROM produk p;

CREATE VIEW view_customer AS
SELECT kode_customer, nama, email
FROM customer;


SELECT * FROM resultView;
SELECT *FROM v_inventory;



CREATE TABLE IF NOT EXISTS validasi_insert_produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message VARCHAR(255)
);

DELIMITER //

CREATE TRIGGER cek_harga_produk
BEFORE INSERT ON produk
FOR EACH ROW
BEGIN
    IF NEW.harga <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Harga produk harus lebih dari 0';
    END IF;
END;
//

CREATE TRIGGER cek_harga_produk_update
BEFORE UPDATE ON produk
FOR EACH ROW
BEGIN
    IF NEW.harga <= 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Harga produk harus lebih dari 0';
    END IF;
END;
//

DELIMITER ;



DELIMITER //

CREATE TRIGGER check_customer_username
BEFORE INSERT ON customer
FOR EACH ROW
BEGIN
    DECLARE username_exists INT;
    SELECT COUNT(*) INTO username_exists FROM customer WHERE username = NEW.username;

    IF username_exists > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Username sudah dipakai. Harap gunakan username lain.';
    END IF;
END;
//

DELIMITER ;


DELIMITER //

CREATE TRIGGER check_qty_update
BEFORE UPDATE ON keranjang
FOR EACH ROW
BEGIN
    IF NEW.qty <= 0 THEN
        DELETE FROM keranjang WHERE id_keranjang = NEW.id_keranjang;
    END IF;
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER after_delete_produk
AFTER DELETE ON produk
FOR EACH ROW
BEGIN
    DELETE FROM keranjang WHERE kode_produk = OLD.kode_produk;
    DELETE FROM inventory WHERE kode_bk = OLD.kode_produk;
END;//
DELIMITER ;



