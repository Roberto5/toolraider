SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


CREATE TABLE IF NOT EXISTS `avvisi` (
  `id` int(5) NOT NULL auto_increment,
  `testo` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `avwp_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL auto_increment,
  `comment_id` bigint(20) unsigned NOT NULL default '0',
  `meta_key` varchar(255) default NULL,
  `meta_value` longtext,
  PRIMARY KEY  (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `avwp_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL auto_increment,
  `comment_post_ID` bigint(20) unsigned NOT NULL default '0',
  `comment_author` tinytext NOT NULL,
  `comment_author_email` varchar(100) NOT NULL default '',
  `comment_author_url` varchar(200) NOT NULL default '',
  `comment_author_IP` varchar(100) NOT NULL default '',
  `comment_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL default '0000-00-00 00:00:00',
  `comment_content` text NOT NULL,
  `comment_karma` int(11) NOT NULL default '0',
  `comment_approved` varchar(20) NOT NULL default '1',
  `comment_agent` varchar(255) NOT NULL default '',
  `comment_type` varchar(20) NOT NULL default '',
  `comment_parent` bigint(20) unsigned NOT NULL default '0',
  `user_id` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`comment_ID`),
  KEY `comment_approved` (`comment_approved`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `avwp_links` (
  `link_id` bigint(20) unsigned NOT NULL auto_increment,
  `link_url` varchar(255) NOT NULL default '',
  `link_name` varchar(255) NOT NULL default '',
  `link_image` varchar(255) NOT NULL default '',
  `link_target` varchar(25) NOT NULL default '',
  `link_description` varchar(255) NOT NULL default '',
  `link_visible` varchar(20) NOT NULL default 'Y',
  `link_owner` bigint(20) unsigned NOT NULL default '1',
  `link_rating` int(11) NOT NULL default '0',
  `link_updated` datetime NOT NULL default '0000-00-00 00:00:00',
  `link_rel` varchar(255) NOT NULL default '',
  `link_notes` mediumtext NOT NULL,
  `link_rss` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `avwp_options` (
  `option_id` bigint(20) unsigned NOT NULL auto_increment,
  `blog_id` int(11) NOT NULL default '0',
  `option_name` varchar(64) NOT NULL default '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL default 'yes',
  PRIMARY KEY  (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM AUTO_INCREMENT=3101 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `avwp_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL auto_increment,
  `post_id` bigint(20) unsigned NOT NULL default '0',
  `meta_key` varchar(255) default NULL,
  `meta_value` longtext,
  PRIMARY KEY  (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `avwp_posts` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `post_author` bigint(20) unsigned NOT NULL default '0',
  `post_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL default '0000-00-00 00:00:00',
  `post_content` longtext NOT NULL,
  `post_title` text NOT NULL,
  `post_excerpt` text NOT NULL,
  `post_status` varchar(20) NOT NULL default 'publish',
  `comment_status` varchar(20) NOT NULL default 'open',
  `ping_status` varchar(20) NOT NULL default 'open',
  `post_password` varchar(20) NOT NULL default '',
  `post_name` varchar(200) NOT NULL default '',
  `to_ping` text NOT NULL,
  `pinged` text NOT NULL,
  `post_modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL default '0000-00-00 00:00:00',
  `post_content_filtered` text NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL default '0',
  `guid` varchar(255) NOT NULL default '',
  `menu_order` int(11) NOT NULL default '0',
  `post_type` varchar(20) NOT NULL default 'post',
  `post_mime_type` varchar(100) NOT NULL default '',
  `comment_count` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `post_name` (`post_name`),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`)
) ENGINE=MyISAM AUTO_INCREMENT=124 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `avwp_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL default '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL default '0',
  `term_order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `avwp_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL auto_increment,
  `term_id` bigint(20) unsigned NOT NULL default '0',
  `taxonomy` varchar(32) NOT NULL default '',
  `description` longtext NOT NULL,
  `parent` bigint(20) unsigned NOT NULL default '0',
  `count` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `avwp_terms` (
  `term_id` bigint(20) unsigned NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `slug` varchar(200) NOT NULL default '',
  `term_group` bigint(10) NOT NULL default '0',
  PRIMARY KEY  (`term_id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `avwp_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL auto_increment,
  `user_id` bigint(20) unsigned NOT NULL default '0',
  `meta_key` varchar(255) default NULL,
  `meta_value` longtext,
  PRIMARY KEY  (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `avwp_users` (
  `ID` bigint(20) unsigned NOT NULL auto_increment,
  `user_login` varchar(60) NOT NULL default '',
  `user_pass` varchar(64) NOT NULL default '',
  `user_nicename` varchar(50) NOT NULL default '',
  `user_email` varchar(100) NOT NULL default '',
  `user_url` varchar(100) NOT NULL default '',
  `user_registered` datetime NOT NULL default '0000-00-00 00:00:00',
  `user_activation_key` varchar(60) NOT NULL default '',
  `user_status` int(11) NOT NULL default '0',
  `display_name` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `ct_comet` (
  `id_comet` varchar(5) NOT NULL default '0',
  `inserita` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `scadenza` timestamp NOT NULL default '0000-00-00 00:00:00',
  `user_comet` varchar(32) NOT NULL default '',
  `ally` int(5) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `ct_cronologia` (
  `uid` varchar(64) NOT NULL default '',
  `numero` int(3) NOT NULL default '0',
  `data` date NOT NULL default '0000-00-00'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `db_clean` (
  `id` int(1) NOT NULL default '1',
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `dl_config` (
  `conferma` smallint(6) NOT NULL default '0',
  `lost_key` varchar(32) NOT NULL default '',
  `lost_id` varchar(10) NOT NULL default '',
  `offline` int(2) default NULL,
  `testo` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`conferma`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `dl_user` (
  `id` int(5) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `nome` varchar(30) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `mail` varchar(64) NOT NULL default '',
  `actived` smallint(6) NOT NULL default '0',
  `attivita` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `auth` varchar(32) NOT NULL default '',
  `ip` varchar(20) NOT NULL default '',
  `type` enum('attesa','rifiutato','master','sharer') NOT NULL default 'attesa',
  `list` int(1) NOT NULL default '0',
  `n_list` int(2) NOT NULL default '0',
  `admin` enum('user','admin') NOT NULL default 'user',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`,`mail`)
) ENGINE=MyISAM AUTO_INCREMENT=136 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(11) NOT NULL auto_increment,
  `domanda` varchar(100) NOT NULL default '',
  `risposta` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `log` (
  `id` int(10) NOT NULL auto_increment,
  `text` text NOT NULL,
  `visita` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `read` (
  `id` int(5) NOT NULL default '0',
  `user` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `report` (
  `id` int(5) NOT NULL auto_increment,
  `mittente` varchar(40) NOT NULL default '',
  `mail` varchar(40) NOT NULL default '',
  `oggetto` varchar(40) NOT NULL default '',
  `testo` text NOT NULL,
  `tipo` int(1) NOT NULL default '0',
  `link` text NOT NULL,
  `letto_a` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `tt_ally` (
  `id` int(5) NOT NULL auto_increment,
  `nome` varchar(30) NOT NULL default '',
  `link` varchar(20) NOT NULL default '',
  `descrizione` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `tt_navi` (
  `uid` varchar(30) NOT NULL default '0',
  `ally` int(5) NOT NULL default '0',
  `pianeta` int(5) NOT NULL default '0',
  `data` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `n1` int(9) NOT NULL default '0',
  `n2` int(9) NOT NULL default '0',
  `n3` int(9) NOT NULL default '0',
  `n4` int(9) NOT NULL default '0',
  `n5` int(9) NOT NULL default '0',
  `n6` int(9) NOT NULL default '0',
  `n7` int(9) NOT NULL default '0',
  `n8` int(9) NOT NULL default '0',
  `n9` int(9) NOT NULL default '0',
  `n10` int(9) NOT NULL default '0',
  `n11` int(9) NOT NULL default '0',
  `n12` int(9) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `tt_richieste` (
  `ally` int(5) NOT NULL default '0',
  `player` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`player`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `us_index` (
  `uid` varchar(30) NOT NULL default '',
  `nome` varchar(30) NOT NULL default '',
  `n` int(5) NOT NULL default '0',
  `lista` enum('activ','inactiv') NOT NULL default 'activ'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `us_list` (
  `lid` int(5) NOT NULL auto_increment,
  `n` int(5) NOT NULL default '0',
  `uid` varchar(30) NOT NULL default '',
  `nome_lista` varchar(30) NOT NULL default '' COMMENT 'pianeta di origine',
  `nome_farm` varchar(30) NOT NULL default '',
  `link` text NOT NULL,
  `comment` text NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `bottino` varchar(20) NOT NULL default '0 0%',
  `tipo_nave` int(1) NOT NULL default '0',
  `num_nave` int(5) NOT NULL default '0',
  `type` enum('activ','inactiv') NOT NULL default 'activ',
  PRIMARY KEY  (`lid`)
) ENGINE=MyISAM AUTO_INCREMENT=252 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `us_mess` (
  `id` int(6) NOT NULL auto_increment,
  `oggetto` varchar(40) NOT NULL default '',
  `messaggio` text NOT NULL,
  `ora` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `destinatario` varchar(30) NOT NULL default '',
  `mittente` varchar(60) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=82 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `us_pianeti` (
  `pid` int(3) NOT NULL auto_increment,
  `id` varchar(30) NOT NULL default '0',
  `g` int(1) NOT NULL default '0',
  `x` int(3) NOT NULL default '0',
  `y` int(3) NOT NULL default '0',
  `nome_pianeta` varchar(30) NOT NULL default '',
  `prod_met` int(6) NOT NULL default '0',
  `prod_cri` int(6) NOT NULL default '0',
  `prod_deu` int(6) NOT NULL default '0',
  `dep_met` int(9) NOT NULL default '0',
  `dep_cri` int(9) NOT NULL default '0',
  `dep_deu` int(9) NOT NULL default '0',
  `mercato` int(2) NOT NULL default '0',
  PRIMARY KEY  (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=363 DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `us_player` (
  `nome` varchar(64) NOT NULL default '',
  `alleanza` int(5) NOT NULL default '0',
  `razza` enum('Titani','Terrestri','Xen') NOT NULL default 'Titani',
  `universo` varchar(40) NOT NULL default 'http://u1.imperion.it',
  PRIMARY KEY  (`nome`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `us_privilegi` (
  `nome` varchar(30) NOT NULL default '',
  `privilegio` char(1) NOT NULL default '',
  PRIMARY KEY  (`nome`,`privilegio`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `us_read` (
  `id` int(5) NOT NULL default '0',
  `user` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `us_ricerche` (
  `id` varchar(30) NOT NULL default '0',
  `ricerca` varchar(30) NOT NULL default '',
  `liv` int(2) NOT NULL default '0',
  PRIMARY KEY  (`id`,`ricerca`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

