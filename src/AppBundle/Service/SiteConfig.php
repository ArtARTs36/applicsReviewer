<?php

namespace AppBundle\Service;

use AppBundle\Entity\ConfigControlVersion;
use Doctrine\Common\Persistence\ObjectManager;

class SiteConfig
{
    const FILE_CONFIG = '../app/config/my_config.json';

    const PARAM_PUSHALL_API_KEY = 'pushall_api_key';
    const PARAM_PUSHALL_APPLICATION_ID = 'pushall_application_id';
    const PARAM_PUSHALL_FEED_LINK = 'pushall_feed_link';
    const PARAM_STAT_REFRESH = 'stat_refresh';

    const PARAM_FOOTER_PHONE_1 = 'footer_phone_1';
    const PARAM_FOOTER_PHONE_2 = 'footer_phone_2';
    const PARAM_FOOTER_EMAIL = 'footer_email';
    const PARAM_FOOTER_ADDRESS = 'footer_address';

    public $descriptionParams = [
        self::PARAM_PUSHALL_API_KEY => 'PushAll: Ключ для API',
        self::PARAM_PUSHALL_APPLICATION_ID => 'PushAll: ID приложения',
        self::PARAM_PUSHALL_FEED_LINK => 'PushAll: Пригласительная ссылка',
        self::PARAM_STAT_REFRESH => 'Обновление статистики (интервал)',
    ];

    public $config;

    /**
     * @var ObjectManager
     */
    private $entityManager = null;

    public function __construct(ObjectManager $entityManager = null)
    {
        $this->config = $this->getFileConfig();
        $this->entityManager = $entityManager;
    }

    /**
     * Получить значение
     *
     * @param $param
     * @return mixed|null
     */
    public function getValue($param)
    {
        return $this->isValue($param) ? $this->config[$param] : null;
    }

    /**
     * Установить значение
     *
     * @param $param
     * @param $value
     * @param null $force - сразу сохранить
     */
    public function setValue($param, $value, $force = null)
    {
        if (!empty($param)) {
            $this->config[$param] = $value;

            if ($force === true) {
                $this->save();
            }
        }
    }

    /**
     * Проверить существует ли значение
     *
     * @param $param
     * @return bool
     */
    public function isValue($param)
    {
        return (isset($this->config[$param]) && !empty($this->config[$param]));
    }

    /**
     * Сохраняем изменения
     */
    public function save()
    {
        $jsonConfig = json_encode($this->config);

        file_put_contents(self::FILE_CONFIG, $jsonConfig);

        if ($this->entityManager !== null) {
            $statControlVersion = new ConfigControlVersion();
            $statControlVersion->setSettings($jsonConfig);

            $this->entityManager->persist($statControlVersion);
            $this->entityManager->flush($statControlVersion);
        }
    }

    /**
     * Проверяем файл конфига на существование:
     *  если есть, отдаем его
     *  если нет, создаем
     *
     * @return array|mixed
     */
    private function getFileConfig()
    {
        if (file_exists(self::FILE_CONFIG)) {
            return json_decode(file_get_contents(self::FILE_CONFIG), true);
        } else {
            file_put_contents(self::FILE_CONFIG, json_encode([]));

            return [];
        }
    }

    public function setEntityManager(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function revert($config)
    {
        $config = json_decode($config, true);
        if (!is_array($config)) {
            return false;
        }

        $this->config = $config;

        $this->save();
    }

    public function getDescription($param)
    {
        return $this->descriptionParams[$param] ?? null;
    }
}
