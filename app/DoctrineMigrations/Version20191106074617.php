<?php

namespace Application\Migrations;

use AppBundle\Helper\NumberHelper;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Миграция для заполнения блока дел: "Юр Поддержка бизнеса"
 */
class Version20191106074617 extends AbstractMigration
{
    const WORK_ID = 4;

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO `works_services` (`works_id`, `name`, `content`, `icon`, `created`) VALUES ('2', 'Регистрация ООО, ЗАО, ИП', 'Регистрация ООО, ЗАО, ИП', '1', '2019-11-04 00:00:00');");
        
        $this->addSql("INSERT INTO `works_services` (`works_id`, `name`, `content`, `icon`, `created`) VALUES ('2', 'Банкротство ФЛ/ЮЛ', 'Банкротство ФЛ/ЮЛ', '1', '2019-11-04 00:00:00');");
        
        $this->addSql("INSERT INTO `works_services` (`works_id`, `name`, `content`, `icon`, `created`) VALUES ('2', 'Ввод/вывод из ООО', 'Ввод/вывод из ООО', '1', '2019-11-04 00:00:00');");
        
        $this->addSql("INSERT INTO `works_services` (`works_id`, `name`, `content`, `icon`, `created`) VALUES ('2', 'Внесение изменений в учредительные документы', 'Внесение изменений в учредительные документы', '1', '2019-11-04 00:00:00');");
        
        $this->addSql("INSERT INTO `works_services` (`works_id`, `name`, `content`, `icon`, `created`) VALUES ('2', 'Арбитражные дела', 'Арбитражные дела', '1', '2019-11-04 00:00:00')");

        $this->addSql("INSERT INTO `works_services` (`works_id`, `name`, `content`, `icon`, `created`) VALUES ('2', 'Налоговые споры', 'Налоговые споры', '1', '2019-11-04 00:00:00')");

        $this->addSql("INSERT INTO `works_services` (`works_id`, `name`, `content`, `icon`, `created`) VALUES ('2', 'Юридическая консультация', 'Юридическая консультация', '1', '2019-11-04 00:00:00')");

        $this->addSql("INSERT INTO `works_services` (`works_id`, `name`, `content`, `icon`, `created`) VALUES ('2', 'Составление документов', 'Составление документов', '1', '2019-11-04 00:00:00')");

        $this->addSql("INSERT INTO `works_services` (`works_id`, `name`, `content`, `icon`, `created`) VALUES ('2', 'Процедура медиации', 'Процедура медиации', '1', '2019-11-04 00:00:00')");

        $this->addSql("INSERT INTO `works_services` (`works_id`, `name`, `content`, `icon`, `created`) VALUES ('2', 'Представление интересов в органах и организациях', 'Представление интересов в органах и организациях', '1', '2019-11-04 00:00:00');");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql( "DELETE FROM `works_services` WHERE `works_services`.`created` = '2019-11-04 00:00:00' AND `works_services`.`created` = '". self::WORK_ID ."'");
    }
}
