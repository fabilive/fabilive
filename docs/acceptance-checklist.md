# Acceptance Checklist - Delivery-Finance-Chat

## Scenario 1: Normal Multi-Seller Flow
1. **Place Order**: Create a multi-seller order.
2. **Sellers Ready**: All sellers mark items as "Ready for Pickup".
3. **Rider Accept**: Rider accepts from marketplace.
4. **Pickups**: Rider marks all pickup stops as completed.
5. **Delivery**: Rider uploads proof and marks as delivered.
   - *Expectation*: Job status -> `delivered_pending_verification`.
6. **Admin Verify**: Admin logs in and verifies.
   - *Expectation*: Job status -> `delivered_verified`, Order -> `completed`, WalletLedgers created, Chat hidden.

## Scenario 2: Chat Permissions
1. **Direct Chat**: Buyer/Seller attempt direct chat.
   - *Expectation*: Access denied (4xx ERR).
2. **Post-Completion Chat**: Rider/Buyer attempt to chat after verification.
   - *Expectation*: Forbidden (403 ERR).

## Scenario 3: Idempotency
1. **Double Payout**: Admin triggers verification twice rapidly.
   - *Expectation*: First succeeds, second returns error ("Already verified").

## Scenario 4: Webhook Replay
1. **Campay Re-send**: Webhook re-sends with same transaction ID.
   - *Expectation*: System ignores due to unique index on `txnid`.
