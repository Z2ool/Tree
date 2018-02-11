-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Ned 11. úno 2018, 15:12
-- Verze serveru: 10.1.21-MariaDB
-- Verze PHP: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Databáze: `tree`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `tree`
--

CREATE TABLE `tree` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `left` int(11) NOT NULL,
  `right` int(11) NOT NULL,
  `depth` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



INSERT INTO `tree` (`id`, `parent_id`, `left`, `right`, `depth`) VALUES
(1, NULL, 1, 2, 1);

ALTER TABLE `tree`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `left_t` (`left`),
  ADD KEY `right_t` (`right`);

ALTER TABLE `tree`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;