# CIPMS - Project Management System

**CIPMS** (Corporate Integrated Project Management System) is a comprehensive project management application built for **PT. Buana Enjiniring Konsultan (BEC)**. It provides end-to-end management of projects, work breakdown structures (WBS), daily logs, and features integration with Autodesk ACC.

## 🚀 Features

- **Modular Architecture**: Organized into logical modules (Project, Operations, Reporting, System).
- **WBS Management**: Unlimited hierarchy support for project tasks with automated progress calculations.
- **Daily Logs**: Time tracking, clock-in/out functionality, and progress increments for tasks.
- **Autodesk ACC Integration**: Seamless connection with Autodesk Construction Cloud for project files and data.
- **Progress Tracking & Analytics**: Automatic calculation of S-Curve variance, weighted progress rollups, and project tracking.
- **Role-Based Access Control**: Granular access control for different roles within the organization.

## 🛠️ Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Livewire 3 (Volt), Alpine.js, Tailwind CSS 4
- **Bundler**: Vite
- **Database**: MySQL
- **Architecture**: Modular (nwidart/laravel-modules)

## 📦 Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

## ⚙️ Installation

1. Clone the repository:
   ```bash
   git clone <your-repository-url>
   cd project-management-bec
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install NPM dependencies:
   ```bash
   npm install
   ```

4. Set up the environment file:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure your database and Autodesk ACC credentials in the `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_project_management
   DB_USERNAME=root
   DB_PASSWORD=your_password

   APS_CLIENT_ID=your_client_id
   APS_CLIENT_SECRET=your_client_secret
   APS_CALLBACK_URL=http://127.0.0.1:8000/auth/autodesk/callback
   ```

6. Run database migrations:
   ```bash
   php artisan migrate
   ```

7. Start the development server (runs Laravel Server, Queue, and Vite concurrently):
   ```bash
   composer run dev
   ```

## 📂 Project Structure (Modules)

This project heavily utilizes a modular architecture to separate concerns. The core modules are located in the `Modules/` directory:

- `Project`: Core project data, WBS, tasks.
- `Operations`: Daily logs, operational activity, timesheets.
- `Reporting`: S-Curves, progress analytics, generated reports.
- `System`: User management, roles, permissions, and system configurations.

## 📄 License

This project is proprietary software belonging to **PT. Buana Enjiniring Konsultan (BEC)**.
