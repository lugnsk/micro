--
-- Table structure for table `blogs`
--

CREATE TABLE IF NOT EXISTS `blogs` (
  `id`      INT(11)      NOT NULL AUTO_INCREMENT,
  `name`    VARCHAR(127) NOT NULL,
  `content` TEXT         NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id`    INT(11)      NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(55)  NOT NULL,
  `login` VARCHAR(55)  NOT NULL,
  `pass`  VARCHAR(32)  NOT NULL,
  `fio`   VARCHAR(127) NOT NULL,
  PRIMARY KEY (`id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
