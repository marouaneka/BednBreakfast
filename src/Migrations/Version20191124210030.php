<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191124210030 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_729F519B7E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__room AS SELECT id, owner_id, summary, description, capacity, superficy, price, address FROM room');
        $this->addSql('DROP TABLE room');
        $this->addSql('CREATE TABLE room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, summary CLOB DEFAULT NULL COLLATE BINARY, description CLOB DEFAULT NULL COLLATE BINARY, capacity INTEGER NOT NULL, superficy DOUBLE PRECISION DEFAULT NULL, price DOUBLE PRECISION NOT NULL, address CLOB NOT NULL COLLATE BINARY, image_name VARCHAR(255) DEFAULT NULL, image_updates_at DATETIME DEFAULT NULL, CONSTRAINT FK_729F519B7E3C61F9 FOREIGN KEY (owner_id) REFERENCES owner (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO room (id, owner_id, summary, description, capacity, superficy, price, address) SELECT id, owner_id, summary, description, capacity, superficy, price, address FROM __temp__room');
        $this->addSql('DROP TABLE __temp__room');
        $this->addSql('CREATE INDEX IDX_729F519B7E3C61F9 ON room (owner_id)');
        $this->addSql('DROP INDEX IDX_4E2C37B798260155');
        $this->addSql('DROP INDEX IDX_4E2C37B754177093');
        $this->addSql('CREATE TEMPORARY TABLE __temp__room_region AS SELECT room_id, region_id FROM room_region');
        $this->addSql('DROP TABLE room_region');
        $this->addSql('CREATE TABLE room_region (room_id INTEGER NOT NULL, region_id INTEGER NOT NULL, PRIMARY KEY(room_id, region_id), CONSTRAINT FK_4E2C37B754177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4E2C37B798260155 FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO room_region (room_id, region_id) SELECT room_id, region_id FROM __temp__room_region');
        $this->addSql('DROP TABLE __temp__room_region');
        $this->addSql('CREATE INDEX IDX_4E2C37B798260155 ON room_region (region_id)');
        $this->addSql('CREATE INDEX IDX_4E2C37B754177093 ON room_region (room_id)');
        $this->addSql('DROP INDEX IDX_A30392F954177093');
        $this->addSql('DROP INDEX IDX_A30392F919EB6921');
        $this->addSql('CREATE TEMPORARY TABLE __temp__client_room AS SELECT client_id, room_id FROM client_room');
        $this->addSql('DROP TABLE client_room');
        $this->addSql('CREATE TABLE client_room (client_id INTEGER NOT NULL, room_id INTEGER NOT NULL, PRIMARY KEY(client_id, room_id), CONSTRAINT FK_A30392F919EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A30392F954177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO client_room (client_id, room_id) SELECT client_id, room_id FROM __temp__client_room');
        $this->addSql('DROP TABLE __temp__client_room');
        $this->addSql('CREATE INDEX IDX_A30392F954177093 ON client_room (room_id)');
        $this->addSql('CREATE INDEX IDX_A30392F919EB6921 ON client_room (client_id)');
        $this->addSql('DROP INDEX IDX_5F9E962A19EB6921');
        $this->addSql('DROP INDEX IDX_5F9E962A54177093');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comments AS SELECT id, room_id, client_id, description, stars FROM comments');
        $this->addSql('DROP TABLE comments');
        $this->addSql('CREATE TABLE comments (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, room_id INTEGER DEFAULT NULL, client_id INTEGER DEFAULT NULL, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, stars INTEGER NOT NULL, CONSTRAINT FK_5F9E962A54177093 FOREIGN KEY (room_id) REFERENCES room (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5F9E962A19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO comments (id, room_id, client_id, description, stars) SELECT id, room_id, client_id, description, stars FROM __temp__comments');
        $this->addSql('DROP TABLE __temp__comments');
        $this->addSql('CREATE INDEX IDX_5F9E962A19EB6921 ON comments (client_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A54177093 ON comments (room_id)');
        $this->addSql('DROP INDEX IDX_42C849557654247D');
        $this->addSql('DROP INDEX IDX_42C84955B51000ED');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reservation AS SELECT id, res_client_id, res_room_id, date_debut, date_fin FROM reservation');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('CREATE TABLE reservation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, res_client_id INTEGER DEFAULT NULL, res_room_id INTEGER DEFAULT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, CONSTRAINT FK_42C84955B51000ED FOREIGN KEY (res_client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_42C849557654247D FOREIGN KEY (res_room_id) REFERENCES room (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO reservation (id, res_client_id, res_room_id, date_debut, date_fin) SELECT id, res_client_id, res_room_id, date_debut, date_fin FROM __temp__reservation');
        $this->addSql('DROP TABLE __temp__reservation');
        $this->addSql('CREATE INDEX IDX_42C849557654247D ON reservation (res_room_id)');
        $this->addSql('CREATE INDEX IDX_42C84955B51000ED ON reservation (res_client_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649D2BFC5E1');
        $this->addSql('DROP INDEX UNIQ_8D93D6495AC6E67F');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, auth_owner_id, client_auth_id, email, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, auth_owner_id INTEGER DEFAULT NULL, client_auth_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL COLLATE BINARY, roles CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        , password VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_8D93D6495AC6E67F FOREIGN KEY (auth_owner_id) REFERENCES owner (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_8D93D649D2BFC5E1 FOREIGN KEY (client_auth_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, auth_owner_id, client_auth_id, email, roles, password) SELECT id, auth_owner_id, client_auth_id, email, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649D2BFC5E1 ON user (client_auth_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6495AC6E67F ON user (auth_owner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_A30392F919EB6921');
        $this->addSql('DROP INDEX IDX_A30392F954177093');
        $this->addSql('CREATE TEMPORARY TABLE __temp__client_room AS SELECT client_id, room_id FROM client_room');
        $this->addSql('DROP TABLE client_room');
        $this->addSql('CREATE TABLE client_room (client_id INTEGER NOT NULL, room_id INTEGER NOT NULL, PRIMARY KEY(client_id, room_id))');
        $this->addSql('INSERT INTO client_room (client_id, room_id) SELECT client_id, room_id FROM __temp__client_room');
        $this->addSql('DROP TABLE __temp__client_room');
        $this->addSql('CREATE INDEX IDX_A30392F919EB6921 ON client_room (client_id)');
        $this->addSql('CREATE INDEX IDX_A30392F954177093 ON client_room (room_id)');
        $this->addSql('DROP INDEX IDX_5F9E962A54177093');
        $this->addSql('DROP INDEX IDX_5F9E962A19EB6921');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comments AS SELECT id, room_id, client_id, description, stars FROM comments');
        $this->addSql('DROP TABLE comments');
        $this->addSql('CREATE TABLE comments (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, room_id INTEGER DEFAULT NULL, client_id INTEGER DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, stars INTEGER NOT NULL)');
        $this->addSql('INSERT INTO comments (id, room_id, client_id, description, stars) SELECT id, room_id, client_id, description, stars FROM __temp__comments');
        $this->addSql('DROP TABLE __temp__comments');
        $this->addSql('CREATE INDEX IDX_5F9E962A54177093 ON comments (room_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A19EB6921 ON comments (client_id)');
        $this->addSql('DROP INDEX IDX_42C84955B51000ED');
        $this->addSql('DROP INDEX IDX_42C849557654247D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reservation AS SELECT id, res_client_id, res_room_id, date_debut, date_fin FROM reservation');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('CREATE TABLE reservation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, res_client_id INTEGER DEFAULT NULL, res_room_id INTEGER DEFAULT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL)');
        $this->addSql('INSERT INTO reservation (id, res_client_id, res_room_id, date_debut, date_fin) SELECT id, res_client_id, res_room_id, date_debut, date_fin FROM __temp__reservation');
        $this->addSql('DROP TABLE __temp__reservation');
        $this->addSql('CREATE INDEX IDX_42C84955B51000ED ON reservation (res_client_id)');
        $this->addSql('CREATE INDEX IDX_42C849557654247D ON reservation (res_room_id)');
        $this->addSql('DROP INDEX IDX_729F519B7E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__room AS SELECT id, owner_id, summary, description, capacity, superficy, price, address FROM room');
        $this->addSql('DROP TABLE room');
        $this->addSql('CREATE TABLE room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, summary CLOB DEFAULT NULL, description CLOB DEFAULT NULL, capacity INTEGER NOT NULL, superficy DOUBLE PRECISION DEFAULT NULL, price DOUBLE PRECISION NOT NULL, address CLOB NOT NULL)');
        $this->addSql('INSERT INTO room (id, owner_id, summary, description, capacity, superficy, price, address) SELECT id, owner_id, summary, description, capacity, superficy, price, address FROM __temp__room');
        $this->addSql('DROP TABLE __temp__room');
        $this->addSql('CREATE INDEX IDX_729F519B7E3C61F9 ON room (owner_id)');
        $this->addSql('DROP INDEX IDX_4E2C37B754177093');
        $this->addSql('DROP INDEX IDX_4E2C37B798260155');
        $this->addSql('CREATE TEMPORARY TABLE __temp__room_region AS SELECT room_id, region_id FROM room_region');
        $this->addSql('DROP TABLE room_region');
        $this->addSql('CREATE TABLE room_region (room_id INTEGER NOT NULL, region_id INTEGER NOT NULL, PRIMARY KEY(room_id, region_id))');
        $this->addSql('INSERT INTO room_region (room_id, region_id) SELECT room_id, region_id FROM __temp__room_region');
        $this->addSql('DROP TABLE __temp__room_region');
        $this->addSql('CREATE INDEX IDX_4E2C37B754177093 ON room_region (room_id)');
        $this->addSql('CREATE INDEX IDX_4E2C37B798260155 ON room_region (region_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74');
        $this->addSql('DROP INDEX UNIQ_8D93D6495AC6E67F');
        $this->addSql('DROP INDEX UNIQ_8D93D649D2BFC5E1');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, auth_owner_id, client_auth_id, email, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, auth_owner_id INTEGER DEFAULT NULL, client_auth_id INTEGER DEFAULT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, auth_owner_id, client_auth_id, email, roles, password) SELECT id, auth_owner_id, client_auth_id, email, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6495AC6E67F ON user (auth_owner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649D2BFC5E1 ON user (client_auth_id)');
    }
}
