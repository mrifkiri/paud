<?php
    include 'header_footer/header.php';
    include 'koneksi/koneksi.php';
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
    <!-- Banner Section -->
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
                $imageUrl = !empty($row['image_url']) ? $row['image_url'] : 'admin/uploads/sliders/default.jpg';
                $slides[] = $imageUrl; // Simpan URL gambar ke array
                echo '<div class="slide"><img src="admin/' . htmlspecialchars($imageUrl) . '" alt="Slider Image" class="slider-image"></div>';
            }
        } else {
            // Tampilkan gambar default jika tidak ada slider
            echo '<div class="slide"><img src="admin/uploads/sliders/default.jpg" alt="Default Image" class="slider-image"></div>';
            $slides[] = 'uploads/sliders/default.jpg'; // Tambahkan gambar default ke array
        }

        // Query untuk mengambil teks banner
        $query_banner = "SELECT text FROM banner_text WHERE id = 1";
        $result_banner = $conn->query($query_banner);
        $banner_text = "Lorem Ipsum is simply dummy text of the printing and typesetting industry."; // Default text
        if ($result_banner && $result_banner->num_rows > 0) {
            $banner_row = $result_banner->fetch_assoc();
            $banner_text = $banner_row['text']; // Ambil teks banner dari database
        }

        // Query untuk mengambil gambar circle-image dengan id = 1
        $query_circle = "SELECT image_url FROM sliders WHERE id = 1";
        $result_circle = $conn->query($query_circle);
        $circle_image = "uploads/sliders/default.jpg"; // Default circle image
        if ($result_circle && $result_circle->num_rows > 0) {
            $circle_row = $result_circle->fetch_assoc();
            $circle_image = !empty($circle_row['image_url']) ? $circle_row['image_url'] : 'admin/uploads/sliders/default.jpg';
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
        <h2>Pendidikan Anak Usia Dini</h2>
        <h1>Qurrota A'yun</h1>
        <!-- Tampilkan teks banner yang diambil dari database -->
        <p><?php echo htmlspecialchars($banner_text); ?></p>
        <button class="btn-register" onclick="window.open('https://wa.me/6285714758408?text=Halo%20saya%20ingin%20mendaftarkan%20anak%20saya', '_blank')">Daftar</button>
    </div>
    <div class="circle-image"><img src="admin/<?php echo htmlspecialchars($circle_image); ?>" alt="Circle Image"></div>
</section>


    <!-- Main Content Section -->
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
                <p class="headmaster-talk">Headmaster Talk</p>
                <h2><?= htmlspecialchars($text_data['h2']) ?></h2>
                <p class="text-lorem"><?= nl2br(htmlspecialchars($text_data['text-lorem'])) ?></p>
                <button class="btn-signature" onclick="openSignaturePopup()">TTD</button>
                <p class="name"><?= htmlspecialchars($text_data['name']) ?></p>
                <p class="signature"><?= htmlspecialchars($text_data['signature']) ?></p>
            </div>

            <!-- Bagian Gambar -->
            <div class="images-content">
                <?php if (isset($images['large'][0])): ?>
                    <div class="img-box large"><img src="admin/<?= htmlspecialchars($images['large'][0]) ?>" alt="Large Image"></div>
                <?php endif; ?>

                <?php if (!empty($images['small'])): ?>
                    <?php foreach ($images['small'] as $small_image): ?>
                        <div class="img-box small"><img src="admin/<?= htmlspecialchars($small_image) ?>" alt="Small Image"></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
        

<!-- Services Section -->
<section class="services">
    <a href="about.php#aktifitas">
        <div class="service-item student">
            <div>
                <h2>STUDENT</h2>
                <p>Events</p>
            </div>
        </div>
    </a>
    <a href="about.php#fasilitas">
        <div class="service-item fasilitas">
            <div>
                <h2>FASILITAS</h2>
                <p>Sekolah</p>
            </div>
        </div>
    </a>
    <a href="about.php#staff">
        <div class="service-item profil">
            <div>
                <h2>PROFIL</h2>
                <p>Guru & Staff</p>
            </div>
        </div>
    </a>
</section>
        <!-- Why Choose Us Section -->
        <?php
        $main_paragraph_result = $conn->query("SELECT main_paragraph FROM reasons WHERE id = 1");
        $main_paragraph = $main_paragraph_result->fetch_assoc()['main_paragraph'];
        $reasons_result = $conn->query("SELECT * FROM reasons WHERE id >= 2 ORDER BY id ASC");
        ?>
        <section class="why-choose-us">
        <h2>Mengapa Memilih PAUD Qurota A'yun?</h2>
        <p class="main"><?php echo $main_paragraph; ?></p>
        <div class="reasons">
            <?php while ($row = $reasons_result->fetch_assoc()): ?>
                <div class="reason-item">
                    <img src="admin/<?php echo $row['image_path']; ?>" alt="Reason Image">
                    <p><?php echo $row['description']; ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </section>        
        
        <section class="kegiatan">
        <h2 style="text-align: center; margin-top: -80px; font-size: 2em; color: #2b4d8f; margin: 15px;">Kegiatan</h2>
        <div class="divider" style="width: 100px; height: 3px; background-color: #f6a71e; margin: 10px auto; margin-bottom: 100px;"></div>
        <?php
            include 'aktifitas.php';
        ?>
    </section>


<!-- Testimonials Section -->
<?php
// Query untuk mengambil data testimonial dari tabel submissions
$sql = "SELECT * FROM submissions ORDER BY created_at DESC";
$result = $conn->query($sql);

// Memulai bagian HTML
echo '<section class="testimonials">
    <h2>Apa Kata Mereka Tentang</h2>
    <h2>PAUD Qurota A\'yun?</h2>    
    <div class="underline"></div>
    <div class="decorative-dots">
        <span class="dot" style="background-color: #E73262;"></span>
        <span class="dot" style="background-color: #F4AC34;"></span>
        <span class="dot" style="background-color: #325E95;"></span>
        <span class="dot" style="background-color: #00D6EE;"></span>
    </div>
    <div class="testimonials-carousel">';

// Mengecek jika ada data testimonial
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Menyusun rating berdasarkan nilai rating yang disimpan
        $rating = $row['rating'];
        $rating_stars = str_repeat('⭐', $rating) . str_repeat('☆', 5 - $rating);

        // Menampilkan testimonial dalam HTML
        echo '<div class="testimonial">
                <div class="profile">
                    <div class="profile-image"><img src="' . $row['photo'] . '" alt="Profile Image"></div>
                    <div class="profile-info">
                        <p class="name">' . $row['name'] . '</p>
                        <p class="role">' . $row['email'] . '</p>
                    </div>
                </div>
                <p class="review">' . $row['message'] . '</p>
                <div class="rating">' . $rating_stars . '</div>
              </div>';
    }
} else {
    echo '<p>No testimonials found.</p>';
}

echo '</div></section>';
?>

<script>
    function scrollLeft() {
        document.querySelector('.testimonials-carousel').scrollBy({
            left: -250,
            behavior: 'smooth'
        });
    }

    function scrollRight() {
        document.querySelector('.testimonials-carousel').scrollBy({
            left: 250,
            behavior: 'smooth'
        });
    }
</script>


        <!-- Join Section -->
        <section class="join">
            <h2>Ayo Bergabung dengan Keluarga Besar <a style="color: #E73262;">PAUD Qurota A'yun</a>!</h2>
            <p>PAUD Qurrota A’yun membuka Penerimaan Peserta Didik Baru. Jadikan tahun ini sebagai awal yang luar biasa bagi buah hati Anda untuk belajar dengan penuh semangat, kreativitas, dan nilai-nilai Islami. Daftar sekarang dan mari tumbuh bersama!</p>
            <button class="btn-join" onclick="window.open('https://wa.me/6285714758408?text=Halo%20saya%20ingin%20mendaftarkan%20anak%20saya', '_blank')">BERGABUNG</button>
        </section>
    </main>
    
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
                        <img src="img/signature.png" alt="Tanda Tangan Digital">
                    </body>
                </html>
            `);
        }
    </script>

    <?php
        include 'header_footer/footer.php';
    ?>
</body>
</html>
