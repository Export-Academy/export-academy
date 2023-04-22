CREATE TABLE `user_type` (
  `id` int PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `description` text
);

CREATE TABLE `user_meta` (
  `id` int PRIMARY KEY,
  `verified` boolean DEFAULT false,
  `email_verified` boolean DEFAULT false,
  `disabled` boolean DEFAULT false,
  `requires_verification` boolean DEFAULT false
);

CREATE TABLE `user` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `email` varchar(255) UNIQUE NOT NULL,
  `password` text NOT NULL,
  `password_token` text,
  `email_token` text,
  `type_id` int,
  `meta_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `updated_at` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `permission` (
  `id` int PRIMARY KEY,
  `name` varchar(255) UNIQUE NOT NULL,
  `description` text
);

CREATE TABLE `role` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) UNIQUE NOT NULL,
  `description` text
);

CREATE TABLE `grants` (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  `enabled` boolean,
  PRIMARY KEY (`role_id`, `permission_id`)
);

CREATE TABLE `restriction` (
  `user_id` int,
  `permission_id` int NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`, `user_id`)
);

CREATE TABLE `user_role` (
  `role_id` int,
  `user_id` int,
  PRIMARY KEY (`role_id`, `user_id`)
);

CREATE TABLE `answer` (
  `id` int PRIMARY KEY,
  `content` blob NOT NULL
);

CREATE TABLE `response` (
  `user_id` int NOT NULL,
  `question_id` int NOT NULL,
  `content` blob NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `updated_at` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  PRIMARY KEY (`user_id`, `question_id`)
);

CREATE TABLE `question_type` (
  `id` int PRIMARY KEY,
  `name` text UNIQUE NOT NULL,
  `handler` text NOT NULL
);

CREATE TABLE `context` (
  `id` int PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `description` text
);

CREATE TABLE `question` (
  `id` int PRIMARY KEY,
  `prompt` text NOT NULL,
  `content` blob NOT NULL,
  `type` int NOT NULL,
  `answer` int NOT NULL,
  `enabled` boolean DEFAULT false,
  `created_at` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `updated_at` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `question_context` (
  `question_id` int NOT NULL,
  `context_id` int NOT NULL,
  PRIMARY KEY (`question_id`, `context_id`)
);

CREATE TABLE `format` (
  `id` int PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `handler` text UNIQUE NOT NULL
);

CREATE TABLE `resource` (
  `id` int PRIMARY KEY,
  `title` text NOT NULL,
  `description` text,
  `content` blob NOT NULL,
  `enabled` boolean DEFAULT false,
  `format_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `updated_at` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);

CREATE TABLE `resource_context` (
  `resource_id` int NOT NULL,
  `context_id` int NOT NULL,
  PRIMARY KEY (`resource_id`, `context_id`)
);

CREATE TABLE `user_context` (
  `user_type_id` int NOT NULL,
  `context_id` int NOT NULL,
  PRIMARY KEY (`user_type_id`, `context_id`)
);

CREATE INDEX `user_type_index_0` ON `user_type` (`name`);

CREATE INDEX `user_index_1` ON `user` (`firstName`);

CREATE INDEX `user_index_2` ON `user` (`lastName`);

CREATE INDEX `user_index_3` ON `user` (`email`);

CREATE INDEX `user_index_4` ON `user` (`meta_id`);

CREATE INDEX `user_index_5` ON `user` (`created_at`);

CREATE INDEX `permission_index_6` ON `permission` (`name`);

CREATE INDEX `role_index_7` ON `role` (`name`);

CREATE INDEX `grants_index_8` ON `grants` (`role_id`);

CREATE INDEX `grants_index_9` ON `grants` (`permission_id`);

CREATE INDEX `response_index_10` ON `response` (`created_at`);

CREATE INDEX `question_type_index_11` ON `question_type` (`name`);

CREATE INDEX `question_index_12` ON `question` (`type`);

CREATE INDEX `question_index_13` ON `question` (`answer`);

CREATE INDEX `format_index_14` ON `format` (`name`);

CREATE INDEX `resource_index_15` ON `resource` (`title`);

ALTER TABLE `user` ADD FOREIGN KEY (`type_id`) REFERENCES `user_type` (`id`);

ALTER TABLE `user` ADD FOREIGN KEY (`meta_id`) REFERENCES `user_meta` (`id`);

ALTER TABLE `grants` ADD FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

ALTER TABLE `grants` ADD FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`);

ALTER TABLE `restriction` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `restriction` ADD FOREIGN KEY (`permission_id`, `role_id`) REFERENCES `grants` (`permission_id`, `role_id`);

ALTER TABLE `user_role` ADD FOREIGN KEY (`role_id`) REFERENCES `role` (`id`);

ALTER TABLE `user_role` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `response` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `response` ADD FOREIGN KEY (`question_id`) REFERENCES `question` (`id`);

ALTER TABLE `question` ADD FOREIGN KEY (`type`) REFERENCES `question_type` (`id`);

ALTER TABLE `question` ADD FOREIGN KEY (`answer`) REFERENCES `answer` (`id`);

ALTER TABLE `question_context` ADD FOREIGN KEY (`question_id`) REFERENCES `question` (`id`);

ALTER TABLE `question_context` ADD FOREIGN KEY (`context_id`) REFERENCES `context` (`id`);

ALTER TABLE `resource` ADD FOREIGN KEY (`format_id`) REFERENCES `format` (`id`);

ALTER TABLE `resource_context` ADD FOREIGN KEY (`resource_id`) REFERENCES `resource` (`id`);

ALTER TABLE `resource_context` ADD FOREIGN KEY (`context_id`) REFERENCES `context` (`id`);

ALTER TABLE `user_context` ADD FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`id`);

ALTER TABLE `user_context` ADD FOREIGN KEY (`context_id`) REFERENCES `context` (`id`);
