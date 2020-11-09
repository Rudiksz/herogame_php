<?php

declare(strict_types=0);
include 'autoloader.php';


use PHPUnit\Framework\TestCase;

final class BattleTest extends TestCase
{
    private $character1;
    private $character2;
    private $player1;
    private $player2;

    protected function setUp(): void
    {
        $this->character1 = new Character(
            'test',
            'Test',
            "Test Character",
            [
              Character::STAT_HEALTH => [100, 100],
              Character::STAT_STRENGTH => [1, 1],
              Character::STAT_DEFENCE => [0, 0],
              Character::STAT_SPEED => [100, 100],
              Character::STAT_LUCK => [0, 0],
            ],
            [],
        );

        $this->character2 = new Character(
            'test',
            'Test',
            "Test Character",
            [
              Character::STAT_HEALTH => [100, 100],
              Character::STAT_STRENGTH => [1, 1],
              Character::STAT_DEFENCE => [0, 0],
              Character::STAT_SPEED => [100, 100],
              Character::STAT_LUCK => [0, 0],
            ],
            [],
        );

        $this->player1 = new Player('test', $this->character1, Player::TYPE_HUMAN);
        $this->player2 = new Player('test', $this->character2, Player::TYPE_MONSTER);
    }


    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            Battle::class,
            new Battle($this->player1, $this->player2)
        );
    }

    public function testFirstPlayerBySpeed(): void
    {
        $this->player1->stats[Character::STAT_SPEED] = 2;
        $this->player2->stats[Character::STAT_SPEED] = 1;
        $battle = new Battle($this->player1, $this->player2);
        $this->assertSame($battle->firstPlayer, $this->player1);

        $this->player1->stats[Character::STAT_SPEED] = 1;
        $this->player2->stats[Character::STAT_SPEED] = 2;
        $battle = new Battle($this->player1, $this->player2);
        $this->assertSame($battle->firstPlayer, $this->player2);
    }

    public function testFirstPlayerByLuck(): void
    {
        $this->player1->stats[Character::STAT_LUCK] = 2;
        $this->player2->stats[Character::STAT_LUCK] = 1;
        $battle = new Battle($this->player1, $this->player2);
        $this->assertSame($battle->firstPlayer, $this->player1);

        $this->player1->stats[Character::STAT_LUCK] = 1;
        $this->player2->stats[Character::STAT_LUCK] = 2;
        $battle = new Battle($this->player1, $this->player2);
        $this->assertSame($battle->firstPlayer, $this->player2);
    }

    public function testFirstPlayerRandom(): void
    {
        $battle = new Battle($this->player1, $this->player2);
        $this->assertContains($battle->firstPlayer, [$this->player1, $this->player2]);

        $battle = new Battle($this->player1, $this->player2);
        $this->assertContains($battle->firstPlayer, [$this->player1, $this->player2]);

        $battle = new Battle($this->player1, $this->player2);
        $this->assertContains($battle->firstPlayer, [$this->player1, $this->player2]);

        $battle = new Battle($this->player1, $this->player2);
        $this->assertContains($battle->firstPlayer, [$this->player1, $this->player2]);        

        $battle = new Battle($this->player1, $this->player2);
        $this->assertContains($battle->firstPlayer, [$this->player1, $this->player2]);
    }

    public function testFirstPlayerHuman(): void
    {
        $this->player1->stats[Character::STAT_SPEED] = 2;
        $this->player2->stats[Character::STAT_SPEED] = 1;
        $battle = new Battle($this->player1, $this->player2);
        $this->assertEmpty($battle->rounds);
    }

    public function testFirstPlayerMonster(): void
    {
        $this->player1->stats[Character::STAT_SPEED] = 1;
        $this->player2->stats[Character::STAT_SPEED] = 2;
        $battle = new Battle($this->player1, $this->player2);
        $this->assertNotEmpty($battle->rounds);
    }

    public function testAttack(): void
    {
        $this->player1->stats[Character::STAT_SPEED] = 2;
        $this->player2->stats[Character::STAT_SPEED] = 1;
        $battle = new Battle($this->player1, $this->player2);
        $battle->attack();
        $this->assertSame(count($battle->rounds), 2);
    }    
    
    public function testAttackWhenComplete(): void
    {
        $this->player1->stats[Character::STAT_SPEED] = 2;
        $this->player2->stats[Character::STAT_SPEED] = 1;
        $battle = new Battle($this->player1, $this->player2);
        $battle->status = Battle::STATUS_COMPLETE;
        $battle->attack();
        $this->assertSame(count($battle->rounds), 0);
    }

    public function testAutoFight(): void
    {
        $this->player1->stats[Character::STAT_SPEED] = 2;
        $this->player2->stats[Character::STAT_SPEED] = 1;
        $battle = new Battle($this->player1, $this->player2);
        $battle->autoFight();
        $this->assertNotEmpty($battle->rounds);
        $this->assertSame($battle->status, Battle::STATUS_COMPLETE);
    }

    public function testRoundLimit(): void
    {
        $this->player1->stats[Character::STAT_STRENGTH] = 1;
        $this->player1->stats[Character::STAT_DEFENCE] = 1;
        $this->player2->stats[Character::STAT_STRENGTH] = 1;
        $this->player2->stats[Character::STAT_DEFENCE] = 1;
        $battle = new Battle($this->player1, $this->player2);
        $battle->autoFight();
        $this->assertNotEmpty($battle->rounds);
        $this->assertSame($battle->status, Battle::STATUS_COMPLETE);
        $this->assertSame(count($battle->rounds), GameConfig::battleRoundLimit);
    }

    public function testWinner(): void
    {
        $this->player1->stats[Character::STAT_STRENGTH] = 1000;
        $this->player1->stats[Character::STAT_SPEED] = 2;
        $this->player2->stats[Character::STAT_SPEED] = 1;
        $battle = new Battle($this->player1, $this->player2);
        $battle->attack();
        $this->assertNotEmpty($battle->rounds);
        $this->assertSame($battle->status, Battle::STATUS_COMPLETE);
        $this->assertSame($battle->winner(), $this->player1);
    }

}