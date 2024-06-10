#!/bin/bash

# Define the path to your PHP files
FILES=(
    "/var/www/html/myapp/signin.php"
    "/var/www/html/myapp/signup.php"
    "/var/www/html/myapp/vote.php"
)

# Database configuration values - use environment variables set previously
DB_HOST="terraform-20240610210130723100000002.cfecqo6ssfrf.us-east-1.rds.amazonaws.com"  # Replace with your actual DB host
DB_NAME="testdatabase"  # Replace with your actual DB name
DB_USER="$DB_USERNAME"  # Use the environment variable
DB_PASS="$DB_PASSWORD"  # Use the environment variable

# Loop through each file and update the database configuration
for FILE in "${FILES[@]}"; do
    sed -i "s/\$host = \".*\";/\$host = \"$DB_HOST\";/" $FILE
    sed -i "s/\$dbname = \".*\";/\$dbname = \"$DB_NAME\";/" $FILE
    sed -i "s/\$username = \".*\";/\$username = \"$DB_USER\";/" $FILE
    sed -i "s/\$dbPassword = \".*\";/\$dbPassword = \"$DB_PASS\";/" $FILE
    echo "Database configuration updated in $FILE"
done
