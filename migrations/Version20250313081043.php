<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250313081043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP CONSTRAINT product_pkey');
        $this->addSql('ALTER TABLE product ADD id SERIAL NOT NULL');
        $this->addSql('ALTER TABLE product ALTER category_id DROP NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04ADF9038C4 ON product (sku)');
        $this->addSql('ALTER TABLE product ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_D34A04ADF9038C4');
        $this->addSql('DROP INDEX product_pkey');
        $this->addSql('ALTER TABLE product DROP id');
        $this->addSql('ALTER TABLE product ALTER category_id SET NOT NULL');
        $this->addSql('ALTER TABLE product ADD PRIMARY KEY (sku)');
    }
}
