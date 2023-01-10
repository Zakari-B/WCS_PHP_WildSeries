<?php

namespace App\Controller;

use Locale;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class UtilityController extends AbstractController
{
    #[Route('/language', name: 'app_language')]
    public function index(Request $request, string $_locale, RouterInterface $matcher): Response
    {
        $referer = $request->headers->get('referer');
        if (!\is_string($referer) || !$referer) {
            return $this->redirectToRoute("app_index");
        }

        $refererPathInfo = Request::create($referer)->getPathInfo();
        preg_match_all('/\/\w{2,3}\/([\w\/\-]{1,255})/i', $refererPathInfo, $matches);
        $pathWithoutLocale = $matches[1][0];
        $urlBuilder = ($matcher->getContext()->getScheme() . '://' . $matcher->getContext()->getHost() . ':' . $matcher->getContext()->getHttpPort() . '/' . $_locale . '/' . $pathWithoutLocale);
        return $this->redirect($urlBuilder);
    }
}
