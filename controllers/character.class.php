<?php

class CharacterController
{
    function list()
    {
        return [
            'characters' => GameConfig::$characters, 
            'monsters' => GameConfig::$monsters,
        ];
    }

    function create()
    {
        $randomCharacter = GameConfig::$characters[array_rand(GameConfig::$characters)];
        $character = isset($_REQUEST['character']) ?  (GameConfig::$characters[$_REQUEST['character']] ?? $randomCharacter) : $randomCharacter;

        return new Player('Rudolf', $character, Player::TYPE_HUMAN);
    }
}
