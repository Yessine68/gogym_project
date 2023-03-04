<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230302222136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement (id INT AUTO_INCREMENT NOT NULL, nom_a VARCHAR(255) NOT NULL, type_a VARCHAR(255) NOT NULL, description_a VARCHAR(255) NOT NULL, prix_a INT NOT NULL, debut_a DATE NOT NULL, fin_a DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE abonnement_salle (abonnement_id INT NOT NULL, salle_id INT NOT NULL, INDEX IDX_C550F595F1D74413 (abonnement_id), INDEX IDX_C550F595DC304035 (salle_id), PRIMARY KEY(abonnement_id, salle_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_evenement (id INT AUTO_INCREMENT NOT NULL, nom_cat_e VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id_com INT AUTO_INCREMENT NOT NULL, etat_com TINYINT(1) NOT NULL, date_com DATE NOT NULL, prixtotal DOUBLE PRECISION NOT NULL, PRIMARY KEY(id_com)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, duree INT NOT NULL, intensite VARCHAR(255) NOT NULL, bienfaits VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, categorie_evenement_id INT DEFAULT NULL, nom_e VARCHAR(255) NOT NULL, description_e VARCHAR(255) NOT NULL, date_e VARCHAR(255) NOT NULL, lieu_e VARCHAR(255) NOT NULL, nbr_participants INT NOT NULL, image VARCHAR(1000) NOT NULL, INDEX IDX_B26681E76D36991 (categorie_evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne_commande (id_ligne INT AUTO_INCREMENT NOT NULL, qte_dem INT NOT NULL, PRIMARY KEY(id_ligne)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livraison (id_liv INT AUTO_INCREMENT NOT NULL, description_liv VARCHAR(255) NOT NULL, etat_liv TINYINT(1) NOT NULL, adresse_liv VARCHAR(255) NOT NULL, PRIMARY KEY(id_liv)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id_panier INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id_panier)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participate (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_event_id INT DEFAULT NULL, verification_code INT DEFAULT NULL, INDEX IDX_D02B13879F37AE5 (id_user_id), INDEX IDX_D02B138212C041E (id_event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id_part INT AUTO_INCREMENT NOT NULL, verif_code INT NOT NULL, PRIMARY KEY(id_part)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, nom_prod VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, nbr_prods INT NOT NULL, INDEX IDX_29A5EC27BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, cours_id INT DEFAULT NULL, date DATE NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_42C849557ECF78B0 (cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salle (id INT AUTO_INCREMENT NOT NULL, nom_s VARCHAR(255) NOT NULL, tel_s INT NOT NULL, email_s VARCHAR(255) NOT NULL, adresse_s VARCHAR(255) NOT NULL, ville_s VARCHAR(255) NOT NULL, perimetre_s DOUBLE PRECISION NOT NULL, image VARCHAR(255) NOT NULL, pos1 DOUBLE PRECISION NOT NULL, pos2 DOUBLE PRECISION NOT NULL, likes INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonnement_salle ADD CONSTRAINT FK_C550F595F1D74413 FOREIGN KEY (abonnement_id) REFERENCES abonnement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE abonnement_salle ADD CONSTRAINT FK_C550F595DC304035 FOREIGN KEY (salle_id) REFERENCES salle (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E76D36991 FOREIGN KEY (categorie_evenement_id) REFERENCES categorie_evenement (id)');
        $this->addSql('ALTER TABLE participate ADD CONSTRAINT FK_D02B13879F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE participate ADD CONSTRAINT FK_D02B138212C041E FOREIGN KEY (id_event_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849557ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement_salle DROP FOREIGN KEY FK_C550F595F1D74413');
        $this->addSql('ALTER TABLE abonnement_salle DROP FOREIGN KEY FK_C550F595DC304035');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E76D36991');
        $this->addSql('ALTER TABLE participate DROP FOREIGN KEY FK_D02B13879F37AE5');
        $this->addSql('ALTER TABLE participate DROP FOREIGN KEY FK_D02B138212C041E');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849557ECF78B0');
        $this->addSql('DROP TABLE abonnement');
        $this->addSql('DROP TABLE abonnement_salle');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE categorie_evenement');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE ligne_commande');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE participate');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE salle');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
