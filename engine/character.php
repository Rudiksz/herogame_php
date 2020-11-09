<?php

class Character
{

    public const STAT_HEALTH = 'Health';
    public const STAT_STRENGTH = 'Strength';
    public const STAT_DEFENCE = 'Defence';
    public const STAT_SPEED = 'Speed';
    public const STAT_LUCK = 'Luck';
    public const STAT_DAMAGE = 'Damage';

    public $id;
    public $name;
    public $description;
    public $stats;
    public $skills;
    public $remote = true;


    function __construct($id, $name, $description, $stats, $skills)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->stats = $stats;
        $this->skills = $skills;
    }
}