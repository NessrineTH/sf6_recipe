<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214093154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sf_admin_utilisateur_groupe DROP FOREIGN KEY FK_820024968A1A7E06');
        $this->addSql('ALTER TABLE sf_admin_utilisateur_groupe DROP FOREIGN KEY FK_82002496C02577EA');
        $this->addSql('DROP TABLE sf_admin_groupe');
        $this->addSql('DROP TABLE sf_admin_utilisateur');
        $this->addSql('DROP TABLE sf_admin_utilisateur_groupe');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sf_admin_groupe (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, droits LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sf_admin_utilisateur (id INT AUTO_INCREMENT NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, last_login DATETIME DEFAULT NULL, is_actif TINYINT(1) DEFAULT NULL, preferences LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_B2564F47E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sf_admin_utilisateur_groupe (adminutilisateur_id INT NOT NULL, admingroupe_id INT NOT NULL, INDEX IDX_820024968A1A7E06 (adminutilisateur_id), INDEX IDX_82002496C02577EA (admingroupe_id), PRIMARY KEY(adminutilisateur_id, admingroupe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE sf_admin_utilisateur_groupe ADD CONSTRAINT FK_820024968A1A7E06 FOREIGN KEY (adminutilisateur_id) REFERENCES sf_admin_utilisateur (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sf_admin_utilisateur_groupe ADD CONSTRAINT FK_82002496C02577EA FOREIGN KEY (admingroupe_id) REFERENCES sf_admin_groupe (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
