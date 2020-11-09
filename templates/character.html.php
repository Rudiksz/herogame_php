<?php class CharacterHtml extends CommonHtml
{
    function list($props)
    {
        echo $this->header($props);

        echo '<div class="container">
            <div class="row row-cols-md-2">';

        foreach ($props['characters'] as $char) {
            echo '<div class="col mb-4">
                <div class="card">
                <div class="card-header">' . $char->name . '</div>
                <img src="assets/' . $char->id . '.jpg" class="card-img-top character" alt="...">
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-2">
                        <div class="col mb-4">
                        <p class="card-text small">' . str_replace('{NAME}', 'The ' . $char->name, $char->description) . '</p>
                        </div>
                        <div class="col mb-4">
                            <h5>Stats</h5>
                            <table class="table table-sm">
                            <tbody>';

            foreach ($char->stats as $stat => $value) {
                echo '      <tr>
                                <td><i class="ra ra-' . GameConfig::statIcons[$stat] . '"></i></td>
                                <td>'  . $stat . '</td>
                                <td>'  . $value[0] . ' - ' . $value[1] . '</td>
                            </tr>';
            }

            echo '
                            </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <div class="row row-cols-1 row-cols-md-2">';

            foreach ($char->skills as $skill) {
                echo '
                        <div class="col mb-2 align-middle" data-toggle="tooltip" title="'  . $skill->description . '">
                            <img class="skill-icon" src="assets/skill-'. $skill->id . '.jpg" />
                            <span>'  . $skill->name . '</span>
                        </div>
                        ';
            }

            echo '

                    </div>
                    
                    <a href="?action=battle&character=' . $char->id . '" class="btn btn-primary float-right">Start</a>
                </div>
                </div>
                </div>';
        }

        echo '
            </div>
          </div>';

        echo '<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="navbar-brand" href="#">  Monsters</div>
        </nav>';


          echo '<div class="container">
          <div class="row row-cols-md-2">';

      foreach ($props['monsters'] as $char) {
          echo '<div class="col mb-4">
              <div class="card">
              <div class="card-header">' . $char->name . '</div>
              <img src="assets/' . $char->id . '.jpg" class="card-img-top character" alt="...">
              <div class="card-body">
                  <div class="row row-cols-1 row-cols-md-2">
                      <div class="col mb-4">
                      <p class="card-text small">' . str_replace('{NAME}', 'The ' . $char->name, $char->description) . '</p>
                      </div>
                      <div class="col mb-4">
                          <h5>Stats</h5>
                          <table class="table table-sm">
                          <tbody>';

          foreach ($char->stats as $stat => $value) {
              echo '      <tr>
                              <td><i class="ra ra-' . GameConfig::statIcons[$stat] . '"></i></td>
                              <td>'  . $stat . '</td>
                              <td>'  . $value[0] . ' - ' . $value[1] . '</td>
                          </tr>';
          }

          echo '
                          </tbody>
                          </table>
                      </div>
                  </div>
              </div>
              </div>
              </div>';
      }

      echo '
          </div>
        </div>';

            // '<div style="color: blue;"> ' . print_r($props, true) . '</div>' .
            echo $this->footer();
    }

    function create($props)
    {
        return $this->header($props) . '<div style="color: blue;"> ' . print_r($props, true) . '</div>' . $this->footer();
    }
}
