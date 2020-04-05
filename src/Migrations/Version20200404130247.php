<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200404130247 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE flight DROP FOREIGN KEY FK_C257E60E30354A65');
        $this->addSql('ALTER TABLE flight DROP FOREIGN KEY FK_C257E60E78CED90B');
        $this->addSql('DROP INDEX IDX_C257E60E30354A65 ON flight');
        $this->addSql('DROP INDEX IDX_C257E60E78CED90B ON flight');
        $this->addSql('ALTER TABLE flight ADD `from` VARCHAR(255) NOT NULL, ADD `to` VARCHAR(255) NOT NULL, DROP from_id, DROP to_id');
        $this->addSql('CREATE UNIQUE INDEX flight ON flight (`from`, `to`)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX flight ON flight');
        $this->addSql('ALTER TABLE flight ADD from_id INT DEFAULT NULL, ADD to_id INT DEFAULT NULL, DROP `from`, DROP `to`');
        $this->addSql('ALTER TABLE flight ADD CONSTRAINT FK_C257E60E30354A65 FOREIGN KEY (to_id) REFERENCES airport (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE flight ADD CONSTRAINT FK_C257E60E78CED90B FOREIGN KEY (from_id) REFERENCES airport (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C257E60E30354A65 ON flight (to_id)');
        $this->addSql('CREATE INDEX IDX_C257E60E78CED90B ON flight (from_id)');
    }
}
