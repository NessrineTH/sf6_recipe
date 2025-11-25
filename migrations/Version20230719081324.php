<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230719081324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sf_admin_groupe (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, droits LONGTEXT NOT NULL, is_utilisable_rh TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sf_admin_utilisateur (id INT AUTO_INCREMENT NOT NULL, created DATETIME DEFAULT NULL, updated DATETIME DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, datetime DATETIME DEFAULT NULL, is_actif TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_B2564F47E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sf_prm_param (id INT AUTO_INCREMENT NOT NULL, ptype VARCHAR(45) NOT NULL, pkey VARCHAR(75) NOT NULL, pcontexte INT DEFAULT NULL, plib VARCHAR(150) DEFAULT NULL, pexpli LONGTEXT DEFAULT NULL, pval_str VARCHAR(2000) DEFAULT NULL, pval_int INT DEFAULT NULL, pval_decimal NUMERIC(10, 2) DEFAULT NULL, pval_date DATE DEFAULT NULL, pval_txt LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sf_messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B6D48824FB7336F0 (queue_name), INDEX IDX_B6D48824E3BD61CE (available_at), INDEX IDX_B6D4882416BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sf_admin_groupe');
        $this->addSql('DROP TABLE sf_admin_utilisateur');
        $this->addSql('DROP TABLE sf_prm_param');
        $this->addSql('DROP TABLE sf_messenger_messages');
    }
}
