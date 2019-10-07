<?php

namespace MobilePushBundle\Interfaces;

abstract class MobilePushInterface
{
    const IS_SOUND = true;

    /**
     * Отправка PUSH-уведомления
     *
     * @param $title
     * @param $message
     * @return mixed
     */
    abstract public function push($title, $message);

    /**
     * Анализ ответа
     *
     * @param $result
     * @return mixed
     */
    abstract protected function analyseAnswer($result);

    protected function isSound()
    {
        return true;
    }

    /**
     * Проверка: ночь ли сейчас
     *
     * @return bool
     * @throws \Exception
     */
    private function isNight()
    {
        $dateTime = new \DateTime();

        $hour = $dateTime->format('H');

        return ($hour > 23 && $hour < 6);
    }
}