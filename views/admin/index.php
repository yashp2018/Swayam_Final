<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swayam Admin - Spiritual Dashboard</title>
    <link rel="stylesheet" href="../public/css/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body">
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-header">
            <div class="admin-logo">
                <div class="logo-icon-admin">
                    <span class="logo-symbol">स्व</span>
                </div>
                <div class="logo-text-admin">
                    <span class="logo-title">Swayam</span>
                    <span class="logo-subtitle">Admin Panel</span>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <ul class="nav-list">
                <li class="nav-item active">
                    <a href="#dashboard" class="nav-link" data-section="dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#pending" class="nav-link" data-section="pending">
                        <i class="fas fa-clock"></i>
                        <span>Pending Approvals</span>
                        <span class="badge" id="pendingCount">12</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#content" class="nav-link" data-section="content">
                        <i class="fas fa-file-alt"></i>
                        <span>Content Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#users" class="nav-link" data-section="users">
                        <i class="fas fa-users"></i>
                        <span>User Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#retreats" class="nav-link" data-section="retreats">
                        <i class="fas fa-mountain"></i>
                        <span>Retreat Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#categories" class="nav-link" data-section="categories">
                        <i class="fas fa-tags"></i>
                        <span>Categories</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#translations" class="nav-link" data-section="translations">
                        <i class="fas fa-language"></i>
                        <span>Translations</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="sidebar-footer">
            <div class="admin-profile">
                <div class="profile-avatar">A</div>
                <div class="profile-info">
                    <span class="profile-name" id="adminName">Admin User</span>
                    <span class="profile-role" id="adminRole">Super Admin</span>
                </div>
            </div>
            <button class="logout-btn" onclick="logout()">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-left">
                <button class="mobile-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <h1 class="page-title" id="pageTitle">Dashboard</h1>
                    <p class="page-subtitle">Welcome to Swayam Admin Panel</p>
                </div>
            </div>
            <div class="header-right">
                <button class="btn-icon notification-btn" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="notification-dot"></span>
                </button>
                <a href="../index.html" class="btn-secondary">
                    <i class="fas fa-home"></i> View Site
                </a>
            </div>
        </header>

        <!-- Dashboard Section -->
        <div id="dashboard-section" class="admin-section active">
            <div class="dashboard-content">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card reveal bg-gradient-primary">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number" id="pendingStats">24</h3>
                            <p class="stat-label">Pending Approvals</p>
                            <span class="stat-change positive">+3 today</span>
                        </div>
                    </div>

                    <div class="stat-card reveal bg-gradient-success" style="animation-delay: 0.1s;">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number" id="userStats">1,247</h3>
                            <p class="stat-label">Total Users</p>
                            <span class="stat-change positive">+12 this week</span>
                        </div>
                    </div>

                    <div class="stat-card reveal bg-gradient-warning" style="animation-delay: 0.2s;">
                        <div class="stat-icon">
                            <i class="fas fa-mountain"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number" id="retreatStats">18</h3>
                            <p class="stat-label">Active Retreats</p>
                            <span class="stat-change neutral">2 upcoming</span>
                        </div>
                    </div>

                    <div class="stat-card reveal bg-gradient-info" style="animation-delay: 0.3s;">
                        <div class="stat-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number" id="contentStats">342</h3>
                            <p class="stat-label">Published Content</p>
                            <span class="stat-change positive">+8 this month</span>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Grid -->
                <div class="dashboard-grid">
                    <!-- Recent Activity -->
                    <div class="dashboard-card reveal" style="animation-delay: 0.4s;">
                        <div class="card-header">
                            <h2 class="card-title">Recent Activity</h2>
                            <button class="btn-text">View All</button>
                        </div>
                        <div class="activity-list" id="activityList">
                            <!-- Activity items will be loaded here -->
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="dashboard-card reveal" style="animation-delay: 0.5s;">
                        <div class="card-header">
                            <h2 class="card-title">Quick Actions</h2>
                        </div>
                        <div class="quick-actions">
                            <button class="action-btn" onclick="showSection('pending')">
                                <div class="action-icon bg-primary">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <span>Review Pending Content</span>
                            </button>
                            <button class="action-btn" onclick="showSection('users')">
                                <div class="action-icon bg-success">
                                    <i class="fas fa-users"></i>
                                </div>
                                <span>Manage Users</span>
                            </button>
                            <button class="action-btn" onclick="showSection('retreats')">
                                <div class="action-icon bg-warning">
                                    <i class="fas fa-mountain"></i>
                                </div>
                                <span>Retreat Bookings</span>
                            </button>
                            <button class="action-btn" onclick="showSection('categories')">
                                <div class="action-icon bg-info">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <span>Edit Categories</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Approvals Section -->
        <div id="pending-section" class="admin-section">
            <div class="section-header">
                <h2 class="section-title">Pending Approvals</h2>
                <div class="section-actions">
                    <button class="btn-primary" onclick="refreshPending()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
            
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="pendingTableBody">
                        <!-- Pending items will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Content Management Section -->
        <div id="content-section" class="admin-section">
            <div class="section-header">
                <h2 class="section-title">Content Management</h2>
                <div class="section-filters">
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        <option value="spirituality">Spirituality</option>
                        <option value="meditation">Meditation</option>
                        <option value="yoga">Yoga</option>
                    </select>
                    <input type="search" class="form-input" id="searchContent" placeholder="Search content...">
                </div>
            </div>
            
            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="contentTableBody">
                        <!-- Content items will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Other sections will be similar -->
        <div id="users-section" class="admin-section">
            <div class="section-header">
                <h2 class="section-title">User Management</h2>
            </div>
            <div class="placeholder-content">
                <i class="fas fa-users"></i>
                <h3>User Management</h3>
                <p>User management interface with role changes and activity monitoring.</p>
            </div>
        </div>

        <div id="retreats-section" class="admin-section">
            <div class="section-header">
                <h2 class="section-title">Retreat Management</h2>
            </div>
            <div class="placeholder-content">
                <i class="fas fa-mountain"></i>
                <h3>Retreat Management</h3>
                <p>Retreat booking management, schedule planning, and participant tracking.</p>
            </div>
        </div>

        <div id="categories-section" class="admin-section">
            <div class="section-header">
                <h2 class="section-title">Category Management</h2>
            </div>
            <div class="placeholder-content">
                <i class="fas fa-tags"></i>
                <h3>Category Management</h3>
                <p>Manage content categories for blogs, stories, and arts.</p>
            </div>
        </div>

        <div id="translations-section" class="admin-section">
            <div class="section-header">
                <h2 class="section-title">Translation Management</h2>
            </div>
            <div class="placeholder-content">
                <i class="fas fa-language"></i>
                <h3>Translation Management</h3>
                <p>Manage UI translations for English, Hindi, and Marathi languages.</p>
            </div>
        </div>
    </main>

    <!-- Content Review Modal -->
    <div id="contentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Content Review</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Content details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button class="btn-success" onclick="approveFromModal()">
                    <i class="fas fa-check"></i> Approve
                </button>
                <button class="btn-danger" onclick="rejectFromModal()">
                    <i class="fas fa-times"></i> Reject
                </button>
                <button class="btn-secondary" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script src="../public/js/admin.js"></script>
</body>
</html>