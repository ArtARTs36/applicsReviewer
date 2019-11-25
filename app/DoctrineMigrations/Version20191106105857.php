<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Статусы для заявок
 */
class Version20191106105857 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("INSERT INTO `role` (`id`, `name`, `role`, `comment_add`) VALUES (1, 'ROLE_USER', 'ROLE_USER', 1);");
        $this->addSql("INSERT INTO `role` (`id`, `name`, `role`, `comment_add`) VALUES (2, 'ROLE_ADMIN', 'ROLE_ADMIN', 1);");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM `role` WHERE `id` IN (1, 2);");
    }
}
