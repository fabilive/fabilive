# Production Runbook & Rollback

## Safe Deploy Order
1. **Maintenance Mode**: `php artisan down`.
2. **Back up Database**: Snapshot or `mysqldump`.
3. **Deploy Code**: Standard CI/CD or `git pull`.
4. **Run Migrations**: `php artisan migrate --force`.
5. **Optimize**: `php artisan optimize`.
6. **Queue Restart**: `php artisan queue:restart`.
7. **Exit Maintenance**: `php artisan up`.

## Verification (Post-Deploy)
- Visit `/tracking/{valid_order_id}` - Expect 200/404 not 500.
- Check Ledger: `php artisan tinker --execute="App\Models\WalletLedger::latest()->first()"`

## Rollback Strategy
### Code Rollback
1. Revert to previous git tag/hash.
2. `php artisan optimize`.

### Database Rollback (Caution)
1. If migration added columns: `php artisan migrate:rollback --step=1`.
2. If data was corrupted: Restore from snapshot.
