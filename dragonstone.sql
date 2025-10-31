DROP DATABASE IF EXISTS dragonstone_db;


CREATE DATABASE dragonstone_db;
USE dragonstone_db;


CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255),
    role VARCHAR(20) DEFAULT 'customer',
    eco_points INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    carbon_impact FLOAT DEFAULT 0,
    subscription_allowed TINYINT(1) DEFAULT 0
);

-
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    points INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    status VARCHAR(20),
    recurrence_days INT DEFAULT 30,
    next_billing DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255),
    body TEXT,
    approved TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    user_id INT,
    body TEXT,
    approved TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


INSERT INTO products (name, description, price, image, carbon_impact, subscription_allowed) VALUES
('Refillable Glass Spray Bottle', 'Reusable glass spray bottle for all cleaning needs.', 49.99, 'assets/images/glass_bottle.jpg', 0.2, 1),
('Bamboo Kitchen Utensil Set', 'Sustainable bamboo utensils.', 199.99, 'assets/images/bamboo_utensils.jpg', 1.2, 0),
('Recycled Glass Vase', 'Handcrafted vase made from recycled glass.', 249.99, 'assets/images/glass_vase.jpg', 2.5, 0),
('Bamboo Toothbrush', 'Compostable bamboo toothbrush.', 29.99, 'assets/images/bamboo_toothbrush.jpg', 0.05, 1),
('Reusable Water Bottle', 'Bottle from recycled stainless steel.', 159.99, 'assets/images/water_bottle.jpg', 0.9, 1),
('Organic Cotton Towel', 'Soft towel from organic cotton.', 229.99, 'assets/images/cotton_towel.jpg', 2.0, 0);

