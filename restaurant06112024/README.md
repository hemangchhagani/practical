# Getting Started with PHP Using API

# restaurants Project Backend

Restful api create for  restaurants crm project  The backend is built with PHP and uses Composer for dependency management. 
Follow the steps below to set up and run the project locally.

# software dependencies
Ensure you have the following installed on your machine:

PHP 8+
Composer(Dependency Manager for PHP)
MySQL  

# Getting Started
+

# Navigate to project directory
root directory practical\restaurant

cd restaurant  

# Install Dependencies
Run the following commands to install the necessary dependencies:
using commond line.

composer install  
composer dump-autoload  


# Configure environment variables

Update the .env  with your database credentials and other necessary configurations.

APP_ENV=development
#APP_ENV=production
DB_HOST=localhost
DB_NAME=restaurants
DB_USER=root
DB_PASS=


JWT_SECRET=your-secret-key
JWT_ISSUER=http://localhost
JWT_AUDIENCE=http://localhost
JWT_EXPIRY_TIME=3600  # 1 hour

SESSION_DOMAIN=localhost:81

# Import database tables and using .sql file located at root named test_db.sql

for example restaurants.sql  upload database using phpmyadmin simple query run.

# Start your php server
Run the following commands to start server 
php -S localhost:8080
