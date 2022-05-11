<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Mautic\IntegrationsBundle\Migration\AbstractMigration;

class Version_0_2_0 extends AbstractMigration
{
    /**
     * {@inheritDoc}
     */
    protected function isApplicable(Schema $schema): bool
    {
        var_dump('migration ?');

        return !$schema->hasTable('transactions');
    }

    /**
     * {@inheritDoc}
     */
    protected function up(): void
    {
        $this->addSql('CREATE TABLE transactions (
                id INT NOT NULL, 
                lead_id INT NOT NULL, 
                date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
                priceWithoutTaxes INT NOT NULL, 
                priceWithTaxes INT NOT NULL, 
                nbProducts INT NOT NULL, 
                PRIMARY KEY(id)
            ) 
            DEFAULT CHARACTER SET utf8mb4 
            COLLATE `utf8mb4_unicode_ci` 
            ENGINE = InnoDB
            ROW_FORMAT = DYNAMIC;
        ');
    }
}
