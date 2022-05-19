/*
 Navicat Premium Data Transfer

 Source Server         : expertsystem-mysql
 Source Server Type    : MySQL
 Source Server Version : 50722
 Source Host           : 172.25.0.1:3306
 Source Schema         : expertsystem_db

 Target Server Type    : MySQL
 Target Server Version : 50722
 File Encoding         : 65001

 Date: 20/05/2022 06:17:25
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for author
-- ----------------------------
DROP TABLE IF EXISTS `author`;
CREATE TABLE `author` (
  `id_author` int(11) NOT NULL,
  `author_img_url` varchar(255) CHARACTER SET latin1 NOT NULL,
  `author_name` varchar(60) NOT NULL,
  `author_alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  `author_affiliation` varchar(255) CHARACTER SET latin1 NOT NULL,
  `author_field` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`id_author`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of author
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for data_hasil_similiarity
-- ----------------------------
DROP TABLE IF EXISTS `data_hasil_similiarity`;
CREATE TABLE `data_hasil_similiarity` (
  `id_data_hasil_similiarity` int(255) NOT NULL AUTO_INCREMENT,
  `bidang_ilmu` varchar(255) DEFAULT NULL,
  `jumlah` int(255) DEFAULT NULL,
  `persentase` int(255) DEFAULT NULL,
  `id_author` int(255) DEFAULT NULL,
  PRIMARY KEY (`id_data_hasil_similiarity`),
  KEY `data_hasil_similiarity_id_author_fk` (`id_author`),
  CONSTRAINT `data_hasil_similiarity_id_author_fk` FOREIGN KEY (`id_author`) REFERENCES `author` (`id_author`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of data_hasil_similiarity
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for jenis_media_publikasi
-- ----------------------------
DROP TABLE IF EXISTS `jenis_media_publikasi`;
CREATE TABLE `jenis_media_publikasi` (
  `id_jenis_media_publikasi` int(255) NOT NULL AUTO_INCREMENT,
  `media` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id_jenis_media_publikasi`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of jenis_media_publikasi
-- ----------------------------
BEGIN;
INSERT INTO `jenis_media_publikasi` VALUES (1, 'Scopus');
INSERT INTO `jenis_media_publikasi` VALUES (2, 'Google Schoolarship');
INSERT INTO `jenis_media_publikasi` VALUES (3, 'Sinta');
INSERT INTO `jenis_media_publikasi` VALUES (4, 'Web Of Science');
COMMIT;

-- ----------------------------
-- Table structure for jumlah_publikasi_tahun
-- ----------------------------
DROP TABLE IF EXISTS `jumlah_publikasi_tahun`;
CREATE TABLE `jumlah_publikasi_tahun` (
  `id_publikasi_tahun` int(255) NOT NULL AUTO_INCREMENT,
  `tahun` year(4) NOT NULL,
  `jumlah` int(255) NOT NULL,
  `id_author` int(255) NOT NULL,
  PRIMARY KEY (`id_publikasi_tahun`),
  KEY `jumlah_publikasi_id_author_fk` (`id_author`),
  CONSTRAINT `jumlah_publikasi_id_author_fk` FOREIGN KEY (`id_author`) REFERENCES `author` (`id_author`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of jumlah_publikasi_tahun
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for kamus_kata
-- ----------------------------
DROP TABLE IF EXISTS `kamus_kata`;
CREATE TABLE `kamus_kata` (
  `id_kamus_kata` int(255) NOT NULL AUTO_INCREMENT,
  `kata` varchar(60) NOT NULL,
  `id_kelompok_bidang` int(255) NOT NULL,
  PRIMARY KEY (`id_kamus_kata`),
  KEY `kamus_kata_id_kelompok_bidang_fk` (`id_kelompok_bidang`),
  CONSTRAINT `kamus_kata_id_kelompok_bidang_fk` FOREIGN KEY (`id_kelompok_bidang`) REFERENCES `kelompok_bidang` (`id_kelompok_bidang`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of kamus_kata
-- ----------------------------
BEGIN;
INSERT INTO `kamus_kata` VALUES (1, 'proxy', 18);
INSERT INTO `kamus_kata` VALUES (2, 'firewall', 18);
INSERT INTO `kamus_kata` VALUES (3, 'protokol', 18);
INSERT INTO `kamus_kata` VALUES (4, 'ip', 18);
INSERT INTO `kamus_kata` VALUES (5, 'blok', 18);
INSERT INTO `kamus_kata` VALUES (6, 'mangle', 18);
COMMIT;

-- ----------------------------
-- Table structure for kelompok_bidang
-- ----------------------------
DROP TABLE IF EXISTS `kelompok_bidang`;
CREATE TABLE `kelompok_bidang` (
  `id_kelompok_bidang` int(255) NOT NULL AUTO_INCREMENT,
  `kelompok` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id_kelompok_bidang`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of kelompok_bidang
-- ----------------------------
BEGIN;
INSERT INTO `kelompok_bidang` VALUES (18, 'Keamanan Jaringan');
INSERT INTO `kelompok_bidang` VALUES (19, 'Algoritma dan Pemrograman');
INSERT INTO `kelompok_bidang` VALUES (20, 'Desain Grafis');
INSERT INTO `kelompok_bidang` VALUES (21, 'Kecerdasan Buatan');
INSERT INTO `kelompok_bidang` VALUES (22, 'Mikrokontroller');
INSERT INTO `kelompok_bidang` VALUES (23, 'Sistem Operasi');
COMMIT;

-- ----------------------------
-- Table structure for kualitas_media
-- ----------------------------
DROP TABLE IF EXISTS `kualitas_media`;
CREATE TABLE `kualitas_media` (
  `id_kualitas_media` int(255) NOT NULL AUTO_INCREMENT,
  `id_author` int(255) NOT NULL,
  `sinta` float(255,4) NOT NULL,
  `scopus` float(255,4) NOT NULL,
  `confer_article` float(255,4) NOT NULL,
  `total` float(255,4) NOT NULL,
  PRIMARY KEY (`id_kualitas_media`),
  KEY `kualitas_media_id_author_fk` (`id_author`),
  CONSTRAINT `kualitas_media_id_author_fk` FOREIGN KEY (`id_author`) REFERENCES `author` (`id_author`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of kualitas_media
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for posisi_penulis
-- ----------------------------
DROP TABLE IF EXISTS `posisi_penulis`;
CREATE TABLE `posisi_penulis` (
  `id_posisi_penulis` int(255) NOT NULL AUTO_INCREMENT,
  `id_author` int(255) NOT NULL,
  `score` float(255,2) NOT NULL,
  PRIMARY KEY (`id_posisi_penulis`),
  KEY `posisi_penulis_id_author_fk` (`id_author`),
  CONSTRAINT `posisi_penulis_id_author_fk` FOREIGN KEY (`id_author`) REFERENCES `author` (`id_author`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of posisi_penulis
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for publikasi
-- ----------------------------
DROP TABLE IF EXISTS `publikasi`;
CREATE TABLE `publikasi` (
  `id_publikasi` int(255) NOT NULL AUTO_INCREMENT,
  `id_author` int(255) NOT NULL,
  `id_jenis_media_publikasi` int(255) NOT NULL,
  `judul` varchar(255) CHARACTER SET latin1 NOT NULL,
  `posisi_penulis` smallint(6) DEFAULT NULL,
  `total_penulis` smallint(255) DEFAULT NULL,
  `tahun_publikasi` year(4) DEFAULT NULL,
  PRIMARY KEY (`id_publikasi`),
  KEY `publikasi_id_media_publikasi_fk` (`id_jenis_media_publikasi`),
  KEY `publikasi_id_author` (`id_author`),
  CONSTRAINT `publikasi_id_author` FOREIGN KEY (`id_author`) REFERENCES `author` (`id_author`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `publikasi_id_media_publikasi_fk` FOREIGN KEY (`id_jenis_media_publikasi`) REFERENCES `jenis_media_publikasi` (`id_jenis_media_publikasi`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of publikasi
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for scopus
-- ----------------------------
DROP TABLE IF EXISTS `scopus`;
CREATE TABLE `scopus` (
  `scopus_id` int(255) NOT NULL AUTO_INCREMENT,
  `author_id` int(255) NOT NULL,
  `q1` int(255) NOT NULL,
  `q2` int(255) NOT NULL,
  `q3` int(255) NOT NULL,
  `q4` int(255) NOT NULL,
  `undefined` int(255) NOT NULL,
  `article` int(255) NOT NULL,
  `conference` int(255) NOT NULL,
  PRIMARY KEY (`scopus_id`) USING BTREE,
  KEY `scopus_id_author_fk` (`author_id`),
  CONSTRAINT `scopus_id_author_fk` FOREIGN KEY (`author_id`) REFERENCES `author` (`id_author`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of scopus
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for sinta
-- ----------------------------
DROP TABLE IF EXISTS `sinta`;
CREATE TABLE `sinta` (
  `sinta_id` int(255) NOT NULL AUTO_INCREMENT,
  `author_id` int(255) NOT NULL,
  `s1` int(255) NOT NULL,
  `s2` int(255) NOT NULL,
  `s3` int(255) NOT NULL,
  `s4` int(255) NOT NULL,
  `s5` int(255) NOT NULL,
  `s6` int(255) NOT NULL,
  `uncategorized` int(255) NOT NULL,
  PRIMARY KEY (`sinta_id`) USING BTREE,
  KEY `sinta_id_authors_fk` (`author_id`),
  CONSTRAINT `sinta_id_authors_fk` FOREIGN KEY (`author_id`) REFERENCES `author` (`id_author`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of sinta
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for sitasi
-- ----------------------------
DROP TABLE IF EXISTS `sitasi`;
CREATE TABLE `sitasi` (
  `id_sitasi` int(255) NOT NULL AUTO_INCREMENT,
  `id_author` int(255) NOT NULL,
  `id_jenis_media_publikasi` int(255) NOT NULL,
  `data_sitasi` int(255) NOT NULL,
  PRIMARY KEY (`id_sitasi`),
  KEY `id_author_fk` (`id_author`),
  KEY `id_jenis_media_publikasi_fk` (`id_jenis_media_publikasi`),
  CONSTRAINT `id_author_fk` FOREIGN KEY (`id_author`) REFERENCES `author` (`id_author`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id_jenis_media_publikasi_fk` FOREIGN KEY (`id_jenis_media_publikasi`) REFERENCES `jenis_media_publikasi` (`id_jenis_media_publikasi`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of sitasi
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of user
-- ----------------------------
BEGIN;
INSERT INTO `user` VALUES (3, 'admin', '$2y$10$NiTbemdV2QZZJiTAKxrrqeKXpfa5WVewVTlu.fCUAssM2/xbvAaxO', '2022-04-17 16:28:34');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
