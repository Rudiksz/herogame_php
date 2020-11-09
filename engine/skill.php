<?php


class Skill
{

    public const TYPE_ATTACK = 'attack';
    public const TYPE_DEFENCE = 'defence';

    public const TARGET_OWNER = 'owner';
    public const TARGET_OPPONENT = 'opponent';
    public const TARGET_ATTACKER = 'attacker';
    public const TARGET_DEFENDER = 'defender';

    public const EFFECT_PERMANENT = 'permanent';
    public const EFFECT_TEMPORARY = 'temporary';
    

    public string $id;
    public string $name;
    public string $description;
    public string $type;
    public string $target;
    public string $effectDuration;
    public string $stat;
    public float $fractionalModifier;
    public int $valueModifier;
    public int $chance;


    function __construct(
        string $id,
        string $name,
        string $description,
        string $type,
        string $target,
        string $effectDuration,
        string $stat,
        float $fractionalModifier,
        int $valueModifier,
        int $chance
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->type = $type;
        $this->target = $target;
        $this->effectDuration = $effectDuration;
        $this->stat = $stat;
        $this->fractionalModifier = $fractionalModifier;
        $this->valueModifier = $valueModifier;
        $this->chance = $chance;
    }

    function apply(int $value): int
    {
        return ($value * $this->fractionalModifier) + $this->valueModifier;
    }
}