<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210405114449 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C948D5142');
        $this->addSql('CREATE TABLE participation_t (id INT AUTO_INCREMENT NOT NULL, tournoi_id INT NOT NULL, user_t_id INT NOT NULL, INDEX IDX_12779D8AF607770A (tournoi_id), INDEX IDX_12779D8A2698B83C (user_t_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participation_t ADD CONSTRAINT FK_12779D8AF607770A FOREIGN KEY (tournoi_id) REFERENCES tournoi (id)');
        $this->addSql('ALTER TABLE participation_t ADD CONSTRAINT FK_12779D8A2698B83C FOREIGN KEY (user_t_id) REFERENCES utilisateur (id)');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE post');
        $this->addSql('ALTER TABLE tournoi ADD user_t_id INT DEFAULT NULL, ADD lien_youtube VARCHAR(255) NOT NULL, ADD active INT NOT NULL');
        $this->addSql('ALTER TABLE tournoi ADD CONSTRAINT FK_18AFD9DF2698B83C FOREIGN KEY (user_t_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_18AFD9DF2698B83C ON tournoi (user_t_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, idpost_id INT DEFAULT NULL, contenue VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date DATE NOT NULL, INDEX IDX_9474526C79F37AE5 (id_user_id), INDEX IDX_9474526C948D5142 (idpost_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, contenu VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, date DATE NOT NULL, image TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, nbrvue INT DEFAULT NULL, INDEX IDX_5A8A6C8D79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C79F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C948D5142 FOREIGN KEY (idpost_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D79F37AE5 FOREIGN KEY (id_user_id) REFERENCES utilisateur (id)');
        $this->addSql('DROP TABLE participation_t');
        $this->addSql('ALTER TABLE tournoi DROP FOREIGN KEY FK_18AFD9DF2698B83C');
        $this->addSql('DROP INDEX IDX_18AFD9DF2698B83C ON tournoi');
        $this->addSql('ALTER TABLE tournoi DROP user_t_id, DROP lien_youtube, DROP active');
    }
}
