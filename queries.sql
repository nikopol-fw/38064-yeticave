INSERT INTO `categories` (`name`)
VALUES ('Доски и лыжи');

INSERT INTO `categories` (`name`)
VALUES ('Крепления');

INSERT INTO `categories` (`name`)
VALUES ('Ботинки');

INSERT INTO `categories` (`name`)
VALUES ('Одежда');

INSERT INTO `categories` (`name`)
VALUES ('Инструменты');

INSERT INTO `categories` (`name`)
VALUES ('Разное');


INSERT INTO `users` (`registration_date`, `email`, `name`, `password`)
VALUES ('2001-05-21 13:54:05', 'mir.trud.may@yandex.ru', 'Alex_hero', '123');

INSERT INTO `users` (`registration_date`, `email`, `name`, `password`)
VALUES ('2011-11-13 01:23:18', 'yunec@mail.ru', 'little.boy', '123');

INSERT INTO `users` (`registration_date`, `email`, `name`, `password`)
VALUES ('2111-01-27 07:50:07', 'future-man@global.web', 'anonym_from_future', '123');


INSERT INTO `lots` (`name`, `picture`, `creation_date`, `end_date`, `start_price`, `bet_step`, `author`, `category`)
VALUES ('2014 Rossignol District Snowboard', 'img/lot-1.jpg', '2018-04-30 21:11:05', '2018-05-25 23:59:59', 10999, 500, 1, 1);

INSERT INTO `lots` (`name`, `picture`, `creation_date`, `end_date`, `start_price`, `bet_step`, `author`, `category`)
VALUES ('DC Ply Mens 2016/2017 Snowboard', 'img/lot-2.jpg', '2018-04-30 11:23:45', '2018-05-26 23:59:59', 159999, 1000, 2, 1);

INSERT INTO `lots` (`name`, `picture`, `creation_date`, `end_date`, `start_price`, `bet_step`, `author`, `category`)
VALUES ('Крепления Union Contact Pro 2015 года размер L/XL', 'img/lot-3.jpg', '2018-05-01 09:43:25', '2018-05-28 23:59:59', 8000, 250, 1, 2);

INSERT INTO `lots` (`name`, `picture`, `creation_date`, `end_date`, `start_price`, `bet_step`, `author`, `category`)
VALUES ('Ботинки для сноуборда DC Mutiny Charocal', 'img/lot-4.jpg', '2018-05-03 15:41:13', '2018-05-27 23:59:59', 10999, 500, 3, 3);

INSERT INTO `lots` (`name`, `picture`, `creation_date`, `end_date`, `start_price`, `bet_step`, `author`, `category`)
VALUES ('Куртка для сноуборда DC Mutiny Charocal', 'img/lot-5.jpg', '2018-04-25 19:37:39', '2018-05-30 23:59:59', 7500, 200, 2, 4);

INSERT INTO `lots` (`name`, `picture`, `creation_date`, `end_date`, `start_price`, `bet_step`, `author`, `category`)
VALUES ('Маска Oakley Canopy', 'img/lot-6.jpg', '2018-05-07 20:41:09', '2018-05-25 23:59:59', 5400, 150, 1, 6);

INSERT INTO `bids` (`date`, `amount`, `user`, `lot`)
VALUES ('2018-05-01 20:32:19', 11499, 2, 1);

INSERT INTO `bids` (`date`, `amount`, `user`, `lot`)
VALUES ('2018-05-11 13:41:31', 8500, 3, 3);

INSERT INTO `bids` (`date`, `amount`, `user`, `lot`)
VALUES ('2018-05-18 23:39:15', 9000, 1, 3);


SELECT `name` FROM `categories`;

SELECT DISTINCT `lots`.`name`, `start_price`, `picture`, MAX(IF(`amount` IS NULL, `start_price`, `amount`)) AS `price`, COUNT(`lot`) AS `bids_number`, `categories`.`name`, `creation_date`
FROM `lots` 
LEFT JOIN `bids` ON `lots`.`id` = `bids`.`lot`
INNER JOIN `categories` ON `lots`.`category` = `categories`.`id`
WHERE CURRENT_TIMESTAMP() < `end_date`
GROUP BY `lots`.`name`, `start_price`, `picture`, `creation_date`, `category`
ORDER BY `creation_date` DESC;

SELECT `lots`.`id`, `lots`.`name`, `lots`.`description`, `lots`.`picture`, `lots`.`creation_date`, `lots`.`end_date`, `lots`.`start_price`, `lots`.`bet_step`, `lots`.`author`, `lots`.`winner`, `categories`.`name` AS `category_name`
FROM `lots`
INNER JOIN `categories` ON `lots`.`category` = `categories`.`id`
WHERE `lots`.`id` = 1;

UPDATE `lots`
SET `lots`.`name` = 'Новое название лота'
WHERE `lots`.`id` = 1;

SELECT `bids`.`date`, `bids`.`amount`, `bids`.`user`
FROM `bids`
WHERE `bids`.`lot` = 3
ORDER BY `bids`.`date` DESC
LIMIT 10;
