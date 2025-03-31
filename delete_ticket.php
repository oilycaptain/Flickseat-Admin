<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "flickseat_db");
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'DB connection failed']));
}

$ticket_id = $_POST['ticket_id'];

// Start transaction
$conn->begin_transaction();

try {
    // 1. Get the ticket data
    $stmt = $conn->prepare("SELECT * FROM tickets WHERE ticket_id = ?");
    $stmt->bind_param("i", $ticket_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ticket = $result->fetch_assoc();
    $stmt->close();

    if (!$ticket) {
        throw new Exception('Ticket not found');
    }

    // 2. Insert into recent_reservations
    $insertStmt = $conn->prepare("INSERT INTO recent_reservations 
                                (ticket_id, user_id, movie_id, showtime_id, seat_id, purchase_date, ticket_price, status) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insertStmt->bind_param("iiiisdis", 
        $ticket['ticket_id'],
        $ticket['user_id'],
        $ticket['movie_id'],
        $ticket['showtime_id'],
        $ticket['seat_id'],
        $ticket['purchase_date'],
        $ticket['ticket_price'],
        $ticket['status']
    );
    $insertStmt->execute();
    $insertStmt->close();

    // 3. Delete from tickets table
    $deleteStmt = $conn->prepare("DELETE FROM tickets WHERE ticket_id = ?");
    $deleteStmt->bind_param("i", $ticket_id);
    $deleteStmt->execute();
    
    if ($deleteStmt->affected_rows === 0) {
        throw new Exception('No ticket was deleted');
    }
    $deleteStmt->close();

    // 4. Ensure we only keep the 10 most recent reservations
    $cleanupStmt = $conn->prepare("DELETE FROM recent_reservations 
                                  WHERE reservation_id NOT IN (
                                      SELECT reservation_id FROM (
                                          SELECT reservation_id 
                                          FROM recent_reservations 
                                          ORDER BY deleted_at DESC 
                                          LIMIT 10
                                      ) AS temp
                                  )");
    $cleanupStmt->execute();
    $cleanupStmt->close();

    // Commit transaction
    $conn->commit();
    
    echo json_encode(['success' => true, 'message' => 'Ticket deleted and moved to recent reservations.']);
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>