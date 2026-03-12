
# 🎟️ Event Ticket Booking System (PHP + MySQL)

A comprehensive web-based event management and ticketing platform built with PHP, MySQL, HTML, CSS, and JavaScript. 
The system supports role-based access for administrators and customers with complete event lifecycle management, 
secure booking and payment processing.

A full-stack **Event Ticket Booking Web Application** built using **PHP, MySQL, HTML, CSS, and JavaScript**.  
The system allows customers to browse events and book tickets while admins manage events, users, and bookings.

This project demonstrates **secure authentication, role-based access control, database transactions, and secure coding practices**.

---

# 🚀 Features
- ✅ View all bookings in one place
- ✅ Statistics (total, confirmed, cancelled, revenue)
- ✅ Cancel individual bookings
- ✅ Automatically restore tickets to inventory
- ✅ Mark tickets as available again
- ✅ Track cancellation date/time
- ✅ Safe with database transactions

## 👥 User Roles
| Role | Description |
|-----|-------------|
| Admin | Manage events, users, and bookings |
| Customer | Browse events, book tickets, and manage orders |

---

# 📊 System Overview

| Feature Category | Count | Details |
|-----------------|------|--------|
| User Roles | 2 | Admin, Customer |
| Main Pages | 25+ | Registration, Login, Dashboards, Event Pages |
| Database Tables | 5 | Users, Events, Tickets, Bookings, Booking_Tickets |
| Admin Features | 10+ | Event CRUD, User Management, Booking Monitoring |
| Customer Features | 8+ | Browse Events, Book Tickets, Payment |
| Security Features | 8+ | Authentication, Authorization, Validation |

---

# 🛠 Technology Stack

## Backend
- PHP 7.4+
- MySQL / MariaDB
- Session Management

## Frontend
- HTML5
- CSS3 (Flexbox, Grid, Gradients)
- JavaScript (Vanilla JS)
- Responsive Design

## Tools
- XAMPP / WAMP (Local Development)
- phpMyAdmin (Database Management)
- Git & GitHub
- Client & Server Side Form Validation

---

# 🗄️ Database Structure

## 1️⃣ Users
Stores user authentication and role information.

Fields include:
- id
- name
- email
- password_hash
- role (admin/customer)
- account_status
- reset_token
- created_at

---

## 2️⃣ Events
Stores event information.

Fields include:
- id
- event_name
- category
- event_date
- description
- event_image
- created_by

---

## 3️⃣ Tickets
Defines ticket types for events.

Fields include:
- id
- event_id
- ticket_type
- price
- quantity
- status

---

## 4️⃣ Bookings
Stores booking transactions.

Fields include:
- id
- user_id
- total_amount
- payment_method
- booking_status
- created_at

---

## 5️⃣ Booking_Tickets
Relationship between bookings and tickets.

Fields include:
- id
- booking_id
- ticket_id
- quantity
- price_snapshot

---

# 🔒 Security Features

This project follows **secure development practices**.

✔ Password Hashing (`bcrypt`)  
✔ SQL Injection Prevention (`Prepared Statements`)  
✔ XSS Protection (`Input Sanitization`)  
✔ Secure Session Management  
✔ CSRF Protection Considerations  
✔ Role-Based Access Control (RBAC)  
✔ Secure Token Generation  
✔ Database Transactions  
✔ File Upload Validation

---

# 📂 Project Structure
<img width="468" height="822" alt="image" src="https://github.com/user-attachments/assets/ef862748-bb07-4e23-89b8-be39c6171dac" />
