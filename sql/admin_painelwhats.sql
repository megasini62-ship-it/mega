-- --------------------------------------------------------
-- Banco de dados: admin_painelwhats
-- Gerenciador Atlas - Estrutura do Banco de Dados
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Criar banco de dados
DROP DATABASE IF EXISTS `admin_painelwhats`;
CREATE DATABASE IF NOT EXISTS `admin_painelwhats` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `admin_painelwhats`;

-- --------------------------------------------------------
-- Tabela: contas (usuarios do painel)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `contas`;
CREATE TABLE IF NOT EXISTS `contas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL COMMENT 'Hash MD5 da senha',
  `admin` varchar(3) NOT NULL DEFAULT 'NAO' COMMENT 'SIM = Revendedor',
  `perm` varchar(3) NOT NULL DEFAULT 'NAO' COMMENT 'SIM = Administrador',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Inserir usuario administrador inicial
-- ATENÇÃO SEGURANÇA: Altere a senha do administrador imediatamente após o primeiro login!
-- Email: admin@admin.com | Senha padrão: admin123 (MD5: 0192023a7bbd73250516f069df18b500)
-- Para gerar novo hash MD5: SELECT MD5('sua_nova_senha');
INSERT INTO `contas` (`name`, `email`, `senha`, `admin`, `perm`) VALUES
('Administrador', 'admin@admin.com', '0192023a7bbd73250516f069df18b500', 'SIM', 'SIM');

-- --------------------------------------------------------
-- Tabela: tokens (tokens de acesso / dominios)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `tokens`;
CREATE TABLE IF NOT EXISTS `tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(500) NOT NULL,
  `dominio` varchar(255) NOT NULL DEFAULT '',
  `vencimento` varchar(50) NOT NULL DEFAULT 'Nunca',
  `dono` int(11) NOT NULL DEFAULT 0 COMMENT 'ID do usuario dono do token',
  `contato` varchar(50) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `idx_dono` (`dono`),
  KEY `idx_dominio` (`dominio`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabela: pagamentos (historico de pagamentos PIX)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `pagamentos`;
CREATE TABLE IF NOT EXISTS `pagamentos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(50) NOT NULL DEFAULT 'Pendente' COMMENT 'Pendente, Aprovado, Cancelado',
  `descricao` text DEFAULT NULL,
  `data_pagamento` datetime NOT NULL DEFAULT current_timestamp(),
  `email_comprador` varchar(255) NOT NULL DEFAULT '',
  `qr_code_copia` text DEFAULT NULL COMMENT 'PIX copia e cola',
  `qr_code_base64` text DEFAULT NULL COMMENT 'QR Code em base64',
  `tipo` varchar(50) DEFAULT NULL COMMENT 'novo ou renovar',
  `token` varchar(500) DEFAULT NULL,
  `dominio` varchar(255) DEFAULT NULL,
  `renovar` varchar(10) DEFAULT NULL,
  `idpayment` varchar(255) DEFAULT NULL COMMENT 'ID do pagamento no MercadoPago',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_email` (`email_comprador`),
  KEY `idx_idpayment` (`idpayment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
