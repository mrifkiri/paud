<?php
session_start();
include '../koneksi/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    header("Location: login_logout/login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT name, avatar FROM user WHERE id = '$user_id'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows > 0) {
    $user_data = $result_user->fetch_assoc();
    $user_name = $user_data['name'];
    $user_logo = $user_data['avatar'];
} else {
    $user_name = "Guest";
    $user_logo = "default_logo.png";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Document</title>
    <link rel="stylesheet" href="style/beranda.css">
</head>
<body>
    <header>
      <div class="logo">
            <img src="../img/logo.png" alt="Logo" style="width: 40px; height: auto;">
        </div>  
        <nav>
            <ul>
                <li><a href="beranda.php" class="<?= basename($_SERVER['PHP_SELF']) == 'beranda.php' ? 'active' : '' ?>">Home</a></li>
                <li><a href="about.php" class="<?= basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : '' ?>">About Us</a></li>
                <li><a href="contact.php" class="<?= basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : '' ?>">Contact</a></li>
            </ul>
        </nav>
        <div class="user">
            <div class="user-info">
                <p>Selamat Datang, <?php echo $user_name; ?></p>
                <img src="uploads/<?php echo $user_logo; ?>" alt="User Logo" class="user-logo" onclick="toggleUserMenu(event)">
                <div id="userDropdown" class="dropdown-content">
                    <a href="login_logout/logout.php">Logout</a>
                </div>
            </div>
        </div>
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

    function toggleUserMenu(event) {
        event.stopPropagation();
        const dropdown = document.getElementById("userDropdown");
        if (dropdown) {
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }
    }

    // Menutup dropdown saat area di luar diklik
    window.onclick = function() {
        const dropdown = document.getElementById("userDropdown");
        if (dropdown && dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
        }
    };
    </script>
</body>
</html>
