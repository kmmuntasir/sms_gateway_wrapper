/*
Navicat MySQL Data Transfer

Source Server         : lampp
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : sms_gateway_wrapper

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2020-10-19 21:57:25
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
  `create_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`client_id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  KEY `status_id` (`status_id`),
  CONSTRAINT `client_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `manager` (`manager_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `client_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `manager` (`manager_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `client_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  `log_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`log_id`),
  KEY `log_type_id` (`log_type_id`),
  KEY `manager_id` (`manager_id`),
  KEY `client_id` (`client_id`),
  KEY `token_id` (`token_id`),
  KEY `token_type_id` (`token_type_id`),
  KEY `recharge_id` (`recharge_id`),
  CONSTRAINT `log_ibfk_1` FOREIGN KEY (`log_type_id`) REFERENCES `log_type` (`log_type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_ibfk_2` FOREIGN KEY (`manager_id`) REFERENCES `manager` (`manager_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_ibfk_3` FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_ibfk_4` FOREIGN KEY (`token_id`) REFERENCES `token` (`token_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_ibfk_5` FOREIGN KEY (`token_type_id`) REFERENCES `token_type` (`token_type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `log_ibfk_6` FOREIGN KEY (`recharge_id`) REFERENCES `recharge` (`recharge_id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  PRIMARY KEY (`manager_id`),
  KEY `status_id` (`status_id`),
  KEY `manager_role_id` (`manager_role_id`),
  CONSTRAINT `manager_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `manager_ibfk_2` FOREIGN KEY (`manager_role_id`) REFERENCES `manager_role` (`manager_role_id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  `created_by` int(10) unsigned NOT NULL,
  `updated_by` int(11) unsigned NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`recharge_id`),
  KEY `token_id` (`token_id`),
  KEY `client_id` (`client_id`),
  KEY `status_id` (`status_id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  CONSTRAINT `recharge_ibfk_1` FOREIGN KEY (`token_id`) REFERENCES `token` (`token_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `recharge_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `recharge_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `recharge_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `manager` (`manager_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `recharge_ibfk_5` FOREIGN KEY (`updated_by`) REFERENCES `manager` (`manager_id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  `token_rate` double(10,2) NOT NULL DEFAULT 0.00,
  `token_balance` double(10,2) NOT NULL DEFAULT 0.00,
  `token_expiry` timestamp NULL DEFAULT NULL,
  `token_type_id` int(10) unsigned NOT NULL,
  `client_id` int(10) unsigned NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `updated_by` int(10) unsigned NOT NULL,
  `status_id` int(10) unsigned NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`token_id`,`token_key`),
  KEY `token_type_id` (`token_type_id`),
  KEY `token_ibfk_2` (`created_by`),
  KEY `token_ibfk_3` (`updated_by`),
  KEY `status_id` (`status_id`),
  KEY `client_id` (`client_id`),
  KEY `token_id` (`token_id`),
  CONSTRAINT `token_ibfk_1` FOREIGN KEY (`token_type_id`) REFERENCES `token_type` (`token_type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `token_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `manager` (`manager_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `token_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `manager` (`manager_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `token_ibfk_4` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `token_ibfk_5` FOREIGN KEY (`client_id`) REFERENCES `client` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  `created_by` int(10) unsigned NOT NULL,
  `updated_by` int(10) unsigned NOT NULL,
  `status_id` int(10) unsigned NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`token_type_id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`),
  KEY `status_id` (`status_id`),
  CONSTRAINT `token_type_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `manager` (`manager_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `token_type_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `manager` (`manager_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `token_type_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of token_type
-- ----------------------------
