<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Swayam</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav id="navigation" class="nav-solid">
        <div class="container">
            <div class="nav-content">
                <a href="index.html" class="logo-link">
                    <div class="logo-icon">
                        <span class="logo-symbol">स्व</span>
                    </div>
                    <div class="logo-text">
                        <span class="logo-title">Swayam</span>
                        <span class="logo-subtitle">Admin Panel</span>
                    </div>
                </a>

                <div class="nav-links">
                    <a href="dashboard.html" class="nav-link active">Dashboard</a>
                    <a href="blog-management.html" class="nav-link">Blogs</a>
                    <a href="users.html" class="nav-link">Users</a>
                    <a href="categories.html" class="nav-link">Categories</a>
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

    <!-- Admin Dashboard Content -->
    <main class="dashboard-main">
        <div class="container">
            <div class="dashboard-header">
                <h1>Admin Dashboard</h1>
                <p>Manage content, users, and system settings</p>
            </div>

            <!-- Stats Cards -->
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <h3>Total Users</h3>
                    <div class="stat-number" id="totalUsers">0</div>
                    <p>Registered members</p>
                </div>

                <div class="dashboard-card">
                    <h3>Pending Content</h3>
                    <div class="stat-number" id="pendingContent">0</div>
                    <p>Awaiting approval</p>
                </div>

                <div class="dashboard-card">
                    <h3>Approved Content</h3>
                    <div class="stat-number" id="approvedContent">0</div>
                    <p>Published content</p>
                </div>

                <div class="dashboard-card">
                    <h3>Categories</h3>
                    <div class="stat-number" id="totalCategories">0</div>
                    <p>Content categories</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="section">
                <h2 class="heading-lg text-center">Quick Actions</h2>
                <div class="dashboard-grid">
                    <div class="dashboard-card">
                        <h3>Review Content</h3>
                        <p>Approve or reject submitted content</p>
                        <a href="content-review.html" class="btn-primary">Review Now</a>
                    </div>

                    <div class="dashboard-card">
                        <h3>Manage Users</h3>
                        <p>View and manage user accounts</p>
                        <a href="users.html" class="btn-primary">Manage Users</a>
                    </div>

                    <div class="dashboard-card">
                        <h3>Add Category</h3>
                        <p>Create new content categories</p>
                        <a href="add-category.html" class="btn-primary">Add Category</a>
                    </div>

                    <div class="dashboard-card">
                        <h3>System Settings</h3>
                        <p>Configure application settings</p>
                        <a href="settings.html" class="btn-primary">Settings</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="../public/js/auth.js"></script>
    <script>
        // Check if admin is logged in
        if (!isLoggedIn()) {
            window.location.href = '../login.html';
        }

        const user = getCurrentUser();
        if (user && user.role !== 'admin') {
            window.location.href = '../dashboard.html';
        }

        if (user) {
            document.getElementById('adminName').textContent = `Admin: ${user.name}`;
        }

        // Load dashboard stats
        loadDashboardStats();

        async function loadDashboardStats() {
            try {
                const response = await fetch('../api/admin/stats.php');
                const stats = await response.json();
                
                if (stats.success) {
                    document.getElementById('totalUsers').textContent = stats.data.users || 0;
                    document.getElementById('pendingContent').textContent = stats.data.pending || 0;
                    document.getElementById('approvedContent').textContent = stats.data.approved || 0;
                    document.getElementById('totalCategories').textContent = stats.data.categories || 0;
                }
            } catch (error) {
                console.error('Failed to load stats:', error);
            }
        }
    </script>

    <style>
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--color-rose);
            margin: 1rem 0;
        }
    </style>
</body>
</html>