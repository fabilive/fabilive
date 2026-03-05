# Staging Runbook - Delivery System

## Environment Variables
Ensure the following are set in the staging `.env`:
- `FILESYSTEM_DISK=s3` (or `local` for initial testing)
- `ABLY_KEY=...`
- `ABLY_PUBLIC_KEY=...`
- `QUEUE_CONNECTION=redis`

## Deployment Steps
1. **Pull Changes**: `git pull origin frontend`.
2. **Install Deps**: `composer install --no-dev`.
3. **Migrate**: `php artisan migrate --force`.
4. **Symlink Storage**: `php artisan storage:link`.
5. **Restart Workers**: `php artisan queue:restart`.

## Verification Commands
- Check Ably connection: `php artisan ably:check` (if exists).
- Verify storage: `php artisan tinker --execute="Storage::disk('public')->put('test.txt', 'staging');"`
