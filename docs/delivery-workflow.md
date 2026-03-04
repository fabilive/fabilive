# Delivery Workflow Documentation

This document describes the statuses and transitions for the Fabilive multi-seller delivery system.

## Delivery Job Statuses

| Status | Actor | Description |
|--------|-------|-------------|
| `pending_readiness` | System | Job created from order, awaiting sellers to mark items as ready. |
| `available` | System | At least one seller is ready; job is visible in the rider marketplace. |
| `assigned` | Rider | A rider has accepted the job. |
| `picked_up` | Rider | All pickup stops have been completed. |
| `delivered_pending_verification` | Rider | Rider has uploaded proof and marked as delivered. |
| `delivered_verified` | Admin | Admin has reviewed proof and verified delivery (triggers payouts). |
| `returned` | Admin | Delivery failed and items were returned. |
| `cancelled` | Admin | Job was cancelled before completion. |

## Workflow Steps

1. **Order Creation**: System creates a `DeliveryJob`.
2. **Seller Readiness**: Sellers mark items as "Ready for Pickup".
3. **Rider Acceptance**: Rider accepts job via `rider/accept/{job}`.
4. **Execution**: Rider updates stops as `arrived` and `picked_up`.
5. **Delivery**: Rider uploads photo via `rider/deliver/{job}`. Job enters `delivered_pending_verification`.
6. **Verification**: Admin verifies via `admin/delivery/verify/{job}`. Funds are released.
7. **Cleanup**: Chats are automatically hidden for participants.
