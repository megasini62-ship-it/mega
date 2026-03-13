-- --------------------------------------------------------
-- Sistema de Atualização Remota
-- Tabelas: atualizacoes, atualizacoes_log
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `atualizacoes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `titulo` VARCHAR(255) NOT NULL,
  `versao` VARCHAR(20) NOT NULL,
  `descricao` TEXT,
  `arquivo` VARCHAR(255) NOT NULL,
  `tamanho` INT,
  `hash_md5` VARCHAR(32),
  `prioridade` ENUM('baixa', 'media', 'alta', 'critica') DEFAULT 'media',
  `status` ENUM('ativo', 'inativo') DEFAULT 'ativo',
  `downloads` INT DEFAULT 0,
  `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `criado_por` INT,
  INDEX idx_versao (`versao`),
  INDEX idx_status (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `atualizacoes_log` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `atualizacao_id` INT NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `dominio` VARCHAR(255),
  `status` ENUM('baixado', 'instalado', 'erro') DEFAULT 'baixado',
  `ip` VARCHAR(45),
  `user_agent` TEXT,
  `mensagem_erro` TEXT,
  `data_download` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (atualizacao_id) REFERENCES atualizacoes(id) ON DELETE CASCADE,
  INDEX idx_token (`token`),
  INDEX idx_status (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
