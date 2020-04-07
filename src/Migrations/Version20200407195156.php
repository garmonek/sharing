<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200407195156 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE exchange_request_offer (exchange_request_id INT NOT NULL, offer_id INT NOT NULL, INDEX IDX_CBBDE3B8E5A8062 (exchange_request_id), INDEX IDX_CBBDE3B853C674EE (offer_id), PRIMARY KEY(exchange_request_id, offer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exchange_request_offer ADD CONSTRAINT FK_CBBDE3B8E5A8062 FOREIGN KEY (exchange_request_id) REFERENCES exchange_request (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE exchange_request_offer ADD CONSTRAINT FK_CBBDE3B853C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE exchange_request_offer');
    }
}
