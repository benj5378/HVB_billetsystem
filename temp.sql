Opsætning af admin
		
-- ---
-- Globals
-- ---

-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- SET FOREIGN_KEY_CHECKS=0;

-- ---
-- Table 'hvb_admin_users'
-- 
-- ---
		
CREATE TABLE `hvb_admin_users` (
  `admin_user_id` INTEGER NOT NULL AUTO_INCREMENT,
  `admin_user_username` VARCHAR(255) NOT NULL,
  `admin_user_password` VARCHAR(255) NOT NULL,
  `admin_user_emailaddress` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`admin_user_id`)
);

-- ---
-- Table 'hvb_admin_privileges'
-- 
-- ---
		
CREATE TABLE `hvb_admin_privileges` (
  `admin_privilege_id` INTEGER NOT NULL AUTO_INCREMENT,
  `admin_privilege_name` VARCHAR(255) NOT NULL,
  `admin_users__admin_user_id` INTEGER NOT NULL,
  PRIMARY KEY (`admin_privilege_id`)
);

-- ---
-- Foreign Keys 
-- ---

ALTER TABLE `hvb_admin_privileges` ADD FOREIGN KEY (admin_users__admin_user_id) REFERENCES `hvb_admin_users` (`admin_user_id`);

-- ---
-- Table Properties
-- ---

-- ALTER TABLE `hvb_admin_users` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `hvb_admin_privileges` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Test Data
-- ---

-- INSERT INTO `hvb_admin_users` (`admin_user_id`,`admin_user_username`,`admin_user_password`,`admin_user_emailaddress`) VALUES
-- ('','','','');
-- INSERT INTO `hvb_admin_privileges` (`admin_privilege_id`,`admin_privilege_name`,`admin_users__admin_user_id`) VALUES
-- ('','','');




<?xml version="1.0" encoding="utf-8" ?>
<!-- SQL XML created by WWW SQL Designer, https://github.com/ondras/wwwsqldesigner/ -->
<!-- Active URL: https://ondras.zarovi.cz/sql/demo/?keyword=default -->
<sql>
<datatypes db="mysql">
  <group label="Numeric" color="rgb(238,238,170)">
    <type label="Integer" length="0" sql="INTEGER" quote=""/>
    <type label="TINYINT" length="0" sql="TINYINT" quote=""/>
    <type label="SMALLINT" length="0" sql="SMALLINT" quote=""/>
    <type label="MEDIUMINT" length="0" sql="MEDIUMINT" quote=""/>
    <type label="INT" length="0" sql="INT" quote=""/>
    <type label="BIGINT" length="0" sql="BIGINT" quote=""/>
    <type label="Decimal" length="1" sql="DECIMAL" re="DEC" quote=""/>
    <type label="Single precision" length="0" sql="FLOAT" quote=""/>
    <type label="Double precision" length="0" sql="DOUBLE" re="DOUBLE" quote=""/>
  </group>
  <group label="Character" color="rgb(255,200,200)">
    <type label="Char" length="1" sql="CHAR" quote="'"/>
    <type label="Varchar" length="1" sql="VARCHAR" quote="'"/>
    <type label="Text" length="0" sql="MEDIUMTEXT" re="TEXT" quote="'"/>
    <type label="Binary" length="1" sql="BINARY" quote="'"/>
    <type label="Varbinary" length="1" sql="VARBINARY" quote="'"/>
    <type label="BLOB" length="0" sql="BLOB" re="BLOB" quote="'"/>
  </group>
  <group label="Date &amp; Time" color="rgb(200,255,200)">
    <type label="Date" length="0" sql="DATE" quote="'"/>
    <type label="Time" length="0" sql="TIME" quote="'"/>
    <type label="Datetime" length="0" sql="DATETIME" quote="'"/>
    <type label="Year" length="0" sql="YEAR" quote=""/>
    <type label="Timestamp" length="0" sql="TIMESTAMP" quote="'"/>
  </group>
  <group label="Miscellaneous" color="rgb(200,200,255)">
    <type label="ENUM" length="1" sql="ENUM" quote=""/>
    <type label="SET" length="1" sql="SET" quote=""/>
    <type label="Bit" length="0" sql="bit" quote=""/>
  </group>
</datatypes><table x="577" y="214" name="hvb_admin_users">
<row name="admin_user_id" null="0" autoincrement="1">
<datatype>INTEGER</datatype>
</row>
<row name="admin_user_username" null="0" autoincrement="0">
<datatype>VARCHAR(255)</datatype>
</row>
<row name="admin_user_password" null="0" autoincrement="0">
<datatype>VARCHAR(255)</datatype>
</row>
<row name="admin_user_emailaddress" null="1" autoincrement="0">
<datatype>VARCHAR(255)</datatype>
<default>NULL</default></row>
<key type="PRIMARY" name="">
<part>admin_user_id</part>
</key>
</table>
<table x="975" y="250" name="hvb_admin_privileges">
<row name="admin_privilege_id" null="0" autoincrement="1">
<datatype>INTEGER</datatype>
</row>
<row name="admin_privilege_name" null="0" autoincrement="0">
<datatype>VARCHAR(255)</datatype>
</row>
<row name="admin_users__admin_user_id" null="0" autoincrement="0">
<datatype>INTEGER</datatype>
<relation table="hvb_admin_users" row="admin_user_id" />
</row>
<key type="PRIMARY" name="">
<part>admin_privilege_id</part>
</key>
</table>
</sql>
