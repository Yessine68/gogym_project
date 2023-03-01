<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301144131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participate (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_event_id INT DEFAULT NULL, verification_code INT DEFAULT NULL, INDEX IDX_D02B13879F37AE5 (id_user_id), INDEX IDX_D02B138212C041E (id_event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participate ADD CONSTRAINT FK_D02B13879F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE participate ADD CONSTRAINT FK_D02B138212C041E FOREIGN KEY (id_event_id) REFERENCES evenement (id)');
        $this->addSql('DROP TABLE participation');
        $this->addSql('ALTER TABLE evenement ADD etat VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit CHANGE image image VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participation (id_part INT AUTO_INCREMENT NOT NULL, verif_code INT NOT NULL, PRIMARY KEY(id_part)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE participate DROP FOREIGN KEY FK_D02B13879F37AE5');
        $this->addSql('ALTER TABLE participate DROP FOREIGN KEY FK_D02B138212C041E');
        $this->addSql('DROP TABLE participate');
        $this->addSql('ALTER TABLE evenement DROP etat');
        $this->addSql('ALTER TABLE produit CHANGE image image VARCHAR(1000) NOT NULL');
    }
}
