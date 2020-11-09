<?php class CommonHtml
{
    function header($props)
    {
        return '
        <html>
        <head>
            <title>Hero Game' . (isset($props['page']) ? ' > ' . $props['page'] : '') .'</title>    
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
            <link rel="stylesheet" href="templates/style.css">
            <link rel="stylesheet" href="templates/rpg-awesome.css">
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

      <script>
      $(function () {
        $(\'[data-toggle="tooltip"]\').tooltip()
      })
      </script>
        </head>
        <body >
        <nav class="navbar navbar-dark bg-primary">
            <div class="navbar-brand" href="#">
                <a href="?action=characters" class="btn btn-primary" type="submit"><i class="material-icons">home</i></a>
                Hero Game' . (isset($props['page']) ? ' > ' . $props['page'] : '') .'
            </div>
        </nav>
        ';
    }

    function footer()
    {
        return '</body>
        </html>';
    }
}
