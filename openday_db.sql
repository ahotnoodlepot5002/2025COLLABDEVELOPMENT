CREATE DATABASE IF NOT EXISTS openday_db;
USE openday_db;

CREATE TABLE IF NOT EXISTS campuses (
    campus_id INT AUTO_INCREMENT PRIMARY KEY,
    campus_name ENUM('Wolverhampton', 'Telford', 'Walsall') UNIQUE,
    address TEXT,
    contact_number VARCHAR(20)
);
CREATE TABLE IF NOT EXISTS student_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    campus_id INT NOT NULL,
    FOREIGN KEY (campus_id) REFERENCES campuses(campus_id),
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(100) NOT NULL,
    event_date DATE NOT NULL,
    campus_id INT NOT NULL,
    FOREIGN KEY (campus_id) REFERENCES campuses(campus_id),
    description TEXT
);
CREATE TABLE IF NOT EXISTS student_event_registrations (
    student_id INT NOT NULL,
    event_id INT NOT NULL,
    PRIMARY KEY (student_id, event_id),
    FOREIGN KEY (student_id) REFERENCES student_registrations(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE
);
