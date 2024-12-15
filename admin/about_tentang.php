<?php
include '../koneksi/koneksi.php';
include 'header/header.php';

// Ambil data teks yang sudah ada
$sql_text = "SELECT * FROM tentang_text LIMIT 2";
$result_text = $conn->query($sql_text);
$teks = $result_text->fetch_all(MYSQLI_ASSOC);

// Ambil data gambar yang sudah ada
$sql_foto = "SELECT * FROM tentang LIMIT 3";
$result_foto = $conn->query($sql_foto);
$foto = $result_foto->fetch_all(MYSQLI_ASSOC);

// Proses untuk mengupdate teks
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_text'])) {
    $konten1 = $_POST['konten1'];
    $konten2 = $_POST['konten2'];

    // Update teks
    $stmt = $conn->prepare("UPDATE tentang_text SET konten = ? WHERE id = 1");
    $stmt->bind_param("s", $konten1);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE tentang_text SET konten = ? WHERE id = 2");
    $stmt->bind_param("s", $konten2);
    $stmt->execute();

    echo "Teks berhasil diperbarui!";
}

// Proses untuk mengupload gambar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['gambar'])) {
    $totalFiles = count($_FILES['gambar']['name']);
    for ($i = 0; $i < $totalFiles; $i++) {
        // Pastikan hanya gambar yang diupload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["gambar"]["name"][$i]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Cek apakah file adalah gambar
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"][$i], $target_file)) {
                // Menyimpan path gambar ke database
                $stmt = $conn->prepare("INSERT INTO tentang (foto) VALUES (?)");
                $stmt->bind_param("s", $target_file);
                $stmt->execute();
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Only image files are allowed.";
        }
    }
    echo "Gambar berhasil diupload!";
}

    // Handle Hapus
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $query = "DELETE FROM tentang WHERE id = $id";
        if ($conn->query($query) === TRUE) {
            echo "Gambar berhasil dihapus.";
        } else {
            echo "Error: " . $conn->error;
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editing PAUD Qurrota A'yun</title>
    <link rel="stylesheet" href="style/about.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Modal Styles */  
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
}

.modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 30px;
    border: 1px solid #888;
    width: 80%;
    max-width: 500px;
    border-radius: 8px;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    position: absolute;
    top: 10px;
    right: 20px;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
}

/* General styling for the page */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
}

/* Styling for the main container */
.kelolaSlider {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px;
    background-color: #fff;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    margin-top: 100px;
}

h1 {
    font-size: 2em;
    text-align: center;
    color: #f0a500;
    margin-bottom: 30px;
}

/* Heading and form styling for text updates */
h2 {
    font-size: 1.8em;
    color: #f0a500;
    margin-bottom: 10px;
}

h3 {
    font-size: 1.2em;
    color: #555;
}

textarea {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 1em;
    color: #333;
    resize: vertical;
    margin-bottom: 15px;
}

button[type="submit"] {
    width: auto;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 25px;
    font-size: 1.1em;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

button[type="submit"]:hover {
    background-color: #e349a4;
    transform: translateY(-2px);
}

/* Gallery section */
.gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.image {
    text-align: center;
    max-width: 200px;
}

.image img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 30px;
}

table, th, td {
    border: 1px solid #ddd;
    text-align: center;
    padding: 10px;
}

th {
    background-color: #f6a71e;
    color: #fff;
}

td {
    background-color: #fafafa;
}

td img {
    width: 100px;
    height: auto;
    border-radius: 8px;
}

td a {
    text-decoration: none;
    color: #a349a4;
    margin: 0 5px;
}

td a:hover {
    color: #e349a4;
}

/* Set a fixed width for the 'Aksi' column */
td.aksi-column {
    width: 120px; /* Adjust the width as needed */
    text-align: center;
}

/* Style for the action icons */
td.aksi-column i {
    font-size: 20px; /* Icon size */
    margin: 0 5px; /* Space between icons */
    cursor: pointer;
}


/* Responsive Design */
@media (max-width: 768px) {
    .kelolaSlider {
        padding: 20px;
    }

    textarea {
        font-size: 0.9em;
        padding: 10px;
    }

    button[type="submit"] {
        font-size: 1em;
        padding: 8px 16px;
    }

    table {
        font-size: 0.9em;
    }

    .image img {
        width: 100%;
        max-width: 180px;
    }
}

    </style>
</head>
<body>

<div class="kelolaSlider">
    <h1>Kelola Konten PAUD Qurrota A'yun</h1>

    <!-- Form untuk Mengedit Teks -->
    <h2>Ubah Teks</h2>
    <form method="POST">
        <h3>Sejajar dengan foto</h3>
        <textarea class="konten1" name="konten1" rows="5" cols="50" style="width: 100%; height: 100px; display: inline-table; margin-bottom: 20px;"><?= htmlspecialchars($teks[0]['konten']) ?></textarea><br>
        <h3>Melebar dibawah foto</h3>
        <textarea class="konten2" name="konten2" rows="5" cols="50" style="width: 100%; height: 100px; display: inline-table; margin-bottom: 20px;"><?= htmlspecialchars($teks[1]['konten']) ?></textarea><br>
        <button type="submit" name="update_text">Update Teks</button>
    </form>

    <hr>

    <!-- Form untuk Mengupload Gambar -->
    <h2>Upload Gambar (Max 3 Gambar)</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="gambar[]" accept="image/*" multiple><br><br>
        <button type="submit">Upload Gambar</button>
    </form>
    
    <hr>
    
    <!-- Menampilkan Gambar yang Telah Diupload -->
<h2>Daftar Gambar</h2>
<table border="1">
    <thead>
        <tr>
            <th>No.</th>
            <th>Gambar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $query = "SELECT * FROM tentang ORDER BY id ASC"; // Urutkan berdasarkan ID (atau sesuai kebutuhan)
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $no = 1; // Variabel untuk nomor urut
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>"; // Menampilkan nomor urut dan kemudian meningkatkan variabel $no
            echo "<td><img src='" . $row['foto'] . "' alt='slider image' width='100'></td>";
            echo "<td class='aksi-column'>
                <a href='javascript:void(0);' class='edit-btn' data-id='" . $row['id'] . "' title='Edit'>
                    <i class='bi bi-pencil-square' style='color: #007bff;'></i>
                </a>
                <a href='?delete=" . $row['id'] . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus slider ini?\")' title='Hapus'>
                    <i class='bi bi-trash-fill' style='color: red;'></i>
                </a>
            </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>Belum ada gambar.</td></tr>";
    }
    ?>
    </tbody>
</table>

            <!-- Modal Edit Slider -->
            <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Edit Gambar</h2>
                <form id="editForm" method="post" enctype="multipart/form-data">
                    <label for="image">Upload Gambar Baru:</label>
                    <input type="file" name="image" id="image" required>
                    <button type="submit">Perbarui</button>
                </form>
            </div>
        </div>


    <script>
            // JavaScript to handle opening and closing the modal
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    var sliderId = this.getAttribute('data-id'); // Get the ID from the data attribute
                    // Optionally, load the slider info (if needed) to pre-fill the form fields here

                    var modal = document.getElementById("editModal");
                    modal.style.display = "block"; // Show the modal
                    document.getElementById('editForm').action = 'about_tentang_edit.php?id=' + sliderId; // Set form action dynamically
                });
            });

            document.querySelector('.close').addEventListener('click', function() {
                document.getElementById("editModal").style.display = "none"; // Close modal
            });

            // Close modal if user clicks anywhere outside the modal
            window.onclick = function(event) {
                if (event.target == document.getElementById("editModal")) {
                    document.getElementById("editModal").style.display = "none";
                }
            }
    </script>
</div>

</body>
</html>
