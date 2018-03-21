CREATE TABLE `smartobject_meta` (
  `metakey`   VARCHAR(50)  NOT NULL DEFAULT '',
  `metavalue` VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`metakey`)
)
  ENGINE = MyISAM;

INSERT INTO `smartobject_meta` VALUES ('version', '2');

CREATE TABLE `smartobject_link` (
  `linkid`     INT(11)      NOT NULL AUTO_INCREMENT,
  `date`       INT(11)      NOT NULL DEFAULT '0',
  `from_uid`   INT(11)      NOT NULL DEFAULT '0',
  `from_email` VARCHAR(255) NOT NULL DEFAULT '',
  `from_name`  VARCHAR(255) NOT NULL DEFAULT '',
  `to_uid`     INT(11)      NOT NULL DEFAULT '0',
  `to_email`   VARCHAR(255) NOT NULL DEFAULT '',
  `to_name`    VARCHAR(255) NOT NULL DEFAULT '',
  `link`       VARCHAR(255) NOT NULL DEFAULT '',
  `subject`    VARCHAR(255) NOT NULL DEFAULT '',
  `body`       TEXT         NOT NULL,
  `mid`        INT(11)      NOT NULL DEFAULT '0',
  `mid_name`   VARCHAR(255) NOT NULL DEFAULT '',

  PRIMARY KEY (`linkid`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;

CREATE TABLE `smartobject_tag` (
  `tagid`       INT(11)      NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(255) NOT NULL DEFAULT '',
  `description` TEXT         NOT NULL,
  PRIMARY KEY (`tagid`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;

CREATE TABLE `smartobject_tag_text` (
  `tagid`    INT(11)      NOT NULL DEFAULT 0,
  `language` VARCHAR(255) NOT NULL DEFAULT '',
  `value`    TEXT         NOT NULL,
  PRIMARY KEY (`tagid`, `language`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;

CREATE TABLE `smartobject_adsense` (
  `adsenseid`        INT(11)      NOT NULL AUTO_INCREMENT,
  `format`           VARCHAR(100) NOT NULL,
  `description`      TEXT         NOT NULL,
  `style`            TEXT         NOT NULL,
  `border_color`     VARCHAR(6)   NOT NULL DEFAULT '',
  `background_color` VARCHAR(6)   NOT NULL DEFAULT '',
  `link_color`       VARCHAR(6)   NOT NULL DEFAULT '',
  `url_color`        VARCHAR(6)   NOT NULL DEFAULT '',
  `text_color`       VARCHAR(6)   NOT NULL DEFAULT '',
  `client_id`        VARCHAR(100) NOT NULL DEFAULT '',
  `tag`              VARCHAR(50)  NOT NULL DEFAULT '',
  PRIMARY KEY (`adsenseid`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;

CREATE TABLE `smartobject_rating` (
  `ratingid` INT(11)      NOT NULL AUTO_INCREMENT,
  `dirname`  VARCHAR(255) NOT NULL,
  `item`     VARCHAR(255) NOT NULL,
  `itemid`   INT(11)      NOT NULL,
  `uid`      INT(11)      NOT NULL,
  `rate`     INT(1)       NOT NULL,
  `date`     INT(11)      NOT NULL,
  PRIMARY KEY (`ratingid`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;

CREATE TABLE `smartobject_currency` (
  `currencyid`       INT(11)      NOT NULL AUTO_INCREMENT,
  `iso4217`          VARCHAR(15)  NOT NULL,
  `name`             VARCHAR(255) NOT NULL,
  `symbol`           VARCHAR(15)  NOT NULL,
  `rate`             FLOAT        NOT NULL,
  `default_currency` INT(1)       NOT NULL,
  PRIMARY KEY (`currencyid`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;

INSERT INTO `smartobject_currency` VALUES (2, 'EUR', 'Euro', '�', 0.65, 0);
INSERT INTO `smartobject_currency` VALUES (3, 'USD', 'American dollar', '$', 0.9, 0);
INSERT INTO `smartobject_currency` VALUES (1, 'CAD', 'Canadian dollar', '$', 1, 1);

CREATE TABLE `smartobject_file` (
  `fileid`      INT(11)      NOT NULL AUTO_INCREMENT,
  `caption`     VARCHAR(255) NOT NULL,
  `url`         VARCHAR(255) NOT NULL,
  `description` TEXT         NOT NULL,
  `moduleid`    INT(11)      NOT NULL,
  `name`        VARCHAR(255) NOT NULL,
  `itemid`      INT(11)      NOT NULL,
  `item`        VARCHAR(255) NOT NULL,
  PRIMARY KEY (`fileid`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;

CREATE TABLE `smartobject_urllink` (
  `urllinkid`   INT(11)      NOT NULL AUTO_INCREMENT,
  `caption`     VARCHAR(255) NOT NULL,
  `url`         VARCHAR(255) NOT NULL,
  `description` TEXT         NOT NULL,
  `moduleid`    INT(11)      NOT NULL,
  `name`        VARCHAR(255) NOT NULL,
  `itemid`      INT(11)      NOT NULL,
  `item`        VARCHAR(255) NOT NULL,
  `target`      VARCHAR(10)  NOT NULL,
  PRIMARY KEY (`urllinkid`)
)
  ENGINE = MYISAM
  AUTO_INCREMENT = 1;

CREATE TABLE `smartobject_customtag` (
  `customtagid` INT(11)      NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(255) NOT NULL,
  `description` TEXT         NOT NULL,
  `content`     TEXT         NOT NULL,
  `language`    TEXT         NOT NULL,
  PRIMARY KEY (`customtagid`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;
