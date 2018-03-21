=================================================
Title:   SmartObject 1.1.0
Date:    2012-03-31
Author:  Kenichi OHWADA
URL:     http://linux2.ohwada.net/
Email:   webmaster@ohwada.net
=================================================

This version adds the following change to 1.0.1
because the offical development site was lost

- Improved : Migrating to PHP 5.3
http://www.php.net/manual/en/migration53.deprecated.php
(1) ereg
(2) Assigning the return value of new by reference is now deprecated.

- Improved : Migrating to MySQL 5.5
(1) TYPE=MyISAM -> ENGINE=MyISAM

- Added : Japanaese language pack
- Added : Japanaese UTF-8 (ja_uft8) in fpdf

- Improved : SmartObject 1.0.1 patch for XOOPS Cube Legacy ( Impresscms fourm )
http://community.impresscms.org/modules/newbb/viewtopic.php?topic_id=2509&post_id=23627

(1) change "preferences" and "modules update" in admin memu.

- Fixed : SmartObject 1.0.1 bugs and patchs ( Impresscms fourm )
http://community.impresscms.org/modules/newbb/viewtopic.php?topic_id=2506&post_id=23622

(1) not read other language file in xoops_version.php
(2) undifined table in xoops_version.php
(3) undefined constant in module manager
(4) Undefined variable in admin's link

- Fixed : SmartObject 1.0.1 bug and patch for PDF ( Impresscms fourm )
http://community.impresscms.org/modules/newbb/viewtopic.php?topic_id=2510&post_id=23635

(1) not show the image
(2) Fatal error in specifing the image which doesn't exist
(3) undefined constant SRC
(4) undedined _MD_POSTEDON
(5) mojibake (character garble) in Japanese

* added files *

- fpdf/language/ja_utf8.php
- language/japanese/
- language/ja_utf8/

* changed files *

- xoops_version.php
- admin/menu.php
- class/smartobjecttable.php
- fpdf/fpdf.php
- fpdf/fpdf.inc.php
- fpdf/makepdf_class.php
- fpdf/language/japanese.php
- fpdf/font/
- language/english/modinfo.php

- changelog.txt
- admin/link.php
- class/smartmetagen.php
- class/smartobjectabout.php
- class/smartobjecthandler.php
- class/smartobjectsingleview.php
- class/smartprinterfriendly.php
- class/smarttip.php
- fpdf/language/ja_utf8.php
- include/functions.php
- include/captcha/captcha.php
- include/captcha/image.php
- include/captcha/text.php
- tcpdf/tcpdf.php
- templates/smartobject_about.html
- sql/mysql.sql

