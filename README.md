# File Manager App

مشروع **Laravel API + Breeze + Vue.js + Vite** لإدارة الملفات.

## 💻 تشغيل المشروع محليًا

### 1️⃣ استنساخ المستودع
```sh
git clone <repo-url>
cd <project-folder>
```
استبدل `<repo-url>` برابط المستودع و `<project-folder>` باسم مجلد المشروع.

---

## ⚙️ إعداد **Backend** (Laravel)
### 📥 تثبيت الحزم
```sh
cd file_manger_backend
composer install
```

### 🛠 إعداد البيئة
```sh
cp .env.example .env
php artisan key:generate
```
قم بتحديث بيانات قاعدة البيانات في `.env` مثل:
```
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 📦 تشغيل المهاجرات
```sh
php artisan migrate
```

### 🚀 تشغيل السيرفر
```sh
php artisan serve
```

---

## 🎨 إعداد **Frontend** (Vue.js + Vite)
### 📥 تثبيت الحزم
```sh
cd ../file_manger_frontend
npm install
```

### ⚙️ إعداد البيئة
قم بتحديث `VITE_API_BASE_URL` في `.env`:
```
VITE_API_BASE_URL=http://127.0.0.1:8000
```

### 🚀 تشغيل Vite
```sh
npm run dev
```
افتح المتصفح على `http://localhost:5173/` لتشغيل التطبيق.

---

## 🛠 متطلبات التشغيل
- **PHP 8.x** + **Composer**
- **Laravel 10.x** أو أحدث
- **MySQL**
- **Node.js 16+** + **npm**
- **Vite.js**

---

إذا واجهت أي مشاكل، لا تتردد في فتح **Issue** في المستودع! 🚀
