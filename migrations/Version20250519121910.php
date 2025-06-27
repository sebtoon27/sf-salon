<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519121910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE prestation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, durée INT NOT NULL, prix VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, data VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, date_debut DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', date_fin DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', description LONGTEXT DEFAULT NULL, image1 VARCHAR(255) DEFAULT NULL, image2 VARCHAR(255) DEFAULT NULL, image3 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, date DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', validation TINYINT(1) DEFAULT NULL, data VARCHAR(255) DEFAULT NULL, INDEX IDX_65E8AA0A19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE rendez_vous_prestation (rendez_vous_id INT NOT NULL, prestation_id INT NOT NULL, INDEX IDX_77B3306591EF7EAA (rendez_vous_id), INDEX IDX_77B330659E45C554 (prestation_id), PRIMARY KEY(rendez_vous_id, prestation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) NOT NULL, statut_premium TINYINT(1) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A19EB6921 FOREIGN KEY (client_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendez_vous_prestation ADD CONSTRAINT FK_77B3306591EF7EAA FOREIGN KEY (rendez_vous_id) REFERENCES rendez_vous (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendez_vous_prestation ADD CONSTRAINT FK_77B330659E45C554 FOREIGN KEY (prestation_id) REFERENCES prestation (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A19EB6921
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendez_vous_prestation DROP FOREIGN KEY FK_77B3306591EF7EAA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE rendez_vous_prestation DROP FOREIGN KEY FK_77B330659E45C554
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE prestation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE produit
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE rendez_vous
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE rendez_vous_prestation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
