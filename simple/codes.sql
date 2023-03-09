CREATE TABLE IF NOT EXISTS `codes`
(
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `value`       CHAR(10) NOT NULL,
    `user_uuid`   CHAR(23) NULL,
    `received_at` DATETIME NULL,
    CONSTRAINT `code` UNIQUE (`value`),
    CONSTRAINT `user_uuid` UNIQUE (`user_uuid`)
);