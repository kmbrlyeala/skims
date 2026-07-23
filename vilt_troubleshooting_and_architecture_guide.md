# VILT Stack: Blank Screen Fix, MVC Best Practices & Role-Based Routing

---

## Part 1 — Troubleshooting Guide: Blank Screen After Login

> [!CAUTION]
> **Bug found in your codebase.** Your [Dashboard.vue](file:///c:/Users/pao/Downloads/skims-shop/skims-shop/resources/js/Pages/Dashboard.vue#L5) uses `$page` without importing it, which crashes Vue and produces a blank screen.

### 🔴 Issue #1 (YOUR BUG) — `$page` Used Incorrectly in `<script setup>`

**File:** [Dashboard.vue](file:///c:/Users/pao/Downloads/skims-shop/skims-shop/resources/js/Pages/Dashboard.vue)

```js
// ❌ Line 5 — This crashes the component. $page is a template-only global.
const userRole = $page.props.auth.user.role;
```

In Vue 3 `<script setup>`, `$page` is **not** available — it's a template-only global property injected by the Inertia plugin. Accessing it in `<script setup>` throws `ReferenceError: $page is not defined`, which prevents the entire Vue app from mounting, producing a **blank white screen**.

**Fix:**
```js
// ✅ Use the usePage() composable from Inertia
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const userRole = computed(() => page.props.auth?.user?.role);
```

> [!IMPORTANT]
> This also assumes `auth.user` is being shared via Inertia's shared data. If it's not, `auth` will be `undefined`, causing a second crash. See Issue #2 below.

---

### 🔴 Issue #2 — `auth.user` Not Shared via Inertia Middleware

**File:** [HandleInertiaRequests.php](file:///c:/Users/pao/Downloads/skims-shop/skims-shop/app/Http/Middleware/HandleInertiaRequests.php#L36-L42)

Your `share()` method is currently empty:

```php
public function share(Request $request): array
{
    return [
        ...parent::share($request),
        // ← Nothing else is shared!
    ];
}
```

But your [Dashboard.vue](file:///c:/Users/pao/Downloads/skims-shop/skims-shop/resources/js/Pages/Dashboard.vue#L27) template references `$page.props.auth.user.name` and `$page.props.auth.user.role`. If Jetstream's parent `share()` doesn't already provide `auth.user` (it may depending on version), then `auth` is `undefined` and the page crashes.

**Fix — explicitly share auth data:**
```php
public function share(Request $request): array
{
    return [
        ...parent::share($request),
        'auth' => [
            'user' => $request->user()
                ? $request->user()->only('id', 'name', 'email', 'role', 'profile_photo_url')
                : null,
        ],
    ];
}
```

> [!TIP]
> Use `->only(...)` instead of returning the full model. This prevents leaking sensitive data like `password`, `remember_token`, etc. to the frontend.

---

### 🟡 Issue #3 — Fortify Redirects to `/dashboard` for ALL Roles

**File:** [fortify.php](file:///c:/Users/pao/Downloads/skims-shop/skims-shop/config/fortify.php#L76)

```php
'home' => '/dashboard',
```

After login, Fortify sends **every user** (admin, supplier, customer) to `/dashboard`. Your [Dashboard.vue](file:///c:/Users/pao/Downloads/skims-shop/skims-shop/resources/js/Pages/Dashboard.vue) then must handle all roles. While this isn't directly causing the blank screen (Issues #1 and #2 are), it creates a poor UX where e.g. a supplier has to click through to their real dashboard.

**Fix — use a callable for role-based redirect (see Part 3 below).**

---

### 🟡 General Blank Screen Checklist

If the above fixes don't resolve your issue, check these in order:

| # | Check | File(s) to Inspect | What to Look For |
|---|-------|-------------------|-----------------|
| 1 | **Browser Console** | DevTools → Console | `ReferenceError`, `Cannot read properties of undefined`, Vue warnings |
| 2 | **Network Tab** | DevTools → Network | Is the POST to `/login` returning `200`? Is the redirect a `302` or `409`? |
| 3 | **Vite dev server running?** | Terminal | `npm run dev` must be running. Without it, no JS/CSS is served |
| 4 | **`@vite` directive in Blade** | [app.blade.php](file:///c:/Users/pao/Downloads/skims-shop/skims-shop/resources/views/app.blade.php#L15) | Must have `@vite(...)`, `@routes`, `@inertia`, `@inertiaHead` |
| 5 | **`@inertia` directive** | [app.blade.php](file:///c:/Users/pao/Downloads/skims-shop/skims-shop/resources/views/app.blade.php#L19) | Must be present in `<body>` — this is the mount point |
| 6 | **HandleInertiaRequests middleware registered** | `bootstrap/app.php` or `app/Http/Kernel.php` | Must be in the `web` middleware group |
| 7 | **Vue page component exists** | [Pages/](file:///c:/Users/pao/Downloads/skims-shop/skims-shop/resources/js/Pages) | The component name in `Inertia::render('X')` must match `Pages/X.vue` exactly (case-sensitive!) |
| 8 | **`resolvePageComponent` glob** | [app.js](file:///c:/Users/pao/Downloads/skims-shop/skims-shop/resources/js/app.js#L13) | `import.meta.glob('./Pages/**/*.vue')` — the `**` ensures subdirectories work |
| 9 | **Layout component crash** | [AppLayout.vue](file:///c:/Users/pao/Downloads/skims-shop/skims-shop/resources/js/Layouts/AppLayout.vue) | If the layout throws, every page using it shows blank |
| 10 | **Inertia version mismatch** | [package.json](file:///c:/Users/pao/Downloads/skims-shop/skims-shop/package.json) + [composer.json](file:///c:/Users/pao/Downloads/skims-shop/skims-shop/composer.json) | `@inertiajs/vue3` v2.x requires `inertiajs/inertia-laravel` v2.x — ✅ yours match |

---

## Part 2 — MVC Best Practices for VILT Authentication

### Architecture Principles

```
┌──────────────────────────────────────────────────────────┐
│                    Laravel (Backend)                      │
│                                                          │
│  Routes (web.php)                                        │
│    └─► Middleware (auth, role-check)                      │
│          └─► Controller (thin, delegates to Actions)     │
│                └─► Action classes (business logic)        │
│                      └─► Models (data access only)       │
│                                                          │
│  HandleInertiaRequests (shared props middleware)          │
│    └─► Shares auth, flash, permissions to every page     │
├──────────────────────────────────────────────────────────┤
│                     Vue (Frontend)                        │
│                                                          │
│  Pages/       ← Receives props from Inertia::render()   │
│  Layouts/     ← Wraps pages, reads $page.props           │
│  Components/  ← Reusable, prop-driven, no $page access  │
└──────────────────────────────────────────────────────────┘
```

### Rule 1: Controllers Are Thin

Controllers should only:
1. Validate the request (or delegate to a Form Request)
2. Call an Action/Service class
3. Return an Inertia response

```php
// ✅ Good — thin controller
class SupplierInventoryController extends Controller
{
    public function index(Request $request)
    {
        $items = InventoryItem::where('supplier_id', $request->user()->id)
            ->latest()
            ->get();

        return Inertia::render('Supplier/Inventory/Index', [
            'items' => $items,
        ]);
    }
}
```

```php
// ❌ Bad — fat controller with business logic
class SupplierInventoryController extends Controller
{
    public function index(Request $request)
    {
        // Don't do calculations, email sending, complex queries here
        $items = InventoryItem::where('supplier_id', $request->user()->id)->get();
        $lowStock = $items->filter(fn ($i) => $i->stock < 10);
        Mail::send(new LowStockAlert($lowStock));
        $analytics = $this->calculateAnalytics($items);
        // ...50 more lines...
        return Inertia::render('SupplierInventory', compact('items', 'analytics'));
    }
}
```

### Rule 2: Use Action Classes for Business Logic

Your project already has `app/Actions/Fortify/` — extend this pattern:

```php
// app/Actions/Supplier/GetInventoryItems.php
class GetInventoryItems
{
    public function execute(User $supplier): Collection
    {
        return InventoryItem::where('supplier_id', $supplier->id)
            ->latest()
            ->get();
    }
}
```

### Rule 3: Organize Vue Pages by Domain

```
resources/js/Pages/
├── Auth/
│   ├── Login.vue
│   └── Register.vue
├── Admin/
│   └── Dashboard.vue        ← was AdminDashboard.vue
├── Supplier/
│   ├── Dashboard.vue         ← was SupplierDashboard.vue
│   └── Inventory/
│       └── Index.vue         ← was SupplierInventory.vue
├── Customer/
│   └── Dashboard.vue
└── Dashboard.vue             ← shared landing / redirect hub
```

### Rule 4: Share Auth Data via Middleware, Not Per-Page

```php
// HandleInertiaRequests.php — share once, use everywhere
public function share(Request $request): array
{
    return [
        ...parent::share($request),
        'auth' => [
            'user' => $request->user()
                ? $request->user()->only('id', 'name', 'email', 'role', 'profile_photo_url')
                : null,
        ],
        'flash' => [
            'success' => $request->session()->get('success'),
            'error'   => $request->session()->get('error'),
        ],
    ];
}
```

### Rule 5: Use `usePage()` in `<script setup>`, `$page` in `<template>`

```vue
<script setup>
// ✅ Correct way to access page props in <script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
</script>

<template>
  <!-- ✅ $page is available directly in templates -->
  <span>{{ $page.props.auth.user.name }}</span>
</template>
```

---

## Part 3 — Role-Based Routing Boilerplate

### Step 1: User Model

**File:** `app/Models/User.php` (your existing model is already good ✅)

```php
// Your existing methods — no changes needed
public function hasRole(string $role): bool
{
    return $this->role === $role;
}

public function hasAnyRole(array $roles): bool
{
    return in_array($this->role, $roles, true);
}

// Optional: add constants for role names to avoid magic strings
public const ROLE_ADMIN    = 'admin';
public const ROLE_SUPPLIER = 'supplier';
public const ROLE_CUSTOMER = 'customer';
```

---

### Step 2: Role Middleware

**File:** `app/Http/Middleware/EnsureUserRole.php` (your existing middleware with minor improvement)

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Ensure the authenticated user has one of the required roles.
     *
     * Usage in routes:
     *   ->middleware(EnsureUserRole::class.':admin')
     *   ->middleware(EnsureUserRole::class.':admin,supplier')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasAnyRole($roles)) {
            // For Inertia requests, abort with 403 so the error page renders
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
```

---

### Step 3: Role-Based Redirect After Login

**File:** `app/Http/Middleware/RedirectByRole.php` *(NEW)*

```php
<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectByRole
{
    /**
     * Redirect authenticated users to their role-specific dashboard.
     *
     * Apply this to the generic /dashboard route so users land
     * on their proper home page after login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $target = match ($user->role) {
            User::ROLE_ADMIN    => route('admin.dashboard'),
            User::ROLE_SUPPLIER => route('supplier.dashboard'),
            User::ROLE_CUSTOMER => route('customer.dashboard'),
            default             => null,
        };

        // Only redirect if we're on the generic /dashboard
        // and the user has a role-specific destination
        if ($target && $request->routeIs('dashboard')) {
            return redirect($target);
        }

        return $next($request);
    }
}
```

---

### Step 4: Authenticated Login Response (Override Fortify)

**File:** `app/Http/Responses/LoginResponse.php` *(NEW)*

```php
<?php

namespace App\Http\Responses;

use App\Models\User;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        $home = match ($user->role) {
            User::ROLE_ADMIN    => route('admin.dashboard'),
            User::ROLE_SUPPLIER => route('supplier.dashboard'),
            User::ROLE_CUSTOMER => route('customer.dashboard'),
            default             => route('dashboard'),
        };

        // Inertia responses must be redirects (not Inertia::render)
        // from POST handlers like login
        return redirect()->intended($home);
    }
}
```

**Register it in** `app/Providers/FortifyServiceProvider.php`:

```php
use App\Http\Responses\LoginResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

public function register(): void
{
    $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
}
```

---

### Step 5: Dashboard Controller

**File:** `app/Http/Controllers/DashboardController.php` *(NEW)*

```php
<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function admin(): Response
    {
        return Inertia::render('Admin/Dashboard');
    }

    public function supplier(): Response
    {
        return Inertia::render('Supplier/Dashboard');
    }

    public function customer(): Response
    {
        return Inertia::render('Customer/Dashboard');
    }
}
```

---

### Step 6: Routes

**File:** `routes/web.php`

```php
<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierInventoryController;
use App\Http\Middleware\EnsureUserRole;
use App\Http\Middleware\RedirectByRole;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'        => Route::has('login'),
        'canRegister'     => Route::has('register'),
        'laravelVersion'  => Application::VERSION,
        'phpVersion'      => PHP_VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Generic dashboard — redirects to role-specific dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard')->middleware(RedirectByRole::class);

    /*
    |----------------------------------------------------------------------
    | Admin Routes
    |----------------------------------------------------------------------
    */
    Route::middleware(EnsureUserRole::class.':admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/', [DashboardController::class, 'admin'])
                ->name('dashboard');
        });

    /*
    |----------------------------------------------------------------------
    | Supplier Routes
    |----------------------------------------------------------------------
    */
    Route::middleware(EnsureUserRole::class.':supplier')
        ->prefix('supplier')
        ->name('supplier.')
        ->group(function () {
            Route::get('/', [DashboardController::class, 'supplier'])
                ->name('dashboard');

            Route::get('/inventory', [SupplierInventoryController::class, 'index'])
                ->name('inventory');
            Route::post('/inventory', [SupplierInventoryController::class, 'store'])
                ->name('inventory.store');
        });

    /*
    |----------------------------------------------------------------------
    | Customer Routes
    |----------------------------------------------------------------------
    */
    Route::middleware(EnsureUserRole::class.':customer')
        ->prefix('customer')
        ->name('customer.')
        ->group(function () {
            Route::get('/', [DashboardController::class, 'customer'])
                ->name('dashboard');
        });
});
```

---

### Step 7: Fix Dashboard.vue

**File:** `resources/js/Pages/Dashboard.vue`

```vue
<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed } from 'vue';

const page = usePage();
const userRole = computed(() => page.props.auth?.user?.role);
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-card bg-white shadow-card">
                    <div class="p-8 text-center">
                        <p class="mb-3 text-xs font-bold uppercase tracking-[0.24em] text-accent">
                            SKIMS SHOP
                        </p>

                        <h1 class="mb-3 text-2xl font-bold text-gray-900">
                            Welcome, {{ $page.props.auth?.user?.name }}! 👋
                        </h1>

                        <p class="mx-auto mb-6 max-w-md leading-relaxed text-muted">
                            You're logged in to your SKIMS SHOP dashboard.
                        </p>

                        <div class="flex flex-wrap justify-center gap-3">
                            <Link
                                v-if="userRole === 'admin'"
                                :href="route('admin.dashboard')"
                                class="rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white"
                            >
                                Open Admin Panel
                            </Link>
                            <Link
                                v-if="userRole === 'supplier'"
                                :href="route('supplier.dashboard')"
                                class="rounded-full bg-pink-600 px-4 py-2 text-sm font-semibold text-white"
                            >
                                Open Supplier Panel
                            </Link>
                            <Link
                                v-if="userRole === 'customer'"
                                :href="route('customer.dashboard')"
                                class="rounded-full bg-violet-600 px-4 py-2 text-sm font-semibold text-white"
                            >
                                Browse Shop
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
```

---

## Summary of All Changes

| Action | File | What |
|--------|------|------|
| **FIX** | `resources/js/Pages/Dashboard.vue` | Replace bare `$page` with `usePage()` composable |
| **FIX** | `app/Http/Middleware/HandleInertiaRequests.php` | Share `auth.user` with role data |
| **NEW** | `app/Http/Middleware/RedirectByRole.php` | Redirect `/dashboard` to role-specific route |
| **NEW** | `app/Http/Responses/LoginResponse.php` | Override Fortify's post-login redirect |
| **NEW** | `app/Http/Controllers/DashboardController.php` | Thin controller for each dashboard |
| **MODIFY** | `app/Providers/FortifyServiceProvider.php` | Bind custom `LoginResponse` |
| **MODIFY** | `routes/web.php` | Organized role-based route groups |
| **MODIFY** | `app/Models/User.php` | Add role constants (optional) |
| **REORGANIZE** | `resources/js/Pages/` | Move dashboards into `Admin/`, `Supplier/`, `Customer/` subdirs |

> [!TIP]
> **Quick test:** After applying the `Dashboard.vue` fix (Issue #1) and the `HandleInertiaRequests.php` fix (Issue #2), your blank screen should be resolved immediately — even before implementing the full role-based routing refactor.
