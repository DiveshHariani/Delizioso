CREATE SCHEMA RESTAURANT;
USE RESTAURANT;

CREATE TABLE USER(
	user_email VARCHAR(100) PRIMARY KEY,
    user_pwd VARCHAR(100) NOT NULL,
    user_name VARCHAR(50) NOT NULL,
    user_permission INT NOT NULL CHECK( user_permission >= 0 AND user_permission <= 2)
);

CREATE TABLE FEEDBACK(
	feedback_id INT PRIMARY KEY AUTO_INCREMENT,
    rating INT NOT NULL CHECK( rating >= 1 AND rating <= 5) DEFAULT 1,
    feed_date DATE NOT NULL,
    review VARCHAR(100),
    cust_email VARCHAR(100) NOT NULL,
    FOREIGN KEY(cust_email) REFERENCES USER(user_email) ON UPDATE CASCADE ON DELETE CASCADE 
);

CREATE TABLE ITEM(
	item_id INT PRIMARY KEY AUTO_INCREMENT,
    item_name VARCHAR(100) NOT NULL UNIQUE,
    item_price INT NOT NULL,
    item_cat VARCHAR(100) NOT NULL,
    item_sub_cat VARCHAR(100),
    item_vegOrNot BOOLEAN,
    item_toa VARCHAR(1) CHECK(item_toa = 'M' OR item_toa = 'A' OR item_toa = 'E')
);

CREATE TABLE REST_TABLE(
	table_num INT PRIMARY KEY,
    table_img VARCHAR(100),
    table_cap INT NOT NULL
);

CREATE TABLE RESERVATION(
	res_user_email VARCHAR(100) NOT NULL,
    res_table_num INT NOT NULL,
    res_date DATE,
    res_time TIME,
	FOREIGN KEY(res_user_email) REFERENCES USER(user_email) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(res_table_num) REFERENCES REST_TABLE(table_num) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE FOOD_ORDER(
	ord_user_id VARCHAR(100) NOT NULL,
    ord_food_id INT NOT NULL,
    ord_quantity INT NOT NULL DEFAULT 1,
    ord_price INT NOT NULL,
    ord_date DATE NOT NULL,
    ord_isComp BOOLEAN NOT NULL DEFAULT FALSE,
    ord_mode INT NOT NULL CHECK(ord_mode >= 1 AND ord_mode <= 2) DEFAULT 1,	# 1 --> in place, 2 --> Home delivery
    ord_dest VARCHAR(50) NOT NULL, # ord dest can be table number in case of booking and address in case of home delivery
    FOREIGN KEY(ord_user_id) REFERENCES USER(user_email) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY(ord_food_id) REFERENCES ITEM(item_id) ON DELETE CASCADE ON UPDATE CASCADE
);

ALTER TABLE FOOD_ORDER ADD order_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE RESERVATION ADD res_id INT PRIMARY KEY AUTO_INCREMENT;
