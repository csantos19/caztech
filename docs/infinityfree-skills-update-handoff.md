# InfinityFree Skills and Progress-Bar Update Handoff

Status: **Prepared only — no live InfinityFree changes were made.**

Target live profile: Christian George Santos, `team_members.id = 2`.

## Local source of truth

The local CAZTech update is validated at:

- `C:\xampp\htdocs\CAZTECH\Database\caztech.sql`
- `C:\xampp\htdocs\CAZTECH\includes\team_profile_helpers.php`
- `C:\xampp\htdocs\CAZTECH\includes\components.php`
- `C:\xampp\htdocs\CAZTECH\index.php`
- `C:\xampp\htdocs\CAZTECH\team_profile.php`
- `C:\xampp\htdocs\CAZTECH\admin\edit_profile.php`

The evidence registry is documented in `docs/christian-skills-evidence.md`. Tests and documentation do not need to be uploaded to the public hosting root unless desired.

## Preconditions

1. Confirm that the live database is the CAZTech database and that `team_members.id = 2` is still Christian George Santos.
2. Export a live database backup before running any SQL.
3. Download/backup the current live versions of the PHP files listed above.
4. Confirm the live PHP version supports the existing CAZTech code and the current MySQL/MariaDB schema.
5. Use the hosting panel, phpMyAdmin, or FTP/FileZilla through its secure interface. Do not paste passwords, API keys, or `.env` values into chat or this document.

## Recommended deployment order

1. Put the site in a maintenance window if the live site is actively receiving profile edits.
2. Upload the PHP files from the validated local project, preserving the live directory structure.
3. Run only the targeted `team_members` skills update from the validated `Database/caztech.sql` payload. Do not import the complete local dump because it contains unrelated local records and schema state.
4. Clear only the applicable hosting/PHP/opcache cache if the host provides that control.
5. Run the post-deployment checks below.
6. Remove any temporary SQL export or backup files from the public web root.

## Post-deployment checks

- `team_profile.php?id=2` returns HTTP 200.
- The response identifies Christian George Santos and displays the Skills and technologies section.
- The profile contains 59 categorized progress bars with valid `aria-valuenow`, `aria-valuemin="0"`, and `aria-valuemax="100"` values.
- `index.php` returns HTTP 200 and displays the verified featured skills bars.
- The homepage no longer uses the old hard-coded `Frontend Dev (React, Tailwind)` or `Backend Systems (PHP, Node)` skill-bar entries.
- `team_profile.php?id=999999` returns HTTP 404.
- The admin profile editor still opens and preserves the `[Category] Skill|score` format.

## Rollback

If the live result is incorrect:

1. Restore the backed-up PHP files.
2. Restore the previous `skills` value for `team_members.id = 2` from the live database backup, after verifying the target identity.
3. Clear only the relevant cache.
4. Repeat the HTTP checks and record the result.

No live deployment, remote database connection, credential handling, or file upload was performed during this local implementation.
