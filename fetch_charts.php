<?php
header('Content-Type: application/json');

$host = "localhost";
$db = "flickseat_db";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed"]);
    exit();
}

// Top 5 movies (based on movie_id only)
$topMoviesQuery = "
    SELECT movie_id AS movie, COUNT(*) AS bookings
    FROM tickets
    WHERE status IN ('booked', 'used')
    GROUP BY movie_id
    ORDER BY bookings DESC
    LIMIT 5
";
$topMoviesResult = $conn->query($topMoviesQuery);

$topMovies = [];
while ($row = $topMoviesResult->fetch_assoc()) {
    $topMovies[] = [
        "movie" => "Movie ID " . $row['movie'],
        "bookings" => (int)$row['bookings']
    ];
}

// Monthly ticket sales
$salesDataQuery = "
    SELECT MONTH(purchase_date) AS month, COUNT(*) AS tickets_sold
    FROM tickets
    WHERE status IN ('booked', 'used')
    GROUP BY MONTH(purchase_date)
    ORDER BY month ASC
";
$salesDataResult = $conn->query($salesDataQuery);

$salesData = [];
while ($row = $salesDataResult->fetch_assoc()) {
    $salesData[] = [
        "month" => (int)$row["month"],
        "tickets_sold" => (int)$row["tickets_sold"]
    ];
}

// Monthly food sales
// Modified food sales query
$foodSalesQuery = "
    SELECT MONTH(created_at) AS month, SUM(quantity) AS foods_sold
    FROM orders
    WHERE status = 'available' AND food_id IS NOT NULL
    GROUP BY MONTH(created_at)
    ORDER BY month ASC
";

// If you need to include drinks as well:
$foodDrinkSalesQuery = "
    SELECT MONTH(created_at) AS month, 
           SUM(CASE WHEN food_id IS NOT NULL THEN quantity ELSE 0 END) AS foods_sold,
           SUM(CASE WHEN drink_id IS NOT NULL THEN quantity ELSE 0 END) AS drinks_sold
    FROM orders
    WHERE status = 'available'
    GROUP BY MONTH(created_at)
    ORDER BY month ASC
";
$foodSalesResult = $conn->query($foodSalesQuery);

$foodSalesData = [];
while ($row = $foodSalesResult->fetch_assoc()) {
    $foodSalesData[] = [
        "month" => (int)$row["month"],
        "foods_sold" => (int)$row["foods_sold"]
    ];
}

// Final output
echo json_encode([
    "topMovies" => $topMovies,
    "salesData" => $salesData,
    "foodSalesData" => $foodSalesData
]);

$conn->close();
?>