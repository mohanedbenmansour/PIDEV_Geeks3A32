<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210311170529 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_detail ADD orderr_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_detail ADD CONSTRAINT FK_ED896F467742FDB3 FOREIGN KEY (orderr_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_ED896F467742FDB3 ON order_detail (orderr_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_detail DROP FOREIGN KEY FK_ED896F467742FDB3');
        $this->addSql('DROP INDEX IDX_ED896F467742FDB3 ON order_detail');
        $this->addSql('ALTER TABLE order_detail DROP orderr_id');
    }
}
