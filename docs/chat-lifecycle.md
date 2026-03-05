# Chat Lifecycle Management

Fabilive ensures that delivery-related communication is temporary and secure.

## Communication Pairs

| Pair | Permission | Condition |
|------|------------|-----------|
| Rider <-> Buyer | Allowed | When `DeliveryJob` is active. |
| Rider <-> Seller | Allowed | When `DeliveryJob` is active and seller is a participant. |
| Buyer <-> Seller | **Forbidden** | Direct chat is blocked server-side for orders. |

## Visibility Rule

- Chats are "soft-hidden" when a job reaches a terminal status (`delivered_verified`, `returned`, `cancelled`).
- Participants see "This chat session has ended" and cannot send further messages.
- **Admin** maintains full archive access for dispute resolution.

## Implementation

- **Middleware**: `EnsureDeliveryChatAccess` checks the `hidden_at` field and job status.
- **Lifecycle Service**: `DeliveryChatLifecycleService` handles the `hidden_at` updates.
- **Storage**: `delivery_chat_threads` mapping table connects jobs to message threads.
