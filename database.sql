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