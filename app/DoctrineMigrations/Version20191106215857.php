<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Статусы для заявок
 */
class Version20191106215857 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO `vocab_applics_status` (`id`, `name`) VALUES
(1, 'Заявка в обработке'),
(2, 'Заявка обработана');");

        $this->addSql("INSERT INTO `vocab_applics_status` (`id`, `name`) VALUES
(0, 'Заявка добавлена');");

        $this->addSql("UPDATE `vocab_applics_status` SET `id` = 0 WHERE `name` = 'Заявка добавлена';");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM `vocab_applics_status` WHERE `id` IN (0, 1, 2);");
    }
}
