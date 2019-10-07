<?php

namespace MobilePushBundle\Sender;

use MobilePushBundle\Interfaces\MobilePushInterface;

class PushAllMobilePushSender extends MobilePushInterface
{
    const LINK_FEED = 'https://pushall.ru/?fs=5195&key=5bad8897b8ca87eab167971d94b55165';
    const API_KEY = '309e2dfe9b87ec8ef58c4b3f423e9f9a';
    const APPLICATION_ID = "91261";
    const PRIORITY = 1;

    private $answer;

    /**
     * Входная точка:
     *
     *  Формируем массив для отправки на PushAll
     *  Отправляем в this->send()
     *
     * @param $title
     * @param $message
     * @param null $url
     * @return bool|mixed|null
     */
    public function push($title, $message, $url = null)
    {
        $array = [
            "type" => "self",
            "id" => self::APPLICATION_ID,
            "key" => self::API_KEY,
            "text" => $message,
            "title" => $title,
            "priority" => $this->getPriority(),
        ];
        if ($url !== null) {
            $array['url'] = $url;
        }

        return $this->send($array);
    }

    /**
     * Определяем приоритет PUSH-уведомления:
     *
     * Если в абстрактном классе можно отправлять звуковые: 1
     * Если в классе установлен низкий приоритет: -1
     * В иных случаях: 0
     *
     * @return int
     */
    private function getPriority()
    {
        if ($this->isSound()) {
            return 1;
        } elseif(self::PRIORITY == '-1') {
            return -1;
        }

        return 0;
    }

    private function send($arraySend)
    {
        curl_setopt_array(
            $ch = curl_init(), array(
                CURLOPT_URL => "https://pushall.ru/api.php",
                CURLOPT_POSTFIELDS => $arraySend,
                CURLOPT_RETURNTRANSFER => true
            )
        );
        $result = curl_exec($ch);
        curl_close($ch);

        return $this->analyseAnswer($result);
    }

    protected function analyseAnswer($result)
    {
        $result = json_decode($result, true) ?? null;

        $this->setAnswer($result);

        if (!is_array($result)) {
            return null;
        }

        if ($result['success'] == 1) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param mixed $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }
}
