/**
 * @ahutor alexsandrov16
 */
!(() => {
    //SideTabs
    class SideTabs {
        constructor() {
            this.sidetabs = document.querySelectorAll(".sidenav-items.tab");
            this.titles = document.querySelectorAll(".side-tabs-title>span");
            this.contents = document.querySelectorAll(".side-tabs-content");

            if (document.querySelector(".side-tabs") && document.querySelector(".layout-editor")) {
                this.side = document.querySelector(".side-tabs").classList;
                this.editor = document.querySelector(".layout-editor").classList;
            }
        }

        init() {
            this.tabs();
            this.main();
        }

        tabs() {
            this.sidetabs.forEach((tab) => {
                tab.addEventListener("click", () => {
                    if (this.side == "side-tabs") {
                        this.side.add("active");
                        this.editor.add('margin');
                    }

                    this.sidetabs.forEach((remv_tab) => {
                        remv_tab.classList.remove("active");
                    });
                    this.titles.forEach((title) => {
                        title.classList.remove("active");
                    });
                    this.contents.forEach((content) => {
                        content.classList.remove("active");
                    });

                    tab.classList.add("active");
                    document.querySelectorAll('#' + tab.dataset.sidetab).forEach((element) => {
                        element.classList.add('active');
                    });
                });
            });
        }

        main() {
            if (document.querySelector(".side-tabs .side-tabs-title .close")) {
                document.querySelector(".side-tabs .side-tabs-title .close").addEventListener("click", () => {
                    this.side.remove("active");
                    this.editor.remove('margin');
                });
            }
        }
    }

    class Modal {
        constructor() {
            this.m_open = document.querySelectorAll(".modal-open");
            this.m_close = document.querySelectorAll(".modal-close");
            this.modals = document.querySelectorAll(".modal");
            this.el = document.createElement("fp-mback");
        }

        init() {
            //Open
            this.m_open.forEach((btnopen) => {
                btnopen.addEventListener("click", (e) => {
                    return this.openModal(e, this.modals, this.el);
                })
            });
            //Click Close
            window.addEventListener("click", (e) => {
                if (e.target == this.el) {
                    return this.closeModal(this.modals, this.el);
                }
            });
            //Close
            this.m_close.forEach((btnclose) => {
                btnclose.addEventListener("click", () => {
                    return this.closeModal(this.modals, this.el);
                });
            });
        }

        openModal(e, modals, el) {
            modals.forEach((modal) => {
                if (modal.id == e.target.dataset.modal) {
                    document.body.appendChild(el);
                    modal.classList.add("active");
                    document.querySelector("body").style.overflow = "hidden";
                }
            });
        }

        closeModal(modals, el) {
            modals.forEach((modal) => {
                if (modal.className == 'modal active') {
                    modal.classList.remove("active");
                    document.querySelector("body").style = "";
                    el.remove();
                }
            });
        }
    }

    //Instancia de las clases
    (new SideTabs).init();
    (new Modal).init();
})();

/**
 * Funciones
 */
function ShowPass(field, activator) {
    let _field = document.querySelector(field);
    let _activator = document.querySelector(activator);

    _field.addEventListener("keyup", () => {
        _activator.style.color = "#444";
        if (_field.value == "") {
            _activator.removeAttribute("style");
        }
    });

    _field.addEventListener("blur", () => {
        if (_field.value != "") {
            _activator.style.color = "#444";
        } else {
            _activator.removeAttribute("style");
        }
    });

    _activator.addEventListener("click", () => {
        if (_field.getAttribute("type") == "password") {
            _field.setAttribute("type", "text");
            _activator.textContent = "visibility_off";
        } else {
            _field.setAttribute("type", "password");
            _activator.textContent = "visibility";
        }
    });
}

function LoadBtn(slector, mnsg = "", toggle = true) {
    let btn = document.querySelector(slector);
    let width = btn.clientWidth;
    btn.addEventListener("click", () => {
        if (mnsg == "") {
            btn.style = "width:" + width + "px";
        }
        btn.innerHTML = mnsg;
        btn.classList.add("loading");
    });

    if (!toggle) {
        btn.innerHTML = mnsg;
        btn.classList.remove("loading");
    }
}

function Message(status, message) {
    let element = document.querySelector(".message");

    let show = () => {

        setTimeout(() => {
            element.innerHTML = '<p>'+message+'</p>';
            element.classList.add(status, 'active');
        }, 500);

        setTimeout(() => {
            element.classList.remove('active', status);
        }, 8000);

    };

    element.addEventListener("click", () => {
        element.classList.remove('active', status);
    });

    return show();
}
function slugfy(value, separator = '-') {
    return value
    .toString()
    .normalize('NFD')                   // split an accented letter in the base letter and the acent
    .replace(/[\u0300-\u036f]/g, '')   // remove all previously split accents
    .toLowerCase()
    .trim()
    .replace(/[^a-z0-9 ]/g, '')   // remove all chars not letters, numbers and spaces (to be replaced)
    .replace(/\s+/g, separator);
}