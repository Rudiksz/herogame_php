<?php

class BattleController
{
    function battle()
    {
        $randomCharacter = GameConfig::$characters[array_rand(GameConfig::$characters)];
        $character = isset($_REQUEST['character']) ?  (GameConfig::$characters[$_REQUEST['character']] ?? $randomCharacter) : $randomCharacter;

        $monster = GameConfig::$monsters[array_rand(GameConfig::$monsters)];

        $battle = new Battle(
            new Player($character->name, $character, Player::TYPE_HUMAN),
            new Player($monster->name, $monster, Player::TYPE_MONSTER)
        );
        $_SESSION[$battle->id] = $battle;
        return ['page' => 'Battle', 'battle' => $battle];
    }

    function attack()
    {
        $battle = $_SESSION[$_REQUEST['id']];
        $battle->attack();
        $_SESSION[$battle->id] = $battle;

        return ['page' => 'Battle', 'battle' => $battle];
    }

    function autoFight()
    {
        
        $battle = $_SESSION[$_REQUEST['id']];
        $battle->autoFight();
        $_SESSION[$battle->id] = $battle;

        return ['page' => 'Battle', 'battle' => $battle];
    }
}
