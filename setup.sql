-- Make sure to change the database name
CREATE DATABASE IF NOT EXISTS specifyTheDatabaseName;
-- Change the database name below as well
USE specifyTheDatabaseName;
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    voted BOOLEAN DEFAULT FALSE
);
CREATE TABLE IF NOT EXISTS candidates (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    votes INT DEFAULT 0
);

-- Make sure to edit the candidates names below
INSERT INTO candidates (name, votes) VALUES ('INSERT_1ST_CANDIDATE_NAME', 0);
INSERT INTO candidates (name, votes) VALUES ('INSERT_2ND_CANDIDATE_NAME', 0);