<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251219105006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE réservation DROP FOREIGN KEY FK_666D6ED17EE5403C');
        $this->addSql('ALTER TABLE réservation DROP FOREIGN KEY FK_666D6ED119EB6921');
        $this->addSql('DROP TABLE réservation');
        $this->addSql('ALTER TABLE panier CHANGE montant_total montant_total NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD administrateur_id INT DEFAULT NULL, ADD nb_personnes INT NOT NULL, ADD date_creation DATETIME NOT NULL, DROP mode_paiement, DROP commentaire, CHANGE nombre_personnes client_id INT NOT NULL, CHANGE status statut VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495519EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849557EE5403C FOREIGN KEY (administrateur_id) REFERENCES administrateur (id)');
        $this->addSql('CREATE INDEX IDX_42C8495519EB6921 ON reservation (client_id)');
        $this->addSql('CREATE INDEX IDX_42C849557EE5403C ON reservation (administrateur_id)');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE réservation (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, administrateur_id INT DEFAULT NULL, INDEX IDX_666D6ED119EB6921 (client_id), INDEX IDX_666D6ED17EE5403C (administrateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE réservation ADD CONSTRAINT FK_666D6ED17EE5403C FOREIGN KEY (administrateur_id) REFERENCES administrateur (id)');
        $this->addSql('ALTER TABLE réservation ADD CONSTRAINT FK_666D6ED119EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE panier CHANGE montant_total montant_total NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE produit DROP image');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495519EB6921');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849557EE5403C');
        $this->addSql('DROP INDEX IDX_42C8495519EB6921 ON reservation');
        $this->addSql('DROP INDEX IDX_42C849557EE5403C ON reservation');
        $this->addSql('ALTER TABLE reservation ADD nombre_personnes INT NOT NULL, ADD mode_paiement VARCHAR(50) DEFAULT \'NULL\', ADD commentaire LONGTEXT DEFAULT NULL, DROP client_id, DROP administrateur_id, DROP nb_personnes, DROP date_creation, CHANGE statut status VARCHAR(50) NOT NULL');
    }
}
