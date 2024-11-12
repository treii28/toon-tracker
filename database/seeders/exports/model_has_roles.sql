--
-- File generated with SQLiteStudio v3.4.4 on Tue Nov 12 17:05:24 2024
--
-- Text encoding used: UTF-8
--
PRAGMA foreign_keys = off;
BEGIN TRANSACTION;

-- Table: model_has_roles
CREATE TABLE IF NOT EXISTS "model_has_roles" ("role_id" integer not null, "model_type" varchar not null, "model_id" integer not null, foreign key("role_id") references "roles"("id") on delete cascade, primary key ("role_id", "model_id", "model_type"));
INSERT INTO model_has_roles (role_id, model_type, model_id) VALUES (2, 'App\Models\User', 1);
INSERT INTO model_has_roles (role_id, model_type, model_id) VALUES (2, 'App\Models\User', 2);
INSERT INTO model_has_roles (role_id, model_type, model_id) VALUES (1, 'App\Models\User', 3);

-- Index: model_has_roles_model_id_model_type_index
CREATE INDEX IF NOT EXISTS "model_has_roles_model_id_model_type_index" on "model_has_roles" ("model_id", "model_type");

-- Index: sqlite_autoindex_model_has_roles_1
CREATE UNIQUE INDEX IF NOT EXISTS sqlite_autoindex_model_has_roles_1 ON model_has_roles (role_id COLLATE BINARY, model_id COLLATE BINARY, model_type COLLATE BINARY);

COMMIT TRANSACTION;
PRAGMA foreign_keys = on;
