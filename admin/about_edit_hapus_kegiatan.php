<?php
    include '../koneksi/koneksi.php';
    if (isset($_GET['id'])) {
        // Ambil data berdasarkan ID kegiatan
        $id = $_GET['id'];
        $query = "SELECT * FROM kegiatan WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id); // 'i' untuk integer
        $stmt->execute();
        $result = $stmt->get_result();
        $kegiatan = $result->fetch_assoc();
    }

    // Form untuk edit kegiatan
    if (isset($kegiatan)):
?>
    <form action="about_kegiatan.php" method="POST" enctype="multipart/form-data" style="max-width: 600px; margin: 0 auto;">
        <input type="hidden" name="id" value="<?= $kegiatan['id']; ?>">
        <input type="hidden" name="gambar_lama" value="<?= $kegiatan['gambar']; ?>">

        <label for="gambar">Gambar Kegiatan:</label>
        <input type="file" name="gambar" id="gambar"><br><br>
        <img src="<?= $kegiatan['gambar']; ?>" alt="Gambar Kegiatan" style="width: 100px; height: auto;"><br><br>

        <label for="tanggal">Tanggal:</label>
        <input type="date" name="tanggal" id="tanggal" value="<?= $kegiatan['tanggal']; ?>" required><br><br>

        <label for="judul">Judul:</label>
        <input type="text" name="judul" id="judul" value="<?= $kegiatan['judul']; ?>" required><br><br>

        <label for="topik">Topik:</label>
        <input type="text" name="topik" id="topik" value="<?= $kegiatan['topik']; ?>" required><br><br>

        <label for="deskripsi">Deskripsi:</label>
        <textarea name="deskripsi" id="deskripsi" rows="5" required><?= $kegiatan['deskripsi']; ?></textarea><br><br>

        <button type="submit" style="background-color: #f6a71e; padding: 10px 20px; border: none; cursor: pointer;">Update Kegiatan</button>
    </form>
<?php endif; ?>
