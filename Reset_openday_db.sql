USE openday_db;

DELETE FROM student_event_registrations;
DELETE FROM student_registrations;
DELETE FROM events;
DELETE FROM campuses;

ALTER TABLE campuses AUTO_INCREMENT = 1;
ALTER TABLE events AUTO_INCREMENT = 1;

-----------------------------------------------------
#Adds dummy data as the example for the demonstration
-----------------------------------------------------
INSERT INTO campuses (campus_name, address, contact_number) VALUES
('Walsall', 'Walsall Campus Address', '01234 567890'),
('Telford', 'Telford Campus Address', '01234 567891'),
('Wolverhampton', 'Wolverhampton Campus Address', '01234 567892');

INSERT INTO events (event_name, event_date, campus_id, description) VALUES
('Open Day', '2025-03-15', 1, 'Test1'),
('Open Day', '2025-03-15', 2, 'Test1'),
('Open Day', '2025-03-15', 3, 'Test1');
