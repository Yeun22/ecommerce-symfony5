<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected  $productRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    protected function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    protected function saveCart(array $cart)
    {
        $this->session->set('cart', $cart);
    }
    public function add(int $id)
    {
        // 1. S'il existe retrouver le pannier (sous forme de tableau)
        //2. Si ^pas de pannier le créer (array vide)
        $cart = $this->getCart();

        //3 Vois si $id existe
        //4. Si oui augmenter la qté sinon l'initailiser à 1
        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }
        $cart[$id]++;

        //5 . Enregistre la MAJ du pannier dans la session 

        $this->saveCart($cart);
    }

    public function remove(int $id)
    {
        //On recupère la session
        $cart = $this->getCart();
        //On supprime de la session à l'identifiant id
        unset($cart[$id]);
        //On met a jour la session
        $this->saveCart($cart);
    }
    public function decrement(int $id)
    {
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            return;
        }

        //Soit le produit est à 1 et on suppr
        if ($cart[$id] === 1) {
            $this->remove($id);
            return;
        }
        //Soit le produit est supérieur à un et donc on enleve jsute 1
        $cart[$id]--;

        $this->saveCart($cart);
    }

    public function getDetailedCartItems(): array
    {
        $detailedCart = [];

        foreach (($this->getCart()) as $id => $qty) {
            $product = $this->productRepository->find($id);
            if (!$product) {
                continue;
            }
            $detailedCart[] = new CartItem($product, $qty);
        }
        return $detailedCart;
    }

    public function getTotal(): int
    {
        $total = 0;

        foreach ($this->getCart() as $id => $qty) {

            $product = $this->productRepository->find($id);
            if (!$product) {
                continue;
            }
            $total += $product->getPrice() * $qty;
        }
        return $total;
    }
}
