<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230505191006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ligne_commande ADD id_l_p INT AUTO_INCREMENT NOT NULL, DROP id_ligne, ADD PRIMARY KEY (id_l_p)');
        $this->addSql('ALTER TABLE livraison CHANGE id_livraison id_livraison INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id_livraison)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ligne_commande MODIFY id_l_p INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON ligne_commande');
        $this->addSql('ALTER TABLE ligne_commande ADD id_ligne INT NOT NULL, DROP id_l_p');
        $this->addSql('ALTER TABLE livraison MODIFY id_livraison INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON livraison');
        $this->addSql('ALTER TABLE livraison CHANGE id_livraison id_livraison INT NOT NULL');
    }
}
