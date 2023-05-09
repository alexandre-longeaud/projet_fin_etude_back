<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509084342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture ADD picture_of_the_week_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F89F69C3A46 FOREIGN KEY (picture_of_the_week_id) REFERENCES picture_of_the_week (id)');
        $this->addSql('CREATE INDEX IDX_16DB4F89F69C3A46 ON picture (picture_of_the_week_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F89F69C3A46');
        $this->addSql('DROP INDEX IDX_16DB4F89F69C3A46 ON picture');
        $this->addSql('ALTER TABLE picture DROP picture_of_the_week_id');
    }
}
