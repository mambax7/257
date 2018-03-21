#
# Table structure for table `xsitemap_plugin`
#

CREATE TABLE `xsitemap_plugin` (
  `plugin_id`           INT(8)       NOT NULL  AUTO_INCREMENT,
  `plugin_name`         VARCHAR(20)  NOT NULL,
  `plugin_mod_version`  VARCHAR(5)   NOT NULL,
  `plugin_mod_table`    VARCHAR(255) NOT NULL,
  `plugin_cat_id`       VARCHAR(255) NOT NULL,
  `plugin_cat_pid`      VARCHAR(255) NOT NULL,
  `plugin_cat_name`     VARCHAR(255) NOT NULL,
  `plugin_weight`       VARCHAR(255) NOT NULL,
  `plugin_call`         VARCHAR(255) NOT NULL,
  `plugin_submitter`    INT(10)      NOT NULL  DEFAULT '0',
  `plugin_date_created` INT(10)      NOT NULL  DEFAULT '0',
  `plugin_online`       TINYINT(1)   NOT NULL  DEFAULT '0',
  PRIMARY KEY (`plugin_id`),
  KEY `plugin_name` (`plugin_name`)
)
  ENGINE = MyISAM;

INSERT INTO `xsitemap_plugin` (`plugin_name`, `plugin_mod_version`, `plugin_mod_table`, `plugin_cat_id`, `plugin_cat_pid`, `plugin_cat_name`, `plugin_weight`, `plugin_call`, `plugin_submitter`, `plugin_date_created`, `plugin_online`)
VALUES
  ('News', '1.6x', 'topics', 'topic_id', 'topic_pid', 'topic_title', 'topic_title', 'index.php?storytopic=', 1, 1250632800, 1),
  ('Articles', '2.41', 'ams_topics', 'topic_id', 'topic_pid', 'topic_title', 'topic_title', 'index.php?storytopic=', 1, 1250632800, 1),
  ('Classifieds', '2.53', 'classifieds_categories', 'cid', 'pid', 'title', 'title', 'catview.php?cid=', 1, 1250805600, 1),
  ('Jobs', '4.4', 'jobs_categories', 'cid', 'pid', 'title', 'title', 'jobscat.php?cid=', 1, 1250805600, 1),
  ('Publisher', '1.0x', 'publisher_categories', 'categoryid', 'parentid', 'name', 'weight', 'category.php?categoryid=', 1, 1250805600, 1),
  ('Oledrion', '2.2', 'oledrion_cat', 'cat_cid', 'cat_pid', 'cat_title', 'cat_title', 'category.php?cat_cid=', 1, 1250805600, 1),
  ('Smart Section', '2.14', 'smartsection_categories', 'categoryid', 'parentid', 'name', 'name', 'category.php?categoryid=', 1, 1250805600, 1),
  ('Extgallery', '1.0.5', 'extgallery_publiccat', 'cat_id', 'cat_id', 'cat_name', 'cat_name', 'public-categories.php?id=', 1, 1250805600, 1),
  ('MyAlbum', '2.8.4', 'myalbum_cat', 'cid', 'pid', 'title', 'title', 'viewcat.php?cid=', 1, 1250805600, 1),
  ('MyLinks', '3.1x', 'mylinks_cat', 'cid', 'pid', 'title', 'title', 'viewcat.php?cid=', 1, 1370536200, 1),
  ('Newbb', '4.3x', 'bb_forums', 'forum_id', 'parent_forum', 'forum_name', 'forum_name', 'viewforum.php?forum=', 1, 1370536200, 1),
  ('XoopsFaq', '1.23', 'xoopsfaq_categories', 'category_id', 'category_id', 'category_title', 'category_title', 'index.php?cat_id=', 1, 1424275386, 1),
  ('Lexikon', '2.00', 'lxcategories', 'categoryID', 'parent', 'name', 'weight', 'category.php?categoryID=', 1, 1424275386, 1),
  ('WfDownloads', '3.2x', 'wfdownloads_cat', 'cid', 'pid', 'title', 'weight', 'viewcat.php?cid=', 1, 1424275386, 1),
  ('MyDownloads', '1.20', 'mydownloads_cat', 'cid', 'pid', 'title', 'title', 'viewcat.php?cid=', 1, 1424275386, 1),
  ('FmContent', '1.10', 'fmcontent_topic', 'topic_id', 'topic_pid', 'topic_title', 'topic_weight', 'content.php?id=', 1, 1424275386, 1),
  ('AdsLight', '2.2', 'adslight_categories', 'cid', 'pid', 'title', 'ordre', 'viewcats.php?cid=', 1, 1424275386, 1),
  ('MyWords', '2.2', 'mod_mywords_categories', 'id_cat', 'parent', 'short_name', 'short_name', 'categories.php?page=', 1, 1424275386, 1),
  ('Qpages', '2', 'mod_qpages_categos', 'id_cat', 'parent', 'name', 'name', 'catego.php?cat=', 1, 1424275386, 1),
  ('Portfolio', '1.31', 'portfolio_categos', 'id_cat', 'parent', 'nombre', 'nombre', 'categos.php?id=', 1, 1424275386, 1),
  ('SmartFaq', '1.12', 'smartfaq_categories', 'categoryid', 'parentid', 'name', 'weight', 'category.php?categoryid=', 1, 1459128937, 1),
  ('TDMDownloads', '1.62', 'tdmdownloads_cat', 'cat_cid', 'cat_pid', 'cat_title', 'cat_weight', 'viewcat.php?cid=', 1, 1424275386, 1),
  ('WfLinks', '1.10', 'wflinks_cat', 'cid', 'pid', 'title', 'weight', 'viewcat.php?cid=', 1, 1424275386, 1),
  ('eGuide', '2.63', 'eguide_category', 'catid', 'catpri', 'catname', 'weight', 'index.php?cat=', 1, 1424275386, 1),
  ('PDLinks', '1.0', 'PDlinks_cat', 'cid', 'pid', 'title', 'weight', 'viewcat.php?cid=', 1, 1424275386, 1),
  ('Recette', '2.2', 'recette', 'topic_id', 'topic_pid', 'topic_title', 'topic_title', 'index.php?storytopic=', 1, 1424275386, 1),
  ('Addresses', '1.7', 'addresses_cat', 'cid', 'pid', 'title', 'title', 'viewcat.php?cid=', 1, 1424275386, 1),
  ('Xyp4all', '1.58', 'xyp_cat', 'cid', 'pid', 'title', 'title', 'viewcat.php?cid=', 1, 1424275386, 1),
  ('MxDirectory', '3.01', 'xdir_cat', 'cid', 'pid', 'title', 'title', 'viewcat.php?cid=', 1, 1424275386, 1),
  ('AmReviews', '0.10', 'amreview_cat', 'id', 'cat_parentid', 'cat_title', 'cat_weight', 'index.php?id=', 1, 1424275386, 1),
  ('AMS', '3.00', 'ams_topics', 'topic_id', 'topic_pid', 'topic_title', 'weight', 'index.php?storytopic=', 1, 1424275386, 1),
  ('MyTube', '1.04', 'xoopstube_cat', 'cid', 'pid', 'title', 'weight', 'viewcat.php?cid=', 1, 1424275386, 1),
  ('SongList', '1.14', 'songlist_category', 'cid', 'pid', 'name', 'weight', 'index.php?cid=', 1, 1424275386, 1),
  ('TDMPictures', '1.07', 'tdmpicture_cat', 'cat_id', 'cat_pid', 'cat_title', 'cat_weight', 'viewcat.php?ct=', 1, 1424275386, 1),
  ('xQuiz', '1.0', 'quiz_cat', 'cid', 'pid', 'title', 'weight', 'index.php?act=v&amp;q=', 1, 1424275386, 1),
  ('myQuiz', '4.1', 'myquiz_categories', 'cid', 'ustid', 'name', 'name', 'index.php?cidi=', 1, 1492906984, 1),
  ('ApCal', '2.22', 'apcal_cat', 'cid', 'pid', 'cat_title', 'weight', 'index.php?cid=', 1, 1492906984, 1);
