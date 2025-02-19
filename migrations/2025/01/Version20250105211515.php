<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250105211515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE pet_id_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE pets (id INT NOT NULL, name VARCHAR(255) NOT NULL, specie VARCHAR(255) NOT NULL, birth_date DATE NOT NULL, color VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, public_id VARCHAR(26) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8638EA3FB5B48B91 ON pets (public_id)');
        $this->addSql('CREATE TABLE pet_veterinarian (pet_id INT NOT NULL, veterinarian_id INT NOT NULL, PRIMARY KEY(pet_id, veterinarian_id))');
        $this->addSql('CREATE INDEX IDX_AAD40354966F7FB6 ON pet_veterinarian (pet_id)');
        $this->addSql('CREATE INDEX IDX_AAD40354804C8213 ON pet_veterinarian (veterinarian_id)');
        $this->addSql('ALTER TABLE pet_veterinarian ADD CONSTRAINT FK_AAD40354966F7FB6 FOREIGN KEY (pet_id) REFERENCES pets (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pet_veterinarian ADD CONSTRAINT FK_AAD40354804C8213 FOREIGN KEY (veterinarian_id) REFERENCES veterinarians (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE pet_id_id_seq CASCADE');
        $this->addSql('ALTER TABLE pet_veterinarian DROP CONSTRAINT FK_AAD40354966F7FB6');
        $this->addSql('ALTER TABLE pet_veterinarian DROP CONSTRAINT FK_AAD40354804C8213');
        $this->addSql('DROP TABLE pets');
        $this->addSql('DROP TABLE pet_veterinarian');
    }
}
