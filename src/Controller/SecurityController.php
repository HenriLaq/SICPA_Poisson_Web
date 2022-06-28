<?php

namespace App\Controller;

use App\Entity\User;
use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(EntityManagerInterface $entityManager, Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');
        //Récupération des credentials
        $username = htmlspecialchars($username);
        $password = htmlspecialchars($password);
        //Impossible qu'il y ai des caracteres speciaux dans le username
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $username))
        {
            $username = "a";
        }
        $uid = "uid=" . $username;
        
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );
        //Infos connexion ldap
        $ldap_host = 'ldap-authentification.inra.fr';
        $base_dn = 'dc=inra,dc=fr';

        //Connexion au ldap
        $connect = ldap_connect($ldap_host); // Vérification syntaxique plausibilité de connexion

        //Options du ldap
        ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);

        //Recherche de l'utilisateur
        $read = ldap_search($connect, $base_dn, $uid);
        //Recherche des infos concernant l'utilisateur
        $info = ldap_get_entries($connect, $read);
        if (count($info) > 1) {
            $bind = false;
            //Try catch pour couvrir les exceptions
            try {
                //Bind avec le password pour vérifier l'autorisation
                $bind = ldap_bind($connect, $info[0]["dn"], $password);
            } catch (Exception $e) {
            }

            if ($bind) {
                $user = $entityManager->getRepository(User::class)->findOneBy(['username' => $credentials['username']]);
                if ($user == null) {
                    $user = new User();
                    $user->setPassword("ldap");
                    $user->setUsername($credentials['username']);
                    $entityManager->persist($user);
                    $entityManager->flush();
                }
            }
        }
        if (isset($user)) {
            $token = new UsernamePasswordToken($user, $user->getPassword(), "main", $user->getRoles());
            $this->get("security.token_storage")->setToken($token);
            return $this->redirectToRoute('experimentation_index');
        }


        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
