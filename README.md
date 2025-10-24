# Event-Management-Portal

A secure PHP/MySQL web app for managing student records (CRUD operations).

---

## Setup with Docker

1. Clone Repository:
    - bash
    - git clone https://github.com/viveksingh09876/Event-Management-Portal.git
    - cd Event-Management-Portal

2. Build & Run with Docker Compose:
    - bash
    - docker-compose up -d --build

    This will start:
    - PHP + Apache container → http://localhost:8080
    - phpmyadmin → http://localhost:8001

3. Database Initialization

    - MySQL container is auto-configured with:
    - Database: event_manager
    - User: event_manager
    - Password: password

    - On first startup, schema from ./db/event_manager.sql will be imported automatically.

4. Access Application
    - http://localhost:8080

5. Default Admin Login
    - Username: admin,
    - Password: admin123

## Features

1. User/Public Functionality
    - A public page to display all available events along with details.
    - A form for users to register for an event.
    - Responsive UI with Bootstrap.
   
2. Admin Functionality
    - Admin authentication (hashed passwords).
    - Add/Edit/Delete Event Details.
    - Table to display all registrations details
    - Responsive UI with Bootstrap.

## Tech Stack

- Backend: PHP 8 + Apache
- Frontend: HTML, CSS, Bootstrap)
- Database: MySQL 8.0

## Security

- Prepared statements (SQL injection prevention).
- Password hashing.
- Input validation.
