<?php
include '../koneksi/koneksi.php';
include 'header/header.php';

// Ambil data teks dari database
$content_result = $conn->query("SELECT element, text FROM info");
$contents = [];
while ($row = $content_result->fetch_assoc()) {
    $contents[$row['element']] = $row['text'];
}

// Ambil data gambar dari database
$image_result = $conn->query("SELECT id, image_url, position FROM info_foto");
$images = [];
while ($row = $image_result->fetch_assoc()) {
    $images[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Content</title>
    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
            margin: 80px;
            padding: 20px;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #007bff;
        }

        h2 {
            color: #0056b3;
            margin-top: 40px;
        }

        /* Form styling */
        form {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"], 
        textarea, 
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        textarea {
            resize: vertical;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Table styling */
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
            background-color: #0056b3;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                padding: 15px;
            }

            table th, 
            table td {
                padding: 5px;
            }

            table td img {
                max-width: 60px;
            }
        }
    </style>
</head>
<body>
    <h1>Edit Content</h1>
    <!-- Form Edit Teks -->
    <form action="update_info.php" method="post">
        <h2>Edit Text</h2>
        <label for="h2">Judul:</label><br>
        <textarea id="h2" name="h2" rows="2" cols="50"><?= htmlspecialchars($contents['h2']) ?></textarea><br><br>
        
        <label for="text-lorem">Teks:</label><br>
        <textarea id="text-lorem" name="text-lorem" rows="5" cols="50"><?= htmlspecialchars($contents['text-lorem']) ?></textarea><br><br>
        
        <label for="name">Nama:</label><br>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($contents['name']) ?>"><br><br>
        
        <label for="signature">Posisi:</label><br>
        <input type="text" id="signature" name="signature" value="<?= htmlspecialchars($contents['signature']) ?>"><br><br>
        
        <button type="submit">Save Changes</button>
    </form>

    <!-- Form Edit Images -->
    <h1>Edit Images</h1>
    <form action="update_info_foto.php" method="post" enctype="multipart/form-data">
        <table>
            <thead>
                <tr>
                    <th>Preview</th>
                    <th>Position</th>
                    <th>Upload New Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($images as $image): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($image['image_url']) ?>" alt="Image"></td>
                        <td><?= htmlspecialchars($image['position']) ?></td>
                        <td>
                            <input type="hidden" name="image_id[]" value="<?= $image['id'] ?>">
                            <input type="file" name="new_image_<?= $image['id'] ?>">
                        </td>
                        <td>
                            <button type="submit" name="update_image_id" value="<?= $image['id'] ?>">Update</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</body>
</html>
