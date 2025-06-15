# Dataspeed
 A VTU airtime web application for purchasing data, airtime, and bill payment.

## **How `send-otp.php` and `sendMail.php` Work Together**  

### **1️⃣ `send-otp.php` – Generates OTP and Calls `sendMail.php`**
- This script **handles the OTP generation** and **stores it in the database**.
- After storing the OTP, it **calls `sendMail()`** to send an email.

### **2️⃣ `sendMail.php` – Sends the Email**
- This script **uses PHPMailer** to send the email.
- It **receives the email body** and **sends the email via SMTP**.

---

## **📌 How They Work Step-by-Step**
### **1️⃣ User Requests OTP (JavaScript)**
- The front-end (JavaScript) sends an AJAX request to `send-otp.php` when the user enters an email.

### **2️⃣ `send-otp.php` Handles the Request**
✔️ Extracts the user's email from `$_POST`.  
✔️ Validates the email format.  
✔️ **Generates a random 6-digit OTP.**  
✔️ Stores the OTP **in the database** (`otp_codes` table).  
✔️ **Prepares the email body** (HTML format).  
✔️ **Calls `sendMail()` function to send the email.**  
✔️ Returns a JSON response to the front-end.

### **3️⃣ `sendMail.php` Sends the Email**
✔️ Uses **PHPMailer** to send an email via **SMTP**.  
✔️ Configures SMTP settings (`host`, `username`, `password`, `encryption`, `port`).  
✔️ **Sends the email to the user** with the OTP.  
✔️ Logs errors if the email fails.  

---

## **🔗 Connection Between Both Files**
- `send-otp.php` **generates and stores OTP** ⏩ calls `sendMail()` from `sendMail.php`.
- `sendMail.php` **takes care of actually sending the email**.

💡 **`send-otp.php` is like the manager, `sendMail.php` is like the delivery guy.** 🚀  

---

## **🛠 Example Workflow**
1️⃣ **User enters email & clicks "Send OTP"**  
2️⃣ `send-otp.php` generates OTP & stores it  
3️⃣ Calls `sendMail($email, $subject, $body)`  
4️⃣ `sendMail.php` sends the email via SMTP  
5️⃣ **User receives OTP email** 🎉  

Let me know if you need more clarification! 🚀


https://www.figma.com/proto/b4pCL3Rx5monu7krNL0n8c/PAYMENT-APP%2F-Fintech%2FBNPL-Payments-(Community)?node-id=0-1&t=dytT6oViyyYkVzms-1

## KYC API Links
https://developers.korapay.com/docs/nigeria-nin


## Codepen
https://codepen.io/Abdullahi-Kabri/pen/GgJGazV