<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200407192641 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE exchange_request (id INT AUTO_INCREMENT NOT NULL, target_id INT NOT NULL, user_id INT NOT NULL, message VARCHAR(255) DEFAULT NULL, INDEX IDX_7C5D591E158E0B66 (target_id), UNIQUE INDEX UNIQ_7C5D591EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exchange_request ADD CONSTRAINT FK_7C5D591E158E0B66 FOREIGN KEY (target_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE exchange_request ADD CONSTRAINT FK_7C5D591EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offer ADD name VARCHAR(60) NOT NULL');
        $this->addSql('ALTER TABLE tag ADD is_valid TINYINT(1) DEFAULT 0');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE exchange_request');
        $this->addSql('ALTER TABLE offer DROP name');
        $this->addSql('ALTER TABLE tag DROP is_valid');
    }
}
