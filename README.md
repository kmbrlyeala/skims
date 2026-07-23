# SKIMS SHOP — VILT Stack Authentication Module (Phase 1)

This project integrates a premium frontend design for **SKIMS SHOP** into a VILT stack architecture (Vue 3, Inertia.js, Laravel 12, Tailwind CSS, MySQL) using Laravel Jetstream as the authentication scaffolding.

---

## 🛠️ Step-by-Step Project Setup

Here is the exact sequence of commands and steps followed to set up this system from scratch:

1. **Scaffolded Laravel 12 Application**:
   Created a fresh Laravel project in `c:\xampp\htdocs\skims-shop` using Composer:
   ```bash
   composer create-project laravel/laravel skims-shop
   ```

2. **Installed Laravel Jetstream (Inertia Stack)**:
   Added Jetstream to configure the frontend bridge with Vue 3 and Inertia:
   ```bash
   composer require laravel/jetstream
   php artisan jetstream:install inertia
   ```

3. **Configured Environment and MySQL Database**:
   Modified the `.env` configuration file to configure MySQL connection parameters and database defaults (using XAMPP standard root access with no password):
   * Set `DB_CONNECTION=mysql`
   * Set `DB_DATABASE=skims_shop`
   * Set `DB_USERNAME=root`
   * Set `DB_PASSWORD=`

   Created the database using MySQL CLI:
   ```bash
   c:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS skims_shop;"
   ```

4. **Executed Migrations**:
   Ran the default Jetstream and Laravel migrations to generate session, jobs, cache, and authentication tables:
   ```bash
   php artisan migrate --force
   ```

5. **Integrated Design Tokens**:
   Updated the [tailwind.config.js](file:///c:/xampp/htdocs/skims-shop/tailwind.config.js) configuration file to register your brand design tokens as utility colors (`surface`, `surface-alt`, `accent`, `border`), custom card border radius (`rounded-card`), and card shadow rules (`shadow-card`).

6. **Customized Frontend Components**:
   Created the base authentication layout and rewrote the pages to match your visual theme. Built the assets using:
   ```bash
   npm run build
   ```

---

## 📁 Created and Modified Files

### Layouts & Shared Wrappers
* **[resources/js/Layouts/AuthLayout.vue](file:///c:/xampp/htdocs/skims-shop/resources/js/Layouts/AuthLayout.vue) [NEW]**
  A reusable wrapper layout for all login and authentication views. Implements a responsive layout, the centered cards matching the `wide-card` and standard design sizes, page gradient styling, and the brand-specific eyebrow title.

### Pages & Sub-Views
* **[resources/js/Pages/Welcome.vue](file:///c:/xampp/htdocs/skims-shop/resources/js/Pages/Welcome.vue) [OVERRIDDEN]**
  Replaces Laravel's default welcome landing view. Uses your styling, benefits listing layout, and buttons leading to standard authentication pathways.
* **[resources/js/Pages/Auth/Login.vue](file:///c:/xampp/htdocs/skims-shop/resources/js/Pages/Auth/Login.vue) [OVERRIDDEN]**
  Faithfully replicates the input fields, error layouts, validation labels, "Remember me" options, and submits via Inertia's `useForm()` composable.
* **[resources/js/Pages/Auth/Register.vue](file:///c:/xampp/htdocs/skims-shop/resources/js/Pages/Auth/Register.vue) [OVERRIDDEN]**
  Custom account creation page with fields for Full Name, Email, Password, and Password Confirmation.
* **[resources/js/Pages/Auth/ForgotPassword.vue](file:///c:/xampp/htdocs/skims-shop/resources/js/Pages/Auth/ForgotPassword.vue) [OVERRIDDEN]**
  Provides a form to submit email addresses to trigger password recovery links.
* **[resources/js/Pages/Auth/ResetPassword.vue](file:///c:/xampp/htdocs/skims-shop/resources/js/Pages/Auth/ResetPassword.vue) [OVERRIDDEN]**
  Retrieves token params via Inertia page props and implements password updating flows.
* **[resources/js/Pages/Auth/VerifyEmail.vue](file:///c:/xampp/htdocs/skims-shop/resources/js/Pages/Auth/VerifyEmail.vue) [OVERRIDDEN]**
  Allows verification links to be resent or users to log out.
* **[resources/js/Pages/Dashboard.vue](file:///c:/xampp/htdocs/skims-shop/resources/js/Pages/Dashboard.vue) [OVERRIDDEN]**
  Clean dashboard placeholder. Edit profile and logout actions were removed directly from this area as requested.
* **[resources/js/Pages/Profile/Show.vue](file:///c:/xampp/htdocs/skims-shop/resources/js/Pages/Profile/Show.vue) [MODIFIED]**
  Cleaned user profile view. The **Two-Factor Authentication (2FA)** and **Browser Sessions** management tools have been removed.

### Styling & Configs
* **[tailwind.config.js](file:///c:/xampp/htdocs/skims-shop/tailwind.config.js) [MODIFIED]**
  Extended the CSS theme to map custom design values:
  * Color tokens (`surface: #ffffff`, `surface-alt: #f8fafc`, `muted: #6b7280`, `accent: #334155`, `border: #d1d5db`).
  * Card dimensions (`rounded-card: 20px`, `shadow-card: 0 10px 30px rgba(15, 23, 42, 0.06)`).

---

## 🗄️ Database Tables Explanation

The database consists of tables optimized for high-performance session tracking, security, and queue execution:

| Table Name | Description | Key Columns |
| :--- | :--- | :--- |
| **`users`** | Core identities database. Stores names, emails, email verification timestamps, password hashes, 2FA values, profile image paths, and tracking dates. | `id`, `name`, `email`, `password`, `two_factor_secret`, `profile_photo_path`, `timestamps` |
| **`password_reset_tokens`** | Secure token lookup storage utilized during forgot-password mail recovery verification. | `email` (Primary Key), `token`, `created_at` |
| **`sessions`** | Tracks logged-in users, active locations, dynamic browser environments, IP locations, and activities. | `id` (Primary Key), `user_id` (Index), `ip_address`, `user_agent`, `payload`, `last_activity` |
| **`cache` & `cache_locks`** | Used by Laravel cache systems to improve page loading and manage execution resource locks. | `key`, `value`, `expiration`, `owner` |
| **`jobs` & `job_batches`** | Manages queue execution queues for tasks such as email routing or parallel queue processing. | `id`, `queue`, `payload`, `attempts`, `available_at` |
| **`failed_jobs`** | Log of failed jobs with connection and trace data for troubleshooting. | `id`, `uuid`, `connection`, `payload`, `exception`, `failed_at` |
| **`personal_access_tokens`** | Managed by Sanctum. Holds API authentication and authorization keys if API routes are used. | `id`, `tokenable_id`, `tokenable_type`, `token`, `abilities`, `expires_at` |

---

## 🚀 How to Continue Developing This Project

### 1. Adding New Routes & Views
All routes are defined in **[routes/web.php](file:///c:/xampp/htdocs/skims-shop/routes/web.php)**. 
To serve a new page:
1. Create a page component in `resources/js/Pages/` (e.g., `Shop.vue`).
2. Add a route definition inside the authenticated route group in `routes/web.php`:
   ```php
   Route::get('/shop', function () {
       return Inertia::render('Shop', [
           'products' => Product::all(), // Data passed directly as reactive props to Vue!
       ]);
   })->name('shop');
   ```

### 2. Modifying Styles and Layout
* **Global Styles**: Global variables and custom directives can be found in `resources/css/app.css`.
* **Theme Configuration**: To register new colors, spacing variables, or styling utilities, modify `tailwind.config.js`.
* **Vite Hot Reload**: Keep `npm run dev` running in your terminal to see your design changes instantly update in the browser.

### 3. Restoring Profile Sections (2FA & Browser Sessions)
To bring back the 2FA and Browser Session options in the future:
1. Open **[resources/js/Pages/Profile/Show.vue](file:///c:/xampp/htdocs/skims-shop/resources/js/Pages/Profile/Show.vue)**.
2. Re-import the partial files:
   ```javascript
   import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
   import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
   ```
3. Re-declare dynamic prop definitions inside `defineProps`:
   ```javascript
   defineProps({
       confirmsTwoFactorAuthentication: Boolean,
       sessions: Array,
   });
   ```
4. Restore components into the template block:
   ```html
   <TwoFactorAuthenticationForm :requires-confirmation="confirmsTwoFactorAuthentication" />
   <LogoutOtherBrowserSessionsForm :sessions="sessions" />
   ```

### 4. Customizing Authentication Logic
* Input validation (e.g. required password length, password rules) and database storage are handled in **[app/Actions/Fortify/](file:///c:/xampp/htdocs/skims-shop/app/Actions/Fortify/)**:
  * Customize registration input validation and storage rules in [CreateNewUser.php](file:///c:/xampp/htdocs/skims-shop/app/Actions/Fortify/CreateNewUser.php).
  * Change how user profiles are updated in [UpdateUserProfileInformation.php](file:///c:/xampp/htdocs/skims-shop/app/Actions/Fortify/UpdateUserProfileInformation.php).
