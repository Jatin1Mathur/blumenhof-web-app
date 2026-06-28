# Frontend component plan for blumenHof

## Scope
This document describes the frontend-only components that were introduced to reflect the provided product/design direction for the florist management experience.

## Frontend components created

### Layout components
- Header navigation with brand identity and theme toggle
- Footer with shop-style structure
- Page shell and content spacing consistent with the florist-brand design

### Content section components
- SectionTitle widget for reusable page headings and callouts
- InfoCard widget for structured feature or company information sections
- FeatureList widget for bullet-style capability lists
- ServiceCard widget for service or workflow cards

### Design alignment considerations
- The UI was shaped around a clean florist-shop aesthetic rather than a generic web app look.
- Content hierarchy was kept simple and editorial to match the document style.
- Reusable blocks were introduced so the same visual system can support future pages without duplicating markup.
- The implementation remains frontend-only and does not introduce backend-specific business logic.

## Backend requirements for the frontend changes
The frontend now expects the following backend support for future implementation:

1. Content management endpoints
   - API or server-rendered endpoints for page content such as:
     - about page hero copy
     - info cards
     - feature list items
     - service cards

2. Dynamic navigation data
   - Backend should provide header/footer navigation items and page labels.

3. Media and branding data
   - Brand name, logo, subtitle, and theme-related assets should be configurable from backend.

4. Form submission handling
   - Contact form, signup, login, password reset, and verification flows already rely on backend validation and email services.

5. Authentication state
   - Current user identity and access state must continue to be supplied by the existing Yii user component.

## Why this frontend design is aligned with the document
The current frontend implementation reflects the document in the following ways:
- It is positioned as an internal florist-management platform, not a public ecommerce storefront.
- It uses a calm, premium, floral-inspired visual system.
- It structures the page into clear informational sections that map naturally to the about/introduction content.
- It uses reusable components so that the design can scale as more pages are added.

## Notes for the backend engineer
Please provide the following payloads or endpoints for the frontend to consume:
- about-page content payload
- navigation payload
- branding payload
- contact form submission endpoint
- authentication-related endpoints already covered by the current frontend flow
