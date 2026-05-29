---
name: db-reset
description: Reset the local development database — runs fresh migrations and seeds. Use when you need a clean slate for local dev/testing.
disable-model-invocation: true
---

Reset the local development database for Peta BPS Enrekang:

1. Confirm the current `APP_ENV` in `.env` is `local` — refuse to proceed if it is `production` or `staging` to prevent accidental data loss.
2. Run `php artisan migrate:fresh --seed` to drop all tables, re-run all migrations, and seed with default data.
3. Remind the user to run `php artisan storage:link` if it was lost during the reset.
4. Report the result and list any seeders that ran.
