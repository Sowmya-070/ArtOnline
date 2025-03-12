<?php
session_start();
include '../config/db.php';

// User Registration
if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // Plain text password
    $role = trim($_POST['role']); // artist, buyer, admin

    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "<script>alert('❌ Email already registered!'); window.location.href='../views/register.php';</script>";
        exit();
    }
    $checkStmt->close();

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("SQL Prepare Error: " . $conn->error);
    }
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Registration successful!'); window.location.href='../views/login.php';</script>";
        exit();
    } else {
        echo "<script>alert('❌ Error registering user. Try again.'); window.location.href='../views/register.php';</script>";
    }
    $stmt->close();
}

// User Login
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // Plain text password comparison

    // Fetch user details
    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $db_password, $role);
        $stmt->fetch();

        if ($password === $db_password) { // Plain text password comparison
            $_SESSION['user_id'] = $id;
            $_SESSION['user_role'] = $role;
            $_SESSION['user_name'] = $name;

            header("Location: ../views/dashboard.php");
            exit();
        } else {
            echo "<script>alert('❌ Invalid Password!'); window.location.href='../views/login.php';</script>";
        }
    } else {
        echo "<script>alert('❌ User not found! Please check your email.'); window.location.href='../views/login.php';</script>";
    }
    $stmt->close();
}

// User Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../views/login.php");
    exit();
}
?>