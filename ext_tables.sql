#
# Table structure for table 'tx_ajaxsearch_config'
#
CREATE TABLE tx_ajaxsearch_config (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title varchar(25) DEFAULT '' NOT NULL,
	
	charset varchar(10) DEFAULT '' NOT NULL,
	language char(5) DEFAULT '' NOT NULL,
	
	mode tinyint(4) DEFAULT '0' NOT NULL,
	showall tinyint(4) DEFAULT '0' NOT NULL,
	showdesc tinyint(4) DEFAULT '0' NOT NULL,
	highlight tinyint(4) DEFAULT '0' NOT NULL,
	
	pages blob NOT NULL,
	recursive tinyint(4) DEFAULT '0' NOT NULL,
	resultpage blob NOT NULL,
	
	dbquery text NOT NULL,
	dblimit tinyint(4) DEFAULT '20' NOT NULL,
	parameters varchar(255) DEFAULT '' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);