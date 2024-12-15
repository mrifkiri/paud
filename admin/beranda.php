<?php
    include 'header/header.php';
    include '../koneksi/koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAUD Qurota A'yun</title>
    <link rel="stylesheet" href="style/beranda.css">
</head>
<body>
    <!-- Bagian Banner -->  
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
                $query_banner = "SELECT text FROM banner_text WHERE id = 1";
                $result_banner = $conn->query($query_banner);
                $banner_text = "Lorem Ipsum is simply dummy text of the printing and typesetting industry."; // Teks default
                if ($result_banner && $result_banner->num_rows > 0) {
                    $banner_row = $result_banner->fetch_assoc();
                    $banner_text = $banner_row['text']; // Ambil teks banner dari database
                }

                // Query untuk mengambil gambar circle-image dengan id = 1
                $query_circle = "SELECT image_url FROM sliders WHERE id = 1";
                $result_circle = $conn->query($query_circle);
                $circle_image = "uploads/sliders/default.jpg"; // Gambar circle default
                if ($result_circle && $result_circle->num_rows > 0) {
                    $circle_row = $result_circle->fetch_assoc();
                    $circle_image = !empty($circle_row['image_url']) ? $circle_row['image_url'] : 'uploads/sliders/default.jpg';
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
            <h1>Beranda</h1>
            <h2 onclick="window.location.href='beranda_slider.php';">Edit Banner</h2>
            <!-- Tampilkan teks banner yang diambil dari database -->
            <p><?php echo htmlspecialchars($banner_text); ?></p>
            <button class="btn-register" onclick="window.open('https://wa.me/6285714758408?text=Halo%20saya%20ingin%20mendaftarkan%20anak%20saya', '_blank')">Daftar</button>
        </div>
        <div class="circle-image"><img src="<?php echo htmlspecialchars($circle_image); ?>" alt="Circle Image"></div>
    </section>


    <!-- Bagian Konten Utama -->
    <main>
        <?php
            // Ambil data teks dari tabel content
            $content_result = $conn->query("SELECT element, text FROM info");
            $text_data = [];
            while ($row = $content_result->fetch_assoc()) {
                $text_data[$row['element']] = $row['text'];
            }

            // Ambil data gambar dari tabel images
            $image_result = $conn->query("SELECT image_url, position FROM info_foto ORDER BY position");
            $images = [];
            while ($row = $image_result->fetch_assoc()) {
                $images[$row['position']][] = $row['image_url'];
            }
        ?>
        <section class="main-info">
            <!-- Bagian Teks -->
            <div class="text-content">
                <p class="headmaster-talk">Headmaster Talk <a class="teks-info" href="beranda_info.php">Edit</a></p>
                <h2><?= htmlspecialchars($text_data['h2']) ?></h2>
                <p class="text-lorem"><?= nl2br(htmlspecialchars($text_data['text-lorem'])) ?></p>
                <button class="btn-signature" onclick="openSignaturePopup()">TTD</button>
                <p class="name"><?= htmlspecialchars($text_data['name']) ?></p>
                <p class="signature"><?= htmlspecialchars($text_data['signature']) ?></p>
            </div>

            <!-- Bagian Gambar -->
            <div class="images-content">
                <?php if (isset($images['large'][0])): ?>
                    <div class="img-box large"><img src="<?= htmlspecialchars($images['large'][0]) ?>" alt="Large Image"></div>
                <?php endif; ?>

                <?php if (!empty($images['small'])): ?>
                    <?php foreach ($images['small'] as $small_image): ?>
                        <div class="img-box small"><img src="<?= htmlspecialchars($small_image) ?>" alt="Small Image"></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Mengapa Memilih Kami -->
        <?php
            $main_paragraph_result = $conn->query("SELECT main_paragraph FROM reasons WHERE id = 1");
            $main_paragraph = $main_paragraph_result->fetch_assoc()['main_paragraph'];
            $reasons_result = $conn->query("SELECT * FROM reasons WHERE id >= 2 ORDER BY id ASC");
        ?>
        <section class="why-choose-us">
            <h2><a class="teks-info" href="beranda_reason.php">Edit</a> Mengapa Memilih PAUD Qurota A'yun?</h2>
            <p class="main"><?php echo $main_paragraph; ?></p>
            <div class="reasons">
                <?php while ($row = $reasons_result->fetch_assoc()): ?>
                    <div class="reason-item">
                        <img src="<?php echo $row['image_path']; ?>" alt="Reason Image">
                        <p><?php echo $row['description']; ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>        
        <!-- Kegiatan -->
        <section class="kegiatan">
            <h2 style="text-align: center; margin-top: -80px; font-size: 2em; color: #2b4d8f; margin: 15px;"><a class="teks-info" href="about_kegiatan.php">Edit</a> Kegiatan</h2>
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
                // Menyembunyikan semua slide
                slides.forEach(slide => slide.style.display = 'none');
                // Menampilkan slide yang dipilih
                slides[index].style.display = 'block';
                
                // Menghapus kelas aktif dari semua dot
                dots.forEach(dot => dot.classList.remove('active'));
                // Menambahkan kelas aktif pada dot yang dipilih
                dots[index].classList.add('active');
            }

            // Menampilkan slide pertama secara default
            updateSlider(currentIndex);

            // Dot pada Slides
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentIndex = index;
                    updateSlider(currentIndex);
                });
            });

            // Otomatis mengganti slides setiap 3 detik
            setInterval(() => {
                currentIndex = (currentIndex + 1) % slides.length;
                updateSlider(currentIndex);
            }, 3000);
        });

        function openSignaturePopup() {

            const popupWidth = 400;
            const popupHeight = 150;
            const left = (window.innerWidth / 2) - (popupWidth / 2);
            const top = (window.innerHeight / 2) - (popupHeight / 2);

            const popup = window.open('', 'Tanda Tangan Digital', `width=${popupWidth},height=${popupHeight},top=${top},left=${left}`);

            popup.document.write(`
                <html>
                    <head>
                        <title>Tanda Tangan Digital</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                text-align: center;
                                padding: 20px;
                                background-color: #f4f4f4;
                            }
                            img {
                                max-width: 100%;
                                height: auto;
                            }
                        </style>
                    </head>
                    <body>
                        <img src="../img/signature.png" alt="Tanda Tangan Digital">
                    </body>
                </html>
            `);
        }
    </script>
</body>
</html>
