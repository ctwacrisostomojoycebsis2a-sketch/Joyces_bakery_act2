JOYCE'S BAKERY - PHP & MYSQL PROJECT

This project follows the basic PHP/MySQL CRUD and user-management structure visible in the screenshots:
- Login and registration
- New user accounts wait for administrator approval
- Admin can approve or disable user accounts
- Dashboard
- Product Create, Read, Update and Delete (CRUD)
- Separate PHP files, MySQL database, and simple CSS

SETUP (XAMPP)
1. Copy the joyces_bakery_tutorial folder into C:\xampp\htdocs\
2. Start Apache and MySQL in XAMPP.
3. Open http://localhost/phpmyadmin
4. Import database.sql.
5. Open http://localhost/joyces_bakery_tutorial/create_admin.php
6. Create your first administrator username and password.
7. Log in at http://localhost/joyces_bakery_tutorial/index.php
8. Register normal users. They will remain pending until an admin approves them.

DATABASE SETTINGS
The default settings in config.php are host=localhost, username=root, blank password, database=joyces_bakery.
Change these if your local MySQL setup uses a different password.

NOTES
- Products use Philippine peso formatting (₱) in the product list.
- The code uses prepared statements and password_hash/password_verify.
- Keep create_admin.php inaccessible or remove it after the first admin is created. It locks itself once an admin exists.
- This is a local learning project. Test it on XAMPP before deployment to a public server.

SOURCE LIMITATION
The screenshots show the products CRUD page, the products table fields, and user-management file names. The full tutorial video source code was not available as readable text, so this is a straightforward implementation matching the visible structure and features rather than a verbatim transcription of every video line.
