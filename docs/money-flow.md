# Fabilive Money Flow Map

## 1. Deposit Lifecycle (Campay -> Wallet)
1. **User Request**: User inputs amount on Deposit page and initiates payment via Campay.
2. **Campay Checkout**: Campay processes Mobile Money payment.
3. **Webhook Reception**: Campay sends a POST webhook to `/api/campay/webhook`.
4. **Idempotency Check**: The webhook handler verifies if `reference` is already in `webhook_events`. If yes, returns 200 immediately.
5. **Ledger Credit**: If payment status is SUCCESSFUL, the handler:
   - Records the raw webhook in `webhook_events`.
   - Starts a DB transaction.
   - Increases `users.balance`.
   - Inserts a record into `wallet_transactions` (type: `deposit`, status: `completed`).
   - Commits the transaction.

## 2. Checkout Lifecycle (Wallet / Campay -> Escrow)
1. **Order Creation**: User places an order.
2. **Payment Processing**: 
   - **Wallet**: Deducts from `users.balance` immediately. Creates a `wallet_transactions` record (type: `escrow_hold`).
   - **Direct Campay**: User pays via Campay. Upon successful webhook, full amount goes directly to Escrow Hold.
3. **Escrow State**: A `wallet_holds` or equivalent record is created linking to `order_id` with status `held`. The money is officially in the system but unavailable to the Seller or Rider.

## 3. Delivery Completion + Split Payout
1. **Delivery Event**: Rider marks order as Delivered.
2. **Admin Verification**: Admin verifies the delivery was completed successfully.
3. **Escrow Release**:
   - Starts a DB transaction.
   - Updates `wallet_holds` status to `released`.
   - **Seller Credit**: Credits Seller's wallet (`users.balance`) for `(Product Total - Admin Commission)`. Records in `wallet_transactions`.
   - **Rider Credit**: Credits Rider's wallet for `(Delivery Fee - Admin Delivery Commission)`. Records in `wallet_transactions`.
   - **Admin Revenue**: Admin system records the commission portions.
   - Commits the transaction.

## 4. Withdrawal Lifecycle
1. **Request**: Seller or Rider initiates a withdrawal request via their dashboard out of their `balance`.
2. **Pending State**: DB transaction deducts the amount from their wallet immediately. Creates a `wallet_transactions` record (`type: withdrawal_pending`) and a `payout_requests` record.
3. **Admin Action**:
   - **Approve**: Admin approves. Money is disbursed via MTN/Orange/Bank. `payout_requests` and `wallet_transactions` updated to `completed`.
   - **Reject**: Admin rejects. DB transaction refunds the amount back to the user's wallet. Creates a `wallet_transactions` record (`type: withdrawal_refund`). Updates `payout_requests` to `rejected`.

## 5. Refund / Cancel Behavior
1. **Cancellation**: If an order is canceled *before* delivery completion.
2. **Escrow Reversal**: DB transaction updates `wallet_holds` to `refunded`.
3. **Buyer Refund**: Credits Buyer's wallet (`users.balance`) for the full held amount. Records in `wallet_transactions` (type: `refund`).
