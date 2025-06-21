--- FIXES ---

- Fixing the primary, secondary btn classes.
- Fixing the balance toggle functionality.✔️
- Finding relevant icons for transactions. ✔️
- Eye toggle icon in the forgot password page and also the resend timeout. ✔️

--- FEATURES ---

- Quick amount issues in buy airtime page.✔️
- No back to OTP page in the register page.
- Also add the login with username functionality.✔️
- Notification sort by date fixes.
- User's account setting failure.✔️
- Sanitize inputs when using quick amount buttons. ✔️
- Sanitizing and validating username field.✔️
- Fixing the email sent to...✔️
- Full fixes of TV subscription page.
- Forgot PIN functionality. ✔️
- Username step in registration form.✔️
- Adding action buttons (e.g., Transfer to Friend)✔️
- In transactions page use the icon of the service provider instead.
- Handle response codes efficiently.
- Use webhooks for updates.
- Query to verify smartcard number.
- Acccount should be locked after 5 wrong PIN attempts.
- An upload form for variations.
- Username field for updates.
- Animation for account balance.
- Add a subdomain for the admins.
- A dashboard panel for admin

--- NOTIFICATION MODULES ---

1. **Transactions**
   - Deposits (successful, failed) ✔️
   - Withdrawals (initiated, successful, failed)
   - Transfers (sent, received, failed) ✔️
   - Payments (bills, airtime, data, TV, etc.) ✔️

2. **Account Security**
   - Login (new device/location)
   - Password change ✔️
   - PIN set or changed ✔️
   - KYC status updates (submitted, approved, rejected)
   - Profile updates (email, phone, photo) ✔️

3. **Referrals & Rewards**
   - Referral reward earned ✔️
   - Referral status changed (pending, approved, rejected) ✔️

4. **System & Promotions**
   - System announcements 
   - Promotional offers
   - Maintenance alerts

5. **Bank Account Actions**
   - Virtual account created ✔️
   - Bank account linked/unlinked 
   - Failed account verification

7. **General Errors or Alerts**
   - Failed actions (e.g., failed transaction, failed KYC)
   - Unusual activity detected


--- SECURITY ISSUES -----

General Security
CSRF Protection: Use CSRF tokens for all AJAX POSTs.
Replay Protection: Ensure each transaction/request ID is unique and not reused.
Strict CORS Policy: Only allow requests from your own domain.
Session Management: Invalidate sessions on logout or after inactivity.
Error Handling: Never leak sensitive info in error messages.