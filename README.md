# HTML Element Counter

## Project Overview

This project follows the MVC (Model-View-Controller) pattern and is organized into various folders to manage configuration, controllers, models, migrations, utilities, and public assets. This documentation provides an overview of the project structure and detailed deployment instructions.

## File Organization

### 1. `config` Folder

**Purpose:** Stores configuration files for the application.

- **`Database.php`:** Contains database connection settings. Update variables like `$host`, `$dbname`, `$dbusername`, and `$dbpassword` to match the target environment's database credentials.

### 2. `controllers` Folder

**Purpose:** Contains controller classes that handle client-side requests and application logic.

- **`request_controller.php`:** Manages client-side requests, processes data, and returns responses. Handles interactions between the user and the application's data.

### 3. `migrations` Folder

**Purpose:** Contains migration files for managing database schema changes.

- **Migration Files:** Each migration file is responsible for creating or modifying database tables. These files ensure that the database schema is kept in sync with the application's requirements.

### 4. `models` Folder

**Purpose:** Contains model classes that interact with the database and manage application data.

- **Model Classes:** Represent the application's data entities and include methods for data retrieval, insertion, and manipulation. Models handle data validation and business logic.

### 5. `utils` Folder

**Purpose:** Includes utility classes for additional functionality.

- **`PageLoader.php`:** Provides methods for handling page loading operations.
- **`Validator.php`:** Offers validation methods for input data, ensuring that data meets specific criteria before processing.

### 6. `public` Folder

**Purpose:** Stores public-facing assets such as stylesheets and JavaScript files.

- **Stylesheets and JavaScript Files:** These files are used to manage the frontend appearance and behavior of the application. They are accessible from the web and are essential for handling forms and other user interactions.

### 7. `index.php`

**Purpose:** The front page of the application and the entry point for all requests.

- **Initialization and Routing:** This file includes the initialization code and routing logic. It directs requests to the appropriate controllers and handles the application flow.

## Deployment Instructions

### Prerequisites

- **cPanel Access:** Ensure you have access to the cPanel of your hosting provider.
- **Website Files:** Prepare your PHP website files.

### Steps for Deployment

1. **Log in to cPanel**
   - Access the cPanel login page of your hosting provider.
   - Enter your username and password to log in.

2. **Upload Website Files**
   - Navigate to **File Manager** in cPanel.
   - Go to the desired directory (e.g., `public_html` or a subdomain folder).
   - Click on **Upload** to upload your website files. You can also upload ZIP files and extract them within cPanel.

3. **Set Up the Database (if applicable)**
   - Go to **MySQL Databases** in cPanel.
   - Create a new database and note the name.
   - Create a new database user and note the username and password.
   - Add the user to the database with appropriate permissions.

4. **Run Migration File**
   - To create the tables, go to the root folder and run the following command in the terminal:
     ```bash
     php migrations/migrate.php
     ```

5. **Update Configuration Files**
   - Open the `Database.php` file in the `config` folder.
   - Update the following variables with your new database settings:
     ```php
     private $host = 'localhost'; // or your database host
     private $dbname = 'your_database_name';
     private $dbusername = 'your_database_username';
     private $dbpassword = 'your_database_password';
     ```
   - Save the changes.

6. **Check PHP Settings**
   - Verify the PHP version in **Select PHP Version** or similar options in cPanel. This project is developed using PHP version 8.2.

7. **Set Up Domain/Subdomain**
   - If deploying to a new domain or subdomain, go to **Domains** or **Subdomains** in cPanel.
   - Add the domain/subdomain and set the document root to the correct directory.

8. **Verify Website**
   - Visit your domain/subdomain to ensure the website is working correctly.
   - Check for errors in cPanel's **Error Logs** and troubleshoot as needed.
