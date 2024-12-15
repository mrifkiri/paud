<?php
include 'header_footer/header.php';
include 'koneksi/koneksi.php';
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
            $query = "SELECT * FROM sliders WHERE id >= 2";
            $result = $conn->query($query);
            $slides = []; // Array untuk menyimpan data gambar slider
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $imageUrl = !empty($row['image_url']) ? $row['image_url'] : 'uploads/sliders/default.jpg';
                    $imageUrlWithAdmin = 'admin/' . $imageUrl; // Menambahkan prefix admin untuk URL gambar
                    // Simpan URL gambar ke array untuk indikator dots
                    $slides[] = $imageUrlWithAdmin;
                    echo '<div class="slide"><img src="' . htmlspecialchars($imageUrlWithAdmin) . '" alt="Slider Image" class="slider-image"></div>';
                }
            } else {
                echo '<div class="slide"><img src="uploads/sliders/default.jpg" alt="Default Image" class="slider-image"></div>';
                $slides[] = 'uploads/sliders/default.jpg';
            }

            // Query untuk mengambil teks banner
            $query_banner = "SELECT text FROM banner_text WHERE id = 3";
            $result_banner = $conn->query($query_banner);
            $banner_text = "Lorem Ipsum is simply dummy text of the printing and typesetting industry.";
            if ($result_banner && $result_banner->num_rows > 0) {
                $banner_row = $result_banner->fetch_assoc();
                $banner_text = $banner_row['text'];
            }
            ?>
        </div>

        <!-- Indikator Dots -->
        <div class="dots">
            <?php
            foreach ($slides as $index => $image) {
                echo '<span class="dot" data-slide="' . $index . '"></span>';
            }
            ?>
        </div>

        <div class="hero-content">
            <h1>Hubungi Kami</h1>
            <p><?php echo htmlspecialchars($banner_text); ?></p>
        </div>
    </section>

    <section class="contact-section">
        <div class="map-container">
            <iframe
            src="https://maps.google.com/maps?width=600&amp;height=400&amp;hl=en&amp;q=Jl. Cibanteng Proyek Ciampea No.49, Cihideung Ilir, Kec. Ciampea, Kabupaten Bogor, Jawa Barat 16620&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"
            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>


        <?php
            // Menangani submit form
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];
                $message = $_POST['message'];
                $rating = $_POST['rating'];

                // Menangani upload foto
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                    $photo = $_FILES['photo']['name'];
                    $tmp_name = $_FILES['photo']['tmp_name'];
                    $upload_dir = 'uploads/';

                    // Memindahkan file foto ke folder uploads
                    if (move_uploaded_file($tmp_name, $upload_dir . $photo)) {
                        $photo_path = $upload_dir . $photo;
                    } else {
                        echo "Gagal mengunggah foto.";
                        $photo_path = NULL;
                    }
                } else {
                    $photo_path = NULL;
                }

                // Menyimpan data ke database
                $sql = "INSERT INTO submissions (name, email, phone, message, rating, photo) 
                        VALUES ('$name', '$email', '$phone', '$message', '$rating', '$photo_path')";

                if ($conn->query($sql) === TRUE) {
                    echo "Data berhasil disimpan!";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        ?>
        <div class="form-info-wrapper">
            <div class="form-container">
                <p>Kontak Kami untuk Pertanyaan atau Dukungan</p>
                <form action="contact.php" method="post" enctype="multipart/form-data">
                    <div id="photo-preview" style="margin-top: 10px;">
                        <!-- Preview photo will be shown here -->
                    </div>
                    <label for="photo">Foto</label>
                    <input type="file" id="photo" name="photo" accept="image/*" onchange="previewImage(event)" required>
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" required>
                    
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>

                    <label for="phone">No Handphone</label>
                    <input type="tel" id="phone" name="phone" required>
                    
                    <label for="message">Pesan</label>
                    <textarea id="message" name="message" required></textarea>

                    <!-- Rating -->
                    <label>Rating:</label>
                    <div class="rating">
                        <input type="radio" id="star5" name="rating" value="1">
                        <label for="star5" class="star">&#9733;</label>
                        <input type="radio" id="star4" name="rating" value="2">
                        <label for="star4" class="star">&#9733;</label>
                        <input type="radio" id="star3" name="rating" value="3">
                        <label for="star3" class="star">&#9733;</label>
                        <input type="radio" id="star2" name="rating" value="4">
                        <label for="star2" class="star">&#9733;</label>
                        <input type="radio" id="star1" name="rating" value="5">
                        <label for="star1" class="star">&#9733;</label>
                    </div>

                    <!-- Upload Photo -->

                    <button type="submit">Kirim</button>
                </form>
            </div>
            <div class="info-container">
                <div class="info-item">
                    <span class="icon">📍</span>
                    <p><strong>Alamat Paud</strong><br> Lorem Ipsum is simply dummy text of the printing and typesetting industry</p>
                </div>
                <div class="info-item">
                    <span class="icon">📧</span>
                    <p><strong>Alamat Email</strong><br> qurrotun@gmail.com</p>
                </div>
                <div class="info-item">
                    <span class="icon">📞</span>
                    <p><strong>Telephone</strong><br> 084563484994</p>
                </div>
            </div>
        </div>
    </section>
    <?php include 'header_footer/footer.php'; ?>
    <script>
        const stars = document.querySelectorAll('.star');

        // Hover effect on stars
        stars.forEach((star, index) => {
            star.addEventListener('mouseover', () => {
                stars.forEach((s, i) => {
                    s.classList.toggle('hover', i <= index);
                });
            });

            star.addEventListener('mouseout', () => {
                stars.forEach((s) => {
                    s.classList.remove('hover');
                });
            });

            star.addEventListener('click', () => {
                stars.forEach((s, i) => {
                    s.style.color = i <= index ? '#f5a623' : '#ccc';
                });
            });
        });

        const defaultRating = 0;
        stars.forEach((star, index) => {
            star.style.color = index < defaultRating ? '#f5a623' : '#ccc';
        });

        function previewImage(event) {
            const previewContainer = document.getElementById("photo-preview");
            previewContainer.innerHTML = '';

            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
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
                }, 3000);
            });
        </script>
</body>
</html>
