<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230628195204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        // Create the users table
        $this->addSql('CREATE TABLE users (
            id INT AUTO_INCREMENT NOT NULL,
            email VARCHAR(255) NOT NULL,
            roles LONGTEXT NOT NULL,
            password VARCHAR(255) NOT NULL,
            first_name VARCHAR(255) NOT NULL,
            last_name VARCHAR(255) NOT NULL,
            is_verified TINYINT(1) NOT NULL,
            profile_image VARCHAR(255) DEFAULT NULL,
            created_at DATETIME DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL,
            PRIMARY KEY(id)
        )');

        // Create the reset_password_requests table
        $this->addSql('CREATE TABLE reset_password_requests (
            id INT AUTO_INCREMENT NOT NULL,
            user_id INT NOT NULL,
            selector VARCHAR(255) NOT NULL,
            hashed_token VARCHAR(255) NOT NULL,
            expires_at DATETIME NOT NULL,
            PRIMARY KEY(id),
            INDEX IDX_A427BB1F76ED395 (user_id),
            CONSTRAINT FK_A427BB1F76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
        )');

        // Add any additional statements or SQL queries if needed
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        // Drop the reset_password_requests table
        $this->addSql('DROP TABLE reset_password_requests');

        // Drop the users table
        $this->addSql('DROP TABLE users');
    }
}