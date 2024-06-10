#!/bin/bash

# Replace with your secret name and AWS region
SECRET_NAME="***************"
REGION="us-east-1"

# Update package lists
sudo apt update -y

# Install Apache2, PHP, and MySQL client
sudo apt install apache2 php libapache2-mod-php php-mysql -y
sudo apt install mysql-client -y

# Restart Apache to apply changes
sudo service apache2 restart

# Retrieve the secret using AWS CLI
SECRET_JSON=$(aws secretsmanager get-secret-value --secret-id $SECRET_NAME --region $REGION --query SecretString --output text)
DB_USERNAME=$(echo $SECRET_JSON | jq -r .username)
DB_PASSWORD=$(echo $SECRET_JSON | jq -r .password)

# Set environment variables for the current session
export DB_USERNAME=$DB_USERNAME
export DB_PASSWORD=$DB_PASSWORD

# Persist environment variables across sessions by updating /etc/environment
sudo sed -i '/DB_USERNAME/d' /etc/environment
sudo sed -i '/DB_PASSWORD/d' /etc/environment
echo "DB_USERNAME=$DB_USERNAME" | sudo tee -a /etc/environment
echo "DB_PASSWORD=$DB_PASSWORD" | sudo tee -a /etc/environment

# Source the /etc/environment to apply changes to the current session
source /etc/environment

# Create a directory for your app
sudo mkdir -p /var/www/html/myapp

# Change ownership of the app directory
sudo chown -R www-data:www-data /var/www/html/myapp

# Move your app's PHP files into the new directory
sudo mv ~/votingapp/signin.php /var/www/html/myapp/
# Move your app's PHP files into the new directory
sudo mv ~/votingapp/signin.php /var/www/html/myapp/
sudo mv ~/votingapp/signup.php /var/www/html/myapp/
sudo mv ~/votingapp/vote.php /var/www/html/myapp/
sudo mv ~/votingapp/logout.php /var/www/html/myapp/

# Replace placeholders and run the SQL setup
mysql -h terraform-20240610210130723100000002.cfecqo6ssfrf.us-east-1.rds.amazonaws.com -u $DB_USERNAME -p$DB_PASSWORD < ~/votingapp/setup.sql
