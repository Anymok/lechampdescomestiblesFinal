<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220509101914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_profil DROP FOREIGN KEY FK_8384A9AAA76ED395');
        $this->addSql('DROP INDEX UNIQ_8384A9AAA76ED395 ON user_profil');
        $this->addSql('ALTER TABLE user_profil DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_profil ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_profil ADD CONSTRAINT FK_8384A9AAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8384A9AAA76ED395 ON user_profil (user_id)');
    }
}
