-- Tables manquantes à ajouter à votre base de données
-- Exécutez ces requêtes après votre script principal

-- Table conversations
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

-- Table conversation_messages
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
