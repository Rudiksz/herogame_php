<?php

declare(strict_types=1);
include 'autoloader.php';


use PHPUnit\Framework\TestCase;

final class PlayerTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $character = new Character(
            'test',
            'Test',
            "Test Character",
            [
              Character::STAT_HEALTH=> [100, 100],
              Character::STAT_STRENGTH => [100, 100],
              Character::STAT_DEFENCE => [100, 100],
              Character::STAT_SPEED => [100, 100],
              Character::STAT_LUCK => [100, 100],
            ],
            [],
        );

        $this->assertInstanceOf(
            Player::class,
            new Player('test', $character, Player::TYPE_HUMAN)
        );
    }

    public function testCanBeCreatedWithRandomStats(): void
    {
        $character = new Character(
            'test',
            'Test',
            "Test Character",
            [
              Character::STAT_HEALTH => [0, 100],
              Character::STAT_STRENGTH => [100, 100],
              Character::STAT_DEFENCE => [100, 0],
              Character::STAT_SPEED => [100, 100],
              Character::STAT_LUCK => [100, 100],
            ],
            [],
        );

        $player = new Player('test', $character, Player::TYPE_HUMAN);

        $this->assertGreaterThanOrEqual(0, $player->stats[Character::STAT_HEALTH]);
        $this->assertLessThanOrEqual(100, $player->stats[Character::STAT_HEALTH]);

        $this->assertSame($player->stats[Character::STAT_STRENGTH], 100);

        $this->assertGreaterThanOrEqual(0, $player->stats[Character::STAT_DEFENCE]);
        $this->assertLessThanOrEqual(100, $player->stats[Character::STAT_DEFENCE]);

    }
}
