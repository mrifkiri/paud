<?php
// Koneksi menggunakan mysqli
include '../koneksi/koneksi.php';

// Cek jika ID gambar ada di parameter URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data gambar yang akan dihapus dari database
    $sql = "SELECT foto FROM tentang WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // "i" untuk integer
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Cek apakah gambar ditemukan
    if ($result->num_rows > 0) {
        // Ambil nama file gambar
        $image = $result->fetch_assoc();
        $filePath = $image['foto'];

        // Hapus gambar dari server
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Hapus gambar dari database
        $delete_sql = "DELETE FROM tentang WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $id);
        $delete_stmt->execute();

        // Menampilkan pesan sukses
        echo "Gambar berhasil dihapus!";
    } else {
        echo "Gambar tidak ditemukan!";
    }
} else {
    echo "ID gambar tidak ditemukan!";
}

// Menutup koneksi
$stmt->close();
$delete_stmt->close();
$conn->close();
?>
