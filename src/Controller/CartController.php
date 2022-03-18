<?php
 
namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

ini_set('display_errors', 'on');
class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mon-panier', name: 'app_cart')]

    public function index(Cart $cart): Response
    {
        $cartComplete = [];

    foreach ($cart->get() as $id => $quantity){
        $cartComplete[] = [
            'product' => $this->entityManager->getRepository(Product::class)->findOneById($id),
            'quantity' => $quantity
        ];
    }

        return $this->render('cart/index.html.twig',[
            'cart' => $cartComplete
        ]);
    }


    #[Route('/cart/add/{id}', name: 'add_to_cart')]
    public function add(Cart $cart, $id)
    {
        $cart->add($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove', name: 'remove_my_cart')]
    public function remove(Cart $cart)
    {
        $cart->remove();
        return $this->redirectToRoute('app_product');
    }

    #[Route('/cart/delete/{id}', name: 'delete_to_cart')]
    public function delete(Cart $cart, $id)
    {
        $cart->delete($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/decrease/{id}', name: 'decrease_to_cart')]
    public function decrese(Cart $cart, $id)
    {
        $cart->decrease($id);

        return $this->redirectToRoute('app_cart');
    }
}
