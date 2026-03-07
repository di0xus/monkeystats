CREATE DATABASE IF NOT EXISTS sae203_db;
USE sae203_db;

CREATE TABLE IF NOT EXISTS Leaderboard (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    wpm FLOAT,
    acc FLOAT,
    rank_pos INT,
    mode INT
);

CREATE TABLE IF NOT EXISTS Users (
    name VARCHAR(255) PRIMARY KEY,
    tests_completed INT DEFAULT 0,
    time_typing INT DEFAULT 0,
    wpm_15 FLOAT DEFAULT 0,
    acc_15 FLOAT DEFAULT 0,
    wpm_60 FLOAT DEFAULT 0,
    acc_60 FLOAT DEFAULT 0,
    xp INT DEFAULT 0,
    level INT DEFAULT 1,
    discord_name VARCHAR(255),
    discord_avatar VARCHAR(255),
    badge_id VARCHAR(255),
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);