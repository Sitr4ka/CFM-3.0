<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240313205719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE churches ADD incomes INT DEFAULT NULL, ADD outgoing INT DEFAULT NULL');
        $this->addSql('ALTER TABLE incomes DROP total');
        $this->addSql('ALTER TABLE outcomes DROP total');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE outcomes ADD total INT DEFAULT NULL');
        $this->addSql('ALTER TABLE churches DROP incomes, DROP outgoing');
        $this->addSql('ALTER TABLE incomes ADD total INT DEFAULT NULL');
    }
}
