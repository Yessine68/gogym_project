<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230218225252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id_a INT AUTO_INCREMENT NOT NULL, libelle_a VARCHAR(255) NOT NULL, type_a VARCHAR(255) NOT NULL, description_a VARCHAR(255) NOT NULL, prix_a INT NOT NULL, duree_a INT NOT NULL, debut_a DATE NOT NULL, fin_a DATE NOT NULL, PRIMARY KEY(id_a)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_evenement (id INT AUTO_INCREMENT NOT NULL, nom_cat_e VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id_com INT AUTO_INCREMENT NOT NULL, etat_com TINYINT(1) NOT NULL, date_com DATE NOT NULL, prixtotal DOUBLE PRECISION NOT NULL, PRIMARY KEY(id_com)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id_cours INT AUTO_INCREMENT NOT NULL, nom_cours VARCHAR(255) NOT NULL, description_cours VARCHAR(255) NOT NULL, duree_cours INT NOT NULL, PRIMARY KEY(id_cours)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, categorie_evenement_id INT DEFAULT NULL, nom_e VARCHAR(255) NOT NULL, description_e VARCHAR(255) NOT NULL, date_e VARCHAR(255) NOT NULL, lieu_e VARCHAR(255) NOT NULL, nbr_participants INT NOT NULL, image VARCHAR(1000) NOT NULL, INDEX IDX_B26681E76D36991 (categorie_evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne_commande (id_ligne INT AUTO_INCREMENT NOT NULL, qte_dem INT NOT NULL, PRIMARY KEY(id_ligne)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livraison (id_liv INT AUTO_INCREMENT NOT NULL, description_liv VARCHAR(255) NOT NULL, etat_liv TINYINT(1) NOT NULL, adresse_liv VARCHAR(255) NOT NULL, PRIMARY KEY(id_liv)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id_panier INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id_panier)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id_part INT AUTO_INCREMENT NOT NULL, verif_code INT NOT NULL, PRIMARY KEY(id_part)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, nom_prod VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(1000) NOT NULL, prix DOUBLE PRECISION NOT NULL, nbr_prods INT NOT NULL, INDEX IDX_29A5EC27BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id_res INT AUTO_INCREMENT NOT NULL, date_res DATE NOT NULL, type_res VARCHAR(255) NOT NULL, PRIMARY KEY(id_res)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salle (id_s INT AUTO_INCREMENT NOT NULL, nom_s VARCHAR(255) NOT NULL, adresse_s VARCHAR(255) NOT NULL, ville_s VARCHAR(255) NOT NULL, perimetre_s DOUBLE PRECISION NOT NULL, PRIMARY KEY(id_s)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E76D36991 FOREIGN KEY (categorie_evenement_id) REFERENCES categorie_evenement (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E76D36991');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE categorie_evenement');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE ligne_commande');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE salle');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
