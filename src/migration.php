<?php
$statement = "
CREATE TABLE IF NOT EXISTS users(
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    username varchar(255) UNIQUE,
    first_name varchar(255),
    second_name varchar(255),
    email varchar(255) UNIQUE,
    password varchar(255),
    description varchar(255),
    bio varchar(255),
    image varchar(255),
    cover varchar(255),
    bakground varchar(255),
    banned_to TIMESTAMP,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)

); 



CREATE TABLE IF NOT EXISTS admins(
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id int UNSIGNED NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id) ,
    FOREIGN KEY (user_id) REFERENCES users(id)

); 


CREATE TABLE IF NOT EXISTS contacts(
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name varchar(255),
    second_name varchar(255),
    email varchar(255) ,
    phone varchar(100) ,
    message varchar(255),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS chat(
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    sender varchar(255),
    msg varchar(255) ,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    FOREIGN KEY (sender) REFERENCES users(username)
);

CREATE TABLE IF NOT EXISTS links(
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    username varchar(255),
    facebook varchar(255) ,
    instagram varchar(255) ,
    tiktok varchar(255) ,
    twitter varchar(255) ,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    FOREIGN KEY (username) REFERENCES users(username)
);

CREATE TABLE IF NOT EXISTS followers(
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    follower varchar(255),
    following varchar(255) ,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    FOREIGN KEY (follower) REFERENCES users(username),
    FOREIGN KEY (following) REFERENCES users(username)
);

CREATE TABLE IF NOT EXISTS stickers(
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    username varchar(255),
    sticker varchar(255) ,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    FOREIGN KEY (username) REFERENCES users(username)
);

CREATE TABLE IF NOT EXISTS favorite_stickers(
    id int UNSIGNED NOT NULL AUTO_INCREMENT,
    username varchar(255),
    sticker varchar(255),
    author varchar(255),
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    FOREIGN KEY (username) REFERENCES users(username),
    FOREIGN KEY (author) REFERENCES users(username)
);
";

$drop = '

DROP TABLE contacts;DROP TABLE  followers; DROP TABLE  links; DROP TABLE  chat  ; DROP TABLE admins;DROP TABLE users';