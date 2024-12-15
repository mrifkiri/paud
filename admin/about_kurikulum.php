<?php
    include 'header/header.php'; 
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $title = '';
    $description = '';
    $background_color = '';

    if ($id) {
        // Ambil data kurikulum berdasarkan ID
        $query = "SELECT * FROM kurikulum WHERE id = '$id'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $title = $row['title'];
            $description = $row['description'];
            $background_color = $row['background_color'];
        }
    }

?>  
    <link rel="stylesheet" href="style/about.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Form Styles */
        form {
            background-color: white;
            padding: 30px;
            margin: 20px auto;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
            margin-top: 100px;
        }

        form h2 {
            font-size: 24px;
            color: #325E95;
            margin-bottom: 20px;
        }

        form label {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        form input[type="text"],
        form textarea,
        form input[type="color"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        form input[type="text"]:focus,
        form textarea:focus,
        form input[type="color"]:focus {
            border-color: #325E95;
            background-color: #fff;
        }

        form input[type="submit"] {
            padding: 12px 20px;
            background-color: #325E95;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #4A90E2;
        }

        /* Modal Styles */
        #popup-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 30px;
            max-width: 400px;
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            text-align: center;
        }

        #popup-modal h2 {
            font-size: 22px;
            color: #333;
            margin-bottom: 15px;
        }

        #popup-modal p {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }

        #close-popup {
            padding: 10px 20px;
            background-color: #EF476F;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        #close-popup:hover {
            background-color: #D63C56;
        }

        /* Daftar Kurikulum */
        h2 {
            font-size: 28px;
            color: #325E95;
            margin-bottom: 20px;
            text-align: center;
        }

        ul.kurikulum {
            list-style-type: none;
            padding: 0;
            margin: 0;
            max-width: 800px;
            margin: 0 auto;
        }

        li.kurikulum {
            display: flex;
            align-items: center;
            background-color: #fff;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        li.kurikulum:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        li.kurikulum span {
            font-weight: bold;
            color: #333;
            font-size: 18px;
            margin-right: 10px;
        }

        li.kurikulum p {
            color: #555;
            font-size: 16px;
            margin-right: auto;
            flex-grow: 1;
        }

        li.kurikulum a {
            text-decoration: none;
            background-color: #4A90E2;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        li.kurikulum a:hover {
            background-color: #325E95;
        }

        li.kurikulum .color-box {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-left: 15px;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            form {
                padding: 20px;
                width: 90%;
            }

            #popup-modal {
                width: 80%;
                padding: 20px;
            }

            form h2 {
                font-size: 20px;
            }

            form input[type="submit"] {
                font-size: 14px;
            }

            ul.kurikulum {
                width: 90%;
            }

            li.kurikulum {
                flex-direction: column;
                align-items: flex-start;
                padding: 10px;
            }

            li.kurikulum span {
                font-size: 16px;
            }

            li.kurikulum p {
                font-size: 14px;
            }

            li.kurikulum a {
                font-size: 14px;
                margin-top: 10px;
            }
        }

        @media (max-width: 480px) {
            form {
                padding: 15px;
            }

            #popup-modal {
                width: 90%;
                padding: 15px;
            }

            form h2 {
                font-size: 18px;
            }

            form input[type="submit"] {
                font-size: 14px;
            }

            li.kurikulum {
                flex-direction: column;
                padding: 8px;
            }

            li.kurikulum span {
                font-size: 14px;
            }

            li.kurikulum p {
                font-size: 12px;
            }

            li.kurikulum a {
                font-size: 12px;
                margin-top: 8px;
            }
        }

        /* Container untuk input color dan kotak warna */
        .color-picker-container {
            display: flex;
            align-items: center;
            position: relative;
        }

        .color-picker-container input[type="color"] {
            width: 40px;
            height: 40px;
            border: none;
            padding: 0;
            margin-right: 10px;
            cursor: pointer;
            visibility: hidden;  /* Menyembunyikan input color, hanya kotak yang terlihat */
        }

        .color-display {
            width: 40px;
            height: 40px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            cursor: pointer;
        }

        .color-display i {
            font-size: 20px;
            color: #325E95;
        }

        .color-display:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .color-display:active {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

    </style>

<!-- Form Input/Edit Kurikulum -->
<form id="kurikulum-form" action="submit_kurikulum.php" method="POST">
    <?php if ($id): ?>
        <input type="hidden" name="id" value="<?= $id ?>">
    <?php endif; ?>

    <label for="title">Judul Kurikulum:</label>
    <input type="text" id="title" name="title" value="<?= htmlspecialchars($title) ?>" required><br><br>

    <label for="description">Deskripsi Kurikulum:</label>
    <textarea id="description" name="description" required><?= htmlspecialchars($description) ?></textarea><br><br>

    <label for="background_color">Warna Latar Belakang:</label>
    <!-- Container untuk menampilkan kotak warna dan ikon -->
    <div class="color-picker-container">
        <input type="color" id="background_color" name="background_color" value="<?= htmlspecialchars($background_color) ?>" required>
        <div id="color-display" class="color-display" style="background-color: <?= htmlspecialchars($background_color) ?>;">
            <i class="bi bi-pencil-square"></i>
        </div>
    </div><br><br>

    <input type="submit" value="<?= $id ? 'Perbarui Kurikulum' : 'Simpan Kurikulum' ?>">
</form>


<!-- Modal untuk Menampilkan Pesan -->
<div id="popup-modal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background-color:white; padding:20px; border:1px solid #ccc; border-radius:5px; box-shadow:0 0 10px rgba(0,0,0,0.2);">
    <h2 id="modal-title">Informasi</h2>
    <p id="modal-message"></p>
    <button id="close-popup" onclick="closePopup()">Tutup</button>
</div>

<script>
// Menangani perubahan warna pada input color
document.getElementById("color-display").addEventListener("click", function() {
    document.getElementById("background_color").click();
});

document.getElementById("background_color").addEventListener("input", function(event) {
    document.getElementById("color-display").style.backgroundColor = event.target.value;
});

document.getElementById("kurikulum-form").addEventListener("submit", function(event) {
    event.preventDefault();  // Mencegah form untuk submit biasa

    var formData = new FormData(this);  // Ambil data form
    var xhr = new XMLHttpRequest();
    
    xhr.open("POST", "submit_kurikulum.php", true);  // Mengirim data ke submit_edit_kurikulum.php

    // Mengatur callback untuk ketika request selesai
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Tampilkan hasil di dalam modal
            var response = xhr.responseText;
            document.getElementById("modal-message").textContent = response;
            document.getElementById("popup-modal").style.display = "block";
        } else {
            // Tampilkan pesan error jika ada
            document.getElementById("modal-message").textContent = "Terjadi kesalahan: " + xhr.statusText;
            document.getElementById("popup-modal").style.display = "block";
        }
    };

    xhr.onerror = function() {
        document.getElementById("modal-message").textContent = "Tidak dapat menghubungi server.";
        document.getElementById("popup-modal").style.display = "block";
    };

    xhr.send(formData);  // Kirim data form
});

function closePopup() {
    document.getElementById("popup-modal").style.display = "none";
}
</script>

<?php
$query = "SELECT * FROM kurikulum";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo '<h2>Daftar Kurikulum</h2>';
    echo '<ul class="kurikulum">';

    while ($row = $result->fetch_assoc()) {
        echo '<li class="kurikulum">';
        echo '<span style="color: ' . $row['background_color'] . ';">' . htmlspecialchars($row['title']) . '</span>';
        echo ' - ' . htmlspecialchars($row['description']);
        echo ' <a class="kurikulum" href="about_kurikulum.php?id=' . $row['id'] . '">Edit</a>';
        echo '</li>';
    }

    echo '</ul>';
} else {
    echo "Tidak ada data kurikulum.";
}

$conn->close();
?>
