<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230318164144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tokens (id INT AUTO_INCREMENT NOT NULL, user_id CHAR(36) DEFAULT NULL, hash CHAR(32) NOT NULL, expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_AA5A118EA76ED395 (user_id), INDEX idx_id (id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tokens ADD CONSTRAINT FK_AA5A118EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE posts CHANGE title title VARCHAR(100) NOT NULL, CHANGE body body TEXT');
        $this->addSql('ALTER TABLE users CHANGE name name VARCHAR(100) NOT NULL, CHANGE username username VARCHAR(100) NOT NULL, CHANGE phone phone VARCHAR(100) NOT NULL, CHANGE address_street address_street VARCHAR(100) NOT NULL, CHANGE address_suite address_suite VARCHAR(100) NOT NULL, CHANGE address_city address_city VARCHAR(100) NOT NULL, CHANGE company_name company_name VARCHAR(100) NOT NULL, CHANGE company_catchphrase company_catchphrase VARCHAR(100) NOT NULL, CHANGE company_bs company_bs VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tokens DROP FOREIGN KEY FK_AA5A118EA76ED395');
        $this->addSql('DROP TABLE tokens');
        $this->addSql('ALTER TABLE posts CHANGE title title VARCHAR(100) NOT NULL, CHANGE body body TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE name name VARCHAR(100) NOT NULL, CHANGE username username VARCHAR(100) NOT NULL, CHANGE phone phone VARCHAR(100) NOT NULL, CHANGE address_street address_street VARCHAR(100) NOT NULL, CHANGE address_suite address_suite VARCHAR(100) NOT NULL, CHANGE address_city address_city VARCHAR(100) NOT NULL, CHANGE company_name company_name VARCHAR(100) NOT NULL, CHANGE company_catchphrase company_catchphrase VARCHAR(100) NOT NULL, CHANGE company_bs company_bs VARCHAR(100) NOT NULL');
    }
}
