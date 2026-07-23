# Database Backup Module

This module provides a comprehensive database backup and restore functionality with role-based access control.

## Features

- **Create Database Backups**: Generate SQL backups of the entire database
- **List Backups**: View all created backups with details (filename, size, creation date)
- **Download Backups**: Download backup files to your local machine
- **Restore Backups**: Restore the database from a backup file
- **Delete Backups**: Remove backup files and records
- **Permission-Based Access**: Control who can access backup features through roles

## Installation

### 1. Database Setup

Run the SQL script to create the backup table:

```sql
-- Execute the SQL in sql/database_backup_table.sql
CREATE TABLE IF NOT EXISTS `tbl_database_backups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(500) NOT NULL,
  `filesize` bigint(20) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `created_at` (`created_at`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

### 2. Add Column to Responsibilities Table

Add the `database_backup` column to the `tbl_responsibilities` table:

```sql
ALTER TABLE `tbl_responsibilities` 
ADD COLUMN `database_backup` varchar(10) DEFAULT '0' AFTER `leakingentry`;
```

### 3. Create Backup Directory

Ensure the backup directory exists and is writable:

```bash
mkdir -p backups
chmod 755 backups
```

Or create it manually in the project root: `backups/`

## Files Created

1. **Controller**: `application/modules/master/controllers/database_backup.php`
2. **Model**: `application/modules/master/models/database_backup_model.php`
3. **View**: `application/modules/master/views/database_backup.php`
4. **SQL Script**: `sql/database_backup_table.sql`

## Files Modified

1. **Responsibilities Model**: `application/modules/master/models/responsibilities_model.php`
   - Added `database_backup` to module list

2. **Admin Header Model**: `application/modules/master/models/adminheader_model.php`
   - Added `database_backup` to module list

## Permission System

The module uses the existing role-based permission system:

- **List (l)**: View backup list and download backups
- **Add (a)**: Create new backups and restore from backups
- **Delete (d)**: Delete backup files

### Setting Permissions

1. Go to the Roles/Responsibilities management page
2. Edit a role
3. Check the "Database Backup" module
4. Select the permissions (List, Add, Delete) you want to grant
5. Save the role

### Permission Checks

- **Super Admin**: Full access (no permission checks)
- **Sub Admin**: Access based on assigned role permissions
- **No Permission**: Redirected to access denied page

## Usage

### Accessing the Module

Navigate to: `http://your-domain/index.php/master/database_backup`

### Creating a Backup

1. Click the "Create Backup" button
2. The system will generate a SQL backup file
3. The backup will be saved in the `backups/` directory
4. A record will be created in the database

### Downloading a Backup

1. Click the download icon (blue) next to a backup
2. The backup file will be downloaded to your computer

### Restoring a Backup

**WARNING**: Restoring a backup will overwrite the current database!

1. Click the restore icon (green) next to a backup
2. Confirm the restore action
3. The database will be restored from the selected backup

### Deleting a Backup

1. Click the delete icon (red) next to a backup
2. Confirm the deletion
3. Both the file and database record will be removed

## Security Considerations

1. **Backup Directory**: Ensure the `backups/` directory is not publicly accessible
2. **File Permissions**: Set appropriate file permissions (755 for directory, 644 for files)
3. **Access Control**: Only grant backup permissions to trusted users
4. **Regular Backups**: Schedule regular backups for data protection
5. **Backup Storage**: Consider storing backups in a secure, off-site location

## Troubleshooting

### Backup Creation Fails

- Check that the `backups/` directory exists and is writable
- Verify database connection settings
- Check PHP error logs for specific errors

### Permission Denied

- Verify user has the correct role assigned
- Check role permissions include the required actions
- Ensure user is logged in with proper session

### Restore Fails

- Verify backup file exists and is readable
- Check database connection
- Ensure sufficient database privileges
- Review PHP error logs

## Notes

- Backups are stored in SQL format
- Backup filenames include timestamp: `backup_YYYY-MM-DD_HH-MM-SS.sql`
- File sizes are displayed in human-readable format (KB, MB, GB)
- The module tracks who created each backup

## Support

For issues or questions, check:
- CodeIgniter documentation
- Database utility class documentation
- PHP file system functions documentation

