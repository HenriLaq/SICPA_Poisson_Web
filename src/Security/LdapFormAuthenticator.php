<?php

namespace App\Security;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;

class LdapFormAuthenticator extends AbstractLoginFormAuthenticator
{
    protected $ldap;
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function authenticate(Request $request): PassportInterface
    {
        $username = $request->request->get('username', '');
        $password = $request->request->get('password', '');
        $uid = "uid=" . $username;

        $passwordCredential = new PasswordCredentials($request->request->get('password', ''));
        $userBadge = new userBadge($username);
        //Infos connexion ldap
        $ldap_host = 'ldap-authentification.inra.fr';
        $base_dn = 'dc=inra,dc=fr';

        $connect = ldap_connect($ldap_host);
        ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);

        $read = ldap_search($connect, $base_dn, $uid);
        $info = ldap_get_entries($connect, $read);

        if (count($info) > 1) {
            $bind = false;
            try {
                $bind = ldap_bind($connect, $info[0]["dn"], $password);
            } catch (Exception $e) {
            }

            if ($bind) {
                $passwordCredential->markResolved(); //Connexion réussi
            } else {
                $userBadge = new UserBadge(-1);
            }
        }
        return new Passport($userBadge, $passwordCredential, [new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token'))]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
