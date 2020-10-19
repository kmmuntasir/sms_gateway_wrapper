/*
Navicat MySQL Data Transfer

Source Server         : lampp
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : sms_gateway_wrapper

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2020-10-19 11:40:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `client`
-- ----------------------------
DROP TABLE IF EXISTS `client`;
CREATE TABLE `client` (
  `client_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_contact_person` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_pass` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `updated_by` int(10) unsigned NOT NULL,
  `status_id` int(10) unsigned NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of client
-- ----------------------------

-- ----------------------------
-- Table structure for `log`
-- ----------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_type_id` int(10) unsigned NOT NULL,
  `manager_id` int(11) unsigned DEFAULT NULL,
  `client_id` int(11) unsigned DEFAULT NULL,
  `token_id` int(11) unsigned DEFAULT NULL,
  `token_type_id` int(11) unsigned DEFAULT NULL,
  `recharge_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of log
-- ----------------------------

-- ----------------------------
-- Table structure for `log_type`
-- ----------------------------
DROP TABLE IF EXISTS `log_type`;
CREATE TABLE `log_type` (
  `log_type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_type_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`log_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of log_type
-- ----------------------------

-- ----------------------------
-- Table structure for `manager`
-- ----------------------------
DROP TABLE IF EXISTS `manager`;
CREATE TABLE `manager` (
  `manager_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `manager_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `manager_user` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `manager_pass` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `manager_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manager_phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `manager_role_id` int(10) unsigned NOT NULL,
  `status_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`manager_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of manager
-- ----------------------------

-- ----------------------------
-- Table structure for `manager_role`
-- ----------------------------
DROP TABLE IF EXISTS `manager_role`;
CREATE TABLE `manager_role` (
  `manager_role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `manager_role_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`manager_role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of manager_role
-- ----------------------------

-- ----------------------------
-- Table structure for `recharge`
-- ----------------------------
DROP TABLE IF EXISTS `recharge`;
CREATE TABLE `recharge` (
  `recharge_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `recharge_amount` double(10,2) NOT NULL,
  `token_id` int(10) unsigned NOT NULL,
  `client_id` int(10) unsigned NOT NULL,
  `status_id` int(10) unsigned NOT NULL,
  `manager_id` int(10) unsigned NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`recharge_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of recharge
-- ----------------------------

-- ----------------------------
-- Table structure for `settings`
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `settings_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `settings_company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `settings_company_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `settings_company_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `settings_company_phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`settings_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of settings
-- ----------------------------

-- ----------------------------
-- Table structure for `status`
-- ----------------------------
DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `status_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of status
-- ----------------------------

-- ----------------------------
-- Table structure for `token`
-- ----------------------------
DROP TABLE IF EXISTS `token`;
CREATE TABLE `token` (
  `token_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token_price` double(10,2) DEFAULT NULL,
  `token_balance` double(10,2) NOT NULL DEFAULT 0.00,
  `token_type_id` int(10) unsigned NOT NULL,
  `client_id` int(10) unsigned NOT NULL,
  `manager_id` int(10) unsigned NOT NULL,
  `status_id` int(10) unsigned NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`token_id`,`token_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of token
-- ----------------------------

-- ----------------------------
-- Table structure for `token_type`
-- ----------------------------
DROP TABLE IF EXISTS `token_type`;
CREATE TABLE `token_type` (
  `token_type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `token_type_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token_type_price` double(10,2) NOT NULL,
  `token_type_min_purchase` int(11) NOT NULL DEFAULT 0,
  `token_type_min_usage` int(11) NOT NULL DEFAULT 0,
  `manager_id` int(10) unsigned NOT NULL,
  `status_id` int(10) unsigned NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`token_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of token_type
-- ----------------------------
