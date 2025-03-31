const sidebar = document.querySelector(".sidebar");
const sidebarToggler = document.querySelector(".sidebar-toggler");
const menuToggler = document.querySelector(".menu-toggler");
const mainContent = document.querySelector(".main-content");

const collapsedSidebarWidth = "85px";
const expandedSidebarWidth = "280px";

// ✅ Load sidebar state IMMEDIATELY before any DOM updates
const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
if (isCollapsed) {
    sidebar.classList.add('collapsed');
} else {
    sidebar.classList.remove('collapsed');
}

// Function to update main content position
const updateMainContentMargin = () => {
    if (sidebar.classList.contains("collapsed")) {
        mainContent.style.marginLeft = collapsedSidebarWidth;
    } else {
        mainContent.style.marginLeft = expandedSidebarWidth;
    }
};

// ✅ Ensure margin is updated immediately after setting sidebar state
updateMainContentMargin();

// Toggle sidebar and adjust main content
sidebarToggler.addEventListener("click", () => {
    const isCollapsed = sidebar.classList.toggle("collapsed");
    // Store sidebar state in localStorage
    localStorage.setItem('sidebarCollapsed', isCollapsed);
    updateMainContentMargin();
});

// Ensure main content is adjusted on window resize
window.addEventListener("resize", () => {
    if (window.innerWidth >= 1024) {
        sidebar.style.height = "calc(100vh - 32px)";
        updateMainContentMargin();
    } else {
        sidebar.classList.remove("collapsed");
        sidebar.style.height = "auto";
        toggleMenu(sidebar.classList.contains("menu-active"));
        updateMainContentMargin();
    }
});

// ✅ Keep sidebar state consistent across page reloads and navigation
window.addEventListener('load', () => {
    updateMainContentMargin(); // Ensure the content is positioned correctly after load
});



let allTickets = []; // Store all fetched tickets

document.addEventListener('DOMContentLoaded', () => {
    fetch('fetch_tickets.php')
        .then(response => response.json())
        .then(data => {
            allTickets = data;
            renderTickets(allTickets); // Initial load
        });
});

function renderTickets(tickets) {
    const tableBody = document.getElementById('ticketsTableBody');
    tableBody.innerHTML = '';

    tickets.forEach(ticket => {
        const row = document.createElement('tr');
        row.setAttribute('data-status', ticket.status.toLowerCase());

        row.innerHTML = `
            <td>${ticket.ticket_id}</td>
            <td>${ticket.user_id}</td>
            <td>${ticket.movie_id}</td>
            <td>${ticket.seat_id}</td>
            <td>${ticket.showtime_id}</td>
            <td>${ticket.purchase_date}</td>
            <td>${ticket.ticket_price}</td>
            <td>${ticket.status}</td>
            <td>${getTicketActionButtons(ticket)}</td>
        `;
        tableBody.appendChild(row);
    });
}

function filterTickets(status) {
    if (status === 'all') {
        renderTickets(allTickets);
    } else {
        const filtered = allTickets.filter(ticket =>
            ticket.status.toLowerCase() === status.toLowerCase()
        );
        renderTickets(filtered);
    }
}

function getTicketActionButtons(ticket) {
    let buttons = '';
    
    if (ticket.status === 'pending') {
        buttons += `
            <button onclick="updateTicket(${ticket.ticket_id}, 'booked')">Approve</button>
            <button onclick="updateTicket(${ticket.ticket_id}, 'cancelled')">Cancel</button>
        `;
    } else if (ticket.status === 'booked') {
        buttons += `<button onclick="updateTicket(${ticket.ticket_id}, 'used')">Mark as Used</button>`;
    }

    // Show delete button only for statuses other than 'pending'
    if (['cancelled', 'booked', 'used'].includes(ticket.status)) {
        buttons += `<button class="delete-btn" onclick="deleteTicket(${ticket.ticket_id})">Delete</button>`;
    }

    return buttons;
}


function deleteTicket(id) {
    if (confirm(`Are you sure you want to delete ticket #${id}? It will be moved to recent reservations.`)) {
        fetch('delete_ticket.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `ticket_id=${id}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Refresh both tables
                loadRecentReservations();
                fetch('fetch_tickets.php')
                    .then(response => response.json())
                    .then(data => {
                        allTickets = data;
                        renderTickets(allTickets);
                    });
            } else {
                alert('Failed to delete: ' + data.error);
            }
        })
        .catch(err => console.error('Error deleting ticket:', err));
    }
}

function updateTicket(id, status) {
    if (confirm(`Mark ticket #${id} as ${status}?`)) {
        fetch('update_ticket.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `ticket_id=${id}&status=${status}`
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            location.reload();
        });
    }
}


$(document).ready(function() {
    function loadNotifications(filter = 'all', search = '') {
        $.ajax({
            url: 'fetch_notifications.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log("Notifications received:", data);
                $('#notification-list').html('');
        
                if (data.length === 0) {
                    $('#no-notifications').show();
                } else {
                    $('#no-notifications').hide();
                }
        
                data.forEach(function(notification) {
                    let itemClass = notification.read_status == 0 ? 'unread' : 'read';
                    $('#notification-list').append(
                        `<li class='notification-item ${itemClass}' data-id='${notification.id}'>
                            ${notification.message}
                            <button class='delete-btn' data-id='${notification.id}'>Delete</button>
                        </li>`
                    );
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching notifications:", status, error);
            }
        });
        
    }

    function startWebSocket() {
        let ws = new WebSocket("ws://localhost:8080");
        ws.onmessage = function(event) {
            loadNotifications();
        };
    }

    loadNotifications();
    startWebSocket();

    $(document).on('click', '.notification-item', function() {
        let notificationId = $(this).data('id');
        $(this).removeClass('unread').addClass('read');
        
        $.post('mark_as_read.php', { id: notificationId }, function() {
            loadNotifications();
        });
        
        $.get('get_notification.php', { id: notificationId }, function(data) {
            let notification = JSON.parse(data);
            $('#modal-title').text('Notification Details');
            $('#modal-description').text(notification.description);
            $('#notification-modal').fadeIn();
        });
    });

    $(document).on('click', '.close-btn', function() {
        $('#notification-modal').fadeOut();
    });

    $(document).on('click', '.delete-btn', function(event) {
        event.stopPropagation();
        let notificationId = $(this).data('id');
        $.post('delete_notification.php', { id: notificationId }, function() {
            loadNotifications();
        });
    });

    $('#markAllRead').click(function() {
        $.post('mark_all_read.php', function() {
            loadNotifications();
        });
    });

    $('#deleteAll').click(function() {
        $.post('delete_all_notifications.php', function() {
            loadNotifications();
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const toast = document.getElementById('toast');
    const passwordInput = document.getElementById('password');
    const passwordFeedback = document.getElementById('passwordFeedback');
    const usernameInput = document.getElementById('username');
    const usernameFeedback = document.getElementById('usernameFeedback');
    const togglePassword = document.getElementById('togglePassword');

    // ✅ Show toast message if available
    if (toast.textContent.trim() !== '') {
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

    // ✅ Real-time username availability check
    usernameInput.addEventListener('input', () => {
        const username = usernameInput.value.trim();
    
        if (username !== '') { // ✅ Allow any length for checking
            $.ajax({
                url: './check_username.php', 
                type: 'POST',
                data: { username: username },
                success: function(response) {
                    if (response.trim() === 'taken') {
                        usernameFeedback.textContent = '❌ Username already taken';
                        usernameFeedback.style.color = '#ff4b4b';
                    } else if (response.trim() === 'available') {
                        usernameFeedback.textContent = '✅ Username is available';
                        usernameFeedback.style.color = '#4caf50';
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        } else {
            usernameFeedback.textContent = '';
        }
    });

    // ✅ Password Strength Check
    passwordInput.addEventListener('input', () => {
        const password = passwordInput.value;
        if (password.length < 8 || password.length > 15) {
            passwordFeedback.textContent = '❌ Password must be 8-15 characters';
            passwordFeedback.style.color = '#ff4b4b';
        } else if (!/[0-9]/.test(password)) {
            passwordFeedback.textContent = '❌ Must include a number';
            passwordFeedback.style.color = '#ff4b4b';
        } else if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
            passwordFeedback.textContent = '❌ Must include a special character';
            passwordFeedback.style.color = '#ff4b4b';
        } else {
            passwordFeedback.textContent = '✅ Strong password';
            passwordFeedback.style.color = '#4caf50';
        }
    });

    // ✅ Toggle Password Visibili

togglePassword.addEventListener('click', () => {
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        togglePassword.innerHTML = '🙈'; // Eye-closed emoji
    } else {
        passwordInput.type = 'password';
        togglePassword.innerHTML = '👁️'; // Eye-open emoji
    }
});

});

// Show toast message if available
document.addEventListener('DOMContentLoaded', () => {
    const toast = document.getElementById('toast');
    if (toast.textContent.trim() !== '') {
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000); // Disappear after 3 seconds
    }
});


function updateDashboardStats() {
    fetch('fetch_stats.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Fetched data:', data); // Debugging
            document.getElementById('totalBookings').textContent = data.totalBookings;
            document.getElementById('availableSeats').textContent = data.availableSeats;
            document.getElementById('cancellations').textContent = data.cancellations;
            document.getElementById('totalSales').textContent = `$${data.totalSales}`;
        })
        .catch(error => {
            console.error('Error fetching stats:', error);
        });
}

document.addEventListener('DOMContentLoaded', updateDashboardStats);


// Call this function on page load
document.addEventListener('DOMContentLoaded', updateDashboardStats);

function updateReservation(id, status) {
    fetch('update_reservation.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&status=${status}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Reservation status updated successfully');
            updateDashboardStats(); // Refresh stats after update
            location.reload(); // Reload table
        } else {
            alert('Failed to update: ' + data.error);
        }
    })
    .catch(error => console.error('Error updating reservation:', error));
}

