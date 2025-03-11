<?php
include '../config/db.php';

class Order {
    public static function getOrdersByUser($conn, $buyer_id) {
        $stmt = $conn->prepare("SELECT orders.*, artworks.title, users.name AS artist_name 
                                FROM orders 
                                JOIN artworks ON orders.artwork_id = artworks.id
                                JOIN users ON artworks.artist_id = users.id
                                WHERE orders.buyer_id = ?");
        $stmt->bind_param("i", $buyer_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function placeOrder($conn, $buyer_id, $artwork_id) {
        $stmt = $conn->prepare("INSERT INTO orders (buyer_id, artwork_id, status) VALUES (?, ?, 'pending')");
        $stmt->bind_param("ii", $buyer_id, $artwork_id);
        return $stmt->execute();
    }

    public static function updateOrderStatus($conn, $order_id, $status) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        return $stmt->execute();
    }

    public static function deleteOrder($conn, $order_id) {
        $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        return $stmt->execute();
    }
}
?>
