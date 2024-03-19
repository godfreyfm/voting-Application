#!/bin/bash

# Define the path to your PHP files
FILES=(
    "/var/www/html/myapp/signin.php"
    "/var/www/html/myapp/signup.php"
    "/var/www/html/myapp/vote.php"
)

# Database configuration values - replace these with your actual database credentials
DB_HOST="your_db_host"
DB_NAME="your_db_name"
DB_USER="your_db_username"
DB_PASS="your_db_password"

# Loop through each file and update the database configuration
for FILE in "${FILES[@]}"; do
    sed -i "s/\$host = \".*\";/\$host = \"$DB_HOST\";/" $FILE
    sed -i "s/\$dbname = \".*\";/\$dbname = \"$DB_NAME\";/" $FILE
    sed -i "s/\$username = \".*\";/\$username = \"$DB_USER\";/" $FILE
    sed -i "s/\$dbPassword = \".*\";/\$dbPassword = \"$DB_PASS\";/" $FILE
    echo "Database configuration updated in $FILE"
done
