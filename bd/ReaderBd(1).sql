-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.0
-- Время создания: Июл 18 2025 г., 19:44
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
  `file_id` int UNSIGNED NOT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Book`
--

INSERT INTO `Book` (`id`, `title`, `autor`, `description`, `file_id`, `is_public`) VALUES
(4, 'Новое название книги 4', 'Новый автор книг', 'Новое описание книги', 4, 0),
(5, 'Название 1', 'Автор книги 1', 'Описание книги 1', 5, 0),
(6, 'Название 1', 'Автор книги 1', '', 6, 0),
(7, 'Название 1', '', '', 7, 0),
(8, 'Название 1', '', '', 8, 0),
(9, 'Новое название книги 5', 'Новый автор книг', 'Новое описание книги', 12, 1),
(11, 'New', 'New', 'New', 14, 0),
(12, 'New', 'New', 'New', 15, 1),
(13, 'New', 'New', 'New', 16, 1),
(14, 'New', 'New', 'New', 17, 1),
(15, 'New', 'New', 'New', 18, 1),
(16, 'New', 'New', 'New', 19, 1),
(17, 'New', 'New', 'New', 20, 1),
(18, 'New', 'New', 'New', 21, 0),
(19, 'New', 'New', 'New', 22, 1),
(20, 'New', 'New', 'New', 23, 0),
(21, 'New', 'New', 'New', 24, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `File`
--

CREATE TABLE `File` (
  `id` int UNSIGNED NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `data_uploads` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `File`
--

INSERT INTO `File` (`id`, `file_url`, `data_uploads`, `user_id`) VALUES
(1, 'RCEnsziFLGlAAEl9Orwj7-2w4dV-bDAf.html', '2025-07-15 09:52:43', 8),
(2, 'yZ2zix0GKom2xyu2Fy8XVYazatYP7OVr.html', '2025-07-15 09:53:00', 8),
(3, '09zd8D7CemyxiIk4TSClLLP3px8_eYcW.html', '2025-07-15 09:53:08', 8),
(4, '1A79Sml6Wr-sNCl6afbb5_rRqePdGG2O.html', '2025-07-15 09:53:36', 8),
(5, 'TPpKRmMCPBFUigpcS1dV-pDJwbJtXtDH.html', '2025-07-15 09:53:41', 8),
(6, 'm2xwlSqNQjFrHADpqcgsa6m4R5ivQTZD.html', '2025-07-15 09:54:52', 8),
(7, 'vbSFzKj6S6skgO_8uNPrkMFM4Xjgdbv5.html', '2025-07-15 09:54:57', 8),
(8, 'ikVQq5rPVJUmpYE4Xe2Uc1-NB8vvRuMR.html', '2025-07-15 09:57:01', 8),
(9, 'Fd3sJQ9fVBgMUIriHFcqnM7yY6JQt1wV.html', '2025-07-15 10:24:53', 8),
(10, 'MBXafvGbJ_32tTNiJHApNtnC-TIsgEl5.html', '2025-07-17 18:15:08', 11),
(11, 'GRzqI-yPBl_DLepFp26kS5TMSPVKg4FQ.html', '2025-07-17 18:17:13', 11),
(12, 'G3b5-Dt-L3WA6y4RJ4xb2oSAspPITxjH.html', '2025-07-17 18:18:31', 11),
(14, 'e9MlixZvD1EJhx1NtCLpMmTno0BpXYHd.html', '2025-07-18 11:43:36', 11),
(15, 'zupe6Kdx3XnyCD5IVBmct_xjsivCbuus.html', '2025-07-18 11:57:31', 11),
(16, 'Aaqb1KXuSnWnBNRNc6Zg5n-P8210khLh.html', '2025-07-18 11:57:48', 11),
(17, 'UIhbpVix3NOs8Ias5T3BHUwPi4F66CLN.html', '2025-07-18 11:57:49', 11),
(18, 'DUdYp6ffkwJ3Q8pAEBTx_6MTn1RCB5Ct.html', '2025-07-18 11:57:50', 11),
(19, 'dkCUjf025JMksqFbJRnc7wCj1tclgdOY.html', '2025-07-18 11:57:51', 11),
(20, 'QxazMstP7uj5BBUQYio1dsynePgN6NFv.html', '2025-07-18 11:57:52', 11),
(21, '3fQPc11WtCgf3AGAHxrK8DGJQZNhHQX8.html', '2025-07-18 12:09:21', 9),
(22, 'KArI-P72gjvG0hNUAaO0k58aEX4DJ6g0.html', '2025-07-18 12:09:22', 9),
(23, '-VP_XCblWFu-_qZZ7ugKWnMD-EG0Ep7J.html', '2025-07-18 12:09:23', 9),
(24, 'cM5ho2StltB8vcNZq8UIoew6IwV1W0QW.html', '2025-07-18 12:09:24', 9);

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
  `background_color` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Parametr`
--

INSERT INTO `Parametr` (`id`, `font_family`, `font_size`, `text_color`, `background_color`) VALUES
(1, 'Arial', 16, '#000000', '$FFFFFF'),
(2, 'dsf', 67, '#D2FDSF', '#DFFDSF'),
(3, 'dsf', 67, '#D2FDSF', '#DFFDSF');

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
(1, 9, 11, 51.6),
(3, 11, 11, 77);

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
  `parametr_id` int NOT NULL DEFAULT '1',
  `role_id` int UNSIGNED NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `User`
--

INSERT INTO `User` (`id`, `name`, `email`, `age`, `gender`, `password`, `parametr_id`, `role_id`, `token`) VALUES
(6, 'Alexey', 'user@prot.ru', 18, NULL, '$2y$13$2qRGFDztqQc8zS7IHD9c3.DyBEZWPU56RFq6HhJU50E3C9QX5p2G.', 1, 1, NULL),
(7, 'Alexey', 'user@prot.ruf', 2, 1, '$2y$13$.qerMqs.tPuQcFm.LxTPoeT/qWdeVIKDV0jyO.ivHleC03pgYgv52', 1, 1, NULL),
(8, 'Alexey', 'user@prot.ruff', 2, 2, '$2y$13$YnP4grtqP3d1hhe8JrWKS.NLcsDKNTDpHnVTrSCC9bkoICzVbhB0e', 1, 1, 'bDfPuS-EYO0rZAbJRumd6l8gVfHc88N6'),
(9, 'Anton', 'reader1@prof.ru', 54, 2, '$2y$13$092FCBTCIsuh2m8stFXTLOczH4v39w5qeBnVNPsChj.oLqWQpkTBK', 3, 2, NULL),
(10, 'Anna', 'reader2@prof.ru', 36, 1, '$2y$13$H7zc9PcY.GZPdWnvXdofveTS1caE7MeT2WZ37FL8lwzzo5JMI6p9y', 1, 1, NULL),
(11, 'Alex', 'admin@prof.ru', 37, 1, '$2y$13$LbhFE2kogJto92htVSt7ruH0ecRLgErI3Jl2kbRRUzV3.10YS05.2', 1, 2, 't4gWmpI_iNUfsQGcNq0pp8HR7n02iBb0');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Book`
--
ALTER TABLE `Book`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_url` (`file_id`);

--
-- Индексы таблицы `File`
--
ALTER TABLE `File`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Gender`
--
ALTER TABLE `Gender`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Parametr`
--
ALTER TABLE `Parametr`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `parametr_id` (`parametr_id`),
  ADD KEY `user_ibfk_2` (`role_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Book`
--
ALTER TABLE `Book`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `File`
--
ALTER TABLE `File`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `Gender`
--
ALTER TABLE `Gender`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `Parametr`
--
ALTER TABLE `Parametr`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `Progress`
--
ALTER TABLE `Progress`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `Role`
--
ALTER TABLE `Role`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `User`
--
ALTER TABLE `User`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Book`
--
ALTER TABLE `Book`
  ADD CONSTRAINT `book_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `File` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`parametr_id`) REFERENCES `Parametr` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `Role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
