<?php
include 'header/header.php';
include '../koneksi/koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin - Edit Content</title>
        <link rel="stylesheet" href="style/about.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
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
        $query_banner = "SELECT text FROM banner_text WHERE id = 2";
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
        <h1>About Us</h1>
        <h2 onclick="window.location.href='about_slider.php';">Edit Banner</h2>
        <!-- Tampilkan teks banner yang diambil dari database -->
        <p><?php echo htmlspecialchars($banner_text); ?></p>
    </div>
</section>


<!-- About Section -->
<?php
    // Ambil data teks tentang dari database
    $sql_text = "SELECT * FROM tentang_text LIMIT 2";
    $result_text = $conn->query($sql_text);
    $teks = $result_text->fetch_all(MYSQLI_ASSOC);

    // Ambil data gambar tentang dari database
    $sql_foto = "SELECT * FROM tentang LIMIT 3";
    $result_foto = $conn->query($sql_foto);
    $foto = $result_foto->fetch_all(MYSQLI_ASSOC);
?>

<section id="about">
    <h2><a class="buttonTentang" href="about_tentang.php">Edit</a> Tentang</h2>
    <div class="divider"></div>
    <div class="about-container">
        <div class="about-content">
            <div class="about-text">
                <?php
                    // Tampilkan teks pertama dari tabel tentang_text
                    if (!empty($teks)) {
                        echo '<p>' . htmlspecialchars($teks[0]['konten']) . '</p>';
                        echo '<p style="width: 205%;">' . htmlspecialchars($teks[1]['konten']) . '</p>';
                    }
                ?>
            </div>
            <div class="about-images">
                <div class="grid-item large">
                    <?php
                        // Tampilkan gambar pertama
                        if (!empty($foto)) {
                            echo '<img src="' . htmlspecialchars($foto[0]['foto']) . '" alt="Gambar 1">';
                        }
                    ?>
                </div>
                <div class="grid-item small">
                    <?php
                        // Tampilkan gambar kedua
                        if (!empty($foto[1])) {
                            echo '<img src="' . htmlspecialchars($foto[1]['foto']) . '" alt="Gambar 2">';
                        }
                    ?>
                </div>
                <div class="grid-item small">
                    <?php
                        // Tampilkan gambar ketiga
                        if (!empty($foto[2])) {
                            echo '<img src="' . htmlspecialchars($foto[2]['foto']) . '" alt="Gambar 3">';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>



    <!-- Vision & Mission Section -->
    <?php
        // Mengambil data visi dan misi
        $sql = "SELECT * FROM visi_misi WHERE id = 1";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    ?>

    <section class="visi" id="vision-mission">
        <h2 class="edit" onclick="window.location.href='about_visi_misi.php';">Edit</h2>
        <h2 style="margin-top: -20px;">Visi & Misi</h2>
        <div class="divider-visi" style="width: 190px; height: 3px; background-color: #f6a71e; margin: 10px auto; margin-bottom: 35px; margin-top: -10px;"></div>
        <div class="visi-grup">
            <p><?php echo nl2br($row['visi']); ?></p>
            <p><?php echo nl2br($row['misi']); ?></p>
        </div>
    </section>

    <!-- Curriculum Section -->
    <?php
        $query = "SELECT * FROM kurikulum";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo '<section id="curriculum">
                    <h2 style="color: #325E95;"><a class="buttonTentang" href="about_kurikulum.php">Edit</a> Kurikulum</h2>
                    <div class="divider-kurikulum"></div>';

            while ($row = $result->fetch_assoc()) {
                echo '<div class="curriculum-item-group">
                        <div class="curriculum-item" style="background-color: ' . $row['background_color'] . ';"></div>
                        <p>' . htmlspecialchars($row['description']) . '</p>
                    </div>';
            }

            echo '</section>';
        } else {
            echo "No records found.";
        }
    ?>
<?php
// Ambil data guru dari database
$query = "SELECT * FROM guru";
$result = $conn->query($query);
?>
<section id="staff">
    <h2><a class="buttonTentang" href="about_guru.php">Edit</a> Profil Guru & Staff</h2>
    <div class="divider"></div>

    <?php
    // Menampilkan data guru dalam beberapa baris
    $counter = 0;  // Untuk mengelompokkan tampilan dalam beberapa row
    while ($row = $result->fetch_assoc()) {
        if ($counter % 3 == 0) {  // Setiap 3 guru, buat baris baru
            echo '<div class="profile-row">';
        }

        // Menampilkan setiap profil guru
        echo '<div class="profile">';
        echo '<div class="profile-img"><img src="uploads/guru/' . $row['foto'] . '" alt="' . $row['nama'] . '"></div>';
        echo '<div class="profile-base ' . $row['warna'] . '">';
        echo '<p>' . $row['nama'] . '</p>';
        if ($row['posisi']) {
            echo '<p>' . $row['posisi'] . '</p>';
        }
        echo '</div>';
        echo '</div>';

        $counter++;

        if ($counter % 3 == 0) {
            echo '</div>';  // Menutup row setelah 3 profil
        }
    }

    // Jika ada sisa profil yang belum ditutup
    if ($counter % 3 != 0) {
        echo '</div>';
    }

    ?>
</section>
    <!-- Facilities Section -->
    <?php
        $query = "SELECT * FROM fasilitas";
        $result = $conn->query($query);

        // Cek jika ada data fasilitas
        if ($result->num_rows > 0) {
            $fasilitas = [];
            while ($row = $result->fetch_assoc()) {
                $fasilitas[] = $row; // Simpan setiap data fasilitas dalam array
            }
        } else {
            echo "Tidak ada data fasilitas.";
        }
    ?>
    <section class="why-choose-us" id="fasilitas">
        <h2><a class="buttonTentang" href="about_fasilitas.php">Edit</a> Fasilitas</h2>
        <div class="reasons">
            <?php
            // Tampilkan data fasilitas dari database
            if (isset($fasilitas) && count($fasilitas) > 0) {
                foreach ($fasilitas as $item) {
                    echo '<div class="reason-item">';
                    echo '<img src="uploads/fasilitas/' . $item['gambar'] . '" alt="' . $item['judul'] . '">';
                    echo '<p>' . $item['deskripsi'] . '</p>';
                    echo '</div>';
                }
            } else {
                echo "<p>Tidak ada fasilitas yang tersedia.</p>";
            }
            ?>
        </div>
    </section>
    <section class="kegiatan">
        <h2 style="text-align: center; margin-top: -80px; font-size: 2em; color: #2b4d8f; margin: 15px;"><a class="buttonTentang" href="about_kegiatan.php">Edit</a> Kegiatan</h2>
        <div class="divider" style="width: 100px; height: 3px; background-color: #f6a71e; margin: 10px auto; margin-bottom: 100px;"></div>
        <?php
            include 'aktifitas.php';
        ?>
    </section>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
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
                }, 5000);
            });
        </script>
</body>
</html>

<?php
// Close the database connection
?>
