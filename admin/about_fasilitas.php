<?php
    include '../koneksi/koneksi.php';
    include 'header/header.php';

// Cek apakah ada id yang dikirimkan untuk mengedit
$id = isset($_GET['id']) ? $_GET['id'] : null;
$judul = $deskripsi = $gambar = "";

// Jika ada id, ambil data fasilitas berdasarkan id
if ($id) {
    $query = "SELECT * FROM fasilitas WHERE id = $id";
    $result = $conn->query($query);
    $data = $result->fetch_assoc();
    $judul = $data['judul'];
    $deskripsi = $data['deskripsi'];
    $gambar = $data['gambar'];
}

// Proses input atau edit data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];

    // Proses upload gambar
    $gambar_baru = $gambar; // Jika gambar tidak diganti, tetap menggunakan gambar lama
    if ($_FILES['gambar']['name']) {
        // Jika ada gambar baru yang diupload
        $gambar_baru = 'fasilitas_' . time() . '.' . pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads/fasilitas/' . $gambar_baru); // Simpan gambar di folder 'uploads'
    }

    if ($id) {
        // Update data fasilitas jika id ada
        $query = "UPDATE fasilitas SET judul = '$judul', deskripsi = '$deskripsi', gambar = '$gambar_baru' WHERE id = $id";
    } else {
        // Insert data fasilitas jika id tidak ada
        $query = "INSERT INTO fasilitas (judul, deskripsi, gambar) VALUES ('$judul', '$deskripsi', '$gambar_baru')";
    }

    if ($conn->query($query)) {
        header("Location: about_fasilitas.php"); // Redirect ke halaman daftar fasilitas
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fitur hapus data fasilitas
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Ambil data gambar fasilitas berdasarkan ID untuk menghapus file gambar terkait
    $query = "SELECT gambar FROM fasilitas WHERE id = $delete_id";
    $result = $conn->query($query);
    $data = $result->fetch_assoc();
    $gambar_hapus = $data['gambar'];

    // Hapus data fasilitas dari database
    $query = "DELETE FROM fasilitas WHERE id = $delete_id";
    if ($conn->query($query)) {
        // Hapus gambar dari folder 'uploads'
        if ($gambar_hapus && file_exists('uploads/fasilitas/' . $gambar_hapus)) {
            unlink('uploads/fasilitas/' . $gambar_hapus); // Menghapus file gambar
        }
        header("Location: about_fasilitas.php"); // Redirect ke halaman daftar fasilitas setelah dihapus
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
    <title>Input & Edit Data Fasilitas</title>
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
        form input, form textarea, form button {
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
        .gambar-fasilitas {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>
</head>
<body>

    <h1>Form Input & Edit Fasilitas</h1>

    <!-- Form Input/Edit -->
    <form action="about_fasilitas.php<?php echo $id ? '?id=' . $id : ''; ?>" method="POST" enctype="multipart/form-data">
        <h2><?php echo $id ? 'Edit' : 'Tambah'; ?> Fasilitas</h2>
        
        <label for="judul">Judul Fasilitas:</label>
        <input type="text" id="judul" name="judul" value="<?php echo $judul; ?>" required>
        
        <label for="deskripsi">Deskripsi Fasilitas:</label>
        <textarea id="deskripsi" name="deskripsi" rows="4" required><?php echo $deskripsi; ?></textarea>
        
        <!-- Gambar Fasilitas -->
        <label for="gambar">Gambar Fasilitas:</label>
        <input type="file" id="gambar" name="gambar" accept="image/*">
        
        <?php if ($gambar): ?>
            <div>
                <h3>Gambar Saat Ini:</h3>
                <img src="uploads/fasilitas/<?php echo $gambar; ?>" alt="Gambar Fasilitas" class="gambar-fasilitas">
            </div>
        <?php endif; ?>
        
        <button type="submit"><?php echo $id ? 'Simpan Perubahan' : 'Tambah Fasilitas'; ?></button>
    </form>

    <!-- Tabel Data Fasilitas -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Ambil data fasilitas dari database
            $query = "SELECT * FROM fasilitas";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['judul'] . "</td>";
                echo "<td>" . $row['deskripsi'] . "</td>";
                echo "<td><img src='uploads/fasilitas/" . $row['gambar'] . "' alt='Gambar Fasilitas' class='gambar-fasilitas'></td>";
                echo "<td>
                    <a href='about_fasilitas.php?id=" . $row['id'] . "' class='btn-edit'>Edit</a>
                    <a href='about_fasilitas.php?delete_id=" . $row['id'] . "' class='btn-delete' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
                </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    
</body>
</html>
