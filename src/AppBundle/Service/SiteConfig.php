<?php

namespace AppBundle\Service;

class SiteConfig
{
    const FILE_CONFIG = '../app/config/my_config.json';

    const PARAM_PUSHALL_API_KEY = 'pushall_api_key';
    const PARAM_PUSHALL_APPLICATION_ID = 'pushall_application_id';
    const PARAM_PUSHALL_FEED_LINK = 'pushall_feed_link';

    private $config;

    public function __construct()
    {
        $this->config = $this->getFileConfig();
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
        file_put_contents(self::FILE_CONFIG, json_encode($this->config));
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
}
