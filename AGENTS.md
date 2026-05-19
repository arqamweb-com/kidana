<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.3
- filament/filament (FILAMENT) - v5
- laravel/framework (LARAVEL) - v13
- laravel/prompts (PROMPTS) - v0
- laravel/sanctum (SANCTUM) - v4
- laravel/socialite (SOCIALITE) - v5
- livewire/livewire (LIVEWIRE) - v4
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- tailwindcss (TAILWINDCSS) - v4

## Skills Activation

This project has domain-specific skills available. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

- `laravel-best-practices` — Apply this skill whenever writing, reviewing, or refactoring Laravel PHP code. This includes creating or modifying controllers, models, migrations, form requests, policies, jobs, scheduled commands, service classes, and Eloquent queries. Triggers for N+1 and query performance issues, caching strategies, authorization and security patterns, validation, error handling, queue and job configuration, route definitions, and architectural decisions. Also use for Laravel code reviews and refactoring existing Laravel code to follow best practices. Covers any task involving Laravel backend PHP code patterns.
- `socialite-development` — Manages OAuth social authentication with Laravel Socialite. Activate when adding social login providers; configuring OAuth redirect/callback flows; retrieving authenticated user details; customizing scopes or parameters; setting up community providers; testing with Socialite fakes; or when the user mentions social login, OAuth, Socialite, or third-party authentication.
- `pest-testing` — Use this skill for Pest PHP testing in Laravel projects only. Trigger whenever any test is being written, edited, fixed, or refactored — including fixing tests that broke after a code change, adding assertions, converting PHPUnit to Pest, adding datasets, and TDD workflows. Always activate when the user asks how to write something in Pest, mentions test files or directories (tests/Feature, tests/Unit, tests/Browser), or needs browser testing, smoke testing multiple pages for JS errors, or architecture tests. Covers: test()/it()/expect() syntax, datasets, mocking, browser testing (visit/click/fill), smoke testing, arch(), Livewire component tests, RefreshDatabase, and all Pest 4 features. Do not use for factories, seeders, migrations, controllers, models, or non-test PHP code.
- `tailwindcss-development` — Always invoke when the user's message includes 'tailwind' in any form. Also invoke for: building responsive grid layouts (multi-column card grids, product grids), flex/grid page structures (dashboards with sidebars, fixed topbars, mobile-toggle navs), styling UI components (cards, tables, navbars, pricing sections, forms, inputs, badges), adding dark mode variants, fixing spacing or typography, and Tailwind v3/v4 work. The core use case: writing or fixing Tailwind utility classes in HTML templates (Blade, JSX, Vue). Skip for backend PHP logic, database queries, API routes, JavaScript with no HTML/CSS component, CSS file audits, build tool configuration, and vanilla CSS.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Application Architecture

This is a dual-purpose Laravel application with an admin dashboard and public-facing website:

### Web Application
- **Routes**: `routes/web.php` defines public-facing routes (home, packages, services, contact, about, umrah-plus)
- **Controllers**: Located in `app/Http/Controllers/`. Single-action controllers use `__invoke()` (e.g., `HomeController`, `PackageSearchController`); multi-action controllers use method names (e.g., `ServiceController::index()`, `ServiceController::show()`)
- **Models**: Located in `app/Models/` — `Services`, `Packages`, `User`. Models use query scopes (`active()`) and array casting for JSON-structured fields (`stats`, `benefits`, `steps`, `gallery`, `testimonials`)
- **Views**: Located in `resources/views/` — Blade templates for pages, services, and packages

### Admin Dashboard (Filament v5)
- **Location**: `/admin` route
- **Configuration**: `app/Providers/Filament/AdminPanelProvider.php`
- **Resources**: Located in `app/Filament/Resources/` with subdirectories for each resource (Services, Packages, Users)
- **Resource Structure**: Each resource follows the pattern:
  - `{Resource}Resource.php` — main configuration
  - `Schemas/{Resource}Form.php`, `Schemas/{Resource}Infolist.php` — form & display schemas (use static `configure()` method)
  - `Tables/{Resource}Table.php` — table configuration (use static `configure()` method)
  - `Pages/` — Create, Edit, List, View page classes
  - `RelationManagers/` — for managing related records (e.g., `PackagesRelationManager` on Services)
- **Filament v5 Pattern**: Form and table configuration extracted into separate classes with `configure()` static methods for better organization

### Routes
- `GET /` → Home
- `GET /packages` → Package listing
- `GET /packages/{package:slug}` → Package detail
- `GET /packages/search` → Package search page
- `GET /services` → Service listing
- `GET /services/{service:slug}` → Service detail
- `GET /contact`, `/about`, `/umrah-plus` → Static pages
- `GET /admin` → Filament admin panel
- `POST /api/user` → Get authenticated user (Sanctum)

### Model Relationships & Scopes
- **Services** → `hasMany(Packages)` (uses `service_id` foreign key)
- **Packages** → `belongsTo(Services)`
- **Scopes**: Both Services and Packages have `active()` scope — use this to filter inactive records
- **Array Fields**: Services stores JSON arrays for `stats`, `benefits`, `steps`, `gallery`, `testimonials` — these are auto-cast and can be queried with JSON operators

### Filament Resource Development
When creating or modifying Filament resources:
1. Extract form fields into `Schemas/{Resource}Form.php` with a static `configure(Schema $schema): Schema` method
2. Extract table columns into `Tables/{Resource}Table.php` with a static `configure(Table $table): Table` method
3. Extract display fields into `Schemas/{Resource}Infolist.php` if you need a view page
4. Use `RelationManagers` in `RelationManagers/` to manage related records inline (not within the form)
5. Keep page classes (`Pages/List*.php`, `Pages/Create*.php`, `Pages/Edit*.php`) minimal — they delegate to schemas

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Testing Patterns

This project uses Pest for testing with good coverage of web routes, model relationships, and content persistence:
- **Feature Tests** (`tests/Feature/`): Test routes, page responses, and user workflows
- **Test Examples**: `HomeServicesTest`, `PackageSearchTest`, `PackageShowPageTest`, `PackagesServiceRelationshipTest`, `ServicesContentPersistenceTest`, `ServicesRoutesTest`
- **Scoped Tests**: Some tests validate that active/inactive records are handled correctly (use model scopes in assertions)
- **Database Testing**: Tests verify data persistence across Services-Packages relationships

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.
- **Vite Setup**: The project uses Vite for asset bundling with Laravel Vite Plugin and Tailwind v4 (`@tailwindcss/vite`). CSS and JS entrypoints are `resources/css/app.css` and `resources/js/app.js`.
- **Blade Templates**: View files are located in `resources/views/` and organized by feature (e.g., `pages/`, `services/`, `packages/`, `layout/`)
- **Tailwind Compiling**: Tailwind is configured to compile through Vite; any CSS changes require the Vite dev server or rebuild

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.
- To check environment variables, read the `.env` file directly.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Filament v5 Resource Development

- Create resources with `php artisan make:filament-resource ResourceName` (with `--generate` to auto-scaffold fields)
- Form & table configuration must be extracted into dedicated schema classes in `Schemas/` and `Tables/` subdirectories, not inline in the Resource
- When adding related records management, create relation managers: `php artisan make:filament-relation-manager RelatedModel field-name`
- Use Filament Actions for bulk operations, modals, and custom buttons — don't override page methods

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.
- Use query scopes for common filters (e.g., `active()`, `published()`) — this project uses scopes extensively

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

## Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.

</laravel-boost-guidelines>
