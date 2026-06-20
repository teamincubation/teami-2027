-- MySQL/MariaDB Base Schema for Team Incubation NGO Platform
-- Collation: UTF8MB4 for full internationalization and emoji support

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Roles Table
CREATE TABLE IF NOT EXISTS `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) UNIQUE NOT NULL,
    `description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Permissions Table
CREATE TABLE IF NOT EXISTS `permissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) UNIQUE NOT NULL,
    `description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Role Permissions Mapping Table
CREATE TABLE IF NOT EXISTS `role_permissions` (
    `role_id` INT NOT NULL,
    `permission_id` INT NOT NULL,
    PRIMARY KEY (`role_id`, `permission_id`),
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Users Table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(191) UNIQUE NOT NULL,
    `password_hash` VARCHAR(255) NULL, -- Null if Google OAuth only
    `google_id` VARCHAR(255) UNIQUE NULL,
    `role_id` INT NOT NULL,
    `status` ENUM('pending_verification', 'active', 'suspended') DEFAULT 'pending_verification',
    `email_verified_at` TIMESTAMP NULL,
    `verification_token_hash` VARCHAR(64) NULL,
    `reset_token_hash` VARCHAR(64) NULL,
    `reset_token_expires_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT,
    INDEX `idx_users_role` (`role_id`),
    INDEX `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Profiles Table (Incubant Details)
CREATE TABLE IF NOT EXISTS `profiles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNIQUE NOT NULL,
    `full_name` VARCHAR(255) NOT NULL,
    `mobile` VARCHAR(20) UNIQUE NOT NULL,
    `gender` ENUM('Male', 'Female', 'Other') NULL,
    `dob` DATE NULL,
    `profile_photo` VARCHAR(255) NULL,
    `address` TEXT NULL,
    `pincode` VARCHAR(10) NULL,
    `country` VARCHAR(100) DEFAULT 'India',
    `state` VARCHAR(100) NULL,
    `district` VARCHAR(100) NULL,
    `place` VARCHAR(100) NULL,
    `education` VARCHAR(255) NULL,
    `institution` VARCHAR(255) NULL,
    `occupation` VARCHAR(255) NULL,
    `interests` TEXT NULL,
    `skills` TEXT NULL,
    `biography` TEXT NULL,
    `social_links` JSON NULL, -- JSON for LinkedIn, Facebook, Twitter
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. User Sessions Table (Security Logs)
CREATE TABLE IF NOT EXISTS `user_sessions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `session_id` VARCHAR(255) NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` TEXT NOT NULL,
    `device_type` VARCHAR(50) NULL,
    `operating_system` VARCHAR(50) NULL,
    `browser` VARCHAR(50) NULL,
    `location_approx` VARCHAR(255) NULL, -- IP-derived location
    `auth_method` ENUM('password', 'google') NOT NULL,
    `last_activity` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `login_status` ENUM('success', 'failed') NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    INDEX `idx_sessions_user` (`user_id`),
    INDEX `idx_sessions_ip` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Remember Me Tokens Table
CREATE TABLE IF NOT EXISTS `remember_tokens` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `token_hash` VARCHAR(64) UNIQUE NOT NULL,
    `expires_at` TIMESTAMP NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    INDEX `idx_remember_token` (`token_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Projects Table
CREATE TABLE IF NOT EXISTS `projects` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE NOT NULL,
    `category` VARCHAR(100) NULL,
    `short_description` TEXT NOT NULL,
    `full_description` LONGTEXT NOT NULL,
    `objectives` TEXT NULL,
    `target_beneficiaries` TEXT NULL,
    `location` VARCHAR(255) NULL,
    `start_date` DATE NULL,
    `end_date` DATE NULL,
    `status` ENUM('planning', 'active', 'completed', 'suspended') DEFAULT 'planning',
    `banner_image` VARCHAR(255) NULL,
    `featured_image` VARCHAR(255) NULL,
    `featured` BOOLEAN DEFAULT FALSE,
    `meta_title` VARCHAR(255) NULL,
    `meta_description` TEXT NULL,
    `meta_keywords` VARCHAR(255) NULL,
    `created_by` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    INDEX `idx_projects_slug` (`slug`),
    INDEX `idx_projects_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Events Table
CREATE TABLE IF NOT EXISTS `events` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `project_id` INT NULL,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE NOT NULL,
    `event_type` ENUM('UPCOMING', 'PAST') DEFAULT 'UPCOMING',
    `description` LONGTEXT NOT NULL,
    `banner` VARCHAR(255) NULL,
    `venue` VARCHAR(255) NULL,
    `online_platform` VARCHAR(100) NULL,
    `meeting_link` VARCHAR(500) NULL,
    `location` VARCHAR(255) NULL,
    `start_date` DATETIME NOT NULL,
    `end_date` DATETIME NOT NULL,
    `reg_open_date` DATETIME NULL,
    `reg_close_date` DATETIME NULL,
    `seat_limit` INT NULL,
    `waiting_list_enabled` BOOLEAN DEFAULT FALSE,
    `is_free` BOOLEAN DEFAULT TRUE,
    `fee` DECIMAL(10,2) DEFAULT 0.00,
    `eligibility` TEXT NULL,
    `certificate_eligible` BOOLEAN DEFAULT FALSE,
    `coordinators` TEXT NULL, -- JSON or comma-separated list of names/IDs
    `speakers` TEXT NULL, -- JSON or text list of names and titles
    `status` ENUM('draft', 'published', 'cancelled', 'completed') DEFAULT 'draft',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
    INDEX `idx_events_slug` (`slug`),
    INDEX `idx_events_type` (`event_type`),
    INDEX `idx_events_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Event Custom Registration Questions
CREATE TABLE IF NOT EXISTS `event_questions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `event_id` INT NOT NULL,
    `question_text` TEXT NOT NULL,
    `question_type` ENUM('text', 'textarea', 'select', 'checkbox', 'file') NOT NULL,
    `is_required` BOOLEAN DEFAULT TRUE,
    `options` TEXT NULL, -- Comma-separated options for select/checkbox
    FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Event Registrations Table
CREATE TABLE IF NOT EXISTS `event_registrations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `event_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `status` ENUM('submitted', 'approved', 'rejected', 'waitlisted') DEFAULT 'submitted',
    `answers` JSON NULL, -- Answers to custom event_questions
    `attendance_status` ENUM('present', 'absent', 'pending') DEFAULT 'pending',
    `feedback` TEXT NULL,
    `rating` INT NULL,
    `acknowledgement_file` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_user_event` (`event_id`, `user_id`),
    INDEX `idx_registrations_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Campaigns Table
CREATE TABLE IF NOT EXISTS `campaigns` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE NOT NULL,
    `type` ENUM('internal', 'collaborative') DEFAULT 'internal',
    `collaborating_organizations` TEXT NULL,
    `objectives` TEXT NULL,
    `description` LONGTEXT NOT NULL,
    `start_date` DATE NULL,
    `end_date` DATE NULL,
    `location` VARCHAR(255) NULL,
    `target_group` VARCHAR(255) NULL,
    `banner` VARCHAR(255) NULL,
    `volunteer_requirements` TEXT NULL,
    `target_stat` DECIMAL(12,2) DEFAULT 0.00,
    `achieved_stat` DECIMAL(12,2) DEFAULT 0.00,
    `coordinators` TEXT NULL,
    `status` ENUM('upcoming', 'active', 'completed') DEFAULT 'upcoming',
    `featured` BOOLEAN DEFAULT FALSE,
    `public_report` TEXT NULL,
    `meta_title` VARCHAR(255) NULL,
    `meta_description` TEXT NULL,
    `meta_keywords` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    INDEX `idx_campaigns_slug` (`slug`),
    INDEX `idx_campaigns_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Internship Opportunities Table
CREATE TABLE IF NOT EXISTS `internship_opportunities` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE NOT NULL,
    `mode` ENUM('online', 'offline', 'hybrid') NOT NULL,
    `description` LONGTEXT NOT NULL,
    `eligibility` TEXT NULL,
    `skills_required` TEXT NULL,
    `seat_limit` INT NULL,
    `deadline` DATE NOT NULL,
    `status` ENUM('active', 'closed') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_intern_opp_slug` (`slug`),
    INDEX `idx_intern_opp_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Internship Custom Questions Table
CREATE TABLE IF NOT EXISTS `internship_questions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `internship_opportunity_id` INT NOT NULL,
    `question_text` TEXT NOT NULL,
    `question_type` ENUM('text', 'textarea', 'select', 'checkbox', 'file') NOT NULL,
    `is_required` BOOLEAN DEFAULT TRUE,
    `options` TEXT NULL,
    FOREIGN KEY (`internship_opportunity_id`) REFERENCES `internship_opportunities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. Internship Applications Table (Full lifecycle tracking)
CREATE TABLE IF NOT EXISTS `internship_applications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `internship_opportunity_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `resume_file` VARCHAR(255) NOT NULL,
    `sop` TEXT NOT NULL, -- Statement of Purpose
    `answers` JSON NULL, -- JSON array of answers to questions
    `status` ENUM('Submitted', 'Under Review', 'Shortlisted', 'Interview Scheduled', 'Selected', 'Onboarding Pending', 'Active', 'On Hold', 'Completed', 'Discontinued', 'Rejected') DEFAULT 'Submitted',
    `interview_time` DATETIME NULL,
    `supervisor_id` INT NULL,
    `intern_id` VARCHAR(50) UNIQUE NULL, -- Formatted internally
    `project_id` INT NULL,
    `performance_rating` INT NULL,
    `supervisor_feedback` TEXT NULL,
    `intern_feedback` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`internship_opportunity_id`) REFERENCES `internship_opportunities` (`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
    INDEX `idx_intern_app_status` (`status`),
    INDEX `idx_intern_app_uid` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. Intern Task Tracking Table
CREATE TABLE IF NOT EXISTS `intern_tasks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `application_id` INT NOT NULL,
    `task_title` VARCHAR(255) NOT NULL,
    `task_description` TEXT NULL,
    `hours_completed` DECIMAL(5,2) DEFAULT 0.00,
    `submission_link` VARCHAR(255) NULL,
    `status` ENUM('assigned', 'completed', 'reviewed') DEFAULT 'assigned',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`application_id`) REFERENCES `internship_applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. Volunteer Applications Table
CREATE TABLE IF NOT EXISTS `volunteer_applications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `areas_of_interest` TEXT NULL,
    `skills` TEXT NULL,
    `availability` TEXT NULL,
    `preferred_projects` TEXT NULL,
    `languages` VARCHAR(255) NULL,
    `previous_experience` TEXT NULL,
    `resume_file` VARCHAR(255) NULL,
    `emergency_contact_name` VARCHAR(255) NOT NULL,
    `emergency_contact_phone` VARCHAR(20) NOT NULL,
    `consent_declared` BOOLEAN DEFAULT TRUE,
    `status` ENUM('submitted', 'approved', 'rejected') DEFAULT 'submitted',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_user_volunteer` (`user_id`),
    INDEX `idx_volunteer_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. Volunteer Active Tasks & Performance Tracking
CREATE TABLE IF NOT EXISTS `volunteer_tasks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `volunteer_id` INT NOT NULL,
    `project_id` INT NULL,
    `campaign_id` INT NULL,
    `event_id` INT NULL,
    `task_description` TEXT NOT NULL,
    `hours_completed` DECIMAL(5,2) DEFAULT 0.00,
    `contribution` TEXT NULL,
    `performance_notes` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`volunteer_id`) REFERENCES `volunteer_applications` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. Certificate Types Table
CREATE TABLE IF NOT EXISTS `certificate_types` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) UNIQUE NOT NULL, -- e.g. 'INTERNSHIP', 'VOLUNTEER', 'EVENT_PARTICIPATION'
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. Certificates Database (Stores relative storage keys)
CREATE TABLE IF NOT EXISTS `certificates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `certificate_number` VARCHAR(100) UNIQUE NOT NULL, -- Can contain strings, numbers, spaces, slashes
    `search_key` VARCHAR(100) UNIQUE NOT NULL, -- Normalized alphanumeric search key (no spaces/slashes)
    `filename_key` VARCHAR(100) UNIQUE NOT NULL, -- File-system safe key for filename mapping
    `holder_name` VARCHAR(255) NOT NULL,
    `type_id` INT NOT NULL,
    `issued_date` DATE NOT NULL,
    `status` ENUM('Active', 'Revoked', 'Expired', 'Replaced') DEFAULT 'Active',
    `file_path` VARCHAR(255) NOT NULL, -- Relative key path: e.g., 'certificates/2026/06/TI-101.pdf'
    `import_batch_id` VARCHAR(50) NULL,
    `associated_programme` VARCHAR(255) NULL,
    `metadata` JSON NULL, -- Dynamic values for template matching
    `qr_reference` VARCHAR(255) NULL,
    `version` INT DEFAULT 1,
    `parent_certificate_id` INT NULL, -- For replacement chains
    `created_by` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`type_id`) REFERENCES `certificate_types` (`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`parent_certificate_id`) REFERENCES `certificates` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    INDEX `idx_cert_search` (`search_key`),
    INDEX `idx_cert_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 21. Certificate HTML Templates (for dynamic rendering)
CREATE TABLE IF NOT EXISTS `certificate_templates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `type_id` INT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `html_template` LONGTEXT NOT NULL,
    `styles_json` TEXT NULL,
    `letterhead_image` VARCHAR(255) NULL,
    `signature_image` VARCHAR(255) NULL,
    `seal_image` VARCHAR(255) NULL,
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`type_id`) REFERENCES `certificate_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 22. Certificate Downloads Tracking (Audits logins, devices)
CREATE TABLE IF NOT EXISTS `certificate_downloads` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `certificate_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `download_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` TEXT NOT NULL,
    `device_type` VARCHAR(50) NULL,
    `operating_system` VARCHAR(50) NULL,
    `browser` VARCHAR(50) NULL,
    `file_format` ENUM('pdf', 'jpeg', 'png') NOT NULL,
    FOREIGN KEY (`certificate_id`) REFERENCES `certificates` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 23. Banners Table (Admins configure landing page sliders)
CREATE TABLE IF NOT EXISTS `banners` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NULL,
    `subtitle` TEXT NULL,
    `desktop_image` VARCHAR(255) NOT NULL,
    `mobile_image` VARCHAR(255) NULL,
    `cta_label` VARCHAR(50) NULL,
    `cta_url` VARCHAR(255) NULL,
    `display_location` VARCHAR(50) NOT NULL, -- e.g., 'home_hero', 'projects', 'events'
    `display_order` INT DEFAULT 0,
    `start_date` DATETIME NULL,
    `end_date` DATETIME NULL,
    `active` BOOLEAN DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 24. Legacy Milestones Table (for Journey Roadmap)
CREATE TABLE IF NOT EXISTS `legacy_milestones` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `year` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `image` VARCHAR(255) NULL,
    `impact_stats` VARCHAR(255) NULL,
    `project_id` INT NULL,
    `event_id` INT NULL,
    `display_order` INT DEFAULT 0,
    `active` BOOLEAN DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL,
    INDEX `idx_milestones_year` (`year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 25. Partners Table (Carousel)
CREATE TABLE IF NOT EXISTS `partners` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `logo` VARCHAR(255) NOT NULL,
    `website` VARCHAR(255) NULL,
    `category` VARCHAR(100) NULL,
    `description` TEXT NULL,
    `collaboration_start` DATE NULL,
    `collaboration_end` DATE NULL,
    `featured` BOOLEAN DEFAULT FALSE,
    `display_order` INT DEFAULT 0,
    `active` BOOLEAN DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 26. Team Members Table
CREATE TABLE IF NOT EXISTS `team_members` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `role` VARCHAR(100) NOT NULL,
    `photo` VARCHAR(255) NULL,
    `bio` TEXT NULL,
    `social_links` JSON NULL,
    `display_order` INT DEFAULT 0,
    `active` BOOLEAN DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 27. Reusable Gallery Table
CREATE TABLE IF NOT EXISTS `gallery` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `media_path` VARCHAR(255) NOT NULL,
    `caption` VARCHAR(255) NULL,
    `project_id` INT NULL,
    `event_id` INT NULL,
    `campaign_id` INT NULL,
    `display_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL,
    FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 28. Testimonials Table
CREATE TABLE IF NOT EXISTS `testimonials` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `author_name` VARCHAR(255) NOT NULL,
    `author_role` VARCHAR(255) NULL,
    `author_institution` VARCHAR(255) NULL,
    `author_photo` VARCHAR(255) NULL,
    `content` TEXT NOT NULL,
    `rating` INT DEFAULT 5,
    `active` BOOLEAN DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 29. News Updates / Blog Table
CREATE TABLE IF NOT EXISTS `news_updates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE NOT NULL,
    `content` LONGTEXT NOT NULL,
    `banner_image` VARCHAR(255) NULL,
    `published_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('draft', 'published') DEFAULT 'draft',
    `created_by` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    INDEX `idx_news_slug` (`slug`),
    INDEX `idx_news_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 30. Email Queue Table (Hostinger SMTP buffer)
CREATE TABLE IF NOT EXISTS `email_queue` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `recipient_email` VARCHAR(191) NOT NULL,
    `recipient_name` VARCHAR(255) NULL,
    `subject` VARCHAR(255) NOT NULL,
    `body_html` LONGTEXT NOT NULL,
    `body_text` LONGTEXT NULL,
    `status` ENUM('pending', 'processing', 'sent', 'failed') DEFAULT 'pending',
    `retry_count` INT DEFAULT 0,
    `max_retries` INT DEFAULT 3,
    `error_message` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `scheduled_at` TIMESTAMP NULL,
    `sent_at` TIMESTAMP NULL,
    INDEX `idx_email_status` (`status`),
    INDEX `idx_email_scheduled` (`scheduled_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 31. In-App Notifications
CREATE TABLE IF NOT EXISTS `in_app_notifications` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `link` VARCHAR(255) NULL,
    `is_read` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    INDEX `idx_notifications_user` (`user_id`, `is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 32. Newsletter Subscribers
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(191) UNIQUE NOT NULL,
    `status` ENUM('subscribed', 'unsubscribed') DEFAULT 'subscribed',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_newsletter_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 33. Contact Enquiries
CREATE TABLE IF NOT EXISTS `contact_enquiries` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(191) NOT NULL,
    `mobile` VARCHAR(20) NULL,
    `subject` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('unread', 'read', 'replied') DEFAULT 'unread',
    `reply_content` TEXT NULL,
    `replied_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_contact_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 34. Security Audit Logs Table
CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `action` VARCHAR(100) NOT NULL,
    `details` TEXT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 35. Core Media Registry Table
CREATE TABLE IF NOT EXISTS `media` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `filename` VARCHAR(255) NOT NULL,
    `original_name` VARCHAR(255) NOT NULL,
    `mime_type` VARCHAR(100) NOT NULL,
    `file_size` INT NOT NULL,
    `relative_path` VARCHAR(255) NOT NULL, -- Path relative to persistent media folder
    `alt_text` VARCHAR(255) NULL,
    `caption` VARCHAR(255) NULL,
    `created_by` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 36. Database Migration History Table
CREATE TABLE IF NOT EXISTS `migrations_history` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `migration_name` VARCHAR(255) UNIQUE NOT NULL,
    `executed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 37. Login Attempts Table (Rate Limiting)
CREATE TABLE IF NOT EXISTS `login_attempts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ip_address` VARCHAR(45) NOT NULL,
    `email` VARCHAR(191) NOT NULL,
    `attempted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 38. Certificate Verification Logs Table
CREATE TABLE IF NOT EXISTS `certificate_verification_logs` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ip_address` VARCHAR(45) NOT NULL,
    `certificate_number` VARCHAR(100) NOT NULL,
    `verified_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('found', 'not_found') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 39. Gallery Table
CREATE TABLE IF NOT EXISTS `gallery` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NULL,
    `caption` TEXT NULL,
    `image_path` VARCHAR(255) NOT NULL,
    `display_order` INT DEFAULT 0,
    `active` BOOLEAN DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
