<?php

namespace UserBundle\Controller;

use AppBundle\Interfaces\MyAdminController;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use UserBundle\Entity\User;
use UserBundle\Entity\UserRole;
use UserBundle\Forms\RegisterUserForm;
use UserBundle\Model\ULoginResponse;
use UserBundle\Service\UserAuthService;

class SecurityController extends MyAdminController
{
    /**
     * @var UserAuthService
     */
    private $authService;

    public function __construct(
        Container $container,
        AuthenticationManagerInterface $authenticationManager,
        TokenStorageInterface $tokenStorage,
        UserAuthService $authService
    )
    {
        $this->container = $container;
        $this->authService = $authService;
    }

    /**
     * @param Request $request
     * @param $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function registerAction(Request $request)
    {
        if ($this->isGranted("ROLE_USER")) {
            return $this->redirectToRoute('admin_action');
        }

        $uLoginResponse = new uLoginResponse(
            $request->get('token'),
            $request->getSession()
        );

        $registerForm = $this->createForm(
            RegisterUserForm::class,
            $uLoginResponse->getUser()
        );

        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            /** @var User $user */
            $user = $registerForm->getData();
            $user->newUser($this->container->get('security.password_encoder'));

            try {
                $roleRepo = $this->getEntityManager()->getRepository(UserRole::class);
                /** @var UserRole $role */
                $role = $roleRepo->find(UserRole::ROLE_ADMIN);

                $user->addRole($role);

                $this->saveUser($user);

                $this->authService->userAuth($user);

                return $this->redirectToRoute('admin_index');

            } catch (\LogicException $exception) {

            }
        }

        $registerForm = $registerForm->createView();

        return $this->render('@User/security/register.html.twig', [
            'registerForm' => $registerForm,
            'uLogin' => $uLoginResponse,
            'user' => $user ?? null
        ]);
    }

    private function saveUser($entity)
    {
        try {
            $this->getDoctrine()->getManager()->persist($entity);
            $this->getDoctrine()->getManager()->flush($entity);
        } catch (\Exception $exception) {

        }
    }

    /*
    public function recoverAction($token, Request $request)
    {
        if (!empty($token)) {
            //@var UserRepository $repo
            $repo = $this->getDoctrine()->getManager()->getRepository(User::class);

            //@var User $user
            $user = $repo->findOneByTokenRecover($token);
            if (is_null($user)) {
                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);

                return $this->redirectToRoute('user_password_recover');
            }
        }

        $recoverModel = new RecoverUserModel();
        $recoverForm = $this->createForm(RecoverUserForm::class, $recoverModel);
        $recoverForm->handleRequest($request);

        if ($recoverForm->isSubmitted()) {

            $email = $recoverModel->getEmail();
            $user = $this->getDoctrine()->getRepository(User::class)->findOneByEmail($email);

            if ($user) {
                $this->get('user.security.recover')->send($user);
            }

            return $this->redirectToRoute('recover');

        }

        return $this->render('@User/security/recover.html.twig', [
            'recover_form' => $recoverForm->createView(),
            'myUser' => $this->getUser()
        ]);
    }*/

    /*
    public function loginAction(Request $request)
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('my_profile');
        }

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@User/security/login.html.twig', array(
            'lastUsername' => $lastUsername,
            'error' => $error,
        ));
    }
    */
}
