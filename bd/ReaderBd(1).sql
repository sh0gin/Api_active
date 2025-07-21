-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.0
-- Время создания: Июл 21 2025 г., 15:16
-- Версия сервера: 8.0.41
-- Версия PHP: 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `ReaderBd`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Book`
--

CREATE TABLE `Book` (
  `id` int UNSIGNED NOT NULL,
  `title` char(64) NOT NULL,
  `autor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `user_id` int UNSIGNED NOT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Book`
--

INSERT INTO `Book` (`id`, `title`, `autor`, `description`, `user_id`, `is_public`) VALUES
(28, 'Название', NULL, NULL, 8, 1),
(29, 'Название', NULL, NULL, 8, 1),
(30, 'Название', NULL, NULL, 12, 1),
(31, 'Название', NULL, NULL, 12, 1),
(32, 'Titlew2312', 'Author', 'description', 12, 0),
(33, 'Название', NULL, NULL, 12, 0),
(34, 'Название', NULL, NULL, 8, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `File`
--

CREATE TABLE `File` (
  `id` int UNSIGNED NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `data_uploads` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `book_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `File`
--

INSERT INTO `File` (`id`, `file_url`, `data_uploads`, `book_id`) VALUES
(31, '09Bs0Ccgi4uy-osk5GcR9aKrGuHW-tTk.html', '2025-07-20 20:55:48', 28),
(32, 'dfGmPKHV3nd0vcGXp3ZEqRWsMY1P4gZQ.html', '2025-07-20 20:59:51', 29),
(33, 'ESB0R5OuiFuw6s8eyrjUIELXeLKw89LL.html', '2025-07-21 09:00:35', 30),
(34, 'EeM4rbZd8jpV1BHZpdXor5H6He6zdbUg.html', '2025-07-21 09:00:36', 31),
(35, 'aaW-HhzIJPE__kxknjLRij02340IsZ92.html', '2025-07-21 09:00:37', 32),
(36, 'aXzhSzdzoP6kye4eEdBtBt0QnndTq2Xm.html', '2025-07-21 09:00:38', 33),
(37, 'B8RWniVUYqXyOFXMGVRkJpVVOJ2k2D_b.html', '2025-07-21 09:26:57', 34);

-- --------------------------------------------------------

--
-- Структура таблицы `Gender`
--

CREATE TABLE `Gender` (
  `id` int UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Gender`
--

INSERT INTO `Gender` (`id`, `role`) VALUES
(1, 'Male'),
(2, 'Female');

-- --------------------------------------------------------

--
-- Структура таблицы `Parametr`
--

CREATE TABLE `Parametr` (
  `id` int NOT NULL,
  `font_family` varchar(255) NOT NULL,
  `font_size` int NOT NULL,
  `text_color` varchar(7) NOT NULL,
  `background_color` varchar(7) NOT NULL,
  `user_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Parametr`
--

INSERT INTO `Parametr` (`id`, `font_family`, `font_size`, `text_color`, `background_color`, `user_id`) VALUES
(4, 'Arical', 25, '#SD76DS', '#SD76DS', 12);

-- --------------------------------------------------------

--
-- Структура таблицы `Progress`
--

CREATE TABLE `Progress` (
  `id` int UNSIGNED NOT NULL,
  `book_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `progress` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Progress`
--

INSERT INTO `Progress` (`id`, `book_id`, `user_id`, `progress`) VALUES
(4, 32, 12, 23),
(5, 33, 12, 23),
(6, 29, 12, 23);

-- --------------------------------------------------------

--
-- Структура таблицы `Role`
--

CREATE TABLE `Role` (
  `id` int UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Role`
--

INSERT INTO `Role` (`id`, `role`) VALUES
(1, 'User'),
(2, 'Admin');

-- --------------------------------------------------------

--
-- Структура таблицы `User`
--

CREATE TABLE `User` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `age` int UNSIGNED NOT NULL,
  `gender` int DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int UNSIGNED NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `User`
--

INSERT INTO `User` (`id`, `name`, `email`, `age`, `gender`, `password`, `role_id`, `token`) VALUES
(6, 'Alexey', 'user@prot.ru', 18, NULL, '$2y$13$2qRGFDztqQc8zS7IHD9c3.DyBEZWPU56RFq6HhJU50E3C9QX5p2G.', 1, NULL),
(7, 'Alexey', 'user@prot.ruf', 2, 1, '$2y$13$.qerMqs.tPuQcFm.LxTPoeT/qWdeVIKDV0jyO.ivHleC03pgYgv52', 1, NULL),
(8, 'Alexey', 'user@prot.ruff', 2, 2, '$2y$13$YnP4grtqP3d1hhe8JrWKS.NLcsDKNTDpHnVTrSCC9bkoICzVbhB0e', 2, 'bDfPuS-EYO0rZAbJRumd6l8gVfHc88N6'),
(9, 'Anton', 'reader1@prof.ru', 54, 2, '$2y$13$092FCBTCIsuh2m8stFXTLOczH4v39w5qeBnVNPsChj.oLqWQpkTBK', 2, NULL),
(10, 'Anna', 'reader2@prof.ru', 36, 1, '$2y$13$H7zc9PcY.GZPdWnvXdofveTS1caE7MeT2WZ37FL8lwzzo5JMI6p9y', 1, NULL),
(11, 'Alex', 'admin@prof.ru', 37, 1, '$2y$13$LbhFE2kogJto92htVSt7ruH0ecRLgErI3Jl2kbRRUzV3.10YS05.2', 2, 't4gWmpI_iNUfsQGcNq0pp8HR7n02iBb0'),
(12, 'Alex', 'tigr12365@gmail.com', 34, 1, '$2y$13$GCm3Kpx7677b9P3q8R2i.uDMrF2afvw0XL5Be9vFJnv8mG5WmDfG.', 1, 'sWFUBs_4e6Ckf6c4Joea4cSB9M2PupgO'),
(17, 'Arrow', 'jord@mail.ru', 23, NULL, '$2y$13$trvvOpYdyMbCo8.VENAFZOjHTE.du0WNbj5Qnzq9XI7UTQYIybH0K', 1, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Book`
--
ALTER TABLE `Book`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `File`
--
ALTER TABLE `File`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`);

--
-- Индексы таблицы `Gender`
--
ALTER TABLE `Gender`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Parametr`
--
ALTER TABLE `Parametr`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `Progress`
--
ALTER TABLE `Progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `Role`
--
ALTER TABLE `Role`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_ibfk_2` (`role_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Book`
--
ALTER TABLE `Book`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT для таблицы `File`
--
ALTER TABLE `File`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT для таблицы `Gender`
--
ALTER TABLE `Gender`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `Parametr`
--
ALTER TABLE `Parametr`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `Progress`
--
ALTER TABLE `Progress`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `Role`
--
ALTER TABLE `Role`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `User`
--
ALTER TABLE `User`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Book`
--
ALTER TABLE `Book`
  ADD CONSTRAINT `book_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `File`
--
ALTER TABLE `File`
  ADD CONSTRAINT `file_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `Book` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `Parametr`
--
ALTER TABLE `Parametr`
  ADD CONSTRAINT `parametr_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `Progress`
--
ALTER TABLE `Progress`
  ADD CONSTRAINT `progress_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `Book` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `progress_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `User`
--
ALTER TABLE `User`
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `Role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
