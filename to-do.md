# Project Development & Security Improvement Blueprint

## 1. High Priority (Security, Core Functionality, Critical UX Fixes)

- **Session lock timeout** for the admin page.
- **Passkey authentication** for admin login.
- Fix **brute force attempts** in the Airtime, Data, and TV pages.
- **Account lock** after 5 wrong PIN attempts (auto logout even if already logged in).
- **Sanitize inputs** when using quick amount buttons. ✔️
- **Sanitize & validate** username field. ✔️
- **Username already taken** flaw logic fix. ✔️
- **Token caching** for Ebills API. ✔️
- **Handle response codes** efficiently.
- **Query to verify smartcard number** before processing TV subscription.
- **Fix primary and secondary button classes**.
- Use **Synk** for vulnerability detection.
- **Use webhooks** for real-time updates.
- **CSRF protection** for all admin/user forms and AJAX endpoints.
- **Session regeneration** after privilege changes (password, role, etc.).
- **Rate-limiting/lockout** for repeated failed login attempts (admin & user).
- **Password complexity and reuse prevention**.
- **PIN lock logic** enforced everywhere.
- **Input validation/sanitization** for all endpoints (notifications, settings, referral codes, etc.).
- **Sensitive error handling** (no stack traces/SQL errors in JSON responses).
- **Authorization checks** (role/permission, not just session presence).
- **API token/secret rotation/invalidation** on logout/credential change.
- **Critical security event notifications** (failed login, suspicious activity, etc.).
- **File upload validation** (type, size, malware scan).
- **Webhook request verification** (origin, signature).
- **Directory traversal protection** in file includes.
- **Immediate logout** for locked/banned users/admins.
- **Feature toggles enforced at API level**.
- **SMTP fallback/retry logic** for email delivery.
- **Audit logging** for all sensitive changes (settings, pricing, before/after diffs).
- **Consistent API response codes** (errors not 200).
- **Content Security Policy (CSP) headers** for front-end.

---

## 2. Medium Priority (UX/UI Consistency, User Dashboard Improvements)

- **Improve UX** and reduce lower spacing of the Buy Airtime container. ✔️
- **Detect network** based on Nigerian phone number format. ✔️
- **Consistency** across the entire UI/UX. ✔️
- Improve **transfer page** to transfer to the exact user. ✔️
- **Transactions** should fetch dynamically in the dashboard without reload. ✔️
- In Buy Data and TV **confirmation dialog**, add row for the `plan_name` (from DB). ✔️
- **Sort plans** by daily, weekly, and monthly (anything < 7 days is daily). ✔️
- Add **updated_at** column to `service_plans` table. ✔️
- Add **global constants file**.
- **Add global TV percentage setting**. ✔️
- **Enhance notifications** for admin for all user transactions.
- **Notification sorting** by date fixes. ✔️
- **Automatically log out** user when account is locked.
- **Make airtime percentage** reflect on users page. ✔️
- **If features are toggled** in admin, reflect on user dashboard immediately. ✔️

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

Collecting workspace informationHere are recommendations and captured flaws based on your workspace, recent conversations, and the current state of your codebase:

## Security & Logical Flaws

1. **CSRF Protection Missing in Admin/API**

   - Most admin and user-facing forms and AJAX endpoints (e.g., users.php, update-passcodes.php) do not implement CSRF tokens. This leaves the app vulnerable to cross-site request forgery.

2. **Session Management Weaknesses**

   - Session lock timeout is listed but not fully implemented for admin and user dashboards. Sessions should auto-expire after inactivity.
   - No session regeneration after privilege changes (e.g., password update, role change).

3. **Brute Force Protection**

   - No rate-limiting or lockout on repeated failed login attempts for both admin (login.php) and users. This is a risk for brute force attacks.

4. **Password & PIN Policy**

   - Password minimum length is enforced, but no complexity (uppercase, number, symbol) or reuse prevention.
   - PIN logic: Account lock after 5 wrong attempts is listed but not enforced everywhere.

5. **Input Validation & Sanitization**

   - Some endpoints sanitize inputs (e.g., username, quick amount), but others (e.g., notifications, settings, referral codes) may accept unsanitized data, risking SQL injection or XSS.

6. **Sensitive Data Exposure**

   - Error messages sometimes leak internal details (e.g., stack traces, SQL errors) in JSON responses.

7. **Authorization Checks**

   - Some admin APIs (e.g., users.php) rely only on session presence, not on role/permission checks. This could allow privilege escalation if session hijacked.

8. **Token/Secret Management**

   - API tokens (eBills, Billstack) are cached in files but not rotated or invalidated on logout or credential change.

9. **Notification Logic**

   - Notifications for critical actions (e.g., password/PIN change, account lock) are sent, but not all security events (e.g., failed login, suspicious activity) trigger alerts.

10. **File Uploads**

    - Bulk upload forms (listed in to-do) should validate file type, size, and scan for malware.

11. **Webhooks**

    - Webhook endpoints (e.g., billstack-deposit.php) do not verify request origin or signature, risking spoofed requests.

12. **Directory Traversal**

    - Some file includes use relative paths without strict validation, which could allow directory traversal if exploited.

13. **Logout Logic**

    - Users and admins are not always logged out immediately when their account is locked or banned.

14. **Feature Toggles**

    - Feature toggles are reflected on dashboards but may not be enforced at API level, risking unauthorized access.

15. **Email Delivery**

    - SMTP reliability is listed as a concern; fallback or retry logic is not implemented.

16. **Audit Logging**

    - Admin actions are logged, but not all sensitive changes (e.g., settings, pricing) are tracked with before/after diffs.

17. **API Response Codes**

    - Some APIs return 200 for errors (e.g., webhook failures), which can confuse clients and mask issues.

18. **Front-End Security**
    - No Content Security Policy (CSP) headers set; risk of XSS via injected scripts.

---

You can add these to your improvement blueprint and share with your client for prioritization.
