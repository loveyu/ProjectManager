/*
Navicat MySQL Data Transfer

Source Server         : loclhost
Source Server Version : 50615
Source Host           : 127.0.0.1:3306
Source Database       : pm

Target Server Type    : MYSQL
Target Server Version : 50615
File Encoding         : 65001

Date: 2014-10-19 14:12:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for item
-- ----------------------------
DROP TABLE IF EXISTS `item`;
CREATE TABLE `item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(32) NOT NULL,
  `title` varchar(128) NOT NULL,
  `desc` varchar(200) NOT NULL,
  `sort` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`) USING BTREE,
  KEY `project_id` (`project_id`) USING BTREE,
  CONSTRAINT `item_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `item_ibfk_2` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for page
-- ----------------------------
DROP TABLE IF EXISTS `page`;
CREATE TABLE `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `keywords` varchar(200) NOT NULL,
  `status` char(10) NOT NULL,
  `time` datetime NOT NULL,
  `uptime` datetime NOT NULL,
  `view` bigint(20) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'page',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for project
-- ----------------------------
DROP TABLE IF EXISTS `project`;
CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `title` varchar(128) NOT NULL,
  `desc` varchar(512) NOT NULL,
  `sort` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `type` char(20) NOT NULL DEFAULT 'project',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE,
  KEY `project_ibfk_1` (`page_id`),
  CONSTRAINT `project_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for setting
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `value` varchar(4096) NOT NULL,
  `auto` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of setting
-- ----------------------------
INSERT INTO `setting` VALUES ('1', 'site_title', '恋羽的个人项目集合', '1');
INSERT INTO `setting` VALUES ('2', 'site_name', 'Loveyu Project', '1');
INSERT INTO `setting` VALUES ('3', 'site_desc', '将自己不错的作品发布出来，一起交流、讨论、学习。', '1');
INSERT INTO `setting` VALUES ('4', 'keywords', '开源程序,恋羽,PHP程序,程序分享', '1');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` char(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(40) NOT NULL,
  `token` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user`(`id`, `user`, `password`, `salt`, `token`) VALUES (1, 'loveyu', '915b601c5dbff1a8cb8ecb1a17afda6bcf774035', '{*:mK;7PHTS:J_g_A\"ajS!k\'&C~S.Cyp{z}x^{u]', 'ac554f2c91daa473214be5d09d104b96f3e280ee');

-- ----------------------------
-- Table structure for version
-- ----------------------------
DROP TABLE IF EXISTS `version`;
CREATE TABLE `version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(32) NOT NULL,
  `top_version` char(20) DEFAULT '0',
  `top_version_code` int(255) unsigned DEFAULT '0',
  `download` varchar(255) DEFAULT NULL COMMENT '下载页面',
  `info` text COMMENT '描述信息',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for version_control
-- ----------------------------
DROP TABLE IF EXISTS `version_control`;
CREATE TABLE `version_control` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(32) DEFAULT NULL COMMENT '表名词',
  `version` char(20) DEFAULT NULL COMMENT '版本号',
  `version_code` int(255) unsigned DEFAULT NULL COMMENT '版本更新信息',
  `build_version` varchar(30) DEFAULT NULL COMMENT '构建版本',
  `update_info` text COMMENT '更新信息',
  `bugs` text COMMENT 'BUG信息',
  `message` varchar(2048) DEFAULT NULL COMMENT '新版本信息',
  `force_update` tinyint(10) unsigned DEFAULT NULL COMMENT '强制更新，0可忽略，1更新',
  `time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `time_update` int(10) unsigned DEFAULT NULL COMMENT '信息更新时间',
  `download_url` varchar(255) DEFAULT NULL COMMENT '下载地址',
  `update_url` varchar(255) DEFAULT NULL COMMENT '更新信息地址',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  CONSTRAINT `version_control_ibfk_1` FOREIGN KEY (`name`) REFERENCES `version` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
