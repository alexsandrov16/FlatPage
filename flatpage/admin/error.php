<?php
defined('FLATPAGE') || die;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ups!</title>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-weight: 300;
        font-family: sans-serif;
    }

    body {
        background: rgb(247, 248, 251, .5);
        color: rgb(0, 32, 51, 1);
        height: 100vh;
        padding: 1.25em;
    }

    main {
        height: 100%;
        margin: 0 auto;
        max-width: 45em;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }


    h1 {
        font-size: 3.5em;
    }

    h3 {
        font-size: 2.25em;
    }

    p {
        text-align: justify;
    }

    a {
        background-color: #345eef;
        text-decoration: none;
        border: 1px solid #345eef;
        border-radius: 5em;
        color:#fff;
        padding: .75em 1em;
        display: inline-block;
        cursor: pointer;
        transition: all .5s;
    }

    a:hover {
        background-color: #6180f1;
        border: 1px solid #345eef;
        border-radius: 5em;
    }
</style>

<body>
    <main>
            <h1>Error 500</h1>
            <h3>Error interno en el servidor</h3>

            <div style="margin-top:1.75em"><a onclick="fun();">Volver a la página anterior</a></div>
        <!--<div><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -7 30 30" width="60">
                <path fill="rgb(0, 32, 51, 1)" d="M13 17.5a1 1 0 11-2 0 1 1 0 012 0zm-.25-8.25a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0v-4.5z"></path>
                <path fill="rgb(0, 32, 51, 1)" fill-rule="evenodd" d="M9.836 3.244c.963-1.665 3.365-1.665 4.328 0l8.967 15.504c.963 1.667-.24 3.752-2.165 3.752H3.034c-1.926 0-3.128-2.085-2.165-3.752L9.836 3.244zm3.03.751a1 1 0 00-1.732 0L2.168 19.499A1 1 0 003.034 21h17.932a1 1 0 00.866-1.5L12.866 3.994z"></path>
            </svg></div>-->


        <script>
            function fun() {
                history.back();
            }
        </script>
    </main>
</body>

</html>