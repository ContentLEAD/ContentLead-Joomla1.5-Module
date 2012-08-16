DROP TABLE IF EXISTS `#__brafton`;
DROP TABLE IF EXISTS `#__brafton_categories`;
DROP TABLE IF EXISTS `#__brafton_options`;

CREATE TABLE `#__brafton` (
	`brafton_id` int(25) NOT NULL,
	`id` int(11) NOT NULL,
	`pic_id` int(11) NOT NULL,
	PRIMARY KEY (`brafton_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `#__brafton_categories` (
	`id` int(11) NOT NULL,
	`brafton_cat_id` int(25) NOT NULL,
	`section_id` int(25) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `#__brafton_options` (
	`id` INT(10) NULL AUTO_INCREMENT,  
	`options_name` VARCHAR(50) NULL,  
	`options_value` VARCHAR(100) NULL,  
	PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 
INSERT INTO `#__brafton_options` (`id`, `options_name`, `options_value`) VALUES (0, 'braf_api_key', 'Enter API Key');
