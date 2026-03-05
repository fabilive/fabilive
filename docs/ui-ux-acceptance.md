# UI/UX Acceptance Checklist — A8

## Responsive Layout
- [x] All pages render without horizontal overflow on mobile (375px)
- [x] Touch targets are >= 44px height
- [x] Tables scroll horizontally on small screens
- [x] Product grid adapts: 1 col mobile, 2 small, 3 tablet, 4 desktop
- [x] Navigation menu usable on mobile

## Images
- [x] All images have `max-width: 100%`
- [x] Product images use `object-fit: cover`
- [x] `loading="lazy"` attribute recommended for below-fold images

## Forms
- [x] Input fields have min-height 44px
- [x] File upload areas styled consistently
- [x] Buttons stack vertically on small screens

## Chat
- [x] Voice note player has play button + duration display
- [x] "Delivery Agent" label (not "delivery boy")
- [x] Chat threads hide correctly after terminal status

## States
- [x] Empty state styling available (`.empty-state`)
- [x] Loading spinner utility (`.loading-spinner`)

## Admin
- [x] Financial report tables wrapped in `.table-responsive-auto`
- [x] Action buttons spaced properly (`.action-buttons`)

## Print
- [x] Navigation/sidebar hidden on print
- [x] Tables not clipped on print

## Build
- [x] No Vite/build errors introduced
- [x] Existing tests remain green
