# Authentication API Documentation

## Overview
This is a Laravel-based REST API for user authentication and profile management with JWT token support.

## Base URL
```
{{url}} = http://localhost:8000/api
```

## Authentication
Most endpoints require JWT Bearer token authentication. Include the token in the request header:
```
Authorization: Bearer {{token}}
```

---

## Auth Endpoints

### 1. Sign In (Login)
Create a new session and obtain JWT token.

**Endpoint:** `POST /signIn`

**Headers:**
```json
{
  "Content-Type": "application/json"
}
```

**Request Body:**
```json
{
  "email": "kad@adasd.com",
  "password": "password123"
}
```

**Success Response:** `200 OK`
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

**Error Responses:**
- `401 Unauthorized` - Invalid credentials
- `422 Unprocessable Entity` - Validation error

---

### 2. Sign Up (Register)
Create a new user account.

**Endpoint:** `POST /signUp`

**Headers:**
```json
{
  "Content-Type": "application/json"
}
```

**Request Body:**
```json
{
  "name": "kadiri",
  "email": "kad@adasd.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Success Response:** `201 Created`
```json
{
  "message": "User created successfully",
  "user": {
    "id": 1,
    "name": "kadiri",
    "email": "kad@adasd.com"
  }
}
```

**Error Responses:**
- `422 Unprocessable Entity` - Validation error (e.g., email already exists, passwords don't match)

---

### 3. Sign Out (Logout)
Invalidate the current JWT token and end the session.

**Endpoint:** `POST /signOut`

**Headers:**
```json
{
  "Authorization": "Bearer {{token}}",
  "Content-Type": "application/json"
}
```

**Request Body:** None (empty)

**Success Response:** `200 OK`
```json
{
  "message": "User signed out successfully"
}
```

**Error Responses:**
- `401 Unauthorized` - Invalid or expired token

---

## Profile Endpoints

### 4. Show Profile
Retrieve the authenticated user's profile information.

**Endpoint:** `GET /showProfile`

**Headers:**
```json
{
  "Authorization": "Bearer {{token}}"
}
```

**Request Body:** None

**Success Response:** `200 OK`
```json
{
  "id": 2,
  "name": "kadiri",
  "email": "kad@adasd.com",
  "created_at": "2025-01-07T16:15:02.000000Z",
  "updated_at": "2025-01-07T16:15:02.000000Z"
}
```

**Error Responses:**
- `401 Unauthorized` - Invalid or expired token
- `404 Not Found` - User not found

---

### 5. Update Profile
Update the authenticated user's name and email.

**Endpoint:** `PUT /updateProfile`

**Headers:**
```json
{
  "Authorization": "Bearer {{token}}",
  "Content-Type": "application/json"
}
```

**Request Body:**
```json
{
  "name": "kadiri Mohamed",
  "email": "kadiri@gmail.com"
}
```

**Success Response:** `200 OK`
```json
{
  "message": "Profile updated successfully",
  "user": {
    "id": 2,
    "name": "kadiri Mohamed",
    "email": "kadiri@gmail.com",
    "updated_at": "2025-01-07T16:20:00.000000Z"
  }
}
```

**Error Responses:**
- `401 Unauthorized` - Invalid or expired token
- `422 Unprocessable Entity` - Validation error (e.g., email already taken)

---

### 6. Update Password
Change the authenticated user's password.

**Endpoint:** `PUT /updatePassword`

**Headers:**
```json
{
  "Authorization": "Bearer {{token}}",
  "Content-Type": "application/json"
}
```

**Request Body:**
```json
{
  "current_password": "password123",
  "password": "newPassword123",
  "password_confirmation": "newPassword123"
}
```

**Success Response:** `200 OK`
```json
{
  "message": "Password updated successfully"
}
```

**Error Responses:**
- `401 Unauthorized` - Invalid or expired token
- `422 Unprocessable Entity` - Validation error (invalid current password, passwords don't match)

---

### 7. Delete Profile
Delete the authenticated user's account permanently.

**Endpoint:** `DELETE /deleteProfile`

**Headers:**
```json
{
  "Authorization": "Bearer {{token}}",
  "Content-Type": "application/json"
}
```

**Request Body:** None

**Success Response:** `204 No Content`

**Error Responses:**
- `401 Unauthorized` - Invalid or expired token
- `404 Not Found` - User not found

---

## Common Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthorized"
}
```
**Cause:** Missing or invalid authentication token.

### 422 Unprocessable Entity
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```
**Cause:** Request validation failed.

### 500 Internal Server Error
```json
{
  "message": "Server error"
}
```
**Cause:** Unexpected server error.

---

## Environment Variables

Create a `.env` file in the project root with the following variables:

```env
APP_NAME="Auth API"
APP_ENV=local
APP_KEY=base64:YOUR_KEY
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=auth_api
DB_USERNAME=root
DB_PASSWORD=

JWT_SECRET=your_jwt_secret_key
JWT_ALGO=HS256
JWT_TTL=60
```

---

## Usage Example (cURL)

### Sign Up
```bash
curl -X POST http://localhost:8000/api/signUp \
  -H "Content-Type: application/json" \
  -d '{
    "name": "kadiri",
    "email": "kad@adasd.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Sign In
```bash
curl -X POST http://localhost:8000/api/signIn \
  -H "Content-Type: application/json" \
  -d '{
    "email": "kad@adasd.com",
    "password": "password123"
  }'
```

### Get Profile (with token)
```bash
curl -X GET http://localhost:8000/api/showProfile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## Testing with Postman

1. Import the `Auth.postman_collection.json` file into Postman
2. Create a new environment with the variable `url` = `http://localhost:8000/api`
3. Set the `token` variable after the Sign In request runs automatically (via test scripts)
4. Use the other endpoints with the saved token

---

## Database Schema

### Users Table
```sql
CREATE TABLE users (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
)
```

---

## Running the API

### Prerequisites
- PHP 8.0+
- Composer
- MySQL/MariaDB

### Setup
```bash
# Install dependencies
composer install

# Copy .env file
cp .env.example .env

# Generate app key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret

# Run migrations
php artisan migrate

# Start the server
php artisan serve
```

The API will be available at `http://localhost:8000/api`
