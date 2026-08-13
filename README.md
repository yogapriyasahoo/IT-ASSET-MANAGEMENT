# IT Asset Management System

A web-based IT Asset Management System developed to efficiently manage, track, and monitor organizational IT assets. The system provides a centralized platform for maintaining asset records, managing departments, tracking employee assignments, recording maintenance activities, and generating reports.

The project was developed using PHP and MySQL with Bootstrap for the user interface and XAMPP as the local development environment.

---

## 📌 Project Overview

The **IT Asset Management System** was developed during my **internship at National Aluminium Company Limited (NALCO), Angul**. The project was developed to provide a centralized and efficient solution for managing organizational IT assets and to simplify the tracking of asset allocation, employee assignments, maintenance activities, and asset status.

The system allows administrators to:

* Add, update, and manage IT assets
* Assign and track assets allocated to employees
* Organize assets based on departments
* Monitor asset status and lifecycle
* Record and manage maintenance activities
* Track maintenance progress and synchronize asset status
* Search, filter, and paginate asset and maintenance records
* Monitor asset and maintenance statistics through a centralized dashboard
* View graphical reports and analyze asset distribution

The project provided practical hands-on experience in **PHP, MySQL, Bootstrap, database design, database record management, session management, asset tracking, maintenance management, and data visualization** in a real-world organizational environment.



---

## 🎯 Objectives

The main objectives of the system are:

- To maintain a centralized database of organizational IT assets.
- To simplify asset allocation and tracking.
- To monitor the current status of IT assets.
- To manage departments and their associated assets.
- To record and track maintenance activities.
- To reduce manual record-keeping.
- To improve asset accountability and transparency.
- To provide useful reports and statistics for management.

---

## 🚀 Key Features

### 📊 Dashboard

The dashboard provides a quick overview of the organization's IT assets and maintenance activities.

It displays statistics such as:

- Total Assets
- Active Assets
- Assets Under Repair
- Retired Assets
- Assigned Assets
- Unassigned Assets
- Maintenance Statistics

The dashboard also provides quick access to important sections of the system.

---

### 💻 Asset Management

The Asset Management module is the core component of the system.

Administrators can:

- Add new assets
- Edit existing assets
- Delete assets
- Search assets
- Filter assets by status
- View asset details
- Track employee assignments
- Track assignment dates
- Navigate through assets using pagination

Each asset contains information such as:

- Asset ID
- Asset Name
- Asset Type
- Brand
- Department
- Status
- Assigned Employee
- Employee ID
- Assigned Date

---

### 👨‍💼 Asset Assignment & Tracking

The system allows administrators to assign IT assets to employees and maintain assignment information.

The system records:

- Employee Name
- Employee ID
- Assigned Date

This helps administrators identify who is currently responsible for a particular asset.

The assignment information is also automatically handled when an asset is retired.

---

### 🏢 Department Management

The Department Management module allows administrators to manage organizational departments.

Features include:

- Add Department
- Edit Department
- Delete Department
- View Departments
- Department-wise asset organization
- Pagination

This allows assets to be categorized according to their respective departments.

---

### 🔧 Maintenance Management

The Maintenance Management module allows administrators to record and monitor issues related to IT assets.

Administrators can:

- Add maintenance logs
- Edit maintenance logs
- Delete maintenance logs
- Search maintenance records
- View maintenance history
- Navigate through logs using pagination
- Track maintenance status
- View the related asset

Maintenance statuses include:

- **Pending**
- **In Progress**
- **Completed**

---

### 🔄 Automatic Asset Status Synchronization

One of the major features of the system is the integration between Asset Management and Maintenance Management.

When a maintenance activity is created or updated, the asset status can be synchronized accordingly.

For example:

**Pending → Under Repair**

**In Progress → Under Repair**

**Completed → Active**

This ensures that the current condition of an asset is reflected correctly in the Asset Management module.

---

### 📝 Log Issue

Administrators can initiate a maintenance record directly from the Asset Management section using the **Log Issue** functionality.

This makes it easier to report an issue without manually searching for the asset in the Maintenance module.

The system also prevents unnecessary maintenance requests for assets that are already under repair or retired.

---

### 🔍 Search & Pagination

Search functionality is implemented across major modules to help users quickly locate records.

Pagination is implemented for:

- Assets
- Departments
- Maintenance Logs

Pagination improves usability by displaying a manageable number of records on each page.

---

### 📈 Reports & Analytics

The Reports module provides statistical and graphical information about the organization's IT assets.

It includes:

- Asset status statistics
- Department-wise asset distribution
- Assigned and unassigned asset information
- Graphical representations
- Summary information

These reports help administrators analyze asset distribution and utilization.

---

### 🔐 Authentication & Session Management

The system includes session-based authentication.

Users must log in before accessing protected sections of the application.

Features include:

- Login
- Session Management
- Logout
- Protected Pages

---

## 🛠️ Technology Stack

### Frontend

- HTML5
- CSS3
- Bootstrap 5
- JavaScript
- Bootstrap Icons

### Backend

- PHP

### Database

- MySQL

### Development Environment

- XAMPP
- Apache
- phpMyAdmin

### Code Editor

- Notepad++ / Visual Studio Code

---

## 🗄️ Database Structure

The system uses MySQL for storing and managing application data.

### Assets Table

Stores information about IT assets.

Important fields include:

- `asset_id`
- `asset_name`
- `asset_type`
- `brand`
- `department`
- `status`
- `assigned_to`
- `employee_id`
- `assigned_date`

---

### Departments Table

Stores organizational department information.

Important fields include:

- `department_id`
- `department_name`

---

### Maintenance Logs Table

Stores maintenance-related information for assets.

Important fields include:

- `log_id`
- `asset_id`
- `issue`
- `reported_date`
- `maintenance_status`
- `technician`

The `asset_id` connects maintenance records with their corresponding assets.

---

## 🔗 System Workflow

The general workflow of the system is:

```text
Login
   ↓
Dashboard
   ↓
Asset Management
   ├── Add Asset
   ├── Edit Asset
   ├── Assign Asset
   └── Log Issue
           ↓
   Maintenance Management
           ↓
   Pending / In Progress / Completed
           ↓
   Asset Status Updated
