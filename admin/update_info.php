<?php
include '../koneksi/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("UPDATE info SET text = ?, updated_at = NOW() WHERE element = ?");
    
    $elements = ['h2', 'text-lorem', 'name', 'signature'];
    foreach ($elements as $element) {
        if (isset($_POST[$element])) {
            $text = $_POST[$element];
            $stmt->bind_param("ss", $text, $element);
            $stmt->execute();
        }
    }
    $stmt->close();
    header("Location: beranda_info.php");
    exit;
}
?>
