# Task: Remove the old "Sharm Club" brand from Kidana content

Read `CLAUDE.md` first and follow it (Laravel Boost rules: `search-docs` before code changes, `php artisan make:` for new files, Pest tests for every change, `vendor/bin/pint --dirty --format agent` at the end).

This task has three parts. Do them in order, one commit per part. **Do not run anything against production.** Everything here is built and tested locally; Youssef runs the commands on live himself.

---

## Background (from a production DB dump taken 2026-10-10)

The site's content was imported from the old Sharm Club website. The old brand survives in two places, **only in the `packages` table**. No other content table (services, destinations, hotels, hotel_rooms, faqs, testimonials, offices, partners) contains it.

### A. Sign / company-name text in `packages.description`
17 packages (ids 9, 13, 14, 17, 22, 65, 77, 124, 129, 130, 151, 215, 263, 276, 278, 279, 283), about 80 occurrences across the 4 locales (`ar`, `en`, `fr`, `id`). `description` is a Spatie translatable JSON column holding HTML. Real examples:

- `...the main arrival hall of Cairo airport with the sign 'Sharm-club.com'.</p>`
- `...سيقابلك مندوبنا عند مخرج الميناء حاملاً لافتة 'sharm-club.com'.</p>`
- `...avec une pancarte « sharm-club.com ».`
- `...with the sign sharm-club.</p>`, `...with a sharm-club sign and breakfast...`
- `...sharm-club representative will meet guests...`, `...مندوب sharm-club الضيوف...`, `...le représentant de sharm-club...`

Casing varies (`sharm-club`, `Sharm-club`). Quotes vary (`'…'`, `« … »`, `“…”`, `\"…\"`).

### B. Hotlinked images (the bigger risk)
Every package's images load directly from the old site:

- `packages.image_url`: 278 of 279 rows hold a full URL like `https://www.sharm-club.com/assets/images/cities/alexandria/alex-montaza-palace-tour.jpg`
- `packages.gallery`: translatable JSON, shape `{"ar": [{"image": "<url>", "caption": ""}, ...], "en": [...], "fr": [...], "id": [...]}`. About 5,575 URL occurrences.
- **190 unique image URLs** in total, all under `https://www.sharm-club.com/assets/images/`.

If sharm-club.com goes down or blocks hotlinking, every image on Kidana disappears. A blind find/replace of `sharm-club` would also break every image.

The views and the API already handle relative paths. `ResolvesImageUrls::imageUrl()` passes `http(s)://` through and otherwise returns `Storage::disk('public')->url($path)`. `packages/show.blade.php` uses `$resolveImageUrl(...)` and `Storage::url(...)`. `FILESYSTEM_DISK=public`. So once a file is on the `public` disk, storing its **relative path** (e.g. `packages/legacy/cities/alexandria/alex-montaza-palace-tour.jpg`) in the DB is enough. Verify this in the listing cards, `packages/show`, the OG image meta and the API before relying on it.

---

## Part 1: Replace the sign / company-name text

Create an artisan command:

```
php artisan content:rebrand-legacy-text {--dry-run}
```

Rules:
1. Scope: `packages.description`, all locales. Also scan every other translatable/text column of `packages`, `services`, `destinations`, `hotels`, `hotel_rooms`, `faqs`, `testimonials`, `offices` and report any hit, but the dump says there are none. Never touch `slug` columns, and never touch `bookings`, `payments`, `api_*`, `users`, logs, jobs, cache or sessions.
2. **Never modify anything inside an image URL.** Only replace text that is not part of a URL. A match preceded by `//`, `www.` or `/`, or followed by `/`, is a URL and must be skipped.
3. Replacements, case-insensitive:
   - `sharm-club.com` → `kidanatravel.com`
   - bare `sharm-club` (not followed by `.com`) → `Kidana Travel`
   - Also catch `sharm club` / `Sharm Club` (with a space) → `Kidana Travel`, in case any exist.
   - Keep the surrounding quotes and punctuation exactly as they are.
4. Work through the models (or decode, edit and re-encode the JSON) so the stored JSON keeps the same encoding style as the existing rows. The dump stores Arabic as real UTF-8 characters and URLs with unescaped slashes. Do not introduce `\uXXXX` escapes or `\/`.
5. Update only rows that changed, and do not change `updated_at` semantics unexpectedly. Normal Eloquent save is fine.
6. `--dry-run` prints a table with model, id, slug, column, locale, the before snippet (about 40 chars around the match) and the after snippet, plus totals. It writes nothing.
7. Without `--dry-run`, it applies the changes inside a DB transaction and prints the same totals.
8. Idempotent: a second run finds 0 matches.

Tests (Pest, feature) must cover:
- An `en` + `ar` + `fr` description with `'Sharm-club.com'`, `« sharm-club.com »` and bare `sharm-club representative` is rewritten correctly.
- A description containing `<img src="https://www.sharm-club.com/assets/images/x.jpg">` keeps the URL unchanged.
- `image_url` and `gallery` are never modified by this command.
- `--dry-run` writes nothing.
- A second run reports 0 changes.

---

## Part 2: Move the hotlinked images onto Kidana's own storage

Create an artisan command:

```
php artisan packages:localize-legacy-images {--dry-run} {--host=sharm-club.com} {--limit=}
```

Behaviour:
1. Collect every unique image URL whose host matches `--host` (with or without `www.`) from `packages.image_url` and from every `image` entry in `packages.gallery`, across all locales.
2. For each unique URL, download it **once**: Laravel `Http` client, a timeout of about 20s, 2 retries, and a browser-like User-Agent. Accept it only if the status is 200 and the `Content-Type` starts with `image/`.
3. Save to the `public` disk at `packages/legacy/{path after /assets/images/}`, keeping the original folders and filename for SEO. For example, `https://www.sharm-club.com/assets/images/cities/aswan/philae/trojan-kiosk-at-philae.jpg` becomes `packages/legacy/cities/aswan/philae/trojan-kiosk-at-philae.jpg`. If the file already exists, skip the download (this is what makes resuming work).
4. Build a map from URL to relative path. Then update `image_url`, and every `gallery[locale][n].image`, to the relative path. Keep the `caption` and all other keys untouched.
5. If a download fails, leave that URL as it is in the DB and list it at the end (URL, HTTP status or error, and the affected package ids). One failure must not stop the run.
6. `--dry-run` lists unique URLs, target paths and affected package counts, and does not download or write.
7. `--limit=N` processes only the first N unique URLs, for a quick trial.
8. Print a summary: downloaded, skipped (already present), failed, packages updated and total bytes.
9. Idempotent: a second run finds nothing left to migrate, except failures.

Also check:
- That `php artisan storage:link` is in place and that the page renders a relative path correctly everywhere a package image shows: listing cards, search, destination pages, the home page, `packages/show` hero and gallery, the OG/meta image, the sitemap if it includes images, the CSV exporter and the API `PackageResource`.
- **Side bug to fix in the same part:** `PackageResource` builds `gallery` with `$this->imageUrls($this->gallery)`. That helper only accepts strings, but gallery items are `{image, caption}` objects, so the API currently returns an empty gallery. Make it map `data_get($item, 'image')` (keeping `caption` if useful) and add a test.

Tests (Pest, feature) with `Http::fake()` and `Storage::fake('public')`:
- `image_url` and the gallery entries in two locales are rewritten to the same relative path, and the file exists on the fake disk.
- The same URL used by two packages is downloaded once (assert the request count).
- A 404 or non-image response leaves the original URL and is reported.
- Non-matching hosts (e.g. an unsplash URL or an existing relative path) are left alone.
- `--dry-run` downloads and writes nothing.
- The API gallery returns the resolved image URLs.

---

## Part 3: Verify locally, then hand over

1. Run the new tests and the existing package-related tests (`PackageShowPageTest`, `PackageSearchTest`, `DestinationPackagesTest`, `PackageExporterTest`, `HomeServicesTest`) with `php artisan test --compact --filter=...`. All must pass.
2. Run `vendor/bin/pint --dirty --format agent`.
3. On the local DB, run both commands with `--dry-run` first, then for real. Open a few affected packages (for example `aswan-nile-cruise-holiday`, `cairo-alexandria-siwa-6-days`, `pyramids-nile-cruise-holidays`) in `ar` and `en` and confirm the text and images.
4. Run this local check and confirm both counts are 0 (failed downloads aside):
   ```sql
   SELECT COUNT(*) FROM packages WHERE LOWER(description) REGEXP 'sharm[- ]club' AND LOWER(description) NOT REGEXP 'sharm-club\\.com/assets';
   SELECT COUNT(*) FROM packages WHERE image_url LIKE '%sharm-club.com%' OR gallery LIKE '%sharm-club.com%';
   ```
   The first query is approximate. If it is not 0, inspect the rows by hand.
5. Commit each part separately with clear messages.

At the end, print this **production runbook** for Youssef. Do not run it yourself:
```
php artisan down
# back up the DB first (mysqldump or the hosting panel's phpMyAdmin export)
git pull   # or the usual deploy
composer install --no-dev --optimize-autoloader
php artisan storage:link        # only if missing
php artisan content:rebrand-legacy-text --dry-run
php artisan content:rebrand-legacy-text
php artisan packages:localize-legacy-images --dry-run
php artisan packages:localize-legacy-images --limit=5   # trial
php artisan packages:localize-legacy-images
php artisan optimize:clear
php artisan up
```
Note for the runbook: the image command needs outbound HTTPS from the server to sharm-club.com and about 190 images' worth of disk space. If the server's PHP time limit is a problem, run it again: already-downloaded files are skipped.

## Out of scope
- Do not change any `slug`.
- Do not touch package names (none contain the old brand), bookings, payments or API clients.
- Do not delete `failed_import_rows`. It holds two old "| Sharm Club" names, but they are not shown anywhere.
