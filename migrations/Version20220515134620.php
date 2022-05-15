<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220515134620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, department_id INT NOT NULL, course_head_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_169E6FB9AE80F5DF (department_id), INDEX IDX_169E6FB9FCD96472 (course_head_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_instructor (course_id INT NOT NULL, instructor_id INT NOT NULL, INDEX IDX_EE1E0D5E591CC992 (course_id), INDEX IDX_EE1E0D5E8C4FC193 (instructor_id), PRIMARY KEY(course_id, instructor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE course_student (course_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_BFE0AADF591CC992 (course_id), INDEX IDX_BFE0AADFCB944F1A (student_id), PRIMARY KEY(course_id, student_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, faculty_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_CD1DE18A680CAB68 (faculty_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE faculty (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE instructor (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, department_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_31FC43DDA76ED395 (user_id), INDEX IDX_31FC43DDAE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule (id INT AUTO_INCREMENT NOT NULL, course_id INT DEFAULT NULL, INDEX IDX_5A3811FB591CC992 (course_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, department_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_B723AF33A76ED395 (user_id), INDEX IDX_B723AF33AE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE time (id INT AUTO_INCREMENT NOT NULL, schedule_id INT DEFAULT NULL, schedule_from DATETIME NOT NULL, schedule_to DATETIME NOT NULL, INDEX IDX_6F949845A40BC2D5 (schedule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB9FCD96472 FOREIGN KEY (course_head_id) REFERENCES instructor (id)');
        $this->addSql('ALTER TABLE course_instructor ADD CONSTRAINT FK_EE1E0D5E591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_instructor ADD CONSTRAINT FK_EE1E0D5E8C4FC193 FOREIGN KEY (instructor_id) REFERENCES instructor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_student ADD CONSTRAINT FK_BFE0AADF591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE course_student ADD CONSTRAINT FK_BFE0AADFCB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18A680CAB68 FOREIGN KEY (faculty_id) REFERENCES faculty (id)');
        $this->addSql('ALTER TABLE instructor ADD CONSTRAINT FK_31FC43DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE instructor ADD CONSTRAINT FK_31FC43DDAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE time ADD CONSTRAINT FK_6F949845A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_instructor DROP FOREIGN KEY FK_EE1E0D5E591CC992');
        $this->addSql('ALTER TABLE course_student DROP FOREIGN KEY FK_BFE0AADF591CC992');
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FB591CC992');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9AE80F5DF');
        $this->addSql('ALTER TABLE instructor DROP FOREIGN KEY FK_31FC43DDAE80F5DF');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33AE80F5DF');
        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18A680CAB68');
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB9FCD96472');
        $this->addSql('ALTER TABLE course_instructor DROP FOREIGN KEY FK_EE1E0D5E8C4FC193');
        $this->addSql('ALTER TABLE time DROP FOREIGN KEY FK_6F949845A40BC2D5');
        $this->addSql('ALTER TABLE course_student DROP FOREIGN KEY FK_BFE0AADFCB944F1A');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE course_instructor');
        $this->addSql('DROP TABLE course_student');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE faculty');
        $this->addSql('DROP TABLE instructor');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE time');
    }
}
