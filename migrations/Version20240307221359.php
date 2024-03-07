<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307221359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE churches (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, design VARCHAR(50) NOT NULL, balance INT DEFAULT 0, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE incomes (id INT AUTO_INCREMENT NOT NULL, churches_id INT NOT NULL, executed_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', motif VARCHAR(30) NOT NULL, amount INT NOT NULL, INDEX IDX_9DE2B5BDDB612504 (churches_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE outcomes (id INT AUTO_INCREMENT NOT NULL, churches_id INT NOT NULL, executed_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', motif VARCHAR(30) NOT NULL, amount INT NOT NULL, INDEX IDX_6E54D0FADB612504 (churches_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE incomes ADD CONSTRAINT FK_9DE2B5BDDB612504 FOREIGN KEY (churches_id) REFERENCES churches (id)');
        $this->addSql('ALTER TABLE outcomes ADD CONSTRAINT FK_6E54D0FADB612504 FOREIGN KEY (churches_id) REFERENCES churches (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE incomes DROP FOREIGN KEY FK_9DE2B5BDDB612504');
        $this->addSql('ALTER TABLE outcomes DROP FOREIGN KEY FK_6E54D0FADB612504');
        $this->addSql('DROP TABLE churches');
        $this->addSql('DROP TABLE incomes');
        $this->addSql('DROP TABLE outcomes');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
