<?php

use AppBundle\Service\SiteConfig;

class Installer
{
    const ACTION_CONFIG_SAVE = 'save';
    const ACTION_CONFIG_EDIT = 'edit';

    const APP_VERSION = 'release 1.0.0';

    private $password = 'fk$rfmfw__d3wl_d3r$23k_kwfwkcea-__d3-_d$w';

    /** @var SiteConfig */
    private $newConfig;

    public function init()
    {
        $this->newConfig = new SiteConfig();

        $this->newConfig->setValue(SiteConfig::PARAM_IS_NEW_USER_REGISTER_ADMIN, true);
        $this->newConfig->setValue(SiteConfig::PARAM_STAT_REFRESH, 10);
        $this->newConfig->setValue(SiteConfig::PARAM_CRON_KEY, 'jfej#nr3-Mr3kws');

        $this->newConfig->setValue(SiteConfig::PARAM_FOOTER_PHONE_1, '89204306583');
        $this->newConfig->setValue(SiteConfig::PARAM_FOOTER_PHONE_2, '89304111808');
        $this->newConfig->setValue(SiteConfig::PARAM_FOOTER_EMAIL, 'tprava36@mail.ru');
        $this->newConfig->setValue(SiteConfig::PARAM_FOOTER_ADDRESS, '394030, г. Воронеж , ул. Свободы 65, офис 8');

        $actionTemplate = '';
        switch ($_POST['action']) {
            case self::ACTION_CONFIG_SAVE:
                $actionTemplate =  $this->install();
                break;

            case self::ACTION_CONFIG_EDIT:
                $actionTemplate = $this->getForm();
                break;

            default:
                $actionTemplate = $this->getPasswordForm();
                break;
        }

        $versionBlock = $this->checkInputPassword() ? 'Вы устанавливаете applicsReviewer: '. self::APP_VERSION . '<br/> <br/>' : '';

        echo <<<HTML
<html>
<head>
<title>Установка приложения</title>
</head>
<body>
{$versionBlock}
{$actionTemplate}
<style>
body {
padding:15px;
background:#eee;
}
input[type="text"], input[type="submit"], input[type="password"] {
width:100%;
padding:8px;
border-radius:5px;
border:1px solid #ccc;
}
input[type="submit"] {
cursor:pointer;
padding:10px;
}
.form-group{
padding:5px;
}
</style>
</body>
</html>
HTML;

    }

    private function getPasswordForm()
    {
        $form = '<form method="POST"><input type="hidden" name="action" value="' . self::ACTION_CONFIG_EDIT . '">
Установка не была произведена! <br/>
Введите пароль полученный от разработчика:
<div class="form-group">
<input type="password" name="password">
</div>
<input type="submit">
</form>';

        return $form;
    }

    private function getForm()
    {
        setcookie('password', $_POST['password']);

        if ($_POST['password'] != $this->password) {
            return 'Вы ввели неверный пароль!';
        }

        $form = '<form method="POST"><input type="hidden" name="action" value="' . self::ACTION_CONFIG_SAVE . '">';

        foreach ($this->newConfig->descriptionParams as $param => $description) {
            $type = $this->newConfig->typeParams[$param];

            if ($type == 'string') {
                $form .= $description . '<div class="form-group"><input type="text" name="v[' . $param . ']" value="' . $this->newConfig->getValue($param) . '" /> </div>';
            } elseif ($type == 'boolean') {
                $form .= $description . '<div class="form-group"><input type="radio" name="v[' . $param . ']" value="1" ' . $this->fieldRadioChecked($param, true) . ' /> Да
                <input type="radio" name="v[' . $param . ']" value="0" ' . $this->fieldRadioChecked($param, false) . '/> Нет </div>';
            }
        }

        $form .= "<input type='submit'></form>";

        return $form;
    }

    private function install()
    {
        if (!$this->checkInputPassword()) {
            return 'Вы ввели неверный пароль!';
        }

        foreach ($_POST['v'] as $key => $value) {
            $this->newConfig->setValue($key, $value);
        }

        foreach ($this->newConfig->config as $param => &$value) {
            if ($this->newConfig->typeParams[$param] == 'boolean') {
                $value = (boolean) $value;
            }
        }

        $this->newConfig->save();

        file_put_contents(__DIR__ . '/../app/config/installer.lock', self::APP_VERSION);

        Header("Location: /");
    }

    private function fieldRadioChecked($param, $bool)
    {
        return $this->newConfig->getValue($param) == $bool ? 'checked' : '';
    }

    private function checkInputPassword()
    {
        return $this->getInputPassword() == $this->password;
    }

    private function getInputPassword()
    {
        return $_COOKIE['password'] ?? null;
    }
}
