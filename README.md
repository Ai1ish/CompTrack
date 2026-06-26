<<<<<<< HEAD
# 💻 CompTrack

**Computer Laboratory Inventory Management System**

CompTrack is a PHP and MySQL web-based system for managing computer laboratory equipment. It helps laboratory administrators and staff track assets, monitor equipment status, record maintenance, upload item photos, and generate printable reports.

## 🎯 Target Users

- Laboratory Administrator
- ICT Staff
- Laboratory Assistant

## ✨ Main Features

- 🔐 User registration, login, logout, and role management
- 🖥️ Equipment CRUD: add, view, edit, and delete equipment
- 🖼️ Optional equipment picture upload
- 🏷️ Category management
- 🔎 Search and filter by keyword, category, room, status, or date added
- 📊 Dashboard with inventory totals and recently added equipment
- 🛠️ Maintenance record management
- 🖨️ Printable inventory and maintenance reports
- 📝 Activity logs for user actions

## 📌 Equipment Status Options

- Available
- In Use
- Under Maintenance
- Damaged
- Retired

## 🧩 Core Modules

| Module | Purpose |
| --- | --- |
| Authentication | Register, login, logout, sessions, and password hashing |
| Dashboard | Shows inventory summary and recent equipment |
| Equipment | Manages laboratory assets and item photos |
| Categories | Manages equipment types |
| Maintenance | Records repair history and schedules |
| Reports | Generates printable reports |
| Logs | Tracks important user actions |

## 🗄️ Database Tables

| Table | Description |
| --- | --- |
| `users` | Stores user accounts and roles |
| `categories` | Stores equipment categories |
| `equipment` | Stores asset details, status, and image path |
| `maintenance` | Stores maintenance and repair records |
| `activity_logs` | Stores user activity history |

## 🛡️ Security Features

- PDO prepared statements for SQL injection protection
- `htmlspecialchars()` for XSS protection
- `password_hash()` for secure passwords
- PHP session authentication
- Server-side input validation
- Duplicate asset number checking
- Image upload validation

## 🛠️ Technologies Used

| Area | Technology |
| --- | --- |
| Frontend | HTML5, CSS3, Bootstrap 5, Bootstrap Icons, JavaScript |
| Backend | PHP 8 |
| Database | MySQL |
| Tool | phpMyAdmin |
| Environment | XAMPP |

## 📁 Folder Structure

```text
CompTrack/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── auth/
│   ├── login.php
│   ├── logout.php
│   └── register.php
├── categories/
│   └── index.php
├── config/
│   └── database.php
├── dashboard/
│   └── index.php
├── equipment/
│   ├── add.php
│   ├── delete.php
│   ├── edit.php
│   ├── form.php
│   ├── list.php
│   └── view.php
├── includes/
│   ├── app_start.php
│   ├── footer.php
│   ├── functions.php
│   ├── header.php
│   ├── navbar.php
│   └── sidebar.php
├── logs/
│   └── index.php
├── maintenance/
│   ├── add.php
│   ├── delete.php
│   ├── edit.php
│   ├── form.php
│   └── index.php
├── reports/
│   └── index.php
├── uploads/
│   └── equipment/
├── comptrack.sql
├── database_update.sql
├── index.php
└── README.md
```

## 🚀 How to Run

1. Start **Apache** and **MySQL** in XAMPP.
2. Open phpMyAdmin:

   ```text
   http://localhost/phpmyadmin
   ```

3. Import the database file:

   ```text
   comptrack.sql
   ```

4. Open the project:

   ```text
   http://localhost/PHP%20PROJECT/CompTrack/
   ```

5. Register an account and log in.

## 🔄 Updating an Existing Database

If you already imported an older version of the database, run this file once in phpMyAdmin:

```text
database_update.sql
```

## ⚙️ Database Configuration

Database settings are located in:

```text
config/database.php
```

Default XAMPP configuration:

```php
$host = 'localhost';
$dbName = 'comptrack_db';
$username = 'root';
$password = '';
```

## 🖼️ Image Upload Notes

Uploaded equipment pictures are saved in:

```text
uploads/equipment/
```

Allowed image types:

- JPG
- PNG
- WebP

Maximum file size:

```text
2 MB
```

If images do not upload, make sure the `uploads/equipment/` folder is writable by Apache.
=======
# CompTrack
>>>>>>> f0d48ae09e53fd71c484e8eb72fdd522cc054831
