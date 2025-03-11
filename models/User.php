<?php
include '../config/db.php';

class User {
    public static function getAllUsers($conn) {
        $stmt = $conn->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function getUserById($conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function deleteUser($conn, $id) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
