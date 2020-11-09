<?php

class Player
{
    public const TYPE_HUMAN = 'human';
    public const TYPE_MONSTER = 'monster';

    public String $name;
    public Character $character;
    public string $type = Player::TYPE_HUMAN;
    public array $stats = [];

    function __construct(string $name, Character $character, string $type)
    {
        $this->name = $name;
        $this->character = $character;
        $this->type = $type;
        $this->stats = [];

        foreach ($character->stats as $key => $value) {
            $min = $value[0] ?? 50;
            $max = $value[1] ?? 100;

            if ($min > $max) {
                $temp = $min;
                $min = $max;
                $max = $temp;
            }

            $val = $min == $max ? $min : rand($min, $max);
            $this->stats[$key] = $val;
        }
    }
}