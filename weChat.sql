/*
 Navicat Premium Data Transfer

 Source Server         : 测试
 Source Server Type    : MySQL
 Source Server Version : 50728
 Source Host           : 192.168.0.163:3306
 Source Schema         : weChat

 Target Server Type    : MySQL
 Target Server Version : 50728
 File Encoding         : 65001

 Date: 29/11/2019 18:38:03
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for chat_admin
-- ----------------------------
DROP TABLE IF EXISTS `chat_admin`;
CREATE TABLE `chat_admin`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `mobile` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '手机号',
  `salt` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '盐',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态:0正常 1禁用',
  `last_login_ip` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '最后登录ip',
  `last_login_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后登录时间',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_admin_permission
-- ----------------------------
DROP TABLE IF EXISTS `chat_admin_permission`;
CREATE TABLE `chat_admin_permission`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '权限名称',
  `url` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '访问url地址',
  `parent_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '上级id',
  `status` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '是否可用 0：正常，1 禁用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员权限表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `chat_admin_role`;
CREATE TABLE `chat_admin_role`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `role_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `role_desc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '角色描述',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态0 可用 1 禁用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_admin_role_permission
-- ----------------------------
DROP TABLE IF EXISTS `chat_admin_role_permission`;
CREATE TABLE `chat_admin_role_permission`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `role_id` int(10) UNSIGNED NOT NULL COMMENT '角色id',
  `permission_id` int(10) UNSIGNED NOT NULL COMMENT '权限id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色权限关系表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_admin_roles
-- ----------------------------
DROP TABLE IF EXISTS `chat_admin_roles`;
CREATE TABLE `chat_admin_roles`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `admin_id` int(10) UNSIGNED NOT NULL COMMENT '管理员id',
  `role_id` int(10) UNSIGNED NOT NULL COMMENT '角色id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员角色关系表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_advertisement
-- ----------------------------
DROP TABLE IF EXISTS `chat_advertisement`;
CREATE TABLE `chat_advertisement`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '广告标题',
  `subhead` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '副标题',
  `content` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '文字内容',
  `pic_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '广告图片',
  `ads_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '广告地址',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '广告状态：0.删除，1.审核，2.正常',
  `impression` tinyint(1) NULL DEFAULT 0 COMMENT '显示优先级：默认：0，数字越高，显示优先级越高',
  `clicks_num` int(10) NULL DEFAULT 0 COMMENT '点击量',
  `business` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '投放广告商',
  `connect_way` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '投放广告商联系方式',
  `expire_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '广告下架时间, 0表示永远不下架',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '备注',
  `placement_id` int(10) UNSIGNED NOT NULL COMMENT '广告位id',
  `create_time` int(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '投放广告表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_advertisement_placements
-- ----------------------------
DROP TABLE IF EXISTS `chat_advertisement_placements`;
CREATE TABLE `chat_advertisement_placements`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `placement` tinyint(2) NOT NULL COMMENT '广告位标志',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '广告位名称',
  `disable` tinyint(1) NOT NULL DEFAULT 1 COMMENT '广告位状态：1表示广告位启用,0表示广告位未启用',
  `create_time` int(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '广告位数据' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_bank_config
-- ----------------------------
DROP TABLE IF EXISTS `chat_bank_config`;
CREATE TABLE `chat_bank_config`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sign` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标识',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '开户行名称',
  `logo` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'logo图片',
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '#fff' COMMENT '颜色',
  `status` int(11) NOT NULL DEFAULT 1 COMMENT '状态：1开启0禁用',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '银行配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_change_record
-- ----------------------------
DROP TABLE IF EXISTS `chat_change_record`;
CREATE TABLE `chat_change_record`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `type` smallint(5) NOT NULL COMMENT '变更类型',
  `before_change` int(10) NOT NULL COMMENT '变更前',
  `after_change` int(10) NOT NULL COMMENT '变更后',
  `create_time` int(10) NOT NULL COMMENT '变更时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '变更记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_friend_circle_comment
-- ----------------------------
DROP TABLE IF EXISTS `chat_friend_circle_comment`;
CREATE TABLE `chat_friend_circle_comment`  (
  `id` bigint(15) NOT NULL AUTO_INCREMENT,
  `fcm_id` bigint(15) NULL DEFAULT NULL COMMENT '朋友圈信息id',
  `user_id` bigint(15) NULL DEFAULT NULL COMMENT '用户id',
  `content` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '创建日期',
  `like_count` int(10) NULL DEFAULT 0 COMMENT '点赞数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '朋友圈评论表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chat_friend_circle_comment
-- ----------------------------
INSERT INTO `chat_friend_circle_comment` VALUES (1, 1, 1001, '请问恶趣味', '2019-11-21 14:15:14', 0);
INSERT INTO `chat_friend_circle_comment` VALUES (2, 1, 1000, '撒大声地', '2019-11-21 15:06:41', 0);

-- ----------------------------
-- Table structure for chat_friend_circle_message
-- ----------------------------
DROP TABLE IF EXISTS `chat_friend_circle_message`;
CREATE TABLE `chat_friend_circle_message`  (
  `id` bigint(15) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` bigint(15) NULL DEFAULT NULL COMMENT '用户id',
  `content` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '内容',
  `picture` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '' COMMENT '图片',
  `location` varbinary(100) NULL DEFAULT '' COMMENT '位置',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '好友文章发布表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chat_friend_circle_message
-- ----------------------------
INSERT INTO `chat_friend_circle_message` VALUES (1, 1002, 'aSDASDQWEQWEASDA奥术大师多', '', '', '2019-11-21 14:16:25');

-- ----------------------------
-- Table structure for chat_friend_circle_timeline
-- ----------------------------
DROP TABLE IF EXISTS `chat_friend_circle_timeline`;
CREATE TABLE `chat_friend_circle_timeline`  (
  `id` bigint(15) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(15) NULL DEFAULT NULL COMMENT '用户id',
  `fcm_id` bigint(15) NULL DEFAULT NULL COMMENT '朋友圈信息id',
  `is_own` int(1) NULL DEFAULT 0 COMMENT '是否是自己的',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '朋友圈时间轴表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chat_friend_circle_timeline
-- ----------------------------
INSERT INTO `chat_friend_circle_timeline` VALUES (1, 1002, 1, 1, '2019-11-21 14:25:04');

-- ----------------------------
-- Table structure for chat_grab_packets
-- ----------------------------
DROP TABLE IF EXISTS `chat_grab_packets`;
CREATE TABLE `chat_grab_packets`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `red_id` int(11) NOT NULL DEFAULT 0 COMMENT '红包ID',
  `red_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '红包金额',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '抢包用户ID',
  `fetched_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '抢包金额',
  `ip` char(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'ip地址',
  `create_time` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`create_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '抢包记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_grade
-- ----------------------------
DROP TABLE IF EXISTS `chat_grade`;
CREATE TABLE `chat_grade`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '等级名称',
  `grade` smallint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '等级',
  `image` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '等级图标',
  `points` int(10) NOT NULL COMMENT '等级积分',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `name`(`name`) USING BTREE COMMENT '等级名称'
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '等级表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_order
-- ----------------------------
DROP TABLE IF EXISTS `chat_order`;
CREATE TABLE `chat_order`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `order_sn` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '订单号',
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单类型',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态 0:未处理 1 处理完成 2 处理失败',
  `create_time` int(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `order_sn`(`order_sn`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_pay_class
-- ----------------------------
DROP TABLE IF EXISTS `chat_pay_class`;
CREATE TABLE `chat_pay_class`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `alias` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类别名',
  `img` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类ICO',
  `level` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0,无等级限制, 1,用户level为1的显示...',
  `show` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否显示：0不显示，1显示',
  `status` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态：0不开启，1开启',
  `sort` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '支付大类' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_pay_ways
-- ----------------------------
DROP TABLE IF EXISTS `chat_pay_ways`;
CREATE TABLE `chat_pay_ways`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支付名称',
  `bank_id` int(11) NOT NULL DEFAULT 0 COMMENT '银行id',
  `account` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '转账账户',
  `account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '转账姓名',
  `pay_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '支付二维码',
  `min_money` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最小充值金额',
  `max_money` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最大充值金额',
  `pay_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '支付跳转url',
  `pay_receive_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '支付回调url',
  `app_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '商户秘钥',
  `merchant_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '商户号',
  `notice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '支付说明',
  `pay_type` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '支付类型：1,线上，2线下转账',
  `user_level` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0，没等级限制，1用户frozen_level为1的显示',
  `pay_class` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '支付大类',
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态：0异常，1正常',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '支付通道' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_red_packets
-- ----------------------------
DROP TABLE IF EXISTS `chat_red_packets`;
CREATE TABLE `chat_red_packets`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户ID',
  `amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '金额',
  `packet_count` int(10) NOT NULL DEFAULT 0 COMMENT '包数',
  `ip` char(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'ip地址',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：1领取中 2已领完 3已过期',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '发包记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_sys_config
-- ----------------------------
DROP TABLE IF EXISTS `chat_sys_config`;
CREATE TABLE `chat_sys_config`  (
  `key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '键',
  `value` tinyint(255) NOT NULL COMMENT '值',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '描述'
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chat_sys_config
-- ----------------------------
INSERT INTO `chat_sys_config` VALUES ('group_admin_num', 5, '群组管理员个数');
INSERT INTO `chat_sys_config` VALUES ('wqee', 12, 'qweqwe');
INSERT INTO `chat_sys_config` VALUES ('qwe`', 11, 'qwe');

-- ----------------------------
-- Table structure for chat_user
-- ----------------------------
DROP TABLE IF EXISTS `chat_user`;
CREATE TABLE `chat_user`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `nick_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(1) UNSIGNED ZEROFILL NULL DEFAULT 0 COMMENT '性别 0:保密 1:男 2:女',
  `age` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '年龄',
  `phone` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `password` char(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `grade` smallint(3) NOT NULL DEFAULT 0 COMMENT '用户等级',
  `points` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户积分',
  `amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户金额',
  `openid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'openid',
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '头像',
  `initials` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '首字母',
  `signature` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '个性签名',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态 0:删除 1:正常 2:禁用',
  `user type` tinyint(1) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 COMMENT '用户类型 0:普通用户 1:系统用户',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `delete_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `account`(`account`) USING BTREE,
  UNIQUE INDEX `phone`(`phone`) USING BTREE,
  INDEX `email`(`email`) USING BTREE,
  INDEX `openid`(`openid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1008 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户基础信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chat_user
-- ----------------------------
INSERT INTO `chat_user` VALUES (1000, 'qiuapeng921', 'Ri2cjkVoJh', 0, 0, '15249279779', '$2y$10$PtlTkxgbu3sEJ7oyTcs8Y.TLLnwg1Vnn/5sHxih7kB600wFLbK6qu', '', 0, 0, 0, '', 'http://192.168.0.163/images/1.jpg', '', '', 1, 0, 1573783796, 0, 0);
INSERT INTO `chat_user` VALUES (1001, 'admin123', '时光巷陌', 2, 0, '15664879332', '$2y$10$xazalDwCOMAXxq8oEABml.GOx4FepUTg1GmJbalcMyXESAd.HCEka', '', 0, 0, 0, '', 'http://192.168.0.106:9501/upload/file/5ddde916dfd20.jpg', '', '', 1, 0, 1573783824, 0, 0);
INSERT INTO `chat_user` VALUES (1002, 'admin111', '日暮途远', 0, 0, '18192393725', '$2y$10$MDNLnQKZRqjmYvP15RwP8.mUGtmF1o/8w1Hgah1Y6k7uFHk8fD3lu', '', 0, 0, 0, '', 'http://192.168.0.106:9501/upload/file/5ddf83539fbbf.png', '', '', 1, 0, 1573784025, 0, 0);
INSERT INTO `chat_user` VALUES (1003, 'xiaodao123', 'PRWs3wk4Y7', 0, 0, '18298839611', '$2y$10$um81JgKM7RQwgkKHYsprIO4OYjOJeBEr8ltdd0eh2rZFPdolYAdc.', '', 0, 0, 0, '', 'http://192.168.0.163/images/2.jpg', '', '', 1, 0, 1573885627, 0, 0);
INSERT INTO `chat_user` VALUES (1004, 'admin1234', 'yVDum0tj8c', 0, 0, '18192393721', '$2y$10$AyHH2QA0HjvDuc4zOWwNfOs.tq825VkagmxESg.W9BSjFPcaXcuCS', '', 0, 0, 0, '', 'http://192.168.0.163/images/6.jpg', '', '', 1, 0, 1573887085, 0, 0);
INSERT INTO `chat_user` VALUES (1005, 'admin12345', 'svZoJKwYmP', 0, 0, '18192393722', '$2y$10$8wQPxsZhwMKFguIuAcfsnuQ9fXVY.hWifrQWyhhrqwSJLG/4FeBcK', '', 0, 0, 0, '', 'http://192.168.0.163/images/2.jpg', '', '', 1, 0, 1573887144, 0, 0);
INSERT INTO `chat_user` VALUES (1006, 'admin123456', '3d5TZJ4IQg', 0, 0, '18192393723', '$2y$10$Fw5SZQd38YPdhlmQ4ThSU.453LRE5UjSzFAAvg2Ub8/g8uyFAVDXm', '', 0, 0, 0, '', 'http://192.168.0.163/images/4.jpg', '', '', 1, 0, 1573887195, 0, 0);
INSERT INTO `chat_user` VALUES (1007, 'admin222', 'hiswdHJOUT', 0, 0, '18192393724', '$2y$10$z7DTNU5/YDmHmzlUFtV7I.dtRIcLYNiUDb6EmDKZFNtXQvbiMKLBi', '', 0, 0, 0, '', 'http://192.168.0.163/images/6.jpg', '', '', 1, 0, 1573887364, 0, 0);

-- ----------------------------
-- Table structure for chat_user_apply
-- ----------------------------
DROP TABLE IF EXISTS `chat_user_apply`;
CREATE TABLE `chat_user_apply`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT ' 主键id',
  `friend_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '被添加人ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加人ID',
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '申请消息',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态(0：待审核1：通过2：拒绝)',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '申请时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 41 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户申请添加好友表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_user_bank_cards
-- ----------------------------
DROP TABLE IF EXISTS `chat_user_bank_cards`;
CREATE TABLE `chat_user_bank_cards`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `bank_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '开户行名称',
  `owner_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '持卡人名称',
  `card_number` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '银行卡号',
  `admin_id` int(10) NOT NULL DEFAULT 0 COMMENT '管理员id',
  `bank_sign` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '开户行标识',
  `province_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '省份编码',
  `city_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '城市编码',
  `branch` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支行名称',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '状态：1启用 0禁用',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE,
  INDEX `owner_name`(`owner_name`) USING BTREE,
  INDEX `card_number`(`card_number`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户银行卡' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_user_behavior
-- ----------------------------
DROP TABLE IF EXISTS `chat_user_behavior`;
CREATE TABLE `chat_user_behavior`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(10) NOT NULL DEFAULT 0 COMMENT '用户id',
  `device_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '设备类型',
  `platform_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '平台类型',
  `platform_version` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '平台版本',
  `browser_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '浏览器类型',
  `browser_version` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '浏览器版本',
  `login_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '登录ip',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户行为表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_user_friend
-- ----------------------------
DROP TABLE IF EXISTS `chat_user_friend`;
CREATE TABLE `chat_user_friend`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `friend_id` int(10) UNSIGNED NOT NULL COMMENT '好友的ID',
  `friend_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '好友备注',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态 0申请 1正常 2拒绝',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `friend_user`(`friend_id`, `user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '好友表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chat_user_friend
-- ----------------------------
INSERT INTO `chat_user_friend` VALUES (1, 1002, '默认昵称', 1000, 1);
INSERT INTO `chat_user_friend` VALUES (2, 1001, '默认昵称', 1000, 1);
INSERT INTO `chat_user_friend` VALUES (4, 1000, '默认昵称', 1001, 1);
INSERT INTO `chat_user_friend` VALUES (5, 1000, '默认昵称', 1002, 1);
INSERT INTO `chat_user_friend` VALUES (6, 1003, '默认昵称', 1002, 1);
INSERT INTO `chat_user_friend` VALUES (8, 1004, '默认昵称', 1002, 1);
INSERT INTO `chat_user_friend` VALUES (9, 1002, '默认昵称', 1004, 1);
INSERT INTO `chat_user_friend` VALUES (10, 1005, '默认昵称', 1002, 1);
INSERT INTO `chat_user_friend` VALUES (11, 1002, '默认昵称', 1005, 1);
INSERT INTO `chat_user_friend` VALUES (12, 1006, '默认昵称', 1002, 1);
INSERT INTO `chat_user_friend` VALUES (13, 1002, '默认昵称', 1006, 1);
INSERT INTO `chat_user_friend` VALUES (14, 1007, '默认昵称', 1002, 1);
INSERT INTO `chat_user_friend` VALUES (15, 1002, '默认昵称', 1007, 1);
INSERT INTO `chat_user_friend` VALUES (24, 1000, '', 1003, 0);
INSERT INTO `chat_user_friend` VALUES (25, 1006, '', 1001, 0);
INSERT INTO `chat_user_friend` VALUES (28, 1003, '', 1001, 1);

-- ----------------------------
-- Table structure for chat_user_group
-- ----------------------------
DROP TABLE IF EXISTS `chat_user_group`;
CREATE TABLE `chat_user_group`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '创建人用户ID',
  `group_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '组名',
  `group_notice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '群公告',
  `admin_num` tinyint(2) NULL DEFAULT NULL COMMENT '已添加管理员人数',
  `open_admin` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否开启群管理',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态(1:正常)',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户组表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chat_user_group
-- ----------------------------
INSERT INTO `chat_user_group` VALUES (9, 1002, '群聊', '', NULL, 0, 1, 0, 0);
INSERT INTO `chat_user_group` VALUES (10, 1002, '群聊', '', NULL, 0, 1, 0, 0);
INSERT INTO `chat_user_group` VALUES (11, 1002, '群聊', '', NULL, 0, 1, 0, 0);
INSERT INTO `chat_user_group` VALUES (12, 1002, '群聊', '', NULL, 0, 1, 0, 0);
INSERT INTO `chat_user_group` VALUES (13, 1001, '群聊', '', NULL, 0, 1, 0, 0);
INSERT INTO `chat_user_group` VALUES (14, 1002, '群聊', '', NULL, 0, 1, 0, 0);
INSERT INTO `chat_user_group` VALUES (15, 1002, '群聊', '', NULL, 0, 1, 0, 0);
INSERT INTO `chat_user_group` VALUES (16, 1002, '群聊', '', NULL, 0, 1, 0, 0);
INSERT INTO `chat_user_group` VALUES (17, 1002, '群聊', '', NULL, 0, 1, 0, 0);
INSERT INTO `chat_user_group` VALUES (18, 1002, '群聊', '', NULL, 0, 1, 0, 0);
INSERT INTO `chat_user_group` VALUES (19, 1002, '群聊', '', NULL, 0, 1, 0, 0);
INSERT INTO `chat_user_group` VALUES (20, 1002, '群聊', '', NULL, 0, 1, 0, 0);

-- ----------------------------
-- Table structure for chat_user_group_member
-- ----------------------------
DROP TABLE IF EXISTS `chat_user_group_member`;
CREATE TABLE `chat_user_group_member`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `group_nick_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '群昵称',
  `group_id` int(10) UNSIGNED NOT NULL COMMENT '组ID',
  `is_admin` tinyint(1) NULL DEFAULT 0 COMMENT '是不是管理员：1是0不是',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '状态：0申请1正常',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `member_id`(`user_id`, `group_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 129 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户组成员表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chat_user_group_member
-- ----------------------------
INSERT INTO `chat_user_group_member` VALUES (67, 1000, NULL, 9, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (68, 1003, NULL, 9, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (69, 1004, NULL, 9, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (70, 1005, NULL, 9, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (71, 1006, NULL, 9, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (72, 1007, NULL, 9, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (73, 1002, NULL, 9, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (74, 1003, NULL, 10, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (75, 1000, NULL, 10, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (76, 1004, NULL, 10, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (77, 1007, NULL, 10, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (78, 1006, NULL, 10, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (79, 1002, NULL, 10, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (80, 1000, NULL, 11, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (81, 1003, NULL, 11, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (82, 1004, NULL, 11, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (83, 1005, NULL, 11, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (84, 1006, NULL, 11, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (85, 1007, NULL, 11, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (86, 1002, NULL, 11, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (87, 1000, NULL, 12, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (88, 1003, NULL, 12, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (89, 1004, NULL, 12, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (90, 1005, NULL, 12, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (91, 1006, NULL, 12, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (92, 1007, NULL, 12, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (93, 1002, NULL, 12, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (94, 1000, NULL, 13, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (95, 1003, NULL, 13, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (96, 1001, NULL, 13, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (97, 1000, NULL, 14, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (98, 1002, NULL, 14, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (99, 1000, NULL, 15, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (100, 1003, NULL, 15, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (101, 1002, NULL, 15, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (102, 1000, NULL, 16, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (103, 1003, NULL, 16, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (104, 1004, NULL, 16, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (105, 1002, NULL, 16, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (106, 1000, NULL, 17, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (107, 1003, NULL, 17, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (108, 1004, NULL, 17, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (109, 1005, NULL, 17, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (110, 1006, NULL, 17, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (111, 1007, NULL, 17, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (112, 1002, NULL, 17, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (113, 1000, NULL, 18, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (114, 1003, NULL, 18, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (115, 1004, NULL, 18, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (116, 1005, NULL, 18, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (117, 1006, NULL, 18, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (118, 1007, NULL, 18, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (119, 1002, NULL, 18, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (120, 1000, NULL, 19, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (121, 1003, NULL, 19, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (122, 1004, NULL, 19, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (123, 1005, NULL, 19, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (124, 1006, NULL, 19, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (125, 1002, NULL, 19, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (126, 1000, NULL, 20, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (127, 1003, NULL, 20, 0, 1);
INSERT INTO `chat_user_group_member` VALUES (128, 1002, NULL, 20, 0, 1);

-- ----------------------------
-- Table structure for chat_user_group_message
-- ----------------------------
DROP TABLE IF EXISTS `chat_user_group_message`;
CREATE TABLE `chat_user_group_message`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_id` int(10) NOT NULL COMMENT '群组ID',
  `user_id` int(10) NOT NULL COMMENT '用户ID',
  `content` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '消息内容',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态1:正常 0:删除',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `group_id`(`group_id`, `status`, `create_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户组聊天记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_user_message
-- ----------------------------
DROP TABLE IF EXISTS `chat_user_message`;
CREATE TABLE `chat_user_message`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '消息内容',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态1:正常 0:删除',
  `create_time` int(10) NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户消息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_user_proxy
-- ----------------------------
DROP TABLE IF EXISTS `chat_user_proxy`;
CREATE TABLE `chat_user_proxy`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `top_id` int(11) NOT NULL COMMENT '顶级代理ID',
  `parent_id` int(11) NOT NULL COMMENT '直系上级ID',
  `rid` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '各级直系代理ID',
  `type` tinyint(4) NOT NULL COMMENT '代理等级',
  `invite_code` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邀请码',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户代理表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_user_recharge
-- ----------------------------
DROP TABLE IF EXISTS `chat_user_recharge`;
CREATE TABLE `chat_user_recharge`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `order_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '订单ID',
  `channel` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '渠道名称',
  `amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '充值金额',
  `bank_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '银行名称',
  `real_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '真实到账',
  `sign` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '附言',
  `client_ip` char(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '充值IP',
  `fail_reason` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '失败原因',
  `desc` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '充值描述',
  `bank_sign` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '银行标识',
  `source` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1' COMMENT '支付类型：1:线上 2:线下转账',
  `admin_id` tinyint(3) NULL DEFAULT NULL COMMENT '管理员id',
  `init_time` int(10) NOT NULL DEFAULT 0 COMMENT '初始化时间',
  `request_time` int(10) NOT NULL DEFAULT 0 COMMENT '请求时间',
  `callback_time` int(10) NOT NULL DEFAULT 0 COMMENT '处理时间',
  `stat_time` int(10) NOT NULL DEFAULT 0 COMMENT '统计时间',
  `status` tinyint(3) NOT NULL DEFAULT 0 COMMENT '处理状态 0:未处理(默认); 1审核通过; 2 拒绝通过',
  `create_time` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_id`(`order_id`) USING BTREE,
  INDEX `user_id_request_time`(`user_id`, `request_time`) USING BTREE,
  INDEX `user_id_callback_time`(`user_id`, `callback_time`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户充值记录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_user_transfer_records
-- ----------------------------
DROP TABLE IF EXISTS `chat_user_transfer_records`;
CREATE TABLE `chat_user_transfer_records`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `from_user_id` int(10) NOT NULL COMMENT '发款人ID',
  `from_avatar` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '发款人头像',
  `from_username` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '发款人名称',
  `from_nickname` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '发款人昵称',
  `to_user_id` int(10) NOT NULL COMMENT '收款人ID',
  `to_avatar` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '收款人头像',
  `to_username` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '收款人名称',
  `to_nickname` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '收款人昵称',
  `amount` int(10) UNSIGNED NOT NULL COMMENT '转账金额',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `from_user_id_to_user_id`(`from_user_id`, `to_user_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户转账记录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat_user_withdraw
-- ----------------------------
DROP TABLE IF EXISTS `chat_user_withdraw`;
CREATE TABLE `chat_user_withdraw`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `order_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '订单ID',
  `card_id` int(10) NOT NULL COMMENT '银行卡ID',
  `amount` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '提现金额',
  `real_amount` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '实际提现金额',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述',
  `bank_sign` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '银行标识',
  `bank_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '银行名称',
  `source` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'web' COMMENT '提现设备',
  `client_ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '设备IP',
  `callback_time` int(10) NOT NULL DEFAULT 0 COMMENT '处理时间',
  `admin_id` int(10) NOT NULL DEFAULT 0 COMMENT '管理员ID',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '状态：0 待审核 1 领取2 审核完成 3 审核失败',
  `request_time` int(10) NOT NULL DEFAULT 0 COMMENT '请求时间',
  `expire_time` int(10) NOT NULL DEFAULT 0 COMMENT '过期时间',
  `process_time` int(10) NOT NULL DEFAULT 0 COMMENT '处理时间',
  `create_time` int(10) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id_request_time`(`user_id`, `request_time`) USING BTREE,
  INDEX `user_id_process_time`(`user_id`, `process_time`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户提现记录' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
