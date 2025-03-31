<?php
header('Content-Type: application/json');

$host = "localhost";
$db = "flickseat_db";
$user = "root";
$pass = "";

// Connect to MySQL
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed"]);
    exit();
}

// Total Bookings
$totalBookingsQuery = "SELECT COUNT(*) AS total FROM tickets";
$totalBookingsResult = $conn->query($totalBookingsQuery);
$totalBookings = $totalBookingsResult->fetch_assoc()['total'] ?? 0;

// Total Sales (booked + used only)
$totalSalesQuery = "SELECT SUM(ticket_price) AS sales FROM tickets WHERE status IN ('booked', 'used')";
$totalSalesResult = $conn->query($totalSalesQuery);
$totalSales = $totalSalesResult->fetch_assoc()['sales'] ?? 0;

// Total Cancellations
$cancellationsQuery = "SELECT COUNT(*) AS cancelled FROM tickets WHERE status = 'cancelled'";
$cancellationsResult = $conn->query($cancellationsQuery);
$cancellations = $cancellationsResult->fetch_assoc()['cancelled'] ?? 0;
// Food and Drink Sales - ALL orders count (regardless of status)
$foodSalesQuery = "SELECT 
                    SUM(CASE 
                        WHEN food_id = 1 THEN 70 * quantity
                        WHEN food_id = 2 THEN 100 * quantity
                        WHEN food_id = 3 THEN 50 * quantity
                        ELSE 0
                    END) AS food_sales,
                    SUM(CASE 
                        WHEN drink_id = 1 THEN 20 * quantity
                        WHEN drink_id = 2 THEN 25 * quantity
                        WHEN drink_id = 3 THEN 30 * quantity
                        ELSE 0
                    END) AS drink_sales
                  FROM orders"; // Removed status condition completely

$foodSalesResult = $conn->query($foodSalesQuery);
$foodSalesData = $foodSalesResult->fetch_assoc();
$foodSales = ($foodSalesData['food_sales'] ?? 0) + ($foodSalesData['drink_sales'] ?? 0);

// Output JSON
echo json_encode([
    "totalBookings" => (int)$totalBookings,
    "totalSales" => (int)$totalSales,
    "cancellations" => (int)$cancellations,
    "foodsales" => (int)$foodSales
]);

$conn->close();
?>