<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Service\FlashMessageBuilder;
use App\Service\ValidationService;



class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
    * @Route("/forgot_password", name="app_forgot_password")
    */
    public function forgotPassword(LoggerInterface $logger, Request $request, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator, FlashMessageBuilder $flashMessageBuilder): Response
    {

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByEmail($email);
            /* @var $user User */
            if ($user === null) {
                $flashMessageBuilder->addErrorMessage('Unknown E-Mail', 'email');
                return $this->redirectToRoute('app_forgot_password');
            }

            $token = $tokenGenerator->generateToken();
            try {
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $flashMessageBuilder->addErrorMessage("Token could not be set!");
                $logger->error($e->getMessage());
                return $this->redirectToRoute('login');
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Reset Password'))
                ->setFrom('kbepa01@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/password_reset_email.html.twig',
                        array(
                            'url' => $url,
                            'email' => $email,
                            'name' => $user->getUsername())
                    ),
                    'text/html'
                );

            $flashMessageBuilder->addSuccessMessage('Reset email sent successfully');
            $mailer->send($message);

            return $this->redirectToRoute('app_forgot_password');
        }

        // if $request->isMethod('GET')
        return $this->render('security/forgot_password.html.twig');
    }

    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder, FlashMessageBuilder $flashMessageBuilder, ValidationService $validation)
    {

        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);
            /* @var $user User */

            if ($user === null) {
                $flashMessageBuilder->addErrorMessage('Token unknown');
                return $this->redirectToRoute('login');
            }
            $user->setResetToken(null);
            $user->setPassword($request->request->get('password'));
            if($validation->isValid($user)) {
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
                $entityManager->persist($user);
                $entityManager->flush();
                $flashMessageBuilder->addSuccessMessage('Password changed');
                return $this->redirectToRoute('login');
            }
            return $this->render('security/reset_password.html.twig', ['token' => $token]);

        } else {
            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }
    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
