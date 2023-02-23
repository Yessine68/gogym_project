<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230223113755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE categorie_produit');
        $this->addSql('ALTER TABLE produit MODIFY id_p INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON produit');
        $this->addSql('ALTER TABLE produit ADD categorie_id INT DEFAULT NULL, ADD nom_prod VARCHAR(255) NOT NULL, ADD description LONGTEXT NOT NULL, ADD image VARCHAR(255) NOT NULL, ADD nbr_prods INT NOT NULL, DROP nom_p, DROP qte_stock, DROP description_p, DROP cat_p, CHANGE id_p id INT AUTO_INCREMENT NOT NULL, CHANGE prix_p prix DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27BCF5E72D ON produit (categorie_id)');
        $this->addSql('ALTER TABLE produit ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('CREATE TABLE categorie_produit (id_cat_p INT AUTO_INCREMENT NOT NULL, nom_cat_p VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id_cat_p)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('ALTER TABLE produit MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX IDX_29A5EC27BCF5E72D ON produit');
        $this->addSql('DROP INDEX `PRIMARY` ON produit');
        $this->addSql('ALTER TABLE produit ADD nom_p VARCHAR(255) NOT NULL, ADD qte_stock VARCHAR(255) NOT NULL, ADD description_p VARCHAR(255) NOT NULL, ADD cat_p VARCHAR(255) NOT NULL, DROP categorie_id, DROP nom_prod, DROP description, DROP image, DROP nbr_prods, CHANGE id id_p INT AUTO_INCREMENT NOT NULL, CHANGE prix prix_p DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE produit ADD PRIMARY KEY (id_p)');
    }
}
