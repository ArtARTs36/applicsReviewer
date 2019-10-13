<?php

namespace UserBundle\Controller;

use AppBundle\Interfaces\MyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use UserBundle\Entity\UserRole;
use UserBundle\Entity\User;
use UserBundle\Repository\UserRepository;

class UserController extends MyController
{
    public function adminLoginAction()
    {
        if ($this->isGranted(UserRole::ROLE_ADMIN_IDENTITY)) {
            return $this->redirectToRoute('admin_index');
        }

        return $this->render('@User/admin/login.html.twig', [
        ]);
    }

    /**
     * Личный кабинет пользователя
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function myProfileAction()
    {
        if (!$this->isGranted(UserRole::ROLE_USER_IDENTITY)) {
            return $this->redirectToRoute('admin_login');
        }

        return $this->render('@User/user/my.profile.html.twig', [
        ]);
    }

    public function otherProfileAction(Request $request)
    {
        $userName = $request->get('username');
        if (!$userName) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $userName));
        }

        $myUser = $this->getUser();
        if ($myUser && $userName == $myUser->getUserName()) {
            return $this->redirectToRoute('my_profile');
        }

        /** @var UserRepository $userRepo */
        $userRepo = $this->getDoctrine()->getRepository(User::class);

        /** @var User $user */
        $user = $userRepo->loadUserByUsername($userName);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $userName));
        }

        return $this->render('@User/user/other.profile.html.twig', [
            'user' => $user,
            'myUser' => $myUser
        ]);

    }

    /*
    public function recoverPasswordAction(Request $request)
    {
        $user = $this->getUser();
        $userAccount = $user->getAccount();

        if (!$userAccount->getTokenRecover())
            return $this->redirectToRoute('user');

        $changePasswordModel = new ChangePasswordModel();
        $formChangePassword = $this->createForm(ChangePasswordForm::class, $changePasswordModel);
        $formChangePassword->handleRequest($request);

        if ($formChangePassword->isSubmitted() && $formChangePassword->isValid()) {

            $encoder = $this->get('security.password_encoder');

            $password = $encoder->encodePassword($user, $changePasswordModel->password);
            $user->setPassword($password);
            $user->setTokenRecover(null);

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user');

        }
        return $this->render('@User/security/recover.html.twig', [
            'recover_form' => $formChangePassword->createView()
        ]);
    }*/
}
