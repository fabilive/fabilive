# Staging Deploy Runbook — v1.0.0-delivery-closure

## Pre-Flight Checks
```bash
# Verify tag exists and matches expected commit
git fetch --tags
git log v1.0.0-delivery-closure --oneline -1
```

## Deployment Steps

```bash
# 1. Maintenance mode ON
php artisan down --render="errors::503" --secret="staging-bypass-2026"

# 2. Pull latest code
git checkout main
git pull origin main

# 3. Install dependencies (production mode)
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# 4. Run database migrations
php artisan migrate --force

# 5. Cache configuration, routes, views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Restart queue workers
php artisan queue:restart

# 7. Maintenance mode OFF
php artisan up
```

## Post-Deploy Acceptance Checklist

| #  | Check | Expected | Pass? |
|----|-------|----------|-------|
| 1  | Admin login | Dashboard loads | ☐ |
| 2  | Financial Reports page | `/admin/reports/financial` loads with summary cards | ☐ |
| 3  | CSV Export | Downloads file with `Order Number` header | ☐ |
| 4  | Delivery Verification | `/admin/delivery/verify/{id}` returns 200 for valid job | ☐ |
| 5  | Idempotency | Second verify call returns 400 "already verified" | ☐ |
| 6  | Escrow Release | Wallet ledger entries created after verify | ☐ |
| 7  | Chat Hidden | Delivery chats hidden after terminal status | ☐ |
| 8  | Buyer↔Seller Block | No buyer_seller thread type in DB | ☐ |
| 9  | Rider Proof Required | Rider cannot mark delivered without proof_uploaded_at | ☐ |
| 10 | Rider Atomic Accept | Only one rider can accept a job (concurrent) | ☐ |

## GO / NO-GO Decision

After completing all 10 checks above:
- **GO**: All checks pass → proceed to production
- **NO-GO**: Any check fails → debug, fix, re-deploy staging

---

# Production Deploy Runbook

## Pre-Production Checklist
- [ ] Staging acceptance 10/10 passed
- [ ] Database backup taken
- [ ] Queue workers drained: `php artisan queue:restart`
- [ ] Team notified of deployment window

## Production Deploy Steps

```bash
# 1. Maintenance mode
php artisan down --render="errors::503" --secret="prod-bypass-2026"

# 2. Deploy code
git fetch --tags
git checkout v1.0.0-delivery-closure

# 3. Install dependencies
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# 4. Migrate (with force for production)
php artisan migrate --force

# 5. Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Restart workers
php artisan queue:restart

# 7. Go live
php artisan up
```

## Rollback Procedure (if needed)

```bash
# 1. Maintenance mode
php artisan down

# 2. Rollback to previous release
git checkout <previous-tag-or-commit>

# 3. Rollback migrations (if schema changes caused issues)
php artisan migrate:rollback --step=5

# 4. Reinstall
composer install --no-dev --optimize-autoloader

# 5. Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Restart + go live
php artisan queue:restart
php artisan up
```

## Migrations in This Release
| Migration | Action |
|-----------|--------|
| `2026_03_03_161407_create_wallet_ledger_table` | Creates `wallet_ledger` table |
| `2026_03_03_161417_update_orders_table_for_escrow` | Adds `escrow_status` to orders |
| `2026_03_04_221500_add_admin_verified_and_ledger_indexes` | Adds `admin_verified` + indexes |
| `2026_03_04_222828_add_proof_uploaded_at_to_delivery_jobs` | Adds `proof_uploaded_at` |
| `2026_03_04_225000_add_coords_to_users_and_orders` | Adds lat/lng coords |
| `2026_03_04_225500_create_delivery_system_tables` | Delivery jobs, stops, events |
| `2026_03_04_225503_harden_payout_and_webhook_idempotency` | Adds `payout_released_at` |
| `2026_03_04_230000_create_delivery_chats_table` | Delivery chat threads + messages |
| `2026_03_04_231000_add_delivery_fees_to_settings` | Delivery fee settings |
