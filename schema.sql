CREATE DATABASE IF NOT EXISTS yeti_cave
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;
	
USE yeti_cave;

CREATE TABLE IF NOT EXISTS users (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	registration_date DATETIME NOT NULL,
	email VARCHAR(128) NOT NULL,
	name VARCHAR(128) NOT NULL,
	password VARCHAR(64) NOT NULL,
	avatar VARCHAR(512),
	contacts VARCHAR(512),
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS categories (
	id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
	name VARCHAR(128),
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS lots (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	creation_date DATETIME NOT NULL,
	name VARCHAR(512) NOT NULL,
	description TEXT,
	picture VARCHAR(512),
	start_price INT UNSIGNED NOT NULL,
	end_date DATETIME NOT NULL,
	bet_step MEDIUMINT UNSIGNED NOT NULL,
	
	author INT UNSIGNED NOT NULL,
	winner INT UNSIGNED NOT NULL,
	category TINYINT UNSIGNED NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS bids (
	id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
	date DATETIME NOT NULL,
	amount INT UNSIGNED NOT NULL,
	
	user INT UNSIGNED NOT NULL,
	lot BIGINT UNSIGNED NOT NULL,
	PRIMARY KEY (id)
);

CREATE UNIQUE user_email ON users(email);
CREATE UNIQUE user_name ON users(name);
CREATE UNIQUE category_name ON categories(name);

CREATE INDEX lots_category ON lots(category);
CREATE INDEX bids_user ON bids(user);
