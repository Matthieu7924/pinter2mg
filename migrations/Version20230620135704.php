<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230620135704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add new columns to the users table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->getTable('users');
        $table->addColumn('first_name', 'string', ['length' => 255]);
        $table->addColumn('last_name', 'string', ['length' => 255]);
        $table->addColumn('email', 'string', ['length' => 255]);
        $table->addColumn('password', 'string', ['length' => 255]);
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('users');
        $table->dropColumn('first_name');
        $table->dropColumn('last_name');
        $table->dropColumn('email');
        $table->dropColumn('password');
    }
}