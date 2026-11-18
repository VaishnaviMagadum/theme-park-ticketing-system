# 🎡 Aurora Park — Theme Park Ticketing & Operations Suite

![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-4.6-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![Chart.js](https://img.shields.io/badge/Chart.js-2.9-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-emerald?style=for-the-badge)

A modern, full-featured **Theme Park Ticketing & Operations Management Web Application** designed for high-traffic park gates, attraction scheduling, and real-time revenue analytics. Built with PHP, MySQL/MariaDB, jQuery/AJAX, Bootstrap 4, and customized with the vibrant **Aurora UI Design System**.

---

## ✨ Key Features

### 🎟️ Point of Sale (POS) & Automated Ticketing
- **Dynamic Fare Calculator:** Real-time JavaScript pricing calculation based on adult/child counts and selected package rates.
- **Unique 12-Digit Ticket Code Generation:** Cryptographically generated collision-free serial codes (`ticket_no`) for every individual pass.
- **Instant Printable Stubs:** Thermal/boarding-pass style printable ticket stubs with ride access details, date stamps, and attendee types.
- **Change Due & Tendered Validation:** Integrated cashier check ensuring tendered cash exceeds total order balance.

### 📊 Real-Time Operations & Revenue Dashboard
- **Live KPI Stat Cards:** Total Rides, Today's Sales, Tickets Sold, and Cumulative Park Revenue.
- **Interactive Visualizations:**
  - **Bar Chart:** Revenue breakdown grouped by ticket package (`Chart.js`).
  - **Doughnut Chart:** Attendee demographic mix (Adults vs. Children).
- **Recent Transactions Stream:** Real-time preview of latest visitor bookings with initials avatar badges.
- **Quick Action Tiles:** One-click shortcuts for ticketing staff and administrators.

### 🎢 Park Attraction & Pricing Management
- **Ride Lineup Control:** Add, edit, list, and delete theme park rides, thrill levels, and descriptions.
- **Customizable Ticket Tiers:** Configure individual ride passes, "Ride All U Can" packages, or general admission entrance rates with distinct adult and child pricing.
- **Conflict Prevention:** Automated backend checks preventing duplicate pricing combinations.

### 📈 Sales Reports & Data Export
- **Date-Range Filtering:** Flexible start and end date selector for daily, weekly, or monthly financial audits.
- **Auditable Summaries:** Detailed breakdown of guest admissions, unit prices, total revenues, and cumulative totals.
- **Clean Print View:** Dedicated, borderless printable report mode ready for printer output or PDF export.

### 👥 Role-Based Access Control (RBAC)
- **Administrator (`Role 1`):** Full system control over rides, pricing packages, sales reports, and user administration.
- **Ticketing Staff (`Role 2`):** Focused, distraction-free POS interface for issuing tickets and browsing customer receipts without access to administrative settings.
- **In-App Account Management:** Profile management modal for editing user names, emails, and passwords on the fly.

---

## 🛠️ Tech Stack & Architecture

- **Backend:** PHP 7.4 / 8.0+ (Procedural OOP Service Layer)
- **Database:** MySQL / MariaDB (Relational schema with foreign keys and auto-increment indexes)
- **Frontend:** HTML5, CSS3, JavaScript (ES6+), jQuery, AJAX
- **UI Framework:** Bootstrap 4, AdminLTE 3, Plus Jakarta Sans, JetBrains Mono
- **Plugins & Libraries:** Chart.js, DataTables, Select2, SweetAlert2, Toastr, Moment.js

---

## 🚀 Quick Start Guide

### Prerequisites
- [XAMPP](https://www.apachefriends.org/), [WAMP](https://www.wampserver.com/), or standalone **PHP 7.4+** & **MySQL 5.7+ / MariaDB 10.4+**.

### 1. Clone the Repository
```bash
git clone https://github.com/VaishnaviMagadum/theme-park-ticketing-system.git
cd theme-park-ticketing-system
```

### 2. Set Up the Database
1. Start your **MySQL / MariaDB** server.
2. Open **phpMyAdmin** (`http://localhost/phpmyadmin`) or MySQL CLI.
3. Create a new database named:
   ```sql
   CREATE DATABASE tpts_db;
   ```
4. Import the schema file located at:
   ```
   database/tpts_db.sql
   ```

### 3. Configure Database Connection
Review `db_connect.php` if your local MySQL port or password differs from default:
```php
<?php 
$conn = new mysqli('localhost', 'root', '', 'tpts_db') 
    or die("Could not connect to mysql: " . mysqli_error($conn));
?>
```

### 4. Run the Application
#### Option A: Using Built-in PHP Server (Fastest)
```bash
php -S localhost:8000
```
Then open: **`http://localhost:8000`**

#### Option B: Using XAMPP / Apache
1. Copy or clone the project folder into your `htdocs` directory (e.g. `C:/xampp/htdocs/Park_Ticketing_System`).
2. Start Apache and MySQL from XAMPP Control Panel.
3. Open: **`http://localhost/Park_Ticketing_System`**

---

## 🔐 Default Demo Credentials

| Role | Email | Password | Access Level |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@admin.com` | `admin123` | Full Access (Rides, Pricing, Sales Reports, Users) |
| **Ticketing Staff** | `marcus.lee@aurorapark.com` | `staff123` | POS Ticketing & Ticket Listing |
| **Ticketing Staff** | `priya.nair@aurorapark.com` | `staff123` | POS Ticketing & Ticket Listing |

---

## 📂 Project Directory Structure

```plaintext
├── assets/                  # CSS, JavaScript vendor plugins, fonts & AdminLTE dist
│   ├── dist/css/styles.css  # Aurora UI Design System & custom themes
│   └── plugins/             # Bootstrap, Chart.js, DataTables, Select2, SweetAlert2
├── database/
│   └── tpts_db.sql          # Clean database structure and initial baseline data
├── admin_class.php          # Core backend controller handling AJAX business logic
├── ajax.php                 # AJAX API router endpoint
├── db_connect.php           # MySQLi database connection handler
├── header.php               # HTML head, Google Fonts, and stylesheet includes
├── topbar.php               # Frosted glass navbar, live park status & user chip
├── sidebar.php              # Twilight gradient sidebar navigation with RBAC filter
├── footer.php               # Modal wrappers, utility loaders, and JS scripts
├── home.php                 # Analytics dashboard, KPI cards & Chart.js widgets
├── login.php                # Aurora split-screen animated login portal
├── new_ticket.php           # Fast POS ticketing desk with live fare calculator
├── ticket_list.php          # Issued tickets directory with print action
├── print_ticket.php         # Thermal boarding pass printable ticket template
├── rides_list.php           # Theme park rides list
├── new_ride.php             # Add / edit ride attraction form
├── pricing_list.php         # Ticket pricing packages list
├── new_pricing.php          # Add / edit ticket pricing tier form
├── reports.php              # Date-range sales auditing & printable reports
├── user_list.php            # Admin & staff user management
├── new_user.php             # Add / edit system user form
└── README.md                # Project documentation
```

---

## 📄 License
This project is open-source under the [MIT License](LICENSE).
