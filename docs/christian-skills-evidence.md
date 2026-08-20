# Christian George Santos — Evidence-Based Skills Registry

Date reviewed: 2026-08-20
Target: `team_members.id = 2` in the local CAZTech database

## Scope and evidence rules

This registry is based on authored source files, root project manifests, database schemas/migrations, build configuration, and project/deployment documentation found under `D:\SYSTEM`. The sanitized baseline analysis in `D:\Chatlog\chatlog-2026-08-15-1702.txt` was used as the starting point and was corroborated against representative Laravel, legacy PHP/MySQL, SQL, JavaScript, and deployment files.

Duplicate archives and copies were grouped by project family. Dependency and generated content was not treated as authored evidence: `vendor`, `node_modules`, build output, `storage`, framework caches, backups, duplicate ZIPs, and filename-only archive claims were excluded. Credentials, `.env` values, passwords, and unrelated database records are intentionally omitted.

Hostinger is included as a separate user-confirmed deployment experience because Christian directly stated that he frequently uses it for larger projects; it is not presented as a claim inferred from the `D:\SYSTEM` source scan.

The reviewed project families include the Laravel apartment system, Poomsae and scoring systems, RJV dental systems, Construction inventory/ERP work, POS work, TKD school-fees work, RONA registry work, CAZTech, and the additional school-fees archive identified by the previous manifest-only review.

## Score meaning

The displayed percentage is **verified project usage**, not a subjective proficiency rating.

- **Coverage (70%)**: how broadly the canonical skill appears across the deduplicated authored project families.
- **Evidence depth (30%)**: direct authored source usage, root manifest/schema evidence, runtime/build configuration, and project/deployment documentation.
- Scores are normalized and clamped to `0–100`; closely related packages are grouped only where that prevents duplicate noise in the public profile.
- Technologies unsupported by authored-project evidence—such as React, Vue, TypeScript, Python, Java, C#, PostgreSQL, and SQL Server—are not included.

## Canonical registry

| Category | Skill | Evidence score |
|---|---|---:|
| Languages | PHP | 96 |
| Languages | JavaScript | 92 |
| Languages | HTML5 | 94 |
| Languages | CSS3 | 90 |
| Languages | SQL | 92 |
| Languages | JSON | 82 |
| Templates | Laravel Blade | 82 |
| Databases | MySQL / MariaDB | 94 |
| Databases | SQLite | 68 |
| Databases | Redis (Laravel realtime/cache configuration) | 56 |
| Backend | Laravel | 86 |
| Backend | Eloquent ORM / migrations | 84 |
| Backend | Laravel Sanctum | 60 |
| Backend | Laravel Breeze | 55 |
| Backend | Laravel Reverb / WebSockets | 62 |
| Backend | Laravel Echo / Pusher JS | 61 |
| Backend | MySQLi / PDO | 92 |
| Backend | REST APIs / AJAX | 90 |
| Backend | Sessions / authentication / role-based access | 94 |
| Frontend | Tailwind CSS | 86 |
| Frontend | Bootstrap | 88 |
| Frontend | Bootstrap Icons | 75 |
| Frontend | Alpine.js | 82 |
| Frontend | jQuery / jQuery UI / jQuery Validate | 79 |
| Frontend | jQuery DataTables | 69 |
| Frontend | jQuery Steps | 48 |
| Frontend | Datepicker | 58 |
| Frontend | Bootstrap Fileupload | 45 |
| Frontend | MetisMenu / MixItUp / PrettyPhoto | 44 |
| Frontend | FullCalendar | 57 |
| Frontend | ApexCharts | 60 |
| Frontend | Chart.js | 55 |
| Frontend | SweetAlert2 | 62 |
| Frontend | Font Awesome | 84 |
| Frontend | Axios | 71 |
| Frontend | Turbo / Hotwire | 42 |
| Frontend | Dropzone / Flatpickr | 48 |
| Frontend | jsVectorMap / Swiper | 40 |
| Frontend | TailAdmin | 66 |
| Email | PHPMailer / SMTP | 72 |
| Reports | Dompdf / PDF generation | 67 |
| Reports | Laravel Excel / PhpSpreadsheet | 58 |
| Tooling | Composer | 86 |
| Tooling | Node.js / npm | 84 |
| Tooling | Vite / Laravel Vite | 78 |
| Tooling | Webpack / Babel | 60 |
| Tooling | PostCSS / Autoprefixer | 73 |
| Tooling | Prettier | 48 |
| Tooling | PHPUnit / Laravel Pint | 62 |
| Tooling | Laravel Artisan / Sail / Tinker | 78 |
| Tooling | PWA / Web App Manifest | 46 |
| Deployment | XAMPP / Apache | 93 |
| Deployment | phpMyAdmin | 84 |
| Deployment | InfinityFree / FTP / FileZilla | 55 |
| Deployment | Hostinger | 72 |
| Tools | VS Code | 78 |
| Tools | Git / GitHub | 68 |
| Automation | Windows Batch / PowerShell / VBScript | 54 |
| Tools | Microsoft Excel | 56 |

## Representative corroboration

- `D:\SYSTEM\htdocs\apartment\composer.json` confirms PHP 8.2, Laravel 11, Composer, Tinker, Pint, Sail, and PHPUnit; its `package.json` and `vite.config.js` confirm Node/npm, Vite, Tailwind, Axios, PostCSS, Autoprefixer, and Laravel Vite.
- `D:\SYSTEM\htdocs\Poomsae\composer.json` and `README.md` confirm Laravel 12, Sanctum, Reverb, MySQL, Bootstrap, Bootstrap Icons, Vite, Echo/Pusher, Axios, Composer, npm, and XAMPP.
- `D:\SYSTEM\RJV-DENTIST-MEDICAL-SYSTEM\db.php`, `index.php`, and `rjv_database.sql` confirm authored PHP, MySQLi, SQL, sessions, authentication, role restrictions, and database-backed application flows.
- RJV PDF/AJAX files confirm PDF/report generation and prepared PHP database interactions.
- RONA registry files confirm PHP, MySQL-style queries, Tailwind, jQuery, Alpine.js, Font Awesome, and AJAX workflows.
- Construction, POS, TKD school-fees, Scoring, archive-manifest, and deployment files corroborate the remaining legacy UI, reporting, database, automation, and hosting ecosystem entries.

This document is an audit trail for the local profile update; it is not a runtime configuration file and contains no credentials.
