

CREATE DATABASE IF NOT EXISTS winterdb;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";



CREATE TABLE IF NOT EXISTS `penguins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `email` varchar(999) NOT NULL,
  `password` varchar(999) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `ubdate` varchar(255) NOT NULL,
  `items` longblob NOT NULL,
  `stamps` longblob NOT NULL,
  `curhead` varchar(10) NOT NULL DEFAULT '0',
  `curface` varchar(10) NOT NULL DEFAULT '0',
  `curneck` varchar(10) NOT NULL DEFAULT '0',
  `curbody` varchar(10) NOT NULL DEFAULT '0',
  `curhands` varchar(10) NOT NULL DEFAULT '0',
  `curfeet` varchar(10) NOT NULL DEFAULT '0',
  `curphoto` varchar(10) NOT NULL DEFAULT '0',
  `curflag` varchar(10) NOT NULL DEFAULT '0',
  `colour` varchar(10) NOT NULL DEFAULT '1',
  `buddies` longtext NOT NULL,
  `ignore` longtext NOT NULL,
  `joindate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lkey` varchar(50) NOT NULL,
  `coins` int(11) NOT NULL,
  `ismoderator` int(11) NOT NULL,
  `rank` int(11) NOT NULL DEFAULT '1',
  `ips` longtext,
  `igloo` int(11) NOT NULL default '1',
  `floor` int(11) NOT NULL default '0',
  `furniture` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

