<?

namespace App\Domain;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

#[Entity, Table(name: 'card')]
final class Card
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id;

    #[Column(type: 'string', nullable: false)]
    private $numero_card;

    #[Column(type: 'string', nullable: false)]
    private $color;

    #[Column(type: 'boolean', nullable: false, options: ['default' => false])]
    private $discarded;

    #[Column(type: 'boolean', nullable: false, options: ['default' => false])]
    private $returned;


    #[ManyToOne(targetEntity: Game::class, inversedBy: 'cards')]
    #[JoinColumn(name: 'game_id', referencedColumnName: 'id')]
    private Game $game;


    public function __construct(string $numero,string $color, $game,$discarded,$returned)
    {
        $this->color = $color;
        $this->discarded = $discarded;
        $this->returned = $returned;
        $this->game = $game;
        $this->numero_card = $numero;
    }


    public function getId(): string

    {
        return $this->id;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function getDiscarded(): bool
    {
        return $this->discarded;
    }

    public function getReturned(): bool
    {
        return $this->returned;
    }

    public function setDiscarded(bool $discarded): void
    {
        $this->discarded = $discarded;
    }

    public function setReturned(bool $returned): void
    {
        $this->returned = $returned;
    }

    public function getNumeroCard(): string
    {
        return $this->numero_card;
    }

    public function __toString()
    {
        return $this->numero_card;
    }

}

