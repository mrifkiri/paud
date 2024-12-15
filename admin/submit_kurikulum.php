<?php
include '../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $background_color = $conn->real_escape_string($_POST['background_color']);
    
    if (isset($_POST['id'])) {
        // Update data kurikulum
        $id = $_POST['id'];
        $query = "UPDATE kurikulum SET title='$title', description='$description', background_color='$background_color' WHERE id='$id'";
        
        if ($conn->query($query) === TRUE) {
            echo "Kurikulum berhasil diperbarui.";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        // Menambahkan data kurikulum baru
        $query = "INSERT INTO kurikulum (title, description, background_color) VALUES ('$title', '$description', '$background_color')";
        
        if ($conn->query($query) === TRUE) {
            echo "Kurikulum berhasil ditambahkan.";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

$conn->close();
?>
