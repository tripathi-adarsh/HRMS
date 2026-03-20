# HRMS Pro — Human Resource Management System

A full-featured HR Management System built with **Laravel 8**, **Bootstrap 5**, and **MySQL**. Designed for small to mid-size organizations to manage employees, attendance, payroll, leaves, performance, and more — with a clean, modern UI and dark mode support.

---

## Author

**Adarsh Tripathi**
GitHub: [@tripathi-adarsh](https://github.com/tripathi-adarsh)

---

## Tech Stack

- **Backend:** PHP 7.4, Laravel 8
- **Frontend:** Bootstrap 5.3, Bootstrap Icons, Chart.js
- **Database:** MySQL
- **Auth:** Laravel Breeze + Spatie Laravel Permission
- **API Auth:** Laravel Sanctum

---

## Features

### Admin / HR
- **Dashboard** — Live stats: total employees, present today, on leave, monthly payroll, attendance chart, department breakdown
- **Employee Management** — Full CRUD with department, designation, salary, joining date, photo
- **Department & Designation** — Manage org structure
- **Attendance Management** — Mark attendance with styled toggle buttons (Present / Absent / Late / Half Day / Holiday), punch in/out times, department filter, live count bar, bulk mark all
- **Leave Management** — Approve/reject leave requests, filter by employee, department, type, status
- **Payroll** — Generate monthly payroll, edit bonus/deductions, view payslip, filter by month/department/status
- **Salary Calculator** — Advanced CTC breakdown tool with HRA, PF, ESI, Professional Tax, New/Old tax regime, live doughnut chart
- **Performance** — Assign tasks, rate employees (1–5 stars), track status
- **Reports** — Attendance, Leave, and Payroll reports with full filters (employee, department, type, status, month/year) and summary cards

### Employee Self-Service (ESS)
- **My Dashboard** — Personalized portal with live clock, punch in/out, this month's attendance summary with progress bar
- **Salary Overview** — View CTC, estimated pro-rated in-hand salary, payroll breakdown, payment status, 6-month history
- **Leave Portal** — View leave summary, apply for leave, track request status
- **Salary Calculator** — Calculate own in-hand salary with custom inputs
- **My Performance** — View assigned tasks and ratings
- **My Attendance** — Calendar view of monthly attendance

### UI/UX
- Deep purple gradient sidebar with glowing active states
- Dark mode toggle (persisted in localStorage)
- Fully responsive — works on mobile, tablet, desktop
- Custom pagination with Bootstrap 5 styling
- Animated login page with clickable demo credential cards

---

## Demo Credentials

| Role     | Email                          | Password |
|----------|-------------------------------|----------|
| Admin    | admin@hrms.com                | password |
| HR       | hr@hrms.com                   | password |
| Employee | arjun.sharma@company.com      | password |

---

## Installation

```bash
# Clone the repo
git clone https://github.com/tripathi-adarsh/HRMS.git
cd HRMS

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Configure your database in .env
DB_DATABASE=hrms
DB_USERNAME=root
DB_PASSWORD=

# Run migrations and seed fake data
php artisan migrate --seed

# Install frontend dependencies (optional)
npm install && npm run dev

# Start the server
php artisan serve
```

Then open `http://127.0.0.1:8000` and log in with any demo credential above.

---

## Project Structure

```
app/
  Http/Controllers/
    AttendanceController.php
    DashboardController.php
    ESSController.php          # Employee Self-Service
    LeaveController.php
    PayrollController.php
    PerformanceController.php
    ReportController.php
    ...
  Models/
    Employee, Attendance, Leave, Payroll, Performance ...

resources/views/
  attendance/     # Attendance management + calendar
  auth/           # Login page
  dashboard.blade.php
  ess/portal.blade.php        # Employee portal
  employees/      # CRUD views
  leaves/         # Leave management
  payroll/        # Payroll + salary calculator
  performance/    # Performance tracking
  reports/        # Attendance, leave, payroll reports
  layouts/app.blade.php       # Main layout with sidebar

database/
  migrations/     # All table schemas
  seeders/        # 20 fake employees + attendance, leaves, payroll
```

---

## License

MIT License — free to use and modify.
