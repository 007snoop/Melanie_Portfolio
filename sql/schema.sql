/* Profile table 1 row
link table many rows */

CREATE TABLE profile (
    id INT PRIMARY KEY AUTO_INCREMENT,
    display_name VARCHAR(100) NOT NULL,
    a_url VARCHAR(255)
);

CREATE TABLE links (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    l_url VARCHAR(255) NOT NULL,
    position INT NOT NULL,
    on_off BOOLEAN NOT NULL DEFAULT TRUE
);