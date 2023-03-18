<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230318130358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users (id CHAR(36) NOT NULL, password CHAR(60) NOT NULL, name VARCHAR(100) NOT NULL, username VARCHAR(100) NOT NULL, email VARCHAR(320) NOT NULL, phone VARCHAR(100) NOT NULL, website VARCHAR(2048) NOT NULL, address_street VARCHAR(100) NOT NULL, address_suite VARCHAR(100) NOT NULL, address_city VARCHAR(100) NOT NULL, address_zipcode VARCHAR(20) NOT NULL, address_geoLocation_latitude DOUBLE PRECISION NOT NULL, address_geoLocation_longitude DOUBLE PRECISION NOT NULL, company_name VARCHAR(100) NOT NULL, company_catchphrase VARCHAR(100) NOT NULL, company_bs VARCHAR(100) NOT NULL, INDEX idx_id (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE posts CHANGE title title VARCHAR(100) NOT NULL, CHANGE body body TEXT');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFAA76ED395');
        $this->addSql('DROP TABLE users');
        $this->addSql('ALTER TABLE posts CHANGE title title VARCHAR(100) NOT NULL, CHANGE body body TEXT DEFAULT NULL');
    }
}
