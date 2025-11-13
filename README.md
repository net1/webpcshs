# ระบบบริหารกิจการดิจิทัล (DigM-I)

**School Management System for Princess Chulabhorn's College Kamphaeng Phet**

A modern, responsive web application built with **Laravel** for managing school operations including student records, dormitory management, food supplements, hospital visits, and more.

![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg) ![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg) ![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg) ![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-38bdf8.svg)

---

## 🌟 Features

### ✅ Core Features (Completed)
- 🔐 **Simple Authentication System** (admin123 / pcshskp123)
- 📊 **Comprehensive Dashboard** with real-time statistics
- 👥 **Student Management** (Add, Delete, Search)
- 🏠 **Dormitory Management**
- 📄 **Reports System** with filtering
- 📱 **Fully Responsive Design** (Mobile, Tablet, Desktop)
- 🎨 **Beautiful UI/UX** with **Sarabun font** and **Tailwind CSS**
- ⚡ **Fast Performance** with Eloquent ORM

### 📝 9 Record Types Supported
1. 🏠 Dormitory Entry/Exit
2. 🥛 Food Supplements
3. 🏥 Hospital Visits
4. 👔 Uniform Inspection
5. 📋 Daily Duty Reports
6. 🔧 Repair Requests
7. 📦 Inventory Management
8. 📄 Permission Requests
9. 📋 Student Leave Requests

---

## 📋 Requirements

- **PHP >= 8.1**
- **MySQL >= 8.0** or **MariaDB >= 10.3**
- **Composer**
- **Web Server** (Apache/Nginx) or PHP built-in server

---

## 🚀 Quick Start

### 1. Install Dependencies
```bash
composer install
```

### 2. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
DB_DATABASE=digm_system
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. Setup Database
```bash
# Create database
mysql -u root -p -e "CREATE DATABASE digm_system CHARACTER SET utf8mb4;"

# Run migrations
php artisan migrate

# Seed sample data (optional)
php artisan db:seed
```

### 4. Start Server
```bash
php artisan serve
```

Visit: **http://localhost:8000**

Login with: `admin123` or `pcshskp123`

---

## 📁 Project Structure

```
webpcshs/
├── app/
│   ├── Http/Controllers/      # Controllers (Auth, Dashboard, Management, etc.)
│   ├── Models/                # Eloquent Models (10+ models)
│   └── Http/Middleware/       # CheckAuth middleware
├── database/
│   ├── migrations/            # 10 migration files
│   └── seeders/               # Sample data seeders
├── resources/views/           # Blade templates
│   ├── layouts/app.blade.php  # Main layout with Sarabun font
│   ├── auth/login.blade.php
│   ├── dashboard/index.blade.php
│   ├── management/index.blade.php
│   └── reports/index.blade.php
├── routes/web.php             # Application routes
└── config/                    # Laravel configuration
```

---

## 🎨 UI/UX Features

- ✅ **Sarabun Font** - Google Fonts (Thai-optimized)
- ✅ **Tailwind CSS 3** - Modern utility-first CSS
- ✅ **Responsive Grid** - Mobile-first design
- ✅ **Smooth Animations** - Slide-in & fade effects
- ✅ **Color-coded Stats** - Visual identification
- ✅ **Auto-hiding Messages** - Flash notifications
- ✅ **Live Clock** - Real-time date/time display

---

## 📊 Database

### Tables
- `dormitories`, `students`, `records`
- `record_students`, `food_records`, `hospital_records`
- `repair_records`, `inventory_records`
- `permission_records`, `student_leave_records`

### Relationships
- Dormitory → Students (1:Many)
- Dormitory → Records (1:Many)
- Record → RecordStudents (1:Many)
- Record → Specific Records (1:1)

---

## 🔧 Development

### Useful Commands
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear

# Reset database
php artisan migrate:fresh --seed

# Run tests
php artisan test
```

---

## 🌐 Production Deployment

### Apache
```apache
DocumentRoot /path/to/webpcshs/public
<Directory /path/to/webpcshs/public>
    AllowOverride All
</Directory>
```

### Nginx
```nginx
root /path/to/webpcshs/public;
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### Checklist
- ✅ Set `APP_ENV=production`
- ✅ Set `APP_DEBUG=false`
- ✅ Configure database
- ✅ Set file permissions
- ✅ Enable HTTPS

---

## 📞 Support

**Developed by:**
กลุ่มบริหารกิจการนักเรียน
โรงเรียนวิทยาศาสตร์จุฬาภรณราชวิทยาลัย กำแพงเพชร

**Powered by:** Laravel, Tailwind CSS, Sarabun Font

---

**Made with ❤️ for PCSHS Kamphaeng Phet**
