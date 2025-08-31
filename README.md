# 📚 Laravel E-Commerce Mini Module - API

## 👋 Overview

Building a secure REST API using **Laravel 8+, Sanctum**, and **MySQL**.  
Supports **Admin** and **User**, **Product management**, **Cart functionality**, and **Checkout**.

The project also includes seeders for initial data:

-   **Admin** user
-   **Regular User**
-   **50 sample products**

---

## Admin Login

| username | password      |
| -------- | ------------- |
| admin    | `welcome@123` |

### **Admin**

| Method | Endpoint                   | Auth Required | Description                                |
| ------ | -------------------------- | ------------- | ------------------------------------------ |
| POST   | `/api/admin/login`         | ❌            | Admin login to receive Bearer token        |
| POST   | `/api/admin/logout`        | ✅            | Logout admin and revoke tokens             |
| GET    | `/api/admin/products`      | ✅            | List all products                          |
| GET    | `/api/admin/products/{id}` | ✅            | Get details of a single product            |
| POST   | `/api/admin/products`      | ✅            | Create a new product                       |
| PUT    | `/api/admin/products/{id}` | ✅            | Update product details (name, desc, price) |
| DELETE | `/api/admin/products/{id}` | ✅            | Delete a product                           |

> All protected endpoints require **Bearer token**:  
> `Authorization: Bearer <access_token>`

---

## User Login

| email          | password      |
| -------------- | ------------- |
| user@gmail.com | `welcome@123` |

### **User**

| Method | Endpoint                             | Auth Required | Description                           |
| ------ | ------------------------------------ | ------------- | ------------------------------------- |
| POST   | `/api/user/login`                    | ❌            | User login to receive Bearer token    |
| POST   | `/api/user/logout`                   | ✅            | Logout user and revoke tokens         |
| GET    | `/api/user/products?search={search}` | ❌            | List all available products           |
| GET    | `/api/user/cart`                     | ❌            | View current cart (session or DB)     |
| POST   | `/api/user/cart/add`                 | ❌            | Add product to cart (session or DB)   |
| PUT    | `/api/user/cart/update/{productId}`  | ❌            | Update quantity of a cart item        |
| DELETE | `/api/user/cart/remove/{productId}`  | ❌            | Remove a product from cart            |
| POST   | `/api/user/checkout`                 | ✅            | Place an order (cart must have items) |

> All protected endpoints require **Bearer token**:  
> `Authorization: Bearer <access_token>`

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
This will seed the database with an **Admin user**, a **Regular user**, and **50 sample products**.
```

6. **Serve the application**

```bash
php artisan serve
By default, the app will run on http://127.0.0.1:8000.
```

## 📬 API Testing with Postman

Import the provided Postman collection (inside the source code folder) to test API endpoints.
Update the baseUrl variable in Postman **environment** to match your local server URL
