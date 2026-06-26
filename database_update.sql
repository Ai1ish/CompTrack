USE comptrack_db;

ALTER TABLE equipment
ADD COLUMN IF NOT EXISTS image_path VARCHAR(255) NULL AFTER remarks;

INSERT INTO categories (category_name, description)
SELECT 'Desktop Computer', 'Complete desktop computer sets used in computer laboratory stations.'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE category_name = 'Desktop Computer');

INSERT INTO categories (category_name, description)
SELECT 'Monitor', 'Display screens and visual output devices.'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE category_name = 'Monitor');

INSERT INTO categories (category_name, description)
SELECT 'Keyboard', 'Wired and wireless keyboards.'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE category_name = 'Keyboard');

INSERT INTO categories (category_name, description)
SELECT 'Mouse', 'Wired and wireless pointing devices.'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE category_name = 'Mouse');

INSERT INTO categories (category_name, description)
SELECT 'Printer', 'Inkjet, laser, and shared laboratory printers.'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE category_name = 'Printer');

INSERT INTO categories (category_name, description)
SELECT 'Projector', 'LCD, DLP, and portable presentation projectors.'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE category_name = 'Projector');

INSERT INTO categories (category_name, description)
SELECT 'Networking Device', 'Routers, switches, access points, and network adapters.'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE category_name = 'Networking Device');

INSERT INTO categories (category_name, description)
SELECT 'Server Equipment', 'Servers, NAS devices, and server room hardware.'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE category_name = 'Server Equipment');

INSERT INTO categories (category_name, description)
SELECT 'Power Device', 'UPS units, AVR units, extension cords, and power strips.'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE category_name = 'Power Device');

INSERT INTO categories (category_name, description)
SELECT 'Audio Device', 'Speakers, headsets, microphones, and audio interfaces.'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE category_name = 'Audio Device');

INSERT INTO categories (category_name, description)
SELECT 'Storage Device', 'External drives, flash drives, and backup storage devices.'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE category_name = 'Storage Device');

INSERT INTO categories (category_name, description)
SELECT 'Cable and Adapter', 'HDMI, VGA, LAN cables, converters, and adapters.'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE category_name = 'Cable and Adapter');
