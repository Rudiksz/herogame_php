<?php

class Battle
{
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETE = 'complete';

    public string $id;
    public Player $player1;
    public Player $player2;
    public Player $firstPlayer;

    public string $status = self::STATUS_ACTIVE;

    public string $description;


    public $rounds = [];

    function __construct(Player $player1, Player $player2)
    {
        $this->id = uniqid();
        $this->player1 = $player1;
        $this->player2 = $player2;
        $this->setFirstPlayer();
        $this->createDescription();
        if ($this->currentAttacker()->type == Player::TYPE_MONSTER) {
            $this->attack();
        }
    }

    private function createDescription()
    {
        if ($this->player1 == $this->firstPlayer) {
            $this->description =
                'As you walk through the ever-green forests of Emagia, you come accross ' . $this->player2->name . '. He seems to be resting in the shade of a large tree';
        } else {
            $this->description =
                'As you walk through the ever-green forests of Emagia, ' . $this->player2->name . ' ambushes you from behind a large tree.';
        }
    }

    private function setFirstPlayer()
    {
        if (
            $this->player1->stats[Character::STAT_SPEED] !=
            $this->player2->stats[Character::STAT_SPEED]
        ) {
            return $this->firstPlayer = $this->player1->stats[Character::STAT_SPEED] >
                $this->player2->stats[Character::STAT_SPEED]
                ? $this->player1
                : $this->player2;
        }
        if (
            $this->player1->stats[Character::STAT_LUCK] !=
            $this->player2->stats[Character::STAT_LUCK]
        ) {
            return $this->firstPlayer = $this->player1->stats[Character::STAT_LUCK] >
                $this->player2->stats[Character::STAT_LUCK]
                ? $this->player1
                : $this->player2;
        }

        // If both speed and luck are the same, the player is choosen randomly
        // ... using pure luck :)
        $this->firstPlayer = rand() % 2 == 0 ? $this->player1 : $this->player2;
    }

    private function lastRound()
    {
        return count($this->rounds) != 0 ? end($this->rounds) : null;
    }

    private function currentAttacker()
    {
        return $this->lastRound()->defender ?? $this->firstPlayer;
    }

    private function currentDefender()
    {
        return $this->lastRound()->attacker ??
            ($this->firstPlayer == $this->player1 ? $this->player2 : $this->player1);
    }

    public function winner()
    {
        return $this->player1->stats[Character::STAT_HEALTH] > $this->player2->stats[Character::STAT_HEALTH]
            ? $this->player1 : $this->player2;
    }

    public function isComplete(): bool
    {
        return $this->player1->stats[Character::STAT_HEALTH] == 0 ||
            $this->player2->stats[Character::STAT_HEALTH] == 0 ||
            count($this->rounds) == GameConfig::battleRoundLimit;
    }

    public function attack()
    {
        if ($this->status == self::STATUS_COMPLETE) return;
        $this->rounds[] = new BattleRound($this->currentAttacker(), $this->currentDefender());


        if ($this->isComplete()) {
            return $this->status = self::STATUS_COMPLETE;
        }

        // Monsters counter attack automatically
        if ($this->currentAttacker()->type == Player::TYPE_MONSTER) {
            $this->attack();
            if ($this->isComplete()) {
                $this->status = self::STATUS_COMPLETE;
            }
        }
    }

    public function autoFight()
    {
        if ($this->status == self::STATUS_COMPLETE) return;
        $this->attack();
        $this->autoFight();
    }
}




