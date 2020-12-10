<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191114165055 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE reservation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, res_client_id INTEGER DEFAULT NULL, res_room_id INTEGER DEFAULT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL)');
        $this->addSql('CREATE INDEX IDX_42C84955B51000ED ON reservation (res_client_id)');
        $this->addSql('CREATE INDEX IDX_42C849557654247D ON reservation (res_room_id)');
        $this->addSql('DROP INDEX IDX_729F519B7E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__room AS SELECT id, owner_id, summary, description, capacity, superficy, price, address FROM room');
        $this->addSql('DROP TABLE room');
        $this->addSql('CREATE TABLE room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, summary CLOB DEFAULT NULL COLLATE BINARY, description CLOB DEFAULT NULL COLLATE BINARY, capacity INTEGER NOT NULL, superficy DOUBLE PRECISION DEFAULT NULL, price DOUBLE PRECISION NOT NULL, address CLOB NOT NULL COLLATE BINARY, CONSTRAINT FK_729F519B7E3C61F9 FOREIGN KEY (owner_id) REFERENCES owner (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO room (id, owner_id, summary, description, capacity, superficy, price, address) SELECT id, owner_id, summary, description, capacity, superficy, price, address FROM __temp__room');
        $this->addSql('DROP TABLE __temp__room');
        $this->addSql('CREATE INDEX IDX_729F519B7E3C61F9 ON room (owner_id)');
        $this->addSql('DROP INDEX IDX_AB3946AC54177093');
        $this->addSql('DROP INDEX IDX_AB3946AC98260155');
        $this->addSql('CREATE TEMPORARY TABLE __temp__region_room AS SELECT region_id, room_id FROM region_room');
        $this->addSql('DROP TABLE region_room');
        $this->addSql('CREATE TABLE region_room (region_id INTEGER NOT NULL, room_id INTEGER NOT NULL, PRIMARY KEY(region_id, room_id), CONSTRAINT FK_AB3946AC98260155 FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_AB3946AC54177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO region_room (region_id, room_id) SELECT region_id, room_id FROM __temp__region_room');
        $this->addSql('DROP TABLE __temp__region_room');
        $this->addSql('CREATE INDEX IDX_AB3946AC54177093 ON region_room (room_id)');
        $this->addSql('CREATE INDEX IDX_AB3946AC98260155 ON region_room (region_id)');
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

        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP INDEX IDX_AB3946AC98260155');
        $this->addSql('DROP INDEX IDX_AB3946AC54177093');
        $this->addSql('CREATE TEMPORARY TABLE __temp__region_room AS SELECT region_id, room_id FROM region_room');
        $this->addSql('DROP TABLE region_room');
        $this->addSql('CREATE TABLE region_room (region_id INTEGER NOT NULL, room_id INTEGER NOT NULL, PRIMARY KEY(region_id, room_id))');
        $this->addSql('INSERT INTO region_room (region_id, room_id) SELECT region_id, room_id FROM __temp__region_room');
        $this->addSql('DROP TABLE __temp__region_room');
        $this->addSql('CREATE INDEX IDX_AB3946AC98260155 ON region_room (region_id)');
        $this->addSql('CREATE INDEX IDX_AB3946AC54177093 ON region_room (room_id)');
        $this->addSql('DROP INDEX IDX_729F519B7E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__room AS SELECT id, owner_id, summary, description, capacity, superficy, price, address FROM room');
        $this->addSql('DROP TABLE room');
        $this->addSql('CREATE TABLE room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, summary CLOB DEFAULT NULL, description CLOB DEFAULT NULL, capacity INTEGER NOT NULL, superficy DOUBLE PRECISION DEFAULT NULL, price DOUBLE PRECISION NOT NULL, address CLOB NOT NULL)');
        $this->addSql('INSERT INTO room (id, owner_id, summary, description, capacity, superficy, price, address) SELECT id, owner_id, summary, description, capacity, superficy, price, address FROM __temp__room');
        $this->addSql('DROP TABLE __temp__room');
        $this->addSql('CREATE INDEX IDX_729F519B7E3C61F9 ON room (owner_id)');
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
