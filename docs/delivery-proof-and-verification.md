# Delivery Proof & Verification

Riders must provide proof of delivery, which is then verified by an Admin before any funds are released from escrow.

## Proof Upload (Rider)

- **Endpoint**: `POST /api/delivery/rider/deliver/{job}`
- **Requirement**: `proof_photo` (Image file).
- **Mechanism**: The photo is stored in `storage/app/public/delivery_proofs`.
- **Timestamp**: `proof_uploaded_at` is recorded on the `delivery_jobs` table.

## Admin Verification

- **Endpoint**: `POST /admin/delivery/verify/{job}`
- **Logic**:
    1. Checks if job is `delivered_pending_verification`.
    2. Uses DB row locks to prevent race conditions (idempotency).
    3. Triggers `EscrowReleaseService`.
    4. Splits payments: Seller Payouts, Rider Earnings, Admin Commission.
    5. Marks job as `delivered_verified`.

## Financial Safety

- **Idempotency**: Payouts cannot be triggered twice for the same order/job.
- **Audit Trail**: Every verification and payout is logged in `delivery_job_events` and `wallet_ledger`.
