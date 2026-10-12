<?php
session_start();
include_once 'Nav.php';

$ImageSet_sql = "select * from ImageSet";
$ImageSet_res = mysqli_query($connect, $ImageSet_sql);
$ImageSet_data = mysqli_fetch_array($ImageSet_res);

include_once 'CloudStorageManager.php';

function getFileList($fileList, $currentPage = 1, $perPage = 8, $isLocal = true)
{
    // 如果是本地文件 获取本地文件路径
    if ($isLocal) {
        // 按路径获取本地文件
        $files = glob($fileList . '*.{JPG,PNG,jpg,jpeg,png,gif,webp}', GLOB_BRACE);
        natcasesort($files); // 自然排序
        $files = array_reverse($files); // 倒序显示
    } else {
        $files = $fileList;
    }

    // 计算总文件数和总页数
    $totalFiles = count($files);
    $totalPages = ceil($totalFiles / $perPage);

    // 防止页数超出范围
    $currentPage = max(1, min($currentPage, $totalPages));

    // 计算起始索引
    $startIndex = ($currentPage - 1) * $perPage;

    // 分页获取文件列表
    $pagedFiles = array_slice($files, $startIndex, $perPage);

    return [
        'files' => $pagedFiles,
        'totalPages' => $totalPages,
        'currentPage' => $currentPage,
    ];
}

$perPage = 8;
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;

if ($ImageSet_data['localOrCloud']) {
    $cloudFileList = $listResponse;

    $fileListResult = getFileList($cloudFileList, $currentPage, $perPage, false);
} else {
    $imageDir = '../' . $ImageSet_data['UploadDirectory'];
    $fileListResult = getFileList($imageDir, $currentPage, $perPage, true);
}

$totalPages = $fileListResult['totalPages'];

$files = $fileListResult['files'];

$imgNum = count($files)
// echo json_encode($cloudFileList)
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-2 size_18">媒体管理</h4>
                <div class="Provider">
                    <span class="badge badge-success badge-pill" style="font-size:0.8rem;padding:.5rem 1rem">
                        <i class="ri-folder-6-fill mr-1"></i><?php echo $ImageSet_data['localOrCloud'] == 1 ? $Provider : '本地存储' ?>
                    </span>
                </div>


                <hr class="User_Hr">


                <div class="Operation-Area">
                    <button id="selectPhotoAll" type="button" class="btn btn-outline-dark btn-rounded mr-2">
                        <i class="ri-stack-fill mr-1"></i>全选
                    </button>
                    <button id="getSelectedKeys" type="button" class="btn btn-danger btn-rounded">
                        <i class="mdi mdi-backspace mr-1"></i>
                        批量删除
                    </button>
                </div>


                <?php if ($imgNum <= 0): ?>
                    <div class="NoDataCard-warp">
                        <div class="NoDataCard">
                            <img src="../Style/img/NoData.svg" alt="">
                        </div>
                        <div class="NoDataInfo">
                            <h3>当前页面暂无数据！</h3>
                        </div>
                    </div>
                <?php endif ?>
                <div class="row mt-5<?php echo ($imgNum <= 0) ? ' HiddenElements' : '' ?>">

                    <?php foreach ($files as $file):

                        if (is_string($file)) {
                            $filepath = $file;
                            $filename = basename($file);
                            $fileSize = filesize($filepath);

                            if ($fileSize >= 1024 * 1024) {
                                $fileSizeFormatted = round($fileSize / (1024 * 1024), 2) . ' MB';
                            } else {
                                $fileSizeFormatted = round($fileSize / 1024, 2) . ' KB';
                            }

                            $imageSize = getimagesize($filepath);
                            $imageWidth = $imageSize[0];
                            $imageHeight = $imageSize[1];

                            $SizeWidthHeight = $imageWidth . ' x ' . $imageHeight;

                            $domainImgHost = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                            $domainImgHost .= "://" . $_SERVER['HTTP_HOST'];
                            $fixedPath = preg_replace('/\.\.\//', '', $file);
                            $absolute_path = "$domainImgHost/$fixedPath";

                            $PhototListInfoArr = [
                                'imageSize' => $fileSizeFormatted,
                                'absolute_path' => $absolute_path,
                                'filename' => $filename,
                                'FileFrom' => 0,
                                'DeleteKey' => $imageDir . $filename
                            ];

                            // 如果文件来自云存储，文件是一个关联数组
                        } elseif (is_array($file)) {
                            $filename = basename($file['key']) ? basename($file['key']) : basename($file['Key']);

                            $DeleteKey = $file['key'] ? $file['key'] : $file['Key'];

                            $fileSize = $file['fsize'] ? $file['fsize'] : $file['Size'];

                            $dateTime = new DateTime($file['LastModified'], new DateTimeZone('UTC'));
                            $dateTime->setTimezone(new DateTimeZone('Asia/Shanghai'));

                            $putTime = $file['putTime'] ? $file['putTime'] : $dateTime->format('Y-m-d H:i:s');

                            $formattedTime = is_numeric($putTime) ? date('Y-m-d H:i:s', $putTime / 10000000) : $putTime;

                            if ($fileSize >= 1024 * 1024) {
                                $fileSizeFormatted = round($fileSize / (1024 * 1024), 2) . ' MB';
                            } else {
                                $fileSizeFormatted = round($fileSize / 1024, 2) . ' KB';
                            }

                            $absolute_path = $file['Purl'];


                            $PhototListInfoArr = [
                                'imageSize' => $fileSizeFormatted,
                                'absolute_path' => $absolute_path,
                                'filename' => $filename,
                                'FileFrom' => 1,
                                'DeleteKey' => $DeleteKey
                            ];
                        }
                    ?>
                        <div class="col-md-6 col-lg-3" data-desc="<?php echo $imageDir . $filename; ?>">
                            <div class="card d-block poR">
                                <div class="imgSize">
                                    <img class="card-img-top" <?php echo $fileSize == 0 ? 'style="object-fit: contain;"' : '' ?> src="<?php echo $fileSize == 0 ? '../Style/img/folder.png' : $absolute_path; ?>"
                                        alt="<?php echo $filename; ?>">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title FileTitle"><?php echo $filename; ?></h5>
                                    <p class="card-text">
                                        <span class="badge badge-warning-lighten badge-pill"><?php echo $SizeWidthHeight ? $SizeWidthHeight : $formattedTime; ?></span>    
                                    
                                        <?php if ($fileSize != 0): ?>
                                            <span class="badge badge-secondary-lighten badge-pill"><?php echo $fileSizeFormatted; ?></span>
                                        <?php endif; ?>
                                    </p>
                                    <?php if ($fileSize != 0): ?>
                                        <div class="LeftCopy">
                                            <button id="CopyImgUrl" type="button" class="btn btn-dark"
                                                data-url="<?php echo $absolute_path; ?>">复制URL</button>
                                        </div>
                                        <div class="textRight poA">
                                            <button type="button" class="btn btn-danger"
                                                onclick="PublicPopUpLayer(6,'','<?php echo htmlspecialchars(json_encode($PhototListInfoArr, JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>')">删除</button>
                                        </div>

                                        <div class="DeleteSelection">
                                            <i class="ri-checkbox-circle-fill DeletePhotosKey" data-fileskey="<?php echo htmlspecialchars(json_encode($PhototListInfoArr, JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($totalPages > 1): ?>
                    <ul class="pagination pagination-rounded textRight">

                        <li class="page-item <?php echo $currentPage == 1 ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="<?php echo $currentPage > 1 ? '?page=' . ($currentPage - 1) : '#'; ?>"
                                aria-label="Previous">
                                <i class="ri-arrow-left-double-line"></i>
                                <span class="sr-only">上一页</span>
                            </a>
                        </li>

                        <?php if ($currentPage > 1): ?>
                            <li class="page-item"><a class="page-link" href="?page=1">1</a></li>
                        <?php endif; ?>

                        <?php if ($currentPage > 3): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>

                        <?php if ($currentPage > 2): ?>
                            <li class="page-item"><a class="page-link"
                                    href="?page=<?php echo $currentPage - 1; ?>"><?php echo $currentPage - 1; ?></a></li>
                        <?php endif; ?>

                        <li class="page-item active"><a class="page-link" href="#"><?php echo $currentPage; ?></a></li>

                        <?php if ($currentPage < $totalPages - 1): ?>
                            <li class="page-item"><a class="page-link"
                                    href="?page=<?php echo $currentPage + 1; ?>"><?php echo $currentPage + 1; ?></a></li>
                        <?php endif; ?>

                        <?php if ($currentPage < $totalPages - 2): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item"><a class="page-link"
                                    href="?page=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a></li>
                        <?php endif; ?>

                        <li class="page-item <?php echo $currentPage == $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="<?php echo $currentPage < $totalPages ? '?page=' . ($currentPage + 1) : '#'; ?>"
                                aria-label="Next">
                                <i class="ri-arrow-right-double-line"></i>
                                <span class="sr-only">下一页</span>
                            </a>
                        </li>

                    </ul>
                <?php endif; ?>


            </div>
        </div>
    </div>
</div>


<script src="../Style/pagelir/spotlight.bundle.js"></script>
<script>
    function myOnClickHandler(obj) {
        var input = document.getElementById("TextWateSwitch");
        if (obj.checked) {
            input.setAttribute("value", "true");
        } else {
            input.setAttribute("value", "false");
        }
    }

    $(document).ready(function() {
        $('#RangeValue').text($("#example-range").val())
        $('#ColorOpacityValue').text($("#ColorOpacity").val())
        $('#example-range').on('input', function() {
            $('#RangeValue').text($(this).val());
            $('#PhotoQuality').val($(this).val());
        });
        $('#ColorOpacity').on('input', function() {
            $('#ColorOpacityValue').text($(this).val());
            $('#OpacityValue').val($(this).val());
        });
        $('#colorPicker').on('input', function() {
            var colorHex = $(this).val();
            var rgb = hexToRgb(colorHex);
            $('#rgbValue').val(rgb.r + ',' + rgb.g + ',' + rgb.b);
        });

        function hexToRgb(hex) {
            hex = hex.replace('#', '');
            var r = parseInt(hex.substring(0, 2), 16);
            var g = parseInt(hex.substring(2, 4), 16);
            var b = parseInt(hex.substring(4, 6), 16);
            return {
                r: r,
                g: g,
                b: b
            };
        }

        var Copy_ImgUrl = new ClipboardJS('#CopyImgUrl', {
            target: function(trigger) {
                var tempInput = $('<input>').attr('type', 'text').addClass('temp-input').val($(trigger).attr('data-url'));
                $('body').append(tempInput); // 将输入框插入到 DOM 中
                return tempInput[0];
            }
        });


    });



    let selectedKeys = [];
    let isBatchDelete = 0;

    $('#getSelectedKeys').prop('disabled', true);

    $('.DeletePhotosKey').on('click', function() {
        $(this).toggleClass('active');
        if ($('.DeletePhotosKey.active').length > 1) {
            $('#getSelectedKeys').prop('disabled', false);
        } else {
            $('#getSelectedKeys').prop('disabled', true);
        }
    });

    $('#selectPhotoAll').on('click', function() {
        let allDeleteButtons = $('.poR .card-body .DeleteSelection .DeletePhotosKey');

        if (allDeleteButtons.hasClass('active')) {
            allDeleteButtons.removeClass('active');
        } else {
            allDeleteButtons.addClass('active');
        }

        if ($('.DeletePhotosKey.active').length > 1) {
            $('#getSelectedKeys').prop('disabled', false);
        } else {
            $('#getSelectedKeys').prop('disabled', true);
        }
    });

    $('#getSelectedKeys').on('click', function() {

        let allFileData = [];
        let fileData;

        $('.DeletePhotosKey.active').each(function() {
            fileData = $(this).data('fileskey');
            allFileData.push(fileData);

            if (fileData && typeof fileData === 'object') {
                let deleteKey = fileData.DeleteKey;
                if (deleteKey) {
                    selectedKeys.push(deleteKey);
                }
            } else {
                try {
                    fileData = JSON.parse(fileData);
                    let deleteKey = fileData.DeleteKey;
                    if (deleteKey) {
                        selectedKeys.push(deleteKey);
                    }
                } catch (e) {
                    console.error('JSON 解析失败:', e);
                }
            }
        });

        if (Array.isArray(allFileData) && allFileData.length > 0) {
            showPopup(allFileData, 9)
            isBatchDelete = 1;

            // console.log(allFileData);
            // console.log(selectedKeys);
        }


    });


    function del() {
        let PhotoKey = $(".pop_up_layer_content #PhotoKey").data('key');
        let isFileFrom = $(".pop_up_layer_content #PhotoKey").data('cloud') || $(".pop_up_layer_content .DelKeys-wrapper").data('cloud');
        let PostPath;
        let DeleteFileRequestData = {};
        if (isFileFrom) {
            // 云存储
            PostPath = "CloudStorageManager.php";
            DeleteFileRequestData.isDeteleFile = 1;
            DeleteFileRequestData.key = PhotoKey;
            DeleteFileRequestData.isBatchDelete = isBatchDelete;
            console.log(selectedKeys);
            DeleteFileRequestData.keys = JSON.stringify(selectedKeys);
        } else {
            // 本地
            DeleteFileRequestData.name = PhotoKey;
            DeleteFileRequestData.keys = JSON.stringify(selectedKeys);
            DeleteFileRequestData.isBatchDelete = isBatchDelete;
            PostPath = "delPhotoFile.php";
        }

        $('#DeletePhoto').attr("disabled", "disabled");
        $.ajax({
            url: PostPath,
            data: DeleteFileRequestData,
            type: "POST",
            dataType: "json",
            success: function(res) {
                if (res == 1 || res.success) {
                    toastr["success"]("删除成功！", "LG_NewUi");
                } else if (res == 2) {
                    toastr["error"]("删除失败 请重试！", "LG_NewUi");
                } else if (res == 3) {
                    toastr["error"]("删除失败 文件不存在！", "LG_NewUi");
                } else {
                    toastr["error"]("删除失败 请联系开发者处理！", "LG_NewUi");
                }
            },
            error: function(err) {
                toastr["error"]("网络错误 请稍后重试！", "LG_NewUi");
            },
            complete: function() {
                $('#DeletePhoto').attr("disabled", false);
                let interval = setInterval(function() {
                    location.reload();
                    clearInterval(interval);
                }, 1000);
                isBatchDelete = 0;
            }
        });
    }

    $(function() {
        $(".imgSize img[src$=jpg],.imgSize img[src$=gif],.imgSize img[src$=JPG],.imgSize img[src$=png],.imgSize img[src$=PNG],.imgSize img[src$=jpeg],.imgSize img[src$=webp]").addClass("spotlight").each(function() {
            this.onclick = function() {
                return hs.expand(this)
            }
        });
    })
</script>


<?php
include_once 'Footer.php';
?>

</body>

</html>