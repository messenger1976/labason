# DOCUMENTS

Company Knowledge–style documentation for the Labason Billing System (sandbox).

These documents record **architectural understanding** and verified behaviour. Application repositories remain the source of **implementation truth** — always verify important claims in code and the live database schema.

Root `README.md` stays at the project root. All other project markdown docs live here.

## Index — Roles & Access

| Document | Purpose |
|----------|---------|
| [HIERARCHICAL_ROLES_AND_PERMISSIONS.md](HIERARCHICAL_ROLES_AND_PERMISSIONS.md) | Hierarchical Roles & Responsibilities (sidebar-aligned permission tree) |

## Index — System Guides

| Document | Purpose |
|----------|---------|
| [WATER_BILLING_SYSTEM_MANUAL.md](WATER_BILLING_SYSTEM_MANUAL.md) | System manual |
| [DEVELOPER_TECHNICAL_GUIDE.md](DEVELOPER_TECHNICAL_GUIDE.md) | Developer technical guide |
| [QUICK_REFERENCE_GUIDE.md](QUICK_REFERENCE_GUIDE.md) | Quick reference |

## Index — Modules & Features

| Document | Purpose |
|----------|---------|
| [DATABASE_BACKUP_MODULE_README.md](DATABASE_BACKUP_MODULE_README.md) | Database backup module |
| [MOBILE_NOTIFICATIONS_MODULE_README.md](MOBILE_NOTIFICATIONS_MODULE_README.md) | Mobile notifications (ITEXMO) |
| [MOBILE_DASHBOARD_PWA_README.md](MOBILE_DASHBOARD_PWA_README.md) | Mobile dashboard PWA |
| [CUSTOMER_MANAGEMENT_DOCUMENTATION.md](CUSTOMER_MANAGEMENT_DOCUMENTATION.md) | Customer management |
| [CUSTOMER_API_DOCUMENTATION.md](CUSTOMER_API_DOCUMENTATION.md) | Customer API |
| [STATEMENT_OF_ACCOUNT_API_DOCUMENTATION.md](STATEMENT_OF_ACCOUNT_API_DOCUMENTATION.md) | Statement of Account API |
| [STATEMENT_OF_ACCOUNT_API_EXAMPLES.md](STATEMENT_OF_ACCOUNT_API_EXAMPLES.md) | SOA API examples |
| [BILLING_AND_PAYMENT_COMPUTATION.md](BILLING_AND_PAYMENT_COMPUTATION.md) | Billing and payment computation |
| [SENIOR_CITIZEN_DISCOUNT_DOCUMENTATION.md](SENIOR_CITIZEN_DISCOUNT_DOCUMENTATION.md) | Senior citizen discount |
| [FRANCHISE_FEE_IMPLEMENTATION.md](FRANCHISE_FEE_IMPLEMENTATION.md) | Franchise fee implementation |
| [AR_ADJUSTMENT_MODULE.md](AR_ADJUSTMENT_MODULE.md) | AR Adjustment (credit/debit memo, write-off, SOA + GL) |
| [CSV_IMPORT_MAPPING.md](CSV_IMPORT_MAPPING.md) | CSV import mapping |
| [GLOBAL_SETTINGS_MODULE_CHANGES.md](GLOBAL_SETTINGS_MODULE_CHANGES.md) | Global settings module changes |
| [GITHUB_AUTH_SETUP.md](GITHUB_AUTH_SETUP.md) | GitHub auth setup |

## How to use

1. Read the relevant document before changing related behaviour.
2. Confirm claims against source code and schema.
3. After a behavioural change, update the document and set **Last validated** where present.

## Conventions

Documents in this folder follow the Company Knowledge handbook shape where applicable.

Superseded editions belong under `DOCUMENTS/ARCHIVE/`.
