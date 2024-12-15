<?php
    include '../koneksi/koneksi.php';
    include 'header/header.php';

    // Proses input atau edit data kegiatan
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Jika ada ID, berarti kita melakukan proses edit
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            // Edit kegiatan
            $id = $_POST['id'];
            $gambar = $_FILES['gambar']['name'] ? $_FILES['gambar']['name'] : $_POST['gambar_lama'];
            $tanggal = $_POST['tanggal'];
            $judul = $_POST['judul'];
            $topik = $_POST['topik'];
            $deskripsi = $_POST['deskripsi'];

            // Validasi file gambar jika ada gambar baru
            if ($_FILES['gambar']['name']) {
                // Validasi tipe file gambar
                $allowed_types = ['image/jpeg', 'image/png'];
                $file_type = $_FILES['gambar']['type'];
                if (!in_array($file_type, $allowed_types)) {
                    echo "Tipe file tidak diizinkan. Harap pilih gambar dengan format JPG atau PNG.";
                    exit();
                }

                // Validasi ukuran file gambar (maksimal 5MB)
                $max_size = 5 * 1024 * 1024; // 5MB
                if ($_FILES['gambar']['size'] > $max_size) {
                    echo "Ukuran file terlalu besar. Maksimal ukuran file adalah 5MB.";
                    exit();
                }

                // Pindahkan gambar baru jika ada
                $target_dir = "uploads/kegiatan/";
                $target_file = $target_dir . basename($gambar);
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                    // Gambar berhasil dipindahkan
                } else {
                    echo "Gagal meng-upload gambar baru.";
                    exit();
                }
            } else {
                // Jika tidak ada gambar baru, pakai gambar lama
                $target_file = $_POST['gambar_lama'];
            }

            // Query untuk mengupdate data kegiatan
            $query = "UPDATE kegiatan SET gambar = ?, tanggal = ?, judul = ?, topik = ?, deskripsi = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sssssi', $target_file, $tanggal, $judul, $topik, $deskripsi, $id);

            if ($stmt->execute()) {
                // Redirect ke halaman yang sama setelah berhasil update
                header('Location: about_kegiatan.php');
                exit();
            } else {
                echo "Terjadi kesalahan saat mengupdate data.";
            }
        } else {
            // Input kegiatan baru
            $gambar = $_FILES['gambar']['name'];
            $tanggal = $_POST['tanggal'];
            $judul = $_POST['judul'];
            $topik = $_POST['topik'];
            $deskripsi = $_POST['deskripsi'];

            // Validasi file gambar
            $allowed_types = ['image/jpeg', 'image/png'];
            $file_type = $_FILES['gambar']['type'];
            if (!in_array($file_type, $allowed_types)) {
                echo "Tipe file tidak diizinkan. Harap pilih gambar dengan format JPG atau PNG.";
                exit();
            }

            // Validasi ukuran file gambar (maksimal 5MB)
            $max_size = 5 * 1024 * 1024; // 5MB
            if ($_FILES['gambar']['size'] > $max_size) {
                echo "Ukuran file terlalu besar. Maksimal ukuran file adalah 5MB.";
                exit();
            }

            // Pindahkan gambar ke folder
            $target_dir = "uploads/kegiatan/";
            $target_file = $target_dir . basename($gambar);
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                // Gambar berhasil dipindahkan
            } else {
                echo "Gagal meng-upload gambar.";
                exit();
            }

            // Query untuk menyimpan data kegiatan
            $query = "INSERT INTO kegiatan (gambar, tanggal, judul, topik, deskripsi) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sssss', $target_file, $tanggal, $judul, $topik, $deskripsi);

            if ($stmt->execute()) {
                // Redirect ke halaman yang sama setelah berhasil input
                header('Location: about_kegiatan.php');
                exit();
            } else {
                echo "Terjadi kesalahan saat menambahkan data.";
            }
        }
    }

    // Proses hapus data kegiatan
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];

        $query = "SELECT gambar FROM kegiatan WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $delete_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        // Hapus gambar fisik jika ada
        if (file_exists($data['gambar'])) {
            unlink($data['gambar']);
        }

        // Hapus data kegiatan dari database
        $query = "DELETE FROM kegiatan WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $delete_id);

        if ($stmt->execute()) {
            // Redirect ke halaman yang sama setelah berhasil hapus
            header('Location: about_kegiatan.php');
            exit();
        } else {
            echo "Terjadi kesalahan saat menghapus data.";
        }
    }

    // Ambil semua data kegiatan terbaru
    $query = "SELECT * FROM kegiatan ORDER BY id DESC";
    $result = $conn->query($query);
    $kegiatanList = $result->fetch_all(MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kegiatan</title>
    <link rel="stylesheet" href="style/about.css">
    <style>
        h1, h2 {
            text-align: center;
            color: #333;
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
        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 50px;
            overflow: auto; /* Allow scrolling when content overflows */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            max-height: 90vh; /* Max height of 90% of viewport height */
            overflow-y: auto; /* Enable vertical scrolling */
        }

        /* Close button styling */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Bagian Input Kegiatan -->
    <section>
        <h2>Input Kegiatan</h2>
        <div class="divider" style="width: 100px; height: 3px; background-color: #f6a71e; margin: 10px auto;"></div>
    </section>

    <form action="about_kegiatan.php" method="POST" enctype="multipart/form-data" style="max-width: 600px; margin: 0 auto;">
        <input type="hidden" name="id" value="">
        <input type="hidden" name="gambar_lama" value="">

        <label for="gambar">Gambar Kegiatan:</label>
        <input type="file" name="gambar" id="gambar"><br><br>

        <label for="tanggal">Tanggal:</label>
        <input type="date" name="tanggal" id="tanggal" required><br><br>

        <label for="judul">Judul:</label>
        <input type="text" name="judul" id="judul" required><br><br>

        <label for="topik">Topik:</label>
        <input type="text" name="topik" id="topik" required><br><br>

        <label for="deskripsi">Deskripsi:</label>
        <textarea name="deskripsi" id="deskripsi" rows="5" required></textarea><br><br>

        <button type="submit" style="background-color: #f6a71e; padding: 10px 20px; border: none; cursor: pointer;">Tambah Kegiatan</button>
    </form>

    <!-- Bagian Daftar Kegiatan -->
    <section>
        <h2>Daftar Kegiatan</h2>
        <div class="divider" style="width: 100px; height: 3px; background-color: #f6a71e; margin: 10px auto;"></div>

        <table border="1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Judul</th>
                    <th>Tanggal</th>
                    <th>Topik</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kegiatanList as $index => $kegiatan): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><img src="<?= $kegiatan['gambar'] ?>" alt="Gambar" style="width: 100px;"></td>
                        <td><?= $kegiatan['judul'] ?></td>
                        <td><?= $kegiatan['tanggal'] ?></td>
                        <td><?= $kegiatan['topik'] ?></td>
                        <td><?= $kegiatan['deskripsi'] ?></td>
                        <td>
                            <!-- Tombol Edit yang memicu modal -->
                            <button class="editBtn" style="background-color: #f6a71e; padding: 10px 20px; border: none; cursor: pointer; color: #fff;" onclick="openModal(<?= $kegiatan['id'] ?>)">Edit</button> | 
                            <a style="background-color: #f44336; padding: 10px 20px; border: none; cursor: pointer; text-decoration: none; color: #fff;" href="about_kegiatan.php?delete_id=<?= $kegiatan['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Modal untuk Edit Kegiatan -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="modalContent"></div> <!-- Isi form akan dimuat di sini -->
        </div>
    </div>

    <script>
        // Fungsi untuk membuka modal
        function openModal(id) {
            const modal = document.getElementById('editModal');
            const modalContent = document.getElementById('modalContent');

            // Ambil form edit kegiatan dari 'about_edit_hapus_kegiatan.php'
            fetch(`about_edit_hapus_kegiatan.php?id=${id}`)
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;
                    modal.style.display = "block";
                })
                .catch(error => console.error('Error:', error));
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            document.getElementById('editModal').style.display = "none";
        }

        // Tutup modal jika klik di luar modal
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>

</body>
</html>