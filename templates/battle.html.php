<?php class BattleHtml extends CommonHtml
{
    function battle($props)
    {
        echo $this->header($props);

        $battle = $props['battle'];
        echo '
        <div class="row">';

        $this->playerTile($battle->player1, $battle);

        $this->battleInfo($battle);

        $this->playerTile($battle->player2, $battle);

        echo '</div>';

        echo $this->footer();
    }

    function attack($props)
    {
        $this->battle($props);
    }

    function autoFight($props)
    {
        $this->battle($props);
    }

    public function playerTile(Player $player, Battle $battle)
    {
        echo '
        <div class="col mb-4">
        <div class="card player-tile">
            <div class="card-header">' . $player->name . '</div>
            <img src="assets/' . $player->character->id . '.jpg" class="card-img-top character" alt="...">
            <div class="card-body">
                <table class="table table-sm">
                <tbody>';

        foreach ($player->stats as $stat => $value) {
            echo '
                <tr>
                    <td><i class="ra ra-' . GameConfig::statIcons[$stat] . '"></i></td>
                    <td>'  . $stat . '</td>
                    <td>'  . $value . '</td>
                </tr>';
        }

        echo '
                </tbody>
                </table>
                <div class="dropdown-divider"></div>
                <div class="row">';

        foreach ($player->character->skills as $skill) {
            echo '
                <div class="col text-center" data-toggle="tooltip" title="'  . $skill->name . ': '  . $skill->description . '">
                <img class="skill-icon" src="assets/skill-'. $skill->id . '.jpg" />
                </div>
                ';
        }

        echo '

                </div>
            </div>';

        if ($player->type == Player::TYPE_HUMAN)
            echo '
            <div class="card-footer">' .
                ($battle->status != Battle::STATUS_COMPLETE ? '
                <a href="?action=battle.attack&id=' . $battle->id . '" class="btn btn-primary">
                    <i class="ra ra-' . GameConfig::attackIcons[$player->character->id] . '"></i> Attack
                </a>
                <a href="?action=battle.auto&id=' . $battle->id . '" class="btn btn-primary">
                    <i class="ra ra-ra-crossed-axes"></i> Quickfight
                </a>
                <a href="?action=battle&character=' . $player->character->id . '" class="btn btn-primary">
                    <i class="ra ra-player-teleport"></i> Flee
                </a>' : '
                <a href="?action=battle&character=' . $player->character->id . '" class="btn btn-primary">
                    <i class="ra ra-player-dodge"></i> Continue
                </a> ') . '     
            </div>';

        echo '
        </div>
        </div>';
    }

    function battleInfo(Battle $battle)
    {
        echo '
        <div class="col col-4">
            <div class="card">
                <div class="card card-body">
                ' . $battle->description . '
                </div>
            </div>';

        if ($battle->status == Battle::STATUS_COMPLETE) {
            echo '<div class="card my-1">
                <div class="card card-body">
                    <p><i class="skill-icon ra ra-trophy float-left"></i> ' . $battle->winner()->name . '</p>
                </div>
            </div>';
        }

        array_walk($battle->rounds, array($this, 'battleRound'), $battle);

        echo '
        </div>';
    }

    function battleRound(BattleRound $round, $key, Battle $battle)
    {
        static $index = 0;
        $index++;

        $status = '';
        if ($round->status == BattleRound::STATUS_MISSED) {
            $status = $round->attacker->name == $battle->player1->name ? 'You missed' : 'You evaded the attack';
        } elseif ($round->status == BattleRound::STATUS_COMPLETED) {
            $status = $round->attacker->name == $battle->player1->name
                ? ('You dealt ' . $round->damage . ' damage')
                : ('You took ' . $round->damage . ' damage');
        }

        echo '
        <div class="card mt-1">
    
            <div class="card-header" id="round-heading-' . $index . '">
                <button class="btn btn-outline-dark" type="button" data-toggle="collapse" data-target="#round-collapse-' . $index . '" aria-expanded="false" aria-controls="round-collapse-' . $index . '">
                Round ' . $index . ': ' . $status . '
                </button>  
            </div>

            <div class="collapse" id="round-collapse-' . $index . '">
            <div class="card card-body">
                Skills used: ' . count($round->skillModifiers) . '<br />
                ';
        if (count($round->skillModifiers)) {
            echo '<ul class="list-group list-group-flush">';
            array_walk($round->skillModifiers, function (SkillModifier $skillModifier, $key) {
                echo '
                    <li class="list-group-item">
                        <strong>'  . $skillModifier->skill->name . '</strong><br />
                        Owner: '  . $skillModifier->owner->name . '<br />
                        Target: '  . $skillModifier->target->name . '<br />
                        Stat: '  . $skillModifier->skill->stat . '<br />
                        Old value: '  . $skillModifier->oldValue . '<br />
                        New value: '  . $skillModifier->newValue . '<br />
                    </li>';
            });
            echo '</ul>';
        }

        echo '
            </div>
    </div>

  </div>';
    }
}
