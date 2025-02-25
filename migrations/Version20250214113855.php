<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214113855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('DROP TABLE spendings');
        $this->addSql('ALTER INDEX uniq_name RENAME TO UNIQ_1483A5E95E237E06');
        $this->addSql('DROP INDEX title_unique');
        $this->addSql('ALTER TABLE movies DROP CONSTRAINT movies_pkey');
        $this->addSql('ALTER TABLE movies RENAME COLUMN imdbid TO imdb_id');
        $this->addSql('ALTER TABLE movies ADD PRIMARY KEY (imdb_id, username, title)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE spendings (month VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, income INT NOT NULL, rentcosts INT NOT NULL, sidecosts INT NOT NULL, foodanddrinkscosts INT NOT NULL, hobbycosts INT NOT NULL, savingscosts INT NOT NULL, mobilitycosts INT NOT NULL, insurancecosts INT NOT NULL, PRIMARY KEY(month, username))');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER INDEX uniq_1483a5e95e237e06 RENAME TO uniq_name');
        $this->addSql('DROP INDEX movies_pkey');
        $this->addSql('ALTER TABLE movies RENAME COLUMN imdb_id TO imdbid');
        $this->addSql('CREATE UNIQUE INDEX title_unique ON movies (title)');
        $this->addSql('ALTER TABLE movies ADD PRIMARY KEY (imdbid, username, title)');
    }
}
