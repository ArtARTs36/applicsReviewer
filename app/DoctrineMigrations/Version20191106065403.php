<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Создаем блоки дел
 */
class Version20191106065403 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO `works` (`id`, `name`, `created`, `url`) VALUES
(1, 'Семейные дела', '2019-11-06 00:00:00', 'family'),
(2, 'Уголовные дела', '2019-11-06 00:00:00', 'penal'),
(3, 'Гражданские дела', '2019-11-06 00:00:00', 'civil'),
(4, 'Юридическая поддержка бизнеса', '2019-11-06 00:00:00', 'business-support')");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM `works` WHERE `works`.`created` = '2019-11-06 00:00:00'");
    }
}
