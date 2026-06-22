<div align="center">

<img src="https://upload.wikimedia.org/wikipedia/commons/0/00/Telkom_University_Logo.png" width="180">

# 🩺 ForenSys
### Forensic Autopsy Case Management System

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-06B6D4?style=for-the-badge&logo=tailwindcss)

### 🏛️ Telkom University Surabaya
**Faculty of Industrial Engineering**  
**Information Systems Study Program**

---

### 📚 Course
**System Testing and Implementation (IS-07-03)**

### 👨‍💻 Developed By
**Group 2**

| Name |
|--------|
| Muhammad Naufal Syamdany |
| Alma Amanina Mujahida |
| Mutiara Adzkia Ibadiah |
| Masyta Gita Faradila |

</div>

---

# 📖 About The Project

ForenSys (Forensic System) is a web-based information system designed to support the management of forensic autopsy cases within a forensic medicine department.

The system digitalizes and streamlines the forensic workflow, starting from case registration, doctor assignment, autopsy examination, verification, until final case closure.

This project was developed as part of the **System Testing and Implementation** course at **Telkom University Purwokerto**.

---

# 🎯 Objectives

- Improve forensic case management efficiency
- Reduce manual documentation processes
- Ensure traceability of forensic examination records
- Support role-based workflow management
- Provide a centralized repository for forensic case information

---

# ✨ Main Features

## 👤 Registration Officer

- Create new forensic cases
- Manage victim information
- Upload supporting documents
- Monitor case status

## 🩺 Forensic Doctor

- View assigned cases
- Perform autopsy examination
- Record examination findings
- Input cause of death analysis
- Manage toxicology requests

## 🔍 Verification Process

- Verify completed examinations
- Review forensic reports
- Approve examination results

## 📁 Case Management

- Search cases
- Filter cases by status
- View case history
- Track workflow progress

---

# 🔄 System Workflow

```text
Registration
      │
      ▼
Case Created
      │
      ▼
Doctor Assigned
      │
      ▼
Autopsy Examination
      │
      ▼
Verification
      │
      ▼
Case Closed
```

---

# 🏗️ Technology Stack

| Technology | Description |
|------------|-------------|
| Laravel 12 | Backend Framework |
| PHP 8 | Programming Language |
| MySQL | Database Management |
| Blade | Templating Engine |
| Tailwind CSS | User Interface Styling |
| PHPUnit | Automated Testing |
| Pest | Feature Testing |

---

# 🧪 Testing Scope

The project includes:

- Functional Testing
- Feature Testing
- Validation Testing
- User Acceptance Testing (UAT)
- Black Box Testing

Example test scenarios:

- Create Case
- Search Case
- Assign Doctor
- Save Examination
- Verify Examination
- Close Case

---

# 📂 Project Structure

```text
ForenSys/
│
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
│
├── composer.json
├── package.json
├── phpunit.xml
└── README.md
```

---

# ⚙️ Installation

Clone repository:

```bash
git clone https://github.com/MNaufalDany/ForenSys.git
```

Move into project directory:

```bash
cd ForenSys
```

Install dependencies:

```bash
composer install
npm install
```

Create environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Run migrations:

```bash
php artisan migrate
```

Start development server:

```bash
php artisan serve
```

---

# 📸 System Modules

- Dashboard
- Case Registration
- Case Assignment
- Autopsy Examination
- Verification
- Case Closure
- User Management
- Reporting

---

# 🎓 Academic Information

**Course:** System Testing and Implementation (IS-07-03)

**Institution:** Telkom Surabaya

**Academic Year:** 2025/2026

---

<div align="center">

### 🩺 ForenSys
### Digitalizing Forensic Case Management

Developed for Academic Purposes

© 2026 Telkom University Surabaya

</div>
