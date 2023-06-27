<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230616073049 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add users table';
    }

    public function up(Schema $schema): void
    {
        $tableName = 'users';

        if (!$schema->hasTable($tableName)) {
            $table = $schema->createTable($tableName);
            
            // Ajoutez ici les colonnes et les contraintes de la table 'users'
            // Exemple :
            /*
            $table->addColumn('id', 'integer', ['autoincrement' => true]);
            $table->addColumn('username', 'string', ['length' => 255]);
            $table->addColumn('password', 'string', ['length' => 255]);
            // ... Ajoutez d'autres colonnes et contraintes ...
            */
        }
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('users');
    }
}