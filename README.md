# 📚 Laravel E-Commerce Mini Module - API

## 👋 Overview

Building a secure REST API using **Laravel 8+, Sanctum**, and **MySQL**.  
Supports **Admin** and **User** roles, product management, cart functionality, and checkout.

---

## 🧱 API Endpoints

### **Admin**

| Method | Endpoint               | Auth Required | Description                                |
| ------ | ---------------------- | ------------- | ------------------------------------------ |
| POST   | `/admin/login`         | ❌            | Admin login to receive Bearer token        |
| POST   | `/admin/logout`        | ✅            | Logout admin and revoke tokens             |
| GET    | `/admin/products`      | ✅            | List all products                          |
| GET    | `/admin/products/{id}` | ✅            | Get details of a single product            |
| POST   | `/admin/products`      | ✅            | Create a new product                       |
| PUT    | `/admin/products/{id}` | ✅            | Update product details (name, desc, price) |
| DELETE | `/admin/products/{id}` | ✅            | Delete a product                           |

> All protected endpoints require **Bearer token**:  
> `Authorization: Bearer <access_token>`

---

### **User**

| Method | Endpoint                        | Auth Required | Description                           |
| ------ | ------------------------------- | ------------- | ------------------------------------- |
| POST   | `/user/login`                   | ❌            | User login to receive Bearer token    |
| POST   | `/user/logout`                  | ✅            | Logout user and revoke tokens         |
| GET    | `/user/products`                | ❌            | List all available products           |
| GET    | `/user/cart`                    | ❌            | View current cart (session or DB)     |
| POST   | `/user/cart/add`                | ❌            | Add product to cart (session or DB)   |
| PUT    | `/user/cart/update/{productId}` | ❌            | Update quantity of a cart item        |
| DELETE | `/user/cart/remove/{productId}` | ❌            | Remove a product from cart            |
| POST   | `/user/checkout`                | ✅            | Place an order (cart must have items) |

> **Note:**
>
> -   Cart endpoints work for both **guests** (session-based) and **logged-in users** (DB-based).
> -   Checkout is only allowed for **authenticated users**.

---

## 🚀 Project Setup Instructions

Follow these steps to get the Laravel E-Commerce Mini Module running locally:

1. **Clone the repository**

```bash
git clone <your-repo-url>
cd <repo-folder>
```

2. **Install PHP dependencies**

```bash
composer install
```

3. **Setup environment file**

```bash
cp .env.example .env
Update .env with your MySQL database credentials and other necessary settings.
```

4. **Run migrations**

```bash
php artisan migrate
```

5. **Seed the database**

```bash
php artisan db:seed
```

6. **Serve the application**

```bash
php artisan serve
By default, the app will run on http://127.0.0.1:8000.
```

## 📬 API Testing with Postman

Import the provided Postman collection (inside the source code folder) to test API endpoints.
Update the baseUrl variable in Postman environment to match your local server URL
