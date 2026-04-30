# 🤖 Zomato Telegram Bot API Documentation

Welcome to the official API documentation for the **Zomato Telegram Bot**. This API powers a sophisticated culinary bot that integrates real-time food discovery with secure user authentication.

## 🚀 Overview

This project is built using **Laravel** and follows modern design patterns (Repository & Strategy) to ensure scalability and reliability. It features a robust security layer including JWT authentication and Two-Factor Authentication (2FA).

### Key Features:
*   **Authentication**: Secure Register/Login system with JWT.
*   **Security**: Integrated 2FA (Two-Factor Authentication) for user verification.
*   **Zomato Integration**: Real-time culinary search and discovery (internal logic).
*   **Bot Simulator**: Endpoint to simulate Telegram Webhooks for testing.
*   **Automated Tests**: Built-in Postman tests to validate responses and automate token management.

---

## 🔐 Getting Started

To start testing the API, follow these steps:

1.  **Environment Setup**: Create a Postman environment and add `base_url` (default: `http://localhost:8000/api`).
2.  **Register**: Create a new account using the `Auth > Register` endpoint.
3.  **Login**: Authenticate via `Auth > Login`. 
    *   If 2FA is disabled, `jwt_token` will be set automatically.
    *   If 2FA is enabled, a `temp_token` will be saved for the next step.
4.  **Verify 2FA**: If required, use the code from your 2FA provider at the `Auth > Verify 2FA` endpoint. The final `jwt_token` will be set upon success.
5.  **Access Protected Routes**: Use the `Me (Profile)` endpoint to verify your session.

---

## 🛠 Variables

| Variable | Description | Initial Value |
| :--- | :--- | :--- |
| `base_url` | The root URL of your API | `http://localhost:8000/api` |
| `jwt_token` | The bearer token for authorized requests | *(Auto-set after login/verify)* |
| `temp_token` | Temporary token used for 2FA verification | *(Auto-set after login)* |

---

## 🧪 Automated Testing

This collection includes **Pre-request Scripts** and **Tests** to streamline your workflow:
*   ✅ **Auto-Token Management**: Login and 2FA Verify scripts automatically update your environment variables.
*   ✅ **Status Code Validation**: Every request verifies if the server responds with the expected status code.
*   ✅ **JSON Schema Validation**: Ensures the response data structure matches the API contract.

---

> [!TIP]
> **Developer Note**: When publishing to a Public Workspace, ensure your `Current Value` for variables (containing actual tokens) is NOT shared. Only `Initial Value` is visible to the public.
