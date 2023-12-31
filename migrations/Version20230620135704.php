<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230620135704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add last_name, email, and password columns to users table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->getTable('users');

        if (!$table->hasColumn('last_name')) {
            $table->addColumn('last_name', 'string', ['length' => 255]);
        }

        if (!$table->hasColumn('email')) {
            $table->addColumn('email', 'string', ['length' => 255]);
        }

        if (!$table->hasColumn('password')) {
            $table->addColumn('password', 'string', ['length' => 255]);
        }
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('users');

        if ($table->hasColumn('last_name')) {
            $table->dropColumn('last_name');
        }

        if ($table->hasColumn('email')) {
            $table->dropColumn('email');
        }

        if ($table->hasColumn('password')) {
            $table->dropColumn('password');
        }
    }
}