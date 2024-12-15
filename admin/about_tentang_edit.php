<?php
    include '../koneksi/koneksi.php';

    // Ambil data slider berdasarkan ID
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = "SELECT * FROM tentang WHERE id = $id";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();

        if (!$row) {
            echo "Data tidak ditemukan!";
            exit;
        }
    } else {
        echo "ID tidak ditemukan!";
        exit;
    }

    // Handle Edit
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $targetDir = "uploads/tentang/";
        $targetFile = $targetDir . basename($_FILES['image']['name']);
        
        // Pastikan file yang di-upload adalah gambar
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Update gambar di database
                $query = "UPDATE tentang SET foto = '$targetFile' WHERE id = $id";
                if ($conn->query($query) === TRUE) {
                    echo "Gambar berhasil diperbarui.";
                    header("Location: about_tentang.php"); // Redirect setelah update berhasil
                    exit; // pastikan setelah header, eksekusi berhenti
                } else {
                    echo "Error: " . $conn->error;
                }
            } else {
                echo "Gagal mengupload gambar.";
            }
        } else {
            echo "Hanya file gambar yang diperbolehkan!";
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Gambar</title>
</head>
<body>
    <h1>Edit Gambar</h1>
    <form method="post" enctype="multipart/form-data">
        <label for="image">Upload Gambar Baru:</label>
        <input type="file" name="image" id="image" required>
        <button type="submit">Perbarui</button>
    </form>
</body>
</html>
