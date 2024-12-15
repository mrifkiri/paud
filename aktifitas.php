<?php

        // Query untuk mengambil data kegiatan
        $query = "SELECT * FROM kegiatan ORDER BY tanggal DESC";
        $result = $conn->query($query);

        // Cek apakah ada data
        if ($result->num_rows > 0) {
            // Ambil data dalam bentuk array asosiatif
            $kegiatan = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $kegiatan = [];
        }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktifitas</title>
    <link rel="stylesheet" href="style/aktifitas.css">
</head>
<body>
<div class="carousel" id="aktifitas">
    <!-- List Item -->
    <div class="list">
        <?php foreach ($kegiatan as $item): ?>
            <div class="item">
            <img src="admin/<?= htmlspecialchars($item['gambar']); ?>" alt="Kegiatan" />
                <div class="content">
                    <div class="author"><?= date("d F Y", strtotime($item['tanggal'])); ?></div>
                    <div class="title"><?= htmlspecialchars($item['judul']); ?></div>
                    <div class="topic"><?= htmlspecialchars($item['topik']); ?></div>
                    <div class="des">
                        <?= nl2br(htmlspecialchars($item['deskripsi'])); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- List Thumbnail -->
    <div class="thumbnail">
        <?php foreach ($kegiatan as $item): ?>
            <div class="item">
                <img src="admin/<?= htmlspecialchars($item['gambar']); ?>" alt="Thumbnail" />
                <div class="content">
                    <div class="title"><?= htmlspecialchars($item['topik']); ?></div>
                    <div class="description"><?= htmlspecialchars($item['deskripsi']); ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Next Prev -->
    <div class="arrows">
        <button id="prev"><</button>
        <button id="next">></button>
    </div>

    <!-- Time Running -->
    <div class="time"></div>
</div>


<!-- Tombol Berikutnya -->
<button class="carousel-btn next" onclick="nextSlide()" style="position: fixed;">&#10095;</button>
<script>
    //step 1: get DOM
let nextDom = document.getElementById('next');
let prevDom = document.getElementById('prev');

let carouselDom = document.querySelector('.carousel');
let SliderDom = carouselDom.querySelector('.carousel .list');
let thumbnailBorderDom = document.querySelector('.carousel .thumbnail');
let thumbnailItemsDom = thumbnailBorderDom.querySelectorAll('.item');
let timeDom = document.querySelector('.carousel .time');

thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);
let timeRunning = 3000;
let timeAutoNext = 7000;

nextDom.onclick = function(){
    showSlider('next');    
}

prevDom.onclick = function(){
    showSlider('prev');    
}
let runTimeOut;
let runNextAuto = setTimeout(() => {
    next.click();
}, timeAutoNext)
function showSlider(type){
    let  SliderItemsDom = SliderDom.querySelectorAll('.carousel .list .item');
    let thumbnailItemsDom = document.querySelectorAll('.carousel .thumbnail .item');
    
    if(type === 'next'){
        SliderDom.appendChild(SliderItemsDom[0]);
        thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);
        carouselDom.classList.add('next');
    }else{
        SliderDom.prepend(SliderItemsDom[SliderItemsDom.length - 1]);
        thumbnailBorderDom.prepend(thumbnailItemsDom[thumbnailItemsDom.length - 1]);
        carouselDom.classList.add('prev');
    }
    clearTimeout(runTimeOut);
    runTimeOut = setTimeout(() => {
        carouselDom.classList.remove('next');
        carouselDom.classList.remove('prev');
    }, timeRunning);

    clearTimeout(runNextAuto);
    runNextAuto = setTimeout(() => {
        next.click();
    }, timeAutoNext)
}
</script>
</body>
</html>
