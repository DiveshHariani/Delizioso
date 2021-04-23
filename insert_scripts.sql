USE RESTAURANT;

INSERT INTO USER VALUES('divesh@gmail.com', SHA1('abcd'), 'Divesh', 0);

INSERT INTO ITEM(item_name, item_price, item_cat, item_sub_cat, item_vegOrNot, item_toa)
VALUES('BBC', 460, 'F', 'INDIAN', FALSE, 'A');

INSERT INTO ITEM(item_name, item_price, item_cat, item_sub_cat, item_vegOrNot, item_toa)
VALUES('Chicken Kolhapuri', 360, 'F', 'INDIAN', FALSE, 'A');

INSERT INTO ITEM(item_name, item_price, item_cat, item_sub_cat, item_vegOrNot, item_toa)
VALUES('Fish Fry', 560, 'F', 'INDIAN', FALSE, 'A');

SELECT * FROM FOOD_ORDER;

DELETE FROM FOOD_ORDER WHERE ord_user_id = 'karan@gmail.com';

SELECT * FROM ITEM;

SELECT * FROM USER;

INSERT INTO USER VALUES('sameer@gmail.com', SHA1('1234'), 'sameer', 2);

SELECT * FROM FOOD_ORDER WHERE ord_date = CURDATE();

SELECT o.order_id, i.item_name, o.ord_quantity, o.ord_date, o.ord_isComp, o.ord_mode, o.ord_dest
FROM FOOD_ORDER o
INNER JOIN ITEM i
ON i.item_id = o.ord_food_id
WHERE o.ord_date = CURDATE() AND o.ord_isComp = FALSE;

UPDATE FOOD_ORDER SET ord_isComp = False WHERE order_id = 8;

SELECT * FROM FOOD_ORDER WHERE ord_date = CURDATE();

SELECT * FROM FOOD_ORDER;

INSERT INTO REST_TABLE VALUES(1, './assets/Table1.jpg', 4);
INSERT INTO REST_TABLE VALUES(6, './assets/Table2.jpg', 4);
INSERT INTO REST_TABLE VALUES(5, './assets/Table3.jpg', 12);

DELETE FROM REST_TABLE WHERE table_num IN (4, 5, 6);

SELECT * FROM REST_TABLE;

DELETE FROM REST_tABLE WHERE TABLE_NUM IN ( 3);

SELECT * FROM RESERVATION;
SELECT * FROM FEEDBACK ORDER BY rating DESC LIMIT 2 ;

INSERT INTO RESERVATION(res_user_id, res_table_num, res_date, res_time, end_time) VALUES('$user_email', $table_num, DATE('$date'), TIME('$time'), DATE_ADD(TIME('$time'), INTERVAL 2 HOUR))



DELETE FROM RESERVATION WHERE res_table_num = 2;

ALTER TABLE RESERVATION ADD end_time TIME NOT NULL;