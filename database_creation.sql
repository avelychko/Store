use pinstore;

DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS users;

CREATE TABLE CUSTOMER (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
);

CREATE TABLE PRODUCT (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(225) NOT NULL,
    image_name VARCHAR(255) NOT NULL,
    price DECIMAL(6, 2) NOT NULL,
    in_stock INT NOT NULL,
    inactive TINYINT NOT NULL
);

CREATE TABLE ORDERS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    customer_id INT,
    quantity INT NOT NULL,
    price DECIMAL(6, 2) NOT NULL,
    tax DECIMAL(6, 2) NOT NULL,
    donation DECIMAL(6, 2) NOT NULL,
    timestamp BIGINT NOT NULL,
    FOREIGN KEY (product_id) REFERENCES PRODUCT(id),
    FOREIGN KEY (customer_id) REFERENCES CUSTOMER(id)
);

CREATE TABLE USERS (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    role INT 
);

INSERT INTO CUSTOMER (first_name, last_name, email)
VALUES
    ('Emma', 'Reed', 'emma.reed@mail.com'),
    ('Ethan', 'Reed', 'ereed@mail.com'),
    ('Anastasiya', 'Velychko', 'velychko.anastasiya@gmail.com');

INSERT INTO PRODUCT (product_name, image_name, price, in_stock, inactive)
VALUES
    ('Bunny', 'images/bunny.png', 6.99, 0, 1),
    ('Moth', 'images/moth.png', 5.99, 5, 0),
    ('Cat', 'images/cat.png', 7.99, 10, 0),
    ('Snake', 'images/snake.png', 4.99, 20, 0);

INSERT INTO USERS (first_name, last_name, password, email, role)
VALUES
    ('Anastasiya', 'Velychko', 'avelychko', 'velychko.anastasiya@gmail.com', 1),
    ('Harry', 'Potter', 'hp_password', 'hp@mail.com', 2);