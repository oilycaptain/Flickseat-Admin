<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="styles.css">
    <title>Flickseat</title>
</head>
<body>
<aside class="sidebar">
        <header class="sidebar-header">
            <a href="#" class="header-logo">
                <img src="Logo.png" alt="logo">
            </a>
            <button class="toggler sidebar-toggler">
                <span class="material-symbols-rounded">chevron_left</span>
            </button>
            <button class="toggler menu-toggler">
                <span class="material-symbols-rounded">menu</span>
            </button>
        </header>
        <nav class="sidebar-nav">
            <ul class="nav-list primary-nav"> 
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link">
                        <span class="material-symbols-rounded">dashboard</span>
                        <span class="nav-label">Dashboard</span>
                    </a>
                    <span class="nav-tooltip">Dashboard</span>
                </li>
                <li class="nav-item">
                    <a href="reservation.php" class="nav-link">
                        <span class="material-symbols-rounded">book</span>
                        <span class="nav-label">Reservations</span>
                    </a>
                    <span class="nav-tooltip">Reservations</span>
                </li>
                <li class="nav-item">
                    <a href="notif.html" class="nav-link">
                        <span class="material-symbols-rounded">notifications</span>
                        <span class="nav-label">Notifications</span>
                    </a>
                    <span class="nav-tooltip">Notifications</span>
                </li>
                <li class="nav-item">
                    <a href="food.php" class="nav-link">
                        <span class="material-symbols-rounded">fastfood</span>
                        <span class="nav-label">Food & Drinks</span>
                    </a>
                    <span class="nav-tooltip">Pagkain</span>
                </li>
            </ul>
            <ul class="nav-list secondary-nav">
            <li class="nav-item">
                    <a href="settings.html" class="nav-link">
                        <span class="material-symbols-rounded">settings</span>
                        <span class="nav-label">Settings</span>
                    </a>
                    <span class="nav-tooltip">Settings</span>
                </li>
                <li class="nav-item">
                    <a href="admin.php" class="nav-link">
                        <span class="material-symbols-rounded">account_circle</span>
                        <span class="nav-label">Admin</span>
                    </a>
                    <span class="nav-tooltip">Account</span>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">
                        <span class="material-symbols-rounded">logout</span>
                        <span class="nav-label">Logout</span>
                    </a>
                    <span class="nav-tooltip">Logout</span>
                </li>
            </ul>
        </nav>
    </aside>

<div class="main-content">
    <h1>Ticket Purchases</h1>

    <div class="filter-buttons">
        <button class="buttton3" onclick="filterTickets('all')">Show All</button>
        <button class="buttton3" onclick="filterTickets('pending')">Show Pending</button>
        <button class="buttton3" onclick="filterTickets('booked')">Show Booked</button>
        <button class="buttton3" onclick="filterTickets('used')">Show Used</button>
        <button class="buttton3" onclick="filterTickets('cancelled')">Show Cancelled</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>User ID</th>
                <th>Movie ID</th>
                <th>Seat ID</th>
                <th>Showtime ID</th>
                <th>Purchase Date</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="ticketsTableBody">
            <!-- Ticket data will be populated by JS -->
        </tbody>
    </table>
</div>

    <script src="script.js"></script>
</body>
</html>
