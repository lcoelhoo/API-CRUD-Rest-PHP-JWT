-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/04/2024 às 23:47
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `apirestphp`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('todo','in_progress','done') DEFAULT 'todo',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `status`, `created_at`, `updated_at`) VALUES
(28, 'Novo rererer', 'Nova descrição da tarefa', 'todo', '2024-04-23 02:08:20', '2024-04-23 02:35:53'),
(30, 'Novo rererer', 'Nova descrição da tarefa', 'todo', '2024-04-23 16:28:52', '2024-04-23 21:21:19'),
(31, 'Minha nova tarefa', 'Esta é a descrição da minha nova tarefa.', 'todo', '2024-04-23 21:20:59', '2024-04-23 21:20:59'),
(32, 'Minha nova tarefa', 'Esta é a descrição da minha nova tarefa.', 'todo', '2024-04-23 21:22:10', '2024-04-23 21:22:10'),
(33, 'Minha nova tarefa', 'Esta é a descrição da minha nova tarefa.', 'todo', '2024-04-23 22:00:35', '2024-04-23 22:00:35'),
(34, 'Minha nova tarefa', 'Esta é a descrição da minha nova tarefa.', 'todo', '2024-04-23 22:00:42', '2024-04-23 22:00:42'),
(35, 'Minha nova tarefa', 'Esta é a descrição da minha nova tarefa.', 'todo', '2024-04-23 22:00:43', '2024-04-23 22:00:43'),
(36, 'Minha nova tarefa', 'Esta é a descrição da minha nova tarefa.', 'todo', '2024-04-23 22:01:42', '2024-04-23 22:01:42'),
(37, 'Novo rererer', 'Nova descrição da tarefa', 'todo', '2024-04-23 22:02:00', '2024-04-25 21:06:24'),
(41, 'Minha nova tarefa', 'Esta é a descrição da minha nova tarefa.', 'todo', '2024-04-23 22:03:35', '2024-04-23 22:03:35'),
(42, 'Minha nova tarefa', 'Esta é a descrição da minha nova tarefa.', 'todo', '2024-04-23 22:34:21', '2024-04-23 22:34:21'),
(43, 'Minha nova tarefa', 'Esta é a descrição da minha nova tarefa.', 'todo', '2024-04-23 22:35:05', '2024-04-23 22:35:05'),
(44, 'Minha nova tarefa', 'Esta é a descrição da minha nova tarefa.', 'todo', '2024-04-23 22:35:53', '2024-04-23 22:35:53'),
(45, 'Minha nova tarefa', 'Esta é a descrição da minha nova tarefa.', 'todo', '2024-04-23 22:39:40', '2024-04-23 22:39:40'),
(46, 'Minha nova tarefwa', 'Esta é a descrição da minha nova tarefa.', 'todo', '2024-04-25 20:45:41', '2024-04-25 20:45:41'),
(47, 'Minha nova tarefwa', 'Esta é a descrição da minha nova tarefa.', 'todo', '2024-04-25 21:06:04', '2024-04-25 21:06:04'),
(48, 'Minha nova tarefwa', 'Esta é a descrição da minha nova tarefa.', 'todo', '2024-04-25 21:09:10', '2024-04-25 21:09:10'),
(49, 'Minha nova tarefwa', 'Esta é a descrição da minha nova tarefa.', 'todo', '2024-04-25 21:09:25', '2024-04-25 21:09:25');

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'teste1', '$2y$10$tHV/i4iTWKZ6.ebQoibujemuDZboh8EK82Ht9FpdtDzkzm1wuiTwG'),
(2, 'teste2', '$2y$10$MP7e4QyJaRkxz2gL6JXkJuVkpNVFtdkMGdxeY7E2wg0jyfMWBmOtW');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
