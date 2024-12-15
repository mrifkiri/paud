<?php
    include '../koneksi/koneksi.php';
    include 'header/header.php';

// Cek apakah ada id yang dikirimkan untuk mengedit
$id = isset($_GET['id']) ? $_GET['id'] : null;
$nama = $posisi = $warna = $foto = "";

// Jika ada id, ambil data guru berdasarkan id
if ($id) {
    $query = "SELECT * FROM guru WHERE id = $id";
    $result = $conn->query($query);
    $data = $result->fetch_assoc();
    $nama = $data['nama'];
    $posisi = $data['posisi'];
    $warna = $data['warna'];
    $foto = $data['foto'];
}

// Proses input atau edit data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $posisi = $_POST['posisi'];
    $warna = $_POST['warna'];

    // Proses upload foto
    $foto_baru = $foto; // Jika foto tidak diganti, tetap menggunakan foto lama
    if ($_FILES['foto']['name']) {
        // Jika ada foto baru yang diupload
        $foto_baru = 'foto_' . time() . '.' . pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/guru/' . $foto_baru); // Simpan foto di folder 'uploads'
    }

    if ($id) {
        // Update data guru jika id ada
        $query = "UPDATE guru SET nama = '$nama', posisi = '$posisi', warna = '$warna', foto = '$foto_baru' WHERE id = $id";
    } else {
        // Insert data guru jika id tidak ada
        $query = "INSERT INTO guru (nama, posisi, warna, foto) VALUES ('$nama', '$posisi', '$warna', '$foto_baru')";
    }

    if ($conn->query($query)) {
        header("Location: about_guru.php"); // Redirect ke halaman daftar guru
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fitur hapus data guru
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Ambil data foto guru berdasarkan ID untuk menghapus file foto terkait
    $query = "SELECT foto FROM guru WHERE id = $delete_id";
    $result = $conn->query($query);
    $data = $result->fetch_assoc();
    $foto_hapus = $data['foto'];

    // Hapus data guru dari database
    $query = "DELETE FROM guru WHERE id = $delete_id";
    if ($conn->query($query)) {
        // Hapus foto dari folder 'uploads'
        if ($foto_hapus && file_exists('uploads/guru/' . $foto_hapus)) {
            unlink('uploads/guru/' . $foto_hapus);
        }
        header("Location: about_guru.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input & Edit Data Guru</title>
    <link rel="stylesheet" href="style/about.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            margin: 20px auto;
            width: 60%;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        form input, form select, form button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        form button:hover {
            background-color: #1e6c21;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #5c6bc0;
            color: white;
        }
        .btn-edit {
            background-color: #ff9800;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
        .btn-edit:hover {
            background-color: #e68900;
        }
        .btn-delete {
            background-color: #f44336;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
        .btn-delete:hover {
            background-color: #d32f2f;
        }
        .foto-guru {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>

    <h1>Form Input & Edit Data Guru</h1>

    <!-- Form Input/Edit -->
    <form action="about_guru.php<?php echo $id ? '?id=' . $id : ''; ?>" method="POST" enctype="multipart/form-data">
        <h2><?php echo $id ? 'Edit' : 'Tambah'; ?> Data Guru</h2>
        
        <label for="nama">Nama Guru:</label>
        <input type="text" id="nama" name="nama" value="<?php echo $nama; ?>" required>
        
        <label for="posisi">Posisi Guru:</label>
        <input type="text" id="posisi" name="posisi" value="<?php echo $posisi; ?>" required>
        
        <label for="warna">Warna:</label>
        <select id="warna" name="warna" required>
            <option value="blue" <?php echo ($warna == 'blue') ? 'selected' : ''; ?>>Blue</option>
            <option value="red" <?php echo ($warna == 'red') ? 'selected' : ''; ?>>Red</option>
            <option value="yellow" <?php echo ($warna == 'yellow') ? 'selected' : ''; ?>>Yellow</option>
            <option value="cyan" <?php echo ($warna == 'cyan') ? 'selected' : ''; ?>>Cyan</option>
        </select>

        <!-- Foto Guru -->
        <label for="foto">Foto Guru:</label>
        <input type="file" id="foto" name="foto" accept="image/*">
        
        <?php if ($foto): ?>
            <div>
                <h3>Foto Saat Ini:</h3>
                <img src="uploads/guru/<?php echo $foto; ?>" alt="Foto Guru" class="foto-guru">
            </div>
        <?php endif; ?>
        
        <button type="submit"><?php echo $id ? 'Simpan Perubahan' : 'Tambah Guru'; ?></button>
    </form>

    <!-- Tabel Data Guru -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Guru</th>
                <th>Posisi Guru</th>
                <th>Foto</th>
                <th>Warna</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM guru";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['nama'] . "</td>";
                echo "<td>" . $row['posisi'] . "</td>";
                echo "<td><img src='uploads/guru/" . $row['foto'] . "' alt='Foto Guru' class='foto-guru'></td>";
                echo "<td><span style='color: " . $row['warna'] . ";'>" . ucfirst($row['warna']) . "</span></td>";
                echo "<td>
                    <a href='about_guru.php?id=" . $row['id'] . "' class='btn-edit'>Edit</a>
                    <a href='about_guru.php?delete_id=" . $row['id'] . "' class='btn-delete' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    
</body>
</html>
