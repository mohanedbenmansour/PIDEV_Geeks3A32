<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210405094634 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participation_t (id INT AUTO_INCREMENT NOT NULL, tournoi_id INT NOT NULL, user_t_id INT NOT NULL, INDEX IDX_12779D8AF607770A (tournoi_id), INDEX IDX_12779D8A2698B83C (user_t_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participation_t ADD CONSTRAINT FK_12779D8AF607770A FOREIGN KEY (tournoi_id) REFERENCES tournoi (id)');
        $this->addSql('ALTER TABLE participation_t ADD CONSTRAINT FK_12779D8A2698B83C FOREIGN KEY (user_t_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE tournoi ADD user_t_id INT DEFAULT NULL, ADD lien_youtube VARCHAR(255) NOT NULL, ADD active INT NOT NULL');
        $this->addSql('ALTER TABLE tournoi ADD CONSTRAINT FK_18AFD9DF2698B83C FOREIGN KEY (user_t_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_18AFD9DF2698B83C ON tournoi (user_t_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE participation_t');
        $this->addSql('ALTER TABLE tournoi DROP FOREIGN KEY FK_18AFD9DF2698B83C');
        $this->addSql('DROP INDEX IDX_18AFD9DF2698B83C ON tournoi');
        $this->addSql('ALTER TABLE tournoi DROP user_t_id, DROP lien_youtube, DROP active');
    }
}
