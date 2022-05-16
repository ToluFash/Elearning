<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220516092131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assignment ADD description LONGTEXT DEFAULT NULL');
        $this->addSql('DROP INDEX title_3 ON course');
        $this->addSql('DROP INDEX title ON course');
        $this->addSql('DROP INDEX title_4 ON course');
        $this->addSql('DROP INDEX description ON course');
        $this->addSql('DROP INDEX description_2 ON course');
        $this->addSql('DROP INDEX title_2 ON course');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assignment DROP description');
        $this->addSql('CREATE FULLTEXT INDEX title_3 ON course (title)');
        $this->addSql('CREATE FULLTEXT INDEX title ON course (title)');
        $this->addSql('CREATE FULLTEXT INDEX title_4 ON course (title)');
        $this->addSql('CREATE FULLTEXT INDEX description ON course (description)');
        $this->addSql('CREATE FULLTEXT INDEX description_2 ON course (description)');
        $this->addSql('CREATE FULLTEXT INDEX title_2 ON course (title)');
    }
}
