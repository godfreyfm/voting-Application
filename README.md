```Voting App with signin/signup page```

The purpose of this is to see how an application connects to a database. We will host a web app on EC2 that talks to an RDS SQL DB. 

### Do the following steps: 
```sh
+ Create the an application vpc with 4 subnets —>> two public and two private 
+ create an RDS mysql database , create a security group for the db instance. Pay attention to the db endpoint,     master username and password 
+ Create the app instance on ec2 using ubuntu AMI. Open up security groups for ssh and http 
+ Connect to the instance using ssh. 
```

### Clone repo into your server
```sh
git clone https://github.com/xashy-devops/votingapp.git
```

### Change to the repo folder
```sh
cd votingapp/
```

### Edit the setup.sql and change "specifyTheDatabaseName" and candidates names
```sh
vi setup.sql
```

### Edit the setup.sh and change "db_endpoint" and "dbusername"
```sh
vi setup.sh
```

### Make shellscript executable and execute it
```sh
chmod +x setup.sh && ./setup.sh
```
*Enter Database pasword when prompted*

### Use WGET to download the 2 images of the voting candidates
```sh
## You can upload the images to a public S3 bucket and use WGET to download them into your server**
## Make sure to download images into the /var/www/html/myapp directory

## Rename the first image png1.jpg 
##Note: Candidate 1 picture should be png 1
wget <INSERT_IMAGE_URL>
sudo mv <INSERT_IMAGE_NAME> /var/www/html/myapp/png1.jpg

## Rename the second image png2.jpg 
##Note: Candidate 1 picture should be png 1
wget <INSERT_IMAGE_URL>
sudo mv <INSERT_IMAGE_NAME> /var/www/html/myapp/png2.jpg

```


### Edit the signup, signin, and vote files with the Database information
```sh
vi update_db.sh
```
```sh
chmod +x update_db.sh && sudo ./update_db.sh
```