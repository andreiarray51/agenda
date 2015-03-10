<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150310024219 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $sql = 'ALTER TABLE `events`
          CHANGE `description`
              `description` LONGTEXT
              CHARACTER SET utf8
              COLLATE utf8_unicode_ci
              NULL DEFAULT NULL;';
        $this->addSql($sql);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $sql = 'ALTER TABLE `events`
          CHANGE `description`
              `description` LONGTEXT
              CHARACTER SET utf8
              COLLATE utf8_unicode_ci
              NOT NULL;';
        $this->addSql($sql);
    }
}
