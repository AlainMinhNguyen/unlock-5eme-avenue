<?php
namespace App;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Views\Twig;

class GameController
{
  private $view;
  public $game_id;

  public function __construct(Twig $view, GameService $gameService)
  {
    $this->view = $view;
    $this->gameService = $gameService;
  }

  public function page(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    return $this->view->render($response, 'menu.html.twig');
    return $response;
  }
  
    public function getOne(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $id = $args['id'];
        $card = $this->gameService->getCard($id);
        if($card == null){
          return $this->view->render($response, 'game.html.twig', [
            'id' => "card not found",
        ]);
        }
        return $this->view->render($response, 'game.html.twig', [
            'id' => $card->getId(),
        ]);
        return $response;
    }

    public function getAll(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $cards = $this->gameService->getAllCards();
        if($cards == null){
          return $this->view->render($response, 'game.html.twig', [
            'ids' => "cards not found",
        ]);
        }
        $liste = [];
        for($i=0; $i<count($cards); $i++){
          array_push($liste,$cards[$i]->getId());
        }
        var_dump($liste);

        return $this->view->render($response, 'game.html.twig', [
            'id' => $liste,
        ]);
        return $response;
    }

    public function newGame(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $game = $this->gameService->createGame();
        $this->game_id = $game->getId();
        return $response->withHeader('Location', '../../game')->withStatus(302);
    }
    
    public function getCardsReturned(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
      $listCards = $this->gameService->getAllReturnedCards();
      return $this->view->render($response, 'game.html.twig', [
          'cards' => $listCards,
      ]);
      return $response;
    }

    public function discardCard(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
      $id = $_POST["discard"];
      $this->gameService->discard($id);
      return $response->withHeader('Location', '../../game')->withStatus(302);
    }

    public function returnCard(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
      $id = $_POST['numero_card'];
      $this->gameService->returnCard($id);
      return $response->withHeader('Location', '../../game')->withStatus(302);
    }

    public function combineCard(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
      $id_one = $_POST['combine_one'];
      $id_two = $_POST['combine_two'];
      $this->gameService->assemblyVerification($id_one, $id_two);
      return $response->withHeader('Location', '../../game')->withStatus(302);
    }

    public function saveGame(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
      $this->gameService->saveGame();
      return $response->withHeader('Location', '../../game')->withStatus(302);
    }

}