SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `colors` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `colors` (`id`, `name`) VALUES
(4, 'black'),
(3, 'blue'),
(2, 'green'),
(1, 'red');

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'register', 'Пользователь, который зарегистрирован на сайте'),
(2, 'writer', 'Пользователь, который может писать сообщения');

CREATE TABLE `group_user` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `group_user` (`group_id`, `user_id`) VALUES
(1, 1),
(2, 1),
(1, 2),
(2, 2),
(1, 3),
(2, 3),
(1, 4),
(2, 4);

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `section_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `readed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `posts` (`id`, `from_user_id`, `to_user_id`, `title`, `text`, `section_id`, `created_at`, `readed_at`) VALUES
(1, 1, 4, 'Проверка первое сообщение', 'Текст сообщения! Проверка связи 123-123-123.', 3, '2020-01-12 19:45:10', NULL),
(2, 1, 4, 'Второе сообщеение', 'Текст второго сообщения. Ляляля', 3, '2020-01-12 19:45:57', '2020-01-13 04:13:09'),
(3, 1, 4, 'Третье послание', 'Проверка текстового сообщения. 123', 7, '2020-01-12 19:46:14', NULL),
(4, 4, 1, 'Ответ на третье послание', 'У меня все ок. Как у тебя дела?', 8, '2020-01-13 08:24:58', '2020-01-14 00:53:17'),
(5, 2, 4, 'For user 4', 'Hello ', 7, '2020-01-14 18:10:24', '2020-01-14 12:10:51');

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `color_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `sections` (`id`, `name`, `parent_id`, `color_id`, `created_by`, `created_at`) VALUES
(1, 'Основные', 0, 1, 1, '2020-01-11 18:27:52'),
(2, 'По работе', 1, 2, 1, '2020-01-11 18:27:52'),
(3, 'Личные', 1, 1, 1, '2020-01-11 18:28:23'),
(4, 'Оповещения', 0, 3, 2, '2020-01-11 18:28:23'),
(5, 'Форумы', 4, 3, 2, '2020-01-11 18:29:02'),
(6, 'Магазины', 4, 4, 2, '2020-01-11 18:29:02'),
(7, 'Техника', 6, 2, 3, '2020-01-12 13:04:53'),
(8, 'Авто', 6, 1, 4, '2020-01-12 13:04:53');

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `user_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email_subscribe` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `active`, `user_name`, `email`, `tel`, `password`, `email_subscribe`) VALUES
(1, 1, 'Test1', 'test1@mail.ru', NULL, '$2y$10$jRD9pC./N5ZafNSooZOGx.W//aWWFhS/ugsnOfEKKMvLtm45fD14.', 0),
(2, 1, 'Test2', 'test2@mail.ru', NULL, '$2y$10$DjuOIQzx1VLvO47E8K0XXOi.t7Kqb5yiG3P4ybfjW534f3NiKqQ7q', 0),
(3, 0, 'Test3', 'test3@mail.ru', NULL, '$2y$10$xQrwto1i2NBzKuhcHO1lOen70ppVHQ1uo/BOBAxOqIVWBKaIj1Br.', 0),
(4, 1, 'Test4', 'test4@mail.ru', NULL, '$2y$10$rKz8ldHj2jgp0ssZDWuAB.NiCIVQkL.b/3h8rZph4RRBfN4CBNC7u', 0),
(5, 0, 'Test5', 'test5@mail.ru', NULL, '$2y$10$/Z1bFGlJv2cUwBOUhCgKT.n.QK3rD/oPQcvm/mopajT9.QxFhDJye', 0);


ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `group_user`
  ADD PRIMARY KEY (`user_id`,`group_id`) USING BTREE,
  ADD KEY `group_id` (`group_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `posts_ibfk_2` (`from_user_id`),
  ADD KEY `to_user_id` (`to_user_id`);

ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `color_id` (`color_id`),
  ADD KEY `created_by` (`created_by`) USING BTREE;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;


ALTER TABLE `group_user`
  ADD CONSTRAINT `group_user_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `group_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `posts_ibfk_3` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `sections_ibfk_2` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

