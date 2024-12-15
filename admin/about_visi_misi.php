<?php
include '../koneksi/koneksi.php';
include 'header/header.php';
// Mengambil data visi dan misi
$sql = "SELECT * FROM visi_misi WHERE id = 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Proses update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visi = $_POST['visi'];
    $misi = $_POST['misi'];

    $update_sql = "UPDATE visi_misi SET visi = ?, misi = ? WHERE id = 1";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('ss', $visi, $misi);
    $stmt->execute();

    // Redirect ke halaman tampil setelah update
    header('Location: about_visi_misi.php');
    exit();
}
?>

<link rel="stylesheet" href="style/about.css">
<style>
/* Styling untuk form edit Visi & Misi */
form {
    width: 80%;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Heading */
h2 {
    font-family: 'Arial', sans-serif;
    color: #f29502;
    text-align: center;
    margin-bottom: 20px;
}

h3 {
    font-size: 1.2em;
    color: #555;
}

/* Styling untuk label */
label {
    font-size: 1.1em;
    color: #333;
    margin-bottom: 8px;
    display: block;
}

/* Styling untuk textarea */
textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 1em;
    color: #333;
    resize: vertical; /* Memungkinkan pengubahan ukuran hanya pada arah vertikal */
}

/* Styling untuk tombol submit */
input[type="submit"] {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 25px;
    background-color: #f29502;
    color: white;
    font-weight: bold;
    font-size: 1.1em;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    margin-top: 20px;
}

input[type="submit"]:hover {
    background-color: #aa6800;
    transform: translateY(-2px); /* Efek sedikit naik saat di-hover */
}

/* Responsivitas */
@media (max-width: 768px) {
    form {
        width: 95%;
    }

    textarea {
        font-size: 0.9em;
        padding: 10px;
    }

    input[type="submit"] {
        font-size: 1em;
    }
}

</style>

<h2 style="margin: 20px; text-align: center;">Edit Visi & Misi PAUD Qurrota A'yun</h2>

<form method="POST">
    <label for="visi">Visi:</label><br>
    <textarea id="visi" name="visi" rows="5" style="width: 100%; height: 100px; display: inline-table; margin-bottom: 20px;"><?php echo htmlspecialchars($row['visi']); ?></textarea><br><br>

    <label for="misi">Misi:</label><br>
    <textarea id="misi" name="misi" rows="5" style="width: 100%; height: 100px; display: inline-table; margin-bottom: 20px;"><?php echo htmlspecialchars($row['misi']); ?></textarea><br><br>

    <input type="submit" value="Simpan Perubahan">
</form>

<?php
$conn->close();
?>
