/*
 Navicat Premium Data Transfer

 Source Server         : @mysql localhost
 Source Server Type    : MySQL
 Source Server Version : 80031 (8.0.31)
 Source Host           : localhost:3306
 Source Schema         : edupustaka

 Target Server Type    : MySQL
 Target Server Version : 80031 (8.0.31)
 File Encoding         : 65001

 Date: 12/08/2023 11:27:59
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ref_agama
-- ----------------------------
DROP TABLE IF EXISTS `ref_agama`;
CREATE TABLE `ref_agama`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `updated_by` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `deleted_by` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 100 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ref_agama
-- ----------------------------
INSERT INTO `ref_agama` VALUES (1, 'Islam', NULL, NULL, NULL, '2013-05-13 00:00:00', '2014-10-17 16:18:02', NULL);
INSERT INTO `ref_agama` VALUES (2, 'Kristen', NULL, NULL, NULL, '2013-05-13 00:00:00', '2014-10-17 16:18:02', NULL);
INSERT INTO `ref_agama` VALUES (3, 'Katholik', NULL, NULL, NULL, '2013-05-13 00:00:00', '2014-10-17 16:18:02', NULL);
INSERT INTO `ref_agama` VALUES (4, 'Hindu', NULL, NULL, NULL, '2013-05-13 00:00:00', '2014-10-17 16:18:02', NULL);
INSERT INTO `ref_agama` VALUES (5, 'Budha', NULL, NULL, NULL, '2013-05-13 00:00:00', '2014-10-17 16:18:02', NULL);
INSERT INTO `ref_agama` VALUES (6, 'Kong Hu Chu', NULL, NULL, NULL, '2013-05-13 00:00:00', '2016-08-01 17:00:00', NULL);
INSERT INTO `ref_agama` VALUES (99, 'Lainnya', NULL, NULL, NULL, '2013-07-25 00:00:00', '2014-10-17 16:18:02', NULL);

-- ----------------------------
-- Table structure for ref_jenjang_pendidikan
-- ----------------------------
DROP TABLE IF EXISTS `ref_jenjang_pendidikan`;
CREATE TABLE `ref_jenjang_pendidikan`  (
  `id` int NOT NULL,
  `nama` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lembaga` int NULL DEFAULT NULL,
  `orang` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ref_jenjang_pendidikan
-- ----------------------------
INSERT INTO `ref_jenjang_pendidikan` VALUES (0, 'Tidak sekolah', 0, 1, '2013-05-13 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (1, 'PAUD', 1, 0, '2013-05-13 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (2, 'TK / sederajat', 1, 0, '2013-05-13 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (3, 'Putus SD', 0, 1, '2013-05-13 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (4, 'SD / sederajat', 1, 1, '2013-05-13 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (5, 'SMP / sederajat', 1, 1, '2013-05-13 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (6, 'SMA / sederajat', 1, 1, '2013-05-13 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (7, 'Paket A', 1, 0, '2013-05-13 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (8, 'Paket B', 1, 0, '2013-05-13 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (9, 'Paket C', 1, 0, '2013-05-13 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (20, 'D1', 1, 1, '2013-05-13 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (21, 'D2', 1, 1, '2013-05-13 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (22, 'D3', 1, 1, '2013-05-14 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (23, 'D4', 1, 1, '2013-05-14 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (30, 'S1', 1, 1, '2013-05-14 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (35, 'S2', 1, 1, '2013-05-14 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (40, 'S3', 1, 1, '2013-05-14 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (90, 'Non formal', 1, 0, '2013-05-14 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (91, 'Informal', 1, 0, '2013-05-14 00:00:00', '2016-07-22 06:00:00', NULL);
INSERT INTO `ref_jenjang_pendidikan` VALUES (98, '(tidak diisi)', 0, 0, '2013-05-25 00:00:00', '2016-07-22 06:00:00', '2013-05-25 00:00:00');
INSERT INTO `ref_jenjang_pendidikan` VALUES (99, 'Lainnya', 1, 0, '2013-05-14 00:00:00', '2021-01-16 15:04:15', '2021-01-16 15:04:03');

-- ----------------------------
-- Table structure for ref_tingkat_kelas
-- ----------------------------
DROP TABLE IF EXISTS `ref_tingkat_kelas`;
CREATE TABLE `ref_tingkat_kelas`  (
  `id` int NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `kode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `sd` int NULL DEFAULT NULL,
  `smp` int NULL DEFAULT NULL,
  `sma` int NULL DEFAULT NULL,
  `smk` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ref_tingkat_kelas
-- ----------------------------
INSERT INTO `ref_tingkat_kelas` VALUES (1, 'Kelas I', 'I', 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `ref_tingkat_kelas` VALUES (2, 'Kelas II', 'II', 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `ref_tingkat_kelas` VALUES (3, 'Kelas III', 'III', 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `ref_tingkat_kelas` VALUES (4, 'Kelas IV', 'IV', 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `ref_tingkat_kelas` VALUES (5, 'Kelas V', 'V', 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `ref_tingkat_kelas` VALUES (6, 'Kelas VI', 'VI', 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `ref_tingkat_kelas` VALUES (7, 'Kelas VII', 'VII', NULL, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `ref_tingkat_kelas` VALUES (8, 'Kelas VIII', 'VIII', NULL, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `ref_tingkat_kelas` VALUES (9, 'Kelas IX', 'IX', NULL, 1, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `ref_tingkat_kelas` VALUES (10, 'Kelas X', 'X', NULL, NULL, 1, 1, NULL, NULL, NULL);
INSERT INTO `ref_tingkat_kelas` VALUES (11, 'Kelas XI', 'XI', NULL, NULL, 1, 1, NULL, NULL, NULL);
INSERT INTO `ref_tingkat_kelas` VALUES (12, 'Kelas XII', 'XII', NULL, NULL, 1, 1, NULL, NULL, NULL);
INSERT INTO `ref_tingkat_kelas` VALUES (13, 'Kelas XIII', 'XIII', NULL, NULL, NULL, 1, NULL, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
