# BrgyConnect

**BrgyConnect** is a web-based **Barangay Information System** designed to help barangay officials manage, organize, and access important community records through a centralized digital platform.

The system provides various modules for managing barangay information, including resident records, household information, blotter reports, and infant immunization records.

## Features

### 🏘️ Barangay Information Management

* Manage household records
* Manage resident information
* Search and view resident records
* Maintain organized barangay records
* Track resident status and information

### ⚖️ Blotter System

The Blotter System allows authorized barangay personnel to digitally record and manage incidents reported within the barangay.

Features include:

* Create blotter records
* Record complainant and respondent information
* Record incident details
* Track blotter status
* View and manage previous blotter records
* Search and filter blotter reports
* Maintain incident history

### 👶 Infant Immunization System

The Infant Immunization System helps barangay health personnel keep track of infants and their vaccination records.

Features include:

* Register infant records
* Record infant information
* Track immunization history
* Record administered vaccines
* Monitor scheduled vaccinations
* Track completed and pending immunizations
* View vaccination records

### 👥 User and Role Management

The system provides role-based access to ensure that users can only access the functions appropriate to their responsibilities.

Example roles may include:

* Administrator
* Barangay Officials
* Committee Heads
* Health Personnel
* Other authorized personnel

## Technologies Used

* **Laravel**
* **PHP**
* **MySQL**
* **Blade**
* **Tailwind CSS**
* **Alpine.js**
* **JavaScript**
* **Vite**

## Requirements

Before installing BrgyConnect, make sure the following are installed:

* PHP 8.2 or higher
* Composer
* Node.js
* npm
* MySQL
* Git

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/brgyconnect.git
```

### 2. Navigate to the Project

```bash
cd brgyconnect
```

### 3. Install PHP Dependencies

```bash
composer install
```

### 4. Install JavaScript Dependencies

```bash
npm install
```

### 5. Create the Environment File

```bash
cp .env.example .env
```

### 6. Generate the Application Key

```bash
php artisan key:generate
```

### 7. Configure the Database

Open the `.env` file and configure your MySQL database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=brgyconnect
DB_USERNAME=root
DB_PASSWORD=
```

Create a MySQL database named:

```text
brgyconnect
```

### 8. Run Database Migrations that includes seeders

```bash
php artisan migrate --seed
```

### 9. Create the Storage Link

```bash
php artisan storage:link
```

### 10. Start the Laravel Server

```bash
php artisan serve
```

The application will normally be available at:

```text
http://127.0.0.1:8000
```

### 11. Start Vite

In another terminal:

```bash
npm run dev
```

## Project Modules

| Module               | Description                                              |
| -------------------- | -------------------------------------------------------- |
| Dashboard            | Provides an overview of important barangay information   |
| Household Management | Manages household records                                |
| Resident Management  | Manages barangay resident information                    |
| Blotter System       | Records and manages reported incidents                   |
| Infant Immunization  | Tracks infant vaccination records                        |
| User Management      | Manages system users and their roles                     |
| Reports              | Provides access to relevant barangay records and reports |

## Security

BrgyConnect uses authentication and role-based authorization to restrict access to sensitive barangay information.

Users are only given access to the modules and actions allowed by their assigned role.

## Development

To run BrgyConnect during development, use:

```bash
php artisan serve
```

and:

```bash
npm run dev
```

## Screenshots

Screenshots of the system can be added here to demonstrate the different modules.

### Dashboard

*Add dashboard screenshot here.*

### Resident Management

*Add resident management screenshot here.*

### Blotter System

*Add blotter system screenshot here.*

### Infant Immunization

*Add infant immunization screenshot here.*

## Future Improvements

Potential improvements for BrgyConnect include:

* SMS notifications
* Automated vaccination reminders
* Printable barangay reports
* Advanced dashboard analytics
* Improved reporting and statistics
* Online request processing
* Additional barangay management modules

## Purpose

BrgyConnect was developed to help modernize barangay record management by reducing reliance on manual paperwork and providing authorized personnel with a centralized system for managing community information.

## License

This project is developed for educational and/or barangay information management purposes.