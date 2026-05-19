# Project Structure

Laravel 13 + Filament v5 — Dashboard & Client Website

---

## Overview

This project has two main parts:

| Part               | Description                               |
|--------------------|-------------------------------------------|
| **Dashboard**      | Filament admin panel for managing content |
| **Client Website** | Laravel Blade frontend for visitors       |

---

## Client Website (Blade)

### Pages

| Page            | Route                  | Controller                | View                                        |
|-----------------|------------------------|---------------------------|---------------------------------------------|
| Home            | `GET /`                | `HomeController`          | `resources/views/home.blade.php`            |
| Packages Search | `GET /packages/search` | `PackageSearchController` | `resources/views/packages/search.blade.php` |

> Posts/Articles section has been removed from the project.

### Views to Add

```
resources/views/
├── home.blade.php              ✅ exists
├── packages/
│   └── search.blade.php        ✅ exists
└── services/
    └── index.blade.php         ⬜ to be created
```

---

## Dashboard (Filament Admin Panel)

Located at `/admin` — managed via `app/Providers/Filament/AdminPanelProvider.php`

### Resources

```
app/Filament/Resources/
├── Categories/
│   ├── CategoryResource.php
│   ├── Pages/
│   │   ├── ListCategories.php
│   │   ├── CreateCategory.php
│   │   └── EditCategory.php
│   ├── Schemas/
│   │   └── CategoryForm.php
│   └── Tables/
│       └── CategoriesTable.php
│
├── Packages/
│   ├── PackagesResource.php
│   ├── Pages/
│   │   ├── ListPackages.php
│   │   ├── CreatePackages.php
│   │   └── EditPackages.php
│   ├── Schemas/
│   │   └── PackagesForm.php
│   └── Tables/
│       └── PackagesTable.php
│
├── Services/
│   ├── ServicesResource.php
│   ├── Pages/
│   │   ├── ListServices.php
│   │   ├── CreateServices.php
│   │   ├── EditServices.php
│   │   └── ViewServices.php
│   ├── Schemas/
│   │   ├── ServicesForm.php
│   │   └── ServicesInfolist.php
│   └── Tables/
│       └── ServicesTable.php
│
└── Users/
    ├── UserResource.php
    ├── Pages/
    │   ├── ListUsers.php
    │   ├── CreateUser.php
    │   └── EditUser.php
    ├── Schemas/
    │   └── UserForm.php
    ├── Tables/
    │   └── UsersTable.php
    └── Widgets/
        └── UserWidget.php
```

### Pages & Widgets

```
app/Filament/
├── Pages/
│   └── Dashboard.php
└── Widgets/
    ├── PostWidget.php
    └── PostWidgetChart.php
```

---

## Models

```
app/Models/
├── User.php
├── Category.php
├── Packages.php
└── Services.php
```

---

## Controllers (Web)

```
app/Http/Controllers/
├── Controller.php
├── HomeController.php          → renders home page
└── PackageSearchController.php → handles packages search
```

---

## Database

```
database/
├── migrations/
│   ├── create_users_table
│   ├── create_cache_table
│   ├── create_jobs_table
│   ├── create_categories_table
│   ├── add_scope_to_categories_table
│   ├── create_packages_table
│   ├── add_search_fields_to_packages_table
│   ├── create_services_table
│   └── create_personal_access_tokens_table
├── factories/
│   ├── UserFactory.php
│   ├── CategoryFactory.php
│   ├── PackagesFactory.php
│   └── ServicesFactory.php
└── seeders/
    └── DatabaseSeeder.php
```

---

## Routes

```
routes/
├── web.php      → GET /  |  GET /packages/search
└── api.php      → API routes (Sanctum)
```

---

## Enums

```
app/Enum/
└── Role.php     → user roles (e.g. Admin, User)
```

---

## Policies

```
app/Policies/
└── CategoryPolicy.php
```

---

## Providers

```
app/Providers/
├── AppServiceProvider.php
└── Filament/
    └── AdminPanelProvider.php
```

---

## Frontend Assets

```
resources/
├── css/
│   └── app.css
└── js/
    ├── app.js
    └── bootstrap.js

public/
└── kidana-home-assets/      → home page images (Egypt, Mecca, etc.)
```

---

## Removed Sections

| Section          | Status                                                                      |
|------------------|-----------------------------------------------------------------------------|
| Posts / Articles | Removed — model & migration still exist in DB but no active routes or views |

⏺ :لثمألا لكيهلا ،ةلصفنم ةحفص ةدحاو لك ول

Routes

// routes/web.php  
Route::get('/', HomeController::class)->name('home');
Route::get('/services', [PageController::class, 'services'])->name('services');  
Route::get('/packages', [PageController::class, 'packages'])->name('packages');  
Route::get('/umrah-plus', [PageController::class, 'umrahPlus'])->name('umrah-plus');
Route::get('/about', [PageController::class, 'about'])->name('about');  
Route::get('/contact', [PageController::class, 'contact'])->name('contact');

Controller

:6 لدب دحاو رللورتنوك

// app/Http/Controllers/PageController.php  
class PageController extends Controller  
{
public function services(): View { return view('pages.services'); }  
public function packages(): View { return view('pages.packages'); }
public function umrahPlus(): View { return view('pages.umrah-plus'); }  
public function about(): View { return view('pages.about'); }
public function contact(): View { return view('pages.contact'); }  
}

## Pages

├── services.blade.php
├── packages.blade.php  
├── umrah-plus.blade.php
├── about.blade.php  
└── contact.blade.php
Nav Links

<a href="{{ route('services') }}">Services</a>  
<a href="{{ route('packages') }}">Packages</a>
<a href="{{ route('umrah-plus') }}">Umrah Plus</a>
