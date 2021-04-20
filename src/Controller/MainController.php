<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    /**
     * @Route ("/features", name="features")
     */
    public function showFeatures():Response
    {
        return $this->render('main/features.html.twig');
    }

    /**
     * @Route ("/pricing", name="pricing")
     */
    public function showPricing():Response
    {
       return $this->render('main/pricing.html.twig');
    }

    /**
     * @Route ("/contact", name="contact")
     */
    public function showContact():Response
    {
        return $this->render('main/contact.html.twig');
    }
}
