<?php 

namespace App\Controller;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Services\CartService;
use App\Services\CartRealProduct;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    // creation de la session stripe
    #[Route('/stripe/checkout', name: 'stripe_checkout')]
    public function createSession(CartService $cartService)
    {
        Stripe::setApiKey('sk_test_51IBjOmJxItuCvN48kVDdR9Tg52Npf4IJydX0TFxyioJFxo5vdlObzoYYTmiVZ2BD2XqGQkvsWaj8UNNzEz3ekgMo00jPcW004c');

        $domain = 'https://localhost:8000';  // domaine https

        /** @var CartRealProduct[] $detailCart */
        $Cart = $cartService->detail();
   
        $productForStripe = [];

        /** @var User $user */
        $user = $this->getUser();

        foreach($Cart["detailCart"] as $item)  // parcours la commande
         {
            $productForStripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $item->getPrice(),
                    'product_data' => [
                        'name' => $item->getProduct()->getProduit()->getDesignation(),
                        'images' => [
                           // $domain . $item.product.imagePath 
                        ]
                    ]
                ],
                'quantity' => $item->getQty()
            ];
        }
        
        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),
            'payment_method_types' => [
                'card',
            ],
            'line_items' => [
                $productForStripe
            ],
            'mode' => 'payment',
              'success_url' => $domain.'/customer/paiement',    // url OK
              'cancel_url' =>   $domain.'/customer/echec',      // url KO
          ]);
            return $this->redirect($checkout_session->url);
              
        
    }
}
