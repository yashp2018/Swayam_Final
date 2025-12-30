<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blogs - Swayam</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav id="navigation" class="nav-solid">
        <div class="container">
            <div class="nav-content">
                <a href="index.html" class="logo-link">
                    <div class="logo-icon"><span class="logo-symbol">स्व</span></div>
                    <div class="logo-text">
                        <span class="logo-title">Swayam</span>
                        <span class="logo-subtitle">Journey Within</span>
                    </div>
                </a>
                <div class="nav-links">
                    <a href="dashboard.html" class="nav-link">Dashboard</a>
                    <a href="user-blog.html" class="nav-link">Create Blog</a>
                    <a href="my-blogs.html" class="nav-link active">My Blogs</a>
                    <div class="lang-dropdown">
                        <button class="lang-btn" id="langBtn">EN ▼</button>
                        <ul class="lang-options" id="langOptions">
                            <li data-lang="EN" class="active">English</li>
                            <li data-lang="HI">हिंदी</li>
                            <li data-lang="MR">मराठी</li>
                        </ul>
                    </div>
                    <span class="user-name" id="userName">User</span>
                    <button onclick="logout()" class="btn-secondary">Logout</button>
                </div>
            </div>
        </div>
    </nav>

    <main class="dashboard-main">
        <div class="container">
            <div class="dashboard-header">
                <h1>My Blogs</h1>
                <div class="btn-group">
                    <a href="user-blog.html" class="btn-primary">Create New Blog</a>
                    <button onclick="loadMyBlogs()" class="btn-secondary">Refresh</button>
                </div>
            </div>

            <div class="filter-tabs">
                <button class="filter-tab active" data-status="all">All</button>
                <button class="filter-tab" data-status="draft">Drafts</button>
                <button class="filter-tab" data-status="pending">Pending</button>
                <button class="filter-tab" data-status="published">Published</button>
                <button class="filter-tab" data-status="rejected">Rejected</button>
            </div>

            <div id="myBlogsList" class="blog-grid"></div>
        </div>
    </main>

    <script src="public/js/auth.js"></script>
    <script>
        if (!isLoggedIn()) {
            window.location.href = 'login.html';
        }

        const user = getCurrentUser();
        if (user) {
            document.getElementById('userName').textContent = user.name;
        }

        let allBlogs = [];
        let currentFilter = 'all';

        document.addEventListener('DOMContentLoaded', function() {
            loadMyBlogs();
            setupFilterTabs();
        });

        function setupFilterTabs() {
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.dataset.status;
                    filterBlogs();
                });
            });
        }

        async function loadMyBlogs() {
            try {
                const response = await fetch('api/my-blogs.php');
                const result = await response.json();
                
                if (result.success) {
                    allBlogs = result.blogs;
                    filterBlogs();
                }
            } catch (error) {
                console.error('Failed to load blogs:', error);
            }
        }

        function filterBlogs() {
            const filteredBlogs = currentFilter === 'all' ? 
                allBlogs : 
                allBlogs.filter(blog => blog.status === currentFilter);
            
            displayBlogs(filteredBlogs);
        }

        function displayBlogs(blogs) {
            const blogsList = document.getElementById('myBlogsList');
            
            if (blogs.length === 0) {
                blogsList.innerHTML = '<div class="no-blogs"><p>No blogs found.</p><a href="user-blog.html" class="btn-primary">Create Your First Blog</a></div>';
                return;
            }
            
            blogsList.innerHTML = blogs.map(blog => `
                <div class="blog-card">
                    ${blog.featured_image ? `<img src="${blog.featured_image}" alt="${blog.title}">` : ''}
                    <div class="blog-card-content">
                        <h3>${blog.title}</h3>
                        <p class="blog-meta">
                            <span class="status ${blog.status}">${blog.status}</span>
                            <span>${blog.category}</span>
                            <span>${new Date(blog.created_at).toLocaleDateString()}</span>
                        </p>
                        <p class="blog-excerpt">${blog.excerpt || 'No excerpt available'}</p>
                        <div class="blog-actions">
                            <button onclick="editBlog(${blog.id})" class="btn-secondary">Edit</button>
                            <button onclick="deleteBlog(${blog.id})" class="btn-danger">Delete</button>
                            ${blog.status === 'published' ? `<button onclick="viewBlog(${blog.id})" class="btn-primary">View</button>` : ''}
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function editBlog(id) {
            window.location.href = `user-blog.html?edit=${id}`;
        }

        async function deleteBlog(id) {
            if (!confirm('Are you sure you want to delete this blog?')) return;
            
            try {
                const response = await fetch('api/delete-my-blog.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Blog deleted successfully!');
                    loadMyBlogs();
                } else {
                    alert(result.message || 'Failed to delete blog');
                }
            } catch (error) {
                alert('Network error. Please try again.');
            }
        }

        function viewBlog(id) {
            window.open(`blog.html?id=${id}`, '_blank');
        }
    </script>

    <style>
        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            margin: 2rem 0;
            justify-content: center;
        }
        .filter-tab {
            padding: 0.75rem 1.5rem;
            border: 2px solid var(--color-beige);
            background: white;
            border-radius: 50px;
            cursor: pointer;
            transition: all var(--transition-base);
        }
        .filter-tab.active {
            background: var(--color-lavender);
            color: white;
            border-color: var(--color-lavender);
        }
        .no-blogs {
            text-align: center;
            padding: 3rem;
        }
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }
        .blog-card {
            background: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: all var(--transition-base);
        }
        .blog-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        .blog-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .blog-card-content {
            padding: 1.5rem;
        }
        .blog-meta {
            display: flex;
            gap: 1rem;
            margin: 0.5rem 0;
            font-size: 0.875rem;
        }
        .status {
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
        }
        .status.published { background: #10b981; color: white; }
        .status.pending { background: #f59e0b; color: white; }
        .status.draft { background: #6b7280; color: white; }
        .status.rejected { background: #ef4444; color: white; }
        .blog-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .blog-actions button {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        .btn-danger {
            background: #ef4444;
            color: white;
            border: none;
            border-radius: var(--radius-sm);
        }
    </style>
</body>
</html>