<?php

namespace Application\Migrations;

use AppBundle\Helper\NumberHelper;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Миграция для заполнения блока дел: "Семейное"
 */
class Version20191106071509 extends AbstractMigration
{
    const WORK_ID = 1;

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $string = '1. Расторжение брака.
2. Раздел имущества супругов.
3. Взыскание алиментов.
4. Увеличение размера алиментов.
5. Уменьшение размера алиментов.
6. Установление отцовства.
7. Оспаривание отцовства.
8. Усыновление ребенка.
9. Лишение родительских прав.
10. Восстановление родительских прав.
11. Определение места жительства ребенка.
12. Определение порядка общения с ребенком.
13. Брачный договор.
14. Юридическая консультация.
15. Составление документов.
16. Процедура медиации.
17. Представление интересов в органах и организациях.';

        $array = explode("\n", $string);
        foreach ($array as $item) {
            $item = NumberHelper::removeNumbers($item);
            $item = str_replace('.', '', $item);
            $item = trim($item);

            $this->addSql("INSERT INTO `works_services` (`works_id`, `name`, `content`, `icon`, `created`) VALUES ('". self::WORK_ID ."', '{$item}', '{$item}', '1', '2019-11-04 11:01:00');");
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql( "DELETE FROM `works_services` WHERE `works_services`.`created` = '2019-11-04 11:01:00' AND `works_services`.`works_id` = '". self::WORK_ID ."'");
    }
}
