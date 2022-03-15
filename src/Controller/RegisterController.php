<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{

    // Injection du formulaire dans la base de donnée

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    // fin de l'injection

    #[Route('/inscription', name: 'app_register')]

    // Quand la fonction est appeler, il appel aussi $request et $encoder
    public function index(Request $request, UserPasswordEncoderInterface $encoder)
    {

        //Création du formulaire a partir de notre Entity User et de l'injection si tout est valide  
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();

            // encode le mot de passe
            $password = $encoder->encodePassword($user,$user->getPassword());

            $user->setPassword($password);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

    
            


        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
