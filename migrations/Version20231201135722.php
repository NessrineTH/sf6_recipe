<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231201135722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sf_admin_utilisateur_groupe (adminutilisateur_id INT NOT NULL, admingroupe_id INT NOT NULL, INDEX IDX_820024968A1A7E06 (adminutilisateur_id), INDEX IDX_82002496C02577EA (admingroupe_id), PRIMARY KEY(adminutilisateur_id, admingroupe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sf_admin_utilisateur_groupe ADD CONSTRAINT FK_820024968A1A7E06 FOREIGN KEY (adminutilisateur_id) REFERENCES sf_admin_utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sf_admin_utilisateur_groupe ADD CONSTRAINT FK_82002496C02577EA FOREIGN KEY (admingroupe_id) REFERENCES sf_admin_groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sf_admin_groupe DROP is_utilisable_rh');
        $this->addSql('ALTER TABLE sf_admin_utilisateur DROP roles');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sf_admin_utilisateur_groupe DROP FOREIGN KEY FK_820024968A1A7E06');
        $this->addSql('ALTER TABLE sf_admin_utilisateur_groupe DROP FOREIGN KEY FK_82002496C02577EA');
        $this->addSql('DROP TABLE sf_admin_utilisateur_groupe');
        $this->addSql('ALTER TABLE sf_admin_groupe ADD is_utilisable_rh TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE sf_admin_utilisateur ADD roles JSON NOT NULL');
    }
}
