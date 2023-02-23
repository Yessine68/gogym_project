<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230219205549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnement_salle (abonnement_id INT NOT NULL, salle_id INT NOT NULL, INDEX IDX_C550F595F1D74413 (abonnement_id), INDEX IDX_C550F595DC304035 (salle_id), PRIMARY KEY(abonnement_id, salle_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abonnement_salle ADD CONSTRAINT FK_C550F595F1D74413 FOREIGN KEY (abonnement_id) REFERENCES abonnement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE abonnement_salle ADD CONSTRAINT FK_C550F595DC304035 FOREIGN KEY (salle_id) REFERENCES salle (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abonnement_salle DROP FOREIGN KEY FK_C550F595F1D74413');
        $this->addSql('ALTER TABLE abonnement_salle DROP FOREIGN KEY FK_C550F595DC304035');
        $this->addSql('DROP TABLE abonnement_salle');
    }
}
