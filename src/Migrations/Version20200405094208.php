<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200405094208 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX flight ON flight');
        $this->addSql('ALTER TABLE flight ADD ap_from INT DEFAULT NULL, ADD ap_to INT DEFAULT NULL, DROP `from`, DROP `to`');
        $this->addSql('ALTER TABLE flight ADD CONSTRAINT FK_C257E60E9992B398 FOREIGN KEY (ap_from) REFERENCES airport (id)');
        $this->addSql('ALTER TABLE flight ADD CONSTRAINT FK_C257E60EF8F1A0CA FOREIGN KEY (ap_to) REFERENCES airport (id)');
        $this->addSql('CREATE INDEX IDX_C257E60E9992B398 ON flight (ap_from)');
        $this->addSql('CREATE INDEX IDX_C257E60EF8F1A0CA ON flight (ap_to)');
        $this->addSql('CREATE UNIQUE INDEX flight ON flight (ap_from, ap_to)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE flight DROP FOREIGN KEY FK_C257E60E9992B398');
        $this->addSql('ALTER TABLE flight DROP FOREIGN KEY FK_C257E60EF8F1A0CA');
        $this->addSql('DROP INDEX IDX_C257E60E9992B398 ON flight');
        $this->addSql('DROP INDEX IDX_C257E60EF8F1A0CA ON flight');
        $this->addSql('DROP INDEX flight ON flight');
        $this->addSql('ALTER TABLE flight ADD `from` INT NOT NULL, ADD `to` INT NOT NULL, DROP ap_from, DROP ap_to');
        $this->addSql('CREATE UNIQUE INDEX flight ON flight (`from`, `to`)');
    }
}
