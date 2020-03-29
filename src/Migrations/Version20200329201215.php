<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200329201215 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE exchange_request (id INT AUTO_INCREMENT NOT NULL, target_id INT DEFAULT NULL, message VARCHAR(2000) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_7C5D591E158E0B66 (target_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exchange_request ADD CONSTRAINT FK_7C5D591E158E0B66 FOREIGN KEY (target_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE offer ADD proposed_in_exchange_requests_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E22A31C49 FOREIGN KEY (proposed_in_exchange_requests_id) REFERENCES exchange_request (id)');
        $this->addSql('CREATE INDEX IDX_29D6873E22A31C49 ON offer (proposed_in_exchange_requests_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E22A31C49');
        $this->addSql('DROP TABLE exchange_request');
        $this->addSql('DROP INDEX IDX_29D6873E22A31C49 ON offer');
        $this->addSql('ALTER TABLE offer DROP proposed_in_exchange_requests_id');
        $this->addSql('DROP INDEX UNIQ_556E7BF53C674EE ON offer_exchange_tags');
        $this->addSql('CREATE INDEX IDX_556E7BF53C674EE ON offer_exchange_tags (offer_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_556E7BF53C674EE ON offer_exchange_tags (offer_id, tag_id)');
    }
}
