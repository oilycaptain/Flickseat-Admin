<?php
$conn = new mysqli("localhost", "root", "", "flickseat_db");

// Number of entries to show
$entries_per_page = 25;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $entries_per_page;

// Search functionality
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$search_condition = $search ? "WHERE username LIKE '%$search%' OR email LIKE '%$search%'" : '';

// Fetch users with pagination and search
$all_users = $conn->query("SELECT * FROM users $search_condition LIMIT $entries_per_page OFFSET $offset");
$total_users = $conn->query("SELECT COUNT(*) as total FROM users $search_condition")->fetch_assoc()['total'];
$total_pages = ceil($total_users / $entries_per_page);

// Check for errors
if (!$all_users) {
    die("Query failed: " . $conn->error);
}

if (isset($_SESSION['delete_message'])) {
    $delete_message = $_SESSION['delete_message'];
    unset($_SESSION['delete_message']);
    // You can then use $delete_message in your page
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Users - Flickseat</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1c1c1e;
            color: #fff;
            margin: 0;
            padding: 20px;
        }

        h2 {
            color: #a855f7;
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 600;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #2c2c2e;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            margin-bottom: 30px;
            overflow: hidden;
        }

        th, td {
            padding: 14px;
            text-align: left;
            color: #fff;
            border-bottom: 1px solid #444;
        }

        th {
            background-color: #1c1c1e;
            color: #a855f7;
            font-size: 16px;
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #252526;
        }

        tr:hover {
            background-color: #333;
            transition: background 0.3s ease;
        }

        /* Delete Button */
        .btn-delete {
            padding: 6px 12px;
            background-color: #e53935;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s ease;
            text-decoration: none;
        }

        .btn-delete:hover {
            background-color: #c62828;
        }

        .snackbar {
            visibility: hidden;
            min-width: 250px;
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 16px;
            border-radius: 8px;
            position: fixed;
            left: 50%;
            bottom: 30px;
            transform: translateX(-50%);
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            opacity: 0;
            transition: opacity 0.5s ease, bottom 0.5s ease;
        }

        .snackbar.success {
            background-color: #4CAF50;
        }

        .snackbar.error {
            background-color: #e53935;
        }

        .snackbar.show {
            visibility: visible;
            opacity: 1;
            bottom: 50px;
        }

        /* Table Controls */
        .table-controls {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            align-items: center;
        }

        .table-controls select, 
        .table-controls input {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #444;
            background-color: #2c2c2e;
            color: #fff;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        .pagination a {
            color: #a855f7;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #444;
        }

        .pagination a:hover {
            background-color: #333;
        }

        .pagination a.active {
            background-color: #a855f7;
            color: white;
        }

        /* Responsive Table */
        @media (max-width: 768px) {
            th, td {
                font-size: 12px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<div id="snackbar" class="snackbar <?= isset($_GET['status']) ? ($_GET['status'] === 'success' ? 'success' : 'error') : '' ?>">
    <?= isset($delete_message) ? $delete_message : (isset($_GET['status']) ? ($_GET['status'] === 'success' ? 'User deleted successfully' : 'Operation failed') : '') ?>
</div>


<h2>All Users</h2>

<div class="table-controls">
    <div>
        <span>Show</span>
        <select id="entries-select" onchange="updateEntriesPerPage()">
            <option value="10" <?= $entries_per_page == 10 ? 'selected' : '' ?>>10</option>
            <option value="25" <?= $entries_per_page == 25 ? 'selected' : '' ?>>25</option>
            <option value="50" <?= $entries_per_page == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= $entries_per_page == 100 ? 'selected' : '' ?>>100</option>
        </select>
        <span>entries</span>
    </div>
    <div>
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
            <input type="hidden" name="page" value="1">
        </form>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>User ID</th>
            <th>Email</th>
            <th>Username</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($all_users->num_rows > 0): ?>
            <?php while ($row = $all_users->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['user_id']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td>
                        <a href="delete_user.php?id=<?= $row['user_id'] ?>" 
                           class="btn-delete"
                           onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        <?php else: ?>
            <tr>
                <td colspan="4" style="text-align: center;">No users found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Pagination -->
<div class="pagination">
    <?php if ($current_page > 1): ?>
        <a href="?page=<?= $current_page - 1 ?>&search=<?= urlencode($search) ?>">&laquo; Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" <?= $i == $current_page ? 'class="active"' : '' ?>>
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($current_page < $total_pages): ?>
        <a href="?page=<?= $current_page + 1 ?>&search=<?= urlencode($search) ?>">Next &raquo;</a>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const snackbar = document.getElementById('snackbar');
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.has('status') || snackbar.textContent.trim() !== '') {
            snackbar.classList.add('show');
            setTimeout(() => {
                snackbar.classList.remove('show');
            }, 3000);
        }
    });

    // Update entries per page
    function updateEntriesPerPage() {
        const select = document.getElementById('entries-select');
        const entries = select.value;
        const url = new URL(window.location.href);
        url.searchParams.set('entries', entries);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }

    // Improved search functionality
    const searchInput = document.getElementById('search-input');
    const searchForm = document.getElementById('search-form');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchForm.submit();
        }, 500); // Submit after 500ms of inactivity
    });
    
    // Also submit when pressing Enter
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchForm.submit();
        }
    });


</script>

<?php $conn->close(); ?>

</body>
</html>