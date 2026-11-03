CREATE DATABASE IF NOT EXISTS booking_db CHARACTER SET utf8mb4;
USE booking_db;

CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description VARCHAR(255) NOT NULL,
  duration_min INT NOT NULL,
  price DECIMAL(8,2) NOT NULL
);

CREATE TABLE appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  email VARCHAR(120) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  service_id INT NOT NULL,
  appt_date DATE NOT NULL,
  time_slot TIME NOT NULL,
  notes TEXT NULL,
  status ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (service_id) REFERENCES services(id),
  UNIQUE KEY uniq_slot (appt_date, time_slot)
);

CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  email VARCHAR(120) NOT NULL,
  subject VARCHAR(120) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO services (name, description, duration_min, price) VALUES
('General Consultation','A one-to-one session to discuss your needs.',30,500.00),
('Follow-up Visit','Review progress after a previous consultation.',20,300.00),
('Detailed Assessment','In-depth evaluation with a written summary.',60,1200.00),
('Online Session','Video call from the comfort of your home.',30,400.00);
