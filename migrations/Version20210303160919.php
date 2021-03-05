<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210303160919 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, date_pub DATE NOT NULL, lieu VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, date_event DATETIME DEFAULT NULL, user_id INT NOT NULL, url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, event_id_id INT NOT NULL, user_id INT NOT NULL, object_id INT NOT NULL, type VARCHAR(255) NOT NULL, date DATETIME NOT NULL, INDEX IDX_AB55E24F3E5F2F7B (event_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F3E5F2F7B FOREIGN KEY (event_id_id) REFERENCES event (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F3E5F2F7B');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE participation');
    }
}
