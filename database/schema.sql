CREATE DATABASE IF NOT EXISTS cafe_pos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cafe_pos;

CREATE TABLE categories (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(80) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE items (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  category_id INT UNSIGNED NOT NULL,
  name VARCHAR(120) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  active TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0,
  photo VARCHAR(80) NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  KEY items_category (category_id),
  CONSTRAINT items_category_fk FOREIGN KEY (category_id) REFERENCES categories (id)
) ENGINE=InnoDB;

CREATE TABLE floor_areas (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(40) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY floor_areas_name (name)
) ENGINE=InnoDB;

CREATE TABLE cafe_tables (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(40) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  active TINYINT(1) NOT NULL DEFAULT 1,
  area_id INT UNSIGNED NULL,
  PRIMARY KEY (id),
  KEY cafe_tables_area (area_id)
) ENGINE=InnoDB;

CREATE TABLE sales (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  table_id INT UNSIGNED NULL,
  table_name VARCHAR(40) NOT NULL,
  opened_at DATETIME NOT NULL,
  service_started_at DATETIME NULL,
  closed_at DATETIME NULL,
  payment_method ENUM('cash', 'online', 'credit') NULL,
  slip_image VARCHAR(80) NOT NULL DEFAULT '',
  pay_review VARCHAR(12) NOT NULL DEFAULT '',
  waiter_id INT UNSIGNED NULL,
  customer_id INT UNSIGNED NULL,
  held TINYINT(1) NOT NULL DEFAULT 0,
  total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  status ENUM('open', 'credit', 'paid') NOT NULL DEFAULT 'open',
  PRIMARY KEY (id),
  KEY sales_closed (closed_at),
  KEY sales_table_status (table_id, status),
  CONSTRAINT sales_table_fk FOREIGN KEY (table_id) REFERENCES cafe_tables (id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE customers (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(80) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  password_hash VARCHAR(255) NULL,
  created_at DATETIME NOT NULL,
  credit_limit DECIMAL(10,2) NULL,
  PRIMARY KEY (id),
  UNIQUE KEY customers_phone (phone)
) ENGINE=InnoDB;

CREATE TABLE open_tables (
  table_id INT UNSIGNED NOT NULL,
  sale_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (table_id),
  UNIQUE KEY open_tables_sale (sale_id),
  CONSTRAINT open_tables_table_fk FOREIGN KEY (table_id) REFERENCES cafe_tables (id),
  CONSTRAINT open_tables_sale_fk FOREIGN KEY (sale_id) REFERENCES sales (id)
) ENGINE=InnoDB;

CREATE TABLE sale_lines (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  sale_id INT UNSIGNED NOT NULL,
  item_id INT UNSIGNED NULL,
  item_name VARCHAR(120) NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  qty INT UNSIGNED NOT NULL,
  line_total DECIMAL(10,2) NOT NULL,
  added_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY sale_lines_sale (sale_id),
  CONSTRAINT sale_lines_sale_fk FOREIGN KEY (sale_id) REFERENCES sales (id),
  CONSTRAINT sale_lines_item_fk FOREIGN KEY (item_id) REFERENCES items (id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE settings (
  id TINYINT UNSIGNED NOT NULL,
  owner_password_hash VARCHAR(255) NOT NULL,
  staff_pin_hash VARCHAR(255) NOT NULL,
  currency_symbol VARCHAR(8) NOT NULL DEFAULT 'MVR',
  cafe_name VARCHAR(80) NOT NULL DEFAULT 'Ithaa Corner',
  bank_image VARCHAR(80) NOT NULL DEFAULT '',
  order_host VARCHAR(80) NOT NULL DEFAULT '',
  default_credit_limit DECIMAL(10,2) NULL DEFAULT 200.00,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(80) NOT NULL,
  username VARCHAR(40) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(40) NOT NULL,
  active TINYINT(1) NOT NULL DEFAULT 1,
  hourly_rate DECIMAL(10,2) NULL,
  PRIMARY KEY (id),
  UNIQUE KEY users_username (username)
) ENGINE=InnoDB;

CREATE TABLE shifts (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NOT NULL,
  clock_in DATETIME NOT NULL,
  clock_out DATETIME NULL,
  PRIMARY KEY (id),
  KEY shifts_user_open (user_id, clock_out),
  KEY shifts_user_in (user_id, clock_in)
) ENGINE=InnoDB;

CREATE TABLE tokens (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  token CHAR(64) NOT NULL,
  user_id INT UNSIGNED NULL,
  customer_id INT UNSIGNED NULL,
  expires_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY tokens_token (token),
  KEY tokens_user (user_id)
) ENGINE=InnoDB;

INSERT INTO categories (id, name, sort_order) VALUES
  (1, 'Drinks', 1),
  (2, 'Food', 2);

INSERT INTO items (category_id, name, price, active, sort_order) VALUES
  (1, 'Black Coffee Hard', 10.00, 1, 1),
  (1, 'Black Coffee Normal', 7.00, 1, 2),
  (1, 'Milk Coffee', 10.00, 1, 3),
  (1, 'Cereal Coffee', 15.00, 1, 4),
  (1, 'Lemon Tea', 10.00, 1, 5),
  (1, 'Black Tea Large', 5.00, 1, 6),
  (1, 'Black Tea Small', 3.00, 1, 7),
  (1, 'Water Bottle Small', 5.00, 1, 8),
  (1, 'Water Bottle Big', 10.00, 1, 9),
  (2, 'Nuts Half', 5.00, 1, 1),
  (2, 'Nuts Full', 10.00, 1, 2),
  (2, 'Hedhika', 5.00, 1, 3),
  (2, 'Bisket', 5.00, 1, 4);

INSERT INTO cafe_tables (name, sort_order, active) VALUES
  ('Hut 1', 1, 1),
  ('Hut 2', 2, 1),
  ('Hut 3', 3, 1),
  ('Hut 4', 4, 1),
  ('Outdoor 1', 5, 1),
  ('Outdoor 2', 6, 1),
  ('Outdoor 3', 7, 1),
  ('Outdoor 4', 8, 1),
  ('Indoor 1', 9, 1);

CREATE TABLE book_partners (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(80) NOT NULL,
  share_percent DECIMAL(5,2) NOT NULL DEFAULT 0,
  sort_order INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE book_days (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  sale_date DATE NOT NULL,
  transfer DECIMAL(10,2) NOT NULL DEFAULT 0,
  cash DECIMAL(10,2) NOT NULL DEFAULT 0,
  origin VARCHAR(20) NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  UNIQUE KEY book_days_date (sale_date)
) ENGINE=InnoDB;

CREATE TABLE book_people (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(80) NOT NULL,
  designation VARCHAR(80) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE book_salaries (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  person_id INT UNSIGNED NOT NULL,
  year_num SMALLINT NOT NULL,
  month_num TINYINT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY book_salary_month (person_id, year_num, month_num)
) ENGINE=InnoDB;

CREATE TABLE book_expenses (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  spent_on DATE NOT NULL,
  category VARCHAR(20) NOT NULL,
  kind VARCHAR(40) NOT NULL DEFAULT '',
  amount DECIMAL(10,2) NOT NULL,
  note VARCHAR(160) NOT NULL DEFAULT '',
  category_id INT UNSIGNED NULL,
  proof VARCHAR(80) NOT NULL DEFAULT '',
  PRIMARY KEY (id),
  KEY book_expenses_date (spent_on)
) ENGINE=InnoDB;

CREATE TABLE settlements (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  reference VARCHAR(24) NOT NULL DEFAULT '',
  customer_id INT UNSIGNED NULL,
  total_amount DECIMAL(10,2) NOT NULL,
  payment_method ENUM('cash', 'online') NOT NULL,
  payment_slip VARCHAR(80) NOT NULL DEFAULT '',
  settled_by INT UNSIGNED NULL,
  settled_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY settlements_reference (reference)
) ENGINE=InnoDB;

CREATE TABLE settlement_bills (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  settlement_id INT UNSIGNED NOT NULL,
  sale_id INT UNSIGNED NOT NULL,
  amount_applied DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY settlement_bills_sale (sale_id),
  KEY settlement_bills_settlement (settlement_id)
) ENGINE=InnoDB;

-- The first request creates a superadmin (admin / admin) and a waiter (waiter / 1234)
-- only when those accounts are missing. Change both before service.
CREATE TABLE roles (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  slug VARCHAR(40) NOT NULL,
  name VARCHAR(80) NOT NULL,
  built_in TINYINT(1) NOT NULL DEFAULT 0,
  sort_order INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY roles_slug (slug)
) ENGINE=InnoDB;

CREATE TABLE supply_items (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(80) NOT NULL,
  unit VARCHAR(20) NOT NULL DEFAULT '',
  active TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

CREATE TABLE supply_weeks (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  week_start DATE NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'requested',
  requested_by INT UNSIGNED NULL,
  quoted_by INT UNSIGNED NULL,
  accepted_by INT UNSIGNED NULL,
  expense_id INT UNSIGNED NULL,
  requested_at DATETIME NULL,
  quoted_at DATETIME NULL,
  accepted_at DATETIME NULL,
  PRIMARY KEY (id),
  UNIQUE KEY supply_weeks_start (week_start)
) ENGINE=InnoDB;

CREATE TABLE supply_lines (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  week_id INT UNSIGNED NOT NULL,
  item_id INT UNSIGNED NOT NULL,
  requested_qty DECIMAL(10,2) NOT NULL DEFAULT 0,
  quote_qty DECIMAL(10,2) NULL,
  quote_price DECIMAL(10,2) NULL,
  PRIMARY KEY (id),
  UNIQUE KEY supply_lines_item (week_id, item_id)
) ENGINE=InnoDB;

CREATE TABLE role_access (
  role_id INT UNSIGNED NOT NULL,
  area VARCHAR(40) NOT NULL,
  PRIMARY KEY (role_id, area)
) ENGINE=InnoDB;

INSERT INTO settings (id, owner_password_hash, staff_pin_hash, currency_symbol, cafe_name, bank_image, default_credit_limit)
VALUES (1, '', '', 'MVR', 'Ithaa Corner', '', 200.00);
