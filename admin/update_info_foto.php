<?php
include '../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['image_id'] as $id) {
        $file_input_name = "new_image_$id";
        if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/info/";
            $target_file = $target_dir . basename($_FILES[$file_input_name]["name"]);

            // Pindahkan file ke direktori target
            if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $target_file)) {
                // Update URL gambar di database
                $stmt = $conn->prepare("UPDATE info_foto SET image_url = ?, updated_at = NOW() WHERE id = ?");
                $stmt->bind_param("si", $target_file, $id);
                $stmt->execute();
            }
        }
    }
    header("Location: beranda_info.php");
    exit;
}
?>
