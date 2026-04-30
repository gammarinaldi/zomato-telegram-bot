# Test Plan: Zomato Telegram Chat Bot

This plan outlines the unit test suite for the Zomato Telegram Chat Bot project. The goal is to ensure high code quality and reliability for core business logic.

## Objectives
- Achieve high code coverage for Services and Strategies.
- Ensure Middleware correctly logs requests.
- Use Mocks for external API calls (Telegram, Zomato).

## Test Suite Components

### 1. Services
- [x] **ZomatoServiceTest**: Verify search and restaurant detail retrieval by mocking HTTP responses.
- [x] **TelegramServiceTest**: Verify message, location, and video sending by mocking Telegram API.

### 2. Strategies (Telegram)
- [x] **TextStrategyTest**: Ensure it correctly handles text messages and delegates to ZomatoService if needed.
- [x] **LocationStrategyTest**: Ensure it correctly processes coordinates and finds nearby restaurants.
- [x] **VideoStrategyTest**: Verify it handles video messages or sends video responses.

### 3. Repositories
- [x] **UserRepositoryTest**: Verify user creation and retrieval by email. (Requires php-sqlite3)
- [x] **ApiLogRepositoryTest**: Verify API log creation and paginated retrieval. (Requires php-sqlite3)

### 4. Middleware
- [x] **LogApiRequestTest**: Verify that API requests are correctly logged to the database. (Requires php-sqlite3)

## Tools & Frameworks
- **Framework**: PHPUnit 12 (bundled with Laravel 13).
- **Mocking**: Mockery / Laravel Built-in Mocking (Http::fake()).

## Implementation Steps
1. Create `tests/Unit/Services` directory.
2. Implement `ZomatoServiceTest`.
3. Implement `TelegramServiceTest`.
4. Create `tests/Unit/Strategies/Telegram` directory.
5. Implement Strategy tests.
6. Implement `LogApiRequestTest`.
7. Run tests using `php artisan test`.
