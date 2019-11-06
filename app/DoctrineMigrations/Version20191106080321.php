<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Заполнение блока "Уголовное право"
 */
class Version20191106080321 extends AbstractMigration
{
    const WORK_ID = 2;

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO `works_services` (`works_id`, `name`, `content`, `icon`, `created`) VALUES (
'". self::WORK_ID ."', 'Юридическая консультация', 'Юридическая консультация', '1', '2019-11-06 11:05:00');");

        $this->addSql("INSERT INTO `works_services` (`works_id`, `name`, `content`, `icon`, `created`) VALUES (
'". self::WORK_ID ."', 'Подбор адвоката', 'Подбор адвоката', '1', '2019-11-06 11:05:00');");

        $this->addSql("INSERT INTO `works_services` (`works_id`, `name`, `content`, `icon`, `created`) VALUES (
'". self::WORK_ID ."', 'Представление интересов в суде', 'Представление интересов в суде', '1', '2019-11-06 11:05:00');");

        $this->addSql("INSERT INTO `works_services` (`works_id`, `name`, `content`, `icon`, `created`) VALUES (
'". self::WORK_ID ."', 'Составление документов', 'Составление документов', '1', '2019-11-06 11:05:00');");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql( "DELETE FROM `works_services` WHERE `works_services`.`created` = '2019-11-06 11:05:00' AND `works_services`.`created` = '". self::WORK_ID ."'");
    }
}
