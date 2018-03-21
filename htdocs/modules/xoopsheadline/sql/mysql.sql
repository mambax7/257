CREATE TABLE xoopsheadline (
  headline_id        SMALLINT(3) UNSIGNED  NOT NULL AUTO_INCREMENT,
  headline_name      VARCHAR(255)          NOT NULL DEFAULT '',
  headline_url       VARCHAR(255)          NOT NULL DEFAULT '',
  headline_rssurl    VARCHAR(255)          NOT NULL DEFAULT '',
  headline_encoding  VARCHAR(15)           NOT NULL DEFAULT '',
  headline_cachetime MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '3600',
  headline_asblock   TINYINT(1) UNSIGNED   NOT NULL DEFAULT '0',
  headline_display   TINYINT(1) UNSIGNED   NOT NULL DEFAULT '0',
  headline_weight    SMALLINT(3) UNSIGNED  NOT NULL DEFAULT '0',
  headline_mainfull  TINYINT(1) UNSIGNED   NOT NULL DEFAULT '1',
  headline_mainimg   TINYINT(1) UNSIGNED   NOT NULL DEFAULT '1',
  headline_mainmax   TINYINT(2) UNSIGNED   NOT NULL DEFAULT '10',
  headline_blockimg  TINYINT(1) UNSIGNED   NOT NULL DEFAULT '0',
  headline_blockmax  TINYINT(2) UNSIGNED   NOT NULL DEFAULT '10',
  headline_xml       TEXT                  NOT NULL,
  headline_updated   INT(10)               NOT NULL DEFAULT '0',
  PRIMARY KEY (headline_id)
)
  ENGINE = MyISAM;


INSERT INTO xoopsheadline VALUES
  (1, 'XOOPS Official Website', 'https://xoops.org/', 'https://xoops.org/backend.php', 'UTF-8', 86400, 0, 1, 0, 1,
      1, 10, 0, 10, '', 0);
