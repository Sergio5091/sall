-- --------------------------------------------------------
-- Base de données corrigée avec ordre de création correct
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `sale_de_jeu` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE=utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sale_de_jeu`;

-- Tables sans dépendances d'abord
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `news` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tables de permissions et rôles (sans dépendances)
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table users (table principale)
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favorite_games` text COLLATE utf8mb4_unicode_ci,
  `specialties` json DEFAULT NULL,
  `social_links` json DEFAULT NULL,
  `profile_photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferences` json DEFAULT NULL,
  `password_updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `referral_code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_referral_code_unique` (`referral_code`),
  KEY `users_role_index` (`role`),
  KEY `users_parent_id_foreign` (`parent_id`),
  KEY `users_country_city_index` (`country`,`city`),
  CONSTRAINT `users_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table salles (dépend de users)
CREATE TABLE IF NOT EXISTS `salles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categorie` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `departement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quartier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_postal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pays` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'France',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_web` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reseaux_sociaux` json DEFAULT NULL,
  `horaires` json DEFAULT NULL,
  `capacite_max` int NOT NULL DEFAULT '0',
  `surface_area` decimal(8,2) DEFAULT NULL,
  `machines_arcade` int NOT NULL DEFAULT '0',
  `casques_vr` int NOT NULL DEFAULT '0',
  `flippers` int NOT NULL DEFAULT '0',
  `consoles_retro` int NOT NULL DEFAULT '0',
  `pc_gaming` int NOT NULL DEFAULT '0',
  `tables_bowling` int NOT NULL DEFAULT '0',
  `tables_billard` int NOT NULL DEFAULT '0',
  `wifi_gratuit` tinyint(1) NOT NULL DEFAULT '0',
  `parking` tinyint(1) NOT NULL DEFAULT '0',
  `climatisation` tinyint(1) NOT NULL DEFAULT '0',
  `accessibilite_pmr` tinyint(1) NOT NULL DEFAULT '0',
  `surveillance_24h` tinyint(1) NOT NULL DEFAULT '0',
  `snack_bar` tinyint(1) NOT NULL DEFAULT '0',
  `restaurant` tinyint(1) NOT NULL DEFAULT '0',
  `bar` tinyint(1) NOT NULL DEFAULT '0',
  `terrasse` tinyint(1) NOT NULL DEFAULT '0',
  `espace_fumeur` tinyint(1) NOT NULL DEFAULT '0',
  `vestiaires` tinyint(1) NOT NULL DEFAULT '0',
  `horaires_ouverture` json DEFAULT NULL,
  `jours_fermes` json DEFAULT NULL,
  `image_couverture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `images_galerie` json DEFAULT NULL,
  `video_presentation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `images_360` json DEFAULT NULL,
  `meta_titre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `mots_cles` json DEFAULT NULL,
  `surface` decimal(8,2) DEFAULT NULL,
  `equipements` json DEFAULT NULL,
  `services` json DEFAULT NULL,
  `prix_heure` decimal(8,2) DEFAULT NULL,
  `prix_journee` decimal(8,2) DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `images` json DEFAULT NULL,
  `statut` enum('actif','inactif','maintenance') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `valide_par_admin` tinyint(1) NOT NULL DEFAULT '0',
  `date_validation` timestamp NULL DEFAULT NULL,
  `motif_rejet` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tarifs` json DEFAULT NULL,
  `reservation_en_ligne` tinyint(1) NOT NULL DEFAULT '0',
  `paiement_en_ligne` tinyint(1) NOT NULL DEFAULT '0',
  `note_moyenne` decimal(3,2) NOT NULL DEFAULT '0.00',
  `nombre_avis` int NOT NULL DEFAULT '0',
  `nombre_vues` int NOT NULL DEFAULT '0',
  `nombre_favoris` int NOT NULL DEFAULT '0',
  `rayon_action_km` int DEFAULT NULL,
  `zones_couvertes` json DEFAULT NULL,
  `point_repere` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valide` tinyint(1) NOT NULL DEFAULT '0',
  `promoter_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `validated_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `salles_slug_unique` (`slug`),
  KEY `salles_ville_statut_index` (`ville`,`statut`),
  KEY `salles_promoter_id_statut_index` (`promoter_id`,`statut`),
  KEY `salles_slug_index` (`slug`),
  KEY `salles_categorie_type_index` (`categorie`,`type`),
  KEY `salles_statut_valide_par_admin_index` (`statut`,`valide_par_admin`),
  KEY `salles_pays_ville_index` (`pays`,`ville`),
  KEY `salles_validated_by_foreign` (`validated_by`),
  CONSTRAINT `salles_promoter_id_foreign` FOREIGN KEY (`promoter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `salles_validated_by_foreign` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tables qui dépendent de users et salles
CREATE TABLE IF NOT EXISTS `evenements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'brouillon',
  `salle_id` bigint unsigned NOT NULL,
  `organisateur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `participants_max` int NOT NULL DEFAULT '0',
  `participants_actuels` int NOT NULL DEFAULT '0',
  `prix` decimal(8,2) DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evenements_statut_date_debut_index` (`statut`,`date_debut`),
  KEY `evenements_salle_id_index` (`salle_id`),
  CONSTRAINT `evenements_salle_id_foreign` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `salle_id` bigint unsigned NOT NULL,
  `promoter_id` bigint unsigned NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_affiche` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_banniere` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lieu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_postal` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pays` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `date_limite_inscription` date DEFAULT NULL,
  `prix_base` decimal(10,2) NOT NULL DEFAULT '0.00',
  `prix_vip` decimal(10,2) DEFAULT NULL,
  `prix_groupe` decimal(10,2) DEFAULT NULL,
  `devise` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'XOF',
  `gratuit` tinyint(1) NOT NULL DEFAULT '0',
  `capacite_max` int DEFAULT NULL,
  `places_disponibles` int DEFAULT NULL,
  `limite_inscription` tinyint(1) NOT NULL DEFAULT '0',
  `categorie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tags` json DEFAULT NULL,
  `public_cible` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age_minimum` int DEFAULT NULL,
  `programme` json DEFAULT NULL,
  `activites` json DEFAULT NULL,
  `prix_speciaux` json DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reseaux_sociaux` json DEFAULT NULL,
  `site_web` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inscription_obligatoire` tinyint(1) NOT NULL DEFAULT '1',
  `paiement_en_ligne` tinyint(1) NOT NULL DEFAULT '0',
  `certificat_participation` tinyint(1) NOT NULL DEFAULT '0',
  `streaming` tinyint(1) NOT NULL DEFAULT '0',
  `url_streaming` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` enum('brouillon','publie','annule','termine') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'brouillon',
  `visibilite` enum('public','prive','invite_seulement') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `mis_en_avant` tinyint(1) NOT NULL DEFAULT '0',
  `meta_titre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `mots_cles` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `events_slug_unique` (`slug`),
  KEY `events_salle_id_date_debut_index` (`salle_id`,`date_debut`),
  KEY `events_promoter_id_statut_index` (`promoter_id`,`statut`),
  KEY `events_categorie_type_index` (`categorie`,`type`),
  KEY `events_date_debut_statut_index` (`date_debut`,`statut`),
  CONSTRAINT `events_promoter_id_foreign` FOREIGN KEY (`promoter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `events_salle_id_foreign` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `favorites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `salle_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `favorites_user_id_salle_id_unique` (`user_id`,`salle_id`),
  KEY `favorites_salle_id_foreign` (`salle_id`),
  CONSTRAINT `favorites_salle_id_foreign` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `inscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `date_inscription` datetime NOT NULL,
  `paiement_statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'non_paye',
  `montant_paye` decimal(8,2) NOT NULL DEFAULT '0.00',
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inscriptions_event_id_statut_index` (`event_id`,`statut`),
  KEY `inscriptions_user_id_event_id_index` (`user_id`,`event_id`),
  CONSTRAINT `inscriptions_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` bigint unsigned NOT NULL,
  `salle_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('general','event','promotion') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `status` enum('draft','sent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `sent_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_salle_id_foreign` (`salle_id`),
  KEY `messages_sender_id_salle_id_index` (`sender_id`,`salle_id`),
  KEY `messages_status_index` (`status`),
  CONSTRAINT `messages_salle_id_foreign` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `related_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `related_id` bigint unsigned DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_is_read_index` (`user_id`,`is_read`),
  KEY `notifications_related_type_related_id_index` (`related_type`,`related_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `promoter_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `main_user_id` bigint unsigned NOT NULL,
  `linked_user_id` bigint unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_active_account` (`main_user_id`,`is_active`),
  UNIQUE KEY `unique_promoter_account` (`main_user_id`,`linked_user_id`),
  KEY `promoter_accounts_main_user_id_is_active_index` (`main_user_id`,`is_active`),
  KEY `promoter_accounts_linked_user_id_index` (`linked_user_id`),
  CONSTRAINT `promoter_accounts_linked_user_id_foreign` FOREIGN KEY (`linked_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `promoter_accounts_main_user_id_foreign` FOREIGN KEY (`main_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `reservations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `salle_id` bigint unsigned NOT NULL,
  `date_heure` datetime NOT NULL,
  `duree` int NOT NULL,
  `nombre_personnes` int NOT NULL,
  `prix_total` decimal(10,2) NOT NULL,
  `statut` enum('en_attente','confirme','annule','termine') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `message` text COLLATE utf8mb4_unicode_ci,
  `promoter_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reservations_user_id_date_heure_index` (`user_id`,`date_heure`),
  KEY `reservations_salle_id_date_heure_index` (`salle_id`,`date_heure`),
  KEY `reservations_statut_index` (`statut`),
  CONSTRAINT `reservations_salle_id_foreign` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `standalone_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organizer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organizer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organizer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `standalone_events_created_by_foreign` (`created_by`),
  KEY `standalone_events_country_city_index` (`country`,`city`),
  KEY `standalone_events_event_date_index` (`event_date`),
  KEY `standalone_events_status_index` (`status`),
  CONSTRAINT `standalone_events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `salle_id` bigint unsigned NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscriptions_user_id_salle_id_unique` (`user_id`,`salle_id`),
  KEY `subscriptions_salle_id_status_index` (`salle_id`,`status`),
  CONSTRAINT `subscriptions_salle_id_foreign` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sub_admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `permissions` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sub_admins_user_id_foreign` (`user_id`),
  KEY `sub_admins_country_city_index` (`country`,`city`),
  CONSTRAINT `sub_admins_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tables manquantes pour le système de messagerie
CREATE TABLE IF NOT EXISTS `conversations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reservation_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL COMMENT 'Client',
  `promoter_id` bigint unsigned NOT NULL COMMENT 'Promoteur',
  `last_message_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `conversations_user_id_promoter_id_index` (`user_id`,`promoter_id`),
  KEY `conversations_reservation_id_index` (`reservation_id`),
  KEY `conversations_last_message_at_index` (`last_message_at`),
  CONSTRAINT `conversations_promoter_id_foreign` FOREIGN KEY (`promoter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conversations_reservation_id_foreign` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conversations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `conversation_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint unsigned NOT NULL,
  `sender_id` bigint unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `conversation_messages_conversation_id_index` (`conversation_id`),
  KEY `conversation_messages_sender_id_index` (`sender_id`),
  CONSTRAINT `conversation_messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conversation_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
