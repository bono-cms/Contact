
DROP TABLE IF EXISTS `bono_module_contact`;
CREATE TABLE `bono_module_contact` (
	
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `order` INT NOT NULL COMMENT 'Sort order',
    `published` varchar(1) NOT NULL COMMENT 'Whether this contact is published or not'

) DEFAULT CHARSET = UTF8;

DROP TABLE IF EXISTS `bono_module_contact_translations`;
CREATE TABLE `bono_module_contact_translations` (

    `id` INT NOT NULL COMMENT 'Contact ID',
	`lang_id` INT NOT NULL COMMENT 'Language id which can be found in another table',
	`name` varchar(254) NOT NULL COMMENT 'The name show in table of this contact',
	`phone` varchar(254) NOT NULL COMMENT 'Phone number. Main one usually',
	`email` varchar(254) NOT NULL,
	`description` TEXT NOT NULL

) DEFAULT CHARSET = UTF8;


DROP TABLE IF EXISTS `bono_module_contact_defaults`;
CREATE TABLE `bono_module_contact_defaults` (
	
	`lang_id` INT NOT NULL,
	`contact_id` INT NOT NULL
	
) DEFAULT CHARSET = UTF8;