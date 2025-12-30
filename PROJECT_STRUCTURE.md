# 🕉️ SWAYAM - Project Structure & Workflow

## 📁 **Complete Directory Structure**

```
Swayam/
├── 📄 **Frontend Pages (User Interface)**
│   ├── index.html                    # Homepage - Entry point
│   ├── login.html                    # User/Admin login
│   ├── register.html                 # User registration
│   ├── dashboard.html                # User dashboard
│   ├── create-blog.html              # Blog creation interface
│   ├── blogs.html                    # Public blog listing
│   └── my-blogs.html                 # User's blog management
│
├── 📁 **admin/** (Admin Panel)
│   ├── index.html                    # Admin analytics dashboard
│   ├── login.php                     # Admin login with PHP
│   ├── dashboard.html                # Admin main dashboard
│   ├── blog-management.html          # Blog approval interface
│   └── masters.html                  # Masters/Experts management
│
├── 📁 **api/** (Backend APIs)
│   ├── 📁 config/
│   │   └── database.php              # Database connection
│   ├── 📁 admin/
│   │   ├── setup-session.php         # Admin session setup
│   │   ├── blog-approval.php         # Blog approval API
│   │   └── dashboard-stats.php       # Admin statistics
│   ├── 📁 user/
│   │   ├── setup-session.php         # User session setup
│   │   └── dashboard-stats.php       # User statistics
│   ├── 📁 blogs/
│   │   └── create.php                # Blog creation API
│   └── login.php                     # Main login API
│
├── 📁 **public/** (Static Assets)
│   ├── 📁 css/
│   │   ├── style.css                 # Main website styles
│   │   └── admin.css                 # Admin panel styles
│   ├── 📁 js/
│   │   ├── auth.js                   # Authentication scripts
│   │   └── main.js                   # Main functionality
│   └── 📁 images/
│       └── (website images)
│
├── 📁 **uploads/** (User Content)
│   └── 📁 blogs/
│       └── (uploaded media files)
│
├── 📄 **Database & Setup Files**
│   ├── swayam_database.sql           # Database structure
│   ├── setup-admin-system.php       # Admin system setup
│   ├── setup-complete-workflow.php  # Complete workflow setup
│   ├── add-blogs-table.php          # Blog table creation
│   ├── test-db.php                   # Database connection test
│   └── check-files.php              # File verification
│
└── 📄 **Documentation**
    ├── README.md                     # Project overview
    └── PROJECT_STRUCTURE.md         # This file
```

## 🔄 **Complete Website Workflow**

### **1. User Journey Flow**

```
🏠 Homepage (index.html)
    ↓
👤 User Registration/Login (login.html)
    ↓
📊 User Dashboard (dashboard.html)
    ↓
✍️ Create Blog (create-blog.html)
    ↓
📤 Submit for Review (api/blogs/create.php)
    ↓
⏳ Pending Admin Approval
    ↓
✅ Published on Website
```

### **2. Admin Journey Flow**

```
🔐 Admin Login (admin/login.php)
    ↓
📈 Admin Dashboard (admin/dashboard.html)
    ↓
📋 Blog Management (admin/blog-management.html)
    ↓
👁️ Review Pending Blogs
    ↓
✅ Approve/❌ Reject (api/admin/blog-approval.php)
    ↓
📢 Blog Published/Rejected
```

## 🌐 **URL Structure & Access Points**

### **Public URLs**
- **Homepage**: `http://localhost/Swayam/`
- **Login**: `http://localhost/Swayam/login.html`
- **Register**: `http://localhost/Swayam/register.html`
- **Blogs**: `http://localhost/Swayam/blogs.html`

### **User Dashboard URLs**
- **Dashboard**: `http://localhost/Swayam/dashboard.html`
- **Create Blog**: `http://localhost/Swayam/create-blog.html`
- **My Blogs**: `http://localhost/Swayam/my-blogs.html`

### **Admin Panel URLs**
- **Admin Login**: `http://localhost/Swayam/admin/login.php`
- **Admin Dashboard**: `http://localhost/Swayam/admin/dashboard.html`
- **Blog Management**: `http://localhost/Swayam/admin/blog-management.html`
- **Masters Management**: `http://localhost/Swayam/admin/masters.html`

### **API Endpoints**
- **User Login**: `POST /api/login.php`
- **Blog Creation**: `POST /api/blogs/create.php`
- **Blog Approval**: `POST /api/admin/blog-approval.php`
- **Dashboard Stats**: `GET /api/user/dashboard-stats.php`
- **Admin Stats**: `GET /api/admin/dashboard-stats.php`

## 🗄️ **Database Structure**

### **Core Tables**
```sql
users                    # User accounts (admin/user)
├── id, name, email, password, role, status, language

blogs                    # Blog content
├── id, user_id, title, content, category, language
├── tags, status, media_files, created_at
├── approved_by, approved_at, rejected_by, rejected_at

admin_activity_log       # Admin actions tracking
├── id, admin_id, action, target_type, target_id

user_activity           # User engagement tracking
├── id, user_id, activity_type, activity_data

content_categories      # Blog categories
├── id, name, name_hi, name_mr, description, icon

masters                 # Spiritual teachers/experts
├── id, user_id, specialization, bio, skills, rating
```

## 🎯 **Key Features & Functionality**

### **User Features**
- ✅ **Multi-language Support** (Hindi, English, Marathi)
- ✅ **Rich Text Editor** (TinyMCE integration)
- ✅ **Media Upload** (Images, Videos, Audio)
- ✅ **Category Selection** (Spiritual Journey, Meditation, etc.)
- ✅ **Draft System** (Save and continue later)
- ✅ **Personal Dashboard** (Statistics and blog management)

### **Admin Features**
- ✅ **Blog Approval Workflow** (Review, Approve, Reject)
- ✅ **Real-time Dashboard** (Pending count, statistics)
- ✅ **User Activity Tracking** (Most active users, content trends)
- ✅ **Content Management** (Categories, masters verification)
- ✅ **Multi-language Content** (Review content in all languages)

### **Technical Features**
- ✅ **Session Management** (PHP sessions for authentication)
- ✅ **File Upload System** (Secure media handling)
- ✅ **Database Abstraction** (PDO for security)
- ✅ **Responsive Design** (Mobile-friendly interface)
- ✅ **Error Handling** (Graceful fallbacks)

## 🚀 **Setup & Installation Process**

### **1. Initial Setup**
```bash
# 1. Start XAMPP (Apache + MySQL)
# 2. Create database 'swayam'
# 3. Import swayam_database.sql
```

### **2. System Configuration**
```bash
# Run setup scripts in order:
http://localhost/Swayam/add-blogs-table.php
http://localhost/Swayam/setup-admin-system.php
http://localhost/Swayam/setup-complete-workflow.php
```

### **3. Verification**
```bash
# Test database connection:
http://localhost/Swayam/test-db.php

# Check file structure:
http://localhost/Swayam/check-files.php
```

## 🔐 **Authentication & Security**

### **User Roles**
- **Admin**: Full system access, blog approval, user management
- **User**: Blog creation, personal dashboard, content submission
- **Master**: Verified spiritual teachers (future feature)

### **Security Measures**
- ✅ **Password Hashing** (PHP password_hash())
- ✅ **SQL Injection Protection** (Prepared statements)
- ✅ **File Upload Validation** (Type and size restrictions)
- ✅ **Session Security** (Proper session management)
- ✅ **XSS Prevention** (Input sanitization)

## 📊 **Data Flow Architecture**

### **Blog Creation Flow**
```
User Input → Form Validation → File Upload → Database Insert → Admin Queue
```

### **Blog Approval Flow**
```
Admin Review → Content Preview → Approval Decision → Status Update → Publication
```

### **User Analytics Flow**
```
User Actions → Activity Logging → Statistics Calculation → Dashboard Display
```

## 🌟 **Spiritual Concepts Integration**

### **Content Categories**
- 🕉️ **Spiritual Journey** (आध्यात्मिक यात्रा)
- 🧘 **Meditation** (ध्यान)
- 🤸 **Yoga & Wellness** (योग और स्वास्थ्य)
- 🎨 **Arts & Culture** (कला और संस्कृति)
- 📚 **Philosophy** (दर्शन)
- 💭 **Personal Stories** (व्यक्तिगत कहानियां)

### **Language Support**
- **Hindi** (हिंदी) - Primary spiritual language
- **English** - Global accessibility
- **Marathi** (मराठी) - Regional connection

### **Core Philosophy**
> **"साधं, सोपं, सरळ जीवन जगण्याची कला"**
> 
> *"The art of living a simple, easy, and straightforward life"*

## 🎯 **Future Enhancements**

### **Phase 2 Features**
- 📧 Email notifications for blog status
- 🔍 Advanced search and filtering
- 💬 Comment system for blogs
- 📱 Progressive Web App (PWA)
- 🏔️ Retreat booking system
- 👥 Master verification system

### **Phase 3 Features**
- 🌐 Multi-tenant architecture
- 📊 Advanced analytics dashboard
- 🎥 Video streaming integration
- 💰 Donation/contribution system
- 🌍 Global community features

---

**🕉️ This structure supports the complete vision of Swayam as a platform for spiritual growth, wisdom sharing, and community building. 🕉️**