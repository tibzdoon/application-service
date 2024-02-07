<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207140622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE application (id INT GENERATED BY DEFAULT AS IDENTITY NOT NULL, topic VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, comment VARCHAR(255) DEFAULT NULL, status INT NOT NULL, owner_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A45BDDC17E3C61F9 ON application (owner_id)');
        $this->addSql('ALTER TABLE application ADD CONSTRAINT FK_A45BDDC17E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE application DROP CONSTRAINT FK_A45BDDC17E3C61F9');
        $this->addSql('DROP TABLE application');
    }
}
