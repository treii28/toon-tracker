--
-- File generated with SQLiteStudio v3.4.4 on Tue Nov 12 17:05:56 2024
--
-- Text encoding used: UTF-8
--
PRAGMA foreign_keys = off;
BEGIN TRANSACTION;

-- Table: users
CREATE TABLE IF NOT EXISTS "users" ("id" integer primary key autoincrement not null, "name" varchar not null, "email" varchar not null, "email_verified_at" datetime, "password" varchar not null, "remember_token" varchar, "created_at" datetime, "updated_at" datetime);
INSERT INTO users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (1, 'Scott Webster Wood', 'treii28@gmail.com', '2024-11-04 12:11:55', '$2y$12$VnFirl41xi6Vtjd9JzokfunU6zReL3CZy90YGuh65iWgkU.jmzoau', 'PJ7NF8GbV230mmgXTe2PqEXaD011PP2jrjnO99wYzoTRDh42SrBiR5lu7wnB', '2024-11-04 12:11:55', '2024-11-04 14:05:24');
INSERT INTO users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (2, 'Catherine Lynn Smith', 'klynntg@hotmail.com', '2024-11-04 12:11:55', '$2y$12$tAZpBszGGadKnSc/hnVWWeXUruCrVhFP5LdC5wF/oNmaVPLKfMZLK', NULL, '2024-11-04 12:49:49', '2024-11-04 14:06:14');
INSERT INTO users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) VALUES (3, 'Toontracker Admin', 'swood@wijg.org', NULL, '$2y$12$cvXUUc2xK69cjbk91n5RJOHFqUvWc.eTY3SQJlUkNBCSSmp0d.jMO', NULL, '2024-11-04 14:08:01', '2024-11-04 14:08:01');

-- Index: users_email_unique
CREATE UNIQUE INDEX IF NOT EXISTS "users_email_unique" on "users" ("email");

COMMIT TRANSACTION;
PRAGMA foreign_keys = on;
