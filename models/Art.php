<?php
include '../config/db.php';

class Art {
    public static function getAllArtworks($conn) {
        $stmt = $conn->prepare("SELECT artworks.*, users.name AS artist_name FROM artworks JOIN users ON artworks.artist_id = users.id");
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function getArtworkById($conn, $id) {
        $stmt = $conn->prepare("SELECT * FROM artworks WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function deleteArtwork($conn, $id) {
        $stmt = $conn->prepare("DELETE FROM artworks WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
