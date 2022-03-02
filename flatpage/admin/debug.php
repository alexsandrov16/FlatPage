<?
defined('FLATPAGE') || die;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Mode</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            font-family: sans-serif;
        }

        body {
            background: rgb(247, 248, 251, .5);
            color: rgb(0, 32, 51, 1)
        }

        #header {
            background: #dd3e3e;
            color: #fff
        }

        #header p {
            font-size: 20px;
        }

        .container {
            max-width: 75rem;
            margin: 0 auto;
            padding: 1rem;
        }

        .search {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .search:hover svg {
            opacity: 1;
        }

        svg {
            opacity: 0;
            fill: #fff;
            width: 2em;
            margin-left: 1.25em;
            cursor: default;
            transition: .5s;
        }

        h1 {
            font-size: 3em;
            font-weight: 400
        }

        p {
            margin: .5em 0 1em
        }

        pre {
            overflow-x: scroll;
            background: #334;
            border-radius: 4px;
            padding: .75em 1.15em
        }

        code {
            color: #d0cece;
            font-family: Menlo, Monaco, Consolas, "Courier New", monospace;
            line-height: 1.4
        }
    </style>
</head>
</head>

<body>
    <div id="header">
        <div class="container">
            <h1><?= $type ?></h1>
            <p class="search">
                <?= $message ?>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                    <path fill-rule="evenodd" d="M.408 15.13a2 2 0 01.59-2.642L17.038 1.33a2 2 0 012.85.602l2.828 4.644a2 2 0 01-.851 2.847l-17.762 8.43a2 2 0 01-2.59-.807L.408 15.13zm5.263-4.066l7.842-5.455 2.857 4.76-8.712 4.135-1.987-3.44zm-1.235.86L1.854 13.72a.5.5 0 00-.147.66l1.105 1.915a.5.5 0 00.648.201l2.838-1.347-1.862-3.225zm13.295-2.2L14.747 4.75l3.148-2.19a.5.5 0 01.713.151l2.826 4.644a.5.5 0 01-.212.712l-3.49 1.656z"></path>
                    <path d="M17.155 22.87a.75.75 0 00.226-1.036l-4-6.239a.75.75 0 00-.941-.278l-2.75 1.25a.75.75 0 00-.318.274l-3.25 4.989a.75.75 0 001.256.819l3.131-4.806.51-.232v5.64a.75.75 0 101.5 0v-6.22l3.6 5.613a.75.75 0 001.036.226z"></path>
                </svg>
            </p>
        </div>
    </div>
    <div class="container">
        <p style="overflow-wrap: anywhere"><?= str_ireplace(ABS_PATH, '..' . DS, $file), ' [', $line, ']' ?></p>
        <pre><code><?= $s_trace ?></code></pre>

    </div>

    <script>
        let search = document.querySelector('.search');

        search.addEventListener('click', () => {
            window.open(
                "https://www.google.com/search?q=<?= str_replace(' ', "+", $message); ?>",
                "_blank" // <- This is what makes it open in a new window.
            );
            console.log('click');
        })
    </script>
</body>

</html>