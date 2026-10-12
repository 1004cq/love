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

$(document).ready(function () {

    window.getMusic = function () {
        const neteaseAudios = document.querySelectorAll('audio[data-type]');
    
        neteaseAudios.forEach(audioElement => {
            const id = audioElement.getAttribute('data-id');
            const dataType = audioElement.getAttribute('data-type');
            const amUrl = audioElement.getAttribute('data-url');
            const dataMusicUrl = audioElement.getAttribute('data-url') || null;
            const MusicCover = audioElement.getAttribute('data-cover') || '';
            const MusicAuthor = audioElement.getAttribute('data-author') || '';
            const MusicName = audioElement.getAttribute('data-name') || '';
    
            if (id && dataType) {
                fetch(`../../music.php?type=song&media=${dataType}&id=${id}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        const dataurl = amUrl || data.url;
    
                        const musicStyle = `
                            <div class="mian_music" style="background: url('${data.cover || MusicCover}') no-repeat center">
                                <div class="music_card" data-url="${data.cover || MusicCover}" view-image>
                                    <img class="cover" src="${data.cover || MusicCover}">
                                    <div class="author">
                                        <span class="music_name">${data.name || MusicName}</span>
                                        <span class="user">${data.author || MusicAuthor}</span>
                                    </div>
                                    <div class="music_play" data-music="${dataurl}"></div>
                                </div>
                            </div>
                        `;
    
                        $(musicStyle).insertBefore(audioElement);
                        audioElement.src = dataurl;
                        afterAjax();
                    })
                    .catch(error => {
                        console.error(`Failed to get music URL for ${id}: ${error.message}`);
                    });
            } else {
                const dataurl = dataMusicUrl;
    
                const musicStyle = `
                    <div class="mian_music" style="background: url('${MusicCover}') no-repeat center">
                        <div class="music_card" data-url="${MusicCover}" view-image>
                            <img class="cover" src="${MusicCover}">
                            <div class="author">
                                <span class="music_name">${MusicName}</span>
                                <span class="user">${MusicAuthor}</span>
                            </div>
                            <div class="music_play" data-music="${dataurl}"></div>
                        </div>
                    </div>
                `;
    
                $(musicStyle).insertBefore(audioElement);
                audioElement.src = dataurl;
            }
        });
    };
    getMusic();

    var audio = $('#music')[0];
    var currentUrl = ''; // 当前正在播放的音乐 URL
    var currentTime = 0; // 上一次暂停的位置
    var play_svg = "Style/img/pause.svg";
    var pause_svg = "Style/img/play.svg";
    var play_svg2 = "Style/img/pause2.svg";
    var pause_svg2 = "Style/img/play2.svg";

    
    // 为播放按钮添加点击事件监听器
    $(".music_info_btn_play").click(function () {
        var musicUrl = $('.music_info_btn_play').attr('data-music');
        if (currentUrl !== musicUrl) {
            audio.pause();
            audio.currentTime = 0;
            currentUrl = musicUrl;
        }
        if (currentTime > 0 && audio.src === musicUrl) {
            audio.currentTime = currentTime;
        }

        // 将音乐的 URL 设置为 <audio> 元素的 src 属性
        if (audio.src !== musicUrl) {
            audio.src = musicUrl;
        }


        // 如果音乐已暂停，则调用 play() 方法播放音乐；否则调用 pause() 方法暂停音乐
        if (audio.paused) {
            audio.play().catch(error => {
                console.error("Error while playing audio:", error);
            });
            $(".music_info_btn_play").css('background-image', 'url(' + play_svg2 + ')');
        } else {
            audio.pause();
            $(".music_info_btn_play").css('background-image', 'url(' + pause_svg2 + ')');
        }

    })

    function afterAjax() {
        setTimeout(function () {
            $('.music_play').css('background-image', 'url(' + pause_svg + ')');

            $('.music_play').click(function () {
                $(".music_info").show();

                // console.log("本页共" + $('.music_play').length + "首音乐")

                var musicUrl = $(this).attr('data-music'); // 获取音乐的 URL

                $('.music_info_btn_play').attr('data-music', musicUrl);
                var covUrl = $(this).parent().attr('data-url');
                $('.music_info').css('background-image', 'url(' + covUrl + ')');

                // 如果当前正在播放其他音乐，则先暂停当前音乐的播放

                if (currentUrl !== musicUrl) {
                    audio.pause();
                    audio.currentTime = 0;
                    currentTime = 0;
                    currentUrl = musicUrl;
                }

                // 如果音乐已经播放过，将音乐播放位置设置为上一次暂停的位置
                if (currentTime > 0 && audio.src === musicUrl) {
                    audio.currentTime = currentTime;
                }

                // 将音乐的 URL 设置为 <audio> 元素的 src 属性
                if (audio.src !== musicUrl) {
                    audio.src = musicUrl;
                }

                // 如果音乐已暂停，则调用 play() 方法播放音乐；否则调用 pause() 方法暂停音乐
                if (audio.paused) {
                    setTimeout(() => {
                        audio.play().catch(error => {
                            console.error("Error while playing audio:", error);
                        });
                    }, 10);
                    $('.music_play').css('background-image', 'url(' + pause_svg + ')');
                    $(".music_info_btn_play").css('background-image', 'url(' + play_svg2 + ')');
                    $(this).css('background-image', 'url(' + play_svg + ')');
                } else {
                    audio.pause();
                    $(".music_info_btn_play").css('background-image', 'url(' + pause_svg2 + ')');
                    $(this).css('background-image', 'url(' + pause_svg + ')');
                }

                console.log(audio.paused);

            });

            // 监听音乐的暂停事件，保存当前播放位置
            audio.addEventListener('timeupdate', function () {
                if (!audio.paused) {
                    currentTime = audio.currentTime;
                }
            });

            // 监听音乐播放完毕事件
            audio.addEventListener('ended', function () {
                // 在这里执行音乐播放完毕后需要执行的代码
                console.log('音乐播放完毕！');
                $(".music_info_btn_play").css('background-image', 'url(' + pause_svg2 + ')');
                $('.music_play').css('background-image', 'url(' + pause_svg + ')');
                audio.currentTime = 0; // 将当前播放时间设置为0
                audio.pause(); // 暂停音乐
                audio.src = ""; // 将音频的src属性设置为空
                audio.load(); // 重新加载音频
                currentTime = 0; // 重置当前播放时间为 0
            });
        }, 1000);

        $('.music_info_btn_close').click(function () {
            $(".music_info").slideUp();
            $('.music_play').css('background-image', 'url(' + pause_svg + ')');
            audio.currentTime = 0; // 将当前播放时间设置为0
            audio.pause(); // 暂停音乐
            audio.src = ""; // 将音频的src属性设置为空
            audio.load(); // 重新加载音频
            currentTime = 0; // 重置当前播放时间为 0
        });
    }

    var musicInfo = document.querySelector(".music_info");
    var isDragging = false;
    var touchX = 0;
    var touchY = 0;

    musicInfo.addEventListener("touchstart", function (event) {
        isDragging = true;
        touchX = event.touches[0].clientX - musicInfo.offsetLeft;
        touchY = event.touches[0].clientY - musicInfo.offsetTop;
        musicInfo.classList.add("music_info_dragging");

    });


    document.addEventListener("touchmove", function (event) {
        if (isDragging) {
            var x = event.touches[0].clientX - touchX;
            var y = event.touches[0].clientY - touchY;
            var maxX = window.innerWidth - musicInfo.offsetWidth;
            var maxY = window.innerHeight - musicInfo.offsetHeight;
            x = Math.max(0, Math.min(x, maxX));
            y = Math.max(0, Math.min(y, maxY));
            musicInfo.style.left = x + "px";
            musicInfo.style.top = y + "px";
        }
    });


    document.addEventListener("touchend", function (event) {
        isDragging = false;
        musicInfo.classList.remove("music_info_dragging");

    });

    musicInfo.addEventListener("mousedown", function (event) {
        isDragging = true;
        mouseX = event.clientX - musicInfo.offsetLeft;
        mouseY = event.clientY - musicInfo.offsetTop;
        musicInfo.classList.add("music_info_dragging");

    });

    document.addEventListener("mousemove", function (event) {
        if (isDragging) {
            var x = event.clientX - mouseX;
            var y = event.clientY - mouseY;
            var maxX = window.innerWidth - musicInfo.offsetWidth;
            var maxY = window.innerHeight - musicInfo.offsetHeight;
            x = Math.max(0, Math.min(x, maxX));
            y = Math.max(0, Math.min(y, maxY));
            musicInfo.style.left = x + "px";
            musicInfo.style.top = y + "px";
        }
    });

    document.addEventListener("mouseup", function (event) {
        isDragging = false;
        musicInfo.classList.remove("music_info_dragging");
    });

});