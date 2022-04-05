<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211218164300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE building (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, floor_area TINYTEXT NOT NULL, living_area TINYTEXT NOT NULL, existing_floor_area VARCHAR(255) NOT NULL, low_floor TINYTEXT NOT NULL, low_floor_thermal VARCHAR(255) NOT NULL, high_floor TINYTEXT NOT NULL, high_floor_thermal VARCHAR(255) NOT NULL, intermediate_floor LONGTEXT NOT NULL, intermediate_floor_thermal VARCHAR(255) NOT NULL, facades LONGTEXT NOT NULL, particular_walls LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_E16F61D4166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE building ADD CONSTRAINT FK_E16F61D4166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE building');
    }
}
