# Clinic Invoice System (CIS) — Enterprise Medical Practice & POS Billing

A modern, resilient, and compliant **Clinic Invoice & Front-Desk POS Billing System** tailored for Malaysian and regional general practitioner (GP) clinics, dental surgeries, and specialist ambulatory practices. Built with **Laravel 12**, **Tailwind CSS**, and modern reactive Blade components, seamlessly unified with **CentraFlow Identity Hub** for enterprise Single Sign-On (SSO).

> 🌐 **Live Interactive Preview (GitHub Pages):**  
> **[https://mralif93.github.io/clinic-invoice-system/](https://mralif93.github.io/clinic-invoice-system/)**

---

## 🌟 Key Highlights & Core Capabilities

- **🚀 Front-Desk POS Generator**:
  - **Quick Catalog Table with Multi-Select Checkboxes**: Search and filter 50+ master items (Consultations, Medications, Minor Procedures, Lab Tests) organized by category tabs (`All`, `Consultation`, `Medication`, `Procedure`, `Lab`) with real-time text search and 10-row scrollable viewport.
  - **Bidirectional Item Sync**: Toggling catalog checkboxes automatically synchronizes with active invoice line items and recalculates totals instantly.
  - **1-Click Patient & Attending Physician Pickers**: Quick search modal for registered patients with vital allergy alerts (e.g. *Penicillin, NSAIDs*) and consulting doctors.
  - **Front-Desk Tender Settlement**: Supports `Cash` (with change calculator and quick buttons), `EDC Terminal Card`, `DuitNow QR Pay`, `Online Transfer`, and `Insurance Panel / GL`.
- **🔐 CentraFlow Central Single Sign-On (SSO) & Single Logout (SLO)**:
  - Enterprise identity management integrated with **CentraFlow Identity Hub (`:8004`)** via OAuth 2.0 Authorization Code Grant (`invoice:manage invoice:read` scopes).
  - Central Single Sign-Out (SLO) with dismissible emerald logout alerts.
  - Standardized modern UI with dark/light mode toggle and responsive mobile drawer.
- **🇲🇾 Malaysian SST Act 2018 Compliant**:
  - Flexible Service Tax rate configurations (`0% Exempt`, `6% SST`, `8% Standard SST`).
  - Correct healthcare statutory handling (clinical consultations, prescription drugs, and essential diagnostics are 0% tax exempt).
- **🖨️ Dual Print Engine with On-Screen Preview**:
  - **80mm ESC/POS Thermal Slip**: Tailored for front-desk receipt roll printers with clinic SSM registration, tax breakdown, and patient allergy badges.
  - **Formal A4 Tax Invoice**: Standardized professional medical invoice with patient details, doctor attribution, and itemized calculations.
  - *All print & PDF triggers launch an interactive on-screen modal preview before physical printing.*
- **👥 Multi-Role Clinical Segregation (RBAC)**:
  - **👑 Administrator / Medical Director**: Executive revenue velocity tracking (`Today`, `Weekly`, `Monthly`, `Yearly`), treatment & drug master catalog, EDC bank reconciliation, tamper-proof activity audit trails, and invoice void authorizations.
  - **💳 Cashier / Front-Desk Nurse**: Fast-lane POS billing, walk-in patient registration with allergy tagging, and daily cash drawer balancing.
- **📊 Real-time Financial Reconciliation**:
  - Daily cash drawer counter balance (system vs. physical count with variance tracking).
  - Bank card EDC batch settlement for merchant terminals.
  - Accounts Receivable (AR) debt aging buckets (`0-30`, `31-60`, `61-90`, `90+` days).
- **🛡️ Governance & Security**: 100% immutable audit log trail recording actor, timestamp, IP address, user-agent, and payload changes for all billing events.

---

## 📖 Documentation & Architecture Guides

Detailed specifications and operating guidelines:
- 👉 **[User Roles & Operating Workflows Guide](docs/user_roles_and_workflows.md)**
- 👉 **[CentraFlow SSO Integration Guide](docs/subsystem-auth-guide.md)**
- 👉 **[System Requirements Specification & Test Plan (SRS)](docs/requirements-system-specifications.md)**

### Quick Role Permissions Matrix

| Operational Feature | 👑 Administrator / Doctor | 💳 Cashier / Front-Desk |
| :--- | :---: | :---: |
| **Authentication via CentraFlow SSO** | ✅ | ✅ |
| **POS Invoice Generation & Multi-Select Catalog** | ✅ | ✅ |
| **Payment Settlement** (Cash, EDC Card, QR, Panel) | ✅ | ✅ |
| **80mm & A4 Modal Preview & Print** | ✅ | ✅ |
| **Patient Registration & Allergy Alerts** | ✅ | ✅ |
| **Daily Cash Drawer Balancing** | ✅ (All Counters) | ✅ (Own Counter) |
| **Item & Drug Catalog Maintenance** | ✅ | ❌ (Hidden) |
| **Bank Reconciliation (EDC Terminal Matches)** | ✅ | ❌ (Hidden) |
| **Executive Revenue & Aging Debt Analytics** | ✅ | ❌ (Hidden) |
| **Invoice Voiding & Cancellation** | ✅ (With Justification) | ❌ (Blocked) |
| **Activity Audit Trail Inspection** | ✅ | ❌ (Hidden) |
| **Clinic Profile & SSM Identification** | ✅ | ❌ (Hidden) |

---

## 💻 Tech Stack & Design System

- **Backend**: PHP 8.2+ & Laravel 12 (SQLite / MySQL)
- **SSO Identity Provider**: CentraFlow OAuth 2.0 Server (`http://localhost:8004`)
- **Frontend**: Blade Components, Vanilla JavaScript, Tailwind CSS (Dark/Light mode)
- **Typography & Icons**: Plus Jakarta Sans, JetBrains Mono, Boxicons
- **Design Archetype**: Standardized shared design system matching HRMS (`:8001`) and PayFlow MY (`:8002`) with deep indigo gradient hero, responsive drawer navigation, and accessible dismissal alerts.

---

## ⚡ Getting Started Locally

```bash
# 1. Clone repository & navigate to Laravel root
git clone https://github.com/mralif93/clinic-invoice-system.git
cd clinic-invoice-system/cis

# 2. Install PHP and Node dependencies
composer install
npm install

# 3. Setup Environment
cp .env.example .env
php artisan key:generate

# 4. Configure CentraFlow SSO in .env
# CENTRAFLOW_HOST=http://localhost:8004
# CENTRAFLOW_CLIENT_ID=9d12a101-0003-4000-8000-000000000003
# CENTRAFLOW_CLIENT_SECRET=invoice_secret_centraflow_2026
# CENTRAFLOW_REDIRECT_URI=http://localhost:8003/auth/callback
# CENTRAFLOW_SCOPES="invoice:manage invoice:read"

# 5. Run Migrations & 50-Record Clinical Seeders
php artisan migrate:fresh --seed

# 6. Compile Assets & Launch Dev Server on Port 8003
npm run build
php artisan serve --port=8003
```

### Pre-Configured Ecosystem Ports & Credentials

| Sub-System | Local URL | Role | Email | Password |
| :--- | :--- | :--- | :--- | :--- |
| **CentraFlow (Identity Hub)** | `http://localhost:8004` | Super Admin | `superadmin@centraflow.local` | `password` |
| **PulseHR (HRMS)** | `http://localhost:8001` | HR Admin | `admin@hrms.test` | `password` |
| **PayFlow MY (Payroll)** | `http://localhost:8002` | Payroll Officer | `admin@payflow.my` | `password` |
| **ClinicFlow (CIS)** | `http://localhost:8003` | Administrator | `admin@clinic.my` | `password` |
| **ClinicFlow (CIS)** | `http://localhost:8003` | Cashier / Staff | `cashier@clinic.my` | `password` |
