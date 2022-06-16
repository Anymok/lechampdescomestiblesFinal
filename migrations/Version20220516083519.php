<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220516083519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_24CC0DF2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier_plants (id INT AUTO_INCREMENT NOT NULL, plants_id INT NOT NULL, panier_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_10573E0862091EAB (plants_id), INDEX IDX_10573E08F77D927C (panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE panier_plants ADD CONSTRAINT FK_10573E0862091EAB FOREIGN KEY (plants_id) REFERENCES plants (id)');
        $this->addSql('ALTER TABLE panier_plants ADD CONSTRAINT FK_10573E08F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier_plants DROP FOREIGN KEY FK_10573E08F77D927C');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE panier_plants');
    }
}
