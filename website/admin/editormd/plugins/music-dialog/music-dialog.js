/*!
 * Link dialog plugin for Editor.md
 *
 * @file        music-dialog.js
 * @author      pandao && Ki
 * @version     1.2.1
 * @updateTime  2015-06-09
 * {@link       https://github.com/pandao/editor.md}
 * @license     MIT
 */

(function () {

    var factory = function (exports) {

        var pluginName = "music-dialog";

        exports.fn.musicDialog = function () {

            var _this = this;
            var cm = this.cm;
            var editor = this.editor;
            var settings = this.settings;
            var selection = cm.getSelection();
            var lang = this.lang;
            var linkLang = lang.dialog.music;
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
                var dialogHTML = `
                <div class="${classPrefix}form">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#apiForm" data-toggle="tab">API解析</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#customForm" data-toggle="tab">自定义</a>
                        </li>
                    </ul>
                    
                    <div class="tab-content">

                        <div class="tab-pane fade show active" id="apiForm">
                            <div class="form-group mb-1">
                                <label><b class="required_marker">*</b>${linkLang.id}</label>
                                <input type="text" class="form-control" placeholder="请输入音乐ID" value="" data-id />
                                <span class="help-block"><small>部分音乐不支持解析（VIP）</small></span>
                            </div>
                            
                            <div class="form-group mb-1">
                                <label><b class="required_marker">*</b>${linkLang.musictype}</label>
                                <select class="form-control" data-type>
                                    <option value="netease" selected>网易云</option>
                                    <option value="tencent">QQ音乐</option>
                                </select>
                                <span class="help-block"><small>目前仅支持QQ音乐、网易云音乐</small></span>
                            </div>

                            <div class="form-group mb-1">
                                <label>自定义URL地址</label>
                                <input type="text" class="form-control" placeholder="无需自定义请留空" value="" data-url />
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="customForm">
                            <div class="form-group mb-1">
                                <label><b class="required_marker">*</b>音乐名称</label>
                                <input type="text" class="form-control" placeholder="请输入音乐名称" value="" data-muname />
                            </div>

                            <div class="form-group mb-1">
                                <label><b class="required_marker">*</b>音乐作者</label>
                                <input type="text" class="form-control" placeholder="请输入音乐作者" value="" data-muauthor />
                            </div>

                            <div class="form-group mb-1">
                                <label><b class="required_marker">*</b>音乐封面</label>
                                <input type="text" class="form-control" placeholder="请输入音乐封面" value="" data-mucover />
                            </div>

                            <div class="form-group mb-1">
                                <label><b class="required_marker">*</b>音频URL地址</label>
                                <input type="text" class="form-control" placeholder="请输入音频URL地址" value="" data-muurl />
                            </div>

                        </div>
                    </div>
                    
                    <input type="hidden" value="${selection}" data-title />
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
                            var id = this.find("[data-id]").val();
                            var type = this.find("[data-type]").val();
                            var url = this.find("[data-url]").val();

                            var muname = this.find("[data-muname]").val();
                            var muauthor = this.find("[data-muauthor]").val();
                            var mucover = this.find("[data-mucover]").val();
                            var muurl = this.find("[data-muurl]").val();


                            var str = '';


                            if (muname || muauthor || mucover || muurl) {
                                str = `<audio id="music" src="" data-type="custom" data-name="${muname}" data-cover="${mucover}" data-url="${muurl}" data-author="${muauthor}"></audio>`
                            } else {

                                if (id === "") {
                                    alert(linkLang.idEmpty);
                                    return false;
                                }
                                if (url) {
                                    str = `<audio id="music" src data-id="${id}" data-type="${type}" data-url="${url}"></audio>`
                                } else {
                                    str = `<audio id="music" src data-id="${id}" data-type="${type}"></audio>`;
                                }
                            }


                            if (type == "") {
                                alert("缺少参数 请重新提交");
                                return false;
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
