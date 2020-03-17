<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200314225321 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, UNIQUE INDEX name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE web_image (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, url VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_82E00C1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE district (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_31C154878BAC62AF (city_id), UNIQUE INDEX name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, district_id INT NOT NULL, active TINYINT(1) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_29D6873EA76ED395 (user_id), INDEX IDX_29D6873EB08FA272 (district_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_web_image (offer_id INT NOT NULL, web_image_id INT NOT NULL, INDEX IDX_ABEB7D0553C674EE (offer_id), INDEX IDX_ABEB7D052AFA3480 (web_image_id), PRIMARY KEY(offer_id, web_image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_image (offer_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_461079B653C674EE (offer_id), INDEX IDX_461079B63DA5256D (image_id), PRIMARY KEY(offer_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_tag (offer_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_2FBCD61B53C674EE (offer_id), INDEX IDX_2FBCD61BBAD26311 (tag_id), PRIMARY KEY(offer_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_exchange_tags (tag_id INT NOT NULL, offer_id INT NOT NULL, INDEX IDX_556E7BFBAD26311 (tag_id), UNIQUE INDEX UNIQ_556E7BF53C674EE (offer_id), PRIMARY KEY(tag_id, offer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, file VARCHAR(255) NOT NULL, alt VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C53D045FA76ED395 (user_id), UNIQUE INDEX file_idx (file), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE web_image ADD CONSTRAINT FK_82E00C1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE district ADD CONSTRAINT FK_31C154878BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EB08FA272 FOREIGN KEY (district_id) REFERENCES district (id)');
        $this->addSql('ALTER TABLE offer_web_image ADD CONSTRAINT FK_ABEB7D0553C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_web_image ADD CONSTRAINT FK_ABEB7D052AFA3480 FOREIGN KEY (web_image_id) REFERENCES web_image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_image ADD CONSTRAINT FK_461079B653C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_image ADD CONSTRAINT FK_461079B63DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_tag ADD CONSTRAINT FK_2FBCD61B53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_tag ADD CONSTRAINT FK_2FBCD61BBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer_exchange_tags ADD CONSTRAINT FK_556E7BFBAD26311 FOREIGN KEY (tag_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE offer_exchange_tags ADD CONSTRAINT FK_556E7BF53C674EE FOREIGN KEY (offer_id) REFERENCES tag (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE offer_tag DROP FOREIGN KEY FK_2FBCD61BBAD26311');
        $this->addSql('ALTER TABLE offer_exchange_tags DROP FOREIGN KEY FK_556E7BF53C674EE');
        $this->addSql('ALTER TABLE district DROP FOREIGN KEY FK_31C154878BAC62AF');
        $this->addSql('ALTER TABLE offer_web_image DROP FOREIGN KEY FK_ABEB7D052AFA3480');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EB08FA272');
        $this->addSql('ALTER TABLE offer_web_image DROP FOREIGN KEY FK_ABEB7D0553C674EE');
        $this->addSql('ALTER TABLE offer_image DROP FOREIGN KEY FK_461079B653C674EE');
        $this->addSql('ALTER TABLE offer_tag DROP FOREIGN KEY FK_2FBCD61B53C674EE');
        $this->addSql('ALTER TABLE offer_exchange_tags DROP FOREIGN KEY FK_556E7BFBAD26311');
        $this->addSql('ALTER TABLE offer_image DROP FOREIGN KEY FK_461079B63DA5256D');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE web_image');
        $this->addSql('DROP TABLE district');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE offer_web_image');
        $this->addSql('DROP TABLE offer_image');
        $this->addSql('DROP TABLE offer_tag');
        $this->addSql('DROP TABLE offer_exchange_tags');
        $this->addSql('DROP TABLE image');
    }
}
