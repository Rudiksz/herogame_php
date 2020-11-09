<?php

class GameConfig
{
  public static $characters;
  public static $monsters;
  public static $skills;

  const battleRoundLimit = 40;

  static function init()
  {
    self::$skills = [
      Skills::shieldBreaker => new Skill(
        'shieldBreaker',
        'Shield Breaker',
        'You deal a crushing blow to your enemy, permanently reducing their defence.',
        Skill::TYPE_ATTACK,
        Skill::TARGET_OPPONENT,
        Skill::EFFECT_PERMANENT,
        Character::STAT_DEFENCE,
        1,
        -1,
        50
      ),
      Skills::deadlyStrike => new Skill(
        'deadlyStrike',
        'Deadly Strike',
        'The time spent sharpening your axe paid off. Your attack causes a deep wound in your opponent, reducing their health by 10%',
        Skill::TYPE_ATTACK,
        Skill::TARGET_OPPONENT,
        Skill::EFFECT_PERMANENT,
        Character::STAT_HEALTH,
        0.9,
        0,
        20
      ),
      Skills::rapidStrike => new Skill(
        'rapidStrike',
        'Rapid Strike',
        'A moment of hesitation from your enemy allows you to deal an extra blow in a rapid succecion.',
        Skill::TYPE_ATTACK,
        Skill::TARGET_OWNER,
        Skill::EFFECT_TEMPORARY,
        Character::STAT_STRENGTH,
        2,
        0,
        10
      ),
      Skills::luckyShot => new Skill(
        'luckyShot',
        'Lucky Shot',
        'Decades of practice allows you to aim for the weakest spots of your enemies, bypassing their defenses. Robin Hood would be proud of you.',
        Skill::TYPE_ATTACK,
        Skill::TARGET_OPPONENT,
        Skill::EFFECT_TEMPORARY,
        Character::STAT_DEFENCE,
        0,
        0,
        5
      ),
      Skills::premonition => new Skill(
        'premonition',
        'Premonition',
        'As you tap into the arcane energies of the surrounding field to summon a fireball, you catch glimpses of the future. You are more successful evading future attacks.',
        Skill::TYPE_ATTACK,
        Skill::TARGET_OWNER,
        Skill::EFFECT_PERMANENT,
        Character::STAT_LUCK,
        1,
        1,
        50
      ),
      Skills::arcaneMastery => new Skill(
        'arcaneMastery',
        'Arcane Mastery',
        'Your mastery of the mystics arts allows you to absorb arcane energy with every spell you cast. Your spells become stronger as you fight.',
        Skill::TYPE_ATTACK,
        Skill::TARGET_OWNER,
        Skill::EFFECT_PERMANENT,
        Character::STAT_STRENGTH,
        1,
        2,
        80
      ),
      Skills::raiseSkeleton => new Skill(
        'raiseSkeleton',
        'Raise Skeleton',
        'Raises a skeleton to fight along your side.  Once raised, skeletons are bound to you for ever. Each skeleton increases your strength.',
        Skill::TYPE_ATTACK,
        Skill::TARGET_OWNER,
        Skill::EFFECT_PERMANENT,
        Character::STAT_STRENGTH,
        1,
        1,
        100
      ),
      Skills::curse => new Skill(
        'curse',
        'Curse',
        'Curses your foe, causing it to cower in fear. Cursed foes are unable to evade your attack.',
        Skill::TYPE_ATTACK,
        Skill::TARGET_OPPONENT,
        Skill::EFFECT_TEMPORARY,
        Character::STAT_LUCK,
        0,
        0,
        50
      ),
      Skills::endurance => new Skill(
        'endurance',
        'Endurance',
        'Your strong physique and relentless training gives you an edge in long battles. Your take less damage as the battle goes on.',
        Skill::TYPE_DEFENCE,
        Skill::TARGET_OWNER,
        Skill::EFFECT_PERMANENT,
        Character::STAT_DEFENCE,
        1,
        1,
        50
      ),
      Skills::magicShield => new Skill(
        'magicShield',
        'Magic Shield',
        'Absorbs half of the damage you receive.',
        Skill::TYPE_DEFENCE,
        Skill::TARGET_OWNER,
        Skill::EFFECT_TEMPORARY,
        Character::STAT_DAMAGE,
        0.5,
        0,
        20
      ),
      Skills::arcaneShield => new Skill(
        'arcaneShield',
        'Arcane Shield',
        'You surround yourself with an arcane force field causing damage to enemies who attack you.',
        Skill::TYPE_DEFENCE,
        Skill::TARGET_OPPONENT,
        Skill::EFFECT_PERMANENT,
        Character::STAT_HEALTH,
        0.9,
        0,
        100
      ),
      Skills::replenish => new Skill(
        'replenish',
        'Replenish',
        'You control the dead, but you also control the life force. When you take damage your health increases.',
        Skill::TYPE_DEFENCE,
        Skill::TARGET_OWNER,
        Skill::EFFECT_PERMANENT,
        Character::STAT_HEALTH,
        1,
        10,
        100
      ),
    ];

    self::$characters =
      [
        CharacterType::barbarian => new Character(
          'barbarian',
          'Thrall',
          "Gray, short hair gently hangs over a full, lived-in face. Piercing hazel eyes, set well within their sockets, watch watchfully over the ships they've sworn to protect for so long.
  A moustache and goatee charmingly compliments his eyes and leaves a pleasant memory of his fortunate upbringing.
  
  This is the face of {NAME}, a true mercenary among humans. He stands easily among others, despite his bulky frame.",
          [
            Character::STAT_HEALTH=> [80, 100],
            Character::STAT_STRENGTH => [80, 80],
            Character::STAT_DEFENCE => [55, 55],
            Character::STAT_SPEED => [50, 60],
            Character::STAT_LUCK => [20, 30],
          ],
          [
            self::$skills[Skills::shieldBreaker],
            self::$skills[Skills::deadlyStrike],
            self::$skills[Skills::endurance]
          ],
        ),
        CharacterType::orderus => new Character(
          'orderus',
          'Orderus',
          "Chestnut, oily hair tight in a ponytail reveals a bony, gloomy face. Beady green eyes, set wickedly within their sockets, watch readily over the spirits they've befriended for so long.
  Fallen debry left a mark reaching from the top of the right cheek , running towards the tip of the nose and ending on her right nostril and leaves a lasting burden of a great reputation.
  
  The is the face of {NAME}, a true challenger among halflings. She stands common among others, despite her skinny frame.",
          [
            Character::STAT_HEALTH=> [70, 90],
            Character::STAT_STRENGTH => [50, 70],
            Character::STAT_DEFENCE => [40, 50],
            Character::STAT_SPEED => [60, 80],
            Character::STAT_LUCK => [20, 30],
          ],
          [
            self::$skills[Skills::rapidStrike],
            self::$skills[Skills::luckyShot],
            self::$skills[Skills::magicShield]
          ],
        ),
        CharacterType::mage => new Character(
          'mage',
          'Mage',
          "Red, short hair neatly coiffured to reveal a fine, tense face. Big, round blue eyes, set seductively within their sockets, watch longingly over the spirits they've grieved with for so long.
  A tattoo of a skull is prominently featured on the side of her left cheekbone and leaves a fascinating memory of redeemed honor.
  
  The is the face of {NAME}, a true warden among humans. She stands big among others, despite her bulky frame.",
          [
            Character::STAT_HEALTH=> [70, 80],
            Character::STAT_STRENGTH => [20, 40],
            Character::STAT_DEFENCE => [40, 30],
            Character::STAT_SPEED => [70, 70],
            Character::STAT_LUCK => [30, 60],
          ],
          [
            self::$skills[Skills::premonition],
            self::$skills[Skills::arcaneMastery],
            self::$skills[Skills::arcaneShield],
          ]
        ),
        CharacterType::necromancer => new Character(
          'necromancer',
          'Necromancer',
          "Blonde, shaggy hair slightly covers a lean, radiant face. Smart gray eyes, set rooted within their sockets, watch energetically over the haven they've been isolated from for so long.
  Several moles are spread awkwardly across his forehead and leaves a pleasurable memory of his luck.
  
  This is the face of {NAME} a true pioneer among vampires. He stands awkwardly among others, despite his subtle frame.",
          [
            Character::STAT_HEALTH=> [70, 100],
            Character::STAT_STRENGTH => [50, 70],
            Character::STAT_DEFENCE => [20, 20],
            Character::STAT_SPEED => [20, 30],
            Character::STAT_LUCK => [30, 80],
          ],
          [
            self::$skills[Skills::curse],
            self::$skills[Skills::raiseSkeleton],
            self::$skills[Skills::replenish]
          ],
        )
      ];

    self::$monsters = [
      new Character(
        'grimehand',
        'Grimehand',
        'This bulky, reptilian, demonic monster dwells in tropical climates. It lures its prey, which includes other predators, small creatures, and humans. It attacks with dizzying blows and obscuring fog. It is rumored that they can cast curses on those who injure them.',
        [
          Character::STAT_HEALTH=> [60, 90],
          Character::STAT_STRENGTH => [60, 90],
          Character::STAT_DEFENCE => [30, 50],
          Character::STAT_SPEED => [50, 70],
          Character::STAT_LUCK => [25, 40],
        ],
        [],
      ),
      new Character(
        'treehugger',
        'Treehugger',
        'This massive draconic beast makes its home in lush valleys. It tracks its prey, which includes magical beasts and mundane beasts. It attacks with fangs, obscuring fog and scorching gas. It dislikes holy icons. They hunt in packs of 4-24. Legend holds that they posses arcane items.',
        [
          Character::STAT_HEALTH=> [80, 90],
          Character::STAT_STRENGTH => [60, 90],
          Character::STAT_DEFENCE => [40, 60],
          Character::STAT_SPEED => [20, 40],
          Character::STAT_LUCK => [10, 20],
        ],
        []
      ),
      new Character(
        'moonhowler',
        'Moonhowler',

        'This small mongrel monster can be found in deserts. It attacks with rapid blows and thrown weapons. It can be easily injured with bright light. It is believed that they can be negotiated with, though their demands are unusual.',
        [
          Character::STAT_HEALTH=> [60, 70],
          Character::STAT_STRENGTH => [40, 50],
          Character::STAT_DEFENCE => [30, 40],
          Character::STAT_SPEED => [50, 70],
          Character::STAT_LUCK => [40, 60],
        ],
        []
      ),
      new Character(
        'skeleton',
        'Bonecrusher',
        'This small, ethereal, undead monster can be found in abandoned ruins. It leaps upon its prey, which includes mundane beasts and magical beasts. It attacks with spines, piercing shrieks and melee weapons.',
        [
          Character::STAT_HEALTH=> [70, 90],
          Character::STAT_STRENGTH => [60, 70],
          Character::STAT_DEFENCE => [20, 30],
          Character::STAT_SPEED => [40, 60],
          Character::STAT_LUCK => [15, 20],
        ],
        []
      ),
      new Character(
        'dreadmask',
        'Dreadmask',
        'This bulky, snake-like, mongrel creature dwells in mountain passages. It stalks its prey, which includes monstrous humanoids, magical beasts, mundane beasts, and other monsters of the same type. It attacks with dizzying blows and ice.',
        [
          Character::STAT_HEALTH=> [90, 100],
          Character::STAT_STRENGTH => [80, 90],
          Character::STAT_DEFENCE => [40, 60],
          Character::STAT_SPEED => [5, 10],
          Character::STAT_LUCK => [10, 20],
        ],
        []
      ),
    ];
  }

  public const statIcons = [
    'Health' => 'health',
    'Strength' => 'axe',
    'Defence' => 'shield',
    'Speed' => 'fast-ship',
    'Luck' => 'perspective-dice-one',
  ];

  public const skillIcons  = [
    'arcaneMastery' => 'burning-book',
    'premonition' => 'bleeding-eye',
    'arcaneShield' => 'burning-eye',
    'shieldBreaker' => 'broken-shield',
    'deadlyStrike' => 'hammer-drop',
    'endurance' => 'muscle-up',
    'rapidStrike' => 'player-shot',
    'luckyShot' => 'supersonic-arrow',
    'magicShield' => 'barrier',
    'curse' => 'player-despair',
    'raiseSkeleton' => 'flaming-claw',
    'replenish' => 'player',
  ];

  public const attackIcons = [
    'barbarian' => 'axe-swing',
    'orderus' => 'archer',
    'mage' => 'dragon-breath',
    'necromancer' => 'flaming-claw',
    'reptilian' =>'crab-claw',
    'treehugger' =>'croc-sword',
    'moonhowler' => 'hammer-drop',
  ];
}

class Skills
{
  public const magicShield = 'magicShield';
  public const rapidStrike = 'rapidStrike';
  public const raiseSkeleton = 'raiseSkeleton';
  public const curse = 'curse';
  public const shieldBreaker = 'shieldBreaker';
  public const deadlyStrike = 'deadlyStrike';
  public const luckyShot = 'luckyShot';
  public const premonition = 'premonition';
  public const arcaneMastery = 'arcaneMastery';
  public const endurance = 'endurance';
  public const arcaneShield = 'arcaneShield';
  public const replenish = 'replenish';
}

class CharacterType
{
  public const barbarian = 'barbarian';
  public const orderus = 'orderus';
  public const mage = 'mage';
  public const necromancer = 'necromancer';
}





