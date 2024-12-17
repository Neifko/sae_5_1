CREATE DATABASE IF NOT EXISTS sae51;
USE sae51;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL ,
    password VARCHAR(255) NOT NULL ,
    admin BOOLEAN DEFAULT 0 NOT NULL
);
