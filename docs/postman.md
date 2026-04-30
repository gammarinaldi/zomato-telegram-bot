# Postman Documentation

## Environment Variables
- `base_url`: `http://localhost:8000/api`
- `jwt_token`: (Set after login/verify)
- `temp_token`: (Set after login if 2FA is required)

## Endpoints

### 1. Register
- **Method**: `POST`
- **URL**: `{{base_url}}/auth/register`
- **Body**:
  ```json
  {
    "name": "User Name",
    "email": "user@example.com",
    "password": "password",
    "password_confirmation": "password"
  }
  ```

### 2. Login
- **Method**: `POST`
- **URL**: `{{base_url}}/auth/login`
- **Body**:
  ```json
  {
    "email": "user@example.com",
    "password": "password"
  }
  ```

### 3. Verify 2FA
- **Method**: `POST`
- **URL**: `{{base_url}}/auth/2fa/verify`
- **Body**:
  ```json
  {
    "token": "{{temp_token}}",
    "one_time_password": "123456"
  }
  ```

### 4. Bot Webhook (Simulator)
- **Method**: `POST`
- **URL**: `{{base_url}}/bot/webhook`
- **Body**:
  ```json
  {
    "update_id": 100,
    "message": {
      "chat": { "id": 123 },
      "text": "/start"
    }
  }
  ```

### 5. View Logs
- **Method**: `GET`
- **URL**: `http://localhost:8000/logs`
- **Description**: Web interface to view captured metadata.
