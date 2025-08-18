# Dataspeed

**Dataspeed** is a VTU (Virtual Top Up) web application for purchasing data, airtime, TV subscriptions, and bill payments in Nigeria. The platform is designed for speed, reliability, and secure wallet transactions.

---

## Project Overview

- **Purpose:**  
  Provide a seamless, secure, and scalable platform for users to buy data, airtime, pay bills, and manage TV subscriptions (MTN, Airtel, Glo, 9mobile, DStv, GOtv, Startimes, etc.) with instant settlements and wallet funding.

- **Target Users:**  
  Nigerian consumers, resellers, and businesses needing fast VTU services.

- **Core Features:**  
  - User registration, login, and password/PIN management  
  - Wallet funding and transfers  
  - Airtime and data purchase  
  - TV subscription payments  
  - Transaction history and receipts  
  - Referral system and rewards  
  - KYC verification  
  - Admin dashboard for user, transaction, and notification management  
  - Real-time notifications and webhooks  
  - Secure API integrations (Ebills, Billstack, etc.)

---

## Technology Stack

- **Backend:** PHP (procedural + utility functions)
- **Frontend:** Bootstrap 5 (CDN), custom CSS, minimal JS
- **Database:** MySQL
- **Email:** PHPMailer via SMTP
- **APIs:** Ebills, Billstack, KoraPay (KYC)
- **Security:** Session-based authentication, input validation, planned CSRF and brute-force protection

---

## Directory Structure

- `/admin` — Admin dashboard, settings, notifications, activity log
- `/public/pages` — User-facing pages (login, register, buy airtime/data, transactions, profile, KYC, etc.)
- `/functions` — PHP utility scripts (sendMail, OTP, API helpers, etc.)
- `/config` — Configuration files
- `/cache` — Token and balance caching
- `/webhooks` — Webhook endpoints for real-time updates
- `/test` — Test scripts and utilities
- `/schema` — Database schema and migrations
- `/public/assets` — CSS, JS, fonts, images

---

## Design Philosophy

- **Minimal, functional UI:**  
  Red and ash color scheme, clarity-first layouts, responsive design.
- **Performance-focused:**  
  Fast page loads, server-side pagination, caching for heavy API calls.
- **Security-first:**  
  Input validation, session management, planned CSRF and brute-force protections.

---

## References & Resources

- **Figma Prototype:**  
  [Payment App Fintech BNPL](https://www.figma.com/proto/b4pCL3Rx5monu7krNL0n8c/PAYMENT-APP%2F-Fintech%2FBNPL-Payments-(Community)?node-id=0-1&t=dytT6oViyyYkVzms-1)
- **KYC API Docs:**  
  [KoraPay Nigeria NIN](https://developers.korapay.com/docs/nigeria-nin)
- **CodePen UI Snippets:**  
  [Codepen](https://codepen.io/Abdullahi-Kabri/pen/GgJGazV)

---

## Maintenance & Future Improvements

See [`to-do.md`](to-do.md) for planned security, UX, and feature upgrades.