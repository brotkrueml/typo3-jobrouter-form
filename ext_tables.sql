CREATE TABLE tx_jobrouterform_domain_model_transfer (
	uid int(11) unsigned AUTO_INCREMENT NOT NULL,
	pid int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	form_identifier VARCHAR(255) DEFAULT '' NOT NULL,
	action tinyint(1) unsigned DEFAULT '0' NOT NULL,
	relation_uid int(11) unsigned DEFAULT '0' NOT NULL,
	data text,
	transfer_success tinyint(1) unsigned DEFAULT '0' NOT NULL,
	transfer_date int(11) unsigned DEFAULT '0' NOT NULL,
	transfer_message text,

	PRIMARY KEY (uid),
	KEY pid (pid),
	KEY action (action),
	KEY in_queue (transfer_date, transfer_success),
	KEY erroneous (transfer_message(10), transfer_success)
);
