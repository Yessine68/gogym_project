<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230505182239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livreur (id_livreur INT AUTO_INCREMENT NOT NULL, nom_liv VARCHAR(30) NOT NULL, prenom_liv VARCHAR(30) NOT NULL, num_tel_liv INT NOT NULL, disponibilite_liv TINYINT(1) NOT NULL, region VARCHAR(50) NOT NULL, PRIMARY KEY(id_livreur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, is_read TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BF5476CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE evenement ADD etat VARCHAR(255) DEFAULT NULL, CHANGE date_e date_e VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ligne_commande MODIFY id_ligne INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON ligne_commande');
        $this->addSql('ALTER TABLE ligne_commande CHANGE id_ligne id_l_p INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE ligne_commande ADD PRIMARY KEY (id_l_p)');
        $this->addSql('ALTER TABLE livraison CHANGE id_livraison id_livraison INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id_livraison)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('DROP TABLE livreur');
        $this->addSql('DROP TABLE notification');
        $this->addSql('ALTER TABLE evenement DROP etat, CHANGE date_e date_e DATETIME NOT NULL');
        $this->addSql('ALTER TABLE ligne_commande MODIFY id_l_p INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON ligne_commande');
        $this->addSql('ALTER TABLE ligne_commande CHANGE id_l_p id_ligne INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE ligne_commande ADD PRIMARY KEY (id_ligne)');
        $this->addSql('ALTER TABLE livraison MODIFY id_livraison INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON livraison');
        $this->addSql('ALTER TABLE livraison CHANGE id_livraison id_livraison INT NOT NULL');
    }
}
