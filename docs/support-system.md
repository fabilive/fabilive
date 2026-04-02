# Customer Support Agent System

## Overview
The Customer Support Agent System is a stand-alone module designed to provide role-specific (Buyer/Vendor) FAQ browsing and direct live chat Escalation for users.

It operates using an isolated set of tables to ensure zero overlap with native buyer/seller private messaging, strictly enforcing platform rules against unmediated direct communications.

## Database Models
- `SupportAgent`: Connects `admins` table to support queues. Stores `is_online` status and capacity limits for load balancing.
- `SupportFaqCategory` & `SupportFaq`: Role-specific FAQs explicitly categorizing knowledge bases.
- `SupportBotRule`: Defines rule mechanisms for Bot automations.
- `SupportConversation`: The core session of a help request.
- `SupportMessage`: Chat content including text, images, and voice notes.
- `SupportConversationEvent`: Audit trail of actions (escalations, endings, ratings).
- `SupportRating`: Post-discussion CSAT tracking.

## Conversation Lifecycle (Statuses)
1. `bot_active`: The conversation just started. Only the Bot responds.
2. `waiting_agent`: User requested live support, but no agents are currently available to pick up.
3. `assigned`: An agent has been bound to the session.
4. `ended`: Either User or Agent deliberately closed the chat session.
5. `rated`: A closed session where the user provided final ratings.

## Routes & API Endpoints

```text
GET  /support/faqs                 => SupportController@getFaqs
POST /support/bot/chat             => SupportController@botChat
POST /support/live/request         => SupportController@requestLiveSupport
POST /support/chat/send            => SupportController@sendMessage
POST /support/chat/end             => SupportController@endConversation
POST /support/chat/rate            => SupportController@rateConversation
GET  /support/chat/history         => SupportController@getChatHistory
```

## Agent Assignment Logic (Escalation)
When `/support/live/request` is invoked, the system attempts to perform an immediate load-balancing assignment:
- It uses MySQL `lockForUpdate()` within an active Transaction to prevent two concurrent requests from picking the same constrained agent slot.
- It finds an agent where `is_online = true` and current active `assigned` chats `< max_active_chats`.
- It prioritizes the agent with the **LEAST** active chats.

## Bot Rules Format
You can add rules directly via the Filament dashboard by navigating to "Support System > Bot Rules".
Matching patterns:
- **Keyword**: A comma-separated list of exact words. Match occurs if any word fires.
- **Contains (Substring)**: A comma-separated string list. Match occurs if text is present anywhere inside the incoming message.
- **Regex**: Fully parsed PCRE matching logic string.

## Adding Agents & Operations
Support Agents are essentially `admin` accounts dynamically linked to the `SupportAgent` settings. Through the admin panel under the Support System group, administrators can set specific operators "Online" and increase/decrease their individual chat capacities to handle demand.
