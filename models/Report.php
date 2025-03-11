<?php
include '../config/db.php';

class Report {
    public static function getAllReports($conn) {
        $stmt = $conn->prepare("SELECT reported_artworks.*, artworks.title, users.name AS reported_by 
                                FROM reported_artworks 
                                JOIN artworks ON reported_artworks.artwork_id = artworks.id
                                JOIN users ON reported_artworks.user_id = users.id");
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function reportArtwork($conn, $artwork_id, $user_id, $reason) {
        $stmt = $conn->prepare("INSERT INTO reported_artworks (artwork_id, user_id, reason) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $artwork_id, $user_id, $reason);
        return $stmt->execute();
    }

    public static function deleteReport($conn, $artwork_id) {
        $stmt = $conn->prepare("DELETE FROM reported_artworks WHERE artwork_id = ?");
        $stmt->bind_param("i", $artwork_id);
        return $stmt->execute();
    }
}
?>
