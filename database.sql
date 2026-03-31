-- ==========================================================
-- SILA COPY AND PASTE JE 
-- TUKAR ROLE DEKAT PHPMYADMIN KALAU NAK TEST ADMIN
-- ==========================================================


CREATE DATABASE IF NOT EXISTS student_db;
USE student_db;


CREATE TABLE IF NOT EXISTS users (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  email varchar(100) NOT NULL,
  role enum('admin','student') NOT NULL DEFAULT 'student',
  id_number varchar(20) NOT NULL,
  phone varchar(20) NOT NULL,
  address text NOT NULL,
  class_name varchar(50) NOT NULL,
  password varchar(255) NOT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id),
  UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS student_records (
  id int(11) NOT NULL AUTO_INCREMENT,
  student_id int(11) NOT NULL,
  record_type varchar(100) NOT NULL,
  description text NOT NULL,
  file_path varchar(255) DEFAULT NULL,
  created_by int(11) NOT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id),
  KEY student_id (student_id),
  KEY created_by (created_by),

  CONSTRAINT fk_student FOREIGN KEY (student_id) REFERENCES users (id) ON DELETE CASCADE,

  CONSTRAINT fk_admin FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

