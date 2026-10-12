<?php
session_start();

include_once "Database.php";
include_once 'Function.php';
if (!userHasPermission($_SESSION['loginadmin'], $conn)) {
    die("<script>alert('参数错误 请先登陆');location.href = 'login.php'</script>");
}
?>
<link href="/admin/assets/videojs/video-js.min.css" rel="stylesheet" />


<div class="media-tab-container">
    <div class="media-tab media-tab-active" id="media-upload">
        <span><i class="ri-folder-upload-line mr-1"></i>媒体上传</span>
    </div>
    <div class="media-tab" id="media-select">
        <span><i class="ri-image-add-fill mr-1"></i>选择媒体</span>
    </div>
</div>

<div class="media-content-container">
    <div class="media-content media-content-active" id="media-upload-content">
        <form id="upload-form" enctype="multipart/form-data">
            <input type="file" name="image[]" id="file-input" accept="image/*,video/*" multiple>
            <h2>请选择需上传的图片/视频 支持拖拽</h2>
            <?php echo "当前PHP上传大小限制：" . ini_get('upload_max_filesize'); ?>

            <div class="skipStyle">
                <div class="skipStyleBor mr-3">
                    <label>跳过本次压缩</label>
                    <input type="checkbox" name="skipCompression" id="skipCompression" data-switch="success" value="0">
                    <label style="display:block;" for="skipCompression" data-on-label="Yes" data-off-label="No"></label>
                </div>
                <div class="skipStyleBor">
                    <label>跳过本次水印</label>
                    <input type="checkbox" name="skipWatermark" id="skipWatermark" data-switch="success" value="0">
                    <label style="display:block;" for="skipWatermark" data-on-label="Yes" data-off-label="No"></label>
                </div>
            </div>

            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <div id="image-preview">
                <div class="UpBtnCont">
                    <i class="UpBtn text-muted dripicons-cloud-upload"></i>
                </div>
            </div>
            <div id="video-preview">
                <div class="VideoCover">
                    <img id="thumbnail" src="../Style/img/VideoCoverloading.gif" alt="Video Thumbnail" class="preview">
                    <button type="button" id="UpVideoCover">获取封面URL地址</button>
                </div>
                <div class="VideoCont">
                    <button type="button" id="getVideoCover">截取当前封面</button>
                    <video id="videoPlayer" class="video-js" controls preload="auto"></video>
                </div>
            </div>
            <div class="img_info_s">
                <span class="file_name btn btn-outline-primary btn-rounded">媒体上传数量</span>
            </div>
            <div class="FunctionButtons">
                <button id="clearMedia" class="funBtn btn btn-danger" type="button" disabled>清空</button>
                <button id="sub_file" class="funBtn btn btn-success" type="submit" disabled>上传媒体</button>
            </div>
        </form>

    </div>
    <div class="media-content" id="media-select-content">
        <div class="row" id="image-gallery"></div>

        <div class="text-center mt-3">
            <button id="load-more-btn" class="btn btn-sm btn-outline-primary btn-rounded"><i
                    class="ri-loader-line mr-1"></i></i>加载更多</button>
        </div>
    </div>
</div>



<div id="loading-overlay">
    <div class="loadingItem">
        <div class="progress-text">
            <span class="progress-text-style"></span>
        </div>
        <div class="progress w95">
            <div class="progress-bar"></div>
        </div>
        <div class="loader-warp">
            <div class="loader"></div>
        </div>

        <button class="btn btn-outline-primary btn-rounded" id="loadingText" type="button" disabled="">
            文件正在上传/处理中 请稍等
        </button>
    </div>
</div>


<style>
    /* .loader {
        width: 48px;
        height: 48px;
        display: inline-block;
        border: 4px solid #fafafa;
        border-bottom-color: #ff7070;
        border-radius: 50%;
        box-sizing: border-box;
        animation: rotation-1 1s linear infinite;
    } */

    @keyframes rotation-1 {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .loader-warp {
        margin: 50px auto;
    }

    .loader {
        width: 50px;
        aspect-ratio: 1;
        border-radius: 50%;
        background: #000;
        box-shadow: 0 0 0 0 #0004;
        animation: l2 1.5s infinite linear;
        position: relative;
    }

    .loader:before,
    .loader:after {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        box-shadow: 0 0 0 0 #0004;
        animation: inherit;
        animation-delay: -0.5s;
    }

    .loader:after {
        animation-delay: -1s;
    }

    @keyframes l2 {
        100% {
            box-shadow: 0 0 0 40px #0000
        }
    }


    .skipStyle {
        display: flex;
        align-items: center;
        justify-content: center;
        padding-top: 1rem;
        margin: .5rem;
        border-top: 1px solid #d5d5d5;
    }

    .skipStyle .skipStyleBor {
        border: 1px dashed #d8d6d6;
        padding: .5rem;
        border-radius: .5rem;
    }

    .skipStyle .skipStyleBor label {
        margin: 0 auto;
    }

    .media-tab-container {
        display: flex;
        background-color: #fff;
        border-radius: 30px;
        padding: 5px;
        max-width: 380px;
        margin: 20px auto;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .media-tab {
        flex: 1;
        text-align: center;
        padding: 5px 0;
        border-radius: 30px;
        cursor: pointer;
        position: relative;
    }

    .media-tab-active {
        background-color: #eef3ff;
    }

    .media-tab span {
        color: #5f5f5f;
        font-weight: 700;
    }

    .media-tab-active span {
        color: #2563eb;
    }

    .media-content {
        display: none;
    }

    .media-content-active {
        display: block;
    }

    #media-select-content {
        border: 1px solid #d8d8d8b5;
        border-radius: .5rem;
        padding: 1rem 0;
    }

    #image-gallery {
        justify-content: center;
    }

    .mediaList {
        width: 130px;
        margin: .5rem;
        height: 130px;
        overflow: hidden;
        border-radius: .3rem;
    }

    .mediaList .btn.btn-diy {
        color: #fff;
        background: #00000078;
        padding: 0px 8px;
        border-radius: 0 .3rem;
        transition: .2s all;
        box-shadow: rgb(255 255 255 / 48%) 0px 3px 29px 0px;
    }

    .mediaList .btn.btn-diy:hover {
        background: #828282f0;
    }

    .mediaList .LeftCopy {
        right: 0;
        left: auto;
        top: -1px;
    }

    .mediaList .imgSize {
        background: #ececec;
    }

    .mediaList .imgSize img {
        background: #f5f5f5;
    }

    .loadingItem {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .loadingItem .loadingImg {
        width: 100px;
        height: 100px;
        background: url("../Style/img/loadingImgB.gif")no-repeat center;
        background-size: cover;
        margin-bottom: 1rem;
    }

    .progress.w95 {
        width: 95%;
    }

    .progress.w95 .progress-bar {
        background-color: #5ca7ff;
    }

    .video-js .vjs-slider,
    .video-js .vjs-load-progress {
        background: #fff;
    }

    .video-js .vjs-control-bar {
        background-color: rgb(255 255 255 / 38%);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    button.vjs-picture-in-picture-control.vjs-control.vjs-button {
        display: none !important;
    }

    #upload-form .UpBtnCont {
        width: 100%;
        display: flex;
        justify-content: center;
        margin: 2rem 0;
    }

    #upload-form .UpBtn.text-muted.dripicons-cloud-upload {
        font-size: 6rem;
        border: 1px dashed #98c7ff;
        padding: 1rem;
        border-radius: 1rem;
        display: flex;
        color: #4298fd !important;
    }


    .video-js .vjs-big-play-button {
        top: auto;
        left: auto;
        right: 10px;
        bottom: 10px;
        background-color: rgb(255 255 255 / 36%);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
    }

    .video-js:hover .vjs-big-play-button,
    .video-js .vjs-big-play-button:focus {
        background-color: rgb(133 133 133 / 50%);
    }


    #video-preview {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        justify-content: center;
        font-family: 'Noto Serif SC', serif;
    }


    #video-preview .infoIconSe,
    #image-preview .infoIconSe {
        position: absolute;
        z-index: 10;
        right: 3px;
        top: 5px;
        background: #ffffff91;
        padding: .3rem;
        border-radius: .5rem;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        background: #f6f6f6e0;
        width: 2.2rem;
        height: 2.2rem;
        border: 1px solid #fff;
    }

    #image-preview .infoIconSe {
        right: 0;
        top: 0;
        width: 1.5rem;
        height: 1.5rem;
        box-shadow: 0 3px 25px #aaabaeb5;
    }


    #video-preview .VideoCont {
        border-radius: 10px;
        box-shadow: 0 12px 12px -9px rgba(0, 0, 0, .29);
        overflow: hidden;
        width: 50%;
        height: 400px;
        position: relative;
    }

    #video-preview .video-js {
        width: 100%;
        height: 100%;
    }

    #video-preview .VideoCover {
        width: 300px;
        height: 500px;
        position: relative;
        margin: 1rem;
        border-radius: 10px;
        box-shadow: 0 5px 20px -8px rgba(0, 0, 0, .29);
        overflow: hidden;
    }

    #video-preview .VideoCover #thumbnail {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #video-preview .VideoCover #UpVideoCover,
    #video-preview .VideoCont #getVideoCover {
        position: absolute;
        left: 50%;
        bottom: 10px;
        transform: translate(-50%);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        background: #a4a4a480;
        border: 1px solid #ffffff;
        box-shadow: 0 12px 12px -9px rgba(0, 0, 0, .29);
        color: #fff;
        border-radius: 20px;
        padding: .2rem 1rem;
        transition: .2s all;
        word-break: keep-all;
        z-index: 10;
    }

    #video-preview .VideoCont #getVideoCover {
        top: 10px !important;
        bottom: auto;
    }

    #video-preview .VideoCover #UpVideoCover:hover,
    #video-preview .VideoCont #getVideoCover:hover {
        background: #e1e1e1b5;
    }


    @media (max-width: 1023px) {

        #video-preview .VideoCover {
            margin: 0;
            margin-bottom: 1rem;
            height: 300px;
        }

        #video-preview .VideoCont {
            width: 100%;
        }
    }

    #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgb(255 255 255 / 86%);
        z-index: 9999;
        display: none;
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
    }

    #loading-overlay .progress-text .progress-text-style {
        font-size: 1.5rem;
        font-family: 'Noto Serif SC', serif;
        font-weight: bold;
        color: #4298fd;
    }

    #image-preview {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        object-fit: cover;
        margin: 0 auto;
    }

    #image-preview .image-item {
        position: relative;
    }

    #image-preview button.ImgCloseBtn {
        position: absolute;
        z-index: 10;
        top: 0;
        right: 0;
        color: #ffffff;
        background: #a3a3a354;
        border: 1px solid #ffffff;
        box-shadow: 0 12px 30px rgb(0 0 0 / 28%);
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 1rem;
        outline: none;
        backdrop-filter: blur(20px);
        transition: .2s all;
    }

    #image-preview button.ImgCloseBtn:hover {
        background: #ff685fba;
    }

    #image-preview img {
        width: 100px;
        height: 100px;
        display: inline-block;
        object-fit: cover;
        margin: 0.5rem;
        border-radius: 0.8rem;
        box-shadow: 0 8px 12px #c9cbcfd6;
    }

    .FunctionButtons {
        margin: 1rem 0;
        position: absolute;
        bottom: 0rem;
        right: 2rem;
    }

    .FunctionButtons .funBtn {
        padding: 0.5rem 2rem;
        border: none;
        outline: none;
        border-radius: 2rem;
        font-family: 'Noto Serif SC', serif;
        font-weight: bold;
    }

    #upload-form button:disabled {
        background: #e8e8e8 !important;
        box-shadow: none;
        color: #7b7676;
    }

    .progress-bar {
        background: #000;
        height: 8px;
        width: 0;
        background-color: #43d07c;
        border: 1px solid;
        transition: width 0.5s ease-in-out;
        border-radius: 1rem;
    }

    .progress {
        background: #f1f1f1;
        padding: 0.3rem 0.4rem;
        border-radius: 1rem;
        margin: 1rem;
    }

    #file-input {
        opacity: 0;
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
    }

    #upload-form {
        padding: 1rem;
        border: 1px dashed #ccc;
        border-radius: 0.5rem;
        text-align: center;
        position: relative;
        box-shadow: 0 5px 18px #eaeaea80;
    }

    #upload-form h2 {
        font-family: 'Noto Serif SC', serif;
        font-weight: 700;
        font-size: 1.5rem;
        letter-spacing: 2px;
    }

    #upload-form .img_info_s {
        margin-top: 2rem;
        margin-bottom: 4rem;
    }

    .text_photo {
        width: 8em;
        overflow: hidden;
        text-overflow: ellipsis
    }

    .row_line_x {
        box-sizing: border-box;
        display: block;
        border: 1px dashed #559fff;
        margin: 3rem auto;
        border-radius: 1rem;
        width: 100%;
    }

    #isVideoCoverInput {
        display: none;
    }
</style>

<script src="/admin/assets/videojs/video.min.js"></script>
<script>
    $(document).ready(function() {
        const videoPlayer = videojs('videoPlayer');
        let files = null;
        let selectedThumbnail = null;
        let isVideoCoverUp = false;
        let isVideoFile = false;
        let isImageFile = false;
        let currentVideoFile = null;

        // let currentPage = 1;
        // let totalPages = 1;

        // 用于存储选中的文件
        let selectedFiles = [];

        let successSvg = `<svg t="1727334939165" class="icon infoIconSe" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1268" width="200" height="200">
                <path d="M511.3 64.2c-247.4 0-448 200.6-448 448s200.6 448 448 448 448-200.6 448-448-200.5-448-448-448z m305.3 367.2L524.7 730.7c-13.1 13.5-30.5 20.2-48 20.2-16.6 0-33.2-6.1-46.2-18.5L226.7 538.3c-26.8-25.5-27.8-67.9-2.3-94.7 25.5-26.8 67.9-27.8 94.7-2.3L475 589.8l245.7-251.9c25.8-26.5 68.3-27 94.7-1.2 26.5 25.8 27 68.3 1.2 94.7z" fill="#3AB483" p-id="1269"></path>
            </svg>`;
        let failSvg = `<svg t="1727334970017" class="icon infoIconSe" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1430" width="200" height="200">
                <path d="M511.3 64.2c-247.4 0-448 200.6-448 448s200.6 448 448 448 448-200.6 448-448-200.5-448-448-448zM733 639.1c26.2 26.2 26.2 68.6 0 94.8-13.1 13.1-30.2 19.6-47.4 19.6s-34.3-6.5-47.4-19.6L511.3 607 384.4 733.9c-13.1 13.1-30.2 19.6-47.4 19.6s-34.3-6.5-47.4-19.6c-26.2-26.2-26.2-68.6 0-94.8l126.9-126.9-126.8-126.9c-26.2-26.2-26.2-68.6 0-94.8 26.2-26.2 68.6-26.2 94.8 0l126.9 126.9 126.9-126.9c26.2-26.2 68.6-26.2 94.8 0 26.2 26.2 26.2 68.6 0 94.8l-127 126.9L733 639.1z" fill="#EC6D65" p-id="1431"></path>
            </svg>`;


        $('.media-tab').on('click', function() {
            let targetContent = '#' + $(this).attr('id') + '-content';

            $('.media-tab').removeClass('media-tab-active');
            $('.media-content').removeClass('media-content-active');

            $(this).addClass('media-tab-active');
            $(targetContent).addClass('media-content-active');

        });

        let loadIcon = `<i class="ri-send-plane-line mr-1"></i>加载更多`;
        let loadIconAm = `<i class="ri-loader-line svg-rotate mr-1"></i>加载中`;

        $('#skipCompression').on('click', function() {
            let skipCompression = $('#skipCompression');
            skipCompression.val(this.checked ? 1 : 0);
        });

        $('#skipWatermark').on('click', function() {
            let skipWatermark = $('#skipWatermark');
            skipWatermark.val(this.checked ? 1 : 0);
        });


        // 初始加载图片
        let currentPage = 1;
        let imagesPerPage = 10;
        let totalImages = 0;
        let imageData = []; // 所有图片数据
        let isCloudValue;

        function loadImagesFromLocalStorage() {
            const gallery = $('#image-gallery');
            let storedImages = JSON.parse(localStorage.getItem('imageData'));

            if (!storedImages || storedImages.length === 0) {
                console.error("No images found in local storage.");
                return;
            }

            totalImages = storedImages.length;

            // 计算每页的起始和结束索引
            const startIndex = (currentPage - 1) * imagesPerPage;
            const endIndex = startIndex + imagesPerPage;
            const currentImages = storedImages.slice(startIndex, endIndex);

            // 循环创建图片元素
            currentImages.forEach(function(file) {
                // console.log(file.Purl);
                let details = {};

                if (isCloudValue) {
                    details.filename = file.Key;
                    details.absolutePath = file.Purl;
                } else {
                    details = getImageDetails(file);
                }

                const imageHtml = `
            <div class="mediaList">
                <div class="card d-block poR">
                    <div class="imgSize">
                        <img class="card-img-top lazy" data-src="${details.absolutePath}" alt="${details.filename}">
                    </div>
                    <div class="LeftCopy">
                        <button id="CopyImgUrl" type="button" class="btn btn-sm btn-diy" data-url="${details.absolutePath}"><i class="ri-file-copy-line"></i></button>
                    </div>
                </div>
            </div>
        `;
                gallery.append(imageHtml);
            });

            // 如果所有页面加载完毕，则隐藏加载更多按钮
            if (endIndex >= totalImages) {
                $('#load-more-btn').hide();
            } else {
                $('#load-more-btn').show();
            }

            lazyLoadInstance.update();
        }

        function loadAllImages() {
            $.ajax({
                url: 'ImageGallery.php',
                method: 'POST',
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    console.log(response.isCloud);

                    if (response.absolutePaths == '') {
                        return
                    }

                    if (response && typeof response.isCloud !== 'undefined') {
                        isCloudValue = response.isCloud;
                    }


                    const images = response.absolutePaths;
                    imageData = images;

                    // 保存数据到 Local Storage
                    localStorage.setItem('imageData', JSON.stringify(imageData));

                    // 加载第一页图片
                    loadImagesFromLocalStorage();
                },
                error: function(error) {
                    console.log('Error loading images:', error);
                }
            });
        }

        function getImageDetails(file) {
            const pathParts = file.split('/');
            const filename = pathParts[pathParts.length - 1];
            return {
                absolutePath: file,
                filename: filename,
            };
        }

        // 加载更多按钮点击事件
        $('#load-more-btn').click(function() {
            currentPage++;
            loadImagesFromLocalStorage();
        });

        // 页面初始化时加载图片
        loadAllImages();




        $('select[name="photo_type"]').on('change', function() {
            let mediaType = $(this).val();
            if (mediaType == 1) {
                $('#isVideoCoverInput').fadeIn(300);
            } else {
                $('#isVideoCoverInput').fadeOut(300);
            }
        });

        $("#video-preview").hide();
        $("#video-preview .VideoCover").hide();



        $('#file-input').on('change', function() {

            let SelectFileLength = Array.from($(this)[0].files);

            if (SelectFileLength.length === 0) {
                $("#loading-overlay").fadeOut(1000);
                $(".img_info_s .file_name").text("请先选择照片上传！");
                return;
            }

            files = SelectFileLength

            // 显示加载中遮罩
            $("#loading-overlay").show();

            files.forEach((file, index) => {

                // 检查文件名是否重复
                const isDuplicate = selectedFiles.some(selectedFile => selectedFile.name === file.name);
                if (isDuplicate) {
                    toastr["warning"]("文件 " + file.name + "已在上传列表中", "LG_NewUi");
                    $("#loading-overlay").fadeOut(1000);
                    return;
                }

                // 判断是否已经选择了视频
                if (isVideoFile && file.type.startsWith('image/')) {
                    toastr["warning"]("已经选择了视频，无法再选择图片上传！", "LG_NewUi");
                    $('#loading-overlay').fadeOut(1000);
                    return false;
                }

                // 判断是否已经选择了图片
                if (isImageFile && file.type.startsWith('video/')) {
                    toastr["warning"]("已经选择了图片，无法再选择视频上传！", "LG_NewUi");
                    $('#loading-overlay').fadeOut(1000);
                    return false;
                }

                // 检查文件允许类型
                if (!(file.type.startsWith('image/') || file.type.startsWith('video/'))) {
                    toastr["warning"]("上传媒体失败 — 只支持图片和视频文件", "LG_NewUi");
                    $("#loading-overlay").fadeOut(1000);
                    return false;
                }

                // 处理视频文件
                if (file.type.startsWith('video/')) {

                    if (currentVideoFile && currentVideoFile.name !== file.name) {
                        toastr["info"]("您已替换视频文件。", "LG_NewUi");
                        $('#thumbnail').attr('src', '../Style/img/VideoCoverloading.gif');
                        $('#UpVideoCover,#getVideoCover').fadeIn(300);
                        $("#video-preview .VideoCover .infoIconSe,#video-preview .VideoCont .infoIconSe").remove()
                        $('.VideoCover').fadeOut(300)
                    }

                    // 更新当前视频文件
                    currentVideoFile = file;

                    if (SelectFileLength > 1) {
                        toastr["warning"]("视频上传仅支持单次一个！", "LG_NewUi");
                        $("#loading-overlay").fadeOut(1000);
                        return false;
                    }

                    isVideoCoverUp = true;
                    isVideoFile = true; // 标记已经选择了视频
                    $('select[name=photo_type]').val('1');
                    $('#sub_file').text("请先上传封面！");
                    $('#clearMedia').attr("disabled", false);
                    $(".img_info_s .file_name").fadeOut(100);
                    $('#isVideoCoverInput').fadeIn(300); // 显示视频封面输入框

                    const TemporaryVideoUrl = URL.createObjectURL(file);
                    videoPlayer.src({
                        type: file.type,
                        src: TemporaryVideoUrl
                    });
                    videoPlayer.on('loadeddata', function() {
                        videoPlayer.currentTime(1); // 跳转到视频1秒处
                    });

                    $('#video-preview').fadeIn(300); // 显示视频预览
                    $('#loading-overlay').fadeOut(1000); // 隐藏加载中遮罩

                } else if (file.type.startsWith('image/')) {
                    isImageFile = true; // 标记已经选择了图片
                    $('select[name=photo_type]').val('0');
                    $('#isVideoCoverInput').fadeOut(300); // 隐藏视频封面输入框
                    $('#sub_file').attr("disabled", false);
                    $('#clearMedia').attr("disabled", false);

                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const imgElement = document.createElement('div');
                        imgElement.className = "image-item";
                        const img = document.createElement('img');
                        img.setAttribute('src', event.target.result);

                        const deleteButton = document.createElement('button');
                        deleteButton.className = 'ImgCloseBtn';
                        deleteButton.innerHTML = '<i class="mdi mdi-close"></i>';

                        // 删除图片列表逻辑
                        deleteButton.onclick = function() {
                            imgElement.remove();
                            const currentCount = selectedFiles.length;
                            selectedFiles.splice(selectedFiles.indexOf(file), 1); // 移除已删除文件
                            $(".img_info_s .file_name").text("上传照片总数：" + (currentCount - 1) + "张");
                        };

                        imgElement.appendChild(img);
                        imgElement.appendChild(deleteButton);
                        $("#image-preview").append(imgElement); // 显示图片预览

                        selectedFiles.push(file); // 添加到已选文件列表
                        $(".img_info_s .file_name").text("上传照片总数：" + selectedFiles.length + "张");
                        $('#loading-overlay').fadeOut(1000); // 隐藏加载中遮罩
                    };
                    reader.readAsDataURL(file);
                }
            });
            $("#image-preview").show();
        });



        $('#clearMedia').on('click', function() {
            // 移除元素
            $('#image-preview .image-item').remove();
            $('#video-preview').fadeOut(300);

            // 初始化变量
            files = null;
            selectedThumbnail = null;
            isVideoCoverUp = false;
            isVideoFile = false;
            isImageFile = false;
            selectedFiles = [];
        });

        $('#getVideoCover').on('click', function() {
            videoPlayer.pause();
            const canvas = document.createElement('canvas');
            canvas.width = videoPlayer.videoWidth();
            canvas.height = videoPlayer.videoHeight();
            const context = canvas.getContext('2d');
            context.drawImage(videoPlayer.el().querySelector('video'), 0, 0);

            // 将 Canvas 转换为 Blob
            canvas.toBlob(function(blob) {
                const uniqueFileName = 'thumbnail_' + Date.now() + '.png';
                $('#thumbnail').attr('src', URL.createObjectURL(blob)).show();
                selectedThumbnail = blob;
                toastr["success"]("视频封面截取成功！", "LG_NewUi");
            }, 'image/png');

            $("#video-preview .VideoCover").fadeIn(300);

        });

        $('#UpVideoCover').on('click', function() {
            $('#upload-form').submit();
        });

        $('#upload-form').on('submit', function(e) {
            e.preventDefault();

            $('.progress-bar').css({
                background: "#43d07c",
                width: '0%'
            });

            var formData = new FormData($(this)[0]);

            formData.append('skipCompression', $('#skipCompression').val());
            formData.append('skipWatermark', $('#skipWatermark').val());

            $('#sub_file').attr("disabled", true);
            $("#loading-overlay").show();

            // 清空原数组
            for (let pair of formData.entries()) {
                if (pair[0] === 'image[]') {
                    formData.delete('image[]');
                }
            }

            if (selectedThumbnail) {
                formData.append('image[]', selectedThumbnail, 'VideoCover.png');
                formData.append('isMediaType', 1);
            } else {
                if (files && isVideoFile) {
                    files.forEach(function(file) {
                        formData.append('image[]', file);
                    });
                }
            }

            // 将选中的文件添加到formData中
            selectedFiles.forEach(function(file) {
                formData.append('image[]', file);
            });


            console.log(formData);

            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1].name || pair[1]);
            }

            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    // 监听上传进度事件
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            var loadedMB = (evt.loaded / (1024 * 1024)).toFixed(2);
                            var totalMB = (evt.total / (1024 * 1024)).toFixed(2);

                            // 更新进度条的宽度和背景颜色
                            $('.progress-bar').css({
                                background: "#43d07c",
                                width: percentComplete * 100 + '%'
                            });

                            // 更新上传进度文本显示
                            $('.progress-text-style').text(loadedMB + "MB / " + totalMB + "MB");
                            $('#loadingText').text('当前上传进度：' + Math.floor(percentComplete * 100) + '%')
                        }
                    }, false);

                    return xhr;
                },
                url: 'upload_img.php',
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    if (response) {
                        if (response.success == true) {
                            if (isVideoCoverUp) {
                                $("#video-preview .VideoCover").append(successSvg);
                                $('#VideoCoverUrl').val(response.data.Coversrc);
                                $('#UpVideoCover,#getVideoCover').hide();
                            } else {
                                $("#image-preview .image-item .ImgCloseBtn").remove();
                                if (isVideoFile) {
                                    $("#video-preview .VideoCont").append(successSvg);
                                } else {
                                    $("#image-preview .image-item").append(successSvg);
                                }
                                $("#src_url").val(response.data.src);
                            }
                            toastr["success"]("上传图片成功 — URL已自动写入输入框", "LG_NewUi");

                            // 如上传成功 则将当前已选择的图片数组清空
                            selectedFiles = [];
                            $('#sub_file').attr("disabled", true);

                        } else if (response.success == false || response.data.info != '') {
                            toastr["error"]("上传图片失败 — " + response.data.info, "LG_NewUi");
                            $('#sub_file').attr("disabled", false);

                            if (isVideoCoverUp) {
                                $("#video-preview .VideoCover").append(failSvg);
                            } else {
                                $("#image-preview .image-item .ImgCloseBtn").remove();
                                $("#video-preview .VideoCont, #image-preview .image-item").append(failSvg);
                            }

                            $('.progress-bar').css({
                                background: "#f56881",
                                width: 10 + "%"
                            });
                        }
                    } else {
                        toastr["error"]("上传图片失败 — 未知错误 请联系开发者！", "LG_NewUi");
                        $('#sub_file').attr("disabled", false);
                    }
                },

                error: function(xhr, status, error) {
                    console.error('请求出错：' + error);
                },
                complete: function() {
                    $("#loading-overlay").fadeOut(1000);
                    $('.progress-text-style').text('');

                    // 如果当前上传的是封面 则不清空所有文件的数组
                    if (!selectedThumbnail) {
                        files = null;
                    }

                    if (selectedThumbnail) {
                        $('#sub_file').text("上传媒体");
                        $('#sub_file').attr("disabled", false);
                    }

                    selectedThumbnail = null;
                    isVideoCoverUp = null;
                }
            });
        });

        var Copy_content = new ClipboardJS('#CopyPicture', {
            target: function() {
                return $('#src_url')[0];
            }
        });

        Copy_content.on('success', function(e) {
            toastr["success"]("复制文本框内容成功！", "LG_NewUi");
        });

        Copy_content.on('error', function(e) {
            if (e.text == '') {
                toastr["error"]("复制失败 文本框内容为空！", "LG_NewUi");
            }
        });
    });
</script>