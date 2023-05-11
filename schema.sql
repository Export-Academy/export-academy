DROP DATABASE IF EXISTS `export-academy`;
CREATE DATABASE `export-academy` DEFAULT CHARACTER SET = 'utf8mb4';
USE `export-academy`;
CREATE TABLE `user_type` (
  `id` int PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `description` text
);
CREATE TABLE `user` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `email` varchar(255) UNIQUE NOT NULL,
  `password` text NOT NULL,
  `token` text,
  `verified` boolean DEFAULT false,
  `email_verified` boolean DEFAULT false,
  `disabled` boolean DEFAULT false,
  `requires_verification` boolean DEFAULT false,
  `type_id` int,
  `last_logged_in` timestamp,
  `created_at` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `updated_at` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP)
);
CREATE TABLE `permission` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
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
  PRIMARY KEY (`role_id`, `permission_id`)
);
CREATE TABLE `restriction` (
  `user_id` int,
  `permission_id` int NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`, `user_id`)
);
CREATE TABLE `user_role` (
  `role_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`role_id`, `user_id`)
);
CREATE TABLE `answer` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `content` blob NOT NULL
);
CREATE TABLE `response` (
  `user_id` int NOT NULL,
  `question_id` int NOT NULL,
  `content` json NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  `updated_at` timestamp NOT NULL DEFAULT (CURRENT_TIMESTAMP),
  PRIMARY KEY (`user_id`, `question_id`)
);
CREATE TABLE `question_type` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` text UNIQUE NOT NULL,
  `handler` text NOT NULL
);
CREATE TABLE `context` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text
);
CREATE TABLE `question` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `prompt` text NOT NULL,
  `content` json NOT NULL,
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
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `handler` text UNIQUE NOT NULL
);
CREATE TABLE `resource` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text,
  `content` json NOT NULL,
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
CREATE TABLE `File` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `bucket` varchar(100) NOT NULL
);
CREATE INDEX `user_type_index_0` ON `user_type` (`name`);
CREATE INDEX `user_index_1` ON `user` (`firstName`);
CREATE INDEX `user_index_2` ON `user` (`lastName`);
CREATE INDEX `user_index_3` ON `user` (`email`);
CREATE INDEX `user_index_4` ON `user` (`verified`);
CREATE INDEX `user_index_5` ON `user` (`email_verified`);
CREATE INDEX `user_index_6` ON `user` (`requires_verification`);
CREATE INDEX `user_index_7` ON `user` (`created_at`);
CREATE INDEX `permission_index_8` ON `permission` (`name`);
CREATE INDEX `role_index_9` ON `role` (`name`);
CREATE INDEX `grants_index_10` ON `grants` (`role_id`);
CREATE INDEX `grants_index_11` ON `grants` (`permission_id`);
CREATE INDEX `response_index_12` ON `response` (`created_at`);
CREATE INDEX `question_type_index_13` ON `question_type` (`name`);
CREATE INDEX `question_index_14` ON `question` (`type`);
CREATE INDEX `question_index_15` ON `question` (`answer`);
CREATE INDEX `format_index_16` ON `format` (`name`);
CREATE INDEX `resource_index_17` ON `resource` (`title`);
CREATE UNIQUE INDEX `File_index_18` ON `File` (`name`, `bucket`);
CREATE INDEX `File_index_19` ON `File` (`bucket`);
ALTER TABLE
  `user`
ADD
  FOREIGN KEY (`type_id`) REFERENCES `user_type` (`id`);
ALTER TABLE
  `grants`
ADD
  FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE
  `grants`
ADD
  FOREIGN KEY (`permission_id`) REFERENCES `permission` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE
  `restriction`
ADD
  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
ALTER TABLE
  `restriction`
ADD
  FOREIGN KEY (`permission_id`, `role_id`) REFERENCES `grants` (`permission_id`, `role_id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE
  `user_role`
ADD
  FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE
  `user_role`
ADD
  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE
  `response`
ADD
  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
ALTER TABLE
  `response`
ADD
  FOREIGN KEY (`question_id`) REFERENCES `question` (`id`);
ALTER TABLE
  `question`
ADD
  FOREIGN KEY (`type`) REFERENCES `question_type` (`id`);
ALTER TABLE
  `question`
ADD
  FOREIGN KEY (`answer`) REFERENCES `answer` (`id`);
ALTER TABLE
  `question_context`
ADD
  FOREIGN KEY (`question_id`) REFERENCES `question` (`id`);
ALTER TABLE
  `question_context`
ADD
  FOREIGN KEY (`context_id`) REFERENCES `context` (`id`);
ALTER TABLE
  `resource`
ADD
  FOREIGN KEY (`format_id`) REFERENCES `format` (`id`);
ALTER TABLE
  `resource_context`
ADD
  FOREIGN KEY (`resource_id`) REFERENCES `resource` (`id`);
ALTER TABLE
  `resource_context`
ADD
  FOREIGN KEY (`context_id`) REFERENCES `context` (`id`);
ALTER TABLE
  `user_context`
ADD
  FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`id`);
ALTER TABLE
  `user_context`
ADD
  FOREIGN KEY (`context_id`) REFERENCES `context` (`id`);
INSERT INTO
  `permission` (`name`)
VALUES
  ('Create User');
INSERT INTO
  `permission` (`name`)
VALUES
  ('Update User');
INSERT INTO
  `permission` (`name`)
VALUES
  ('Access User Controller');
INSERT INTO
  `permission` (`name`)
VALUES
  ('Create Question');
INSERT INTO
  `permission` (`name`)
VALUES
  ('Update Question');
INSERT INTO
  `permission` (`name`)
VALUES
  ('Access Question Controller');
INSERT INTO
  `permission` (`name`)
VALUES
  ('Access Resource Controller');
INSERT INTO
  `permission` (`name`)
VALUES
  ('Update Resource');
INSERT INTO
  `permission` (`name`)
VALUES
  ('Create Resource');
INSERT INTO
  `permission` (`name`)
VALUES
  ('Create User Role');
INSERT INTO
  `permission` (`name`)
VALUES
  ('Update User Role');
INSERT INTO
  `permission` (`name`)
VALUES
  ('Create Permission');
INSERT INTO
  `permission` (`name`)
VALUES
  ('Update Permission');
INSERT INTO
  `role` (`name`)
VALUES
  ('Administrator');
INSERT INTO
  `role` (`name`)
VALUES
  ('Developer');
INSERT INTO
  `question_type` (`name`, `handler`)
VALUES
  (
    'Multiple Choice',
    'common\\models\\assessment\\MultipleChoice'
  );
INSERT INTO
  `question_type` (`name`, `handler`)
VALUES
  (
    'Dropdown',
    'common\\models\\assessment\\Dropdown'
  );
INSERT INTO
  `question_type` (`name`, `handler`)
VALUES
  (
    'Checkbox',
    'common\\models\\assessment\\Checkboxes'
  );