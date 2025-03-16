CREATE TABLE categories (
    id INT(11) NOT NULL AUTO_INCREMENT,
    category_name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE users (
    id INT(10) NOT NULL AUTO_INCREMENT,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE order_items (
    id INT(11) NOT NULL AUTO_INCREMENT,
    order_id INT(11) NOT NULL,
    item_id INT(11) NOT NULL,
    itemName VARCHAR(255) NOT NULL,
    itemPrice DECIMAL(10,2) NOT NULL,
    quantity INT(11) NOT NULL,
    PRIMARY KEY (id),
    KEY order_id (order_id),  -- Foreign Key can be added later
    KEY item_id (item_id)     -- Foreign Key can be added later
);

CREATE TABLE orders (
    id INT(11) NOT NULL AUTO_INCREMENT,
    fullName VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) DEFAULT 'Pending',
    phone VARCHAR(15) NULL,
    PRIMARY KEY (id)
);

CREATE TABLE items (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    photo VARCHAR(255) NOT NULL,
    description TEXT NULL,
    offers VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    stock INT(11) NOT NULL DEFAULT 0,
    category_id INT(11) NULL,
    category VARCHAR(255) DEFAULT 'Uncategorized',
    PRIMARY KEY (id),
    KEY category_id (category_id)  -- Foreign Key can be added later
);


ALTER TABLE order_items 
ADD CONSTRAINT fk_order FOREIGN KEY (order_id) REFERENCES orders(id),
ADD CONSTRAINT fk_item FOREIGN KEY (item_id) REFERENCES items(id);

ALTER TABLE items 
ADD CONSTRAINT fk_category FOREIGN KEY (category_id) REFERENCES categories(id);
