# Specification: Zomato Telegram Chat Bot

## 1. Overview
A Telegram Bot that allows users to search for restaurants, view menus, addresses, and reviews using the Zomato API. Built with Laravel, PostgreSQL, and JWT Authentication.

## 2. Technology Stack
- **Framework**: Laravel 13.7
- **Database**: PostgreSQL
- **Authentication**: JWT (using `tymon/jwt-auth`) with 2FA.
- **External APIs**: 
    - Telegram Bot API
    - Zomato API (Vivek-Raj/zomato-api/1.0.0)
- **Documentation**: Postman (published environment)
- **CI/CD**: GitHub Actions
- **Testing**: PHPUnit / Pest for automated testing

## 3. Core Features
### A. Authentication & Security
- User registration and login via REST API.
- JWT-based authentication for secure access.
- Two-Factor Authentication (2FA) implementation.

### B. Telegram Bot Integration
- Support for multiple message types:
    - **Text**: Search by name or keyword.
    - **Location**: Find nearby restaurants.
    - **Contact**: Share contact for specific services (if applicable).
    - **Video/Media**: Display restaurant media if available.
- Interactive menus using Telegram Keyboards (Inline & Reply).

### C. Zomato API Integration
- Search restaurants by name, location, and cuisine.
- Fetch restaurant details: address, menu, and reviews.

### D. Metadata Recording (Monitoring)
- Middleware to capture:
    - Request Body
    - IP Address
    - Request Headers
    - Timestamp
    - User ID (if authenticated)
- Admin dashboard/page to view these logs with rich aesthetics.

### E. Design Patterns
- **Repository Pattern**: Abstract data access.
- **Service Pattern**: Business logic encapsulation.
- **Strategy Pattern**: Handling different Telegram message types.

## 4. Database Schema (PostgreSQL)
- `users`: Standard Laravel user table + 2FA fields.
- `api_logs`: id, user_id, ip_address, method, url, headers, body, response_status, created_at.

## 5. API Endpoints
### Auth
- `POST /api/register`
- `POST /api/login`
- `POST /api/2fa/verify`

### Bot
- `POST /api/bot/webhook`: Telegram Webhook handler.
- `GET /api/logs`: View recorded metadata.

## 6. CI/CD & Testing
- Automated tests for all API endpoints.
- GitHub Actions for linting and testing.
- Postman Collection with environment variables.
