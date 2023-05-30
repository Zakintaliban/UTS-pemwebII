<?php
// Include file konfigurasi database
include 'config.php';

// Fungsi untuk menambahkan data dosen
function tambahDosen($conn, $nama, $nidn, $jenjangPendidikan)
{
    $sql = "INSERT INTO Dosen (Nama, NIDN, JenjangPendidikan) VALUES ('$nama', '$nidn', '$jenjangPendidikan')";
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        return false;
    }
}

// Fungsi untuk mengambil semua data dosen
function ambilSemuaDosen($conn)
{
    $sql = "SELECT * FROM Dosen";
    $result = mysqli_query($conn, $sql);
    $dosen = array();

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $dosen[] = $row;
        }
    }

    return $dosen;
}

// Fungsi untuk mengambil data dosen berdasarkan ID
function ambilDosenByID($conn, $id)
{
    $sql = "SELECT * FROM Dosen WHERE ID=$id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $dosen = mysqli_fetch_assoc($result);
        return $dosen;
    } else {
        return false;
    }
}

// Fungsi untuk memperbarui data dosen
function perbaruiDosen($conn, $id, $nama, $nidn, $jenjangPendidikan)
{
    $sql = "UPDATE Dosen SET Nama='$nama', NIDN='$nidn', JenjangPendidikan='$jenjangPendidikan' WHERE ID=$id";
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        return false;
    }
}

// Fungsi untuk menghapus data dosen berdasarkan ID
function hapusDosen($conn, $id)
{
    $sql = "DELETE FROM Dosen WHERE ID=$id";
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        return false;
    }
}

// Proses tambah data dosen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tambah"])) {
    $nama = $_POST["nama"];
    $nidn = $_POST["nidn"];
    $jenjangPendidikan = $_POST["jenjangPendidikan"];

    if (tambahDosen($conn, $nama, $nidn, $jenjangPendidikan)) {
        echo "Data dosen berhasil ditambahkan.";
    }
}

// Proses perbarui data dosen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["perbarui"])) {
    $id = $_POST["id"];
    $nama = $_POST["nama"];
    $nidn = $_POST["nidn"];
    $jenjangPendidikan = $_POST["jenjangPendidikan"];

    if (perbaruiDosen($conn, $id, $nama, $nidn, $jenjangPendidikan)) {
        echo "Data dosen berhasil diperbarui.";
    }
}

// Proses hapus data dosen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["hapus"])) {
    $id = $_POST["id"];

    if (hapusDosen($conn, $id)) {
        echo "Data dosen berhasil dihapus.";
    }
}

// Ambil semua data dosen
$dosen = ambilSemuaDosen($conn);
?>

<!DOCTYPE html>
<html>

<head>
    <title>CRUD Dosen</title>
    <link rel="stylesheet" type="text/css" href="style-crud.css">
</head>

<body>
    <h2>CRUD Dosen</h2>

    <!-- Form tambah dosen -->
    <h3>Tambah Dosen</h3>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <input type="text" name="nama" placeholder="Nama Dosen" required><br>
        <input type="text" name="nidn" placeholder="NIDN" required><br>
        <select name="jenjangPendidikan" required>
            <option value="S2">S2</option>
            <option value="S3">S3</option>
        </select><br>
        <input type="submit" name="tambah" value="Tambah">
    </form>

    <!-- Tabel data dosen -->
    <h3>Data Dosen</h3>
    <?php if (count($dosen) > 0) : ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama Dosen</th>
                <th>NIDN</th>
                <th>Jenjang Pendidikan</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($dosen as $d) : ?>
                <tr>
                    <td><?php echo $d["ID"]; ?></td>
                    <td><?php echo $d["Nama"]; ?></td>
                    <td><?php echo $d["NIDN"]; ?></td>
                    <td><?php echo $d["JenjangPendidikan"]; ?></td>
                    <td>
                        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                            <input type="hidden" name="id" value="<?php echo $d["ID"]; ?>">
                            <input type="submit" name="edit" value="Edit">
                            <input type="submit" name="hapus" value="Hapus">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>Tidak ada data dosen.</p>
    <?php endif; ?>

    <!-- Form edit dosen -->
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit"])) : ?>
        <?php
        $id = $_POST["id"];
        $dosen = ambilDosenByID($conn, $id);

        if ($dosen !== false) :
        ?>
            <h3>Edit Dosen</h3>
            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <input type="hidden" name="id" value="<?php echo $dosen["ID"]; ?>">
                <input type="text" name="nama" placeholder="Nama Dosen" value="<?php echo $dosen["Nama"]; ?>" required><br>
                <input type="text" name="nidn" placeholder="NIDN" value="<?php echo $dosen["NIDN"]; ?>" required><br>
                <select name="jenjangPendidikan" required>
                    <option value="S2" <?php if ($dosen["JenjangPendidikan"] == "S2") echo "selected"; ?>>S2</option>
                    <option value="S3" <?php if ($dosen["JenjangPendidikan"] == "S3") echo "selected"; ?>>S3</option>
                </select><br>
                <input type="submit" name="perbarui" value="Perbarui">
            </form>
        <?php else : ?>
            <p>Data dosen tidak ditemukan.</p>
        <?php endif; ?>
    <?php endif; ?>

    <button><a href="index.php">Balik ke Halaman Utama</a></button>

</body>

</html>