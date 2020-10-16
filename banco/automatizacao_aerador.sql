-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 14-Out-2020 às 23:19
-- Versão do servidor: 8.0.21-0ubuntu0.20.04.4
-- versão do PHP: 7.0.33-32+ubuntu20.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `automatizacao_aerador`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `aerador`
--

CREATE TABLE `aerador` (
  `idAerador` int NOT NULL,
  `dataHoraInicio` timestamp NULL DEFAULT NULL,
  `dataHoraFim` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `aerador`
--

INSERT INTO `aerador` (`idAerador`, `dataHoraInicio`, `dataHoraFim`) VALUES
(9, '2020-10-06 01:44:11', '2020-10-06 01:49:06'),
(10, '2020-10-06 02:09:16', '2020-10-06 03:11:58'),
(11, '2020-10-06 23:49:31', '2020-10-07 02:50:00'),
(12, '2020-10-06 23:49:31', '2020-10-07 01:39:35');

-- --------------------------------------------------------

--
-- Estrutura da tabela `calibracao_sonda_od`
--

CREATE TABLE `calibracao_sonda_od` (
  `idCalibracao` int NOT NULL,
  `valorCalibrado` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `calibracao_sonda_od`
--

INSERT INTO `calibracao_sonda_od` (`idCalibracao`, `valorCalibrado`) VALUES
(10, 281.27);

-- --------------------------------------------------------

--
-- Estrutura da tabela `dado_obtido_sensor`
--

CREATE TABLE `dado_obtido_sensor` (
  `idDadoObtidoSensor` int NOT NULL,
  `dataHora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `valorObtidoSonda` float NOT NULL,
  `idSensor` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `dado_obtido_sensor`
--

INSERT INTO `dado_obtido_sensor` (`idDadoObtidoSensor`, `valorObtidoSonda`, `idSensor`) VALUES
(253, 4.43, 1),
(254, 5.3646, 2),
(255, 25, 3),
(259, 8.43, 1),
(260, 5.4159, 2),
(261, 25, 3),
(262, 4.63, 1),
(263, 5.6097, 2),
(264, 25, 3),
(265, 5.19, 1),
(266, 5.9004, 2),
(267, 24, 3),
(268, 8.71, 1),
(269, 5.1423, 2),
(270, 23.5, 3),
(271, 5.03, 1),
(272, 1.6653, 2),
(273, 27, 3),
(274, 4.42, 1),
(275, 1.7679, 2),
(276, 27, 3),
(277, 4.13, 1),
(278, 1.6824, 2),
(279, 27, 3),
(280, 6.23, 1),
(281, 5.4273, 2),
(282, 26.5, 3),
(283, 6.35, 1),
(284, 5.3532, 2),
(285, 26.5, 3),
(286, 6.95, 1),
(287, 4.9086, 2),
(288, 26, 3),
(289, 6.73, 1),
(290, 5.6952, 2),
(291, 25.5, 3),
(292, 6.56, 1),
(293, 6.1626, 2),
(294, 25, 3),
(295, 6.37, 1),
(296, 6.0885, 2),
(297, 25, 3),
(298, 6.35, 1),
(299, 6.2709, 2),
(300, 24.5, 3),
(301, 6.36, 1),
(302, 6.7611, 2),
(303, 24, 3),
(304, 6.45, 1),
(305, 6.6813, 2),
(306, 24, 3),
(307, 6.26, 1),
(308, 6.9207, 2),
(309, 24, 3),
(310, 6.36, 1),
(311, 6.117, 2),
(312, 24, 3),
(313, 6.3, 1),
(314, 5.5926, 2),
(315, 24, 3),
(316, 6.54, 1),
(317, 5.4786, 2),
(318, 23.5, 3),
(319, 6.39, 1),
(320, 5.5071, 2),
(321, 23.5, 3),
(322, 6.43, 1),
(323, 5.0226, 2),
(324, 23.5, 3),
(325, 6.7, 1),
(326, 5.1366, 2),
(327, 23, 3),
(328, 7.4, 1),
(329, 4.8687, 2),
(330, 23, 3),
(331, 7.89, 1),
(332, 4.7946, 2),
(333, 23, 3),
(334, 7.42, 1),
(335, 4.8516, 2),
(336, 23, 3),
(337, 7.53, 1),
(338, 4.8117, 2),
(339, 22.5, 3),
(340, 7.36, 1),
(341, 4.8402, 2),
(342, 22.5, 3),
(343, 7.54, 1),
(344, 4.8231, 2),
(345, 22.5, 3),
(346, 7.38, 1),
(347, 4.7946, 2),
(348, 22.5, 3),
(349, 7.09, 1),
(350, 4.8459, 2),
(351, 22.5, 3),
(352, 7.22, 1),
(353, 4.806, 2),
(354, 22.5, 3),
(355, 7.18, 1),
(356, 4.8003, 2),
(357, 22, 3),
(358, 6.86, 1),
(359, 4.1106, 2),
(360, 25, 3),
(361, 6.51, 1),
(362, 3.5292, 2),
(363, 25, 3),
(364, 7.32, 1),
(365, 3.3126, 2),
(366, 24.5, 3),
(367, 6.98, 1),
(368, 3.3525, 2),
(369, 24.5, 3),
(370, 7.46, 1),
(371, 3.2499, 2),
(372, 24.5, 3),
(373, 7.18, 1),
(374, 3.3525, 2),
(375, 24.5, 3),
(376, 7.28, 1),
(377, 3.3183, 2),
(378, 24, 3),
(379, 6.92, 1),
(380, 3.3411, 2),
(381, 24, 3),
(382, 7.5, 1),
(383, 3.3924, 2),
(384, 24, 3),
(385, 6.75, 1),
(386, 3.3696, 2),
(387, 24, 3),
(388, 7.27, 1),
(389, 3.4836, 2),
(390, 24, 3),
(391, 7.22, 1),
(392, 3.5862, 2),
(393, 23.5, 3),
(394, 14.22, 1),
(395, 10.2666, 2),
(396, 20.5, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `sensor`
--

CREATE TABLE `sensor` (
  `idSensor` int NOT NULL,
  `descricaoSensor` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `sensor`
--

INSERT INTO `sensor` (`idSensor`, `descricaoSensor`) VALUES
(1, 'Oxigenio'),
(2, 'pH'),
(3, 'Temperatura');

-- --------------------------------------------------------

--
-- Estrutura da tabela `temperatura_saturacao`
--

CREATE TABLE `temperatura_saturacao` (
  `idTemperaturaSaturacao` int NOT NULL,
  `temperatura` float NOT NULL,
  `saturacao_oxigenio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `temperatura_saturacao`
--

INSERT INTO `temperatura_saturacao` (`idTemperaturaSaturacao`, `temperatura`, `saturacao_oxigenio`) VALUES
(1, 4, 13.12),
(2, 4.5, 12.96),
(3, 5, 12.81),
(4, 5.5, 12.66),
(5, 6, 12.51),
(6, 6.5, 12.37),
(7, 7, 12.22),
(8, 7.5, 12.08),
(9, 8, 11.94),
(10, 8.5, 11.8),
(11, 9, 11.66),
(12, 9.5, 11.52),
(13, 10, 11.39),
(14, 10.5, 11.26),
(15, 11, 11.13),
(16, 11.5, 11),
(17, 12, 10.87),
(18, 12.5, 10.74),
(19, 13, 10.62),
(20, 13.5, 10.5),
(21, 14, 10.38),
(22, 14.5, 10.26),
(23, 15, 10.14),
(24, 15.5, 10.03),
(25, 16, 9.91),
(26, 16.5, 9.8),
(27, 17, 9.69),
(28, 17.5, 9.58),
(29, 18, 9.48),
(30, 18.5, 9.37),
(31, 19, 9.27),
(32, 19.5, 9.17),
(33, 20, 9.07),
(34, 20.5, 8.97),
(35, 21, 8.88),
(36, 21.5, 8.78),
(37, 22, 8.69),
(38, 22.5, 8.6),
(39, 23, 8.51),
(40, 23.5, 8.42),
(41, 24, 8.34),
(42, 24.5, 8.25),
(43, 25, 8.17),
(44, 25.5, 8.09),
(45, 26, 8.01),
(46, 26.5, 7.94),
(47, 27, 7.86),
(48, 27.5, 7.79),
(49, 28, 7.72),
(50, 28.5, 7.65),
(51, 29, 7.58),
(52, 29.5, 7.51),
(53, 30, 7.45),
(54, 30.5, 7.39),
(55, 31, 7.33),
(56, 31.5, 7.27),
(57, 32, 7.21),
(58, 32.5, 7.16),
(59, 33, 7.1),
(60, 33.5, 7.05),
(61, 34, 7),
(62, 34.5, 6.95),
(63, 35, 6.9),
(64, 35.5, 6.86),
(65, 36, 6.82),
(66, 36.5, 6.77);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `aerador`
--
ALTER TABLE `aerador`
  ADD PRIMARY KEY (`idAerador`);

--
-- Índices para tabela `calibracao_sonda_od`
--
ALTER TABLE `calibracao_sonda_od`
  ADD PRIMARY KEY (`idCalibracao`);

--
-- Índices para tabela `dado_obtido_sensor`
--
ALTER TABLE `dado_obtido_sensor`
  ADD PRIMARY KEY (`idDadoObtidoSensor`),
  ADD KEY `idSensor_fk` (`idSensor`);

--
-- Índices para tabela `sensor`
--
ALTER TABLE `sensor`
  ADD PRIMARY KEY (`idSensor`);

--
-- Índices para tabela `temperatura_saturacao`
--
ALTER TABLE `temperatura_saturacao`
  ADD PRIMARY KEY (`idTemperaturaSaturacao`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `aerador`
--
ALTER TABLE `aerador`
  MODIFY `idAerador` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `calibracao_sonda_od`
--
ALTER TABLE `calibracao_sonda_od`
  MODIFY `idCalibracao` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `dado_obtido_sensor`
--
ALTER TABLE `dado_obtido_sensor`
  MODIFY `idDadoObtidoSensor` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=397;

--
-- AUTO_INCREMENT de tabela `sensor`
--
ALTER TABLE `sensor`
  MODIFY `idSensor` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `temperatura_saturacao`
--
ALTER TABLE `temperatura_saturacao`
  MODIFY `idTemperaturaSaturacao` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `dado_obtido_sensor`
--
ALTER TABLE `dado_obtido_sensor`
  ADD CONSTRAINT `fk_dado_obtido_sensor_1` FOREIGN KEY (`idSensor`) REFERENCES `sensor` (`idSensor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
