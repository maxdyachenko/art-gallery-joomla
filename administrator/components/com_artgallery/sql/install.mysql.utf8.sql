DROP TABLE IF EXISTS `#__gallerys_list`;
DROP TABLE IF EXISTS `#__users_imgs`;
CREATE TABLE `#__gallerys_list` (
	`id`       INT(11)     NOT NULL AUTO_INCREMENT,
	`user_id` INT(11) NOT NULL,
	`name` VARCHAR(255) NOT NULL,
	`avatar` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`id`)
)
	ENGINE =MyISAM
	AUTO_INCREMENT =0
	DEFAULT CHARSET =utf8;

CREATE TABLE `#__users_imgs` (
	`id`       INT(11)     NOT NULL AUTO_INCREMENT,
	`gallery_id` INT(11) NOT NULL,
	`user_id` INT(25) NOT NULL,
	`user_img` VARCHAR(4) NOT NULL,
	PRIMARY KEY (`id`)
)
	ENGINE =MyISAM
	AUTO_INCREMENT =0
	DEFAULT CHARSET =utf8;

