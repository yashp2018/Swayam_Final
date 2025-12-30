<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Swayam Admin</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav id="navigation" class="nav-solid">
        <div class="container">
            <div class="nav-content">
                <a href="../index.html" class="logo-link">
                    <div class="logo-icon"><span class="logo-symbol">स्व</span></div>
                    <div class="logo-text">
                        <span class="logo-title">Swayam</span>
                        <span class="logo-subtitle">Admin Panel</span>
                    </div>
                </a>
                <div class="nav-links">
                    <a href="dashboard.html" class="nav-link">Dashboard</a>
                    <a href="blog-management.html" class="nav-link">Blogs</a>
                    <a href="users.html" class="nav-link active">Users</a>
                    <div class="lang-dropdown">
                        <button class="lang-btn" id="langBtn">EN ▼</button>
                        <ul class="lang-options" id="langOptions">
                            <li data-lang="EN" class="active">English</li>
                            <li data-lang="HI">हिंदी</li>
                            <li data-lang="MR">मराठी</li>
                        </ul>
                    </div>
                    <span class="user-name" id="adminName">Admin</span>
                    <button onclick="logout()" class="btn-secondary">Logout</button>
                </div>
            </div>
        </div>
    </nav>

    <main class="dashboard-main">
        <div class="container">
            <div class="dashboard-header">
                <h1>User Management</h1>
                <button onclick="loadUsers()" class="btn-secondary">Refresh</button>
            </div>

            <div class="glass-card">
                <div class="users-table">
                    <table id="usersTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <tr><td colspan="6">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script src="../public/js/auth.js"></script>
    <script>
        if (!isLoggedIn() || getCurrentUser()?.role !== 'admin') {
            window.location.href = '../login.html';
        }

        document.addEventListener('DOMContentLoaded', loadUsers);

        async function loadUsers() {
            try {
                const response = await fetch('../api/admin/users.php');
                const result = await response.json();
                
                if (result.success) {
                    displayUsers(result.users);
                }
            } catch (error) {
                console.error('Failed to load users:', error);
            }
        }

        function displayUsers(users) {
            const tbody = document.getElementById('usersTableBody');
            
            if (users.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6">No users found</td></tr>';
                return;
            }
            
            tbody.innerHTML = users.map(user => `
                <tr>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td><span class="role-badge ${user.role}">${user.role}</span></td>
                    <td><span class="status-badge ${user.status}">${user.status}</span></td>
                    <td>${new Date(user.created_at).toLocaleDateString()}</td>
                    <td>
                        <button onclick="toggleUserStatus(${user.id}, '${user.status}')" class="btn-sm">
                            ${user.status === 'active' ? 'Suspend' : 'Activate'}
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        async function toggleUserStatus(userId, currentStatus) {
            const newStatus = currentStatus === 'active' ? 'suspended' : 'active';
            
            try {
                const response = await fetch('../api/admin/toggle-user-status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ userId, status: newStatus })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert(`User ${newStatus} successfully!`);
                    loadUsers();
                } else {
                    alert(result.message || 'Failed to update user status');
                }
            } catch (error) {
                alert('Network error. Please try again.');
            }
        }
    </script>

    <style>
        .users-table { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--color-beige); }
        th { background: var(--color-cream); font-weight: 600; }
        .role-badge, .status-badge { padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; }
        .role-badge.admin { background: #ef4444; color: white; }
        .role-badge.user { background: #10b981; color: white; }
        .status-badge.active { background: #10b981; color: white; }
        .status-badge.suspended { background: #f59e0b; color: white; }
        .btn-sm { padding: 0.5rem 1rem; font-size: 0.75rem; }
    </style>
</body>
</html>