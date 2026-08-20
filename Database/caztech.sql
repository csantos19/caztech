-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2026 at 10:29 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `caztech`
--

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `business` varchar(255) NOT NULL,
  `project_type` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `name`, `business`, `project_type`, `created_at`) VALUES
(1, 'adasd', 'adas', 'System', '2026-03-17 05:54:17');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `business` varchar(255) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `review` text NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL DEFAULT 5,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon_svg` text DEFAULT NULL,
  `bg_class` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `icon_image` varchar(255) DEFAULT NULL,
  `project_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `category`, `description`, `icon_svg`, `bg_class`, `created_at`, `icon_image`, `project_url`) VALUES
(1, 'Clinic Management System', 'Web App', 'Comprehensive medical records, scheduling, and billing system built for modern clinics.', '<svg class=\"w-8 h-8 text-blue-500\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10\"></path></svg>', 'bg-blue-50 dark:bg-blue-900/30', '2026-03-18 06:32:11', NULL, NULL),
(2, 'E-Commerce Platform', 'Website', 'High-conversion online retail store with real-time inventory tracking and payments.', '<svg class=\"w-8 h-8 text-purple-500\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z\"></path></svg>', 'bg-purple-50 dark:bg-purple-900/30', '2026-03-18 06:32:11', NULL, NULL),
(3, 'Inventory Tracker', 'System', 'Real-time stock management and analytics dashboard for warehousing operations.', '<svg class=\"w-8 h-8 text-green-500\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4\"></path></svg>', 'bg-green-50 dark:bg-green-900/30', '2026-03-18 06:32:11', NULL, NULL),
(4, 'RJV Dental & Medical Clinic', 'Web App', 'This project was added automatically to verify the dynamic system.', '<svg class=\'w-8 h-8 text-orange-500\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\'></path></svg>', '', '2026-03-18 06:34:48', NULL, NULL),
(5, 'RJV Medical & Dental Clinic', 'Web App', '', NULL, 'bg-blue-50 dark:bg-blue-900/30', '2026-03-18 06:56:28', 'uploads/projects/proj_69ba4c9c7b557.png', 'https://rjvmdc.com/login');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(100) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `profile_headline` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `years_experience` varchar(50) DEFAULT NULL,
  `projects_completed` varchar(50) DEFAULT NULL,
  `clients_served` varchar(50) DEFAULT NULL,
  `satisfaction_rate` varchar(50) DEFAULT NULL,
  `github_url` varchar(255) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `profile_email` varchar(255) DEFAULT NULL,
  `resume_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `name`, `role`, `image_path`, `created_at`) VALUES
(2, 'Christian George Santos', 'Full-Stack Developer', 'uploads/team/team_69ba5858da42a.jpg', '2026-03-18 07:46:32'),
(3, 'Anntricia Feliciano', 'Full-Stack Developer', 'uploads/team/team_69ba58a0747aa.jpg', '2026-03-18 07:47:44'),
(5, 'Zildjian Geronimo', 'Full-Stack Developer', 'uploads/team/team_69ba63a769db3.jpg', '2026-03-18 08:34:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_testimonials_approved_created` (`approved`,`created_at`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- Skills for Christian George Santos (`team_members.id` = 2).
UPDATE `team_members`
SET `skills` = '[Languages] PHP|96
[Languages] JavaScript|92
[Languages] HTML5|94
[Languages] CSS3|90
[Languages] SQL|92
[Languages] JSON|82
[Templates] Laravel Blade|82
[Databases] MySQL / MariaDB|94
[Databases] SQLite|68
[Databases] Redis (Laravel realtime/cache configuration)|56
[Backend] Laravel|86
[Backend] Eloquent ORM / migrations|84
[Backend] Laravel Sanctum|60
[Backend] Laravel Breeze|55
[Backend] Laravel Reverb / WebSockets|62
[Backend] Laravel Echo / Pusher JS|61
[Backend] MySQLi / PDO|92
[Backend] REST APIs / AJAX|90
[Backend] Sessions / authentication / role-based access|94
[Frontend] Tailwind CSS|86
[Frontend] Bootstrap|88
[Frontend] Bootstrap Icons|75
[Frontend] Alpine.js|82
[Frontend] jQuery / jQuery UI / jQuery Validate|79
[Frontend] jQuery DataTables|69
[Frontend] jQuery Steps|48
[Frontend] Datepicker|58
[Frontend] Bootstrap Fileupload|45
[Frontend] MetisMenu / MixItUp / PrettyPhoto|44
[Frontend] FullCalendar|57
[Frontend] ApexCharts|60
[Frontend] Chart.js|55
[Frontend] SweetAlert2|62
[Frontend] Font Awesome|84
[Frontend] Axios|71
[Frontend] Turbo / Hotwire|42
[Frontend] Dropzone / Flatpickr|48
[Frontend] jsVectorMap / Swiper|40
[Frontend] TailAdmin|66
[Email] PHPMailer / SMTP|72
[Reports] Dompdf / PDF generation|67
[Reports] Laravel Excel / PhpSpreadsheet|58
[Tooling] Composer|86
[Tooling] Node.js / npm|84
[Tooling] Vite / Laravel Vite|78
[Tooling] Webpack / Babel|60
[Tooling] PostCSS / Autoprefixer|73
[Tooling] Prettier|48
[Tooling] PHPUnit / Laravel Pint|62
[Tooling] Laravel Artisan / Sail / Tinker|78
[Tooling] PWA / Web App Manifest|46
[Deployment] XAMPP / Apache|93
[Deployment] phpMyAdmin|84
[Deployment] InfinityFree / FTP / FileZilla|55
[Deployment] Hostinger|72
[Tools] VS Code|78
[Tools] Git / GitHub|68
[Automation] Windows Batch / PowerShell / VBScript|54
[Tools] Microsoft Excel|56'
WHERE `id` = 2;
