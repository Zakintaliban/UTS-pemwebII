<?php
include 'config.php';

function addMatakuliah($conn, $nama, $kodeMatakuliah, $deskripsi)
{
    $sql = "INSERT INTO Matakuliah (Nama, KodeMatakuliah, Deskripsi) VALUES ('$nama', '$kodeMatakuliah', '$deskripsi')";
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        return false;
    }
}

function getAllMatakuliah($conn)
{
    $sql = "SELECT * FROM Matakuliah";
    $result = mysqli_query($conn, $sql);
    $matakuliah = array();

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $matakuliah[] = $row;
        }
    }

    return $matakuliah;
}

function getMatakuliahByID($conn, $id)
{
    $sql = "SELECT * FROM Matakuliah WHERE ID=$id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $matakuliah = mysqli_fetch_assoc($result);
        return $matakuliah;
    } else {
        return false;
    }
}

function updateMatakuliah($conn, $id, $nama, $kodeMatakuliah, $deskripsi)
{
    $sql = "UPDATE Matakuliah SET Nama='$nama', KodeMatakuliah='$kodeMatakuliah', Deskripsi='$deskripsi' WHERE ID=$id";
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        return false;
    }
}

function deleteMatakuliah($conn, $id)
{
    $sql = "DELETE FROM Matakuliah WHERE ID=$id";
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tambah"])) {
    $nama = $_POST["nama"];
    $kodeMatakuliah = $_POST["kodeMatakuliah"];
    $deskripsi = $_POST["deskripsi"];

    if (addMatakuliah($conn, $nama, $kodeMatakuliah, $deskripsi)) {
        echo "Data matakuliah berhasil ditambahkan.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["perbarui"])) {
    $id = $_POST["id"];
    $nama = $_POST["nama"];
    $kodeMatakuliah = $_POST["kodeMatakuliah"];
    $deskripsi = $_POST["deskripsi"];

    if (updateMatakuliah($conn, $id, $nama, $kodeMatakuliah, $deskripsi)) {
        echo "Data matakuliah berhasil diperbarui.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["hapus"])) {
    $id = $_POST["id"];

    if (deleteMatakuliah($conn, $id)) {
        echo "Data matakuliah berhasil dihapus.";
    }
}

$matakuliah = getAllMatakuliah($conn);
?>

<!DOCTYPE html>
<html>

<head>
    <title>CRUD Matakuliah</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h2>CRUD Matakuliah</h2>

    <h3>Tambah Matakuliah</h3>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <input type="text" name="nama" placeholder="Nama Matakuliah" required><br>
        <input type="text" name="kodeMatakuliah" placeholder="Kode Matakuliah" required><br>
        <textarea name="deskripsi" placeholder="Deskripsi" required></textarea><br>
        <input type="submit" name="tambah" value="Tambah">
    </form>

    <h3>Data Matakuliah</h3>
    <?php if (count($matakuliah) > 0) : ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama Matakuliah</th>
                <th>Kode Matakuliah</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($matakuliah as $m) : ?>
                <tr>
                    <td><?php echo $m["ID"]; ?></td>
                    <td><?php echo $m["Nama"]; ?></td>
                    <td><?php echo $m["KodeMatakuliah"]; ?></td>
                    <td><?php echo $m["Deskripsi"]; ?></td>
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
        <p>Tidak ada data matakuliah.</p>
    <?php endif; ?>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit"])) : ?>
        <?php
        $id = $_POST["id"];
        $matakuliah = getMatakuliahByID($conn, $id);

        if ($matakuliah !== false) :
        ?>
            <h3>Edit Matakuliah</h3>
            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <input type="hidden" name="id" value="<?php echo $matakuliah["ID"]; ?>">
                <input type="text" name="nama" placeholder="Nama Matakuliah" value="<?php echo $matakuliah["Nama"]; ?>" required><br>
                <input type="text" name="kodeMatakuliah" placeholder="Kode Matakuliah" value="<?php echo $matakuliah["KodeMatakuliah"]; ?>" required><br>
                <textarea name="deskripsi" placeholder="Deskripsi" required><?php echo $matakuliah["Deskripsi"]; ?></textarea><br>
                <input type="submit" name="perbarui" value="Perbarui">
            </form>
        <?php else : ?>
            <p>Data matakuliah tidak ditemukan.</p>
        <?php endif; ?>
    <?php endif; ?>
    <button><a href="index.php">balik ke halaman utama</a></button>
</body>

</html>