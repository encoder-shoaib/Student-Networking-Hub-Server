-- Create the database
CREATE DATABASE IF NOT EXISTS student_networking_hub;

-- Use the database
USE student_networking_hub;

-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,       -- Unique user ID
    username VARCHAR(100) NOT NULL,          -- User's name
    email VARCHAR(150) NOT NULL UNIQUE,      -- User's email
    password VARCHAR(255) NOT NULL,          -- Encrypted password
    age INT NOT NULL,                        -- User's age
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Registration time
    location VARCHAR(255) DEFAULT NULL,      -- User's location
    phone VARCHAR(15) DEFAULT NULL,          -- Phone number
    university VARCHAR(255) DEFAULT NULL,    -- User's university name
    education_duration VARCHAR(50) DEFAULT NULL, -- Education duration
    skills TEXT DEFAULT NULL,                -- Skills as comma-separated values
    profile_photo VARCHAR(255) DEFAULT NULL  -- Path to profile photo
);
