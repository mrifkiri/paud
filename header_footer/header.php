<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/header.css">
</head>
<body>
    <header>
      <div class="logo">
            <img src="img/logo.png" alt="Logo" style="width: 40px; height: auto;" id="logo"> <!-- Replace "logo.png" with your logo file -->
        </div>  
        <nav>
            <ul>
                <li><a href="beranda.php" class="<?= basename($_SERVER['PHP_SELF']) == 'beranda.php' ? 'active' : '' ?>">Home</a></li>
                <li><a href="about.php" class="<?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>">About Us</a></li>
                <li><a href="contact.php" class="<?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>">Contact</a></li>
            </ul>
        </nav>
    </header>
    <script>
        document.addEventListener('scroll', function () {
            const header = document.querySelector('header');
            if (window.scrollY > 50) { // Jika halaman di-scroll lebih dari 50px
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        let clickCount = 0;
            document.getElementById("logo").addEventListener("click", function() {
                clickCount++;
                if (clickCount === 10) {
                    window.location.href = "admin/login_logout/login.php";
                }
            });
    </script>
</body>
</html>
