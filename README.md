# Zomato Telegram Chat Bot

A robust RESTful API and Telegram Bot integration built with Laravel 13, providing restaurant search capabilities powered by the Zomato API.

## 🚀 Features

- **Advanced Authentication**: 
    - JWT (JSON Web Token) implementation for secure API access.
    - Two-Factor Authentication (2FA) with Google Authenticator support (QR Code setup included).
- **Telegram Bot Integration**:
    - **Strategy Pattern** implementation for polymorphic message handling.
    - Supports multiple message types: **Text, Location, Video, and Contact**.
    - Search for restaurants by keyword or current location.
- **API Monitoring & Logging**:
    - Custom middleware to capture comprehensive request metadata (IP, Headers, Body, Method).
    - Web dashboard to view and monitor API activity.
- **Database**: 
    - Optimized for **PostgreSQL**.
- **Design Patterns**:
    - **Strategy Pattern** for Telegram message types.
    - **Service Pattern** for API integrations (Zomato & Telegram).
    - **Repository Pattern** for clean data access abstraction.
- **CI/CD**: 
    - Automated testing and deployment configured via GitHub Actions.

## 🛠 Tech Stack

- **Framework**: Laravel 13
- **Database**: PostgreSQL
- **Auth**: JWT (tymon/jwt-auth fork), Google 2FA
- **Testing**: PHPUnit
- **API Documentation**: Postman

## 📥 Installation

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd zomato-chat-bot
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Configure Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan jwt:secret
   ```
   *Note: Ensure your `DB_CONNECTION` is set to `pgsql` and provide your Zomato and Telegram Bot tokens.*

4. **Run Migrations**:
   ```bash
   php artisan migrate
   ```

5. **Start the server**:
   ```bash
   php artisan serve
   ```

## 🧪 Testing

The project includes a robust unit test suite covering services, strategies, repositories, and middleware.

### Prerequisites
To run database-related tests locally, ensure the SQLite extension is installed:
```bash
sudo apt-get install php8.3-sqlite3
```

### Running Tests
Run the entire suite:
```bash
./vendor/bin/phpunit
```

Run specific test layers:
| Layer | Command |
| :--- | :--- |
| **All Unit Tests** | `./vendor/bin/phpunit tests/Unit` |
| **Services** | `./vendor/bin/phpunit tests/Unit/Services` |
| **Strategies** | `./vendor/bin/phpunit tests/Unit/Strategies` |
| **Repositories** | `./vendor/bin/phpunit tests/Unit/Repositories` |
| **Middleware** | `./vendor/bin/phpunit tests/Unit/Middleware` |
| **Feature Tests** | `./vendor/bin/phpunit tests/Feature` |

## 📖 Documentation

- **API Documentation**: Detailed Postman collection documentation can be found in [docs/postman.md](docs/postman.md).
- **Verification Report**: Full project compliance report is available in [docs/superpowers/verification_report.md](docs/superpowers/verification_report.md).

## 📄 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
