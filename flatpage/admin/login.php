<?php
defined('FLATPAGE') || die;

use FlatPage\Core\App;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title><?= $title ?></title>
    <?= App::themes()->favicon() ?>
    <?= App::themes()->stylesheet('builder.css', true) ?>
    <style>
        body {
            background: var(--background);
        }

        main {
            margin: 0;
            padding-top: 8% !important;
        }

        img {
            margin-bottom: .75em;
        }

        h2 {
            font-size: 2.5rem;
            font-weight: 100;
            text-align: center;
            margin-bottom: .25em;
        }

        #homepage {
            margin-top: 2em;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-hover);
            margin-bottom: 1em;
        }

        @media(min-width:768px) {
            main {
                margin: 0 auto;
                width: 24em;
            }
        }
    </style>
</head>

<body>
    <main class="container">
        <center>
            <img src="<?= env('base_url') ?>/flatpage/admin/assets/img/logo.png" alt="" width="100">
        </center>

        <div class="box ">
            <div class="box-content">
                <h2 class="light t-center">Iniciar Sesión</h2>
                <form method="post">
                    <label for="">Usuario</label>
                    <div class="input-field">
                        <input type="text" name="user" autocomplete="off">
                    </div>
                    <label for="">Contraseña</label>
                    <div class="input-field">
                        <input type="password" name="password" id="pass">
                        <i class="material-icons see">visibility</i>
                    </div>
                    <!--<span><input type="checkbox" name="remberme">Recordar</span>-->
                    <button type="submit" id="load" class="btn lg">enviar</button>
                </form>
            </div>
        </div>
        <a id="homepage" class="" href="<?= env('base_url') ?>">
            <i class="material-icons">home</i>
            Volver al Inicio
        </a>
    </main>

    <div class="message"></div>

    <?= App::themes()->script('flatpage.js', true) ?>

    <script>
        ShowPass('#pass','.see');
        LoadBtn('button#load', 'enviando');

        let mnsgError = '<?= $message ?>';

        window.onload = () => {
            if (mnsgError != '') {
                Message('danger', mnsgError);
            }
        }
    </script>
</body>

</html>