<?php
session_start();
include_once "Database.php";
include_once 'Function.php';
if (!userHasPermission($_SESSION['loginadmin'], $conn)) {
    die("<script>alert('参数错误 请先登陆');location.href = 'login.php'</script>");
}
/*
 * @Page: "ajax.php"
 * @Author: Ki.
 * @Project: LG_NewUi
 * @LastEditTime: 2024-04-25 14:45:47
 * @Description: 小手一牵 岁岁年年
 * Copyright (c) 2022-2024 by Ki All Rights Reserved.
 */
?>
<script>
    const SiteTitle = $('.topnav-logo span').eq(0).text();

    function adopt(id) {
        $.ajax({
            url: "leav_switch.php",
            data: {
                id: id,
                type: "adopt"
            },
            type: "POST",
            dataType: "json",
            success: function (res) {
                toastr["success"](res.msg_s, "LG_NewUi");
                <?php if ($PublicSendingSwitch == "true") {
                    echo "mail_user(res.data,'已通过','adopt');";
                } ?>
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    }


    function fail(id) {
        $.ajax({
            url: "leav_switch.php",
            data: {
                id: id,
                type: "fail"
            },
            type: "POST",
            dataType: "json",
            success: function (res) {
                toastr["success"](res.msg_s, "LG_NewUi");
                <?php if ($PublicSendingSwitch == "true") {
                    echo "mail_user(res.data,'已被驳回','fail');";
                } ?>
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    }

    function mail_user(user_arr, MailContent, Type) {
        if (user_arr.QQ == "anon") {
            toastr["warning"]("当前留言为匿名游客</br>本次取消邮件发送！", "LG_NewUi");
            return false
        }
        let mail = user_arr.QQ + "@qq.com";
        let date = user_arr.time;
        let type;
        if (Type == "adopt") {
            type = "SendAdopt"
        } else if (Type == "fail") {
            type = "SendFail"
        } else {
            toastr["error"]("发信失败 缺少参数标识", "LG_NewUi");
            return false
        }
        $.ajax({
            url: "Mail_sent.php",
            data: {
                mail_name: user_arr.name,
                mail_text: user_arr.text,
                user_mail: mail,
                release_time: date,
                sending: false,
                mail_sw: MailContent,
                type: type
            },
            type: "POST",
            dataType: "json",
            success: function (res) {
                toastr["success"]("已发送邮件通知留言用户", "LG_NewUi");
            },
            error: function (err) {
                if (err.responseText == "success") {
                    toastr["success"]("已发送邮件通知留言用户", "LG_NewUi");
                } else {
                    toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
                    console.log(err.responseText);
                }

            }
        })
    }


    function processAdminAction() {
        let Webanimation = $("select[name='Webanimation']").val();
        let open_pw = $("input[name='open_pw']").val();
        let pw_bg_url = $("input[name='pw_bg_url']").val();
        let diy_page_pw = $("input[name='diy_page_pw']").val();
        let url_pw = $("input[name='url_pw']").val();
        let index_leav_num = $("input[name='index_leav_num']").val();
        let index_essay_num = $("input[name='index_essay_num']").val();
        let pcCarouselHeight = $("input[name='pcCarouselHeight']").val();
        let mobileCarouselHeight = $("input[name='mobileCarouselHeight']").val();
        let adminName_1 = $("input[name='adminName_1']").val();
        let pw_1 = $("input[name='pw_1']").val();
        let adminName_2 = $("input[name='adminName_2']").val();
        let pw_2 = $("input[name='pw_2']").val();
        let SCode = $("input[name='SCode']").val();
        let siteMaintenance = $("input[name='siteMaintenance']").val();
        let SmTitie = $("input[name='SmTitie']").val();
        let SmText = $("input[name='SmText']").val();
        let SmBgUrl = $("input[name='SmBgUrl']").val();
        let is_aboutActive = $("input[name='is_aboutActive']").val();
        let cssCon = $("textarea[name='cssCon']").val();
        let headCon = $("textarea[name='headCon']").val();
        let footerCon = $("textarea[name='footerCon']").val();
        let carousel = $("textarea[name='carousel']").val();
        let currentBottomTab = $("input[name='TabTemplate']").val();

        //用户开启页面维护 则判断标题、文本、背景图值是否为空
        if (parseInt(siteMaintenance)) {
            if (!(SmTitie && SmText && SmBgUrl)) {
                toastr["warning"]("已开启页面维护 请先填写完整对应表单内容！", "LG_NewUi");
                return false
            }
        }

        //用户开启前端页面加密访问 则判断密码、背景图值是否为空
        if (parseInt(open_pw)) {
            if (!(diy_page_pw && url_pw)) {
                toastr["warning"]("已开启前端加密访问 请先填写完整对应表单内容！", "LG_NewUi");
                return false
            }
        }

        //留言截取数量、文章截取数量、顶部轮播图背景URL
        if (!(index_leav_num && index_essay_num && carousel)) {
            toastr["warning"]("请检查留言/文章显示数量/轮播图URL表单是否填写完整！", "LG_NewUi");
            return false
        }

        //检查管理员用户名设置是否合法
        function CheckAdmin(CheckUser) {
            let usernameRegex = /^[a-zA-Z0-9]{6,}$/;
            if (!CheckUser || CheckUser.length == 0) {
                return 0;
            } else if (!usernameRegex.test(CheckUser)) {
                return 1;
            }
        }

        //检查管理员用户名设置是否合法
        function CheckAdmin(CheckUser) {
            //满足6位数 并包含任意一位大/小字母
            let usernameRegex = /^(?=.*[a-zA-Z0-9])[a-zA-Z0-9]{6,}$/;
            if (!CheckUser || CheckUser.length == 0) {
                return 0;
            } else if (!usernameRegex.test(CheckUser)) {
                return 1;
            }
        }

        //检查管理员密码置是否合法
        function CheckPw(PwCheck) {
            // 满足七位数 并包含其中一位大小写字母
            let PwRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d!@#$%^&*]{7,}$/;
            if (!PwRegex.test(PwCheck)) {
                return 1;
            }
        }

        if (CheckAdmin(adminName_1) == 0) {
            toastr["warning"]("请检查男主用户名是否为空！", "LG_NewUi");
            return false
        }

        if (CheckAdmin(adminName_2) == 0) {
            toastr["warning"]("请检查女主用户名是为空！", "LG_NewUi");
            return false
        }

        if (pw_1 != '') {
            if (CheckPw(pw_1) == 1) {
                toastr["warning"]("请检查男主密码是包含大小写字母数字并满足7位以上！", "LG_NewUi");
                return false
            }
        }

        if (pw_2 != '') {
            if (CheckPw(pw_2) == 1) {
                toastr["warning"]("请检查女主密码是包含大小写字母数字并满足7位以上！", "LG_NewUi");
                return false
            }
        }


        $.ajax({
            url: "userPost.php",
            data: {
                siteMaintenance: siteMaintenance,
                SmTitie: SmTitie,
                SmText: SmText,
                SmBgUrl: SmBgUrl,
                Webanimation: Webanimation,
                open_pw: open_pw,
                diy_page_pw: diy_page_pw,
                url_pw: url_pw,
                index_leav_num: index_leav_num,
                index_essay_num: index_essay_num,
                adminName_one: adminName_1,
                pw_one: pw_1,
                adminName_two: adminName_2,
                pw_two: pw_2,
                SCode: SCode,
                cssCon: cssCon,
                headCon: headCon,
                footerCon: footerCon,
                carousel: carousel,
                is_aboutActive: is_aboutActive,
                pcCarouselHeight: pcCarouselHeight,
                mobileCarouselHeight: mobileCarouselHeight,
                currentBottomTab: currentBottomTab,
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1835) {
                    closePopupLayer();
                    toastr["success"]("更新登录信息成功！", "LG_NewUi");
                    toastr["success"]("更新全局信息成功", "LG_NewUi");
                    toastr["success"]("更新自定义内容成功", "LG_NewUi");
                    let interval = setInterval(function () {
                        location.reload();
                        clearInterval(interval);
                    }, 1000);
                } else if (res == 0946) {
                    toastr["error"]("更新登录信息失败！", "LG_NewUi");
                    toastr["error"]("更新全局信息失败！", "LG_NewUi");
                    toastr["error"]("更新自定义内容失败！", "LG_NewUi");

                } else if (res == 7) {
                    toastr["error"]("安全码错误！", "LG_NewUi");
                } else {
                    toastr["error"]("未知错误！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    }



    $("#adminPost").click(function () {
        let title = $("input[name='title']").val();
        let logo = $("input[name='logo']").val();
        let writing = $("input[name='writing']").val();
        let Fullscreen = $("select[name='Fullscreen']").val();
        let WebBlur = $("select[name='WebBlur']").val();
        let WebPjax = $("select[name='WebPjax']").val();
        let BackgroundMask = $("select[name='BackgroundMask']").val();

        let seo_switch = $("input[name='seo_switch']").val();
        let site_description = $("textarea[name='site_description']").val();
        let global_keywords = $("textarea[name='global_keywords']").val();

        if (!(title && logo && writing)) {
            toastr["warning"]("站点标题/LOGO/文案不可为空！", "LG_NewUi");
            return false
        }

        $.ajax({
            url: "adminPost.php",
            data: {
                title: title,
                logo: logo,
                writing: writing,
                WebBlur: WebBlur,
                WebPjax: WebPjax,
                Fullscreen: Fullscreen,
                BackgroundMask: BackgroundMask,
                seo_switch,
                site_description,
                global_keywords,
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 13) {
                    toastr["success"]("基本信息修改成功！", "LG_NewUi");
                    toastr["success"]("开关设置成功！", "LG_NewUi");
                } else if (res == 04) {
                    toastr["error"]("基本信息修改失败！", "LG_NewUi");
                    toastr["error"]("开关设置修改失败！", "LG_NewUi");
                } else {
                    toastr["error"]("未知错误！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })

    $("#Mail_Post").click(function () {
        var title = $("input[name='title']").val();
        var from_name = $("input[name='from_name']").val();
        var host = $("input[name='host']").val();
        var mail_ssl = $("input[name='mail_ssl']").val();
        var port = $("input[name='port']").val();
        var mail_user = $("input[name='mail_user']").val();
        var mail_pw = $("input[name='mail_pw']").val();
        var admin_mail = $("input[name='admin_mail']").val();
        var admin_name = $("input[name='admin_name']").val();
        var user_mail = $("input[name='user_mail']").val();
        var user_name = $("input[name='user_name']").val();
        var SendingSwitch = $("input[name='SendingSwitch']").val();
        var LoginMailSwitch = $("input[name='LoginMailSwitch']").val();
        var LoginSendlMinute = $("input[name='LoginSendlMinute']").val();

        if (SendingSwitch || LoginMailSwitch) {
            if (!(title && from_name && host && port && mail_user && mail_pw)) {
                toastr["warning"]("请检查邮箱信息配置中的—</br>《发信配置》模块是否填写完整！", "LG_NewUi");
                return false
            }
            if (!(admin_mail && admin_name && user_mail && user_name)) {
                toastr["warning"]("请检查邮箱信息配置中的—</br>《接收配置》模块是否填写完整！", "LG_NewUi");
                return false
            }
        }


        $.ajax({
            url: "MailPost.php",
            data: {
                title: title,
                from_name: from_name,
                host: host,
                mail_ssl: mail_ssl,
                port: port,
                mail_user: mail_user,
                mail_pw: mail_pw,
                admin_mail: admin_mail,
                admin_name: admin_name,
                user_mail: user_mail,
                user_name: user_name,
                SendingSwitch: SendingSwitch,
                LoginMailSwitch: LoginMailSwitch,
                LoginSendlMinute: LoginSendlMinute
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1) {
                    toastr["success"]("修改邮箱配置成功", "LG_NewUi");
                } else if (res == 0) {
                    toastr["error"]("修改邮箱配置失败 请稍后重试", "LG_NewUi");
                } else {
                    toastr["error"]("未知错误！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })


    $("#loveadminPost").click(function () {
        let boy = $("input[name='boy']").val();
        let girl = $("input[name='girl']").val();
        let boyimg = $("input[name='boyimg']").val();
        let girlimg = $("input[name='girlimg']").val();
        let if_aiv = $("input[name='if_aiv']").val();
        let b_diy = $("input[name='b_diy']").val();
        let g_diy = $("input[name='g_diy']").val();
        let startTime = $("input[name='startTime']").val();
        let loveDayTitle = $("input[name='loveDayTitle']").val();
        let loveIconLink = $("input[name='loveIconLink']").val();

        let ml_frame = $("select[name='ml_frame']").val()
        let fl_frame = $("select[name='fl_frame']").val()

        if (!(boy && girl)) {
            toastr["warning"]("男主/女主昵称不可为空！", "LG_NewUi");
            return false
        }

        if (!loveDayTitle && !loveIconLink) {
            toastr["warning"]("自定义卡片标题/爱心图标不可为空！", "LG_NewUi");
            return false
        }

        if (if_aiv == 1) {
            if (!(b_diy && g_diy)) {
                toastr["warning"]("男主/女主头像URL地址不可为空！", "LG_NewUi");
                return false
            }
        }

        if (if_aiv == 0) {
            if (!(boyimg && girlimg)) {
                toastr["warning"]("男主/女主QQ号码不可为空！", "LG_NewUi");
                return false
            }
        }
        console.log(ml_frame);
        $.ajax({
            url: "loveadminPost.php",
            data: {
                if_aiv: if_aiv,
                g_diy: g_diy,
                b_diy: b_diy,
                boy: boy,
                girl: girl,
                boyimg: boyimg,
                girlimg: girlimg,
                startTime: startTime,
                loveDayTitle: loveDayTitle,
                loveIconLink: loveIconLink,
                ml_frame: ml_frame,
                fl_frame: fl_frame,
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1) {
                    toastr["success"]("情侣信息修改成功！", "LG_NewUi");
                } else if (res == 0) {
                    toastr["error"]("情侣信息修改失败！", "LG_NewUi");
                } else if (res == 3) {
                    toastr["error"]("QQ号码格式错误！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })

    $("#CardadminPost").click(function () {
        var bgimg = $("input[name='bgimg']").val();
        var card1 = $("input[name='card1']").val();
        var deci1 = $("input[name='deci1']").val();
        var card2 = $("input[name='card2']").val();
        var deci2 = $("input[name='deci2']").val();
        var card3 = $("input[name='card3']").val();
        var deci3 = $("input[name='deci3']").val();
        var card4 = $("input[name='card4']").val();
        var deci4 = $("input[name='deci4']").val();
        var icp = $("input[name='icp']").val();
        var Moelicp = $("input[name='Moelicp']").val();
        var policeICP = $("input[name='policeICP']").val();
        var Copyright = $("input[name='Copyright']").val();

        if (!(card1 && card2 && card3 && card4)) {
            toastr["warning"]("请检查页面卡片标题是否为空！", "LG_NewUi");
            return false
        }
        if (!(deci1 && deci2 && deci3 && deci4)) {
            toastr["warning"]("请检查页面卡片描述是否为空！", "LG_NewUi");
            return false
        }
        $.ajax({
            url: "CardadminPost.php",
            data: {
                bgimg: bgimg,
                card1: card1,
                deci1: deci1,
                card2: card2,
                deci2: deci2,
                card3: card3,
                deci3: deci3,
                card4: card4,
                deci4: deci4,
                icp: icp,
                Copyright: Copyright,
                Moelicp,
                policeICP,
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1) {
                    toastr["success"]("卡片信息修改成功！", "LG_NewUi");
                } else if (res == 0) {
                    toastr["error"]("卡片信息修改失败！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })

    $("#leavPPost").click(function () {
        let jiequ = $("input[name='jiequ']").val();
        let leav_day_num = $("input[name='leav_day_num']").val();
        let lanjiezf = $("textarea[name='lanjiezf']").val();
        let randomWord = $("textarea[name='randomWord']").val();
        let anonymousArr = $("textarea[name='anonymousArr']").val();

        let MsgSwitch = $("select[name='MsgSwitch']").val();
        let MsgVerify = $("select[name='MsgVerify']").val();
        let CutOffSwitch = $("select[name='CutOffSwitch']").val();
        let EmojiSwitch = $("select[name='EmojiSwitch']").val();


        if (!(jiequ && leav_day_num && lanjiezf && anonymousArr)) {
            toastr["warning"]("请检查留言页配置表单内容否为空！", "LG_NewUi");
            return false
        }
        let PIjiequ = parseInt(jiequ)
        let PILdn = parseInt(leav_day_num)
        if (!(typeof PIjiequ === 'number' && !isNaN(PIjiequ)) || !(typeof PILdn === 'number' && !isNaN(PILdn))) {
            toastr["warning"]("限制留言次数/显示最新N条留言</br>必须为纯数字 当前内容不合法！", "LG_NewUi");
            return false

        }
        $.ajax({
            url: "leavPPost.php",
            data: {
                jiequ: jiequ,
                leav_day_num: leav_day_num,
                lanjiezf: lanjiezf,
                anonymousArr: anonymousArr,
                randomWord,
                MsgSwitch,
                MsgVerify,
                CutOffSwitch,
                EmojiSwitch
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1) {
                    toastr["success"]("留言设置修改成功！", "LG_NewUi");
                } else if (res == 0) {
                    toastr["error"]("留言设置修改失败！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })

    $("#littleupda").click(function () {
        var id = $("input[name='id']").val();
        var articletitle = $("input[name='articletitle']").val();
        var little_day = $("input[name='little_day']").val();
        var encryptionSwitch = $("input[name='encryptionSwitch']").val();
        var PasswordSee = $("input[name='PasswordSee']").val();
        var articletext = $("textarea[name='articletext']").val();

        if (encryptionSwitch == 1) {
            if (!PasswordSee) {
                toastr["warning"]("请输入自定义密码！", "LG_NewUi");
                return false
            }
        }

        if (!(articletitle && articletext)) {
            toastr["warning"]("请检查标题/内容是否为空 请重试！", "LG_NewUi");
            return false
        }

        $.ajax({
            url: "littleupda.php",
            data: {
                articletitle: articletitle,
                articletext: articletext,
                little_day: little_day,
                encryptionSwitch: encryptionSwitch,
                PasswordSee: PasswordSee,
                id: id,
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1) {
                    toastr["success"]("文章修改成功！", "LG_NewUi");
                    $('#littleupda').text('修改中...');
                    $("#littleupda").attr("disabled", "disabled");
                    setInterval("window.location.href='littleSet.php'", 1000);
                } else if (res == 0) {
                    toastr["error"]("文章修改失败！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })
    $("#littleAddPost").click(function () {
        var city_ipa = $("input[name='city_ip']").val();
        var articlename = $("select[name='articlename']").val();
        var articletitle = $("input[name='articletitle']").val();
        var little_day = $("input[name='little_day']").val();
        var encryptionSwitch = $("input[name='encryptionSwitch']").val();
        var PasswordSee = $("input[name='PasswordSee']").val();
        var articletext = $("textarea[name='articletext']").val();

        if (encryptionSwitch == 1) {
            if (!PasswordSee) {
                toastr["warning"]("请输入自定义密码！", "LG_NewUi");
                return false
            }
        }

        if (!(articletitle && articletext)) {
            toastr["warning"]("请检查标题/内容是否为空 请重试！", "LG_NewUi");
            return false
        }
        $.ajax({
            url: "littleAddPost.php",
            data: {
                articletitle: articletitle,
                articletext: articletext,
                articlename: articlename,
                city_ipa: city_ipa,
                little_day: little_day,
                encryptionSwitch: encryptionSwitch,
                PasswordSee: PasswordSee
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1) {
                    toastr["success"]("新增文章成功！", "LG_NewUi");
                    $('#littleAddPost').text('发布中...');
                    $("#littleAddPost").attr("disabled", "disabled");
                    setInterval("window.location.href='littleSet.php'", 1000);
                } else if (res == 0) {
                    toastr["error"]("新增文章失败！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })
    $("#ImgUpdaPost").click(function () {
        var imgDatd = $("input[name='imgDatd']").val();
        var imgText = $("input[name='imgText']").val();
        var id = $("input[name='id']").val();
        var imgUrl = $("input[name='imgUrl']").val();
        var encryptionSwitch = $("input[name='encryptionSwitch']").val();
        var PasswordSee = $("input[name='PasswordSee']").val();

        if (encryptionSwitch == 1) {
            if (!PasswordSee) {
                toastr["warning"]("请输入自定义密码！", "LG_NewUi");
                return false
            }
        }

        $.ajax({
            url: "ImgUpdaPost.php",
            data: {
                imgDatd: imgDatd,
                imgText: imgText,
                imgUrl: imgUrl,
                id: id,
                encryptionSwitch: encryptionSwitch,
                PasswordSee: PasswordSee
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1) {
                    toastr["success"]("相册修改成功！", "LG_NewUi");
                    $('#ImgUpdaPost').text('修改中...');
                    $("#ImgUpdaPost").attr("disabled", "disabled");
                    setInterval("window.location.href='loveImgSet.php'", 1000);
                } else if (res == 0) {
                    toastr["error"]("相册修改失败！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })
    $("#ImgAddPost").click(function () {
        var upload_name = $("select[name='upload_name']").val();
        var imgDatd = $("input[name='imgDatd']").val();
        var imgText = $("input[name='imgText']").val();
        var imgUrl = $("input[name='imgUrl']").val();
        var img_code = $("input[name='img_code']").val();
        var encryptionSwitch = $("input[name='encryptionSwitch']").val();
        var PasswordSee = $("input[name='PasswordSee']").val();

        if (encryptionSwitch == 1) {
            if (!PasswordSee) {
                toastr["warning"]("请输入自定义密码！", "LG_NewUi");
                return false
            }
        }

        if (!(imgText && imgUrl)) {
            toastr["warning"]("请检查相册描述/封面URL否为空</br> 请重试！", "LG_NewUi");
            return false
        }

        $.ajax({
            url: "ImgAddPost.php",
            data: {
                upload_name: upload_name,
                imgDatd: imgDatd,
                imgText: imgText,
                imgUrl: imgUrl,
                img_code: img_code,
                encryptionSwitch: encryptionSwitch,
                PasswordSee: PasswordSee
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1) {
                    toastr["success"]("新增相册成功！", "LG_NewUi");
                    $('#ImgUpdaPost').text('新增中...');
                    $("#ImgUpdaPost").attr("disabled", "disabled");
                    setInterval("window.location.href='loveImgSet.php'", 1000);
                } else if (res == 0) {
                    toastr["error"]("新增相册失败！", "LG_NewUi");
                } else if (res == 2) {
                    toastr["error"]("新增相册失败 缺少参数！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })
    $("#listaddPost").click(function () {
        var eventname = $("input[name='eventname']").val();
        var icon = $("input[name='icon']").val();
        var img = $("textarea[name='img']").val();
        if (!eventname) {
            toastr["warning"]("请检查事件标题是否为空</br>请重试！", "LG_NewUi");
            return false
        }

        $.ajax({
            url: "listaddPost.php",
            data: {
                eventname: eventname,
                icon: icon,
                img: img,
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1) {
                    toastr["success"]("新增事件成功！", "LG_NewUi");
                    $('#listaddPost').text('新增中...');
                    $("#listaddPost").attr("disabled", "disabled");
                    setInterval("window.location.href='lovelist.php'", 1000);
                } else if (res == 0) {
                    toastr["error"]("新增事件失败！", "LG_NewUi");
                } else {
                    toastr["error"](res, "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })
    $("#ipAddPost").click(function () {
        var ipdz = $("input[name='ipdz']").val();
        var bz = $("input[name='bz']").val();

        if (!(ipdz && bz)) {
            toastr["warning"]("请检查IP地址/信息备注是否为空</br>请重试！", "LG_NewUi");
            return false
        }
        $.ajax({
            url: "ipAddPost.php",
            data: {
                ipdz: ipdz,
                bz: bz,
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1) {
                    toastr["success"]("IP封禁成功！", "LG_NewUi");
                    $('#ipAddPost').text('提交中...');
                    $("#ipAddPost").attr("disabled", "disabled");
                    setInterval("window.location.href='ipList.php'", 1000);
                } else if (res == 0) {
                    toastr["error"]("IP封禁失败！", "LG_NewUi");
                } else if (res == 9) {
                    toastr["warning"]("IP地址已存在 请更换后重试！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })
    $("#listupda").click(function () {
        var eventname = $("input[name='eventname']").val();
        var icon = $("input[name='icon']").val();
        var imgurl = $("textarea[name='imgurl']").val();
        var id = $("input[name='id']").val();

        if (!eventname) {
            toastr["warning"]("请检查事件名称是否为空</br> 请重试！", "LG_NewUi");
            return false
        }

        $.ajax({
            url: "listupda.php",
            data: {
                eventname: eventname,
                icon: icon,
                imgurl: imgurl,
                id: id,
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1) {
                    toastr["success"]("修改事件成功！", "LG_NewUi");
                    $('#listupda').text('修改中...');
                    $("#listupda").attr("disabled", "disabled");
                    setInterval("window.location.href='lovelist.php'", 1000);
                } else if (res == 0) {
                    toastr["error"]("修改事件失败！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })

    $("#Photoup_Post").click(function () {
        let photoDatd = $("input[name='photoDatd']").val();
        let by_name = $("select[name='by_name']").val();
        let photoType = $("select[name='photo_type']").val();
        let VideoCover = $("input[name='VideoCover']").val();
        let photoText = $("input[name='photoText']").val();
        let photoUrl = $("input[name='photoUrl']").val();
        let id = $("input[name='id']").val();
        $.ajax({
            url: "Photoup_Post.php",
            data: {
                photoDatd: photoDatd,
                by_name: by_name,
                photoText: photoText,
                photoType: photoType,
                id: id,
                photoUrl: photoUrl,
                VideoCover: VideoCover,
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1) {
                    toastr["success"]("修改相册信息成功！", "LG_NewUi");
                    $('#Photoup_Post').text('修改中...');
                    $("#Photoup_Post").attr("disabled", "disabled");
                    let interval = setInterval(function () {
                        location.reload();
                        clearInterval(interval);
                    }, 1000);
                } else if (res == 0) {
                    toastr["error"]("修改相册信息失败！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })

    $("#imgdiy_Post").click(function () {
        let diy_load = $("input[name='diy_load']").val();
        let aiv_name_show = $("input[name='aiv_name_show']").val();

        let RandomSort = $("input[name='RandomSort']").val();
        let pcPhotoCoverHeight = $("input[name='pcPhotoCoverHeight']").val();
        let mobilePhotoCoverHeight = $("input[name='mobilePhotoCoverHeight']").val();
        let pcImgMaxHeight = $("input[name='pcImgMaxHeight']").val();
        let mobileImgMaxHeight = $("input[name='mobileImgMaxHeight']").val();



        if (!diy_load) {
            toastr["warning"]("Loading加载图URL不可为空</br>请重试！", "LG_NewUi");
            return false
        }

        $.ajax({
            url: "imgdiy_Post.php",
            data: {
                diy_load,
                aiv_name_show,
                RandomSort,
                pcPhotoCoverHeight,
                mobilePhotoCoverHeight,
                pcImgMaxHeight,
                mobileImgMaxHeight,
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1) {
                    toastr["success"]("相册配置修改成功！", "LG_NewUi");
                    $('#imgdiy_Post').text('修改中...');
                    $("#imgdiy_Post").attr("disabled", "disabled");
                    setInterval("window.location.href='Img_set.php'", 1000);
                } else if (res == 0) {
                    toastr["error"]("修改相册配置失败！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })

    $("#ImgListPost").click(function () {
        let id = $("input[name='id']").val();
        let imglistDatd = $("input[name='imglistDatd']").val();
        let by_name = $("select[name='by_name']").val();
        let imglist_text = $("input[name='imglist_text']").val();
        let img_code = $("input[name='img_code']").val();
        let imglistUrl = $("textarea[name='imglistUrl']").val();
        let VideoCover = $("input[name='VideoCover']").val();
        let photo_type = $("select[name='photo_type']").val();

        if (!(imglist_text && imglistUrl)) {
            toastr["warning"]("请检查文本描述/URl是否为空</br>请重试！", "LG_NewUi");
            return false
        }

        if (photo_type == 1) {
            if (!VideoCover) {
                toastr["warning"]("视频封面URL地址为空</br>请先获取封面URL地址！", "LG_NewUi");
                return false
            }
        }


        $.ajax({
            url: "Addimglist_Post.php",
            data: {
                id: id,
                imglistDatd: imglistDatd,
                by_name: by_name,
                img_code: img_code,
                imglist_text: imglist_text,
                imglistUrl: imglistUrl,
                photo_type: photo_type,
                VideoCover: VideoCover,
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res) {
                    toastr["success"](res, "LG_NewUi");
                    $("#ImgListPost").attr("disabled", "disabled");
                    let interval = setInterval(function () {
                        location.reload();
                        clearInterval(interval);
                    }, 1000);
                } else if (res == 0) {
                    toastr["error"]("新增相册图片失败！", "LG_NewUi");
                } else if (res == 99) {
                    toastr["error"]("缺少参数 请填写！", "LG_NewUi");
                } else {
                    toastr["error"]("未知错误！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })


    $("#dayAddPost").click(function () {
        let day_date = $("input[name='day']").val();
        let day_type = $("select[name='day_type']").val();
        let day_title = $("input[name='day_title']").val();
        let day_Weights = $("input[name='day_Weights']").val();
        let PIWeights = parseInt(day_Weights)
        let isDateHidden = $("input[name='isDateHidden']").val();

        if (!day_title) {
            toastr["warning"]("新增恋爱日期 标题必须填写</br>请重试！", "LG_NewUi");
            return false
        }
        if (PIWeights) {
            if (!(typeof PIWeights === 'number' && !isNaN(PIWeights))) {
                toastr["warning"]("恋爱日期排序设置</br>必须为纯数字 当前内容不合法！", "LG_NewUi");
                return false
            }
        }
        $.ajax({
            url: "Addloveday_Post.php",
            data: {
                day_date: day_date,
                day_type: day_type,
                day_title: day_title,
                day_Weights: day_Weights,
                isDateHidden: isDateHidden
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1) {
                    toastr["success"]("新增恋爱日期成功！", "LG_NewUi");
                    $("#dayAddPost").attr("disabled", "disabled");
                    setInterval("location.href= 'list_day.php'", 1000);
                } else if (res == 0) {
                    toastr["error"]("新增恋爱日期失败！", "LG_NewUi");
                } else if (res == 99) {
                    toastr["error"]("缺少参数 请填写！", "LG_NewUi");
                } else {
                    toastr["error"]("未知错误！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })

    $("#upDayPost").click(function () {
        let day_date = $("input[name='day']").val();
        let day_type = $("select[name='day_type']").val();
        let day_title = $("input[name='day_title']").val();
        let day_Weights = $("input[name='day_Weights']").val();
        let isDateHidden = $("input[name='isDateHidden']").val();


        var id = $("input[name='id']").val();
        let PIWeights = parseInt(day_Weights)
        if (!day_title) {
            toastr["warning"]("新增恋爱日期 标题必须填写</br>请重试！", "LG_NewUi");
            return false
        }
        if (PIWeights) {
            if (!(typeof PIWeights === 'number' && !isNaN(PIWeights))) {
                toastr["warning"]("恋爱日期排序设置</br>必须为纯数字 当前内容不合法！", "LG_NewUi");
                return false
            }
        }

        $.ajax({
            url: "up_dayPost.php",
            data: {
                day_date: day_date,
                day_type: day_type,
                day_title: day_title,
                id: id,
                day_Weights: day_Weights,
                isDateHidden: isDateHidden,
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1) {
                    toastr["success"]("修改恋爱日期成功！", "LG_NewUi");
                    $("#ImgListPost").attr("disabled", "disabled");
                    setInterval("location.href= 'list_day.php'", 1000);
                } else if (res == 0) {
                    toastr["error"]("修改恋爱日期失败！", "LG_NewUi");
                } else if (res == 99) {
                    toastr["error"]("缺少参数 请填写！", "LG_NewUi");
                } else {
                    toastr["error"]("未知错误！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })


    $("#ImgSetInfo").click(function () {
        let cloudStorageSwitch = $("input[name='cloudStorageSwitch']").val();
        let UploadDirectory = $("input[name='UploadDirectory']").val();
        let TextWateSwitch = $("input[name='TextWateSwitch']").val();
        let ImgText = $("input[name='ImgText']").val();
        let OpacityValue = $("input[name='OpacityValue']").val();
        let colorPicker = $("input[name='colorPicker']").val();
        let PhotoQuality = $("input[name='PhotoQuality']").val();

        if (!ImgText) {
            toastr["warning"]("请先填写文本水印内容！", "LG_NewUi");
            return false
        }
        $.ajax({
            url: "ImgProcessing.php",
            data: {
                cloudStorageSwitch,
                UploadDirectory,
                TextWateSwitch,
                ImgText,
                OpacityValue,
                colorPicker,
                PhotoQuality
            },
            type: "POST",
            dataType: "json",
            success: function (res) {
                if (res.success) {
                    toastr["success"](res.info, "LG_NewUi");
                    $("#ImgSetInfo").attr("disabled", "disabled");
                    let interval = setInterval(function () {
                        location.reload();
                        clearInterval(interval);
                    }, 1000);
                } else {
                    toastr["error"](res.info, "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })


    function OnBlockIP() {
        let ip = $(".pop_up_layer_content #UserIPText").text();
        let Ipreason = $("input[name='Ipreason']").val();

        if (!Ipreason) {
            toastr["error"]("请先输入封禁理由！", "LG_NewUi");
            return false
        }
        $('#BlockIP').attr("disabled", "disabled");
        $.ajax({
            url: "ipAddPost.php",
            data: {
                ipdz: ip,
                bz: Ipreason
            },
            type: "POST",
            dataType: "json",
            success: function (res) {
                console.log(res);
                if (res == 1) {
                    toastr["success"]("封禁成功！", "LG_NewUi");
                } else if (res == 0) {
                    toastr["error"]("封禁失败 请稍后重试！", "LG_NewUi");
                } else if (res == 9) {
                    toastr["error"]("IP已封禁 禁止重复封禁！", "LG_NewUi");
                }
            },
            error: function (err) {
                console.log(err);
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            },
            complete: function () {
                let interval = setInterval(function () {
                    location.reload();
                    clearInterval(interval);
                }, 1000);
            }
        })
    }


    function StorageUpdate() {
        let currentCloudId = $("input[name='currentCloudId']").val();
        let AccessKey = $("input[name='AccessKey']").val();
        let SecretKey = $("input[name='SecretKey']").val();
        let Bucket = $("input[name='Bucket']").val();
        let Endpoint = $("input[name='Endpoint']").val();
        let customCDN = $("input[name='customCDN']").val();
        let Region = $("input[name='Region']").val();

        if (AccessKey == '' || SecretKey == '' || Bucket == '') {
            toastr["warning"]("请输入对象存储基本信息！", "LG_NewUi");
            return false
        }

        $('#UpCloudStorage').attr("disabled", true);

        $.ajax({
            url: "UpCloudStorage.php",
            data: {
                currentCloudId,
                AccessKey,
                SecretKey,
                Bucket,
                Endpoint,
                customCDN,
                Region
            },
            type: "POST",
            dataType: "json",
            success: function (res) {
                // console.log(res);
                if (res) {
                    if (res.States) {
                        toastr["success"](res.info, "LG_NewUi");
                    } else {
                        toastr["error"](res.info, "LG_NewUi");
                        $('#UpCloudStorage').attr("disabled", false);
                    }
                }
            },
            error: function (err) {
                console.log(err);
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            },
            complete: function () {
                let interval = setInterval(function () {
                    location.reload();
                    clearInterval(interval);
                }, 1000);
            }
        })
    }


    $("#aboutPost").click(function () {
        var title = $("input[name='title']").val();
        var aboutimg = $("input[name='aboutimg']").val();
        var info1 = $("input[name='info1']").val();
        var info2 = $("input[name='info2']").val();
        var info3 = $("input[name='info3']").val();
        var btn1 = $("input[name='btn1']").val();
        var btn2 = $("input[name='btn2']").val();
        var infox1 = $("input[name='infox1']").val();
        var infox2 = $("input[name='infox2']").val();
        var infox3 = $("input[name='infox3']").val();
        var infox4 = $("input[name='infox4']").val();
        var infox5 = $("input[name='infox5']").val();
        var infox6 = $("input[name='infox6']").val();
        var btnx2 = $("input[name='btnx2']").val();
        var infof1 = $("input[name='infof1']").val();
        var infof2 = $("input[name='infof2']").val();
        var infof3 = $("input[name='infof3']").val();
        var infof4 = $("input[name='infof4']").val();
        var btnf3 = $("input[name='btnf3']").val();
        var infod1 = $("input[name='infod1']").val();
        var infod2 = $("input[name='infod2']").val();
        var infod3 = $("input[name='infod3']").val();
        var infod4 = $("input[name='infod4']").val();
        var infod5 = $("input[name='infod5']").val();

        $.ajax({
            url: "aboutPost.php",
            data: {
                title: title,
                aboutimg: aboutimg,
                info1: info1,
                info2: info2,
                info3: info3,
                btn1: btn1,
                btn2: btn2,
                infox1: infox1,
                infox2: infox2,
                infox3: infox3,
                infox4: infox4,
                infox5: infox5,
                infox6: infox6,
                btnx2: btnx2,
                infof1: infof1,
                infof2: infof2,
                infof3: infof3,
                infof4: infof4,
                btnf3: btnf3,
                infod1: infod1,
                infod2: infod2,
                infod3: infod3,
                infod4: infod4,
                infod5: infod5,
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1) {
                    toastr["success"]("修改对话配置成功！", "LG_NewUi");
                } else if (res == 0) {
                    toastr["error"]("修改对话配置失败！", "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })


    $("#upMsgPost").click(function () {
        let MsgText = $("textarea[name='MsgText']").val();
        let MsgName = $("input[name='MsgName']").val();
        let MsgQQ = $("input[name='MsgQQ']").val();
        let MsgTime = $("input[name='MsgTime']").val();
        let MsgIP = $("input[name='MsgIP']").val();
        let MsgCity = $("input[name='MsgCity']").val();
        let Os_info = $("select[name='Os_info']").val();
        let Br_info = $("select[name='Br_info']").val();
        let id = $("input[name='id']").val();

        if (!MsgText || !MsgName || !MsgTime) {
            toastr["warning"]("请检查表单输入框是否为空</br> 请重试！", SiteTitle);
            return false
        }

        $.ajax({
            url: "editMessagePost.php",
            data: {
                id,
                MsgText,
                MsgName,
                MsgQQ,
                MsgTime,
                MsgIP,
                MsgCity,
                Os_info,
                Br_info
            },
            type: "POST",
            dataType: "text",
            success: function (res) {
                if (res == 1) {
                    toastr["success"]("修改游客留言成功！", SiteTitle);
                    $(this).text('修改中...');
                    $(this).attr("disabled", "disabled");
                    setTimeout(function () {
                        window.location.href = 'leavSet.php';
                    }, 1000);
                } else if (res == 0) {
                    toastr["error"]("修改游客留言失败！", SiteTitle);
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })


    $("#wxAppPost").click(function () {
        let audit_mode = $("input[name='audit_mode']").val();
        let template_id = $("input[name='TabTemplate']").val();
        let test_cover = $("input[name='test_cover']").val();
        let sample_title = $("input[name='sample_title']").val();
        let sample_description = $("textarea[name='sample_description']").val();
        let swiperHeight = $("input[name='swiperHeight']").val();
        let share_title = $("input[name='share_title']").val();
        let share_cover = $("input[name='share_cover']").val();
        let custom_font_url = $("input[name='custom_font_url']").val();


        if (template_id == 1) {
            if (!test_cover || !sample_title || !sample_description) {
                toastr["warning"]("当前审核模板为：1</br> 请先完善模板配置！", "LG_NewUi");
                return false
            }
        }

        $.ajax({
            url: "wx_config_post.php",
            data: {
                audit_mode,
                template_id,
                test_cover,
                sample_title,
                sample_description,
                swiperHeight,
                share_title,
                share_cover,
                custom_font_url,
            },
            type: "POST",
            dataType: "json",
            success: function (res) {
                if (res.Status) {
                    // 配置修改成功后自动更新API数据 这里觉得太慢 所以注释掉了
                    // fetch('../api/LGNewUiApiV3.php', {
                    //     method: 'GET',
                    //     headers: {
                    //         'Content-Type': 'application/json'
                    //     }
                    // })
                    toastr["success"](res.message, "LG_NewUi");
                    $(this).text('修改中...');
                    $(this).attr("disabled", "disabled");
                    let interval = setInterval(function () {
                        location.reload();
                        clearInterval(interval);
                    }, 1000);
                } else {
                    toastr["error"](res.message, "LG_NewUi");
                }
            },
            error: function (err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            }
        })
    })


    $(function () {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "rtl": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": 300,
            "hideDuration": 1000,
            "timeOut": 5000,
            "extendedTimeOut": 1000,
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        // 本地图片上传按钮开关
        $(".ImageUploadDiy_Btn").click(function () {
            $(".ImageUploadDiy_warp").slideDown()
        })

        $("#ImageUploadDiy_close").click(function () {
            $(".ImageUploadDiy_warp").fadeOut()
        })

    })
</script>