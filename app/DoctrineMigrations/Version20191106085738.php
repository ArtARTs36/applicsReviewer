<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Миграция для заполнения "судебных практик"
 */
class Version20191106085738 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO `court_practices` (`id`, `theme`, `content`, `created`, `priority`) VALUES
(1, 'Установлены алименты на содержание ребенка', 'После длительных судебных процессов, рассмотрения дела в суде апелляционной инстанции, в суде по иску об изменении размера алиментов заключено мировое соглашение.', '2019-11-03 19:50:07', 3),
(2, 'Определен порядок пользования техническим этажом', 'При наличии конфликтных отношений между соседями, в судебном процессе наши опытные юристы подвели стороны к заключению мирового соглашения об определении порядка пользования техническим этажом (чердаком).', '2019-11-03 19:50:27', 3),
(3, 'Залитие', 'Получено положительное решение о взыскании суммы ущерба, полученного в результате залития, а также компенсированы судебные расходы', '2019-11-03 19:50:47', 3),
(4, 'Отменен судебный приказ', 'Отменен судебный приказ, на основании которого со счета были списаны денежные средства в счет оплаты ЖКУ, а также осуществлен поворот исполнения решения.', '2019-11-03 19:51:08', 3),
(5, 'Возврат стоимости навязанных услуг', 'Получено решение об исключении из числа застрахованных лиц по договору страхования заемщиков и возврате уплаченных денежных средств за указанную услугу.', '2019-11-03 19:51:22', 3);
");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM `court_practices` WHERE `court_practices`.`id` IN(1, 2, 3, 4, 5)");
    }
}
