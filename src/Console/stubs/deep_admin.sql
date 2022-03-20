-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 20, 2022 at 12:13 PM
-- Server version: 5.7.30-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `camera_sc`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_entities`
--

CREATE TABLE `admin_entities` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `table_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `is_internal` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `enable_comment` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_show_content_manage` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `default_sort` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'id' COMMENT '默认排序字段',
  `sort_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT 'desc' COMMENT '排序类型',
  `actions` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '["create","edit","delete"]' COMMENT '支持操作',
  `actions_width` int(11) DEFAULT '100' COMMENT '操作栏宽度'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_entities`
--

INSERT INTO `admin_entities` (`id`, `name`, `table_name`, `description`, `is_internal`, `enable_comment`, `is_show_content_manage`, `created_at`, `updated_at`, `default_sort`, `sort_type`, `actions`, `actions_width`) VALUES
(1, '模型表', 'admin_entities', '模型表', 0, 0, 1, '2020-09-01 09:58:41', '2020-09-01 09:58:41', 'id', 'desc', '[\"create\",\"edit\",\"delete\"]', 100),
(2, '模型表字段', 'admin_entity_fields', '模型表字段', 0, 0, 1, '2020-09-01 10:03:49', '2020-09-01 10:03:49', 'id', 'desc', '[]', 100);

-- --------------------------------------------------------

--
-- Table structure for table `admin_entity_fields`
--

CREATE TABLE `admin_entity_fields` (
  `id` int(10) UNSIGNED NOT NULL,
  `entity_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `field_length` int(11) DEFAULT '0',
  `field_total` int(10) DEFAULT '0' COMMENT '整数位长度',
  `field_scale` int(10) DEFAULT '0' COMMENT '小数位长度',
  `comment` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `default_value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `form_name` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `form_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `form_comment` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `form_params` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `is_show` tinyint(3) UNSIGNED DEFAULT '1' COMMENT ' 1.不显示、2.编辑显示、3.创建显示、4.始终显示 ',
  `is_list_show` int(3) UNSIGNED DEFAULT '0' COMMENT '是否列表展示（0否、1是）',
  `is_show_inline` tinyint(3) UNSIGNED DEFAULT '0',
  `is_edit` tinyint(3) UNSIGNED DEFAULT '1' COMMENT '1.不显示、2.编辑显示、3.创建显示、4.始终显示',
  `is_required` tinyint(3) UNSIGNED DEFAULT '0' COMMENT '是否必填（0否、1是）',
  `is_unique` int(3) DEFAULT '0',
  `is_order` tinyint(3) UNSIGNED DEFAULT '0' COMMENT '支持列表排序（0不支持、1支持）',
  `order` int(10) UNSIGNED DEFAULT '100' COMMENT '表单显示排序',
  `list_order` int(10) UNSIGNED DEFAULT '100' COMMENT '列表显示排序',
  `is_search` int(10) DEFAULT '0' COMMENT '是否支持查询',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `list_width` int(11) DEFAULT '0' COMMENT '列表宽度',
  `table_where` text COLLATE utf8mb4_unicode_ci COMMENT '连表查询条件'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_entity_fields`
--

INSERT INTO `admin_entity_fields` (`id`, `entity_id`, `name`, `type`, `field_length`, `field_total`, `field_scale`, `comment`, `default_value`, `form_name`, `form_type`, `form_comment`, `form_params`, `is_show`, `is_list_show`, `is_show_inline`, `is_edit`, `is_required`, `is_unique`, `is_order`, `order`, `list_order`, `is_search`, `created_at`, `updated_at`, `list_width`, `table_where`) VALUES
(1, 1, 'default_sort', 'string', 20, 4, 0, '默认排序字段', 'id', '默认排序字段', 'input', '默认排序字段', NULL, 4, 1, 0, 1, 0, 0, 1, 100, 100, 3, '2020-09-01 10:00:25', '2020-09-01 10:00:25', 0, NULL),
(2, 1, 'sort_type', 'string', 20, 4, 0, '排序类型', 'desc', '排序类型', 'select', '排序类型', 'asc=升序\ndesc=降序', 4, 1, 0, 1, 0, 0, 1, 100, 100, 4, '2020-09-01 10:01:26', '2020-09-01 10:01:26', 0, NULL),
(3, 1, 'actions', 'string', 255, 4, 0, '支持操作', '[\"create\",\"edit\",\"delete\"]', '支持操作', 'checkbox', '支持操作', 'create=添加\nedit=编辑\ndelete=删除', 4, 1, 0, 1, 0, 0, 1, 100, 100, 4, '2020-09-01 10:02:06', '2020-09-01 10:02:06', 0, NULL),
(4, 1, 'actions_width', 'integer', 10, 4, 0, '操作栏宽度', '100', '操作栏宽度', 'inputNumber', '操作栏宽度', NULL, 4, 1, 0, 1, 0, 0, 1, 100, 100, 1, '2020-09-01 10:02:37', '2020-09-01 10:02:37', 0, NULL),
(5, 2, 'list_width', 'integer', 10, 4, 0, '列表宽度', '0', '列表宽度', 'inputNumber', '列表宽度', NULL, 4, 1, 0, 1, 0, 0, 1, 100, 100, 1, '2020-09-01 10:05:47', '2020-09-01 10:05:47', 0, NULL),
(6, 2, 'is_unique', 'integer', 10, 4, 0, '是否唯一', '0', '是否唯一', 'switch', '是否唯一', NULL, 4, 1, 0, 1, 0, 0, 1, 100, 100, 1, '2020-09-01 10:06:18', '2020-09-01 10:06:18', 0, NULL),
(7, 2, 'table_where', 'text', 255, 4, 0, '连表查询条件', NULL, '连表查询条件', 'textArea', '连表查询条件', NULL, 4, 0, 0, 1, 0, 0, 0, 100, 100, 1, '2020-09-01 10:06:52', '2020-09-01 10:06:52', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_files`
--

CREATE TABLE `admin_files` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'image,avatar,file等',
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '存储驱动类型，如local，qiniu',
  `local_path` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '本地存储路径，如果设置了本地存储',
  `md5` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文件md5',
  `sha1` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文件sha1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_menu`
--

CREATE TABLE `admin_menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uri` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_menu`
--

INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `created_at`, `updated_at`) VALUES
(1, 0, 1, '首页', 'el-icon-monitor', '/home', '[2]', NULL, '2020-07-03 02:20:54'),
(2, 0, 10, '系统管理', 'el-icon-setting', 'system', NULL, NULL, '2020-06-11 01:12:35'),
(3, 2, 3, '管理员', 'el-icon-user-solid', 'auth/users', NULL, NULL, '2020-06-03 05:38:19'),
(4, 2, 4, '角色', 'el-icon-key', 'auth/roles', NULL, NULL, '2020-06-03 05:39:09'),
(5, 2, 5, '权限', 'el-icon-lock', 'auth/permissions', NULL, NULL, '2020-06-03 05:39:30'),
(6, 2, 6, '菜单', 'el-icon-notebook-2', 'auth/menu', NULL, NULL, '2020-06-03 05:42:24'),
(7, 2, 7, '操作日志', 'el-icon-s-comment', 'auth/logs', NULL, NULL, '2020-06-03 05:43:35'),
(8, 0, 2, '模型管理', 'el-icon-news', 'entitie', '[]', '2020-06-02 13:30:07', '2020-07-03 02:23:05'),
(10, 8, 1, '模型列表', 'el-icon-postcard', 'entities/entity', '[]', '2020-06-02 23:57:39', '2020-07-03 02:22:41');

-- --------------------------------------------------------

--
-- Table structure for table `admin_operation_log`
--

CREATE TABLE `admin_operation_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `input` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_operation_log`
--

INSERT INTO `admin_operation_log` (`id`, `user_id`, `path`, `method`, `ip`, `input`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin-api/auth/permissions', 'GET', '::1', '[]', '2021-07-22 13:26:41', '2021-07-22 13:26:41'),
(2, 1, 'admin-api/auth/permissions', 'GET', '::1', '{\"get_data\":\"true\",\"page\":\"1\",\"per_page\":\"15\",\"sort_prop\":\"id\",\"sort_order\":\"desc\",\"sort_field\":\"id\"}', '2021-07-22 13:26:43', '2021-07-22 13:26:43'),
(3, 1, 'admin-api/auth/permissions/7', 'DELETE', '::1', '[]', '2021-07-22 13:26:49', '2021-07-22 13:26:49'),
(4, 1, 'admin-api/auth/permissions', 'GET', '::1', '{\"get_data\":\"true\",\"page\":\"1\",\"per_page\":\"15\",\"sort_prop\":\"id\",\"sort_order\":\"desc\",\"sort_field\":\"id\"}', '2021-07-22 13:26:52', '2021-07-22 13:26:52'),
(5, 1, 'admin-api/auth/permissions/6', 'DELETE', '::1', '[]', '2021-07-22 13:26:57', '2021-07-22 13:26:57'),
(6, 1, 'admin-api/auth/permissions', 'GET', '::1', '{\"get_data\":\"true\",\"page\":\"1\",\"per_page\":\"15\",\"sort_prop\":\"id\",\"sort_order\":\"desc\",\"sort_field\":\"id\"}', '2021-07-22 13:27:00', '2021-07-22 13:27:00'),
(7, 1, 'admin-api/home', 'GET', '::1', '[]', '2022-03-20 04:11:31', '2022-03-20 04:11:31'),
(8, 1, 'admin-api/entities/entity', 'GET', '::1', '[]', '2022-03-20 04:11:33', '2022-03-20 04:11:33'),
(9, 1, 'admin-api/entities/entity', 'GET', '::1', '{\"get_data\":\"true\",\"page\":\"1\",\"per_page\":\"15\",\"sort_prop\":\"id\",\"sort_order\":\"desc\",\"sort_field\":\"id\"}', '2022-03-20 04:11:34', '2022-03-20 04:11:34'),
(10, 1, 'admin-api/auth/users', 'GET', '::1', '[]', '2022-03-20 04:11:42', '2022-03-20 04:11:42'),
(11, 1, 'admin-api/auth/users', 'GET', '::1', '{\"get_data\":\"true\",\"page\":\"1\",\"per_page\":\"15\",\"sort_prop\":\"id\",\"sort_order\":\"desc\",\"sort_field\":\"id\"}', '2022-03-20 04:11:43', '2022-03-20 04:11:43'),
(12, 1, 'admin-api/auth/users/9', 'DELETE', '::1', '[]', '2022-03-20 04:11:50', '2022-03-20 04:11:50'),
(13, 1, 'admin-api/auth/users', 'GET', '::1', '{\"get_data\":\"true\",\"page\":\"1\",\"per_page\":\"15\",\"sort_prop\":\"id\",\"sort_order\":\"desc\",\"sort_field\":\"id\"}', '2022-03-20 04:11:51', '2022-03-20 04:11:51'),
(14, 1, 'admin-api/auth/users/8', 'DELETE', '::1', '[]', '2022-03-20 04:11:54', '2022-03-20 04:11:54'),
(15, 1, 'admin-api/auth/users', 'GET', '::1', '{\"get_data\":\"true\",\"page\":\"1\",\"per_page\":\"15\",\"sort_prop\":\"id\",\"sort_order\":\"desc\",\"sort_field\":\"id\"}', '2022-03-20 04:11:55', '2022-03-20 04:11:55'),
(16, 1, 'admin-api/auth/users/6', 'DELETE', '::1', '[]', '2022-03-20 04:11:58', '2022-03-20 04:11:58'),
(17, 1, 'admin-api/auth/users', 'GET', '::1', '{\"get_data\":\"true\",\"page\":\"1\",\"per_page\":\"15\",\"sort_prop\":\"id\",\"sort_order\":\"desc\",\"sort_field\":\"id\"}', '2022-03-20 04:11:59', '2022-03-20 04:11:59'),
(18, 1, 'admin-api/auth/users/4', 'DELETE', '::1', '[]', '2022-03-20 04:12:02', '2022-03-20 04:12:02'),
(19, 1, 'admin-api/auth/users', 'GET', '::1', '{\"get_data\":\"true\",\"page\":\"1\",\"per_page\":\"15\",\"sort_prop\":\"id\",\"sort_order\":\"desc\",\"sort_field\":\"id\"}', '2022-03-20 04:12:02', '2022-03-20 04:12:02'),
(20, 1, 'admin-api/auth/users/3', 'DELETE', '::1', '[]', '2022-03-20 04:12:06', '2022-03-20 04:12:06'),
(21, 1, 'admin-api/auth/users', 'GET', '::1', '{\"get_data\":\"true\",\"page\":\"1\",\"per_page\":\"15\",\"sort_prop\":\"id\",\"sort_order\":\"desc\",\"sort_field\":\"id\"}', '2022-03-20 04:12:07', '2022-03-20 04:12:07'),
(22, 1, 'admin-api/auth/users/2', 'DELETE', '::1', '[]', '2022-03-20 04:12:10', '2022-03-20 04:12:10'),
(23, 1, 'admin-api/auth/users', 'GET', '::1', '{\"get_data\":\"true\",\"page\":\"1\",\"per_page\":\"15\",\"sort_prop\":\"id\",\"sort_order\":\"desc\",\"sort_field\":\"id\"}', '2022-03-20 04:12:11', '2022-03-20 04:12:11'),
(24, 1, 'admin-api/auth/users/1/edit', 'GET', '::1', '{\"get_data\":\"true\"}', '2022-03-20 04:12:14', '2022-03-20 04:12:14'),
(25, 1, 'admin-api/auth/users/1', 'PUT', '::1', '{\"username\":\"dadmin\",\"name\":\"\\u8d85\\u7ea7\\u7ba1\\u7406\\u5458\",\"avatar\":\"https:\\/\\/gw.alipayobjects.com\\/zos\\/antfincdn\\/XAosXuNZyF\\/BiazfanxmamNRoxxVxka.png\",\"password\":\"D_Admin##\",\"password_confirmation\":\"D_Admin##\",\"roles\":[1],\"permissions\":[]}', '2022-03-20 04:12:40', '2022-03-20 04:12:40'),
(26, 1, 'admin-api/auth/users', 'GET', '::1', '{\"get_data\":\"true\",\"page\":\"1\",\"per_page\":\"15\",\"sort_prop\":\"id\",\"sort_order\":\"desc\",\"sort_field\":\"id\"}', '2022-03-20 04:12:41', '2022-03-20 04:12:41');

-- --------------------------------------------------------

--
-- Table structure for table `admin_permissions`
--

CREATE TABLE `admin_permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `http_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `http_path` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int(11) DEFAULT '100',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_permissions`
--

INSERT INTO `admin_permissions` (`id`, `name`, `slug`, `http_method`, `http_path`, `order`, `created_at`, `updated_at`) VALUES
(1, '所有权限', '*', '', '*', 100, NULL, NULL),
(2, '首页', 'dashboard', 'GET', '/home', 100, NULL, '2020-07-03 02:13:18'),
(3, '登录/退出', 'auth.login', '', '/auth/login\r\n/auth/logout', 100, NULL, NULL),
(4, '用户设置', 'auth.setting', 'GET,PUT', '/auth/setting', 100, NULL, NULL),
(5, '系统设置', 'auth.management', '', '/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs', 100, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_roles`
--

CREATE TABLE `admin_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_roles`
--

INSERT INTO `admin_roles` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, '超级管理员', 'administrator', '2020-06-02 13:28:56', '2020-06-02 13:28:56');

-- --------------------------------------------------------

--
-- Table structure for table `admin_role_menu`
--

CREATE TABLE `admin_role_menu` (
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_role_menu`
--

INSERT INTO `admin_role_menu` (`role_id`, `menu_id`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL),
(1, 8, NULL, NULL),
(1, 10, NULL, NULL),
(1, 13, NULL, NULL),
(1, 15, NULL, NULL),
(1, 16, NULL, NULL),
(1, 17, NULL, NULL),
(3, 17, NULL, NULL),
(1, 1, NULL, NULL),
(3, 1, NULL, NULL),
(1, 18, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_role_permissions`
--

CREATE TABLE `admin_role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_role_permissions`
--

INSERT INTO `admin_role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_role_users`
--

CREATE TABLE `admin_role_users` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_role_users`
--

INSERT INTO `admin_role_users` (`role_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_token` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `name`, `avatar`, `remember_token`, `api_token`, `created_at`, `updated_at`) VALUES
(1, 'dadmin', '$2y$10$9PKnKF3B7rw2fM.ZYgUweecN3wC.m4Wq5mDe1qyW6juOac.l3R8r2', '超级管理员', 'https://gw.alipayobjects.com/zos/antfincdn/XAosXuNZyF/BiazfanxmamNRoxxVxka.png', 'RODOLkQMdFROggJFg734l6H7DLxxerruk4h3mALiRiDRoSy6C9PLUI9IQram', NULL, '2020-06-02 13:28:56', '2022-03-20 04:12:41');

-- --------------------------------------------------------

--
-- Table structure for table `admin_user_permissions`
--

CREATE TABLE `admin_user_permissions` (
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(2, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(3, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(4, '2016_06_01_000004_create_oauth_clients_table', 1),
(5, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(6, '2019_03_08_120020_create_entities_table', 1),
(7, '2019_03_08_135202_create_entity_fields_table', 1),
(10, '2020_12_09_210026_create_admin_files', 2);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('7c7011cd7d3659ed1b2772cb27411b925c32a85648f26bcd4d0988b29e4737204caea14dadee2e4c', 1, 1, 'token', '[]', 0, '2020-08-29 08:04:39', '2020-08-29 08:04:39', '2021-08-29 16:04:39'),
('d8c0825019abb5bfeed11fbc0b7a89b1355b15458f169ed851ea22042ca30c8f032bd53f8bd428a8', 1, 1, 'token', '[]', 0, '2020-07-09 11:35:05', '2020-07-09 11:35:05', '2021-07-09 19:35:05');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'DeepAdmin Personal Access Client', 'IWIyJwbC5jbQGH8dhA1JVDQP7ylz7ve8mfkV95z7', NULL, 'http://localhost', 1, 0, 0, '2020-07-08 13:24:43', '2020-07-08 13:24:43'),
(2, NULL, 'DeepAdmin Password Grant Client', 'pTM64BbmQT9iFKkVPVx7WF8oGapMhyq2Ipzv1vQq', 'users', 'http://localhost', 0, 1, 0, '2020-07-08 13:24:43', '2020-07-08 13:24:43');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2020-07-08 13:24:43', '2020-07-08 13:24:43');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_entities`
--
ALTER TABLE `admin_entities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_entities_table_name_unique` (`table_name`);

--
-- Indexes for table `admin_entity_fields`
--
ALTER TABLE `admin_entity_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_entity_fields_entity_id_name_unique` (`entity_id`,`name`);

--
-- Indexes for table `admin_files`
--
ALTER TABLE `admin_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_menu`
--
ALTER TABLE `admin_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_operation_log`
--
ALTER TABLE `admin_operation_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_operation_log_user_id_index` (`user_id`);

--
-- Indexes for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_permissions_name_unique` (`name`),
  ADD UNIQUE KEY `admin_permissions_slug_unique` (`slug`);

--
-- Indexes for table `admin_roles`
--
ALTER TABLE `admin_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_roles_name_unique` (`name`),
  ADD UNIQUE KEY `admin_roles_slug_unique` (`slug`);

--
-- Indexes for table `admin_role_menu`
--
ALTER TABLE `admin_role_menu`
  ADD KEY `admin_role_menu_role_id_menu_id_index` (`role_id`,`menu_id`);

--
-- Indexes for table `admin_role_permissions`
--
ALTER TABLE `admin_role_permissions`
  ADD KEY `admin_role_permissions_role_id_permission_id_index` (`role_id`,`permission_id`);

--
-- Indexes for table `admin_role_users`
--
ALTER TABLE `admin_role_users`
  ADD KEY `admin_role_users_role_id_user_id_index` (`role_id`,`user_id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_users_username_unique` (`username`),
  ADD UNIQUE KEY `api_token` (`api_token`);

--
-- Indexes for table `admin_user_permissions`
--
ALTER TABLE `admin_user_permissions`
  ADD KEY `admin_user_permissions_user_id_permission_id_index` (`user_id`,`permission_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_entities`
--
ALTER TABLE `admin_entities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_entity_fields`
--
ALTER TABLE `admin_entity_fields`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `admin_files`
--
ALTER TABLE `admin_files`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `admin_menu`
--
ALTER TABLE `admin_menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `admin_operation_log`
--
ALTER TABLE `admin_operation_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `admin_roles`
--
ALTER TABLE `admin_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
