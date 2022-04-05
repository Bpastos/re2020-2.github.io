<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220115165403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticket ADD finished_thermician_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3184F2EA3 FOREIGN KEY (finished_thermician_id) REFERENCES thermician (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3184F2EA3 ON ticket (finished_thermician_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3184F2EA3');
        $this->addSql('DROP INDEX IDX_97A0ADA3184F2EA3 ON ticket');
        $this->addSql('ALTER TABLE ticket DROP finished_thermician_id');
    }
}
