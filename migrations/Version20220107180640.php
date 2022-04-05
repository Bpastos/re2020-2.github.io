<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220107180640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticket ADD old_thermician_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3CD9CE09F FOREIGN KEY (old_thermician_id) REFERENCES thermician (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_97A0ADA3CD9CE09F ON ticket (old_thermician_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3CD9CE09F');
        $this->addSql('DROP INDEX UNIQ_97A0ADA3CD9CE09F ON ticket');
        $this->addSql('ALTER TABLE ticket DROP old_thermician_id');
    }
}
