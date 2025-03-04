# Civil Registry Application

This project is a simple web application developed in pure PHP (without frameworks) to manage civil registry acts (birth, marriage, and death) for Benin. It uses a MySQL database for storage and the FPDF library to generate downloadable PDF documents for each act.

## Features

- **User Authentication:** Login and logout functionality.
- **Act Recording:** Record acts of birth, marriage, and death.
- **Act Viewing:** View a list of recorded acts.
- **PDF Download:** Generate and download a PDF document for each act.
- **Environment Configuration:** Uses a `.env` file to securely store sensitive data.

## Requirements

- PHP 7.2 or higher
- MySQL or MariaDB
- Apache (or any compatible web server)
- Composer (for dependency management)
- [FPDF](http://www.fpdf.org/) library

## Installation

### 1. Clone the Repository

```bash
git clone <repository_url>
cd <repository_folder>
```

### 2. Set Up the Database
Create a MySQL database (e.g., etat_civil).
Import the provided database schema or run the SQL commands to create the tables:

```bash
mysql -u your_username -p etat_civil < database.sql
```

### 3. Configure Environment Variables
Create a .env file in the project root with the following content (adjust as needed).

### 4. Install Composer Dependencies
If you are using Composer, run:

```bash
Copy
composer install
```
This will install dependencies like vlucas/phpdotenv to load environment variables.

### 5. Install FPDF
- Download FPDF from http://www.fpdf.org/.
- Extract the downloaded archive and place the fpdf folder in the project root (or update the path in your scripts accordingly).

### 6. Configure Apache
Place your project folder inside your web server root (for example, /var/www/html/ on Ubuntu) and ensure Apache has the proper permissions. Restart Apache if necessary:

```bash
Copy
sudo systemctl restart apache2`
```