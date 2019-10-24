<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use App\Entity\User; 
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Form\Type\AuthenticationType;
use ReCaptcha\ReCaptcha;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // if ($this->getUser()) {
        //    $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername(); 
        $form = $this->createForm(AuthenticationType::class);
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error,'form' => $form->createView()]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
     
    /**
     * @Route("/forgot", name="app_forgot_password", methods="GET|POST")
     */
    public function forgotPassword(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator): Response
    {
        if ($request->isMethod('POST')) {
             // Captcha verification
             $res = $request->request->get('g-recaptcha-response');
             $secret = $_ENV['APP_RECAPTCHA_SECRET_KEY'];
             $recaptcha = new ReCaptcha($secret);
             $resp = $recaptcha->verify($res);
 
             if (!$resp->isSuccess()) {
                $this->addFlash('error','Invalid Captcha');
                throw new CustomUserMessageAuthenticationException('Invalid Captcha');
             }else if($resp->isSuccess()){
                $email = $request->request->get('email');
                $entityManager = $this->getDoctrine()->getManager();
                $user = $entityManager->getRepository(User::class)->findOneByEmail($email);
               /* @var $user User */
                if ($user === null) {
                     $this->addFlash('error','Email Unknow');
                     return $this->redirectToRoute('app_login');
                }
                $token = $tokenGenerator->generateToken();
                try{
                    $user->setResetToken($token);
                    $entityManager->flush();
                } catch (\Exception $e) {
                    $this->addFlash('error', $e->getMessage());
                    return $this->redirectToRoute('app_login');
                }
                $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
                $message = (new \Swift_Message('Change your password'))
                    ->setFrom(array('noreply@gmail.com'))
                    ->setTo($user->getEmail())
                    ->setBody(
                    $this->renderView(
                        'security/emails/resetPasswordMail.html.twig',
                        [
                            'user'=>$user,
                            'url'=>$url
                        ]
                    ),
                        'text/html'
                    );
                $numSent=$mailer->send($message);
                if ($numSent==false){
                    $this->addFlash('error','Fail to send email');
                }
                else{
                    $this->addFlash('success','Email send to '.$user->getEmail());
            }
                return $this->redirectToRoute('app_login');
             }
        }
        $form = $this->createForm(AuthenticationType::class);
        return $this->render('security/forgotPassword.html.twig',['form' => $form->createView()]);
    }
 
    /**
     * @Route("/reset/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);
            /* @var $user User */
            if ($user === null) {
                $this->addFlash('error','Unable to verify token');
                return $this->redirectToRoute('app_login');
            }
            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager->flush();
            $this->addFlash('success','Password updated');
            return $this->redirectToRoute('app_login');
        }else {
            return $this->render('security/resetPassword.html.twig', ['token' => $token]);
        }
 
    }
}
