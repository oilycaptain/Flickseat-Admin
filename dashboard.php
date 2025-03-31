<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}
?>

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
    <h1>Welcome, <?php echo $_SESSION["admin"]; ?>!</h1>
    <!-- In your dashboard.php, update the cards section -->
<div class="cards">
    <div class="box"><h2 class="text-card">Total Bookings: <br><span id="totalBookings"></span></h2></div>
    <div class="box"><h2 class="text-card">Ticket Sales: <br><span id="totalSales"></span></h2></div>
    <div class="box"><h2 class="text-card">Food & Drink Sales: <br><span id="foodsales"></span></h2></div>
    <div class="box"><h2 class="text-card">Cancellations: <br><span id="cancellations"></span></h2></div>
</div>

        <div class="charts">           
  
            <div class="charts-card">
              <h2 class="chart-title">Purchase and Sales Orders</h2>
              <div id="area-chart"></div>
            </div>

            <div class="charts-card">
              <h2 class="chart-title">Top 5 Movies Booked</h2>
              <div id="bar-chart"></div>
            </div>
  
        </div>
        <h1 class="text3">Recent Reservations: </h1>
        <table id="reservationsTable">
  <thead>
    <tr>
      <th>RESERVATION ID</th>
      <th>TICKET ID</th>
      <th>USER ID</th>
      <th>MOVIE ID</th>
      <th>SHOWTIME ID</th>
      <th>SEAT ID</th>
      <th>PURCHASE DATE</th>
      <th>TICKET PRICE</th>
      <th>STATUS</th>
      <th>DELETED AT</th>
    </tr>
  </thead>
  <tbody>
    <!-- JavaScript will populate this -->
  </tbody>
</table>

        
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.5/apexcharts.min.js"></script>
    <script>
// Dashboard JavaScript - Optimized for Smooth Resizing

let barChartInstance = null;
let areaChartInstance = null;
let resizeTimeout;

const sidebar = document.querySelector(".sidebar");
const sidebarToggler = document.querySelector(".sidebar-toggler");
const menuToggler = document.querySelector(".menu-toggler");
const mainContent = document.querySelector(".main-content");

const collapsedSidebarWidth = "85px";
const expandedSidebarWidth = "280px";

// Smoothly update main content margin with transition
const updateMainContentMargin = () => {
    mainContent.style.transition = "margin-left 0.3s ease";
    if (sidebar.classList.contains("collapsed")) {
        mainContent.style.marginLeft = collapsedSidebarWidth;
    } else {
        mainContent.style.marginLeft = expandedSidebarWidth;
    }
};

// Load sidebar state
const storedCollapse = localStorage.getItem('sidebarCollapsed') === 'true';
if (storedCollapse) {
    sidebar.classList.add('collapsed');
} else {
    sidebar.classList.remove('collapsed');
}
updateMainContentMargin();

// Optimized sidebar toggle with smooth transitions
sidebarToggler.addEventListener("click", () => {
    const collapsed = sidebar.classList.toggle("collapsed");
    localStorage.setItem('sidebarCollapsed', collapsed);
    updateMainContentMargin();

    // Clear any pending resize
    clearTimeout(resizeTimeout);
    
    // Start resizing charts during the transition
    const startTime = Date.now();
    const duration = 300; // Match CSS transition duration
    
    const resizeCharts = () => {
        const elapsed = Date.now() - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        if (barChartInstance) {
            barChartInstance.updateOptions({
                chart: { width: document.querySelector("#bar-chart").offsetWidth }
            }, false, false);
        }
        if (areaChartInstance) {
            areaChartInstance.updateOptions({
                chart: { width: document.querySelector("#area-chart").offsetWidth }
            }, false, false);
        }
        
        if (progress < 1) {
            requestAnimationFrame(resizeCharts);
        }
    };
    
    // Start the smooth resize process
    requestAnimationFrame(resizeCharts);
});

// Debounced window resize handler
window.addEventListener("resize", () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
        if (window.innerWidth >= 1024) {
            sidebar.style.height = "calc(100vh - 32px)";
            updateMainContentMargin();
        } else {
            sidebar.classList.remove("collapsed");
            sidebar.style.height = "auto";
            updateMainContentMargin();
        }
        
        if (barChartInstance) barChartInstance.resize();
        if (areaChartInstance) areaChartInstance.resize();
    }, 100);
});

// Initialize dashboard
document.addEventListener('DOMContentLoaded', () => {
    updateDashboardStats();
    updateTopMoviesChart();
    updateSalesChart();
    loadRecentReservations();
});

// Dashboard stats functions (unchanged)
function updateDashboardStats() {
    fetch('fetch_stats.php')
        .then(res => res.ok ? res.json() : Promise.reject(res.status))
        .then(data => {
            document.getElementById('totalBookings').textContent = data.totalBookings;
            document.getElementById('cancellations').textContent = data.cancellations;
            document.getElementById('foodsales').textContent = data.foodsales;
            document.getElementById('totalSales').textContent = `$${data.totalSales}`;
        })
        .catch(err => console.error('Error fetching stats:', err));
}

// Enhanced chart functions with smoother animations
function updateTopMoviesChart() {
    fetch('fetch_charts.php')
        .then(res => res.json())
        .then(data => {
            const movieNames = data.topMovies.map(m => m.movie);
            const bookings = data.topMovies.map(m => m.bookings);

            const options = {
    series: [{ name: 'Bookings', data: bookings }],
    chart: {
        type: 'bar',
        height: 350,
        background: 'transparent',
        toolbar: { show: false },
        id: 'bar-chart',
        animations: {  // Reduced animations
            enabled: true,
            easing: 'linear',
            speed: 500,
            animateGradually: {
                enabled: false
            }
        }
    },
                colors: ['#4CAF50', '#FF9800', '#F44336', '#3F51B5', '#9C27B0'],
                grid: {
                    borderColor: '#2E7D32',
                    strokeDashArray: 5,
                    padding: { left: 10, right: 10, top: 0, bottom: 0 }
                },
                stroke: { curve: 'smooth', width: 3 },
                plotOptions: { bar: { borderRadius: 5, columnWidth: '50%' } },
                dataLabels: {
                    enabled: true,
                    style: { fontSize: '14px', fontWeight: 'bold', colors: ['#fff'] }
                },
                xaxis: {
                    categories: movieNames,
                    labels: { style: { fontSize: '14px', fontWeight: 'bold', colors: '#ffffff' } }
                },
                yaxis: {
                    title: {
                        text: 'Bookings',
                        style: { fontSize: '16px', fontWeight: 'bold', color: '#ffffff' }
                    },
                    labels: { style: { fontSize: '14px', fontWeight: 'bold', colors: '#ffffff' } }
                },
                tooltip: { theme: 'dark' }
            };

            if (barChartInstance) barChartInstance.destroy();
            barChartInstance = new ApexCharts(document.querySelector("#bar-chart"), options);
            barChartInstance.render();
        })
        .catch(err => console.error('Error fetching top movies data:', err));
}

function updateSalesChart() {
    fetch('fetch_charts.php')
        .then(res => res.json())
        .then(data => {
            const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            
            // Process ticket sales data
            const ticketSales = Array(12).fill(0);
            data.salesData.forEach(item => {
                ticketSales[item.month - 1] = item.tickets_sold;
            });
            
            // Process food sales data
            const foodSales = Array(12).fill(0);
            data.foodSalesData.forEach(item => {
                foodSales[item.month - 1] = item.foods_sold;
            });

            const options = {
                series: [
                    {
                        name: 'Tickets Sold',
                        data: ticketSales
                    },
                    {
                        name: 'Food Items Sold',
                        data: foodSales
                    }
                ],
                chart: {
        type: 'area',
        height: 350,
        background: 'transparent',
        toolbar: { show: false },
        id: 'area-chart',
        animations: {  // Reduced animations
            enabled: true,
            easing: 'linear', // Changed from 'easeinout'
            speed: 500,      // Increased from 300 (slower means less CPU usage)
            animateGradually: {
                enabled: false // Disable gradual animation
            }
        },
                    events: {
                        mounted: (chartContext) => {
                            chartContext.updateOptions({
                                chart: {
                                    width: document.querySelector("#area-chart").offsetWidth
                                }
                            }, false, false);
                        }
                    }
                },
                colors: ['#2962FF', '#FF5722'], // Different colors for each series
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.5,
                        opacityTo: 0.1,
                        stops: [0, 100]
                    }
                },
                markers: { size: 5, strokeWidth: 2, strokeColor: '#ffffff' },
                grid: {
                    borderColor: '#2E7D32',
                    strokeDashArray: 5,
                    padding: { left: 10, right: 10, top: 0, bottom: 0 }
                },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: {
                    categories: months,
                    labels: { style: { fontSize: '14px', fontWeight: 'bold', colors: '#ffffff' } }
                },
                yaxis: {
                    title: {
                        text: 'Items Sold',
                        style: { fontSize: '16px', fontWeight: 'bold', color: '#ffffff' }
                    },
                    labels: { style: { fontSize: '14px', fontWeight: 'bold', colors: '#ffffff' } }
                },
                tooltip: { 
                    theme: 'dark',
                    y: {
                        formatter: function(value) {
                            return value + " items";
                        }
                    }
                },
                legend: {
                    position: 'top',
                    labels: {
                        colors: '#ffffff',
                        useSeriesColors: false
                    }
                }
            };

            if (areaChartInstance) areaChartInstance.destroy();
            areaChartInstance = new ApexCharts(document.querySelector("#area-chart"), options);
            areaChartInstance.render();
        })
        .catch(err => console.error('Error fetching sales data:', err));
}

fetch('fetch_recent.php')
    .then(response => response.json())
    .then(data => {
      const tableBody = document.querySelector("#reservationsTable tbody");
      tableBody.innerHTML = ""; // Clear existing rows if any

      data.forEach(row => {
        const tr = document.createElement("tr");

        tr.innerHTML = `
          <td>${row.reservation_id}</td>
          <td>${row.ticket_id}</td>
          <td>${row.user_id}</td>
          <td>${row.movie_id}</td>
          <td>${row.showtime_id}</td>
          <td>${row.seat_id}</td>
          <td>${row.purchase_date}</td>
          <td>${row.ticket_price}</td>
          <td>${row.status}</td>
          <td>${row.deleted_at}</td>
        `;

        tableBody.appendChild(tr);
      });
    })
    .catch(error => console.error("Error fetching recent reservations:", error));
</script>
</body>
</html>