<?php
session_start();
include '../../koneksi/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {

        $upload_dir = '../uploads/';
        $avatar = $_FILES['avatar'];
        $avatar_name = basename($avatar['name']);
        $avatar_tmp = $avatar['tmp_name'];
        $avatar_ext = pathinfo($avatar_name, PATHINFO_EXTENSION);
        $avatar_path = $upload_dir . uniqid() . '.' . $avatar_ext;

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($avatar_ext), $allowed_types)) {
            if (move_uploaded_file($avatar_tmp, $avatar_path)) {
            } else {
                echo "Failed to upload avatar.";
                exit();
            }
        } else {
            echo "Invalid file type. Only JPG, PNG, and GIF are allowed.";
            exit();
        }
    } else {
        $avatar_path = '';
    }

    $query = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "An account with this email already exists.";
    } else {
        $query = "INSERT INTO user (name, email, password, avatar) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $avatar_path);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['email'] = $email;
            header("Location: ../login_logout/login.php");
            exit();
        } else {
            echo "Error: Could not register user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="register-wrapper">
        <div class="logo-box">
            <img src="../../img/logo.png" alt="SiPADI Logo">
        </div>
        <div class="register-container">
            <form action="register.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="e-mail" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <input type="file" name="avatar" accept="image/*">
                
                <div class="register-button">
                    <button type="submit">REGISTER</button>
                </div>
            </form>
        </div>
        <footer>
            <p>&copy; 2024 | IT 17 STIKOM EL Rahma</p>
        </footer>
    </div>
</body>
</html>
