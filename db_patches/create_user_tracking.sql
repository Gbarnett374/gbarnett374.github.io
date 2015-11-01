CREATE TABLE user_tracking(
id int(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
ip_address varchar(25),
user_agent varchar(500),
created_date timestamp DEFAULT current_timestamp
)