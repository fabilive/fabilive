# AI Architecture — Fabilive

## Overview
All AI features are behind **feature flags** in `config/ai.php`. No AI calls are made unless the corresponding flag is enabled via `.env`.

## Provider Configuration
```env
AI_PROVIDER=openai          # openai | gemini | anthropic
OPENAI_API_KEY=sk-...
OPENAI_MODEL=gpt-4o-mini
```

## Feature Flags
```env
AI_FEATURE_ASSISTANT=false
AI_FEATURE_LISTING_GEN=false
AI_FEATURE_PHOTO_ENHANCE=false
AI_FEATURE_ANTI_SCAM=false
AI_FEATURE_REPUTATION=false
```

## Services
| Service | Feature | Type |
|---------|---------|------|
| `AIService` | Base | Multi-provider abstraction + rate limiting + audit |
| `ListingGeneratorService` | AI2 | Title/desc/price from category + notes |
| `AntiScamService` | AI4 | Rule-based phone/pricing/text analysis |
| `ReputationService` | AI5 | Badge computation (Fast Responder/Honest/Trusted) |

## Rate Limiting
- Per user, per feature: 10/min, 100/hr, 500/day (configurable)
- Logged to `ai_audit_logs`

## Anti-Scam Signals
Stored in `scam_signals` table with:
- Phone analysis (too short, repeated/sequential digits)
- Price z-score outlier detection
- Text pattern matching (off-platform payment keywords)

## Reputation Badges
Computed from: delivery completion rates, cancellations, scam flags, complaint rates. Stored in `seller_badges`.
