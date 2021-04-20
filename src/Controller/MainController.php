<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
    public function showFeatures(): Response
    {
        return $this->render('main/features.html.twig');
    }

    /**
     * @Route ("/pricing", name="pricing")
     */
    public function showPricing(): Response
    {
        return $this->render('main/pricing.html.twig');
    }

    /**
     * @Route ("/contact", name="contact")
     */
    public function showContact(Request $request, MailerInterface $mailer): Response
    {



        $form = $this->createForm(ContactFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $form = $form->getData();
            $email = (new Email())
                ->from($form['from'])
                ->to('todoapppoland@gmail.com')
                ->subject($form['name'])
                ->text($form['message'])
                ;

            $mailer->send($email);

            $this->addFlash('success', 'Mail sended!');
            return $this->redirect($this->generateUrl('contact'));
        }

        return $this->render(
            'main/contact.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
    /*
         * @Route("/contact/send", name="send_mail")

        public function sendMail(Request $request):Response
        {
            dd($request->getData());
        }*/
}
