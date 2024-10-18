## Server details

Ensure you have the following installed on your machine:

- *PHP 8+*
- *Composer* (Dependency Manager for PHP)
- *MySQL* 

### 3. Install Dependencies
Run the following commands to install the necessary dependencies:
```bash  
composer install  
composer dump-autoload  
```

### 4. Configure environment variables
Update the .env  with your database credentials and other necessary configurations.

DB_SERVER=localhost  
DB_USER=root 
DB_PASSWORD= 
DB_NAME=test_db  

SECRET_KEY=secret_key_for_this_project  


### 5. Import database tables and using .sql file located at root named test_db.sql

### 6. Start your php server
Run the following commands to start server 
php -S localhost:8080

## Credentials to login
username - admin  
password - admin@123  
