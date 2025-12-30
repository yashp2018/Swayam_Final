<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Management - Swayam Admin</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/pcmab3wyhu1kq9en5scf3e6jsyo3deyffci3odyfyhk3flps/tinymce/6/tinymce.min.js"></script>
</head>
<body>
    <nav id="navigation" class="nav-solid">
        <div class="container">
            <div class="nav-content">
                <a href="../index.html" class="logo-link">
                    <div class="logo-icon">
                        <span class="logo-symbol">स्व</span>
                    </div>
                    <div class="logo-text">
                        <span class="logo-title">Swayam</span>
                        <span class="logo-subtitle">Admin Panel</span>
                    </div>
                </a>
                <div class="nav-links">
                    <a href="dashboard.html" class="nav-link">Dashboard</a>
                    <a href="blog-management.html" class="nav-link active">Blogs</a>
                    <a href="users.html" class="nav-link">Users</a>
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
                <h1>Blog Management</h1>
                <div class="btn-group">
                    <button onclick="showCreateForm()" class="btn-primary">Create New Blog</button>
                    <button onclick="loadBlogs()" class="btn-secondary">Refresh</button>
                </div>
            </div>

            <!-- Create/Edit Blog Form -->
            <div id="blogForm" class="glass-card" style="display: none;">
                <h2 id="formTitle">Create New Blog</h2>
                <form id="blogCreateForm" class="form-grid">
                    <input type="hidden" id="blogId" name="blogId">
                    
                    <div class="form-group">
                        <label for="title">Title *</label>
                        <input type="text" id="title" name="title" required>
                    </div>

                    <div class="form-group">
                        <label for="category">Category *</label>
                        <select id="category" name="category" required>
                            <option value="">Select Category</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="language">Language *</label>
                        <select id="language" name="language" required>
                            <option value="en">English</option>
                            <option value="hi">हिंदी</option>
                            <option value="mr">मराठी</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="excerpt">Excerpt</label>
                        <textarea id="excerpt" name="excerpt" rows="3" placeholder="Brief description..."></textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="featuredImage">Featured Image URL</label>
                        <input type="url" id="featuredImage" name="featuredImage" placeholder="https://...">
                    </div>

                    <!-- Media Upload Section -->
                    <div class="form-group full-width">
                        <label>Media Content</label>
                        <div class="media-upload-section">
                            <div class="media-tabs">
                                <button type="button" class="media-tab active" data-tab="text">Text</button>
                                <button type="button" class="media-tab" data-tab="image">Images</button>
                                <button type="button" class="media-tab" data-tab="video">Videos</button>
                                <button type="button" class="media-tab" data-tab="audio">Audio</button>
                            </div>
                            
                            <div id="textTab" class="media-content active">
                                <textarea id="content" name="content"></textarea>
                            </div>
                            
                            <div id="imageTab" class="media-content">
                                <div class="image-upload">
                                    <input type="url" placeholder="Image URL" class="media-url">
                                    <button type="button" onclick="addMedia('image')">Add Image</button>
                                </div>
                                <div id="imageList" class="media-list"></div>
                            </div>
                            
                            <div id="videoTab" class="media-content">
                                <div class="video-upload">
                                    <input type="url" placeholder="YouTube/Vimeo URL" class="media-url">
                                    <button type="button" onclick="addMedia('video')">Add Video</button>
                                </div>
                                <div id="videoList" class="media-list"></div>
                            </div>
                            
                            <div id="audioTab" class="media-content">
                                <div class="audio-upload">
                                    <input type="url" placeholder="Audio URL" class="media-url">
                                    <button type="button" onclick="addMedia('audio')">Add Audio</button>
                                </div>
                                <div id="audioList" class="media-list"></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <button type="submit" class="btn-primary">Save Blog</button>
                        <button type="button" onclick="hideCreateForm()" class="btn-secondary">Cancel</button>
                    </div>
                </form>
            </div>

            <!-- Blog List -->
            <div class="section">
                <h2>All Blogs</h2>
                <div id="blogsList" class="blog-grid"></div>
            </div>
        </div>
    </main>

    <script src="../public/js/auth.js"></script>
    <script>
        let mediaData = { images: [], videos: [], audios: [] };

        // Initialize TinyMCE
        tinymce.init({
            selector: '#content',
            height: 400,
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount emoticons',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | emoticons | removeformat | help',
            menubar: 'file edit view insert format tools table help',
            branding: false,
            promotion: false
        });

        // Check admin access
        if (!isLoggedIn() || getCurrentUser()?.role !== 'admin') {
            window.location.href = '../login.html';
        }

        // Load categories and blogs on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCategories();
            loadBlogs();
            setupMediaTabs();
        });

        // Media tabs functionality
        function setupMediaTabs() {
            document.querySelectorAll('.media-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabName = this.dataset.tab;
                    
                    // Update active tab
                    document.querySelectorAll('.media-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Show corresponding content
                    document.querySelectorAll('.media-content').forEach(content => {
                        content.classList.remove('active');
                    });
                    document.getElementById(tabName + 'Tab').classList.add('active');
                });
            });
        }

        // Add media function
        function addMedia(type) {
            const input = document.querySelector(`#${type}Tab .media-url`);
            const url = input.value.trim();
            
            if (!url) return;
            
            mediaData[type + 's'].push(url);
            input.value = '';
            updateMediaList(type);
        }

        // Update media list display
        function updateMediaList(type) {
            const list = document.getElementById(type + 'List');
            const items = mediaData[type + 's'];
            
            list.innerHTML = items.map((url, index) => `
                <div class="media-item">
                    ${type === 'image' ? `<img src="${url}" alt="Image ${index + 1}">` : 
                      type === 'video' ? `<video src="${url}" controls></video>` :
                      `<audio src="${url}" controls></audio>`}
                    <button type="button" onclick="removeMedia('${type}', ${index})">Remove</button>
                </div>
            `).join('');
        }

        // Remove media
        function removeMedia(type, index) {
            mediaData[type + 's'].splice(index, 1);
            updateMediaList(type);
        }

        // Show/hide create form
        function showCreateForm() {
            document.getElementById('blogForm').style.display = 'block';
            document.getElementById('formTitle').textContent = 'Create New Blog';
            document.getElementById('blogCreateForm').reset();
            document.getElementById('blogId').value = '';
            mediaData = { images: [], videos: [], audios: [] };
        }

        function hideCreateForm() {
            document.getElementById('blogForm').style.display = 'none';
        }

        // Load categories
        async function loadCategories() {
            try {
                const response = await fetch('../api/categories.php');
                const result = await response.json();
                
                if (result.success) {
                    const categorySelect = document.getElementById('category');
                    categorySelect.innerHTML = '<option value="">Select Category</option>';
                    result.categories.forEach(category => {
                        categorySelect.innerHTML += `<option value="${category.name_en}">${category.name_en}</option>`;
                    });
                }
            } catch (error) {
                console.error('Failed to load categories:', error);
            }
        }

        // Load blogs
        async function loadBlogs() {
            try {
                const response = await fetch('../api/admin/blogs.php');
                const result = await response.json();
                
                if (result.success) {
                    displayBlogs(result.blogs);
                }
            } catch (error) {
                console.error('Failed to load blogs:', error);
            }
        }

        // Display blogs
        function displayBlogs(blogs) {
            const blogsList = document.getElementById('blogsList');
            
            if (blogs.length === 0) {
                blogsList.innerHTML = '<p>No blogs found.</p>';
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
                            <span>${blog.language}</span>
                        </p>
                        <p class="blog-excerpt">${blog.excerpt || 'No excerpt available'}</p>
                        <div class="blog-actions">
                            <button onclick="editBlog(${blog.id})" class="btn-secondary">Edit</button>
                            <button onclick="deleteBlog(${blog.id})" class="btn-danger">Delete</button>
                            ${blog.status === 'draft' ? 
                                `<button onclick="publishBlog(${blog.id})" class="btn-primary">Publish</button>` : 
                                `<button onclick="unpublishBlog(${blog.id})" class="btn-secondary">Unpublish</button>`
                            }
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Form submission
        document.getElementById('blogCreateForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const blogData = {
                id: formData.get('blogId'),
                title: formData.get('title'),
                category: formData.get('category'),
                language: formData.get('language'),
                status: formData.get('status'),
                excerpt: formData.get('excerpt'),
                featured_image: formData.get('featuredImage'),
                content: tinymce.get('content').getContent(),
                media: mediaData
            };
            
            try {
                const response = await fetch('../api/admin/save-blog.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(blogData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Blog saved successfully!');
                    hideCreateForm();
                    loadBlogs();
                } else {
                    alert(result.message || 'Failed to save blog');
                }
            } catch (error) {
                alert('Network error. Please try again.');
            }
        });

        // Edit blog
        async function editBlog(id) {
            try {
                const response = await fetch(`../api/admin/get-blog.php?id=${id}`);
                const result = await response.json();
                
                if (result.success) {
                    const blog = result.blog;
                    document.getElementById('blogId').value = blog.id;
                    document.getElementById('title').value = blog.title;
                    document.getElementById('category').value = blog.category;
                    document.getElementById('language').value = blog.language;
                    document.getElementById('status').value = blog.status;
                    document.getElementById('excerpt').value = blog.excerpt || '';
                    document.getElementById('featuredImage').value = blog.featured_image || '';
                    
                    tinymce.get('content').setContent(blog.content || '');
                    
                    if (blog.media) {
                        mediaData = JSON.parse(blog.media);
                        updateMediaList('image');
                        updateMediaList('video');
                        updateMediaList('audio');
                    }
                    
                    document.getElementById('formTitle').textContent = 'Edit Blog';
                    showCreateForm();
                }
            } catch (error) {
                alert('Failed to load blog for editing');
            }
        }

        // Delete blog
        async function deleteBlog(id) {
            if (!confirm('Are you sure you want to delete this blog?')) return;
            
            try {
                const response = await fetch('../api/admin/delete-blog.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Blog deleted successfully!');
                    loadBlogs();
                } else {
                    alert(result.message || 'Failed to delete blog');
                }
            } catch (error) {
                alert('Network error. Please try again.');
            }
        }

        // Publish/Unpublish blog
        async function publishBlog(id) {
            await updateBlogStatus(id, 'published');
        }

        async function unpublishBlog(id) {
            await updateBlogStatus(id, 'draft');
        }

        async function updateBlogStatus(id, status) {
            try {
                const response = await fetch('../api/admin/update-blog-status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, status })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert(`Blog ${status} successfully!`);
                    loadBlogs();
                } else {
                    alert(result.message || 'Failed to update blog status');
                }
            } catch (error) {
                alert('Network error. Please try again.');
            }
        }
    </script>

    <style>
        .media-upload-section {
            border: 1px solid var(--color-beige);
            border-radius: var(--radius-md);
            overflow: hidden;
        }

        .media-tabs {
            display: flex;
            background: var(--color-beige);
        }

        .media-tab {
            flex: 1;
            padding: 1rem;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all var(--transition-base);
        }

        .media-tab.active {
            background: white;
            color: var(--color-lavender);
        }

        .media-content {
            display: none;
            padding: 1.5rem;
        }

        .media-content.active {
            display: block;
        }

        .media-upload {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .media-url {
            flex: 1;
            padding: 0.5rem;
            border: 1px solid var(--color-beige);
            border-radius: var(--radius-sm);
        }

        .media-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .media-item {
            border: 1px solid var(--color-beige);
            border-radius: var(--radius-sm);
            padding: 1rem;
            text-align: center;
        }

        .media-item img,
        .media-item video,
        .media-item audio {
            width: 100%;
            max-height: 150px;
            object-fit: cover;
            margin-bottom: 0.5rem;
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

        .status.published {
            background: #10b981;
            color: white;
        }

        .status.draft {
            background: #f59e0b;
            color: white;
        }

        .blog-excerpt {
            color: var(--color-grey);
            margin: 1rem 0;
        }

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
            transition: all var(--transition-base);
        }

        .btn-danger:hover {
            background: #dc2626;
        }
    </style>
</body>
</html>