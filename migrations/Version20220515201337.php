<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220515201337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course_week (id INT AUTO_INCREMENT NOT NULL, course_id INT DEFAULT NULL, INDEX IDX_93F8B4C6591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lecture (id INT AUTO_INCREMENT NOT NULL, course_week_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, video LONGTEXT DEFAULT NULL, file LONGTEXT DEFAULT NULL, INDEX IDX_C1677948C0AC3EC3 (course_week_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lecture_instructor (lecture_id INT NOT NULL, instructor_id INT NOT NULL, INDEX IDX_BA6E4E9935E32FCD (lecture_id), INDEX IDX_BA6E4E998C4FC193 (instructor_id), PRIMARY KEY(lecture_id, instructor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course_week ADD CONSTRAINT FK_93F8B4C6591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE lecture ADD CONSTRAINT FK_C1677948C0AC3EC3 FOREIGN KEY (course_week_id) REFERENCES course_week (id)');
        $this->addSql('ALTER TABLE lecture_instructor ADD CONSTRAINT FK_BA6E4E9935E32FCD FOREIGN KEY (lecture_id) REFERENCES lecture (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lecture_instructor ADD CONSTRAINT FK_BA6E4E998C4FC193 FOREIGN KEY (instructor_id) REFERENCES instructor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE assignment ADD course_week_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE assignment ADD CONSTRAINT FK_30C544BAC0AC3EC3 FOREIGN KEY (course_week_id) REFERENCES course_week (id)');
        $this->addSql('CREATE INDEX IDX_30C544BAC0AC3EC3 ON assignment (course_week_id)');
        $this->addSql('DROP INDEX title ON course');
        $this->addSql('DROP INDEX title_2 ON course');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assignment DROP FOREIGN KEY FK_30C544BAC0AC3EC3');
        $this->addSql('ALTER TABLE lecture DROP FOREIGN KEY FK_C1677948C0AC3EC3');
        $this->addSql('ALTER TABLE lecture_instructor DROP FOREIGN KEY FK_BA6E4E9935E32FCD');
        $this->addSql('DROP TABLE course_week');
        $this->addSql('DROP TABLE lecture');
        $this->addSql('DROP TABLE lecture_instructor');
        $this->addSql('DROP INDEX IDX_30C544BAC0AC3EC3 ON assignment');
        $this->addSql('ALTER TABLE assignment DROP course_week_id');
        $this->addSql('CREATE FULLTEXT INDEX title ON course (title, description)');
        $this->addSql('CREATE FULLTEXT INDEX title_2 ON course (title)');
    }
}
