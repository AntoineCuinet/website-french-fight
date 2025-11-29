<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251129111006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fight ADD fighter_aage INT DEFAULT NULL, ADD fighter_aheight VARCHAR(20) DEFAULT NULL, ADD fighter_aweight VARCHAR(20) DEFAULT NULL, ADD fighter_bage INT DEFAULT NULL, ADD fighter_bheight VARCHAR(20) DEFAULT NULL, ADD fighter_bweight VARCHAR(20) DEFAULT NULL, ADD broadcaster VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fight DROP fighter_aage, DROP fighter_aheight, DROP fighter_aweight, DROP fighter_bage, DROP fighter_bheight, DROP fighter_bweight, DROP broadcaster');
    }
}
