# Clinic Invoice System (CIS) — Enterprise Medical Practice & POS Billing

A modern, resilient, and compliant **Clinic Invoice & Front-Desk POS Billing System** tailored for Malaysian and regional general practitioner (GP) clinics, dental surgeries, and specialist ambulatory practices. Built with **Laravel 12**, **Tailwind CSS**, and modern reactive Blade components.

---

## 🌟 Key Highlights & Core Capabilities

- **🚀 Front-Desk POS Generator**:
  - **Quick Catalog Table with Multi-Select Checkboxes**: Search and filter 50+ master items (Consultations, Medications, Minor Procedures, Lab Tests) organized by category tabs (`All`, `Consultation`, `Medication`, `Procedure`, `Lab`) with real-time text search and 10-row scrollable viewport.
  - **Bidirectional Item Sync**: Toggling catalog checkboxes automatically synchronizes with active invoice line items and recalculates totals instantly.
  - **1-Click Patient & Attending Physician Pickers**: Quick search modal for registered patients and consulting doctors.
  - **Front-Desk Tender Settlement**: Supports `Cash`, `EDC Terminal Card`, `DuitNow QR Pay`, `Online Transfer`, and `Insurance Panel / GL` with change calculation and exact-amount quick buttons.
- **🇲🇾 Malaysian SST Act 2018 Compliant**:
  - Flexible Service Tax rate configurations (`0% Exempt`, `6% SST`, `8% Standard SST`).
  - Correct healthcare statutory handling (clinical consultations, prescription drugs, and essential diagnostics are 0% tax exempt).
- **🖨️ Dual Print Engine with On-Screen Preview**:
  - **80mm ESC/POS Thermal Slip**: Tailored for front-desk receipt roll printers with clinic SSM registration, tax breakdown, and patient allergy badges.
  - **Formal A4 Tax Invoice**: Standardized professional medical invoice with patient details, doctor attribution, and itemized calculations.
  - *All print & PDF triggers launch an interactive on-screen modal preview before physical printing.*
- **👥 Multi-Role Clinical Segregation (RBAC)**:
  - **👑 Administrator / Medical Director**: Executive revenue velocity tracking (`Today`, `Weekly`, `Monthly`), treatment & drug master catalog, EDC bank reconciliation, tamper-proof activity audit trails, and invoice void authorizations.
  - **💳 Cashier / Front-Desk Nurse**: Fast-lane POS billing, walk-in patient registration with allergy tagging, and daily cash drawer balancing.
  - **Role Workflows**: In-app modal documentation tailored strictly to the authenticated user's active role.
- **📊 Real-time Financial Reconciliation**:
  - Daily cash drawer counter balance (system vs. physical count with variance tracking).
  - Bank card EDC batch settlement.
  - Accounts Receivable (AR) debt aging buckets (`0-30`, `31-60`, `61-90`, `90+` days).
- **🛡️ Governance & Security**: 100% immutable audit log trail recording actor, timestamp, IP address, user-agent, and payload changes for all billing events.

---

## 📖 User Roles & Operating Workflows

Comprehensive step-by-step guides, role comparison matrices, and process flowcharts are documented in:
👉 **[User Roles & Workflows Guide](user_roles_and_workflows.md)**

### Quick Role Permissions Matrix

| Feature | 👑 Administrator / Doctor | 💳 Cashier / Front-Desk |
| :--- | :---: | :---: |
| **POS Invoice Generation** | ✅ | ✅ |
| **Catalog Checkbox & Live Filtering** | ✅ | ✅ |
| **Payment Settlement** (Cash, EDC Card, QR, Panel) | ✅ | ✅ |
| **80mm & A4 Modal Preview & Print** | ✅ | ✅ |
| **Patient Registration & Allergy Alerts** | ✅ | ✅ |
| **Daily Cash Drawer Balancing** | ✅ | ✅ (Own Drawer) |
| **Item & Drug Catalog Maintenance** | ✅ | ❌ (Hidden) |
| **Bank Reconciliation (EDC Terminal Matches)** | ✅ | ❌ (Hidden) |
| **Executive Revenue & Aging Debt Analytics** | ✅ | ❌ (Hidden) |
| **Invoice Voiding & Cancellation** | ✅ (With Justification) | ❌ |
| **Activity Audit Trail Inspection** | ✅ | ❌ (Hidden) |
| **Clinic Profile & SSM Identification** | ✅ | ❌ (Hidden) |

---

## 💻 Tech Stack & Design System

- **Backend**: PHP 8.2+ & Laravel 12 (SQLite / MySQL)
- **Frontend**: Blade Components, Vanilla JavaScript, Tailwind CSS (Dark/Light mode)
- **Typography & Icons**: Plus Jakarta Sans, JetBrains Mono, Boxicons
- **Design Archetype**: Dark Slate & Indigo accent (`#4f46e5`), high-density data tables, frosted cards, and responsive mobile-first dual layouts.

---

## ⚡ Getting Started Locally

```bash
# 1. Clone repository & install dependencies
git clone https://github.com/mralif93/clinic-invoice-system.git
cd clinic-invoice-system/cis
composer install
npm install

# 2. Setup Environment
cp .env.example .env
php artisan key:generate

# 3. Run Migrations & 50-Record Clinical Seeders
php artisan migrate:fresh --seed

# 4. Compile Assets & Launch Dev Server
npm run build
php artisan serve --port=8001
```

### Default Credentials

| Role | Email | Password | Staff ID |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin@clinic.my` | `password` | `ADM-001` |
| **Cashier / Staff** | `cashier@clinic.my` | `password` | `STF-001` |
