<?php

class SkillModifier
{
    public Skill $skill;
    public Player $owner;
    public Player $target;
    public int $oldValue;
    public int $newValue;

    public function __construct(Skill $skill,  Player $owner,  Player $target,  int $oldValue, int $newValue)
    {
        $this->skill = $skill;
        $this->owner = $owner;
        $this->target = $target;
        $this->oldValue = $oldValue;
        $this->newValue = $newValue;
    }
}