CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `comment_text` text NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `pub_date` int NOT NULL,
  `lvl` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY rgt (rgt),
  KEY lft (lft)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
insert into `comments` VALUES
  (1, '', 0, 0, 1, 1, 2);
