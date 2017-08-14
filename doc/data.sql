drop database iugo;
create database `iugo`;
use `iugo`;

CREATE TABLE IF NOT EXISTS `transaction`(
	`id` INT NOT NULL , 
	`user_id` INT NOT NULL, 
	`currency_amount` INT NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS `score_post`(
	`user_id` INT NOT NULL , 
	`leaderboard_id` INT NOT NULL, 
	`score` INT NOT NULL,
	PRIMARY KEY (user_id,leaderboard_id)
);

CREATE TABLE IF NOT EXISTS `user_setting` (
  user_id INT NOT NULL,
  data_key varchar(500) NOT NULL,
  data_value VARCHAR(100),
  PRIMARY KEY (user_id,data_key)
);