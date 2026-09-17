# ABShop — PHP + MySQL (InfinityFree Ready)

## Your InfinityFree Details (already filled)

- **Host:** sql106.infinityfree.com
- **Database:** if0_42897329_abshop
- **Username:** if0_42897329
- **Password:** ← You must still put this in `config/database.php`

## Admin Login

- **URL:** https://abshop.page.gd/login.php
- **Username:** admin
- **Password:** admin123

(Change this later for security)

## Setup Steps

### 1. Import Database
1. Open phpMyAdmin
2. Select database `if0_42897329_abshop`
3. Go to **Import** tab
4. Choose file `sql/schema.sql`
5. Click **Go**

### 2. Set Database Password
Edit `config/database.php` and replace:

```php
define('DB_PASS', 'YOUR_PASSWORD_HERE');
```

with your real MySQL password.

### 3. Upload Files
Upload everything inside this folder into the `htdocs` of your domain.

### 4. Test
- Store: https://abshop.page.gd
- Admin: https://abshop.page.gd/login.php

## Default Admin
Username: `admin`  
Password: `admin123`
