<p align="center">
    <a href="http://krayincrm.com">
        <img 
            src="https://bagisto.com/wp-content/uploads/2021/06/bagisto-logo.png" 
            alt="Total Downloads"
        >
    </a>
</p>

## Topics

1. [Introduction](#introduction)
2. [Requirements](#requirements)
3. [Installation & Configuration](#installation-and-configuration)
4. [License](#license)
5. [Security Vulnerabilities](#security-vulnerabilities)

### Introduction

Krayin ERP is a comprehensive, open-source Enterprise Resource Planning (ERP) solution designed for Small and Medium Enterprises (SMEs) and large-scale enterprises. Built on **[Laravel](https://laravel.com)**, the most popular PHP framework, and **[FilamentPHP](https://filamentphp.com)**, a dynamic resource management library, Krayin ERP offers an extensible and developer-friendly platform for managing every aspect of your business operations.

**Key Features**

-   **Built with Laravel**: Leverages the robust and scalable features of Laravel, ensuring security, reliability, and flexibility for enterprise needs.
-   **Powered by FilamentPHP**: Incorporates FilamentPHP for intuitive resource management, modular forms, and dynamic admin panels.
-   **Highly Modular Design**: Enables seamless integration of custom modules for finance, HR, inventory, CRM, and more.
-   **Open-Source Solution**: Free to use, modify, and extend, fostering community-driven innovation and improvements.
-   **Scalable for Enterprises**: Built to handle complex business processes and workflows, making it suitable for growing organizations.

**Why Choose Krayin ERP?**

-   **Modern Technology Stack**: Combines Laravel's backend strength with FilamentPHP's efficient frontend capabilities.
-   **Developer-Centric Design**: Offers clean code, modular architecture, and extensive documentation for custom development.
-   **User-Friendly Interface**: Features responsive and visually appealing designs with TailwindCSS.
-   **Scalable & Customizable**: Adapts to the unique requirements of businesses of all sizes.
-   **Community-Driven**: Backed by a thriving open-source community for support and innovation.

### Requirements

To run and develop Krayin ERP, ensure your environment meets the following requirements:

-   **PHP**: Version 8.2 or higher.
-   **Laravel**: Version 11.x, for leveraging the latest framework features and improvements.
-   **FilamentPHP**: Version 3.x, for a seamless and modern admin panel experience.
-   **Database**: MySQL 8.0+ or SQLite for database management.
-   **Composer**: Latest version, to manage PHP dependencies.
-   **Node.js & NPM**: Latest stable versions for compiling front-end assets.
-   **Server**: Apache/Nginx with required PHP extensions (e.g., OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON).
-   **Browser**: A modern browser (Chrome, Firefox, Edge) for accessing the admin panel.

### Installation & Configuration

Installing and setting up Krayin ERP is quick and straightforward. Follow the steps below to get started:

1. **Run the Installation Command**  
   Simply execute the following command in your terminal:

    ```bash
    php artisan erp:install
    ```

2. **What Happens During Installation**:

    - **Migrations & Seeders**:
        - All migrations and seeders from the core or base Laravel project are executed to set up the database schema and populate initial data.
    - **Roles & Permissions**:
        - The `Filament Shield` package automatically generates roles and permissions for the application.
    - **Database Seeders**:
        - Additional seeders are generated and executed to ensure the database is fully populated with the required default configurations.

3. **Admin Account Setup**

    - After the installation process, the command prompts you to provide **Admin Login Credentials** (email and password).
    - These credentials are used to log in to the admin panel.

4. **Installation Complete**  
   Once the above steps are finished, the installation process is complete, and you can start using Krayin ERP.

That’s it! With just one command, your Krayin ERP environment is ready to use.

### License

Krayin CRM is a truly opensource CRM framework which will always be free under the OSL-3.0 License.

### Security Vulnerabilities

Please don't disclose security vulnerabilities publicly. If you find any security vulnerability in Krayin CRM then please email us: sales@krayinerp.com.
