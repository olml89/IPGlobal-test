<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230318191858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE posts CHANGE title title VARCHAR(100) NOT NULL, CHANGE body body TEXT');
        $this->addSql('ALTER TABLE users CHANGE name name VARCHAR(100) NOT NULL, CHANGE username username VARCHAR(100) NOT NULL, CHANGE phone phone VARCHAR(100) NOT NULL, CHANGE address_street address_street VARCHAR(100) NOT NULL, CHANGE address_suite address_suite VARCHAR(100) NOT NULL, CHANGE address_city address_city VARCHAR(100) NOT NULL, CHANGE company_name company_name VARCHAR(100) NOT NULL, CHANGE company_catchphrase company_catchphrase VARCHAR(100) NOT NULL, CHANGE company_bs company_bs VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE posts CHANGE title title VARCHAR(100) NOT NULL, CHANGE body body TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE name name VARCHAR(100) NOT NULL, CHANGE username username VARCHAR(100) NOT NULL, CHANGE phone phone VARCHAR(100) NOT NULL, CHANGE address_street address_street VARCHAR(100) NOT NULL, CHANGE address_suite address_suite VARCHAR(100) NOT NULL, CHANGE address_city address_city VARCHAR(100) NOT NULL, CHANGE company_name company_name VARCHAR(100) NOT NULL, CHANGE company_catchphrase company_catchphrase VARCHAR(100) NOT NULL, CHANGE company_bs company_bs VARCHAR(100) NOT NULL');
    }
}
