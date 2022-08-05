<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class AuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    //redirect si non connecté
    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        $request->getSession()->getFlashBag()->add('note', 'Vous devez être connecté.');
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }
}