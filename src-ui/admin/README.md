# TAF Admin Dashboard - Setup Guide

## Overview
This admin dashboard allows you to manage Top Destinations for your Tourist Attraction Finder website without manually accessing phpMyAdmin.

## Features
- ✅ Add, Edit, Delete Top Destinations
- ✅ Upload and manage destination images
- ✅ Control display order of destinations
- ✅ Filter by City/Municipality categories
- ✅ Secure admin authentication
- ✅ Real-time preview of changes

## Setup Instructions

### 1. Database Setup

First, run the database migration to add the required columns:

```sql
-- Run this in phpMyAdmin or your MySQL client
USE tourist_finder;

-- Add image_url column
ALTER TABLE `attractions` 
ADD COLUMN `image_url` VARCHAR(500) DEFAULT NULL AFTER `description`;

-- Add is_top_destination flag
ALTER TABLE `attractions` 
ADD COLUMN `is_top_destination` TINYINT(1) DEFAULT 0 AFTER `image_url`;

-- Add display_order
ALTER TABLE `attractions` 
ADD COLUMN `display_order` INT DEFAULT 0 AFTER `is_top_destination`;
```

Or simply import the `Backend/database_updates.sql` file.

### 2. Create Upload Directory

Create the directory for uploaded images:
```
assets/img/destinations/
```

Make sure this directory is writable by the web server.

### 3. Access Admin Dashboard

Navigate to: `http://localhost/TAF/admin/`

**Default Login Credentials:**
- Email: `admin@example.com`
- Password: `password123`

**⚠️ IMPORTANT:** Change the default password after first login!

### 4. Managing Top Destinations

1. **Add New Destination:**
   - Click "Add Destination" button
   - Fill in the form:
     - Destination Name (required)
     - Location (required)
     - Category (City/Municipality)
     - Description (optional)
     - Display Order (0 = first position)
     - Upload Image (click the upload area)
   - Click "Save Destination"

2. **Edit Destination:**
   - Click "Edit" button on any destination
   - Modify the fields
   - Click "Save Destination"

3. **Delete Destination:**
   - Click "Delete" button
   - Confirm the deletion

4. **Reorder Destinations:**
   - Change the number in the "Order" column
   - Lower numbers appear first

### 5. View Changes

Your changes will automatically appear on the landing page in the "Top Destinations" section.

## File Structure

```
admin/
├── index.php                 # Admin entry point (redirects to login or dashboard)
├── login.php                 # Login page
├── logout.php                # Logout handler
├── top-destinations.php      # Main admin dashboard
├── upload.php                # Image upload handler
├── includes/
│   ├── auth.php              # Authentication functions
│   └── header.php            # Admin header template
└── README.md                 # This file

Backend/
├── app/
│   ├── Controllers/
│   │   ├── AdminController.php      # Admin API controller
│   │   └── LoginController.php      # Login API controller
│   └── UseCases/
│       └── ManageAttraction.php     # Business logic
├── Infrastructure/
│   └── Repositories/
│       └── AttractionRepositoryImpl.php  # Database operations
└── routes/
    └── api.php                 # API routes
```

## API Endpoints

### Public Endpoints
- `GET /api/top-destinations` - Fetch all top destinations for landing page

### Admin Endpoints (require authentication)
- `GET /api/admin/attractions` - List all attractions
- `POST /api/admin/attractions` - Create new attraction
- `GET /api/admin/attractions/{id}` - Get attraction by ID
- `PUT /api/admin/attractions/{id}` - Update attraction
- `DELETE /api/admin/attractions/{id}` - Delete attraction

## Troubleshooting

### Images not uploading
1. Check that `assets/img/destinations/` directory exists
2. Ensure the directory has write permissions
3. Verify file size is under 5MB
4. Check file type (JPG, PNG, WebP, GIF only)

### Changes not appearing on landing page
1. Clear browser cache (Ctrl + F5)
2. Check browser console for JavaScript errors
3. Verify API endpoint is returning data correctly

### Login not working
1. Verify database connection in `Backend/config/config.php`
2. Check that `admins` table exists with default admin
3. Ensure session cookies are enabled in browser

## Security Notes

- Always use HTTPS in production
- Change default admin password immediately
- Keep PHP and MySQL updated
- Use strong passwords
- Regularly backup your database

## Support

If you encounter any issues, please check:
1. PHP error logs
2. Browser console for JavaScript errors
3. Database connection settings
4. File permissions

---

**Version:** 1.0.0  
**Last Updated:** 2026-04-04