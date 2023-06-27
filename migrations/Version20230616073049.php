<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230616073049 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add columns to the users table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->getTable('users');

        if (!$table->hasColumn('first_name')) {
            $table->addColumn('first_name', 'string', ['length' => 255]);
        }

        if (!$table->hasColumn('last_name')) {
            $table->addColumn('last_name', 'string', ['length' => 255]);
        }
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('users');

        if ($table->hasColumn('first_name')) {
            $table->dropColumn('first_name');
        }

        if ($table->hasColumn('last_name')) {
            $table->dropColumn('last_name');
        }
    }
}