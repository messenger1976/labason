# Mobile Dashboard – Installable App (PWA)

The Mobile Billing dashboard at `http://labason.com/master/mobile_dashboard` is now a **Progressive Web App (PWA)**. Users can install it on their phones and open it like a native app, with the **same layout** as the web page.

## What Was Added

1. **`manifest-mobile-dashboard.json`**  
   - PWA manifest: name, icons, theme, `start_url`, shortcuts (Dashboard, Tickets).

2. **`sw-mobile-dashboard.js`**  
   - Service worker that:
     - Caches CSS, JS, and images for faster loads.
     - Uses network-first for the dashboard and API (so data stays up to date when online).

3. **`application/views/admin-includes/mobile_header.php`**  
   - Manifest link, `theme-color`, `apple-mobile-web-app-title`, and service worker registration (with scope `/master/`).

## How to Install on a Phone

### Android (Chrome)

1. Open `http://labason.com/master/mobile_dashboard` in Chrome and log in.
2. In the menu (⋮), tap **“Add to Home screen”** or **“Install app”**.
3. Confirm; the **“Mobile Billing”** icon is added to the home screen.
4. Open it like any other app (standalone, no browser UI).

### iPhone (Safari)

1. Open `http://labason.com/master/mobile_dashboard` in Safari and log in.
2. Tap the **Share** button (□↑).
3. Tap **“Add to Home Screen”**.
4. Name it (e.g. “Mobile Billing”) and tap **Add**.
5. The icon appears on the home screen; open it for a full-screen app experience.

## Layout and Behavior

- **Layout**: Unchanged; same as the web `mobile_dashboard` (header, nav, search, meter form, billing fields, Print/Save/Cancel).
- **Display**: When launched from the home screen, it runs in **standalone** mode (no URL bar, app-like).
- **Offline**: Static assets (CSS, JS, images) are cached; the dashboard itself and APIs still need a connection. If offline, you may see cached styling but data will not load until back online.

## If the Site Is in a Subfolder

If the app is served from a subfolder (e.g. `https://yoursite.com/labason/`):

1. **Manifest**  
   - `start_url` and `scope` in `manifest-mobile-dashboard.json` assume the app is at the root (`/master/...`).  
   - For a subfolder, either:
     - Rename `manifest-mobile-dashboard.json` to `manifest-mobile-dashboard.php`, output `Content-Type: application/json`, and build `start_url` and `scope` with `base_url()` (or your base path), or  
     - Manually change `start_url` and `scope` to include the subfolder (e.g. `"/labason/master/mobile_dashboard"`, `"/labason/master/"`).

2. **Service worker**  
   - Registration in `mobile_header.php` already uses a scope derived from `base_url()`, so it should adapt if `base_url()` includes the subfolder. If not, adjust the `$sw_scope` logic to match your real `/master/` path.

## Files Touched

| File | Role |
|------|------|
| `manifest-mobile-dashboard.json` | PWA manifest |
| `sw-mobile-dashboard.js` | Service worker for `/master/` |
| `application/views/admin-includes/mobile_header.php` | Manifest link, PWA meta, SW registration |

The existing `manifest.json` and `sw.js` used by the Statement of Account are unchanged; the mobile dashboard uses its own manifest and service worker.
