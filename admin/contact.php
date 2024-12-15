<?php
include 'header/header.php';
include '../koneksi/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="style/contact.css">
</head>
<body>
    <!-- Hero Section -->
    <section id="hero">
    <div class="slider">
        <?php
        // Query untuk mengambil data slider
        $query = "SELECT * FROM sliders WHERE id >= 2";
        $result = $conn->query($query);
        
        $slides = []; // Array untuk menyimpan data gambar slider
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Pastikan URL gambar valid
                $imageUrl = !empty($row['image_url']) ? $row['image_url'] : 'uploads/sliders/default.jpg';
                $slides[] = $imageUrl; // Simpan URL gambar ke array
                echo '<div class="slide"><img src="' . htmlspecialchars($imageUrl) . '" alt="Slider Image" class="slider-image"></div>';
            }
        } else {
            // Tampilkan gambar default jika tidak ada slider
            echo '<div class="slide"><img src="uploads/sliders/default.jpg" alt="Default Image" class="slider-image"></div>';
            $slides[] = 'uploads/sliders/default.jpg'; // Tambahkan gambar default ke array
        }

        // Query untuk mengambil teks banner
        $query_banner = "SELECT text FROM banner_text WHERE id = 3";
        $result_banner = $conn->query($query_banner);
        $banner_text = "Lorem Ipsum is simply dummy text of the printing and typesetting industry."; // Default text
        if ($result_banner && $result_banner->num_rows > 0) {
            $banner_row = $result_banner->fetch_assoc();
            $banner_text = $banner_row['text']; // Ambil teks banner dari database
        }
        ?>
    </div>

    <!-- Indikator Dots -->
    <div class="dots">
        <?php
        // Output dots sesuai jumlah gambar slider
        foreach ($slides as $index => $image) {
            echo '<span class="dot" data-slide="' . $index . '"></span>';
        }
        ?>
    </div>

    <div class="hero-content">
        <h1>Contact</h1>
        <h2 onclick="window.location.href='contact_slider.php';">Edit Banner</h2>
        <!-- Tampilkan teks banner yang diambil dari database -->
        <p><?php echo htmlspecialchars($banner_text); ?></p>
    </div>
</section>

    <section class="contact-section">
        <?php
// Menghapus testimonial jika ada ID yang diberikan
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM submissions WHERE id = $delete_id";
    if ($conn->query($sql) === TRUE) {
        echo "Testimonial berhasil dihapus!";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Query untuk mengambil data testimonial dari tabel submissions
$sql = "SELECT * FROM submissions ORDER BY created_at DESC";
$result = $conn->query($sql);

// Tampilkan tabel
echo '<h1>Manajemen Testimonial</h1>';
echo '<table border="1" cellpadding="10">';
echo '<tr><th>ID</th><th>Nama</th><th>Email</th><th>Telepon</th><th>Pesan</th><th>Rating</th><th>Foto</th><th>Aksi</th></tr>';

if ($result->num_rows > 0) {
    // Loop untuk menampilkan data testimonial dalam tabel
    while ($row = $result->fetch_assoc()) {
        $rating = str_repeat('⭐', $row['rating']) . str_repeat('☆', 5 - $row['rating']);
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['name'] . '</td>';
        echo '<td>' . $row['email'] . '</td>';
        echo '<td>' . $row['phone'] . '</td>';
        echo '<td>' . $row['message'] . '</td>';
        echo '<td>' . $rating . '</td>';
        echo '<td><img src="../' . $row['photo'] . '" width="50" height="50"></td>';
        echo '<td><a href="admin.php?delete_id=' . $row['id'] . '" onclick="return confirm(\'Apakah Anda yakin ingin menghapus testimonial ini?\')">Hapus</a></td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="8">Tidak ada testimonial ditemukan.</td></tr>';
}

echo '</table>';
?>
</section>

<script>
            document.addEventListener('DOMContentLoaded', function () {
                const dots = document.querySelectorAll('.dot');
                const slides = document.querySelectorAll('.slider .slide');
                let currentIndex = 0;

                function updateSlider(index) {
                    // Hiding all slides
                    slides.forEach(slide => slide.style.display = 'none');
                    // Showing the current slide
                    slides[index].style.display = 'block';
                    
                    // Remove active class from all dots
                    dots.forEach(dot => dot.classList.remove('active'));
                    // Add active class to the current dot
                    dots[index].classList.add('active');
                }

                // Initially show the first slide
                updateSlider(currentIndex);

                // Add click event to each dot
                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        currentIndex = index;
                        updateSlider(currentIndex);
                    });
                });

                // Optional: Automatically change slides every 3 seconds
                setInterval(() => {
                    currentIndex = (currentIndex + 1) % slides.length;
                    updateSlider(currentIndex);
                }, 3000);
            });
        </script>
</body>
</html>
