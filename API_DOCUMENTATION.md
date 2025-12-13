# API Endpoints Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication
Semua endpoint yang memerlukan autentikasi harus menyertakan header:
```
Authorization: Bearer {your_token}
```

---

## 🔐 Authentication Endpoints

### Login Admin
```http
POST /api/admin/login
Content-Type: application/json

{
  "email": "admin@selatdesa.com",
  "password": "your_password"
}
```

**Response:**
```json
{
  "message": "Login berhasil",
  "token": "1|xxxxxxxxxxxxx",
  "admin": {
    "id": 1,
    "name": "Admin Utama",
    "email": "admin@selatdesa.com",
    "role": "admin",
    "last_login": "2025-12-12 13:53:00"
  }
}
```

---

## 👥 User Management Endpoints

### Get All Users
```http
GET /api/users
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "081234567890",
      "created_at": "2024-01-15T00:00:00.000000Z"
    }
  ]
}
```

### Create User
```http
POST /api/users
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "081234567890",
  "password": "password123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "User berhasil dibuat",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "081234567890",
    "role": "user"
  }
}
```

### Get Single User
```http
GET /api/users/{id}
Authorization: Bearer {token}
```

### Update User
```http
PUT /api/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "John Updated",
  "email": "john.updated@example.com",
  "phone": "081234567891"
}
```

**Note:** Password is optional. Only include if you want to update it.

### Delete User
```http
DELETE /api/users/{id}
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "User berhasil dihapus"
}
```

---

## 👨‍💼 Admin Management Endpoints

### Get All Admins
```http
GET /api/admins
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Admin Utama",
      "email": "admin@selatdesa.com",
      "last_login": "2025-12-12 13:53:00",
      "created_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
}
```

### Create Admin
```http
POST /api/admins
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "New Admin",
  "email": "newadmin@selatdesa.com",
  "password": "password123"
}
```

### Get Single Admin
```http
GET /api/admins/{id}
Authorization: Bearer {token}
```

### Update Admin
```http
PUT /api/admins/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Updated Admin",
  "email": "updated@selatdesa.com"
}
```

### Delete Admin
```http
DELETE /api/admins/{id}
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "message": "Admin berhasil dihapus"
}
```

**Note:** Sistem akan mencegah penghapusan admin terakhir.

---

## 📦 Product Management Endpoints

### Delete Product
```http
DELETE /api/dashboard/products/{id}
Authorization: Bearer {token}
```

**Response:**
```json
{
  "message": "Product deleted"
}
```

---

## 📰 Article Management Endpoints

### Delete Article
```http
DELETE /api/dashboard/articles/{id}
Authorization: Bearer {token}
```

**Response:**
```json
{
  "message": "Article deleted successfully!"
}
```

---

## Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 404 Not Found
```json
{
  "message": "No query results for model [App\\Models\\User] {id}"
}
```

### 422 Validation Error
```json
{
  "message": "The email has already been taken.",
  "errors": {
    "email": [
      "The email has already been taken."
    ]
  }
}
```

---

## Testing with cURL

### 1. Login
```bash
curl -X POST http://localhost:8000/api/admin/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@selatdesa.com","password":"your_password"}'
```

### 2. Get Users (replace {TOKEN} with actual token)
```bash
curl -X GET http://localhost:8000/api/users \
  -H "Authorization: Bearer {TOKEN}"
```

### 3. Create User
```bash
curl -X POST http://localhost:8000/api/users \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "phone": "081234567890",
    "password": "password123"
  }'
```

### 4. Update User
```bash
curl -X PUT http://localhost:8000/api/users/1 \
  -H "Authorization: Bearer {TOKEN}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Updated Name",
    "email": "updated@example.com",
    "phone": "081234567891"
  }'
```

### 5. Delete User
```bash
curl -X DELETE http://localhost:8000/api/users/1 \
  -H "Authorization: Bearer {TOKEN}"
```
