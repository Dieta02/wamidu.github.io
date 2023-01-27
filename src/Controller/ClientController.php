<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\QrCodeService;
use Symfony\Component\Mailer\MailerInterface;

use Symfony\Bridge\Twig\Mime\TemplatedEmail as MimeTemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

#[Route('/client')]
class ClientController extends AbstractController
{

    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ClientRepository $clientRepository, QrCodeService $qrCodeService, MailerInterface $mailer): Response
    {
        $message = '';
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $clientRepository->save($client, true);

            $qrcode = $qrCodeService->qrcode($client->getId());


            $email = (new Email())
                ->from(new Address('no-reply@wamidu.com', 'WAMIDU'))
                ->to($client->getEmail())
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Wamidu Qr Code !')
                ->html('<header><img src="public/assets/img/logo.png"  alt="wamidu"/></header>
            <div>
            <h4>Salut ' . $client->getSurname() . ' ,</h4>
            <div>Voici ton QrCode qui te permet d accéder aux services wamidu</br>
            <img src="' . $qrcode . '"  alt="wamidu" width="60" height="60"/>
            <p> Ton code personnel est : ' . $client->getCodeUser() . '</p>
            Clique sur ce bouton pour voir ton historique et le nombre de tickets restants
            <button><buton/>
             </div>
            </div>
            <footer><a><img src="assets/img/logo.png"  alt="wamidu"  width="60" height="60"/>WAMIDU</a>
            </br>
            <p> Copyright 2023 WAMIDU</p>
            
            <footer>');
            $mailer->send($email);

            //$this->addFlash('success', $client->getSurname() . ' ' . $client->getName() . ' a été enregistré avec succès');
            $message = ('ajouté avec succes');
            return $this->redirectToRoute('app_client_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form,

        ]);
    }


    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, ClientRepository $clientRepository): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientRepository->save($client, true);

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, ClientRepository $clientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->request->get('_token'))) {
            $clientRepository->remove($client, true);
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }
}