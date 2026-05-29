---
name: deploy-check
description: Run a pre-deployment checklist for this Laravel app — verifies migrations, storage link, asset build, queue, and env config are ready.
disable-model-invocation: true
---

Run through the following pre-deployment checklist for the Peta BPS Enrekang app and report the status of each item:

1. **Pending migrations** — Run `php artisan migrate:status` and flag any unapplied migrations.
2. **Storage link** — Check that `public/storage` symlink exists. If not, run `php artisan storage:link`.
3. **Asset build** — Check that `public/build/manifest.json` exists (indicates a production Vite build). If missing, run `npm run build`.
4. **Queue connection** — Check `QUEUE_CONNECTION` in `.env`. Warn if it's `sync` (jobs run inline, not async — Excel imports may block requests).
5. **App environment** — Verify `APP_ENV=production` and `APP_DEBUG=false` in `.env` for production deploys.
6. **Cache config** — Run `php artisan config:cache` and `php artisan route:cache` to cache for production.

Report each item as ✓ (OK), ✗ (needs attention), or ⚠ (warning with recommendation). Summarize what still needs action.
