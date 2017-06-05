/*
Navicat MySQL Data Transfer

Source Server         : zjtd_new
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : zj_luntan

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-05-17 09:23:11
*/

SET FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS `zj_luntan`;
CREATE DATABASE `zj_luntan`;
USE `zj_luntan`;

-- ----------------------------
-- Table structure for `zj_admin`
-- ----------------------------
DROP TABLE IF EXISTS `zj_admin`;
CREATE TABLE `zj_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `passwd` varchar(44) NOT NULL,
  `keys` char(8) DEFAULT NULL,
  `login_time` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00',
  `login_ip` varchar(36) DEFAULT NULL,
  `last_time` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00',
  `last_ip` varchar(36) DEFAULT NULL,
  `group` int(10) unsigned DEFAULT '0' COMMENT '管理员权限分组0超级管理员',
  `token` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of zj_admin
-- ----------------------------
INSERT INTO `zj_admin` VALUES ('4', 'root', '27aeb69a3b67402f5d67a12da3c8a1f4cf489a91', '13052088', '2017-05-12 03:05:01', '127.0.0.1', '2017-05-11 10:05:58', '127.0.0.1', '0', 'e4bb0dc964e0d1503562cdb73caf9f33d744c277dacb15113ef12126ac356e188a13db6d');
INSERT INTO `zj_admin` VALUES ('26', '韩楠', '65b8e12e7bc6113baed21d09c43dbfab768de4cb', '329109', '2017-05-11 04:05:08', '127.0.0.1', '1970-01-02 00:00:00', null, '1', '4bae6c869f0bc51f16e17560bee78d08969d6a61b4f6cfb4ef1200b2ac1be74f3a513f6a');
INSERT INTO `zj_admin` VALUES ('11', 'admin', 'a2e8c53cb965555aef00b7225d732a502049ceb2', '529977', '2017-05-12 03:05:26', '127.0.0.1', '2017-05-12 03:05:16', '127.0.0.1', '1', '766ea26cbf4ea0427bcce431046807dc68f78c193ed218afe1a199099d9b5874f69c1efe');
INSERT INTO `zj_admin` VALUES ('19', '丁雄', '1bec7cbc63e7d5f4d8c785ad09a214ad17c2dd30', '50037754', '1970-01-02 00:00:00', '1928896125', '1970-01-02 00:00:00', '1928756562', '1', 'c9628014a16ba3a5fb99fca6487602ed74d4ec27b249e5b3f5690dbb9b9d4a2505af0600');
INSERT INTO `zj_admin` VALUES ('21', '海哥', '6e1b24f61e1b660150ebc8706751be64c59f6b06', '26469835', '1970-01-02 00:00:00', '1928895466', '1970-01-02 00:00:00', '1928895466', '1', '');

-- ----------------------------
-- Table structure for `zj_admin_permission`
-- ----------------------------
DROP TABLE IF EXISTS `zj_admin_permission`;
CREATE TABLE `zj_admin_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '权限列表详情id',
  `controller` varchar(50) DEFAULT NULL COMMENT '能够使用的控制器',
  `function` text COMMENT '能够使用的控制器下的方法，分隔开:,',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='后台登录管理员权限组详细配置表';

-- ----------------------------
-- Records of zj_admin_permission
-- ----------------------------
INSERT INTO `zj_admin_permission` VALUES ('1', 'admin:权限管理', 'admin_admin_index:查看所有管理员,admin_admin_add:添加管理员,admin_admin_delete:删除管理员,admin_admin_edit:修改管理员信息,admin_admin_role_index:查看所有角色,admin_admin_role_add:添加角色,admin_admin_role_delete:删除角色,admin_admin_role_edit:查看角色权限,admin_admin_role_update:修改角色权限');
INSERT INTO `zj_admin_permission` VALUES ('2', 'user:用户管理', 'user_user_index:查看未禁用用户,user_user_disabled:查看已禁用用户,user_user_friends:查看某个用户的好友,user_user_blacklist:查看某个用户的黑名单,user_user_disable:禁用某个用户,user_user_enable:启用某个用户');
INSERT INTO `zj_admin_permission` VALUES ('3', 'qun:群管理', 'qun_qun_index:查看所有的群,qun_qun_members:查看群成员,qun_qun_delete:删除群');
INSERT INTO `zj_admin_permission` VALUES ('4', 'luntan:论坛管理', 'luntan_luntan_index:查看论坛,luntan_luntan_blocks:查看板块,luntan_luntan_block_delete:删除板块,luntan_luntan_block_add:添加板块');

-- ----------------------------
-- Table structure for `zj_admin_role`
-- ----------------------------
DROP TABLE IF EXISTS `zj_admin_role`;
CREATE TABLE `zj_admin_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员id，0：超级管理员，可以改变其他管理员的权限等 1：高级管理员，不能分配权限，可以删除修改信息等 2：普通管理员，只能查看',
  `name` varchar(30) NOT NULL COMMENT '权限组名称',
  `controller_funcs` text COMMENT '控制器权限，用{c-f}模式分割',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='系统管理员权限组';

-- ----------------------------
-- Records of zj_admin_role
-- ----------------------------
INSERT INTO `zj_admin_role` VALUES ('1', '高级管理员', '{user_user_index},{user_user_disabled},{user_user_friends},{user_user_blacklist},{user_user_disable},{user_user_enable},{qun_qun_index},{qun_qun_members},{qun_qun_delete}');
INSERT INTO `zj_admin_role` VALUES ('5', '普通管理员', '{user_user_index},{user_user_disabled},{user_user_friends},{user_user_blacklist},{qun_qun_index},{qun_qun_members}');

-- ----------------------------
-- Table structure for `zj_blacklist`
-- ----------------------------
DROP TABLE IF EXISTS `zj_blacklist`;
CREATE TABLE `zj_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID,对应zj_user表中的id',
  `blacklist_id` int(11) NOT NULL COMMENT '黑名单ID,对应zj_user表中的id',
  `blacklist_mobile` varchar(25) DEFAULT NULL COMMENT '好友手机号,对应zj_user表中的mobile',
  `blacklist_nickname` varchar(25) DEFAULT NULL COMMENT '好友昵称,对应zj_user表中的nickname',
  `blacklist_photo` varchar(255) DEFAULT NULL COMMENT '头像,对应zj_user表中的photo',
  `blacklist_sex` char(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_blacklist
-- ----------------------------
INSERT INTO `zj_blacklist` VALUES ('1', '15', '15', '15501030414', '哈哈', 'public/image/timg.jpg', null);
INSERT INTO `zj_blacklist` VALUES ('2', '14', '15', '15501030414', '哈哈', 'public/image/timg.jpg', null);

-- ----------------------------
-- Table structure for `zj_friend`
-- ----------------------------
DROP TABLE IF EXISTS `zj_friend`;
CREATE TABLE `zj_friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `friend_id` int(11) NOT NULL COMMENT '好友ID',
  `friend_mobile` varchar(25) DEFAULT NULL COMMENT '好友手机号,对应zj_user表中的mobile',
  `friend_nickname` varchar(25) DEFAULT NULL COMMENT '好友昵称,对应zj_user表中的nickname',
  `friend_photo` varchar(255) DEFAULT NULL COMMENT '头像,对应zj_user表中的photo',
  `friend_comment` varchar(25) DEFAULT NULL COMMENT '好友备注',
  `friend_sex` char(1) DEFAULT NULL,
  `see_friend_circle` smallint(1) DEFAULT '1' COMMENT '是否可以看朋友圈 0: 不能看朋友圈 1: 可以看朋友圈',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  `is_deleted` smallint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_friend
-- ----------------------------
INSERT INTO `zj_friend` VALUES ('13', '7', '6', '18010021437', '小小新', 'public/upload/images/20170509/recson_201705091100261932170074850.jpg', '', null, '1', null, '0');
INSERT INTO `zj_friend` VALUES ('14', '6', '7', '10810021474', '517820', 'public/image/timg.jpg', '寿山石', null, '1', null, '0');
INSERT INTO `zj_friend` VALUES ('17', '6', '15', '15501030414', '哈哈', 'public/image/timg.jpg', '', null, '0', null, '0');
INSERT INTO `zj_friend` VALUES ('18', '15', '6', '18010021437', '小小新', 'public/upload/images/20170509/recson_201705091100261932170074850.jpg', '韩难', null, '0', '', '0');
INSERT INTO `zj_friend` VALUES ('23', '14', '15', '15501030414', '哈哈', 'public/image/timg.jpg', '', null, '1', null, '0');
INSERT INTO `zj_friend` VALUES ('24', '15', '14', '18510251293', '110186ff', 'public/upload/images/20170510/recson_201705101745427079260077163.png', '', null, '0', null, '0');
INSERT INTO `zj_friend` VALUES ('25', '14', '15', '15501030414', '哈哈', 'public/image/timg.jpg', '古风', null, '1', null, '0');
INSERT INTO `zj_friend` VALUES ('26', '15', '14', '18510251293', '110186ff', 'public/upload/images/20170510/recson_201705101745427079260077163.png', '古风', null, '0', null, '0');
INSERT INTO `zj_friend` VALUES ('27', '14', '13', '18510251292', '735556', 'public/image/timg.jpg', '', null, '1', null, '0');
INSERT INTO `zj_friend` VALUES ('28', '13', '14', '18510251293', '110186ff', 'public/upload/images/20170510/recson_201705101745427079260077163.png', '', null, '1', null, '0');
INSERT INTO `zj_friend` VALUES ('29', '15', '13', '18510251292', '735556', 'public/image/timg.jpg', '', null, '0', null, '0');
INSERT INTO `zj_friend` VALUES ('30', '13', '15', '15501030414', '哈哈', 'public/image/timg.jpg', '', null, '1', null, '0');

-- ----------------------------
-- Table structure for `zj_friend_apply`
-- ----------------------------
DROP TABLE IF EXISTS `zj_friend_apply`;
CREATE TABLE `zj_friend_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `access_user_id` int(11) NOT NULL COMMENT '等待接受的用户id, 对应zj_user表中的id',
  `apply_user_id` int(11) NOT NULL COMMENT '申请的用户id, 对应zj_user表中的id',
  `is_accepted` smallint(1) DEFAULT '0' COMMENT '通过还是拒绝 0: 未处理 1: 已接受 2: 已拒绝',
  `create_time` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00' COMMENT '申请时间',
  `friend_comment` varchar(25) DEFAULT NULL COMMENT '好友备注',
  `see_friend_circle` smallint(1) DEFAULT '1' COMMENT '是否可以看朋友圈 0: 不能看朋友圈 1: 可以看朋友圈',
  `description` varchar(255) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_friend_apply
-- ----------------------------
INSERT INTO `zj_friend_apply` VALUES ('6', '8', '6', '0', '2017-05-05 10:03:16', '寿山石', '1', '');
INSERT INTO `zj_friend_apply` VALUES ('7', '8', '7', '0', '2017-05-05 10:03:41', '寿山石', '1', '');
INSERT INTO `zj_friend_apply` VALUES ('9', '6', '7', '1', '2017-05-09 14:04:04', '寿山石', '1', '');
INSERT INTO `zj_friend_apply` VALUES ('11', '15', '15', '1', '2017-05-10 16:10:52', '', '0', '哈哈');
INSERT INTO `zj_friend_apply` VALUES ('12', '15', '6', '1', '2017-05-11 11:08:07', '', '0', '韩寒');
INSERT INTO `zj_friend_apply` VALUES ('14', '14', '14', '1', '2017-05-11 14:22:46', '', '0', '');
INSERT INTO `zj_friend_apply` VALUES ('16', '15', '14', '1', '2017-05-16 10:17:26', '古风', '0', '110186ff');
INSERT INTO `zj_friend_apply` VALUES ('17', '13', '14', '1', '2017-05-16 18:40:59', '', '1', '');
INSERT INTO `zj_friend_apply` VALUES ('18', '13', '15', '1', '2017-05-16 19:44:51', '', '1', '');

-- ----------------------------
-- Table structure for `zj_group`
-- ----------------------------
DROP TABLE IF EXISTS `zj_group`;
CREATE TABLE `zj_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(36) NOT NULL COMMENT '群名称',
  `owner_id` int(11) NOT NULL COMMENT '群主, 对应zj_user表中的id',
  `create_time` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00' COMMENT '创建时间',
  `maxusers` int(11) NOT NULL DEFAULT '1000',
  `currentusers` int(11) NOT NULL DEFAULT '1',
  `huanxin_group_id` varchar(36) NOT NULL,
  `icon` varchar(255) DEFAULT NULL COMMENT '群头像',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_group
-- ----------------------------
INSERT INTO `zj_group` VALUES ('5', '嗨吧', '7', '2017-05-11 14:41:22', '1000', '5', '15819317772289');
INSERT INTO `zj_group` VALUES ('17', '群聊', '14', '2017-05-12 10:26:01', '1000', '3', '15893846360065');
INSERT INTO `zj_group` VALUES ('18', '群聊', '15', '2017-05-15 14:01:13', '1000', '3', '16179185909761');
INSERT INTO `zj_group` VALUES ('19', '群聊', '15', '2017-05-15 16:15:23', '1000', '2', '16187627995137');
INSERT INTO `zj_group` VALUES ('20', '群聊', '15', '2017-05-16 20:01:16', '1000', '3', '16292433166337');

-- ----------------------------
-- Table structure for `zj_inapp_code`
-- ----------------------------
DROP TABLE IF EXISTS `zj_inapp_code`;
CREATE TABLE `zj_inapp_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(6) DEFAULT NULL COMMENT '验证码',
  `time` int(11) DEFAULT NULL COMMENT '时间',
  `mobile` char(11) NOT NULL COMMENT '电话',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_inapp_code
-- ----------------------------
INSERT INTO `zj_inapp_code` VALUES ('1', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('2', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('3', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('4', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('5', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('6', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('7', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('8', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('9', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('10', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('11', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('12', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('13', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('14', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('15', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('16', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('17', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('18', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('19', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('20', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('21', '652369', '1494929230', '18510251290');
INSERT INTO `zj_inapp_code` VALUES ('22', '272396', '1494930169', '18010021437');

-- ----------------------------
-- Table structure for `zj_luntan`
-- ----------------------------
DROP TABLE IF EXISTS `zj_luntan`;
CREATE TABLE `zj_luntan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `luntan_name` varchar(36) NOT NULL COMMENT '论坛名字',
  `icon` varchar(255) NOT NULL COMMENT '论坛图标',
  `tiezi_count` int(11) DEFAULT '0' COMMENT '发帖量',
  `comment_count` int(11) DEFAULT '0' COMMENT '评论量',
  `today_see_count` int(11) DEFAULT '0' COMMENT '今日浏览量',
  `today` date DEFAULT NULL,
  `focus_count` int(11) DEFAULT '0' COMMENT '关注量',
  `create_time` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_luntan
-- ----------------------------
INSERT INTO `zj_luntan` VALUES ('1', '甲友论坛', 'public/image/timg.jpg', '2', '0', '3', '2017-05-10', '2', '1970-01-02 00:00:00');
INSERT INTO `zj_luntan` VALUES ('2', '群聊杂谈', 'public/image/timg.jpg', '0', '0', '0', null, '0', '1970-01-02 00:00:00');
INSERT INTO `zj_luntan` VALUES ('3', '工作视图', 'public/image/timg.jpg', '0', '0', '0', null, '0', '1970-01-02 00:00:00');
INSERT INTO `zj_luntan` VALUES ('4', '招聘求职', 'public/image/timg.jpg', '0', '0', '0', null, '0', '1970-01-02 00:00:00');
INSERT INTO `zj_luntan` VALUES ('5', '二手设备', 'public/image/timg.jpg', '0', '0', '0', null, '0', '1970-01-02 00:00:00');
INSERT INTO `zj_luntan` VALUES ('6', '部件维修', 'public/image/timg.jpg', '0', '0', '0', null, '0', '1970-01-02 00:00:00');
INSERT INTO `zj_luntan` VALUES ('7', '故障咨询', 'public/image/timg.jpg', '0', '0', '0', null, '0', '1970-01-02 00:00:00');

-- ----------------------------
-- Table structure for `zj_luntan_block`
-- ----------------------------
DROP TABLE IF EXISTS `zj_luntan_block`;
CREATE TABLE `zj_luntan_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `luntan_id` int(11) NOT NULL COMMENT '论坛id, 对应zj_luntan表中的id',
  `block_name` varchar(36) NOT NULL COMMENT '板块名字',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_luntan_block
-- ----------------------------
INSERT INTO `zj_luntan_block` VALUES ('1', '1', '板块1');
INSERT INTO `zj_luntan_block` VALUES ('2', '1', '板块2');
INSERT INTO `zj_luntan_block` VALUES ('3', '1', '板块3');
INSERT INTO `zj_luntan_block` VALUES ('4', '1', '板块4');
INSERT INTO `zj_luntan_block` VALUES ('6', '2', '板块1');
INSERT INTO `zj_luntan_block` VALUES ('7', '2', '板块2');
INSERT INTO `zj_luntan_block` VALUES ('8', '2', '板块3');
INSERT INTO `zj_luntan_block` VALUES ('9', '2', '板块4');
INSERT INTO `zj_luntan_block` VALUES ('10', '2', '板块5');
INSERT INTO `zj_luntan_block` VALUES ('11', '1', '板块5');
INSERT INTO `zj_luntan_block` VALUES ('15', '1', '板块7');

-- ----------------------------
-- Table structure for `zj_luntan_tiezi`
-- ----------------------------
DROP TABLE IF EXISTS `zj_luntan_tiezi`;
CREATE TABLE `zj_luntan_tiezi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id, 对应zj_user表中的id',
  `luntan_id` int(11) NOT NULL COMMENT '论坛id, 对应zj_luntan表中的id',
  `luntan_block_id` int(11) NOT NULL COMMENT '论坛板块id, 对应zj_luntan_block表中的id',
  `title` varchar(36) NOT NULL COMMENT '帖子标题',
  `content` text NOT NULL COMMENT '帖子内容',
  `comment_count` int(11) DEFAULT '0' COMMENT '评论量',
  `see_count` int(11) DEFAULT '0' COMMENT '浏览量',
  `location_province` varchar(36) DEFAULT NULL COMMENT '省',
  `location_city` varchar(36) DEFAULT NULL COMMENT '市',
  `location_county` varchar(36) DEFAULT NULL COMMENT '县',
  `is_essence` smallint(1) DEFAULT '0' COMMENT '是否为精华帖 0: 不是精华帖 1: 是精华帖',
  `has_img` smallint(1) DEFAULT '0' COMMENT '是否有图片 0: 没有图片 1: 有图片',
  `create_time` timestamp NULL DEFAULT '1970-01-02 00:00:00',
  `comment_time` timestamp NULL DEFAULT '1970-01-02 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_luntan_tiezi
-- ----------------------------
INSERT INTO `zj_luntan_tiezi` VALUES ('3', '6', '1', '1', 'hi', 'hello world', '5', '0', '河南省', '焦作市', '中站区', '1', '0', '2017-05-10 11:57:06', '2017-05-10 13:46:22');
INSERT INTO `zj_luntan_tiezi` VALUES ('4', '6', '1', '1', 'hi', 'hello world', '1', '0', '河南省', '焦作市', '中站区', '1', '1', '2017-05-10 11:17:02', '2017-05-10 13:46:46');
INSERT INTO `zj_luntan_tiezi` VALUES ('5', '6', '1', '1', 'hi', 'hello world', '0', '0', '河南省', '焦作市', '解放区', '1', '2', '2017-05-10 11:18:40', '1970-01-02 00:00:00');
INSERT INTO `zj_luntan_tiezi` VALUES ('11', '6', '1', '2', 'hi', '和哈哈', '0', '0', '北京市', '北京市', '大兴区', '1', '0', '2017-05-10 15:03:09', '1970-01-02 00:00:00');
INSERT INTO `zj_luntan_tiezi` VALUES ('12', '6', '1', '2', 'hi', '和哈哈', '0', '0', '北京市', '北京市', '大兴区', '1', '0', '2017-05-10 15:03:42', '1970-01-02 00:00:00');

-- ----------------------------
-- Table structure for `zj_luntan_tiezi_img`
-- ----------------------------
DROP TABLE IF EXISTS `zj_luntan_tiezi_img`;
CREATE TABLE `zj_luntan_tiezi_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '谁的朋友圈图片, 对应zj_user表中的id',
  `luntan_tiezi_id` int(11) NOT NULL COMMENT '某个帖子的id, 对应zj_luntan_tiezi表中的id',
  `url` varchar(255) DEFAULT NULL COMMENT '图片的链接',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_luntan_tiezi_img
-- ----------------------------
INSERT INTO `zj_luntan_tiezi_img` VALUES ('1', '6', '4', 'public/upload/images/20170510/recson_201705101117027911030033557.jpg');
INSERT INTO `zj_luntan_tiezi_img` VALUES ('2', '6', '4', 'public/upload/images/20170510/recson_201705101117033961370079641.jpg');

-- ----------------------------
-- Table structure for `zj_luntan_tiezi_video`
-- ----------------------------
DROP TABLE IF EXISTS `zj_luntan_tiezi_video`;
CREATE TABLE `zj_luntan_tiezi_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '谁的朋友圈图片, 对应zj_user表中的id',
  `luntan_tiezi_id` int(11) NOT NULL COMMENT '某个帖子的id, 对应zj_luntan_tiezi表中的id',
  `url` varchar(255) DEFAULT NULL COMMENT '视频的链接',
  `thumbnail_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_luntan_tiezi_video
-- ----------------------------
INSERT INTO `zj_luntan_tiezi_video` VALUES ('1', '6', '5', 'public/upload/files/20170510111840_596.mp4', null);

-- ----------------------------
-- Table structure for `zj_tiezi_comment_img`
-- ----------------------------
DROP TABLE IF EXISTS `zj_tiezi_comment_img`;
CREATE TABLE `zj_tiezi_comment_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tiezi_comment_id` int(11) NOT NULL COMMENT '某个评论的id, 对应zj_user_tiezi_comment表中的id',
  `url` varchar(255) DEFAULT NULL COMMENT '图片的链接',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_tiezi_comment_img
-- ----------------------------
INSERT INTO `zj_tiezi_comment_img` VALUES ('5', '5', 'public/upload/images/20170510/recson_201705101346222205530095782.jpg');
INSERT INTO `zj_tiezi_comment_img` VALUES ('6', '5', 'public/upload/images/20170510/recson_201705101346222555550030871.jpg');
INSERT INTO `zj_tiezi_comment_img` VALUES ('7', '6', 'public/upload/images/20170510/recson_201705101346468969640047274.jpg');
INSERT INTO `zj_tiezi_comment_img` VALUES ('8', '6', 'public/upload/images/20170510/recson_201705101346469229660034780.jpg');
INSERT INTO `zj_tiezi_comment_img` VALUES ('9', '7', 'public/upload/images/20170510/recson_20170510134725214156006982.jpg');
INSERT INTO `zj_tiezi_comment_img` VALUES ('10', '7', 'public/upload/images/20170510/recson_20170510134725242157001599.jpg');

-- ----------------------------
-- Table structure for `zj_user`
-- ----------------------------
DROP TABLE IF EXISTS `zj_user`;
CREATE TABLE `zj_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` char(11) NOT NULL COMMENT '电话',
  `passwd` varchar(32) NOT NULL COMMENT '密码',
  `qr_path` varchar(255) DEFAULT NULL COMMENT '二维码路径',
  `code` smallint(4) DEFAULT NULL COMMENT '短信验证码',
  `nickname` varchar(25) DEFAULT NULL COMMENT '昵称',
  `sex` char(1) DEFAULT NULL COMMENT '性别',
  `signature` varchar(25) DEFAULT NULL COMMENT '个性签名',
  `token` varchar(80) DEFAULT NULL,
  `key` varchar(25) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL COMMENT '头像',
  `longitude` varchar(36) DEFAULT NULL,
  `latitude` varchar(36) DEFAULT NULL,
  `social_account` varchar(80) DEFAULT NULL COMMENT '第三方账号',
  `social_type` enum('wechat','qq','none') DEFAULT 'none' COMMENT '第三方账号类型 wechat: 微信 qq: qq',
  `registe_time` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00' COMMENT '注册时间',
  `login_time` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00' COMMENT '最后登录时间',
  `login_ip` varchar(36) DEFAULT NULL COMMENT '最后登录ip',
  `is_disabled` smallint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_user
-- ----------------------------
INSERT INTO `zj_user` VALUES ('6', '18010021437', 'd90393517e3fd06cce23eda3fb5476cc', 'public/upload/phpqrcode/temp/d300a6d0c7ef75dea2b0ebc895677d77.png', '32767', '小小新', null, '小小新', 'ed914860ee79f3d00a1909f15ffdee20c254237466bfcd43de4d095eebefa75e505e16e7', '520612', 'public/upload/images/20170509/recson_201705091100261932170074850.jpg', '113.08802', '36.19838', '601502546', 'qq', '2017-05-04 16:08:49', '2017-05-16 18:23:52', '192.168.1.133', '0');
INSERT INTO `zj_user` VALUES ('7', '10810021474', '1c6082459c847b0b401f9ae0695592d4', 'public/upload/phpqrcode/temp/048fa0f0815673e98f4ca0920e9d3d23.png', '32767', '517820', null, 'hello world', 'bd889f38ac1834cd4fb58aa13941a114', '558655', 'public/image/timg.jpg', '113.619325', '34.747778', '', 'none', '2017-05-04 18:35:39', '1970-01-02 00:00:00', null, '0');
INSERT INTO `zj_user` VALUES ('8', '10810021475', '2948d4c5f28f30faf602b63c19bf197d', 'public/upload/phpqrcode/temp/f69e006934883b7ad5e6c3922cfb71f6.png', '32767', '517822', null, 'hello world', '0f1f596638ab012c1b612777e832cd35', '715050', 'public/image/timg.jpg', '113.102191', '36.177988', '', 'none', '2017-05-04 18:39:53', '1970-01-02 00:00:00', null, '0');
INSERT INTO `zj_user` VALUES ('9', '10810021476', '9f7c30c977d881cb57dea16d2de9113e', 'public/upload/phpqrcode/temp/282faee9799163adc2e061721dee1bc7.png', '32767', '517823', null, 'hello world', '9111114dbcd26c2afa2e8e6b9899ad99', '415721', 'public/image/timg.jpg', '113.109726', '36.195245', '', 'none', '2017-05-04 18:40:37', '1970-01-02 00:00:00', null, '0');
INSERT INTO `zj_user` VALUES ('10', '10810021477', '904746b15ded0fce4a7b57657436662f', 'public/upload/phpqrcode/temp/23fbc70222884f201ff59eacc570532a.png', '32767', '499561', null, 'hello world', 'a71595888960c61a604d6d6953c7d91a', '732393', 'public/image/timg.jpg', null, null, '', 'none', '2017-05-04 18:40:53', '1970-01-02 00:00:00', null, '0');
INSERT INTO `zj_user` VALUES ('11', '10810021478', 'a0aff869c4cf232a42b2747ebf221de8', 'public/upload/phpqrcode/temp/bc2079b3abb0d9614d675648841614aa.png', '32767', '329202', null, 'hello world', 'f0122c7e091b204df4d63754b44da9c9', '217691', 'public/image/timg.jpg', null, null, '', 'none', '2017-05-04 18:41:11', '1970-01-02 00:00:00', null, '0');
INSERT INTO `zj_user` VALUES ('12', '10810021479', '15a90ba2d22d331490f9c3c869462014', 'public/upload/phpqrcode/temp/8b538b89ead85b5da403c3c422034c1e.png', '32767', '351005', null, 'hello world', '485a3c2bdec9282e81d308800c3d5c7b', '550058', 'public/image/timg.jpg', null, null, '', 'none', '2017-05-04 18:41:38', '1970-01-02 00:00:00', null, '0');
INSERT INTO `zj_user` VALUES ('13', '18510251292', '6eb3b5d19f2e62c5675d9397cd0fd509', 'public/upload/phpqrcode/temp/ad325dce4944dbcae06f9e7fef0073f5.png', '32767', '735556', null, 'hello world', 'cff29d6bb790229ea79ecd196f4a8d610121f7a228aeb241a2e09716600cc473bf31aabe', '134133', 'public/image/timg.jpg', null, null, '', 'none', '2017-05-08 10:26:19', '2017-05-16 18:40:39', '192.168.1.133', '0');
INSERT INTO `zj_user` VALUES ('14', '18510251293', '0b3bd7e315e1bb9dabe6ac80aa4a5f01', 'public/upload/phpqrcode/temp/01253975d7d07cbfd786a076bd9a92f3.png', '32767', '110186ff', null, 'hello world ', '4b511a75718c74a07cc6acc7504bf32e67be61026d74e8cda65fb7b710efae40129bae2f', '344543', 'public/upload/images/20170517/recson_20170517084616270090009674.png', null, null, '', 'none', '2017-05-08 10:34:14', '2017-05-16 19:51:13', '192.168.1.132', '0');
INSERT INTO `zj_user` VALUES ('15', '15501030414', 'd89dfc53e54db1166e403ac26ab3a365', 'public/upload/phpqrcode/temp/b878ff42ec914135fa39b312571ecc32.png', '32767', '哈哈', null, '哈哈哈哈', '7c81c4188eb6dadb2f0e5fc066c7042dede6b6918a0b973f26a4b222146e60c698290a5a', '162288', 'public/image/timg.jpg', null, null, '', 'none', '2017-05-08 11:58:35', '2017-05-17 09:20:37', '192.168.1.122', '0');
INSERT INTO `zj_user` VALUES ('16', '15501030111', '167efc9484c473298074f8b81424d2c9', 'public/upload/phpqrcode/temp/b56e998a7c7d6addf45f67d62b243439.png', '32767', '137721', null, 'hello world', '1ff496d193d0de7227098446b3909657', '648510', 'public/image/timg.jpg', null, null, '', 'none', '2017-05-08 13:39:45', '1970-01-02 00:00:00', null, '1');

-- ----------------------------
-- Table structure for `zj_user_circle`
-- ----------------------------
DROP TABLE IF EXISTS `zj_user_circle`;
CREATE TABLE `zj_user_circle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id, 对应zj_user表中的id',
  `content` varchar(255) DEFAULT NULL COMMENT '文本内容',
  `location` varchar(255) DEFAULT NULL COMMENT '所在位置',
  `longitude` varchar(36) NOT NULL COMMENT '经度',
  `latitude` varchar(36) NOT NULL COMMENT '纬度',
  `url` varchar(255) DEFAULT NULL,
  `extra_url` varchar(255) DEFAULT NULL,
  `thumbs_up_count` int(11) DEFAULT '0' COMMENT '点赞个数',
  `comment_count` int(11) DEFAULT '0' COMMENT '评论个数',
  `create_time` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00' COMMENT '发布时间',
  `see_type` enum('public','self','partial') DEFAULT 'public' COMMENT '谁可以看 public: 所有朋友可见 self:自己可见 partial:部分可见',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_user_circle
-- ----------------------------
INSERT INTO `zj_user_circle` VALUES ('5', '6', 'hello world hello world hello world hello world hello world ', '北京东方', '0', '0', null, null, '1', '1', '2017-05-08 17:21:20', 'partial');
INSERT INTO `zj_user_circle` VALUES ('7', '6', '亚欧巴萨', '北京东方', '1111111111', '11111111', 'www.baidu.com', null, '0', '0', '2017-05-08 17:22:58', 'public');
INSERT INTO `zj_user_circle` VALUES ('8', '6', 'hi hi hi', '北京东方', '0', '0', null, null, '0', '0', '2017-05-08 17:23:50', 'public');
INSERT INTO `zj_user_circle` VALUES ('10', '7', '', '', '0', '0', null, 'www.baidu.com', '0', '0', '2017-05-09 16:51:58', 'public');

-- ----------------------------
-- Table structure for `zj_user_circle_access`
-- ----------------------------
DROP TABLE IF EXISTS `zj_user_circle_access`;
CREATE TABLE `zj_user_circle_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_circle_id` int(11) NOT NULL COMMENT '某条朋友圈的id, 对应zj_user_circle表中的id',
  `access_user_id` int(11) NOT NULL COMMENT '哪些好友可以看这条朋友圈 user_circle_access_all: 全部朋友可看; user_circle_access_self: 仅自己可见; 如果为数字，则这个数字的用户可看',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_user_circle_access
-- ----------------------------
INSERT INTO `zj_user_circle_access` VALUES ('57', '5', '7');
INSERT INTO `zj_user_circle_access` VALUES ('58', '5', '8');
INSERT INTO `zj_user_circle_access` VALUES ('59', '6', '11');
INSERT INTO `zj_user_circle_access` VALUES ('60', '6', '12');

-- ----------------------------
-- Table structure for `zj_user_circle_collection`
-- ----------------------------
DROP TABLE IF EXISTS `zj_user_circle_collection`;
CREATE TABLE `zj_user_circle_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id, 对应zj_user表中的id',
  `user_circle_id` int(11) NOT NULL COMMENT '某条朋友圈的id, 对应zj_user_circle表中的id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_user_circle_collection
-- ----------------------------
INSERT INTO `zj_user_circle_collection` VALUES ('2', '7', '5');
INSERT INTO `zj_user_circle_collection` VALUES ('3', '7', '7');
INSERT INTO `zj_user_circle_collection` VALUES ('4', '7', '8');

-- ----------------------------
-- Table structure for `zj_user_circle_comment`
-- ----------------------------
DROP TABLE IF EXISTS `zj_user_circle_comment`;
CREATE TABLE `zj_user_circle_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL COMMENT '评论所对应的父级id, comment_type为for_circle时，parent_id对应zj_user_circle表中的id;comment_type为for_comment时，parent_id对应当前表中的id;',
  `comment_user_id` int(11) NOT NULL COMMENT '评论的用户id, 对应zj_user表中的id',
  `comment_time` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00' COMMENT '评论时间',
  `comment_type` enum('for_circle','for_comment') DEFAULT 'for_circle' COMMENT '评论类型 for_circle: 朋友圈的评价 for_comment:评价的评价',
  `content` varchar(255) DEFAULT NULL COMMENT '评论内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_user_circle_comment
-- ----------------------------
INSERT INTO `zj_user_circle_comment` VALUES ('3', '5', '7', '2017-05-09 09:35:32', 'for_circle', '不错哦');
INSERT INTO `zj_user_circle_comment` VALUES ('4', '5', '9', '1970-01-02 00:00:00', 'for_circle', '哈哈');
INSERT INTO `zj_user_circle_comment` VALUES ('5', '3', '8', '2017-05-09 09:35:55', 'for_comment', '也不错哦');
INSERT INTO `zj_user_circle_comment` VALUES ('6', '5', '10', '2017-05-09 10:17:51', 'for_comment', '嘿嘿');

-- ----------------------------
-- Table structure for `zj_user_circle_img`
-- ----------------------------
DROP TABLE IF EXISTS `zj_user_circle_img`;
CREATE TABLE `zj_user_circle_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_circle_id` int(11) NOT NULL COMMENT '某条朋友圈的id, 对应zj_user_circle表中的id',
  `url` varchar(255) DEFAULT NULL COMMENT '图片的链接',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_user_circle_img
-- ----------------------------
INSERT INTO `zj_user_circle_img` VALUES ('5', '6', '7', 'public/upload/images/20170508/recson_201705081722583504410039688.jpg');
INSERT INTO `zj_user_circle_img` VALUES ('6', '6', '7', 'public/upload/images/20170508/recson_201705081722592134900087051.jpg');
INSERT INTO `zj_user_circle_img` VALUES ('7', '6', '8', 'public/upload/images/20170508/recson_201705081723508944460098870.jpg');
INSERT INTO `zj_user_circle_img` VALUES ('8', '6', '8', 'public/upload/images/20170508/recson_201705081723513204700018051.jpg');

-- ----------------------------
-- Table structure for `zj_user_circle_thumbs_up`
-- ----------------------------
DROP TABLE IF EXISTS `zj_user_circle_thumbs_up`;
CREATE TABLE `zj_user_circle_thumbs_up` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_circle_id` int(11) NOT NULL COMMENT '某条朋友圈的id, 对应zj_user_circle表中的id',
  `thumbs_up_user_id` int(11) NOT NULL COMMENT '点赞的用户id, 对应zj_user表中的id',
  `thumbs_up_time` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00' COMMENT '点赞时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_user_circle_thumbs_up
-- ----------------------------
INSERT INTO `zj_user_circle_thumbs_up` VALUES ('2', '5', '8', '2017-05-09 09:36:40');
INSERT INTO `zj_user_circle_thumbs_up` VALUES ('3', '5', '9', '1970-01-02 00:00:00');

-- ----------------------------
-- Table structure for `zj_user_circle_tip`
-- ----------------------------
DROP TABLE IF EXISTS `zj_user_circle_tip`;
CREATE TABLE `zj_user_circle_tip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_circle_id` int(11) NOT NULL COMMENT '某条朋友圈的id, 对应zj_user_circle表中的id',
  `tip_user_id` varchar(36) DEFAULT NULL COMMENT '提醒看某条朋友圈的用户id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_user_circle_tip
-- ----------------------------
INSERT INTO `zj_user_circle_tip` VALUES ('57', '5', '9');
INSERT INTO `zj_user_circle_tip` VALUES ('58', '5', '10');
INSERT INTO `zj_user_circle_tip` VALUES ('59', '6', '9');
INSERT INTO `zj_user_circle_tip` VALUES ('60', '6', '10');

-- ----------------------------
-- Table structure for `zj_user_circle_video`
-- ----------------------------
DROP TABLE IF EXISTS `zj_user_circle_video`;
CREATE TABLE `zj_user_circle_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_circle_id` int(11) NOT NULL COMMENT '某条朋友圈的id, 对应zj_user_circle表中的id',
  `url` varchar(255) DEFAULT NULL COMMENT '视频的链接',
  `thumbnail_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_user_circle_video
-- ----------------------------

-- ----------------------------
-- Table structure for `zj_user_group`
-- ----------------------------
DROP TABLE IF EXISTS `zj_user_group`;
CREATE TABLE `zj_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id, 对应zj_user表中的id',
  `group_id` int(11) NOT NULL COMMENT '群id, 对应zj_group表中的id',
  `in_address_book` smallint(1) DEFAULT '0' COMMENT '是否保存到通讯录 0: 没有保存到通讯录 1: 已经保存到通讯录',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_user_group
-- ----------------------------
INSERT INTO `zj_user_group` VALUES ('16', '7', '5', '0');
INSERT INTO `zj_user_group` VALUES ('17', '10', '5', '0');
INSERT INTO `zj_user_group` VALUES ('18', '11', '5', '0');
INSERT INTO `zj_user_group` VALUES ('19', '12', '5', '0');
INSERT INTO `zj_user_group` VALUES ('20', '6', '5', '0');
INSERT INTO `zj_user_group` VALUES ('38', '14', '17', '1');
INSERT INTO `zj_user_group` VALUES ('39', '15', '17', '0');
INSERT INTO `zj_user_group` VALUES ('40', '14', '17', '1');
INSERT INTO `zj_user_group` VALUES ('41', '15', '18', '0');
INSERT INTO `zj_user_group` VALUES ('42', '6', '18', '0');
INSERT INTO `zj_user_group` VALUES ('43', '14', '18', '0');
INSERT INTO `zj_user_group` VALUES ('44', '15', '19', '0');
INSERT INTO `zj_user_group` VALUES ('45', '6', '19', '0');
INSERT INTO `zj_user_group` VALUES ('46', '15', '20', '0');
INSERT INTO `zj_user_group` VALUES ('47', '6', '20', '0');
INSERT INTO `zj_user_group` VALUES ('48', '14', '20', '0');

-- ----------------------------
-- Table structure for `zj_user_luntan_complaint`
-- ----------------------------
DROP TABLE IF EXISTS `zj_user_luntan_complaint`;
CREATE TABLE `zj_user_luntan_complaint` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id, 对应zj_user表中的id',
  `complaint_user_id` int(11) NOT NULL COMMENT '被投诉用户id, 对应zj_user表中的id',
  `content` varchar(36) DEFAULT NULL COMMENT '投诉内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_user_luntan_complaint
-- ----------------------------

-- ----------------------------
-- Table structure for `zj_user_luntan_focus`
-- ----------------------------
DROP TABLE IF EXISTS `zj_user_luntan_focus`;
CREATE TABLE `zj_user_luntan_focus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id, 对应zj_user表中的id',
  `luntan_id` int(11) NOT NULL COMMENT '论坛id, 对应zj_luntan表中的id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_user_luntan_focus
-- ----------------------------
INSERT INTO `zj_user_luntan_focus` VALUES ('2', '6', '2');
INSERT INTO `zj_user_luntan_focus` VALUES ('6', '6', '1');

-- ----------------------------
-- Table structure for `zj_user_tiezi_collection`
-- ----------------------------
DROP TABLE IF EXISTS `zj_user_tiezi_collection`;
CREATE TABLE `zj_user_tiezi_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id, 对应zj_user表中的id',
  `luntan_tiezi_id` int(11) NOT NULL COMMENT '论坛帖子id, 对应zj_luntan_tiezi表中的id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_user_tiezi_collection
-- ----------------------------
INSERT INTO `zj_user_tiezi_collection` VALUES ('2', '6', '3');
INSERT INTO `zj_user_tiezi_collection` VALUES ('3', '6', '4');
INSERT INTO `zj_user_tiezi_collection` VALUES ('4', '6', '5');

-- ----------------------------
-- Table structure for `zj_user_tiezi_comment`
-- ----------------------------
DROP TABLE IF EXISTS `zj_user_tiezi_comment`;
CREATE TABLE `zj_user_tiezi_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL COMMENT '评论所对应的父级id, comment_type为for_tiezi时，parent_id对应zj_luntan_tiezi表中的id;comment_type为for_comment时，parent_id对应当前表中的id;',
  `comment_user_id` int(11) NOT NULL COMMENT '评论的用户id, 对应zj_user表中的id',
  `comment_time` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00' COMMENT '评论时间',
  `comment_type` enum('for_tiezi','for_comment') DEFAULT 'for_tiezi' COMMENT '评论类型 for_tiezi: 帖子的评价 for_comment:评价的评价',
  `content` varchar(255) DEFAULT NULL COMMENT '评论内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zj_user_tiezi_comment
-- ----------------------------
INSERT INTO `zj_user_tiezi_comment` VALUES ('5', '3', '10', '2017-05-10 13:46:22', 'for_tiezi', 'haha');
INSERT INTO `zj_user_tiezi_comment` VALUES ('6', '4', '10', '2017-05-10 13:46:46', 'for_tiezi', '嘿嘿');
INSERT INTO `zj_user_tiezi_comment` VALUES ('7', '5', '10', '2017-05-10 13:47:25', 'for_comment', '嘿嘿');
INSERT INTO `zj_user_tiezi_comment` VALUES ('8', '7', '8', '2017-05-10 13:48:18', 'for_comment', '呵呵呵');


-- ----------------------------
-- Table structure for `zj_package_version`
-- ----------------------------
DROP TABLE IF EXISTS `zj_package_version`;
CREATE TABLE `zj_package_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` enum('iphone','android','none') DEFAULT 'none' COMMENT '消息类型 iphone: 苹果手机 android:安卓手机',
  `url` varchar(255) DEFAULT NULL COMMENT '下载地址',
  `version` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
