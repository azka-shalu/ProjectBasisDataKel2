<?php
// Koneksi database
$con = mysqli_connect("localhost", "root", "", "digikos");
if(mysqli_connect_errno()){
    echo "Koneksi database gagal: " . mysqli_connect_error();
    exit();
}

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $table = $_POST['table'];
    
    if ($action == 'create') {
        if ($table == 'tipe_kamar') {
            $nama = $_POST['nama'];
            $harga = $_POST['harga'];
            $fasilitas = $_POST['fasilitas'];
            $sql = "INSERT INTO tipe_kamar (nama, harga, fasilitas) VALUES ('$nama', '$harga', '$fasilitas')";
        }
        elseif ($table == 'kamar') {
            $nomor_kamar = $_POST['nomor_kamar'];
            $tipe_kamar_id = $_POST['tipe_kamar_id'];
            $status = $_POST['status'];
            $sql = "INSERT INTO kamar (nomor_kamar, tipe_kamar_id, status) VALUES ('$nomor_kamar', '$tipe_kamar_id', '$status')";
        }
        elseif ($table == 'penyewa') {
            $nama = $_POST['nama'];
            $nik = $_POST['nik'];
            $no_hp = $_POST['no_hp'];
            $email = $_POST['email'];
            $alamat = $_POST['alamat'];
            $sql = "INSERT INTO penyewa (nama, nik, no_hp, email, alamat) VALUES ('$nama', '$nik', '$no_hp', '$email', '$alamat')";
        }
        elseif ($table == 'kontrak') {
            $nomor_kontrak = $_POST['nomor_kontrak'];
            $penyewa_id = $_POST['penyewa_id'];
            $kamar_id = $_POST['kamar_id'];
            $tanggal_mulai = $_POST['tanggal_mulai'];
            $tanggal_selesai = $_POST['tanggal_selesai'];
            $biaya_sewa = $_POST['biaya_sewa'];
            $status = $_POST['status'];
            $sql = "INSERT INTO kontrak (nomor_kontrak, penyewa_id, kamar_id, tanggal_mulai, tanggal_selesai, biaya_sewa, status) VALUES ('$nomor_kontrak', '$penyewa_id', '$kamar_id', '$tanggal_mulai', '$tanggal_selesai', '$biaya_sewa', '$status')";
        }
        elseif ($table == 'pembayaran') {
            $nomor_pembayaran = $_POST['nomor_pembayaran'];
            $kontrak_id = $_POST['kontrak_id'];
            $jumlah = $_POST['jumlah'];
            $tanggal_pembayaran = $_POST['tanggal_pembayaran'];
            $metode_pembayaran = $_POST['metode_pembayaran'];
            $keterangan = $_POST['keterangan'];
            $sql = "INSERT INTO pembayaran (nomor_pembayaran, kontrak_id, jumlah, tanggal_pembayaran, metode_pembayaran, keterangan) VALUES ('$nomor_pembayaran', '$kontrak_id', '$jumlah', '$tanggal_pembayaran', '$metode_pembayaran', '$keterangan')";
        }
        mysqli_query($con, $sql);
    }
    
    elseif ($action == 'update') {
        $id = $_POST['id'];
        if ($table == 'tipe_kamar') {
            $nama = $_POST['nama'];
            $harga = $_POST['harga'];
            $fasilitas = $_POST['fasilitas'];
            $sql = "UPDATE tipe_kamar SET nama='$nama', harga='$harga', fasilitas='$fasilitas' WHERE id='$id'";
        }
        elseif ($table == 'kamar') {
            $nomor_kamar = $_POST['nomor_kamar'];
            $tipe_kamar_id = $_POST['tipe_kamar_id'];
            $status = $_POST['status'];
            $sql = "UPDATE kamar SET nomor_kamar='$nomor_kamar', tipe_kamar_id='$tipe_kamar_id', status='$status' WHERE id='$id'";
        }
        elseif ($table == 'penyewa') {
            $nama = $_POST['nama'];
            $nik = $_POST['nik'];
            $no_hp = $_POST['no_hp'];
            $email = $_POST['email'];
            $alamat = $_POST['alamat'];
            $sql = "UPDATE penyewa SET nama='$nama', nik='$nik', no_hp='$no_hp', email='$email', alamat='$alamat' WHERE id='$id'";
        }
        elseif ($table == 'kontrak') {
            $nomor_kontrak = $_POST['nomor_kontrak'];
            $penyewa_id = $_POST['penyewa_id'];
            $kamar_id = $_POST['kamar_id'];
            $tanggal_mulai = $_POST['tanggal_mulai'];
            $tanggal_selesai = $_POST['tanggal_selesai'];
            $biaya_sewa = $_POST['biaya_sewa'];
            $status = $_POST['status'];
            $sql = "UPDATE kontrak SET nomor_kontrak='$nomor_kontrak', penyewa_id='$penyewa_id', kamar_id='$kamar_id', tanggal_mulai='$tanggal_mulai', tanggal_selesai='$tanggal_selesai', biaya_sewa='$biaya_sewa', status='$status' WHERE id='$id'";
        }
        elseif ($table == 'pembayaran') {
            $nomor_pembayaran = $_POST['nomor_pembayaran'];
            $kontrak_id = $_POST['kontrak_id'];
            $jumlah = $_POST['jumlah'];
            $tanggal_pembayaran = $_POST['tanggal_pembayaran'];
            $metode_pembayaran = $_POST['metode_pembayaran'];
            $keterangan = $_POST['keterangan'];
            $sql = "UPDATE pembayaran SET nomor_pembayaran='$nomor_pembayaran', kontrak_id='$kontrak_id', jumlah='$jumlah', tanggal_pembayaran='$tanggal_pembayaran', metode_pembayaran='$metode_pembayaran', keterangan='$keterangan' WHERE id='$id'";
        }
        mysqli_query($con, $sql);
    }
    
    elseif ($action == 'delete') {
        $id = $_POST['id'];
        $table = $_POST['table'];
        
        // Validasi input
        if (empty($id) || empty($table)) {
            echo "<script>alert('Data tidak lengkap!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
            exit();
        }
        
        // Whitelist tabel yang diizinkan untuk keamanan
        $allowed_tables = ['tipe_kamar', 'kamar', 'penyewa', 'kontrak', 'pembayaran'];
        if (!in_array($table, $allowed_tables)) {
            echo "<script>alert('Tabel tidak valid!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
            exit();
        }
        
        // Cek apakah data masih digunakan di tabel lain (untuk menghindari foreign key constraint error)
        $can_delete = true;
        $error_message = "";
        
        if ($table == 'tipe_kamar') {
            $check = mysqli_query($con, "SELECT COUNT(*) as count FROM kamar WHERE tipe_kamar_id = '$id'");
            $result = mysqli_fetch_assoc($check);
            if ($result['count'] > 0) {
                $can_delete = false;
                $error_message = "Tidak dapat menghapus tipe kamar karena masih digunakan oleh kamar!";
            }
        }
        elseif ($table == 'kamar') {
            $check = mysqli_query($con, "SELECT COUNT(*) as count FROM kontrak WHERE kamar_id = '$id'");
            $result = mysqli_fetch_assoc($check);
            if ($result['count'] > 0) {
                $can_delete = false;
                $error_message = "Tidak dapat menghapus kamar karena masih ada kontrak aktif!";
            }
        }
        elseif ($table == 'penyewa') {
            $check = mysqli_query($con, "SELECT COUNT(*) as count FROM kontrak WHERE penyewa_id = '$id'");
            $result = mysqli_fetch_assoc($check);
            if ($result['count'] > 0) {
                $can_delete = false;
                $error_message = "Tidak dapat menghapus penyewa karena masih ada kontrak!";
            }
        }
        elseif ($table == 'kontrak') {
            $check = mysqli_query($con, "SELECT COUNT(*) as count FROM pembayaran WHERE kontrak_id = '$id'");
            $result = mysqli_fetch_assoc($check);
            if ($result['count'] > 0) {
                $can_delete = false;
                $error_message = "Tidak dapat menghapus kontrak karena masih ada data pembayaran!";
            }
        }
        
        if ($can_delete) {
            // Gunakan prepared statement untuk keamanan
            $stmt = mysqli_prepare($con, "DELETE FROM $table WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    echo "<script>alert('Data berhasil dihapus!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
                } else {
                    echo "<script>alert('Data tidak ditemukan atau sudah terhapus!'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
                }
            } else {
                echo "<script>alert('Gagal menghapus data: " . mysqli_error($con) . "'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
            }
            
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('$error_message'); window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
        }
    }
}

// Get edit data
$edit_data = null;
if (isset($_GET['edit']) && isset($_GET['table']) && isset($_GET['id'])) {
    $table = $_GET['table'];
    $id = $_GET['id'];
    $result = mysqli_query($con, "SELECT * FROM $table WHERE id='$id'");
    $edit_data = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistem Kos Digital - CRUD</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Digikost Database</h1>
    
    <!-- TIPE KAMAR -->
    <h2>Tipe Kamar</h2>
    <h3>Tambah/Edit Tipe Kamar</h3>
    <form method="POST">
        <input type="hidden" name="action" value="<?= ($edit_data && $_GET['table'] == 'tipe_kamar') ? 'update' : 'create' ?>">
        <input type="hidden" name="table" value="tipe_kamar">
        <?php if ($edit_data && $_GET['table'] == 'tipe_kamar'): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>
        
        Nama: <input type="text" name="nama" value="<?= ($edit_data && $_GET['table'] == 'tipe_kamar') ? $edit_data['nama'] : '' ?>" required><br>
        Harga: <input type="number" name="harga" value="<?= ($edit_data && $_GET['table'] == 'tipe_kamar') ? $edit_data['harga'] : '' ?>" required><br>
        Fasilitas: <textarea name="fasilitas"><?= ($edit_data && $_GET['table'] == 'tipe_kamar') ? $edit_data['fasilitas'] : '' ?></textarea><br>
        <input type="submit" value="<?= ($edit_data && $_GET['table'] == 'tipe_kamar') ? 'Update' : 'Tambah' ?>">
        <?php if ($edit_data && $_GET['table'] == 'tipe_kamar'): ?>
            <a href="?">Batal</a>
        <?php endif; ?>
    </form>
    
    <h3>Data Tipe Kamar</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Fasilitas</th>
            <th>Aksi</th>
        </tr>
        <?php
        $result = mysqli_query($con, "SELECT * FROM tipe_kamar");
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['nama'] . "</td>";
            echo "<td>" . $row['harga'] . "</td>";
            echo "<td>" . $row['fasilitas'] . "</td>";
            echo "<td>
                <a href='?edit=1&table=tipe_kamar&id=" . $row['id'] . "'>Edit</a> | 
                <form method='POST' style='display:inline'>
                    <input type='hidden' name='action' value='delete'>
                    <input type='hidden' name='table' value='tipe_kamar'>
                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                    <input type='submit' value='Hapus' onclick='return confirm(\"Yakin hapus?\")'>
                </form>
            </td>";
            echo "</tr>";
        }
        ?>
    </table>
    
    <hr>
    
    <!-- KAMAR -->
    <h2>Kamar</h2>
    <h3>Tambah/Edit Kamar</h3>
    <form method="POST">
        <input type="hidden" name="action" value="<?= ($edit_data && $_GET['table'] == 'kamar') ? 'update' : 'create' ?>">
        <input type="hidden" name="table" value="kamar">
        <?php if ($edit_data && $_GET['table'] == 'kamar'): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>
        
        Nomor Kamar: <input type="text" name="nomor_kamar" value="<?= ($edit_data && $_GET['table'] == 'kamar') ? $edit_data['nomor_kamar'] : '' ?>" required><br>
        Tipe Kamar: 
        <select name="tipe_kamar_id" required>
            <option value="">Pilih Tipe Kamar</option>
            <?php
            $tipe_result = mysqli_query($con, "SELECT * FROM tipe_kamar");
            while($tipe_row = mysqli_fetch_assoc($tipe_result)) {
                $selected = ($edit_data && $_GET['table'] == 'kamar' && $edit_data['tipe_kamar_id'] == $tipe_row['id']) ? 'selected' : '';
                echo "<option value='" . $tipe_row['id'] . "' $selected>" . $tipe_row['nama'] . "</option>";
            }
            ?>
        </select><br>
        Status: 
        <select name="status" required>
            <option value="">Pilih Status</option>
            <option value="Kosong" <?= ($edit_data && $_GET['table'] == 'kamar' && $edit_data['status'] == 'Kosong') ? 'selected' : '' ?>>Kosong</option>
            <option value="Terisi" <?= ($edit_data && $_GET['table'] == 'kamar' && $edit_data['status'] == 'Terisi') ? 'selected' : '' ?>>Terisi</option>
        </select><br>
        <input type="submit" value="<?= ($edit_data && $_GET['table'] == 'kamar') ? 'Update' : 'Tambah' ?>">
        <?php if ($edit_data && $_GET['table'] == 'kamar'): ?>
            <a href="?">Batal</a>
        <?php endif; ?>
    </form>
    
    <h3>Data Kamar</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nomor Kamar</th>
            <th>Tipe Kamar</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php
        $result = mysqli_query($con, "SELECT k.*, tk.nama as tipe_nama FROM kamar k LEFT JOIN tipe_kamar tk ON k.tipe_kamar_id = tk.id");
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['nomor_kamar'] . "</td>";
            echo "<td>" . $row['tipe_nama'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>
                <a href='?edit=1&table=kamar&id=" . $row['id'] . "'>Edit</a> | 
                <form method='POST' style='display:inline'>
                    <input type='hidden' name='action' value='delete'>
                    <input type='hidden' name='table' value='kamar'>
                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                    <input type='submit' value='Hapus' onclick='return confirm(\"Yakin hapus?\")'>
                </form>
            </td>";
            echo "</tr>";
        }
        ?>
    </table>
    
    <hr>
    
    <!-- PENYEWA -->
    <h2>Penyewa</h2>
    <h3>Tambah/Edit Penyewa</h3>
    <form method="POST">
        <input type="hidden" name="action" value="<?= ($edit_data && $_GET['table'] == 'penyewa') ? 'update' : 'create' ?>">
        <input type="hidden" name="table" value="penyewa">
        <?php if ($edit_data && $_GET['table'] == 'penyewa'): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>
        
        Nama: <input type="text" name="nama" value="<?= ($edit_data && $_GET['table'] == 'penyewa') ? $edit_data['nama'] : '' ?>" required><br>
        NIK: <input type="text" name="nik" value="<?= ($edit_data && $_GET['table'] == 'penyewa') ? $edit_data['nik'] : '' ?>" required><br>
        No HP: <input type="text" name="no_hp" value="<?= ($edit_data && $_GET['table'] == 'penyewa') ? $edit_data['no_hp'] : '' ?>" required><br>
        Email: <input type="email" name="email" value="<?= ($edit_data && $_GET['table'] == 'penyewa') ? $edit_data['email'] : '' ?>" required><br>
        Alamat: <textarea name="alamat"><?= ($edit_data && $_GET['table'] == 'penyewa') ? $edit_data['alamat'] : '' ?></textarea><br>
        <input type="submit" value="<?= ($edit_data && $_GET['table'] == 'penyewa') ? 'Update' : 'Tambah' ?>">
        <?php if ($edit_data && $_GET['table'] == 'penyewa'): ?>
            <a href="?">Batal</a>
        <?php endif; ?>
    </form>
    
    <h3>Data Penyewa</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>NIK</th>
            <th>No HP</th>
            <th>Email</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
        <?php
        $result = mysqli_query($con, "SELECT * FROM penyewa");
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['nama'] . "</td>";
            echo "<td>" . $row['nik'] . "</td>";
            echo "<td>" . $row['no_hp'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['alamat'] . "</td>";
            echo "<td>
                <a href='?edit=1&table=penyewa&id=" . $row['id'] . "'>Edit</a> | 
                <form method='POST' style='display:inline'>
                    <input type='hidden' name='action' value='delete'>
                    <input type='hidden' name='table' value='penyewa'>
                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                    <input type='submit' value='Hapus' onclick='return confirm(\"Yakin hapus?\")'>
                </form>
            </td>";
            echo "</tr>";
        }
        ?>
    </table>
    
    <hr>
    
    <!-- KONTRAK -->
    <h2>Kontrak</h2>
    <h3>Tambah/Edit Kontrak</h3>
    <form method="POST">
        <input type="hidden" name="action" value="<?= ($edit_data && $_GET['table'] == 'kontrak') ? 'update' : 'create' ?>">
        <input type="hidden" name="table" value="kontrak">
        <?php if ($edit_data && $_GET['table'] == 'kontrak'): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>
        
        Nomor Kontrak: <input type="text" name="nomor_kontrak" value="<?= ($edit_data && $_GET['table'] == 'kontrak') ? $edit_data['nomor_kontrak'] : '' ?>" required><br>
        Penyewa: 
        <select name="penyewa_id" required>
            <option value="">Pilih Penyewa</option>
            <?php
            $penyewa_result = mysqli_query($con, "SELECT * FROM penyewa");
            while($penyewa_row = mysqli_fetch_assoc($penyewa_result)) {
                $selected = ($edit_data && $_GET['table'] == 'kontrak' && $edit_data['penyewa_id'] == $penyewa_row['id']) ? 'selected' : '';
                echo "<option value='" . $penyewa_row['id'] . "' $selected>" . $penyewa_row['nama'] . "</option>";
            }
            ?>
        </select><br>
        Kamar: 
        <select name="kamar_id" required>
            <option value="">Pilih Kamar</option>
            <?php
            $kamar_result = mysqli_query($con, "SELECT * FROM kamar");
            while($kamar_row = mysqli_fetch_assoc($kamar_result)) {
                $selected = ($edit_data && $_GET['table'] == 'kontrak' && $edit_data['kamar_id'] == $kamar_row['id']) ? 'selected' : '';
                echo "<option value='" . $kamar_row['id'] . "' $selected>" . $kamar_row['nomor_kamar'] . "</option>";
            }
            ?>
        </select><br>
        Tanggal Mulai: <input type="date" name="tanggal_mulai" value="<?= ($edit_data && $_GET['table'] == 'kontrak') ? $edit_data['tanggal_mulai'] : '' ?>" required><br>
        Tanggal Selesai: <input type="date" name="tanggal_selesai" value="<?= ($edit_data && $_GET['table'] == 'kontrak') ? $edit_data['tanggal_selesai'] : '' ?>" required><br>
        Biaya Sewa: <input type="number" name="biaya_sewa" value="<?= ($edit_data && $_GET['table'] == 'kontrak') ? $edit_data['biaya_sewa'] : '' ?>" required><br>
        Status: 
        <select name="status" required>
            <option value="">Pilih Status</option>
            <option value="Aktif" <?= ($edit_data && $_GET['table'] == 'kontrak' && $edit_data['status'] == 'Aktif') ? 'selected' : '' ?>>Aktif</option>
            <option value="Selesai" <?= ($edit_data && $_GET['table'] == 'kontrak' && $edit_data['status'] == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
            <option value="Dibatalkan" <?= ($edit_data && $_GET['table'] == 'kontrak' && $edit_data['status'] == 'Dibatalkan') ? 'selected' : '' ?>>Dibatalkan</option>
        </select><br>
        <input type="submit" value="<?= ($edit_data && $_GET['table'] == 'kontrak') ? 'Update' : 'Tambah' ?>">
        <?php if ($edit_data && $_GET['table'] == 'kontrak'): ?>
            <a href="?">Batal</a>
        <?php endif; ?>
    </form>
    
    <h3>Data Kontrak</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nomor Kontrak</th>
            <th>Penyewa</th>
            <th>Kamar</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Biaya Sewa</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php
        $result = mysqli_query($con, "SELECT k.*, p.nama as penyewa_nama, km.nomor_kamar FROM kontrak k LEFT JOIN penyewa p ON k.penyewa_id = p.id LEFT JOIN kamar km ON k.kamar_id = km.id");
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['nomor_kontrak'] . "</td>";
            echo "<td>" . $row['penyewa_nama'] . "</td>";
            echo "<td>" . $row['nomor_kamar'] . "</td>";
            echo "<td>" . $row['tanggal_mulai'] . "</td>";
            echo "<td>" . $row['tanggal_selesai'] . "</td>";
            echo "<td>" . $row['biaya_sewa'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>
                <a href='?edit=1&table=kontrak&id=" . $row['id'] . "'>Edit</a> | 
                <form method='POST' style='display:inline'>
                    <input type='hidden' name='action' value='delete'>
                    <input type='hidden' name='table' value='kontrak'>
                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                    <input type='submit' value='Hapus' onclick='return confirm(\"Yakin hapus?\")'>
                </form>
            </td>";
            echo "</tr>";
        }
        ?>
    </table>
    
    <hr>
    
    <!-- PEMBAYARAN -->
    <h2>Pembayaran</h2>
    <h3>Tambah/Edit Pembayaran</h3>
    <form method="POST">
        <input type="hidden" name="action" value="<?= ($edit_data && $_GET['table'] == 'pembayaran') ? 'update' : 'create' ?>">
        <input type="hidden" name="table" value="pembayaran">
        <?php if ($edit_data && $_GET['table'] == 'pembayaran'): ?>
            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
        <?php endif; ?>
        
        Nomor Pembayaran: <input type="text" name="nomor_pembayaran" value="<?= ($edit_data && $_GET['table'] == 'pembayaran') ? $edit_data['nomor_pembayaran'] : '' ?>" required><br>
        Kontrak: 
        <select name="kontrak_id" required>
            <option value="">Pilih Kontrak</option>
            <?php
            $kontrak_result = mysqli_query($con, "SELECT k.*, p.nama as penyewa_nama FROM kontrak k LEFT JOIN penyewa p ON k.penyewa_id = p.id");
            while($kontrak_row = mysqli_fetch_assoc($kontrak_result)) {
                $selected = ($edit_data && $_GET['table'] == 'pembayaran' && $edit_data['kontrak_id'] == $kontrak_row['id']) ? 'selected' : '';
                echo "<option value='" . $kontrak_row['id'] . "' $selected>" . $kontrak_row['nomor_kontrak'] . " - " . $kontrak_row['penyewa_nama'] . "</option>";
            }
            ?>
        </select><br>
        Jumlah: <input type="number" name="jumlah" value="<?= ($edit_data && $_GET['table'] == 'pembayaran') ? $edit_data['jumlah'] : '' ?>" required><br>
        Tanggal Pembayaran: <input type="date" name="tanggal_pembayaran" value="<?= ($edit_data && $_GET['table'] == 'pembayaran') ? $edit_data['tanggal_pembayaran'] : '' ?>" required><br>
        Metode Pembayaran: <input type="text" name="metode_pembayaran" value="<?= ($edit_data && $_GET['table'] == 'pembayaran') ? $edit_data['metode_pembayaran'] : '' ?>" required><br>
        Keterangan: <textarea name="keterangan"><?= ($edit_data && $_GET['table'] == 'pembayaran') ? $edit_data['keterangan'] : '' ?></textarea><br>
        <input type="submit" value="<?= ($edit_data && $_GET['table'] == 'pembayaran') ? 'Update' : 'Tambah' ?>">
        <?php if ($edit_data && $_GET['table'] == 'pembayaran'): ?>
            <a href="?">Batal</a>
        <?php endif; ?>
    </form>
    
    <h3>Data Pembayaran</h3>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nomor Pembayaran</th>
            <th>Kontrak</th>
            <th>Penyewa</th>
            <th>Jumlah</th>
            <th>Tanggal Pembayaran</th>
            <th>Metode Pembayaran</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
        <?php
        $result = mysqli_query($con, "SELECT pb.*, k.nomor_kontrak, p.nama as penyewa_nama FROM pembayaran pb LEFT JOIN kontrak k ON pb.kontrak_id = k.id LEFT JOIN penyewa p ON k.penyewa_id = p.id");
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['nomor_pembayaran'] . "</td>";
            echo "<td>" . $row['nomor_kontrak'] . "</td>";
            echo "<td>" . $row['penyewa_nama'] . "</td>";
            echo "<td>" . $row['jumlah'] . "</td>";
            echo "<td>" . $row['tanggal_pembayaran'] . "</td>";
            echo "<td>" . $row['metode_pembayaran'] . "</td>";
            echo "<td>" . $row['keterangan'] . "</td>";
            echo "<td>
                <a href='?edit=1&table=pembayaran&id=" . $row['id'] . "'>Edit</a> | 
                <form method='POST' style='display:inline'>
                    <input type='hidden' name='action' value='delete'>
                    <input type='hidden' name='table' value='pembayaran'>
                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                    <input type='submit' value='Hapus' onclick='return confirm(\"Yakin hapus?\")'>
                </form>
            </td>";
            echo "</tr>";
        }
        ?>
    </table>
    
</body>
</html>