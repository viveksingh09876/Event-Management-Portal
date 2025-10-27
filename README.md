# Event-Management-Portal

A secure PHP/MySQL web app for managing events (CRUD operations).

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
    - MySQL container on localhost:3306
    - phpmyadmin container on localhost:8001

3. Database Initialization

    - MySQL container is auto-configured with:
    - Database: event_manager
    - User: event_manager
    - Password: password

    - On first startup, schema from ./db/event_manager.sql will be imported automatically.

4. Access Application
    - http://localhost:8080

5. Default Login
    - Username: admin,
    - Password: admin1234

## Features

- Admin authentication (hashed passwords).
- Admin creation through admin dashboard.
- Add/Edit/Delete event records.
- User registraion for the events.
- Responsive UI with Bootstrap.

## Tech Stack

- Backend: PHP 8 + Apache
- Frontend: HTML, CSS, Bootstrap
- Database: MySQL 8.0

## Security

- Prepared statements (SQL injection prevention).
- Password hashing.
- Input validation.
