CREATE DATABASE IF NOT EXISTS demo
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS email(
  id INT AUTO_INCREMENT,
  email VARCHAR(255) UNIQUE NOT NULL,
  PRIMARY KEY (id)
) ENGINE=INNODB;

-- ------------------------------
-- Уточнение в задаче, что домены могут быть одинаковыми для адресов и их много навеяло вот такую схему:

CREATE TABLE IF NOT EXISTS email(
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(64) NOT NULL,
  domain_id INT NOT NULL,

  PRIMARY KEY (id),

  INDEX domain_id_idx(domain_id),
  UNIQUE INDEX email_unq_idx(username, domain_id),

  FOREIGN KEY (domain_id) REFERENCES domain(id) ON DELETE CASCADE
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS domain(
  id INT AUTO_INCREMENT PRIMARY KEY,
  domain VARCHAR(255) UNIQUE NOT NULL
) ENGINE=INNODB;

-- Чего я НЕ сообразил - как это можно использовать? Если только для более удобного сбора каких-то данных, например, сколько у нас
-- адресов принадлежат такому-то домену ...
