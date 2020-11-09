<?php

class BattleRound
{
    const STATUS_WAITING = 'waiting';
    const STATUS_MISSED = 'missed';
    const STATUS_COMPLETED = 'completed';

    public Player $attacker;
    public Player $defender;
    public string $status;
    public int $damage = 0;
    public $skillModifiers = [];
    public $tempAttackerStats = [];
    public $tempDefenderStats = [];

    public function __construct(Player $attacker, Player $defender)
    {
        $this->attacker = $attacker;
        $this->defender = $defender;
        $this->attack();
    }

    private function attack()
    {
        // Create a temporary copy of the player stats
        $this->tempAttackerStats = $this->attacker->stats;
        $this->tempDefenderStats = $this->defender->stats;

        // 1. Before hit check, apply all luck modifying skills
        $skills = array_filter($this->attacker->character->skills, function (Skill $skill) {
            return $skill->stat == Character::STAT_LUCK && $skill->type == Skill::TYPE_ATTACK;
        });
        array_walk(
            $skills, 
            array($this, 'applySkill'),
            Skill::TARGET_ATTACKER,
        );

        $skills = array_filter($this->defender->character->skills, function (Skill $skill) {
            return $skill->stat == Character::STAT_LUCK && $skill->type == Skill::TYPE_DEFENCE;
        });
        array_walk(
            $skills,
            array($this, 'applySkill'),
            Skill::TARGET_DEFENDER,
        );

        // 2. Check if attack is evaded
        $luck = $this->tempDefenderStats[Character::STAT_LUCK];
        if ($luck != 0 && $this->chance($luck)) {
            return $this->status = self::STATUS_MISSED;
        }

        // 3. Apply stat modifier skills
        $skills = array_filter($this->attacker->character->skills, function (Skill $skill) {
            return $skill->stat != Character::STAT_LUCK
                && $skill->stat != Character::STAT_DAMAGE
                && $skill->type == Skill::TYPE_ATTACK;
        });
        array_walk(
            $skills,
            array($this, 'applySkill'),
            Skill::TARGET_ATTACKER,
        );

        $skills = array_filter($this->defender->character->skills, function (Skill $skill) {
            return $skill->stat != Character::STAT_LUCK
                && $skill->stat != Character::STAT_DAMAGE
                && $skill->type == Skill::TYPE_DEFENCE;
        });
        array_walk(
            $skills,
            array($this, 'applySkill'),
            Skill::TARGET_DEFENDER,
        );

        // 4. Calculate the damage
        $strength = $this->tempAttackerStats[Character::STAT_STRENGTH];
        $defence = $this->tempDefenderStats[Character::STAT_DEFENCE];

        $this->damage = max(0, $strength - $defence);

        // 5. Apply damage modifier skills
        $skills = array_filter($this->attacker->character->skills, function (Skill $skill) {
            return $skill->stat == Character::STAT_DAMAGE
                && $skill->type == Skill::TYPE_ATTACK;
        });
        array_walk(
            $skills,
            function (Skill $skill, $key) {
                $this->applyDamageSkill($skill, Skill::TARGET_ATTACKER, $this->damage);
            }
        );

        $skills = array_filter($this->defender->character->skills, function (Skill $skill) {
            return $skill->stat == Character::STAT_DAMAGE
                && $skill->type == Skill::TYPE_DEFENCE;
        });
        array_walk(
            $skills,
            function (Skill $skill, $key) {
                $this->applyDamageSkill($skill, Skill::TARGET_DEFENDER, $this->damage);
            }
        );

        // 6. Apply the final damage
        $this->defender->stats[Character::STAT_HEALTH] =
            max(0, $this->defender->stats[Character::STAT_HEALTH] - $this->damage);

        $this->status = self::STATUS_COMPLETED;
    }

    private function applySkill(Skill $skill, $key, string $owner)
    {
        if (!$this->chance($skill->chance))
            return;

        $target = $owner == Skill::TARGET_ATTACKER
            ? ($skill->target == Skill::TARGET_OWNER ? $this->attacker : $this->defender)
            : ($skill->target == Skill::TARGET_OWNER ? $this->defender : $this->attacker);

        // Bummer. PHP can't assign expression values by reference
        $targetStats = &$this->tempAttackerStats;
        if ($owner == Skill::TARGET_ATTACKER) {
            if ($skill->target == Skill::TARGET_OWNER)
                $targetStats = &$this->tempAttackerStats;
            else
                $targetStats = &$this->tempDefenderStats;
        } else {
            if ($skill->target == Skill::TARGET_OWNER)
                $targetStats = &$this->tempDefenderStats;
            else
                $targetStats = &$this->tempAttackerStats;
        }

        $oldValue = $targetStats[$skill->stat];
        $newValue = $skill->apply($oldValue);

        if ($skill->effectDuration == Skill::EFFECT_PERMANENT) {
            $target->stats[$skill->stat] = $newValue;
        }

        $targetStats[$skill->stat] = $newValue;

        //Log the skill
        $this->skillModifiers[] = new SkillModifier(
            $skill,
            $owner == Skill::TARGET_ATTACKER ? $this->attacker : $this->defender,
            $target,
            $oldValue,
            $newValue,
        );

        return $newValue;
    }

    private function applyDamageSkill(Skill $skill, string $owner, &$damage)
    {
        if (!$this->chance($skill->chance))
            return;

        $target = $owner == Skill::TARGET_ATTACKER
            ? ($skill->target == Skill::TARGET_OWNER ? $this->attacker : $this->defender)
            : ($skill->target == Skill::TARGET_OWNER ? $this->defender : $this->attacker);


        $oldValue = $damage;
        $damage = $skill->apply($oldValue);

        //Log the skill
        $this->skillModifiers[] = new SkillModifier(
            $skill,
            $owner == Skill::TARGET_ATTACKER ? $this->attacker : $this->defender,
            $target,
            $oldValue,
            $damage,
        );

        return $damage;
    }

    function chance(int $likelihood): bool
    {
        if (!isset($likelihood) || $likelihood < 1)
            return false;

        if ($likelihood > 99)
            return true;

        return rand(0, 100) < $likelihood;
    }
}