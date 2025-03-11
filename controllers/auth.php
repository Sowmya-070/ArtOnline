<?php
session_start();
include '../config/db.php';

// User Registration
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // No hashing applied
    $role = $_POST['role']; // artist, buyer, admin

    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    if ($conn->query($sql)) {
        header("Location: ../views/login.php");
    } else {
        echo "Error: " . $conn->error;
    }
}

// User Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) { // Direct comparison
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: ../views/dashboard.php");
        } else {
            echo "Invalid Password!";
        }
    } else {
        echo "User not found!";
    }
}

// User Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../views/login.php");
}
?>