<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509075925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag_picture (tag_id INT NOT NULL, picture_id INT NOT NULL, INDEX IDX_24EA6223BAD26311 (tag_id), INDEX IDX_24EA6223EE45BDBF (picture_id), PRIMARY KEY(tag_id, picture_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag_picture ADD CONSTRAINT FK_24EA6223BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_picture ADD CONSTRAINT FK_24EA6223EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_picture DROP FOREIGN KEY FK_24EA6223BAD26311');
        $this->addSql('ALTER TABLE tag_picture DROP FOREIGN KEY FK_24EA6223EE45BDBF');
        $this->addSql('DROP TABLE tag_picture');
    }
}
