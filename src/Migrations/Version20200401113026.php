<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200401113026 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE flight ADD from_id INT DEFAULT NULL, ADD to_id INT DEFAULT NULL, DROP `from`, DROP `to`');
        $this->addSql('ALTER TABLE flight ADD CONSTRAINT FK_C257E60E78CED90B FOREIGN KEY (from_id) REFERENCES airport (id)');
        $this->addSql('ALTER TABLE flight ADD CONSTRAINT FK_C257E60E30354A65 FOREIGN KEY (to_id) REFERENCES airport (id)');
        $this->addSql('CREATE INDEX IDX_C257E60E78CED90B ON flight (from_id)');
        $this->addSql('CREATE INDEX IDX_C257E60E30354A65 ON flight (to_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE flight DROP FOREIGN KEY FK_C257E60E78CED90B');
        $this->addSql('ALTER TABLE flight DROP FOREIGN KEY FK_C257E60E30354A65');
        $this->addSql('DROP INDEX IDX_C257E60E78CED90B ON flight');
        $this->addSql('DROP INDEX IDX_C257E60E30354A65 ON flight');
        $this->addSql('ALTER TABLE flight ADD `from` LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD `to` LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP from_id, DROP to_id');
    }
}
