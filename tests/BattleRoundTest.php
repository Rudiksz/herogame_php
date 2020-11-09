<?php

declare(strict_types=0);
include 'autoloader.php';


use PHPUnit\Framework\TestCase;

final class BattleRoundTest extends TestCase
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
            BattleRound::class,
            new BattleRound($this->player1, $this->player2)
        );
    }

    public function testEvade(): void
    {
        // Player2 always evades...
        $this->player2->stats[Character::STAT_LUCK] = 100;
        $round = new BattleRound($this->player1, $this->player2);
        $this->assertSame($round->status, BattleRound::STATUS_MISSED);

        // Player2 never evades...
        $this->player2->stats[Character::STAT_LUCK] = 0;
        $round = new BattleRound($this->player1, $this->player2);
        $this->assertSame($round->status, BattleRound::STATUS_COMPLETED);
    }

    public function testDamage(): void
    {
        

        // Attack < defense
        $this->player1->stats[Character::STAT_STRENGTH] = 1;
        $this->player2->stats[Character::STAT_DEFENCE] = 2;
        $round = new BattleRound($this->player1, $this->player2);
        $this->assertSame($round->damage, 0);
        $this->assertSame($this->player2->stats[Character::STAT_HEALTH], 100);

        // Attack == defense
        $this->player1->stats[Character::STAT_STRENGTH] = 1;
        $this->player2->stats[Character::STAT_DEFENCE] = 1;
        $round = new BattleRound($this->player1, $this->player2);
        $this->assertSame($round->damage, 0);
        $this->assertSame($this->player2->stats[Character::STAT_HEALTH], 100);

        // Attack > defense
        $this->player1->stats[Character::STAT_STRENGTH] = 2;
        $this->player2->stats[Character::STAT_DEFENCE] = 1;
        $round = new BattleRound($this->player1, $this->player2);
        $this->assertSame($round->damage, 1);
        $this->assertSame($this->player2->stats[Character::STAT_HEALTH], 99);
    }

    public function testCanApplySkill(): void
    {
        $skill = new Skill(
            'Test',
            'Test',
            'Test skill',
            Skill::TYPE_ATTACK,
            Skill::TARGET_OWNER,
            Skill::EFFECT_TEMPORARY,
            Character::STAT_DEFENCE,
            1,
            1,
            100
        );

        $this->character1->skills = [$skill];
        $round = new BattleRound($this->player1, $this->player2);
        $this->assertNotEmpty($round->skillModifiers);


        $this->assertSame(0,0);

    }


    public function testCanApplyTemporaryOwnerAttackSkill(): void
    {
        $skill = new Skill(
            'Test',
            'Test',
            'Test skill',
            Skill::TYPE_ATTACK,
            Skill::TARGET_OWNER,
            Skill::EFFECT_TEMPORARY,
            Character::STAT_DEFENCE,
            1,
            1,
            100
        );

        $this->character1->skills = [$skill];
        $oldValue = $this->player1->stats[Character::STAT_DEFENCE];
        $round = new BattleRound($this->player1, $this->player2);
        $this->assertNotEmpty($round->skillModifiers);
        $this->assertSame($this->player1->stats[Character::STAT_DEFENCE], $oldValue);
    }

    public function testCanApplyTemporaryOwnerDefenceSkill(): void
    {
        $skill = new Skill(
            'Test',
            'Test',
            'Test skill',
            Skill::TYPE_DEFENCE,
            Skill::TARGET_OWNER,
            Skill::EFFECT_TEMPORARY,
            Character::STAT_DEFENCE,
            1,
            1,
            100
        );

        $this->character2->skills = [$skill];
        $oldValue = $this->player2->stats[Character::STAT_DEFENCE];
        $round = new BattleRound($this->player1, $this->player2);
        $this->assertNotEmpty($round->skillModifiers);
        $this->assertSame($this->player2->stats[Character::STAT_DEFENCE], $oldValue);
    }

    public function testCanApplyPermanentOwnerAttackSkill(): void
    {
        $skill = new Skill(
            'Test',
            'Test',
            'Test skill',
            Skill::TYPE_ATTACK,
            Skill::TARGET_OWNER,
            Skill::EFFECT_PERMANENT,
            Character::STAT_DEFENCE,
            1,
            1,
            100
        );

        $this->character1->skills = [$skill];
        $oldValue = $this->player1->stats[Character::STAT_DEFENCE];
        $round = new BattleRound($this->player1, $this->player2);
        $this->assertNotEmpty($round->skillModifiers);
        $this->assertSame($this->player1->stats[Character::STAT_DEFENCE], $oldValue+1);
    }

    public function testCanApplyPermanentOwnerDefenceSkill(): void
    {
        $skill = new Skill(
            'Test',
            'Test',
            'Test skill',
            Skill::TYPE_DEFENCE,
            Skill::TARGET_OWNER,
            Skill::EFFECT_PERMANENT,
            Character::STAT_DEFENCE,
            1,
            1,
            100
        );

        $this->character2->skills = [$skill];
        $oldValue = $this->player2->stats[Character::STAT_DEFENCE];
        $round = new BattleRound($this->player1, $this->player2);
        $this->assertNotEmpty($round->skillModifiers);
        $this->assertSame($this->player2->stats[Character::STAT_DEFENCE], $oldValue+1);
    }

    public function testCanApplyTemporaryOpponentAttackSkill(): void
    {
        $skill = new Skill(
            'Test',
            'Test',
            'Test skill',
            Skill::TYPE_ATTACK,
            Skill::TARGET_OPPONENT,
            Skill::EFFECT_TEMPORARY,
            Character::STAT_DEFENCE,
            1,
            1,
            100
        );

        $this->character1->skills = [$skill];
        $oldValue = $this->player2->stats[Character::STAT_DEFENCE];
        $round = new BattleRound($this->player1, $this->player2);
        $this->assertNotEmpty($round->skillModifiers);
        $this->assertSame($this->player2->stats[Character::STAT_DEFENCE], $oldValue);
    }

    public function testCanApplyTemporaryOpponentDefenceSkill(): void
    {
        $skill = new Skill(
            'Test',
            'Test',
            'Test skill',
            Skill::TYPE_DEFENCE,
            Skill::TARGET_OPPONENT,
            Skill::EFFECT_TEMPORARY,
            Character::STAT_DEFENCE,
            1,
            1,
            100
        );

        $this->character2->skills = [$skill];
        $oldValue = $this->player1->stats[Character::STAT_DEFENCE];
        $round = new BattleRound($this->player1, $this->player2);
        $this->assertNotEmpty($round->skillModifiers);
        $this->assertSame($this->player1->stats[Character::STAT_DEFENCE], $oldValue);
    }


    public function testCanApplyPermanentOpponentAttackSkill(): void
    {
        $skill = new Skill(
            'Test',
            'Test',
            'Test skill',
            Skill::TYPE_ATTACK,
            Skill::TARGET_OPPONENT,
            Skill::EFFECT_PERMANENT,
            Character::STAT_DEFENCE,
            1,
            1,
            100
        );

        $this->character1->skills = [$skill];
        $oldValue = $this->player2->stats[Character::STAT_DEFENCE];
        $round = new BattleRound($this->player1, $this->player2);
        $this->assertNotEmpty($round->skillModifiers);
        $this->assertSame($this->player2->stats[Character::STAT_DEFENCE], $oldValue+1);
    }

    public function testCanApplyPermanentOpponentDefenceSkill(): void
    {
        $skill = new Skill(
            'Test',
            'Test',
            'Test skill',
            Skill::TYPE_DEFENCE,
            Skill::TARGET_OPPONENT,
            Skill::EFFECT_PERMANENT,
            Character::STAT_DEFENCE,
            1,
            1,
            100
        );

        $this->character2->skills = [$skill];
        $oldValue = $this->player1->stats[Character::STAT_DEFENCE];
        $round = new BattleRound($this->player1, $this->player2);
        $this->assertNotEmpty($round->skillModifiers);
        $this->assertSame($this->player1->stats[Character::STAT_DEFENCE], $oldValue+1);
    }

    public function testCanApplyAttackerDamageSkill(): void
    {
        $skill = new Skill(
            'Test',
            'Test',
            'Test skill',
            Skill::TYPE_ATTACK,
            Skill::TARGET_OWNER,
            Skill::EFFECT_PERMANENT,
            Character::STAT_DAMAGE,
            1,
            1,
            100
        );

        $this->character1->skills = [$skill];
        $round = new BattleRound($this->player1, $this->player2);
        $this->assertNotEmpty($round->skillModifiers);
        $this->assertNotSame($round->skillModifiers[0]->oldValue, $round->skillModifiers[0]->newValue);
    }

    public function testCanApplyDefenderDamageSkill(): void
    {
        $skill = new Skill(
            'Test',
            'Test',
            'Test skill',
            Skill::TYPE_DEFENCE,
            Skill::TARGET_OWNER,
            Skill::EFFECT_PERMANENT,
            Character::STAT_DAMAGE,
            1,
            1,
            100
        );

        $this->character2->skills = [$skill];
        $round = new BattleRound($this->player1, $this->player2);
        $this->assertNotEmpty($round->skillModifiers);
        $this->assertNotSame($round->skillModifiers[0]->oldValue, $round->skillModifiers[0]->newValue);
    }

}
