<?php
include '../koneksi/koneksi.php';
include 'header/header.php';

// Menangani form input alasan
if (isset($_POST['submit_reason'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/reasons/";
    $target_file = $target_dir . basename($image);

    // Mengupload gambar
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO reasons (title, description, image_path) VALUES ('$title', '$description', '$target_file')";
        if ($conn->query($sql) === TRUE) {
            echo "Data alasan berhasil disimpan!";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Gagal mengupload gambar.";
    }
}

// Menangani update main_paragraph
if (isset($_POST['update_main_paragraph'])) {
    $main_paragraph = $_POST['main_paragraph'];
    $sql = "UPDATE reasons SET main_paragraph='$main_paragraph' WHERE id=1";
    if ($conn->query($sql) === TRUE) {
        echo "Main paragraph berhasil diperbarui!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Menangani edit alasan
if (isset($_POST['update_reason'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];

    $sql = "UPDATE reasons SET title='$title', description='$description' WHERE id=$id";

    // Jika ada gambar baru
    if ($image) {
        $target_dir = "uploads/reasons/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        // Update dengan gambar baru
        $sql = "UPDATE reasons SET title='$title', description='$description', image_path='$target_file' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Data alasan berhasil diperbarui!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Menangani penghapusan alasan
if (isset($_GET['delete_reason'])) {
    $delete_id = $_GET['delete_reason'];
    $sql = "DELETE FROM reasons WHERE id=$delete_id";
    if ($conn->query($sql) === TRUE) {
        echo "Alasan berhasil dihapus!";
        header("Location: beranda_reason.php"); // Redirect agar halaman kembali dimuat setelah penghapusan
    } else {
        echo "Error: " . $conn->error;
    }
}

// Mengambil data main_paragraph dan alasan untuk ditampilkan
$main_paragraph_result = $conn->query("SELECT main_paragraph FROM reasons WHERE id = 1");
$main_paragraph = $main_paragraph_result->fetch_assoc()['main_paragraph'];

$reasons_result = $conn->query("SELECT * FROM reasons WHERE id >= 2");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Halaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;
            margin-top: 90px;
            padding: 50px;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            color: #4CAF50;
        }
        form {
            margin-bottom: 20px;
        }
        textarea, input[type="text"], input[type="file"], button {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .image-preview {
            max-width: 100px;
            border-radius: 4px;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        a.hapus {
            color: red;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Kelola Halaman PAUD</h1>

    <!-- Form Edit Paragraf Utama -->
    <form method="POST">
        <h2>Edit Paragraf Utama</h2>
        <textarea name="main_paragraph" rows="4" cols="50" required><?php echo $main_paragraph; ?></textarea><br><br>
        <button type="submit" name="update_main_paragraph">Update Main Paragraph</button>
    </form>

    <hr>

    <!-- Form Tambah Alasan -->
    <h2>Tambah Alasan</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Judul:</label>
        <input type="text" name="title" required><br>
        <label>Deskripsi:</label>
        <textarea name="description" rows="4" required></textarea><br>
        <label>Gambar:</label>
        <input type="file" name="image" required><br>
        <button type="submit" name="submit_reason">Tambah</button>
    </form>

    <hr>

    <!-- Daftar Alasan -->
    <h2>Daftar Alasan</h2>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $nomor = 1;
                while ($row = $reasons_result->fetch_assoc()):
            ?>
                <tr>
                    <td><?php echo $nomor++; ?></td>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><img src="<?php echo $row['image_path']; ?>" width="100"></td>
                    <td>
                        <form method="POST" enctype="multipart/form-data" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="text" name="title" value="<?php echo $row['title']; ?>" required>
                            <textarea name="description" rows="2" required><?php echo $row['description']; ?></textarea>
                            <input type="file" name="image">
                            <button type="submit" name="update_reason">Edit</button>
                        </form>
                        <a class="hapus" href="?delete_reason=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
