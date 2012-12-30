DROP TABLE IF EXISTS `test_table`;
CREATE TABLE `test_table` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `col1` int(11) DEFAULT NULL,
  `col2` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
