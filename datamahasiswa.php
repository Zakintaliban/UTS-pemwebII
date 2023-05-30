<?php
// Include file konfigurasi database
include 'config.php';

// Fungsi untuk menambahkan data mahasiswa
function tambahMahasiswa($conn, $nama, $nim, $programStudi)
{
    $sql = "INSERT INTO Mahasiswa (Nama, NIM, ProgramStudi) VALUES ('$nama', '$nim', '$programStudi')";
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        return false;
    }
}

// Fungsi untuk mengambil semua data mahasiswa
function ambilSemuaMahasiswa($conn)
{
    $sql = "SELECT * FROM Mahasiswa";
    $result = mysqli_query($conn, $sql);
    $mahasiswa = array();

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $mahasiswa[] = $row;
        }
    }

    return $mahasiswa;
}

// Fungsi untuk mengambil data mahasiswa berdasarkan ID
function ambilMahasiswaByID($conn, $id)
{
    $sql = "SELECT * FROM Mahasiswa WHERE ID=$id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $mahasiswa = mysqli_fetch_assoc($result);
        return $mahasiswa;
    } else {
        return false;
    }
}

// Fungsi untuk memperbarui data mahasiswa
function perbaruiMahasiswa($conn, $id, $nama, $nim, $programStudi)
{
    $sql = "UPDATE Mahasiswa SET Nama='$nama', NIM='$nim', ProgramStudi='$programStudi' WHERE ID=$id";
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        return false;
    }
}

// Fungsi untuk menghapus data mahasiswa berdasarkan ID
function hapusMahasiswa($conn, $id)
{
    $sql = "DELETE FROM Mahasiswa WHERE ID=$id";
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        return false;
    }
}

// Proses tambah data mahasiswa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tambah"])) {
    $nama = $_POST["nama"];
    $nim = $_POST["nim"];
    $programStudi = $_POST["programStudi"];

    if (tambahMahasiswa($conn, $nama, $nim, $programStudi)) {
        echo "Data mahasiswa berhasil ditambahkan.";
    }
}

// Proses perbarui data mahasiswa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["perbarui"])) {
    $id = $_POST["id"];
    $nama = $_POST["nama"];
    $nim = $_POST["nim"];
    $programStudi = $_POST["programStudi"];

    if (perbaruiMahasiswa($conn, $id, $nama, $nim, $programStudi)) {
        echo "Data mahasiswa berhasil diperbarui.";
    }
}

// Proses hapus data mahasiswa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["hapus"])) {
    $id = $_POST["id"];

    if (hapusMahasiswa($conn, $id)) {
        echo "Data mahasiswa berhasil dihapus.";
    }
}

// Ambil semua data mahasiswa
$mahasiswa = ambilSemuaMahasiswa($conn);
?>

<!DOCTYPE html>
<html>

<head>
    <title>CRUD Mahasiswa</title>
    <link rel="stylesheet" type="text/css" href="style-crud.css">
</head>

<body>
    <h2>CRUD Mahasiswa</h2>

    <!-- Form tambah mahasiswa -->
    <h3>Tambah Mahasiswa</h3>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <input type="text" name="nama" placeholder="Nama Mahasiswa" required><br>
        <input type="text" name="nim" placeholder="NIM" required><br>
        <input type="text" name="programStudi" placeholder="Program Studi" required><br>
        <input type="submit" name="tambah" value="Tambah">
    </form>

    <!-- Tabel data mahasiswa -->
    <h3>Data Mahasiswa</h3>
    <?php if (count($mahasiswa) > 0) : ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama Mahasiswa</th>
                <th>NIM</th>
                <th>Program Studi</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($mahasiswa as $m) : ?>
                <tr>
                    <td><?php echo $m["ID"]; ?></td>
                    <td><?php echo $m["Nama"]; ?></td>
                    <td><?php echo $m["NIM"]; ?></td>
                    <td><?php echo $m["ProgramStudi"]; ?></td>
                    <td>
                        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                            <input type="hidden" name="id" value="<?php echo $m["ID"]; ?>">
                            <input type="submit" name="edit" value="Edit">
                            <input type="submit" name="hapus" value="Hapus">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>Tidak ada data mahasiswa.</p>
    <?php endif; ?>

    <!-- Form edit mahasiswa -->
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit"])) : ?>
        <?php
        $id = $_POST["id"];
        $mahasiswa = ambilMahasiswaByID($conn, $id);

        if ($mahasiswa !== false) :
        ?>
            <h3>Edit Mahasiswa</h3>
            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <input type="hidden" name="id" value="<?php echo $mahasiswa["ID"]; ?>">
                <input type="text" name="nama" placeholder="Nama Mahasiswa" value="<?php echo $mahasiswa["Nama"]; ?>" required><br>
                <input type="text" name="nim" placeholder="NIM" value="<?php echo $mahasiswa["NIM"]; ?>" required><br>
                <input type="text" name="programStudi" placeholder="Program Studi" value="<?php echo $mahasiswa["ProgramStudi"]; ?>" required><br>
                <input type="submit" name="perbarui" value="Perbarui">
            </form>
        <?php else : ?>
            <p>Data mahasiswa tidak ditemukan.</p>
        <?php endif; ?>
    <?php endif; ?>

    <button><a href="index.php">Balik ke Halaman Utama</a></button>

</body>

</html>