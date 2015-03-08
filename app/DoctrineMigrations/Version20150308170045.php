<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150308170045 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $sql = 'ALTER TABLE `events`
          CHANGE `updated_at`
          `updated_at`
            TIMESTAMP
            on update CURRENT_TIMESTAMP
            NOT NULL DEFAULT \'0000-00-00 00:00:00\';';
        $this->addSql($sql);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $sql = 'ALTER TABLE `events`
          CHANGE `updated_at`
          `updated_at`
            DATETIME
            NOT NULL DEFAULT \'0000-00-00 00:00:00\';';
        $this->addSql($sql);
    }
}
