/*!
 * Project: LG_NewUi
 * Author: Ki
 * Last Updated: 2024-10-18
 * Description: 小手一牵 岁岁年年
 * 
 * Copyright (c) 2022-2024 by Ki. All Rights Reserved.
 * 
 * License: This software is licensed for both personal and commercial use under specific terms.
 * Redistribution, modification, or resale without explicit written permission is strictly prohibited.
 * Unauthorized use, copying, or redistribution will result in immediate termination of support and updates.
 * 
 * Documentation and License: https://lguiy.kikiw.cn/wiki
 * 
 * * Warning: This is an original work, and all copyright belongs to LG_NewUi.
 * Any attempt to scrape, copy, sell, or redistribute this software without permission is strictly forbidden.
 * Violators will face immediate legal action, blacklisting, and permanent cessation of future updates.
 * 
 * Development requires substantial time and effort. We appreciate your respect for the work involved and your support.
 */

let lazyLoadInstance = new LazyLoad({
    threshold: 0,
    elements_selector: ".Item_OwO img,img.lazy"
});


function insertEmoticon(tag) {
    var commentBox = document.getElementById('wenben');
    var startPos = commentBox.selectionStart;
    var endPos = commentBox.selectionEnd;
    var oldValue = commentBox.value;

    commentBox.value = oldValue.substring(0, startPos) + tag + oldValue.substring(endPos, oldValue.length);

    commentBox.selectionStart = commentBox.selectionEnd = startPos + tag.length;

    commentBox.focus();
}


function GetEm() {
    $.ajax({
        url: "/OwO/emoji.json",
        type: "GET",
        dataType: "json",
        success: function (res) {
            // console.log(res);

            const tabMenu = $(".OwO_Tab_Menu");
            const tabContents = $(".OwO_Tab_Contents");

            tabMenu.empty();
            tabContents.empty();

            let keys = Object.keys(res).reverse();

            keys.forEach((key, index) => {

                tabMenu.append(`
    <li class="${index === 0 ? 'active' : ''}" data-tab="OwO_${index + 1}">${key}</li>
`);

                tabContents.append(`
    <div id="OwO_${index + 1}" class="OwO_Content ${index === 0 ? 'active' : ''}">
        <ul class="OwO_items"></ul>
    </div>
`);

                let tabData = res[key].container;

                tabData.forEach(element => {
                    $(`#OwO_${index + 1} ul`).append(`
        <li class="Item_OwO" title="${element.text}" onclick="insertEmoticon('${element.data}')">
            <img class="lazy" data-src="../OwO/images/${element.icon}">
        </li>
    `);
                });
            });

            tabMenu.on("click", "li", function () {
                let tabId = $(this).data("tab");

                $(this).addClass("active").siblings().removeClass("active");
                $("#" + tabId).addClass("active").siblings().removeClass("active");
            });

            lazyLoadInstance.update();
        },
        error: function (err) {
            console.error("无法加载表情包数据:", err);
        }
    })
}

function Charu(data) {
    $("#wenben").text(data)
}

GetEm();
