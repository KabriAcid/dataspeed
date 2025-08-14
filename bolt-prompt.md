## Admin Dashboard Build Prompt (Bolt AI)

Objective

- Build a premium-looking Admin Dashboard for a VTU app with a modern sidebar + card UI, subtle animations, and vanilla JavaScript only.
- Keep ALL admin code self-contained under the admin directory: admin/pages, admin/includes, admin/assets, admin/api.
- Use existing login logic pattern: AJAX-based login with a single API file (validate + authenticate), no OTP.
- Use showToasted(message, type) for ALL feedback (no inline form errors).

Tech and constraints

- PHP 8 + PDO (existing app).
- Vanilla JS (no jQuery). Optional: Bootstrap 5 via CDN is allowed; provide Nucleo Icons for UI icons.
- No OTP for admin login.
- Sessions: store only admin_id.
- Clean JSON responses from APIs; showToasted for success/error/info feedback.
- Keep URLs under /dataspeed/admin/...

Directory layout (must)

- admin/
  - login.php, logout.php, dashboard.php
  - api/
    - auth.php (single file; handles validation + login)
    - users.php (list/view/create/update/toggle lock)
    - pricing.php (plans list/update, airtime markup update)
    - settings.php (read/update basic settings)
    - stats.php (dashboard counters)
  - assets/
    - css/admin.css (premium theme, animations)
    - js/admin.js (sidebar toggle, AJAX helpers, showToasted usage, inline edit)
    - img/… (optional)
  - includes/
  - header.php (HTML head, CSS links; include Nucleo Icons)
  - topbar.php (admin top navbar)
  - sidebar.php (responsive sidebar)
  - footer.php
  - scripts.php (Bootstrap CDN, Nucleo Icons, admin.js)
  - users/ (optional page shells if not SPA-like)
  - transactions/ (optional)
  - settings/
    - index.php (UI shell using API)
- Do not place admin code outside admin/.

Auth flow (must)

- login.php renders a simple, elegant form (email + password).
- On submit, vanilla JS fetch POST to admin/api/auth.php with action=validate to pre-check inputs (email format, presence); on failure, call showToasted('message','error'). Then call action=login to authenticate.
- On success: set $\_SESSION['admin_id'] and redirect to dashboard.php; use showToasted for success/error.
- logout.php destroys session and redirects to login.php.
- Enforce gate on all admin pages: if empty($\_SESSION['admin_id']) redirect to login.php.

UI/UX guidelines

- Premium aesthetics: clean typography (Plus Jakarta Sans), consistent spacing, soft shadows, rounded cards, subtle gradients.
- Sidebar: collapsible on mobile (slide-in/out), active state highlighting, icons + labels.
- Cards: animated hover (translateY, shadow), color accents for different metrics.
- Animations: CSS transitions only; keep tasteful and fast.
- Dark-on-light theme; accessible contrast.
- Keep things snappy: skeleton loaders/spinners on async lists.
- Top navbar (on every admin page): sticky bar containing
  - Left: sidebar toggler (hamburger), dynamic page title/breadcrumb.
  - Right: optional search, notifications bell, and admin avatar dropdown (Profile, Logout → links to admin/logout.php).
  - Must remain visible on scroll; works on mobile (collapses items into a menu if needed).
- Use modals for create/edit forms across pages (Users Add/Edit, Pricing richer edits, Settings quick edits):
  - Open modal with prefilled data for Edit; empty for Create.
  - Submit via AJAX; disable submit button while pending; showToasted for success/error; close modal on success and refresh affected list/row.
  - No inline validation messages; rely on toasts only.
- Visual language: follow existing design philosophy and tokens from public/assets/css/style.css (variables like --primary, --primary-light, fonts Quicksand/DM Sans). Use soft neutrals for surfaces, primary accents for CTAs/highlights, and consistent rounded radii/shadows.

Pages and features

1. Dashboard (admin/dashboard.php)

- KPI cards (use Nucleo Icons):
  - Active Users (icon: ni ni-single-02)
  - Today’s Revenue (₦) (icon: ni ni-money-coins)
  - New Users Today (icon: ni ni-circle-08)
  - Total Users’ Balance (₦) (icon: ni ni-wallet-43)
- Optional secondary row: Pending Transactions or lightweight trend bars.
- Fetch metrics from admin/api/stats.php.
- No heavy charts required; simple sparkline-like bars via CSS or tiny inline SVG is fine.

2. Users management

- List with search, filter (status/kyc), pagination.
- Actions: view, edit basic fields, lock/unlock.
- Create and Edit happen in a modal (e.g., #userModal) with fields: full_name, email, phone, status (active/locked).
- View can be a read-only modal or a details pane.
- APIs: admin/api/users.php
  - GET ?action=list&query=&page=
  - GET ?action=view&id=
  - POST action=create (JSON body)
  - POST action=update (JSON body with id)
  - POST action=toggleLock (JSON body with id, lock=true/false)

3. Pricing management

- Table for service_plans with inline editable price and a global Airtime Markup field.
- Inline edit triggers POST to admin/api/pricing.php to persist.
- Each row also has an “Edit Plan” action that opens a modal with richer fields (name, network, code/slug, price). Save via AJAX.
- APIs:
  - GET ?action=plans
  - POST action=updatePlanPrice {plan_id, price}
  - POST action=updatePlan {id, name, network, code, price}
  - POST action=updateAirtimeMarkup {percentage}

4. Settings

- Basic platform settings (read/update): e.g., fee/charges, feature toggles.
- Each setting edited through a small modal; no inline forms.
- APIs: admin/api/settings.php
  - GET ?action=get
  - POST action=update {key,value} or {settings:{…}}

Vanilla JS requirements

- Use fetch with JSON; no jQuery.
- Central helper in admin/assets/js/admin.js:
  - apiFetch(url, options): wraps fetch + JSON parse + error handling; on errors, call showToasted.
  - showToasted(message, type): success/info/error (existing global helper) for all feedback (no inline errors).
  - inlineEdit(id, onSave): used by pricing table.
  - openModal(id, data?): helper to show and populate modals.
  - bindModalForm(modalEl, {endpoint, method, onSuccess}): attach submit handler that disables button, posts JSON, shows toasts, closes modal on success, and runs onSuccess (e.g., refresh list or update row).
  - topbarInit(): wires sidebar toggle, sets page title, and binds logout in avatar dropdown.

Auth API contract (single file)

- File: admin/api/auth.php
- Endpoints (action param):
  - POST action=validate {email, password}
    - 200 {success:true} or {success:false, message:'Reason for validation failure'}
  - POST action=login {email, password}
    - On success: set session, return {success:true, redirect:'dashboard.php'}
    - On failure: {success:false, message:'Invalid credentials'}
- Implementation notes:
  - Server-side validation (email format, min password length).
  - Verify hashed password; if admin locked, return error.
  - Regenerate session ID on successful login.

Other API contracts (samples)

- stats.php
  - GET: {success:true, data:{active_users, today_revenue_amount, new_users_today, total_users_balance, pending_transactions, series:{daily_transactions:[{date, amount}], bill_distribution:[{label, value}]}}}
- users.php
  - GET list: {success:true, data:{items:[…], pagination:{page,total_pages,count}}}
  - GET view: {success:true, data:{…user…}}
  - POST create/update/toggleLock: {success:true} or {success:false,message}
- pricing.php
  - GET plans: {success:true, data:{plans:[{id,name,network,price,…}], airtime_markup: number}}
  - POST updates: {success:true} or {success:false,message}
- settings.php
  - GET: {success:true, data:{…}}
  - POST: {success:true}

Security and quality

- Protect all admin pages and APIs with session check (except login and auth API).
- Sanitize inputs server-side; return clean JSON always.
- Avoid leaking stack traces. Log server errors to file.
- Use prepared statements (PDO).
- Idempotency where relevant (e.g., not critical for admin edits, but apply server-side validation).

Deliverables

- All admin files under admin/ as specified.
- Fully responsive sidebar layout with premium styling and subtle animations.
- Functional pages for Dashboard, Users, Pricing, Settings using the APIs.
- Vanilla JS-only interactions (fetch, showToasted, inline edit).
- No OTP in login; single auth API handles validate + login.

Acceptance

- Login via AJAX (validate + login) works; redirects to dashboard on success; session stores only admin_id.
- Sidebar collapses on mobile, overlays body, and animates smoothly.
- Users list paginates, filters, searches; edit and lock/unlock work.
- Pricing inline edits persist and show toasts; airtime markup updates.
- Settings read/update works and shows toasts.
- All APIs return deterministic JSON and handle errors gracefully.
- Top navbar is present on all admin pages, sticky, with working sidebar toggle and logout.
- All create/edit operations are done via modals; forms submit via AJAX; submit button disables while pending; uses showToasted; modals close on success and lists refresh.

Notes

- Reuse existing DB schema and session conventions.
- Use Bootstrap 5 + Bootstrap Icons via CDN or ship minimal equivalents in admin/assets if preferred.
- Keep CSS in admin/assets/css/admin.css and JS in admin/assets/js/admin.js.
