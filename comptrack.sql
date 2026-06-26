CREATE DATABASE IF NOT EXISTS comptrack_db;
USE comptrack_db;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('Administrator','Laboratory Staff') DEFAULT 'Laboratory Staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    description TEXT
);

INSERT INTO categories (category_name, description) VALUES
('Desktop Computer', 'Complete desktop computer sets used in computer laboratory stations.'),
('Monitor', 'Display screens and visual output devices.'),
('Keyboard', 'Wired and wireless keyboards.'),
('Mouse', 'Wired and wireless pointing devices.'),
('Printer', 'Inkjet, laser, and shared laboratory printers.'),
('Projector', 'LCD, DLP, and portable presentation projectors.'),
('Networking Device', 'Routers, switches, access points, and network adapters.'),
('Server Equipment', 'Servers, NAS devices, and server room hardware.'),
('Power Device', 'UPS units, AVR units, extension cords, and power strips.'),
('Audio Device', 'Speakers, headsets, microphones, and audio interfaces.'),
('Storage Device', 'External drives, flash drives, and backup storage devices.'),
('Cable and Adapter', 'HDMI, VGA, LAN cables, converters, and adapters.');

CREATE TABLE equipment (
    equipment_id INT AUTO_INCREMENT PRIMARY KEY,
    asset_number VARCHAR(50) UNIQUE NOT NULL,
    equipment_name VARCHAR(100) NOT NULL,
    category_id INT,
    brand VARCHAR(100),
    model VARCHAR(100),
    serial_number VARCHAR(100),
    laboratory_room VARCHAR(50),
    purchase_date DATE,
    status ENUM('Available','In Use','Under Maintenance','Damaged','Retired') DEFAULT 'Available',
    remarks TEXT,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL
);

CREATE TABLE maintenance (
    maintenance_id INT AUTO_INCREMENT PRIMARY KEY,
    equipment_id INT NOT NULL,
    maintenance_date DATE NOT NULL,
    technician VARCHAR(100),
    description TEXT,
    cost DECIMAL(10,2),
    next_schedule DATE,
    FOREIGN KEY (equipment_id) REFERENCES equipment(equipment_id) ON DELETE CASCADE
);

CREATE TABLE activity_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    equipment_id INT,
    action VARCHAR(255),
    log_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (equipment_id) REFERENCES equipment(equipment_id) ON DELETE SET NULL
);
