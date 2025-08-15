-- Admin Notifications Table
-- Compatible with MySQL 5.7+/8.0 (avoid strict JSON type for portability)

CREATE TABLE IF NOT EXISTS admin_notifications (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  type ENUM('system','user','security') NOT NULL DEFAULT 'system',
  title VARCHAR(200) NOT NULL,
  message TEXT NOT NULL,
  meta TEXT NULL,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_created_at (created_at),
  KEY idx_is_read (is_read),
  KEY idx_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
