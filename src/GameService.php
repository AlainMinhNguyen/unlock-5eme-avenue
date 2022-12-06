<?php

namespace App;

use App\Domain\Game;
use App\Domain\Card;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

final class GameService
{
    private EntityManager $em;

    public function __construct(EntityManager $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function createCard($numero,$couleur,$game,$discarded,$returned){

        $newCard = new Card($numero,$couleur,$game,$discarded,$returned);

        $this->logger->info("Card {$numero} signed up");

        $this->em->persist($newCard);
        $this->em->flush();

        return $newCard;
    }

    public function createGame()
    {
        $game = new Game();
        $this->em->persist($game);
        $this->em->flush();
        // transforme $game to a string
        $this->createCard(5,"grey",$game,false,true);
        $this->createCard(8,"blue",$game,false,false);
        $this->createCard(11,"grey",$game,false,false);
        $this->createCard(15,"blue",$game,false,false);
        $this->createCard(22,"red",$game,false,false);
        $this->createCard(25,"red",$game,false,false);
        $this->createCard(30,"grey",$game,false,false);
        $this->createCard(37,"blue",$game,false,false);
        $this->createCard(42,"blue",$game,false,false);
        $this->createCard(43,"grey",$game,false,false);
        $this->createCard(44,"red",$game,false,false);
        $this->createCard(52,"grey",$game,false,false);
        $this->createCard(58,"red",$game,false,false);
        $this->createCard(66,"grey",$game,false,false);
        $this->createCard(73,"grey",$game,false,false);
        $this->createCard(86,"grey",$game,false,false);
        $this->createCard(88,"grey",$game,false,false);
        $this->createCard(92,"grey",$game,false,false);
        $this->createCard("C","grey",$game,false,false);
        $this->createCard("F","grey",$game,false,false);
        $this->createCard("G","yellow",$game,false,false);
        $this->createCard("H","grey",$game,false,false);
        $this->createCard("R","yellow",$game,false,false);
        $this->createCard("V","grey",$game,false,false);
        $this->createCard("W","green",$game,false,false);
        return $game;
    }

    public function convertNum($num){
    $card = $this->em->getRepository(Card::class)->findBy(['numero_card' => $num]);
    if ($card != null) {
        $str = substr(serialize($card[0]), 55, 60);
        $str = substr($str, 0, strpos($str, ";"));
        return (int)$str;
    }
    else {
        return 0;
    }
}

    public function getAllCards(){
        $cards = $this->em->getRepository(Card::class)->findAll();
        return $cards;
    }

    public function getAllReturnedCards(){
        $cards = $this->em->getRepository(Card::class)->findBy(['returned' => true]);
        return $cards;
    }

    public function getAllDiscardedCards(){
        $cards = $this->em->getRepository(Card::class)->findBy(['discarded' => true]);
        return $cards;
    }

    public function returnCard($id){
        $id = self::convertNum($id);
            if ($id != 0) {
            $card = $this->em->getRepository(Card::class)->find($id);
            if ($card != null && $card->getDiscarded() == false) {
            $card->setReturned(true);
            $this->em->persist($card);
            $this->em->flush();
            }
}
    }

    public function discard(string $numero): void
    {
        $numero = self::convertNum($numero);
        if ($numero != 0) {
            $card = $this->em->getRepository(Card::class)->findOneBy(['id' => $numero]);
            if ($card->getReturned() == true) {
                $card->setDiscarded(true);
                $card->setReturned(false);
                $this->em->persist($card);
                $this->em->flush();
            }
        }
        
    }

    public function assemble($card1, $card2)
    {
        if ($card1->getColor() == "blue" && $card2->getColor() =="red"|| $card1->getColor() == "red" && $card2->getColor() =="blue")
            {
                return $card1->getNumeroCard() +$card2->getNumeroCard();
            }
        else if ($card1->getColor() == null || $card2->getColor() == null)
        {
            return $card1->getNumeroCard() + $card2->getNumeroCard();
        }
        else if($card1->getColor() =="grey" || $card1->getColor() =="green" || $card1->getColor() =="yellow" 
                   || $card2->getColor() =="grey" || $card2->getColor() =="green" || $card2->getColor() =="yellow")
            {
                return(0);
            }
    }

    public function assemblyVerification($numero1,$numero2)
    {
        $num1 = self::convertNum($numero1);
        $num2 = self::convertNum($numero2);
        $card1 = $this->em->getRepository(Card::class)->find($num1);
        $card2 = $this->em->getRepository(Card::class)->find($num2);

        if($card1 != null && $card2 != null)
        {
            $assemblyNumber = $this->assemble($card1,$card2);

        }else if($card1 == null && $card2 != null)
        {
            $assemblyNumber = $numero1 + $card2->getNumeroCard();

        } else if($card1 != null && $card2 == null)
        {
            $assemblyNumber = $card1->getNumeroCard() + $numero2;

        }

        if($assemblyNumber != 0)
            {
                $this->returnCard($assemblyNumber);
            }
    
}
    

    public function getCard($id){
        $card = $this->em->getRepository(Card::class)->find($id);
        return $card;
    }

    public function saveGame($game)
    {
        $this->em->persist($game);
        $this->em->flush();
    }


}