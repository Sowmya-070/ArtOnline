<?php
include '../config/db.php';

class Wishlist {
    public static function getWishlistByUser($conn, $user_id) {
        $stmt = $conn->prepare("SELECT artworks.* FROM wishlist 
                                JOIN artworks ON wishlist.artwork_id = artworks.id 
                                WHERE wishlist.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function addToWishlist($conn, $user_id, $artwork_id) {
        $stmt = $conn->prepare("INSERT INTO wishlist (user_id, artwork_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $artwork_id);
        return $stmt->execute();
    }

    public static function removeFromWishlist($conn, $user_id, $artwork_id) {
        $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND artwork_id = ?");
        $stmt->bind_param("ii", $user_id, $artwork_id);
        return $stmt->execute();
    }
}
