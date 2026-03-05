# Fabilive Data Reset Runbook

## Overview
The `fabilive:reset` command is designed to wipe all volatile transactional data out of the system (orders, chats, notifications, logs, delivery jobs, wallet ledgers) while completely preserving the core administrative data, catalog structure, store configs, products, categories, and system settings. 

This command is typically used right before moving to production to clear out test transactions without breaking the configured shop structure.

## Prerequisites & Safety Guards
By design, the command is built with multiple fail-safes to prevent accidental usage in a live production environment.

1. **Production Lockout:**
If `APP_ENV=production`, the command will outright abort. You absolutely must explicitly set `DATA_RESET_ENABLED=true` inside the `.env` file to bypass this lockout.

2. **Confirmation Flags:**
The command requires explicit flags to signal acknowledgment of data loss: `--force` and `--i-understand`.

## Execution Steps

1. **Verify Database Backup**
   Before running a reset on any environment hosting critical catalog data, take a database backup.
   
2. **Enable the Override (If in Production)**
   Edit `.env` and add:
   ```env
   DATA_RESET_ENABLED=true
   ```

3. **Run the Command**
   Execute the following Artisan command from your project root:
   ```bash
   php artisan fabilive:reset --force --i-understand
   ```

4. **Verify Output**
   The terminal will output `✅ Reset complete:` followed by the table truncation statistics. Check your user ledger balances to ensure they were zeroed correctly.

5. **Disable the Override (CRITICAL)**
   Once the reset is complete, remove `DATA_RESET_ENABLED=true` from your `.env` or set it to `false`.

6. **Check Audit Logs**
   A warning entry `FABILIVE DATA RESET EXECUTED` will be recorded in the `storage/logs/laravel.log` file, detailing the operation for security purposes.
