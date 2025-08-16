# Project Development & Improvement Blueprint

## 1. High Priority (Security, Core Functionality, Critical UX Fixes)
- **Session lock timeout** for the admin page.  
- **Passkey authentication** for admin login.  
- Fix **brute force attempts** in the Airtime, Data, and TV pages.  
- **Account lock** after 5 wrong PIN attempts (auto logout even if already logged in).  ✔️
- **Sanitize inputs** when using quick amount buttons.  
- **Sanitize & validate** username field.  ✔️
- **Username already taken** flaw logic fix.
- **Token caching** for Ebills API.  
- **Handle response codes** efficiently.  
- **Query to verify smartcard number** before processing TV subscription.  
- **Fix primary and secondary button classes**.  
- Use **Synk** for vulnerability detection.  
- **Use webhooks** for real-time updates.  

---

## 2. Medium Priority (UX/UI Consistency, User Dashboard Improvements)
- **Improve UX** and reduce lower spacing of the Buy Airtime container.  
- **Detect network** based on Nigerian phone number format.  
- **Consistency** across the entire UI/UX.  
- Improve **transfer page** to transfer to the exact user.  
- **Transactions** should fetch dynamically in the dashboard without reload.  
- In Buy Data and TV **confirmation dialog**, add row for the `plan_name` (from DB).  
- **Sort plans** by daily, weekly, and monthly (anything < 7 days is daily).  
- Add **updated_at** column to `service_plans` table.  
- Add **global constants file**.  
- **Add global TV percentage setting**.  
- **Enhance notifications** for admin for all user transactions.  
- **Notification sorting** by date fixes.  
- **Automatically log out** user when account is locked.  
- **Make airtime percentage** reflect on users page.  
- **If features are toggled** in admin, reflect on user dashboard immediately.  

---

## 3. Admin Dashboard & Notifications
- **Add notifications section** in admin dashboard with auto-refresh.  
- **Add ding sound** for notifications in admin notifications page.  
- Replace **KPI card background colors** with lighter shades.  
- **Animation** for account balance.  
- **Email loader animation** for better UX.  
- **Use a different SMTP** for email reliability.  
- **Subdomain** for admin panel.  
- **Dashboard panel** for admin with service management tools.  

---

## 4. UI Enhancements & Directory Structure
- Restructure **project directory** and rename files for consistency & standards.  
- In Transactions page, **use service provider icon** instead of generic icon.  
- **Improve responsiveness** of referrals table and its buttons.  
- **Full fix** of TV subscription page.  
- **No back to OTP page** in registration process.  
- **On transaction click**, redirect to receipt/success page with reference.  
- **General DB cleanup** (truncate test data, ensure naming consistency).  

---

## 5. Additional Features
- **Animation for account balance** display.  
- **Upload form** for variations (bulk upload support).  
- **Use different SMTP** for better email deliverability.  
- **Handle response codes** for all API calls.  
- **Use webhooks** for status updates.  

---
