CREATE TABLE `emailaddresses` (
	`emailaddress_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`modified` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
	`created` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`account_id` BIGINT(20) UNSIGNED NOT NULL,
	`emailaddress` VARCHAR(255) NOT NULL,
	`passwordcrypt` CHAR(60) BINARY NOT NULL,
	PRIMARY KEY (`emailaddress_id`),
	UNIQUE KEY `account_id` (`account_id`),
	UNIQUE KEY `emailaddress` (`emailaddress`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `passwordrequests` (
	`passwordrequest_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`created` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`emailaddress_id` BIGINT(20) UNSIGNED NOT NULL,
	`passwordrequesthash` CHAR(32) BINARY NOT NULL,
	PRIMARY KEY (`passwordrequest_id`),
	UNIQUE KEY `passwordrequesthash` (`passwordrequesthash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
