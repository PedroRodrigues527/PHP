-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16-Jan-2022 às 19:11
-- Versão do servidor: 10.4.21-MariaDB
-- versão do PHP: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `er_db`
--
CREATE DATABASE IF NOT EXISTS `er_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `er_db`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `bicycle`
--

DROP TABLE IF EXISTS `bicycle`;
CREATE TABLE IF NOT EXISTS `bicycle` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `state` tinyint(4) NOT NULL,
  `location` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `bicycle`
--

INSERT INTO `bicycle` (`id`, `state`, `location`) VALUES
(1, 1, 'Edifício 2000'),
(2, 1, 'Mercado dos Lavradores'),
(3, 1, 'Universidade Madeira'),
(4, 1, 'Avenida do Mar'),
(5, 1, 'Edifício 2000'),
(6, 1, 'Mercado dos Lavradores'),
(7, 1, 'Universidade Madeira'),
(8, 1, 'Avenida do Mar'),
(9, 1, 'Edifício 2000'),
(10, 1, 'Mercado dos Lavradores'),
(11, 1, 'Universidade Madeira'),
(12, 1, 'Avenida do Mar'),
(13, 1, 'Edifício 2000'),
(14, 1, 'Mercado dos Lavradores'),
(15, 1, 'Universidade Madeira'),
(16, 1, 'Avenida do Mar'),
(17, 0, 'Edifício 2000'),
(18, 0, 'Mercado dos Lavradores'),
(19, 0, 'Universidade Madeira'),
(20, 0, 'Avenida do Mar');

-- --------------------------------------------------------

--
-- Estrutura da tabela `credit_card`
--

DROP TABLE IF EXISTS `credit_card`;
CREATE TABLE IF NOT EXISTS `credit_card` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `number` bigint(20) NOT NULL,
  `month_val` tinyint(4) NOT NULL,
  `year_val` year(4) NOT NULL,
  `PIN` smallint(6) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `saldo` decimal(20,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_credit_card_user_idx` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `credit_card`
--

INSERT INTO `credit_card` (`id`, `number`, `month_val`, `year_val`, `PIN`, `user_id`, `saldo`) VALUES
(1, 1, 11, 2032, 1234, 1, '900.01'),
(2, 2, 10, 2026, 1234, 3, '999.99'),
(3, 3, 9, 2028, 1234, 2, '853.09'),
(4, 4, 11, 2030, 1234, 1, '1.00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `reserve`
--

DROP TABLE IF EXISTS `reserve`;
CREATE TABLE IF NOT EXISTS `reserve` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `inicial_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `bicycle_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_reserve_user1_idx` (`user_id`),
  KEY `fk_reserve_bicycle1_idx` (`bicycle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `reserve`
--

INSERT INTO `reserve` (`id`, `inicial_date`, `end_date`, `user_id`, `bicycle_id`) VALUES
(3, '2022-01-27 14:01:00', '2022-01-27 00:00:00', 2, 9),
(4, '2022-01-29 12:12:00', '2022-01-29 00:00:00', 2, 11),
(5, '2022-01-29 12:13:00', '2022-01-29 00:00:00', 2, 1),
(6, '2022-01-31 12:29:00', '2022-01-31 13:29:00', 2, 3),
(7, '2022-01-28 15:06:00', '2022-01-28 16:06:00', 4, 1),
(10, '2022-01-16 18:19:00', '2022-01-16 19:19:00', 1, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `is_pro` tinyint(4) NOT NULL,
  `data_pro` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `is_pro`, `data_pro`) VALUES
(1, 'testej', 'password', 'email@gmail.com', 0, NULL),
(2, 'abacate', '1234', 'email2@gmail.com', 0, NULL),
(3, 'diogo', '123', 'email3@gmail.com', 0, NULL),
(4, 'subsc', 'pass', 'email7@gmail.com', 1, '2023-01-15'),
(10, 'outrouser', 'pass', 'outroemail@hotmail.com', 0, NULL),
(11, 'userrr', 'dasd', 'asdas@gmail.com', 0, NULL);

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `credit_card`
--
ALTER TABLE `credit_card`
  ADD CONSTRAINT `fk_credit_card_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `reserve`
--
ALTER TABLE `reserve`
  ADD CONSTRAINT `fk_reserve_bicycle1` FOREIGN KEY (`bicycle_id`) REFERENCES `bicycle` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_reserve_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
