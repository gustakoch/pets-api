<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250111224326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE vaccination_id_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE vaccinations (id INT NOT NULL, pet_id INT NOT NULL, veterinarian_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, application_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, booster_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, price BIGINT NOT NULL, manufacturer VARCHAR(255) NOT NULL, notes TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, public_id VARCHAR(26) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_92C6ED72B5B48B91 ON vaccinations (public_id)');
        $this->addSql('CREATE INDEX IDX_92C6ED72966F7FB6 ON vaccinations (pet_id)');
        $this->addSql('CREATE INDEX IDX_92C6ED72804C8213 ON vaccinations (veterinarian_id)');
        $this->addSql('ALTER TABLE vaccinations ADD CONSTRAINT FK_92C6ED72966F7FB6 FOREIGN KEY (pet_id) REFERENCES pets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vaccinations ADD CONSTRAINT FK_92C6ED72804C8213 FOREIGN KEY (veterinarian_id) REFERENCES veterinarians (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE vaccination_id_id_seq CASCADE');
        $this->addSql('ALTER TABLE vaccinations DROP CONSTRAINT FK_92C6ED72966F7FB6');
        $this->addSql('ALTER TABLE vaccinations DROP CONSTRAINT FK_92C6ED72804C8213');
        $this->addSql('DROP TABLE vaccinations');
    }
}
