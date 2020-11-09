<?php

declare(strict_types=1);
include 'autoloader.php';


use PHPUnit\Framework\TestCase;

final class SkillTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            Skill::class,
            new Skill('test', 'Test', 'Test description', Skill::TYPE_ATTACK, Skill::TARGET_OWNER, Skill::EFFECT_TEMPORARY, Character::STAT_HEALTH, 1, 0, 100)
        );
    }

    public function testCanApplyFractionalModifier(): void
    {
        $skill = new Skill('test', 'Test', 'Test description', Skill::TYPE_ATTACK, Skill::TARGET_OWNER, Skill::EFFECT_TEMPORARY, Character::STAT_HEALTH, 1, 0, 100);

        $skill->fractionalModifier = 0;
        $this->assertSame($skill->apply(100), 0);
        
        $skill->fractionalModifier = .5;
        $this->assertSame($skill->apply(100), 50);

        // No-op value
        $skill->fractionalModifier = 1;
        $this->assertSame($skill->apply(100), 100);

        $skill->fractionalModifier = 2;
        $this->assertSame($skill->apply(100), 200);
    }

    public function testCanApplyValueModifier(): void
    {
        $skill = new Skill('test', 'Test', 'Test description', Skill::TYPE_ATTACK, Skill::TARGET_OWNER, Skill::EFFECT_TEMPORARY, Character::STAT_HEALTH, 1, 0, 100);

        // No-op value
        $skill->valueModifier = 0;
        $this->assertSame($skill->apply(100), 100);
        
        $skill->valueModifier = 1;
        $this->assertSame($skill->apply(100), 101);

        $skill->valueModifier = -1;
        $this->assertSame($skill->apply(100), 99);
    }    

    public function testCanApplyFranctionalAndValueModifier(): void
    {
        $skill = new Skill('test', 'Test', 'Test description', Skill::TYPE_ATTACK, Skill::TARGET_OWNER, Skill::EFFECT_TEMPORARY, Character::STAT_HEALTH, 1, 0, 100);

        // No-op values
        $skill->valueModifier = 0;
        $skill->fractionalModifier = 1;
        $this->assertSame($skill->apply(100), 100);
        
        // Value no-op, fraction op
        $skill->valueModifier = 0;
        $skill->fractionalModifier = 0.5;
        $this->assertSame($skill->apply(100), 50);

        // Value op, fraction no-op
        $skill->valueModifier = 1;
        $skill->fractionalModifier = 1;
        $this->assertSame($skill->apply(100), 101);

        // Value op, fraction op
        $skill->valueModifier = 1;
        $skill->fractionalModifier = 0.5;
        $this->assertSame($skill->apply(100), 51);
    }
}
