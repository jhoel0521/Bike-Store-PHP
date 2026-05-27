-- BLOQUE 1: CREACIÓN DE BASE DE DATOS Y TABLAS
-- Compatible con MySQL 8.x

DROP DATABASE IF EXISTS Bike_Store;
CREATE DATABASE Bike_Store CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE Bike_Store;

-- Tabla: categories
CREATE TABLE categories (
  category_id INT AUTO_INCREMENT PRIMARY KEY,
  category_name VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- Tabla: products
CREATE TABLE products (
  product_id INT AUTO_INCREMENT PRIMARY KEY,
  product_name VARCHAR(100) NOT NULL,
  foto VARCHAR(250) NULL,
  model_year SMALLINT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  category_id INT NOT NULL,
  CONSTRAINT fk_categories FOREIGN KEY (category_id) REFERENCES categories(category_id)
    ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Tabla: customers
CREATE TABLE customers (
  customer_id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  imagen VARCHAR(250) NULL,
  phone VARCHAR(25),
  email VARCHAR(100) NOT NULL,
  street VARCHAR(50),
  city VARCHAR(50),
  state VARCHAR(25)
) ENGINE=InnoDB;

-- Tabla: orders
CREATE TABLE orders (
  order_id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  order_date DATE NOT NULL, 
  user_id INT NOT NULL,
  estado VARCHAR(25) NOT NULL DEFAULT 'Pendiente',
  total_amount DECIMAL(10,2) NOT NULL, 
  CONSTRAINT fk_customer FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
    ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Tabla: order_items
CREATE TABLE order_items (
  order_items_id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  discount DECIMAL(4,2) NOT NULL DEFAULT 0,
  CONSTRAINT fk_order FOREIGN KEY (order_id) REFERENCES orders(order_id)
    ON DELETE RESTRICT,
  CONSTRAINT fk_product FOREIGN KEY (product_id) REFERENCES products(product_id)
    ON DELETE RESTRICT
) ENGINE=InnoDB;

-- BLOQUE 2: INSERCIÓN DE DATOS (CATEGORÍAS Y PRODUCTOS)
USE Bike_Store;

-- Inserción de categorías (puedes ampliar según tu catálogo real)
INSERT INTO categories (category_name) VALUES
('Mountain Bikes'),
('Road Bikes'),
('Electric Bikes'),
('Kids Bikes'),
('Cruiser Bikes'),
('Accessories');

-- Inserción de productos (Parte 1)
INSERT INTO products (product_name, foto, model_year, price, category_id)
VALUES
('Trek 820 - 2017', NULL, 2017, 379.99, 1),
('Ritchey Timberwolf Frameset - 2017', NULL, 2018, 749.99, 1),
('Surly Wednesday Frameset - 2017', NULL, 2019, 999.99, 1),
('Trek Fuel EX 8 29 - 2017', NULL, 2019, 2899.99, 1),
('Heller Shagamaw Frame - 2017', NULL, 2017, 1320.99, 1),
('Surly Ice Cream Truck Frameset - 2017', NULL, 2017, 469.99, 1),
('Trek Slash 8 27.5 - 2017', NULL, 2017, 3999.99, 1),
('Trek Remedy 29 Carbon Frameset - 2017', NULL, 2017, 1799.99, 1),
('Trek Conduit+ - 2017', NULL, 2017, 2999.99, 3),
('Surly Straggler - 2017', NULL, 2017, 1549.00, 2),
('Surly Straggler 650b - 2017', NULL, 2017, 1680.99, 2),
('Electra Townie Original 21D - 2017', NULL, 2017, 549.99, 5),
('Electra Cruiser 1 (24-Inch) - 2017', NULL, 2010, 269.99, 5),
('Electra Girl''s Hawaii 1 (16-inch) - 2015/2017', NULL, 2011, 269.99, 4),
('Electra Moto 1 - 2017', NULL, 2012, 529.99, 5),
('Electra Townie Original 7D EQ - 2017', NULL, 2013, 599.99, 5),
('Pure Cycles Vine 8-Speed - 2017', NULL, 2014, 429.00, 2),
('Pure Cycles Western 3-Speed - Women''s - 2015/2017', NULL, 2015, 449.00, 2),
('Pure Cycles William 3-Speed - 2017', NULL, 2017, 449.00, 2),
('Electra Townie Original 7D EQ - Women''s - 2017', NULL, 2017, 599.99, 5),
('Electra Cruiser 1 (24-Inch) - 2017', NULL, 2017, 269.99, 5),
('Electra Girl''s Hawaii 1 (16-inch) - 2015/2017', NULL, 2017, 269.99, 4),
('Electra Girl''s Hawaii 1 (20-inch) - 2015/2017', NULL, 2017, 299.99, 4),
('Electra Townie Original 21D - 2017', NULL, 2017, 549.99, 5),
('Electra Townie Original 7D - 2015/2017', NULL, 2017, 499.99, 5),
('Electra Townie Original 7D EQ - 2017', NULL, 2017, 599.99, 5),
('Surly Big Dummy Frameset - 2018', NULL, 2018, 999.99, 1),
('Surly Karate Monkey 27.5+ Frameset - 2018', NULL, 2018, 2499.99, 1),
('Trek X-Caliber 8 - 2018', NULL, 2018, 999.99, 1),
('Surly Ice Cream Truck Frameset - 2018', NULL, 2018, 999.99, 1);

-- BLOQUE 2 (Parte 2): INSERCIÓN DE PRODUCTOS (continuación)
USE Bike_Store;

INSERT INTO products (product_name, foto, model_year, price, category_id)
VALUES
('Surly Wednesday - 2018', NULL, 2018, 1632.99, 1),
('Trek Farley Alloy Frameset - 2018', NULL, 2018, 469.99, 1),
('Surly Wednesday Frameset - 2018', NULL, 2018, 469.99, 1),
('Trek Session DH 27.5 Carbon Frameset - 2018', NULL, 2018, 469.99, 1),
('Sun Bicycles Spider 3i - 2018', NULL, 2018, 832.99, 5),
('Surly Troll Frameset - 2018', NULL, 2018, 832.99, 1),
('Haro Flightline One ST - 2018', NULL, 2018, 379.99, 1),
('Haro Flightline Two 26 Plus - 2018', NULL, 2018, 549.99, 1),
('Trek Stache 5 - 2018', NULL, 2018, 1499.99, 1),
('Trek Fuel EX 9.8 29 - 2018', NULL, 2018, 4999.99, 1),
('Haro Shift R3 - 2018', NULL, 2018, 1469.99, 1),
('Trek Fuel EX 5 27.5 Plus - 2018', NULL, 2018, 2299.99, 1),
('Trek Fuel EX 9.8 27.5 Plus - 2018', NULL, 2018, 5299.99, 1),
('Haro SR 1.1 - 2018', NULL, 2018, 539.99, 2),
('Haro SR 1.2 - 2018', NULL, 2018, 869.99, 2),
('Haro SR 1.3 - 2018', NULL, 2018, 1409.99, 2),
('Trek Remedy 9.8 - 2018', NULL, 2018, 5299.99, 1),
('Trek Emonda S 4 - 2018', NULL, 1994, 1499.99, 2),
('Trek Domane SL 6 - 2018', NULL, 1995, 3499.99, 2),
('Trek Silque SLR 7 Women''s - 2018', NULL, 1996, 5999.99, 2),
('Trek Silque SLR 8 Women''s - 2018', NULL, 1997, 6499.99, 2),
('Surly Steamroller - 2018', NULL, 1998, 875.99, 2),
('Surly Ogre Frameset - 2018', NULL, 1999, 749.99, 1),
('Trek Domane SL Disc Frameset - 2018', NULL, 2000, 3199.99, 2),
('Trek Domane S 6 - 2018', NULL, 2001, 2699.99, 2),
('Trek Domane SLR 6 Disc - 2018', NULL, 2002, 5499.99, 2),
('Trek Emonda S 5 - 2018', NULL, 2003, 1999.99, 2),
('Trek Madone 9.2 - 2018', NULL, 2004, 4999.99, 2),
('Trek Domane S 5 Disc - 2018', NULL, 2005, 2599.99, 2),
('Sun Bicycles ElectroLite - 2018', NULL, 2006, 1559.99, 3),
('Trek Powerfly 8 FS Plus - 2018', NULL, 2007, 4999.99, 3),
('Trek Boone 7 - 2018', NULL, 2008, 3499.99, 2),
('Trek Boone Race Shop Limited - 2018', NULL, 2009, 3499.99, 2),
('Electra Townie Original 7D - 2018', NULL, 2010, 489.99, 5),
('Sun Bicycles Lil Bolt Type-R - 2018', NULL, 2011, 346.99, 4),
('Sun Bicycles Revolutions 24 - 2018', NULL, 2012, 250.99, 4),
('Sun Bicycles Revolutions 24 - Girl''s - 2018', NULL, 2013, 250.99, 4),
('Sun Bicycles Cruz 3 - 2018', NULL, 2014, 449.99, 5),
('Sun Bicycles Cruz 7 - 2018', NULL, 2015, 416.99, 5),
('Electra Amsterdam Original 3i - 2015/2018', NULL, 2017, 659.99, 5),
('Sun Bicycles Atlas X-Type - 2018', NULL, 2018, 416.99, 5),
('Sun Bicycles Biscayne Tandem 7 - 2018', NULL, 2018, 619.99, 5),
('Sun Bicycles Brickell Tandem 7 - 2018', NULL, 2018, 749.99, 5),
('Electra Cruiser Lux 1 - 2018', NULL, 2018, 439.99, 5),
('Electra Cruiser Lux Fat Tire 1 Ladies - 2018', NULL, 2018, 599.99, 5),
('Electra Girl''s Hawaii 1 16" - 2018', NULL, 2018, 299.99, 4),
('Electra Glam Punk 3i Ladies'' - 2018', NULL, 2018, 799.99, 5),
('Sun Bicycles Biscayne Tandem CB - 2018', NULL, 2018, 647.99, 5),
('Sun Bicycles Boardwalk (24-inch Wheels) - 2018', NULL, 2018, 402.99, 5),
('Sun Bicycles Brickell Tandem CB - 2018', NULL, 2018, 761.99, 5),
('Electra Amsterdam Fashion 7i Ladies'' - 2018', NULL, 2018, 1099.99, 5),
('Electra Amsterdam Original 3i Ladies'' - 2018', NULL, 2018, 659.99, 5),
('Trek Boy''s Kickster - 2015/2018', NULL, 2018, 149.99, 4),
('Sun Bicycles Lil Kitt''n - 2018', NULL, 2018, 109.99, 4),
('Haro Downtown 16 - 2018', NULL, 2018, 329.99, 4),
('Trek Girl''s Kickster - 2018', NULL, 2018, 149.99, 4),
('Trek Precaliber 12 Boys - 2018', NULL, 2018, 189.99, 4),
('Trek Precaliber 12 Girls - 2018', NULL, 2018, 189.99, 4),
('Trek Precaliber 16 Boys - 2018', NULL, 2018, 209.99, 4),
('Trek Precaliber 16 Girls - 2018', NULL, 2018, 209.99, 4);

-- BLOQUE 2 (Parte 3): INSERCIÓN DE PRODUCTOS (final)
USE Bike_Store;

INSERT INTO products (product_name, foto, model_year, price, category_id)
VALUES
('Trek 820 - 2019', NULL, 2019, 379.99, 1),
('Trek Marlin 5 - 2019', NULL, 2019, 489.99, 1),
('Trek Marlin 6 - 2019', NULL, 2019, 579.99, 1),
('Trek Fuel EX 8 29 - 2019', NULL, 2019, 3199.99, 1),
('Trek Marlin 7 - 2018/2019', NULL, 2019, 749.99, 1),
('Trek Ticket S Frame - 2019', NULL, 2019, 1469.99, 1),
('Trek X-Caliber 8 - 2019', NULL, 2019, 999.99, 1),
('Trek Kids'' Neko - 2019', NULL, 2019, 469.99, 4),
('Trek Fuel EX 7 29 - 2019', NULL, 2019, 2499.99, 1),
('Surly Krampus Frameset - 2019', NULL, 2019, 2499.99, 1),
('Surly Troll Frameset - 2019', NULL, 2019, 2499.99, 1),
('Trek Farley Carbon Frameset - 2019', NULL, 2019, 999.99, 1),
('Surly Krampus - 2019', NULL, 2019, 1499.00, 1),
('Trek Kids'' Dual Sport - 2019', NULL, 2019, 469.99, 4),
('Surly Big Fat Dummy Frameset - 2019', NULL, 2019, 469.99, 1),
('Surly Pack Rat Frameset - 2019', NULL, 2019, 469.99, 2),
('Surly ECR 27.5 - 2019', NULL, 2019, 1899.00, 1),
('Trek X-Caliber 7 - 2019', NULL, 2019, 919.99, 1),
('Trek Procaliber Frameset - 2019', NULL, 2019, 1499.99, 1),
('Trek Remedy 9.8 27.5 - 2019', NULL, 2019, 4999.99, 1),
('Trek Domane ALR 3 - 2019', NULL, 2019, 1099.99, 2),
('Trek Domane SL 6 Disc - 2019', NULL, 2019, 3499.99, 2),
('Trek Emonda SL 6 Disc - 2019', NULL, 2019, 2999.99, 2);

-- BLOQUE 3 (Parte 1): INSERCIÓN DE CLIENTES
USE Bike_Store;

INSERT INTO customers (first_name, last_name, imagen, phone, email, street, city, state) VALUES
('Debra', 'Burks', NULL, NULL, 'debra.burks@yahoo.com', '9273 Thorne Ave.', 'Orchard Park', 'NY'),
('Kasha', 'Todd', NULL, NULL, 'kasha.todd@yahoo.com', '910 Vine Street', 'Campbell', 'CA'),
('Tameka', 'Fisher', NULL, NULL, 'tameka.fisher@aol.com', '769C Honey Creek St.', 'Redondo Beach', 'CA'),
('Daryl', 'Spence', NULL, NULL, 'daryl.spence@aol.com', '988 Pearl Lane', 'Uniondale', 'NY'),
('Charolette', 'Rice', NULL, '(916) 381-6003', 'charolette.rice@msn.com', '107 River Dr.', 'Sacramento', 'CA'),
('Lyndsey', 'Bean', NULL, NULL, 'lyndsey.bean@hotmail.com', '769 West Road', 'Fairport', 'NY'),
('Latasha', 'Hays', NULL, '(716) 986-3359', 'latasha.hays@hotmail.com', '7014 Manor Station Rd.', 'Buffalo', 'NY'),
('Jacquline', 'Duncan', NULL, NULL, 'jacquline.duncan@yahoo.com', '15 Brown St.', 'Jackson Heights', 'NY'),
('Genoveva', 'Baldwin', NULL, NULL, 'genoveva.baldwin@msn.com', '8550 Spruce Drive', 'Port Washington', 'NY'),
('Pamelia', 'Newman', NULL, NULL, 'pamelia.newman@gmail.com', '476 Chestnut Ave.', 'Monroe', 'NY'),
('Deshawn', 'Mendoza', NULL, NULL, 'deshawn.mendoza@yahoo.com', '8790 Cobblestone Street', 'Monsey', 'NY'),
('Robby', 'Sykes', NULL, '(516) 583-7761', 'robby.sykes@hotmail.com', '486 Rock Maple Street', 'Hempstead', 'NY'),
('Lashawn', 'Ortiz', NULL, NULL, 'lashawn.ortiz@msn.com', '27 Washington Rd.', 'Longview', 'TX'),
('Garry', 'Espinoza', NULL, NULL, 'garry.espinoza@hotmail.com', '7858 Rockaway Court', 'Forney', 'TX'),
('Linnie', 'Branch', NULL, NULL, 'linnie.branch@gmail.com', '314 South Columbia Ave.', 'Plattsburgh', 'NY'),
('Emmitt', 'Sanchez', NULL, '(212) 945-8823', 'emmitt.sanchez@hotmail.com', '461 Squaw Creek Road', 'New York', 'NY'),
('Caren', 'Stephens', NULL, NULL, 'caren.stephens@msn.com', '914 Brook St.', 'Scarsdale', 'NY'),
('Georgetta', 'Hardin', NULL, NULL, 'georgetta.hardin@aol.com', '474 Chapel Dr.', 'Canandaigua', 'NY'),
('Lizzette', 'Stein', NULL, NULL, 'lizzette.stein@yahoo.com', '19 Green Hill Lane', 'Orchard Park', 'NY'),
('Aleta', 'Shepard', NULL, NULL, 'aleta.shepard@aol.com', '684 Howard St.', 'Sugar Land', 'TX'),
('Tobie', 'Little', NULL, NULL, 'tobie.little@gmail.com', '10 Silver Spear Dr.', 'Victoria', 'TX'),
('Adelle', 'Larsen', NULL, NULL, 'adelle.larsen@gmail.com', '683 West Kirkland Dr.', 'East Northport', 'NY'),
('Kaylee', 'English', NULL, NULL, 'kaylee.english@msn.com', '8786 Fulton Rd.', 'Hollis', 'NY'),
('Corene', 'Wall', NULL, NULL, 'corene.wall@msn.com', '9601 Ocean Rd.', 'Atwater', 'CA'),
('Regenia', 'Vaughan', NULL, NULL, 'regenia.vaughan@gmail.com', '44 Stonybrook Street', 'Mahopac', 'NY'),
('Theo', 'Reese', NULL, '(562) 215-2907', 'theo.reese@gmail.com', '8755 W. Wild Horse St.', 'Long Beach', 'NY'),
('Santos', 'Valencia', NULL, NULL, 'santos.valencia@yahoo.com', '7479 Carpenter Street', 'Sunnyside', 'NY'),
('Jeanice', 'Frost', NULL, NULL, 'jeanice.frost@hotmail.com', '76 Devon Lane', 'Ossining', 'NY'),
('Syreeta', 'Hendricks', NULL, NULL, 'syreeta.hendricks@msn.com', '193 Spruce Road', 'Mahopac', 'NY'),
('Jamaal', 'Albert', NULL, NULL, 'jamaal.albert@gmail.com', '853 Stonybrook Street', 'Torrance', 'CA'),
('Williemae', 'Holloway', NULL, '(510) 246-8375', 'williemae.holloway@msn.com', '69 Cypress St.', 'Oakland', 'CA'),
('Araceli', 'Golden', NULL, NULL, 'araceli.golden@msn.com', '12 Ridgeview Ave.', 'Fullerton', 'CA'),
('Deloris', 'Burke', NULL, NULL, 'deloris.burke@hotmail.com', '895 Edgemont Drive', 'Palos Verdes Peninsula', 'CA'),
('Brittney', 'Woodward', NULL, NULL, 'brittney.woodward@aol.com', '960 River St.', 'East Northport', 'NY'),
('Guillermina', 'Noble', NULL, NULL, 'guillermina.noble@msn.com', '6 Del Monte Lane', 'Baldwinsville', 'NY'),
('Bernita', 'Mcdaniel', NULL, NULL, 'bernita.mcdaniel@hotmail.com', '2 Peg Shop Ave.', 'Liverpool', 'NY'),
('Melia', 'Brady', NULL, NULL, 'melia.brady@gmail.com', '907 Shirley Rd.', 'Maspeth', 'NY');

-- BLOQUE 3 (Parte 2): INSERCIÓN DE CLIENTES (final)
USE Bike_Store;

INSERT INTO customers (first_name, last_name, imagen, phone, email, street, city, state) VALUES
('Ricky', 'Andersen', NULL, NULL, 'ricky.andersen@gmail.com', '62 E. Orange Ave.', 'Riverside', 'CA'),
('Dorian', 'Vasquez', NULL, NULL, 'dorian.vasquez@aol.com', '23 10th St.', 'Albany', 'NY'),
('Marlana', 'Hernandez', NULL, NULL, 'marlana.hernandez@yahoo.com', '121 East Second Dr.', 'Yonkers', 'NY'),
('Wynell', 'Henson', NULL, '(415) 444-2902', 'wynell.henson@msn.com', '8298 Bald Hill Rd.', 'San Francisco', 'CA'),
('Jerrell', 'Moses', NULL, NULL, 'jerrell.moses@hotmail.com', '422 Beechwood St.', 'Elmira', 'NY'),
('Donette', 'Chaney', NULL, NULL, 'donette.chaney@gmail.com', '62 Creek St.', 'Utica', 'NY'),
('Lonna', 'Bush', NULL, NULL, 'lonna.bush@hotmail.com', '787 Ridge St.', 'Binghamton', 'NY'),
('Terry', 'Oneill', NULL, NULL, 'terry.oneill@hotmail.com', '542 Madison Dr.', 'New Rochelle', 'NY'),
('Kelley', 'Hines', NULL, NULL, 'kelley.hines@gmail.com', '8821 Glenwood Ave.', 'Staten Island', 'NY'),
('Patience', 'Nash', NULL, NULL, 'patience.nash@yahoo.com', '374 Mayflower Dr.', 'Floral Park', 'NY'),
('Kaitlin', 'Riddle', NULL, '(212) 384-2980', 'kaitlin.riddle@aol.com', '92 Elm St.', 'New York', 'NY'),
('Lashon', 'Cantrell', NULL, NULL, 'lashon.cantrell@msn.com', '763 Lincoln Dr.', 'Queens', 'NY'),
('Tama', 'Carpenter', NULL, NULL, 'tama.carpenter@gmail.com', '944 Dogwood Lane', 'Cheektowaga', 'NY'),
('Aida', 'Cochran', NULL, NULL, 'aida.cochran@yahoo.com', '338 Jefferson St.', 'Brooklyn', 'NY'),
('Grady', 'Sparks', NULL, NULL, 'grady.sparks@aol.com', '22 Spruce Street', 'Brooklyn', 'NY');

-- BLOQUE 4 (Parte 1): INSERCIÓN DE ÓRDENES
USE Bike_Store;

INSERT INTO orders (customer_id, order_date, user_id, estado, total_amount) VALUES
(1, '2021-02-18', 1, 'Pendiente', 0),
(2, '2021-02-20', 1, 'Pendiente', 0),
(3, '2021-03-01', 1, 'Pendiente', 0),
(4, '2021-03-05', 1, 'Pendiente', 0),
(5, '2021-03-06', 1, 'Pendiente', 0),
(6, '2021-03-09', 1, 'Pendiente', 0),
(7, '2021-03-10', 1, 'Pendiente', 0),
(8, '2021-03-12', 1, 'Pendiente', 0),
(9, '2021-03-13', 1, 'Pendiente', 0),
(10, '2021-03-16', 1, 'Pendiente', 0),
(11, '2021-03-20', 1, 'Pendiente', 0),
(12, '2021-03-22', 1, 'Pendiente', 0),
(13, '2021-03-25', 1, 'Pendiente', 0),
(14, '2021-03-26', 1, 'Pendiente', 0),
(15, '2021-03-28', 1, 'Pendiente', 0),
(16, '2021-03-30', 1, 'Pendiente', 0),
(17, '2021-04-02', 1, 'Pendiente', 0),
(18, '2021-04-05', 1, 'Pendiente', 0),
(19, '2021-04-09', 1, 'Pendiente', 0),
(20, '2021-04-12', 1, 'Pendiente', 0),
(21, '2021-04-15', 1, 'Pendiente', 0),
(22, '2021-04-18', 1, 'Pendiente', 0),
(23, '2021-04-20', 1, 'Pendiente', 0),
(24, '2021-04-22', 1, 'Pendiente', 0),
(25, '2021-04-25', 1, 'Pendiente', 0),
(26, '2021-04-28', 1, 'Pendiente', 0),
(27, '2021-05-01', 1, 'Pendiente', 0),
(28, '2021-05-03', 1, 'Pendiente', 0),
(29, '2021-05-06', 1, 'Pendiente', 0),
(30, '2021-05-08', 1, 'Pendiente', 0),
(31, '2021-05-10', 1, 'Pendiente', 0),
(32, '2021-05-12', 1, 'Pendiente', 0),
(33, '2021-05-14', 1, 'Pendiente', 0),
(34, '2021-05-16', 1, 'Pendiente', 0),
(35, '2021-05-18', 1, 'Pendiente', 0),
(36, '2021-05-20', 1, 'Pendiente', 0),
(37, '2021-05-22', 1, 'Pendiente', 0),
(38, '2021-05-24', 1, 'Pendiente', 0),
(39, '2021-05-26', 1, 'Pendiente', 0),
(40, '2021-05-28', 1, 'Pendiente', 0),
(41, '2021-05-30', 1, 'Pendiente', 0),
(42, '2021-06-01', 1, 'Pendiente', 0),
(43, '2021-06-03', 1, 'Pendiente', 0),
(44, '2021-06-05', 1, 'Pendiente', 0),
(45, '2021-06-08', 1, 'Pendiente', 0),
(46, '2021-06-10', 1, 'Pendiente', 0),
(47, '2021-06-12', 1, 'Pendiente', 0),
(48, '2021-06-15', 1, 'Pendiente', 0),
(49, '2021-06-18', 1, 'Pendiente', 0),
(50, '2021-06-20', 1, 'Pendiente', 0);

-- BLOQUE 4 (Parte 2): INSERCIÓN DE DETALLES DE ÓRDENES
USE Bike_Store;

INSERT INTO order_items (order_id, product_id, quantity, price, discount) VALUES
(1, 5, 2, 1320.99, 0.10),
(1, 3, 1, 999.99, 0.00),
(2, 8, 1, 1799.99, 0.05),
(3, 2, 3, 749.99, 0.00),
(3, 9, 2, 2999.99, 0.15),
(4, 15, 1, 529.99, 0.00),
(5, 14, 2, 269.99, 0.00),
(6, 17, 1, 429.00, 0.00),
(7, 18, 2, 449.00, 0.05),
(8, 19, 1, 449.00, 0.10),
(9, 20, 1, 599.99, 0.10),
(10, 21, 1, 269.99, 0.00),
(11, 22, 3, 269.99, 0.05),
(12, 23, 2, 299.99, 0.00),
(13, 24, 1, 549.99, 0.00),
(14, 25, 1, 499.99, 0.00),
(15, 26, 1, 599.99, 0.10),
(16, 28, 1, 2499.99, 0.10),
(17, 30, 1, 999.99, 0.00),
(18, 32, 2, 4999.99, 0.05),
(19, 33, 2, 1469.99, 0.10),
(20, 36, 1, 1499.99, 0.00),
(21, 37, 1, 4999.99, 0.10),
(22, 39, 1, 869.99, 0.00),
(23, 41, 2, 3499.99, 0.05),
(24, 42, 1, 6499.99, 0.10),
(25, 43, 1, 875.99, 0.00),
(26, 45, 1, 2699.99, 0.10),
(27, 46, 1, 5499.99, 0.05),
(28, 47, 2, 1999.99, 0.00),
(29, 49, 1, 4999.99, 0.10),
(30, 50, 1, 4999.99, 0.15),
(31, 51, 1, 1999.99, 0.00),
(32, 53, 2, 2599.99, 0.00),
(33, 54, 1, 4999.99, 0.10),
(34, 55, 1, 1999.99, 0.00),
(35, 57, 1, 1499.99, 0.10),
(36, 59, 1, 3199.99, 0.00),
(37, 60, 1, 3799.99, 0.00),
(38, 61, 1, 2699.99, 0.05),
(39, 62, 1, 2999.99, 0.10),
(40, 63, 1, 4499.99, 0.10),
(41, 64, 2, 7499.99, 0.10),
(42, 65, 1, 11999.99, 0.15),
(43, 66, 1, 559.99, 0.00),
(44, 67, 1, 269.99, 0.00),
(45, 68, 1, 899.99, 0.10),
(46, 69, 2, 319.99, 0.00),
(47, 70, 1, 479.99, 0.10),
(48, 71, 1, 699.99, 0.00),
(49, 72, 1, 2999.99, 0.10),
(50, 75, 1, 4999.99, 0.15);