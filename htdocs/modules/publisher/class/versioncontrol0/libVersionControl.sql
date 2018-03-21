#
#	$RCSfile: libVersionControl.sql,v $
#	@author 	$Author: Cornelius Bolten $
#	@version	$Revision: 1.3 $
#
#	@description:
#	sql-setup-script for libVersionControl
#

CREATE TABLE `libVersionControl` (
        `vc_id`					int(10)     NOT NULL auto_increment,
        `vc_content_id`         int(5)      NOT NULL default '0',
        `vc_content_table`      varchar(50) NOT NULL default '',
        `vc_content_version`    int(5)      NOT NULL default '0',
        `vc_content_editor`     int(5)      NOT NULL default '0',
        `vc_content_date`       varchar(15) NOT NULL default '',
        `vc_content_data`       longtext	NOT NULL,
        `vc_content_hash`       varchar(32) NOT NULL default '',
        `vc_content_changes`	longtext,		

        PRIMARY KEY  (`vc_id`),
        UNIQUE KEY `vc_content_id` (
                `vc_content_id`,
                `vc_content_table`,
                `vc_content_version`,
                `vc_content_hash`
        )
) ENGINE=MyISAM COMMENT='VersionControl-Table' AUTO_INCREMENT=1 ;

