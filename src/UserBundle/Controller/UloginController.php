<?php

namespace UserBundle\Controller;

use AppBundle\Interfaces\MyClientPartController;
use AppBundle\Interfaces\MyController;
use AppBundle\Service\SiteConfig;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use UserBundle\Entity\User;
use UserBundle\Repository\UserRepository;

class UloginController extends MyClientPartController
{
    private $authenticationManager;
    private $tokenStorage;

    public function __construct(
        Container $container,
        AuthenticationManagerInterface $authenticationManager,
        TokenStorageInterface $tokenStorage)
    {
        $this->container = $container;
        $this->authenticationManager = $authenticationManager;
        $this->tokenStorage = $tokenStorage;

        parent::__construct();
    }

    public function getUserDataByTokenULoginAction(Request $request)
    {
        if ($this->isGranted("ROLE_USER")) {
            return $this->redirectToRoute('my_profile');
        }

        try {
            $token = $this->checkULoginToken($request->request->get('token') ?? null);
            if ($token === null) {
                return $this->redirectToRoute('admin_index');
            }
            $data = $this->getResponse($token);
            $this->checkResponseData($data ?? null);

            /** @var UserRepository $userRepo */
            $userRepo = $this->getDoctrine()->getManager()->getRepository(User::class);
            $user = $userRepo->findByIdentity($data['identity']);
            if ($user !== null && $user->getId()) { // Если пользователь уже авторизовывался через эту соц.сеть
                $this->userAuth($user);

                return $this->redirectToRoute('my_profile');

            } else { // Регистрируем
                if ($this->siteConfig->getValue(SiteConfig::PARAM_IS_NEW_USER_REGISTER_ADMIN)) {
                    return $this->userRegister($data, $request);
                } else {
                    return $this->redirectToRoute('homepage');
                }
            }

        } catch (\LogicException $exception) {
            dump($exception);
            //return $this->redirectToRoute('home');
        }
    }

    /**
     * Авторизуем пользователя
     *
     * @param User $user
     */
    public function userAuth($user)
    {
        $unauthenticatedToken = new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            'user'
        );

        $authenticatedToken = $this
            ->authenticationManager
            ->authenticate($unauthenticatedToken);

        $this->tokenStorage->setToken($authenticatedToken);
    }

    /**
     * Получение ответа от uLogin по полученному токену
     *
     * @param $token
     * @return mixed|null
     */
    private function getResponse($token)
    {
        $uLoginResponse = @file_get_contents(
                "http://ulogin.ru/token.php?token={$token}&host={$_SERVER['HTTP_HOST']}"
            ) ?? null;

        if (!isset($uLoginResponse) || is_null($uLoginResponse)) {
            throw new \LogicException('Не получен ответ от uLogin!');
        }

        $data = json_decode($uLoginResponse, true);

        return $data ?? null;
    }

    /**
     * Проверка полученного токена на корректность
     *
     * @param $token
     * @return string
     */
    private function checkULoginToken($token)
    {
        if (
            !isset($token) || empty($token) ||
            !$this->isValidMd5($token)
        ) {
            return null;

            //throw new \LogicException('Токена не существует!');
        }

        return $token;
    }

    /**
     * Проверка является ли строка хэшем MD5
     *
     * @param $str
     * @return false|int
     */
    private function isValidMd5($str)
    {
        return preg_match('/^[a-f0-9]{32}$/', $str);
    }

    /**
     * Создание локального токена и его сохранение в сессию
     *
     * @param $data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | false
     */
    public function userRegister($data, Request $request)
    {
        $token = md5(rand(2132345, 3254546435) . rand(4324532532, 4534246235) . time());

        $session = $request->getSession();

        $session->set('ulogin_' . $token, $data);
        $session->set('ulogin_' . $token, $data);

        return $this->redirectToRoute('register_token', ['token' => $token]);
    }

    /**
     * @param $data
     * @return bool
     */
    private function checkResponseData($data)
    {
        if (is_null($data)) {
            throw new \LogicException('Не получены ответ от uLogin!');
        }

        if (isset($data['error'])) {
            throw new \LogicException("Ответ uLogin: {$data['error']}");
        }

        if (
            !isset($data['first_name']) || empty($data['first_name']) ||
            !isset($data['last_name']) || empty($data['last_name']) ||
            !isset($data['identity']) || empty($data['identity'])
        ) {
            throw new \LogicException('Не получены необходимые данные от uLogin!');
        } else {
            return true;
        }
    }

    //Валидация еще не готова
    public function validateValue(&$model)
    {
        $validator = $this->container->get('validator');
        $errors = $validator->validate($model);

        if (count($errors) > 0) {
            $errorsString = (string)$errors;

            return new Response($errorsString);
        }

        return true;
    }
}
