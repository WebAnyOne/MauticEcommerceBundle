<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Mautic\IntegrationsBundle\Migration\AbstractMigration;

class Version_0_0_1 extends AbstractMigration
{
    /**
     * {@inheritDoc}
     */
    protected function isApplicable(Schema $schema): bool
    {
        return !$schema->hasTable('ecommerce_transaction') && !$schema->hasTable('ecommerce_product');
    }

    /**
     * {@inheritDoc}
     */
    protected function up(): void
    {
        $this->addSql(<<<SQL
            CREATE TABLE ecommerce_transaction_product (
                id INT AUTO_INCREMENT NOT NULL,
                transaction_id INT NOT NULL, 
                product_id INT NOT NULL, 
                quantity INT NOT NULL, 
                INDEX IDX_FAF642FB4584665A (product_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC;

            CREATE TABLE ecommerce_transaction (
                id INT NOT NULL, 
                lead_id BIGINT UNSIGNED DEFAULT NULL, 
                date DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', 
                priceWithoutTaxes INT NOT NULL, 
                priceWithTaxes INT NOT NULL, 
                nbProducts INT NOT NULL, 
                INDEX IDX_2B76E15655458D (lead_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC;

            CREATE TABLE ecommerce_product (
                id INT AUTO_INCREMENT NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                unitPrice INT NOT NULL, 
                createdAt DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', 
                updatedAt DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC;

            ALTER TABLE ecommerce_transaction_product 
                ADD CONSTRAINT FK_FAF642FB2FC0CB0F 
                    FOREIGN KEY (transaction_id) 
                    REFERENCES ecommerce_transaction (id);

            ALTER TABLE ecommerce_transaction_product 
                ADD CONSTRAINT FK_FAF642FB4584665A 
                    FOREIGN KEY (product_id) 
                    REFERENCES ecommerce_product (id);

            ALTER TABLE ecommerce_transaction 
                ADD CONSTRAINT FK_2B76E15655458D 
                    FOREIGN KEY (lead_id)
                    REFERENCES leads (id);
SQL);
    }
}
