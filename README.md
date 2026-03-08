# Cyber Café Management System

The project entitled “Cyber Café Management System using PHP and MySQLite” is a software package, which can be used in cyber cafés for managing clients' computer access efficiently. Now a days cyber terrorism, which is mainly undergone through internet cafés, need to be tackled properly. Thereby, it is indeed necessary to store the valid information of the user who comes for internet access. The system being used, the time at which the user logs in and logs out should be recorded systematically.

## 🚀 Key Features

* **Modern Animated UI**: Implemented a desktop and mobile-friendly dark theme with glassmorphism backgrounds, CSS fade-in animations, neon styled buttons, and smooth sidebar toggles.
* **Database Setup Page**: Easy one-click database initialization.
* **MD5 Security**: Administrator passwords are encrypted using MD5 hashing.
* **Dashboard**: In this section admin can briefly view total number of computers and total number of users who visited the cyber cafe, along with total earnings and currently active users.
* **Computer**: In this section, admin can manage the computers (add/update/delete).
* **Users**: In this section, admin can add new users, update out-time, price, and remarks via a beautiful modal, and view details of old users.
* **Search**: In this section admin can search users on the basis of entry ID, name, or mobile number.
* **Report**: In this section admin can view the number of users who came to the cyber café in a particular date range (From Date - To Date) and view the total revenue generated.
* **Profile**: In this section admin can update his/her profile (Name, Email).
* **Change Password**: In this section admin can securely change his/her passwords.
* **Logout & Recovery**: Admin can logout easily, and there's a simulated password recovery feature on the login page.

## 🛠️ Technology Stack

* **Frontend**: HTML5, CSS3 (Vanilla CSS with Modern Glassmorphism & Neon UI)
* **Backend**: PHP 8+
* **Database**: SQLite (PDO)
* **Icons**: Feather Icons

## ⚙️ Installation & Setup

1. Clone or download this repository.
2. Put the folder in your web server directory (e.g., `htdocs` for XAMPP or `www` for WAMP), or use the built-in PHP server via terminal in the project directory:
   ```bash
   php -S localhost:8001
   ```
3. Open your web browser and navigate to the setup page to initialize the database:
   ```
   http://localhost:8001/setup.php
   ```
4. Click the **Install Database** button. This will automatically create the `cybercafe.sqlite` database file with all necessary tables and a default admin account.
5. Go back to the login page:
   ```
   http://localhost:8001/index.php
   ```
6. Login using the default credentials:
   **Username:** `admin`
   **Password:** `admin123`

## 📱 Responsive Design

The application is fully responsive. On smaller screens (tablets and mobile devices), the sidebar collapses elegantly, and a hamburger menu appears in the top header to toggle navigation. All tables, forms, and grid layouts auto-adjust for the best viewing experience.

## 🔒 Security Features

* **SQL Injection Prevention:** Uses PDO Prepared Statements for all database queries.
* **Password Hashing:** Uses MD5 encryption for admin passwords.
* **Session Management:** Secure PHP sessions used for admin authentication and routing protection.

---
*Created for efficient and modern cyber café management.*
