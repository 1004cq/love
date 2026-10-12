/*!
 * Link dialog plugin for Editor.md
 *
 * @file        link-dialog.js
 * @author      pandao
 * @version     1.2.1
 * @updateTime  2015-06-09
 * {@link       https://github.com/pandao/editor.md}
 * @license     MIT
 */

(function () {

    var factory = function (exports) {

        var pluginName = "link-dialog";

        exports.fn.linkDialog = function () {

            var _this = this;
            var cm = this.cm;
            var editor = this.editor;
            var settings = this.settings;
            var selection = cm.getSelection();
            var lang = this.lang;
            var linkLang = lang.dialog.link;
            var classPrefix = this.classPrefix;
            var dialogName = classPrefix + pluginName, dialog;

            cm.focus();

            if (editor.find("." + dialogName).length > 0) {
                dialog = editor.find("." + dialogName);
                dialog.find("[data-url]").val("http://");
                dialog.find("[data-title]").val(selection);

                this.dialogShowMask(dialog);
                this.dialogLockScreen();
                dialog.show();
            } else {
                var dialogHTML = `<div class="${classPrefix}form">
                                    <div class="form-group mb-1">
                                        <label><b class="required_marker">*</b>${linkLang.url}</label>
                                        <input type="text" class="form-control" placeholder="请输入需跳转的链接" value="" data-url />
                                    </div>

                                    <div class="form-group mb-1">
                                        <label><b class="required_marker">*</b>链接标题</label>
                                        <input type="text" class="form-control" placeholder="请输入标题文字" value="${selection}" data-title />
                                    </div>

                                    <div class="form-group mb-1">
                                        <label>${linkLang.urlTitle}</label>
                                        <select class="form-control" name="WinSwitch" data-xin>
                                            <option value="1">新窗口</option>
                                            <option value="2">本窗口</option>
                                        </select>
                                    </div>
                                 </div>    
                                `;

                dialog = this.createDialog({
                    title: linkLang.title,
                    width: 380,
                    height: "auto",
                    content: dialogHTML,
                    mask: settings.dialogShowMask,
                    drag: settings.dialogDraggable,
                    lockScreen: settings.dialogLockScreen,
                    maskStyle: {
                        opacity: settings.dialogMaskOpacity,
                        backgroundColor: settings.dialogMaskBgColor
                    },
                    buttons: {
                        enter: [lang.buttons.enter, function () {
                            var url = this.find("[data-url]").val();
                            var title = this.find("[data-title]").val();
                            var xin = this.find("[data-xin]").val();

                            if (url === "http://" || url === "") {
                                alert(linkLang.urlEmpty);
                                return false;
                            }

                            console.log(xin);

                            if (title === "") {
                                alert("标题为空 请填写标题文字");
                                return false;
                            }


                            var str = `<a href="${url}" target="_blank">${title}</a>`;

                            if (xin == 2) {
                                str = `<a href="${url}">${title}</a>`;
                            }

                            cm.replaceSelection(str);

                            this.hide().lockScreen(false).hideMask();

                            return false;
                        }],

                        cancel: [lang.buttons.cancel, function () {
                            this.hide().lockScreen(false).hideMask();

                            return false;
                        }]
                    }
                });
            }
        };

    };

    // CommonJS/Node.js
    if (typeof require === "function" && typeof exports === "object" && typeof module === "object") {
        module.exports = factory;
    } else if (typeof define === "function")  // AMD/CMD/Sea.js
    {
        if (define.amd) { // for Require.js

            define(["editormd"], function (editormd) {
                factory(editormd);
            });

        } else { // for Sea.js
            define(function (require) {
                var editormd = require("./../../editormd");
                factory(editormd);
            });
        }
    } else {
        factory(window.editormd);
    }

})();
