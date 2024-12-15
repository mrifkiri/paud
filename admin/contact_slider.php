<?php
    include '../koneksi/koneksi.php';
    include 'header/header.php';

    // Handle Tambah
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_slider'])) {
        $targetDir = "uploads/sliders/";
        $targetFile = $targetDir . basename($_FILES['image']['name']);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $query = "INSERT INTO sliders (image_url) VALUES ('$targetFile')";
            if ($conn->query($query) === TRUE) {
                echo "Gambar berhasil ditambahkan.";
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Gagal mengupload gambar.";
        }
    }

    // Handle Hapus
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $query = "DELETE FROM sliders WHERE id = $id";
        if ($conn->query($query) === TRUE) {
            echo "Gambar berhasil dihapus.";
        } else {
            echo "Error: " . $conn->error;
        }
    }

    // Handle Edit
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_slider'])) {
        $id = $_POST['id'];
        $targetDir = "uploads/sliders/";
        $targetFile = $targetDir . basename($_FILES['image']['name']);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $query = "UPDATE sliders SET image_url = '$targetFile' WHERE id = $id";
            if ($conn->query($query) === TRUE) {
                echo "Gambar berhasil diperbarui.";
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "Gagal mengupload gambar.";
        }
    }
    // Update Banner Text (di about_slider.php atau halaman yang relevan)
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_banner'])) {
        $new_banner_text = mysqli_real_escape_string($conn, $_POST['banner_text']);
        
        // Update query dengan escaped text
        $query = "UPDATE banner_text SET text = '$new_banner_text' WHERE id = 3";
        if ($conn->query($query) === TRUE) {
        } else {
            echo "Error: " . $conn->error;
        }
    }


    // Ambil teks banner saat ini
    $query_banner = "SELECT text FROM banner_text WHERE id = 3";
    $result_banner = $conn->query($query_banner);
    $banner_text = "Lorem Ipsum is simply dummy text."; // Default text
    if ($result_banner && $result_banner->num_rows > 0) {
        $banner_row = $result_banner->fetch_assoc();
        $banner_text = $banner_row['text'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Slider</title>
    <link rel="stylesheet" href="style/about.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Container for Slider Management */
        .kelolaSlider {
            max-width: 1200px;
            margin: 50px auto;
            padding: 40px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Heading Styles */
        h1 {
            text-align: center;
            font-size: 2.5em;
            color: #f0a500;
            margin-bottom: 30px;
        }

        h2 {
            font-size: 1.8em;
            color: #333;
            margin-top: 20px;
        }

        /* Banner Text Styling */
        .banner {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
            padding: 10px;
            background-color: #f4f4f4;
            border: 2px solid #ccc;
            border-radius: 6px;
        }

        .banner_text {
            width: 100%;
            height: 100px;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            resize: vertical;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 12px;
        }

        th {
            background-color: #f6a71e;
            color: #fff;
            font-weight: bold;
        }

        td {
            background-color: #fafafa;
        }

        td img {
            width: 100px;
            height: auto;
            border-radius: 8px;
        }

        /* Aksi Column (Action) Styling */
        .aksi-column {
            width: 140px; /* Fixed width for action column */
            text-align: center;
        }

        .aksi-column i {
            font-size: 18px;
            margin: 0 10px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .aksi-column i:hover {
            transform: scale(1.1);
        }

        .aksi-column a {
            text-decoration: none;
        }

        /* Modal Styles */
        .modal {
            display: none; /* Hide by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Button Styles */
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            border: none;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button[type="submit"]:hover {
            background-color: #e349a4;
            transform: translateY(-2px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .kelolaSlider {
                padding: 20px;
            }

            table, th, td {
                font-size: 0.9em;
            }

            .banner {
                font-size: 20px;
            }

            .banner_text {
                font-size: 14px;
                height: 80px;
            }

            button[type="submit"] {
                font-size: 1em;
                padding: 8px 16px;
            }

            .aksi-column i {
                font-size: 16px;
                margin: 0 8px;
            }
        }
    </style>
</head>
<body>
    <div class="kelolaSlider">
        <h1>Kelola Slider</h1>

        <!-- Banner Content -->
        <div class="banner">
            <!-- Display the current banner text from the database -->
            <?php echo htmlspecialchars($banner_text); ?>
        </div>

        <!-- Form to Update Banner Text -->
        <h2>Edit Kata-Kata Banner</h2>
        <form method="post">
            <textarea class="banner_text" name="banner_text" id="banner_text" style="width: 100%; height: 100px; display: inline-table; margin-bottom: 20px;" required><?php echo htmlspecialchars($banner_text); ?></textarea>
            <button type="submit" name="update_banner">Update Banner Text</button>
        </form>

        <!-- Form Tambah -->
        <h2>Tambah Slider</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="add_slider_image">Upload Gambar:</label>
        <input type="file" name="image" id="add_slider_image" required>
        <button type="submit" name="add_slider">Tambah Slider</button>
    </form>

        <h2>Daftar Slider</h2>
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
                $query = "SELECT * FROM sliders WHERE id >= 2";
                $result = $conn->query($query);


            if ($result->num_rows > 0) {
                $no = 1; // Variabel untuk nomor urut
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>"; // Menampilkan nomor urut dan kemudian meningkatkan variabel $no
                    echo "<td><img src='" . $row['image_url'] . "' alt='slider image' width='100'></td>";
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
                echo "<tr><td colspan='4'>Belum ada slider.</td></tr>";
            }
            ?>
            </tbody>
        </table>

        <!-- Modal Edit Slider -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Edit Slider</h2>
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
                    document.getElementById('editForm').action = 'about_edit_slider.php?id=' + sliderId; // Set form action dynamically
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
