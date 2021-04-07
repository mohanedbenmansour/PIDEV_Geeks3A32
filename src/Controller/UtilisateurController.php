<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\SearchEventType;
use App\Form\SearchUserType;
use App\Form\UtilisateurType;
use App\Security\EmailVerifier;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;



class UtilisateurController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }


    /**
     * @Route("/utilisateur", name="utilisateur_index")
     */
    public function index(UtilisateurRepository $utilisateurRepository, Session $session,Request $request): Response
    {
        //besoin de droits admin
        $utilisateur = $this->getUser();
        if(!$utilisateur)
        {
            $session->set("message", "Merci de vous connecter");
            return $this->redirectToRoute('app_login');
        }

        else {
            $utilisateurs=$utilisateurRepository->findAll();
            $form = $this->createForm(SearchUserType::class);

            $search = $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $utilisateurs = $utilisateurRepository->search(
                    $search->get('mots')->getData()
                );
            }
            return $this->render('utilisateur/index.html.twig', [
                'utilisateurs' => $utilisateurs,
                'form' => $form->createView()
            ]);
        }

        return $this->redirectToRoute('home_page');
    }

    /**
     * @Route("/userDashboard", name="dashboard_user_index", methods={"GET"})
     */
    public function DashboardIndex(UtilisateurRepository $utilisateurRepository, Session $session): Response
    {
        //besoin de droits admin
        $utilisateur = $this->getUser();
        if(!$utilisateur)
        {
            $session->set("message", "Merci de vous connecter");
            return $this->redirectToRoute('app_login');
        }

        else if(in_array('ROLE_ADMIN', $utilisateur->getRoles())){
            return $this->render('utilisateur/dashboardIndex.html.twig', [
                'utilisateurs' => $utilisateurRepository->findAll(),
            ]);
        }

        return $this->redirectToRoute('home_page');
    }

    /**
     * @Route("/adminDashboard", name="adminDashboard", methods={"GET"})
     */
    public function dashboard (UtilisateurRepository $utilisateurRepository, Session $session): Response
    {
        //besoin de droits admin
        $utilisateur = $this->getUser();
        if(!$utilisateur)
        {
            $session->set("message", "Merci de vous connecter");
            return $this->redirectToRoute('app_login');
        }

        else if(in_array('ROLE_ADMIN', $utilisateur->getRoles())){
            return $this->render('base_home_page/index.html.twig', [
                'controller_name' => 'BaseHomePageController',
            ]);
        }

        return $this->redirectToRoute('home_page');
    }


    /**
     * @Route("/signUp", name="utilisateur_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, Session $session): Response
    {

        //test de sécurité, un utilisateur connecté ne peut pas s'inscrire
        $utilisateur = $this->getUser();
        //(dd($this->getUser()));
        if($utilisateur)
        {
            $session->set("message", "Vous ne pouvez pas créer un compte lorsque vous êtes connecté");
            return $this->redirectToRoute('membre');
        }

        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword()));

            /* uniquement pour créer un admin
            $role = ['ROLE_ADMIN'];
            $utilisateur->setRoles($role); */
            $session->set("user",$utilisateur);
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $utilisateur,
                (new TemplatedEmail())
                    ->from(new Address('esprit.geeks@hotmail.com', 'geek bot'))
                    ->to($utilisateur->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('utilisateur/confirmation_email.html.twig')
            );


            return $this->redirectToRoute('utilisateur_index');
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */

    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('utilisateur_index');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('utilisateur_index');
    }


    /**
     * @Route("/utilisateur/{id}", name="utilisateur_show", methods={"GET"})
     */
    public function show(Utilisateur $utilisateur): Response
    {
        //accès géré dans le security.yaml
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * @Route("/userDashboard/{id}", name="dashboard_user_show", methods={"GET"})
     */
    public function AdminShow(Utilisateur $utilisateur): Response
    {
        //accès géré dans le security.yaml
        return $this->render('utilisateur/AdminShow.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * @Route("/utilisateur/{id}/edit", name="utilisateur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Utilisateur $utilisateur, UserPasswordEncoderInterface $passwordEncoder, Session $session, $id): Response
    {
        $utilisateur = $this->getUser();
        if($utilisateur->getId() != $id )
        {
            // un utilisateur ne peut pas en modifier un autre
            $session->set("message", "Vous ne pouvez pas modifier cet utilisateur");
            return $this->redirectToRoute('membre');
        }

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->add('imageFile', FileType::class, [
            'mapped' => false,
            'required' => false,
        ]);
        $form->add('coverFile', FileType::class, [
            'mapped' => false,
            'required' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword()));
//photo de profil

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['imageFile']->getData();
            if ($uploadedFile){
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $utilisateur->setImage($newFilename);
            }

//photo de couverture
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['coverFile']->getData();
            if ($uploadedFile){
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newCovername = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newCovername
                );
                $utilisateur->setPhotocover($newCovername);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('utilisateur_index');
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/utilisateur/{id}", name="utilisateur_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Utilisateur $utilisateur, Session $session, $id): Response
    {
        $utilisateur = $this->getUser();
        if($utilisateur->getId() != $id )
        {
            // un utilisateur ne peut pas en supprimer un autre
            $session->set("message", "Vous ne pouvez pas supprimer cet utilisateur");
            return $this->redirectToRoute('membre');
        }

        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token')))
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($utilisateur);
            $entityManager->flush();
            // permet de fermer la session utilisateur et d'éviter que l'EntityProvider ne trouve pas la session
            $session = new Session();
            $session->invalidate();
        }

        return $this->redirectToRoute('home_page');
    }
}