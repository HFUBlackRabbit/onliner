CREATE TABLE IF NOT EXISTS `users`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `login` VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
    `password` CHAR(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    PRIMARY KEY(`id`),
    UNIQUE KEY `login`(`login`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `codes`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `value` CHAR(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `received_at` DATETIME DEFAULT NULL,
    PRIMARY KEY(`id`),
    UNIQUE KEY `code`(`value`),
    UNIQUE KEY `user_id`(`user_id`) USING BTREE,
    CONSTRAINT `codes_users_id_fk` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
