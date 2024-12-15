<?php
    include '../koneksi/koneksi.php';

    // Ambil data slider berdasarkan ID
    $id = $_GET['id'];
    $query = "SELECT * FROM sliders WHERE id = $id";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "Data tidak ditemukan!";
        exit;
    }

    // Handle Edit
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $targetDir = "uploads/sliders/";
        $targetFile = $targetDir . basename($_FILES['image']['name']);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $query = "UPDATE sliders SET image_url = '$targetFile' WHERE id = $id";
            if ($conn->query($query) === TRUE) {
                echo "Gambar berhasil diperbarui.";
                header("Location: beranda_slider.php");
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Gagal mengupload gambar.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Slider</title>
</head>
<body>
    <h1>Edit Slider</h1>
    <form method="post" enctype="multipart/form-data">
        <label for="image">Upload Gambar Baru:</label>
        <input type="file" name="image" id="image" required>
        <button type="submit">Perbarui</button>
    </form>
</body>
</html>
