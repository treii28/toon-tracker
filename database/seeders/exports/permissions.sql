--
-- File generated with SQLiteStudio v3.4.4 on Tue Nov 12 17:04:00 2024
--
-- Text encoding used: UTF-8
--
PRAGMA foreign_keys = off;
BEGIN TRANSACTION;

-- Table: permissions
CREATE TABLE IF NOT EXISTS "permissions" ("id" integer primary key autoincrement not null, "name" varchar not null, "guard_name" varchar not null, "created_at" datetime, "updated_at" datetime);
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (1, 'view_item', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (2, 'view_any_item', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (3, 'create_item', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (4, 'update_item', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (5, 'restore_item', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (6, 'restore_any_item', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (7, 'replicate_item', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (8, 'reorder_item', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (9, 'delete_item', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (10, 'delete_any_item', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (11, 'force_delete_item', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (12, 'force_delete_any_item', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (13, 'view_laravel::route', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (14, 'view_any_laravel::route', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (15, 'create_laravel::route', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (16, 'update_laravel::route', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (17, 'restore_laravel::route', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (18, 'restore_any_laravel::route', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (19, 'replicate_laravel::route', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (20, 'reorder_laravel::route', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (21, 'delete_laravel::route', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (22, 'delete_any_laravel::route', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (23, 'force_delete_laravel::route', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (24, 'force_delete_any_laravel::route', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (25, 'view_need', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (26, 'view_any_need', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (27, 'create_need', 'web', '2024-11-04 14:00:31', '2024-11-04 14:00:31');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (28, 'update_need', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (29, 'restore_need', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (30, 'restore_any_need', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (31, 'replicate_need', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (32, 'reorder_need', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (33, 'delete_need', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (34, 'delete_any_need', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (35, 'force_delete_need', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (36, 'force_delete_any_need', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (37, 'view_role', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (38, 'view_any_role', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (39, 'create_role', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (40, 'update_role', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (41, 'delete_role', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (42, 'delete_any_role', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (43, 'view_toon', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (44, 'view_any_toon', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (45, 'create_toon', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (46, 'update_toon', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (47, 'restore_toon', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (48, 'restore_any_toon', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (49, 'replicate_toon', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (50, 'reorder_toon', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (51, 'delete_toon', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (52, 'delete_any_toon', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (53, 'force_delete_toon', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (54, 'force_delete_any_toon', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (55, 'view_user', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (56, 'view_any_user', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (57, 'create_user', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (58, 'update_user', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (59, 'restore_user', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (60, 'restore_any_user', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (61, 'replicate_user', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (62, 'reorder_user', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (63, 'delete_user', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (64, 'delete_any_user', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (65, 'force_delete_user', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');
INSERT INTO permissions (id, name, guard_name, created_at, updated_at) VALUES (66, 'force_delete_any_user', 'web', '2024-11-04 14:00:32', '2024-11-04 14:00:32');

-- Index: permissions_name_guard_name_unique
CREATE UNIQUE INDEX IF NOT EXISTS "permissions_name_guard_name_unique" on "permissions" ("name", "guard_name");

COMMIT TRANSACTION;
PRAGMA foreign_keys = on;
