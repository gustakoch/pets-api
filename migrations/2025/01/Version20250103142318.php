<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250103142318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE veterinarian_id_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE veterinarians (id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, has_phone_whatsapp BOOLEAN NOT NULL, specializations TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, public_id VARCHAR(26) NOT NULL, address_postal_code VARCHAR(20) NOT NULL, address_street VARCHAR(255) NOT NULL, address_number INT NOT NULL, address_neighborhood VARCHAR(255) NOT NULL, address_city VARCHAR(255) NOT NULL, address_state VARCHAR(255) NOT NULL, address_complement VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6B2A679CB5B48B91 ON veterinarians (public_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE veterinarian_id_id_seq CASCADE');
        $this->addSql('DROP TABLE veterinarians');
    }
}
