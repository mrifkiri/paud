<?php
session_start();
include '../../koneksi/koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../about.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            $redirect_url = isset($_SESSION['redirect_to']) ? $_SESSION['redirect_to'] : '../about.php';
            header("Location: $redirect_url");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="logo-box" id="logo-box">
            <img src="../../img/logo.png" alt="SiPADI Logo">
        </div>
        <div class="login-container">
            <form id="login-form" action="login.php" method="POST">
                <input type="email" name="email" placeholder="e-mail" required>
                <input type="password" name="password" placeholder="password" required>
                <div class="options">
                    <label><input type="checkbox" name="remember"> Remember me</label>
                    <a href="forgot_password.php">Forgot Password?</a>
                </div>
                <div class="login-button">
                    <button type="submit">LOGIN</button>
                </div>
            </form>
        </div>
        <footer>
            <p>&copy; 2024 | IT 17 STIKOM EL Rahma</p>
        </footer>
    </div>
    <script>
            let clickCount = 0;

            document.getElementById("logo-box").addEventListener("click", function() {
                clickCount++;
                if (clickCount === 10) {
                    window.location.href = "../register/register.php";
                }
            });
        </script>
</body>
</html>
