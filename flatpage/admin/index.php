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
</head>

<body>
    <!--Aside-->
    <aside class="sidenav">
        <div>
            <div class="sidenav-logo">
                <a href="<?= env('base_url') ?>/admin" class="logo">
                    <img src="<?= env('base_url') ?>/flatpage/admin/assets/img/logo.png" alt="" width="30">
                </a>
            </div>

            <div class="sidenav-items tab" data-sidetab="menu">
                <i class="material-icons">menu_open</i>
            </div>

            <div class="sidenav-items tab" data-sidetab="password">
                <i class="material-icons">password</i>
            </div>

            <div class="sidenav-items tab" data-sidetab="setting">
                <i class="material-icons">settings</i>
            </div>

        </div>


        <div>
            <a class="sidenav-items user" href="<?= env('base_url') ?>" target="_blank">
                <i class="material-icons">launch</i>
            </a>

            <div class="sidenav-items user">
                <i class="material-icons modal-open" data-modal="logout">power_settings_new</i>
            </div>
        </div>
    </aside>
    <!--/Aside-->

    <main>
        <div class="side-tabs">

            <div class="container">
                <span class="side-tabs-title">
                    <span id="menu">Menu</span>
                    <span id="setting">Ajuste</span>
                    <span id="password">Cambiar Contraseña</span>
                    <i class="material-icons close">close</i>
                </span>

                <!--Menu-->
                <div class="side-tabs-content" id="menu">
                    <a class="btn btn-sm f-right modal-open" data-modal="add-menu">
                        <i class="material-icons">add</i>
                        añadir
                    </a>
                    <br>
                    <ul id="list-menu" style="margin-top: 1em;">
                        <?php
                        $men = [];
                        foreach ($menus as $key => $value) {
                            foreach ($value as $name => $link) { ?>
                                <li class="">
                                    <?= $name ?>
                                    <span>
                                        <i class="material-icons modal-open" data-modal="edit-<?= $key ?>" id="edit">edit</i>
                                        <i class="material-icons modal-open" data-modal="del-<?= $key ?>" id="delete">delete</i>
                                    </span>
                                </li>
                        <?php }
                            //$men[$key] = $value;

                        } ?>
                    </ul>
                    <style>
                        #list-menu {
                            margin-top: 1em;
                        }

                        #list-menu li {
                            display: flex;
                            justify-content: space-between;
                            padding: .25em 0;
                            border-bottom: 1px solid transparent;
                        }

                        #list-menu li:hover {
                            border-bottom: 1px solid #e3e3e3;
                        }

                        #list-menu li span i {
                            font-size: 1em;
                            margin-left: .75em;
                            cursor: pointer;
                        }

                        #list-menu li span i#edit {
                            color: var(--info);
                        }

                        #list-menu li span i#delete {
                            color: var(--danger);
                        }
                    </style>
                </div>

                <!--Password-->
                <div class="side-tabs-content" id="password">
                    <span onclick="s('.side-tabs-content#password span')">Mostrar</span>
                    <style>
                        .side-tabs-content#password span {
                            float: right;
                            color: #80868b;
                            cursor: pointer;
                            font-size: small;
                        }

                        .side-tabs-content#password span:hover {
                            color: #666;
                        }
                    </style>

                    <label for="">Nueva Contraseña</label>
                    <div class="input-field"><input type="password" id="pass" autocomplete="off"></div>

                    <label for="">Confirmar Contraseña</label>
                    <div class="input-field"><input type="password" id="confirm" autocomplete="off"></div>

                    <button type="submit" class="btn lg" id="change_pass" disabled>cambiar</button>
                </div>
                <!--Password-->

                <!--Ajustes-->
                <div class="side-tabs-content" id="setting">
                    <label for="">Nombre del Sitio</label>
                    <div class="input-field"><input type="text" id="title" value="<?= env('title') ?>" autocomplete="off"></div>

                    <label for="">Descripción del Sitio</label>
                    <textarea id="description" autocomplete="off"><?= env('description') ?></textarea>

                    <label for="">Url del Sitio</label>
                    <div class="input-field"><input type="text" id="base_url" value="<?= env('base_url') ?>" autocomplete="off"></div>

                    <label for="">Plantilla</label>
                    <div class="input-field"><input type="text" id="template" value="<?= env('template') ?>" autocomplete="off"></div>

                    <button id="btn_sett" class="btn lg">actualizar</button>
                </div>
                <!--Ajustes-->
            </div>
        </div>


        <div class="container layout-editor">
            <div class="navbar">
                <span></span>
                <!--<span class="material-icons dropdown-toggle">more_vert</span>-->
                <button class="btn btn-sm" id="sendDoc" onclick="saveDoc()">publicar</button>
            </div>

            <div id="editor"></div>
        </div>
    </main>
    <!--/Main-->

    <!--Footer-->
    <footer class="container">
        <?= '&copy; ', App::$name, ' v', App::$version ?>
    </footer>
    <!--/Footer-->

    <div class="message"></div>

    <!--Modals-->
    <div class="modal" id="logout">
        <div class="modal-content">
            <div class="modal-title">
                ¿Desea Salir?
            </div>
            <p>Hemos detectado que la sesión actual está a punto de cerrarse ¿desea continuar el cierre de la sesión?</p>
            <div class="modal-footer">
                <button class="modal-close btn">cancelar</button>
                <button type="submit" class="btn" onclick="logoff()">aceptar</button>
                <script>
                    let logoff = () => {
                        window.location.href = '<?= env('base_url') ?>/admin/logout';
                    }
                </script>
            </div>
        </div>
    </div>

    <div class="modal" id="add-menu">
        <form method="POST" action="<?= env('base_url') . '/admin/menu' ?>" class="modal-content">
            <div class="modal-title">
                Nuevo Menu
            </div>
            <input type="hidden" name="action" value="create">
            <label>Título</label>
            <div class="input-field"><input type="text" name="name" id="c-menu-name" autocomplete="off"></div>
            <label>Url</label>
            <div class="input-field"><input type="text" name="link" id="" autocomplete="off"></div>
            <input type="hidden" name="slug" id="c-menu-slug">
            <div class="modal-footer">
                <button type="reset" class="modal-close btn">cancelar</button>
                <button type="submit" class="btn">aceptar</button>
            </div>
        </form>
    </div>



    <?php
    foreach ($menus as $key => $value) {
        foreach ($value as $name => $link) { ?>
            <div class="modal" id="edit-<?= $key ?>">

                <form method="POST" action="<?= env('base_url') . '/admin/menu' ?>" class="modal-content">
                    <div class="modal-title">
                        Editar Menu
                    </div>
                    <input type="hidden" name="action" value="update">
                    <label>Título</label>
                    <div class="input-field"><input type="text" name="name" id="" autocomplete="off" value="<?= $name ?>"></div>
                    <label>Url</label>
                    <div class="input-field"><input type="text" name="link" id="" autocomplete="off" value="<?= $link ?>"></div>
                    <input type="hidden" name="slug" id="c-menu-slug" value="<?= $key ?>">
                    <div class="modal-footer">
                        <button type="reset" class="modal-close btn">cancelar</button>
                        <button type="submit" class="btn">aceptar</button>
                    </div>
                </form>
            </div>

            <div class="modal" id="del-<?= $key ?>">
                <form action="<?= env('base_url') . '/admin/menu' ?>" method="POST" class="modal-content">
                    <div class="modal-title">
                        Eliminar Menu
                    </div>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="del" value="<?= $key ?>">
                    ¿Confirma que desea eliminar este elemento?
                    <div class="modal-footer">
                        <button type="reset" class="modal-close btn">cancelar</button>
                        <button type="submit" class="btn">aceptar</button>
                    </div>
                </form>
            </div>
    <?php }
    } ?>

    <?= App::themes()->script('flatpage.js', true) ?>

    <?php
    if (isset($_COOKIE['alert']) && $_COOKIE['alert'] == true) { ?>
        <script>
            Message('warning', 'Cambie su contraseña');
        </script>
    <?php
        setcookie('alert', null, time() - 60);
    }

    if (env('root')) { ?>
        <script>
            Message('warning', 'Superusuario <b><?= $_SESSION['user'] ?></b> habilitado. Por favor cambie su contraseña.');
        </script>
    <?php } ?>

    <script>
        //Setting
        LoadBtn('button#btn_sett', 'actualizando')
        document.querySelector('button#btn_sett').addEventListener('click', () => {
            let formData = new FormData();

            formData.append('title', document.querySelector('#setting input#title').value);
            formData.append('description', document.querySelector('#setting textarea#description').value);
            formData.append('base_url', document.querySelector('#setting input#base_url').value);
            formData.append('template', document.querySelector('#setting input#template').value);

            let url = '<?= env('base_url'), '/admin/settings' ?>';
            let init = {
                method: 'POST',
                body: formData,
            };


            fetch(url, init)
                .then((response) => {
                    return response.text();
                })
                .then((data) => {
                    if (data === true) {
                        Message('success', 'Cambios salvados');
                    } else {
                        Message('danger', 'Se produjo un error al salvar los cambios');
                    }
                    LoadBtn('button#btn_sett', 'actualizar', false);
                })
                .catch((error) => {
                    Message('danger', error);
                    LoadBtn('button#btn_sett', 'actualizar', false);
                })
        })

        //Password
        let btn_pass = document.querySelector('button#change_pass');
        let user = document.querySelector('#password input#user');
        let pass = document.querySelector('#password input#pass');
        let pass_confirm = document.querySelector('#password input#confirm');

        const s = (a) => {
            let act = document.querySelector(a);
            if (pass.getAttribute("type") == "password" && pass_confirm.getAttribute("type") == "password") {
                pass.setAttribute("type", "text");
                pass_confirm.setAttribute("type", "text");
                act.innerText = 'Ocultar';
            } else {
                pass.setAttribute("type", "password");
                pass_confirm.setAttribute("type", "password");
                act.innerText = 'Mostrar'
            }
        }

        pass_confirm.addEventListener('keyup', () => {
            if (pass_confirm.value == pass.value) {
                btn_pass.removeAttribute('disabled');
            } else {
                btn_pass.setAttribute('disabled', 'disabled');
            }
        });

        window.addEventListener('click', (e) => {
            if (document.querySelector('#password.side-tabs-content.active') == null) {
                pass.value = null;
                pass_confirm.value = null;
                btn_pass.setAttribute('disabled', 'disabled');
            }
        })

        LoadBtn('button#change_pass', 'actualizando')
        btn_pass.addEventListener('click', () => {
            let formData = new FormData();

            formData.append('pass', pass.value);

            let url = '<?= env('base_url') . '/admin/password' ?>';
            let init = {
                method: 'POST',
                body: formData,
            };


            fetch(url, init)
                .then((response) => {
                    return response.text();
                })
                .then((data) => {
                    console.log(data);
                    if (data === true) {
                        Message('success', 'Contraseña actualizada');
                    } else {
                        Message('danger', 'Se produjo un error');
                    }
                    LoadBtn('button#change_pass', 'cambiar', false);
                })
                .catch((error) => {
                    Message('danger', error);
                    LoadBtn('button#change_pass', 'cambiar', false);
                })
        })


        //Menu
        LoadBtn("#add-menu form button[type='submit']");

        let c_name = document.querySelector('input#c-menu-name');
        let c_slug = document.querySelector('input#c-menu-slug');
        c_name.onkeyup = () => {
            c_slug.value = slugfy(c_name.value);
        }
    </script>

    <?=
    App::themes()->script('EditorJS/editor.js', true),
    App::themes()->script('EditorJS/modules/header@latest.js', true),
    App::themes()->script('EditorJS/modules/list@latest.js', true),
    App::themes()->script('EditorJS/modules/quote@latest.js', true),
    App::themes()->script('EditorJS/modules/image@2.3.js', true),
    App::themes()->script('EditorJS/modules/inline-code.js', true),
    App::themes()->script('EditorJS/modules/code.js', true),
    App::themes()->script('EditorJS/modules/underline@latest.js', true)
    ?>
    <!--Editor.Js-->
    <script>
        const ImageTool = window.ImageTool;

        const editor = new EditorJS({
            /**
             * Id of Element that should contain the Editor
             */
            holder: 'editor',

            /**
             * PlaceHolder
             */
            placeholder: '¡Escribamos una historia asombrosa!',
            /**
             * Available Tools list.
             * Pass Tool's class or Settings object for each Tool you want to use
             */
            tools: {

                //string
                header: {
                    class: Header,
                    config: {
                        levels: [1, 2, 3, 4, 5, 6],
                        defaultLevel: 1
                    },
                },
                list: List,
                quote: Quote,
                underline: Underline,
                code: CodeTool,
                inlineCode: InlineCode,

                //file
                image: {
                    class: ImageTool,
                    config: {
                        types: 'image/*',
                        endpoints: {
                            byFile: '<?= env('base_url'), '/admin/page' ?>', // Your backend file uploader endpoint
                            //byUrl: '<?= env('base_url'), '/admin/page' ?>', // Your endpoint that provides uploading by Url
                        }
                    },
                },
            },

            /**
             * Internationalzation config
             */
            i18n: {
                /**
                 * @type {I18nDictionary}
                 */
                messages: {
                    /**
                     * Other below: translation of different UI components of the editor.js core
                     */
                    ui: {
                        "blockTunes": {
                            "toggler": {
                                "Click to tune": "Haga clic para personalizar",
                                "or drag to move": " o arrastre para mover"
                            },
                        },
                        "inlineToolbar": {
                            "converter": {
                                "Convert to": "Convertir a"
                            }
                        },
                        "toolbar": {
                            "toolbox": {
                                "Add": "Añadir"
                            }
                        }
                    },

                    /**
                     * Section for translation Tool Names: both block and inline tools
                     */
                    toolNames: {
                        "Text": "Texto",
                        "Heading": "Título",
                        "List": "Lista",
                        /*
                                                "Warning": "Примечание",
                                                "Checklist": "Чеклист",*/
                        "Quote": "Cita",
                        "Code": "Código",
                        /*
                                                "Delimiter": "Разделитель",*/
                        "Raw HTML": "HTML-фрагмент",
                        "Table": "Tabla",
                        "Link": "Enlace",
                        "Marker": "Marcador",
                        "Bold": "Negrita",
                        "Italic": "Cursiva",
                        "Underline": "Subrayado",
                        "InlineCode": "Código",
                        "Image": "Imagen"
                    },

                    /**
                     * Section for passing translations to the external tools classes
                     */
                    tools: {
                        /**
                         * Each subsection is the i18n dictionary that will be passed to the corresponded plugin
                         * The name of a plugin should be equal the name you specify in the 'tool' section for that plugin
                         */
                        "warning": { // <-- 'Warning' tool will accept this dictionary section
                            "Title": "Название",
                            "Message": "Сообщение",
                        },

                        /**
                         * Link is the internal Inline Tool
                         */
                        "link": {
                            "Add a link": "Añadir enlace"
                        },
                        "list": {
                            "Ordered": "Ordenado",
                            "Unordered": "Desordenado"
                        },
                        /**
                         * The "stub" is an internal block tool, used to fit blocks that does not have the corresponded plugin
                         */
                        "stub": {
                            'The block can not be displayed correctly.': 'Блок не может быть отображен'
                        }
                    },

                    /**
                     * Section allows to translate Block Tunes
                     */
                    blockTunes: {
                        /**
                         * Each subsection is the i18n dictionary that will be passed to the corresponded Block Tune plugin
                         * The name of a plugin should be equal the name you specify in the 'tunes' section for that plugin
                         *
                         * Also, there are few internal block tunes: "delete", "moveUp" and "moveDown"
                         */
                        "delete": {
                            "Delete": "Eliminar bloque"
                        },
                        "moveUp": {
                            "Move up": "Subir bloque"
                        },
                        "moveDown": {
                            "Move down": "Bajar bloque"
                        }
                    },
                }
            },

            /**
             * Add Data
             */
            data: <?= $content ?>
        });

        LoadBtn('button#sendDoc', 'publicando');


        function saveDoc() {
            const btn = document.querySelector('button#sendDoc');
            btn.style = 'display: flex;align-items: center;';

            editor.save().then((outputData) => {

                let formData = new FormData();

                formData.append('page', JSON.stringify(outputData));

                fetch('<?= env('base_url'), '/admin/page' ?>', {
                        method: 'POST',
                        body: formData,
                    })
                    .then(function(response) {
                        return response.text();
                    })
                    .then(function(data) {
                        LoadBtn('button#sendDoc', 'publicar', false);
                        Message('success', data);
                    })
                    .catch(function(errorFetch) {
                        Message('danger', "Error Fetch: ", errorFetch);
                        LoadBtn('button#sendDoc', 'publicar', false);
                        console.error(errorFetch);
                    });

            }).catch((errorEditor) => {
                Message('danger', "Error en el editor: ", errorEditor);
                LoadBtn('button#sendDoc', 'publicar', false);
                console.log("Error: ", errorEditor)
            });
        }
    </script>

</body>

</html>