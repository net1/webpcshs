# ระบบบริหารกิจการดิจิทัล (DigM-I)

School Management System for Princess Chulabhorn's College Kamphaeng Phet

## Features

- 🔐 Authentication System
- 📊 Dashboard with Statistics
- 📝 9 Types of Records Management
- 👥 Student & Dormitory Management
- 📄 Report Approval System
- 📤 Excel Import/Export

## Installation

1. **Database Setup**
   ```bash
   mysql -u root -p < database/schema.sql
   ```

2. **Configure Database**
   - Edit `config/database.php` with your MySQL credentials

3. **Web Server**
   - Point your web server to the project root
   - Make sure PHP 7.4+ is installed

4. **Login**
   - Password: `admin123` or `pcshskp123`
   - Approval Code: `2812`

## Directory Structure

```
webpcshs/
├── config/          # Configuration files
├── database/        # Database schema
├── includes/        # PHP includes (header, footer, functions)
├── assets/          # CSS, JS, images
├── pages/           # Application pages
├── api/             # API endpoints for AJAX
└── index.php        # Entry point
```

## Technologies

- PHP 8+
- MySQL 8+
- Tailwind CSS
- PHPSpreadsheet (for Excel)

## Credits

Developed by Student Affairs Division
Princess Chulabhorn's College Kamphaeng Phet
