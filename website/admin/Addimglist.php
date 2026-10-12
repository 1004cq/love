<?php
session_start();
include_once 'Nav.php';
$id = $_GET['id'];
$loveImg = "select * from loveImg WHERE id= $id limit 1";
$resImg = mysqli_query($connect, $loveImg);
$Imglist = mysqli_fetch_array($resImg);
$time = date("Y-m-d");
$photo_list = "select * from Photolist WHERE photo_id = $id order by id desc";
$photo_Img = mysqli_query($connect, $photo_list);

?>

<style>
    .row_line_x {
        box-sizing: border-box;
        display: block;
        border: none !important;
        border-top: 1px solid #559fff !important;
        margin: 2rem auto;
        border-radius: 1rem;
        width: 80%;
    }
</style>

<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">上传相册—— ID：
                    <?php echo $Imglist['id'] ?>
                </h4>

                <form class="needs-validation" action="#" method="post" novalidate>
                    <div class="form-group mb-3">
                        <label for="validationCustom01">当前相册描述信息</label>
                        <input name="imgText" type="text" class="form-control" placeholder="请输入图片描述"
                            value="<?php echo $Imglist['imgText'] ?>" required readonly>
                    </div>

                    <span class="row_line_x"></span>

                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="validationCustom01"><b class="required_marker">*</b>拍摄日期</label>
                            <input class="form-control" id="example-date" type="date" name="imglistDatd"
                                class="form-control" placeholder="日期" value="<?php echo $time ?>" required>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="validationCustom01"><b class="required_marker">*</b>拍摄者By—Name</label>
                            <select class="form-control" id="byname" name="by_name">
                                <option value="<?php echo $text['boy'] ?>" <?php if ($User_InfoNU['name'] == $text['boy']) {
                                       echo "selected";
                                   } ?>><?php echo $text['boy'];
                                    if ($User_InfoNU['name'] == $text['boy']) {
                                        echo "（当前登录）";
                                    } ?></option>
                                <option value="<?php echo $text['girl'] ?>" <?php if ($User_InfoNU['name'] == $text['girl']) {
                                       echo "selected";
                                   } ?>><?php echo $text['girl'];
                                    if ($User_InfoNU['name'] == $text['girl']) {
                                        echo "（当前登录）";
                                    } ?></option>
                                <option value="0">自定义Name</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="diy_name">
                        <label for="validationCustom01" style="color:#ff5076;">自定义拍摄者Name</label>
                        <input type="text" id="day_n" class="form-control" placeholder="请输入图片描述"
                            style="border-color:#ff5076;" value="" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="validationCustom01"><b class="required_marker">*</b>文本描述</label>
                        <input type="text" name="imglist_text" class="form-control" placeholder="请输入文本描述" value=""
                            required>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-4">
                            <label for="validationCustom01"><b class="required_marker">*</b>媒体URL类型</label>
                            <select class="form-control" name="photo_type">
                                <option value="0">图片</option>
                                <option value="1">视频</option>
                            </select>
                            <span class="help-block"><small>上传后会默认判断媒体类型 如判断有误 请手动选择媒体URL类型</small></span>
                        </div>

                        <div class="form-group col-lg-8" id="isVideoCoverInput">
                            <label for="validationCustom01"><b class="required_marker">*</b>视频封面URL直链</label>
                            <input type="text" name="VideoCover" id="VideoCoverUrl" class="form-control"
                                placeholder="请输入视频封面URL" value="" required>
                        </div>
                    </div>

                    <div class="form-group mb-3" id="img_url">
                        <label for="validationCustom01"><b class="required_marker">*</b>媒体URL直链
                            <textarea id="src_url" name="imglistUrl" class="form-control" rows="5"
                                placeholder="请输入图片/视频URL地址"></textarea>
                            <span class="help-block"><small>视频不支持批量插入 请一行输入一个图片URL地址</small></span>
                    </div>

                    <div class="form-group mb-3 text_right">
                        <input name="id" value="<?php echo $id ?>" type="hidden">
                        <input name="img_code" value="<?php echo $Imglist['img_code'] ?>" type="hidden">
                        <button class="btn btn-primary" type="button" id="ImgListPost">新增图片/视频</button>
                    </div>
                </form>


                <?php
                include_once "picture_upload.php";
                ?>


                <span class="row_line_x"></span>

                <h4 class="header-title mb-3 size_18">子相册列表</h4>


                <div id="accordion" class="custom-accordion mb-4">

                    <div class="card mb-0">
                        <div class="card-header" id="headingOne">
                            <h5 class="m-0">
                                <a class="custom-accordion-title d-block pt-2 pb-2" data-toggle="collapse"
                                    href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    相册列表--<?php echo $Imglist['imgText'] ?>（点击展开）<span class="float-right"><i
                                            class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#ID</th>
                                            <th>图片描述</th>
                                            <th>By_Name</th>
                                            <th>URL类型</th>
                                            <th style="width:150px;">操作</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $num_p = 0;
                                        while ($photo = mysqli_fetch_array($photo_Img)) {
                                            $photo['PhotoTitle'] = $Imglist['imgText'];
                                            ?>
                                            <tr>
                                                <td>
                                                    <span class="badge badge-danger badge-pill" style="font-size:0.8rem;">
                                                    <i class="ri-at-line rihjt-0"></i>
                                                        <?php echo getNextSequentialNumber(); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="text_photo">
                                                        <?php echo $photo['photo_text'] ?>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-success-lighten">
                                                        <?php echo $photo['photo_byname'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($photo['photo_type']) { ?>
                                                        <svg t="1682776947968" class="icon" viewBox="0 0 1024 1024"
                                                            version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1827"
                                                            width="30" height="30">
                                                            <path
                                                                d="M21.333329 405.333462l981.333129 0 0 597.333209-981.333129 0 0-597.333209Z"
                                                                fill="#9FDBAD" p-id="1828"></path>
                                                            <path
                                                                d="M959.9998 426.666791a21.333329 21.333329 0 0 1 21.333329 21.333329v511.999893a21.333329 21.333329 0 0 1-21.333329 21.333329H63.999987a21.333329 21.333329 0 0 1-21.333329-21.333329V448.00012a21.333329 21.333329 0 0 1 21.333329-21.333329h895.999813m0-42.666658H63.999987a63.999987 63.999987 0 0 0-63.999987 63.999987v511.999893a63.999987 63.999987 0 0 0 63.999987 63.999987h895.999813a63.999987 63.999987 0 0 0 63.999987-63.999987V448.00012a63.999987 63.999987 0 0 0-63.999987-63.999987z"
                                                                fill="#5C2D51" p-id="1829"></path>
                                                            <path
                                                                d="M66.986653 407.040129a15.999997 15.999997 0 0 1-15.359997-11.946665L21.333329 281.600155a15.999997 15.999997 0 0 1 11.519997-19.626663L951.679802 21.333542h4.053332a15.999997 15.999997 0 0 1 15.359997 11.946664l29.653327 113.49331a15.999997 15.999997 0 0 1-11.519997 19.626663L71.039985 406.613462z"
                                                                fill="#FFFFFF" p-id="1830"></path>
                                                            <path
                                                                d="M951.893135 43.946871l27.093328 103.253312L70.826652 384.000133l-26.879994-102.613312L951.893135 43.946871M955.733134 0.000213a37.333326 37.333326 0 0 0-9.386664 1.28L27.946661 241.493496a37.333326 37.333326 0 0 0-26.666661 45.439991l29.653327 113.49331A37.333326 37.333326 0 0 0 76.373317 426.666791L994.986459 186.880174a37.333326 37.333326 0 0 0 26.666661-45.653323L991.999793 27.946874A37.333326 37.333326 0 0 0 955.733134 0.000213z"
                                                                fill="#5C2D51" p-id="1831"></path>
                                                            <path
                                                                d="M177.493296 224.426833l78.933317-20.693329 9.173332 151.893302-78.933317 20.693329-9.173332-151.893302z"
                                                                fill="#F05071" p-id="1832"></path>
                                                            <path
                                                                d="M236.799951 230.826832l6.399998 108.586644-36.906659 9.599998-6.399998-108.586644 36.906659-9.599998m39.466658-54.399989L155.306634 208.213503l11.519998 195.413293 120.959975-31.573327-11.519998-195.413292z"
                                                                fill="#5C2D51" p-id="1833"></path>
                                                            <path
                                                                d="M369.919923 174.080177l78.933317-20.693329 8.959998 152.106635-78.933317 20.479996-8.959998-151.893302z"
                                                                fill="#F05071" p-id="1834"></path>
                                                            <path
                                                                d="M429.013244 180.693509l6.399999 108.586644-36.693326 9.386665-6.399999-108.586644 36.906659-9.599998M469.333236 126.29352L347.519928 157.866847l11.519997 195.413293 120.959975-31.573327-10.666664-195.413293z"
                                                                fill="#5C2D51" p-id="1835"></path>
                                                            <path
                                                                d="M562.133216 123.733521l78.933317-20.693329 9.173332 152.106635-79.146651 20.693329-8.959998-152.106635z"
                                                                fill="#F05071" p-id="1836"></path>
                                                            <path
                                                                d="M621.439871 130.346853l6.399998 108.586644-36.906659 9.599998-6.399998-108.586644 36.906659-9.599998M661.333196 75.946864L539.946554 106.666858l11.519998 195.413292 120.959975-31.573326L661.333196 75.946864z"
                                                                fill="#5C2D51" p-id="1837"></path>
                                                            <path
                                                                d="M754.559843 73.386865l78.719983-20.479996 9.173332 151.893302-78.933317 20.693329-8.959998-152.106635z"
                                                                fill="#F05071" p-id="1838"></path>
                                                            <path
                                                                d="M813.653164 80.000197l6.399998 108.586644-36.906658 9.599998-6.399999-108.586644 36.906659-9.599998M853.333156 25.600208l-121.173309 31.573327 11.519998 195.413292 120.959975-31.573326L853.333156 25.600208z"
                                                                fill="#5C2D51" p-id="1839"></path>
                                                            <path
                                                                d="M429.013244 842.026705a42.666658 42.666658 0 0 1-42.666658-42.666658v-189.866627a42.666658 42.666658 0 0 1 42.666658-42.666658 42.666658 42.666658 0 0 1 21.333329 5.759999l165.973299 94.933313a42.666658 42.666658 0 0 1 0 74.666651l-165.973299 94.079981a42.666658 42.666658 0 0 1-21.333329 5.759999z"
                                                                fill="#FDCA89" p-id="1840"></path>
                                                            <path
                                                                d="M429.013244 587.306758a21.333329 21.333329 0 0 1 10.666664 2.986666l165.973299 94.933313a21.333329 21.333329 0 0 1 0 37.759992l-165.973299 94.933314a21.333329 21.333329 0 0 1-10.666664 2.986666 21.333329 21.333329 0 0 1-21.333329-21.333329v-189.866627a21.333329 21.333329 0 0 1 21.333329-21.333329m0-42.666658a63.999987 63.999987 0 0 0-63.999987 63.999987v189.866627a63.999987 63.999987 0 0 0 96.426647 55.893322l165.973299-94.933314a63.999987 63.999987 0 0 0 0-111.786643l-165.973299-94.933314a63.999987 63.999987 0 0 0-31.78666-8.533331z"
                                                                fill="#5C2D51" p-id="1841"></path>
                                                        </svg>
                                                    <?php } else { ?>
                                                        <svg t="1682777006316" class="icon" viewBox="0 0 1281 1024"
                                                            version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1756"
                                                            width="30" height="30">
                                                            <path
                                                                d="M106.805737 997.298566a80.104302 80.104302 0 0 1-80.104303-80.104303V106.805737a80.104302 80.104302 0 0 1 80.104303-80.104303h1068.057366a80.104302 80.104302 0 0 1 80.104302 80.104303v825.074315a80.104302 80.104302 0 0 1-80.104302 65.418514z"
                                                                fill="#9FDBAD" p-id="1757"></path>
                                                            <path
                                                                d="M1174.863103 53.402868a53.402868 53.402868 0 0 1 53.402868 53.402869v822.404172a53.402868 53.402868 0 0 1-53.402868 41.387223H106.805737a53.402868 53.402868 0 0 1-53.402869-53.402869V106.805737a53.402868 53.402868 0 0 1 53.402869-53.402869h1068.057366m0-53.402868H106.805737a106.805737 106.805737 0 0 0-106.805737 106.805737v810.388526a106.805737 106.805737 0 0 0 106.805737 106.805737h1068.057366a106.805737 106.805737 0 0 0 106.805737-106.805737h-1.335072V106.805737a106.805737 106.805737 0 0 0-106.805737-106.805737z"
                                                                fill="#5C2D51" p-id="1758"></path>
                                                            <path
                                                                d="M299.056063 400.521512a99.863364 99.863364 0 0 1-100.931422-101.465449A100.931421 100.931421 0 1 1 299.056063 400.521512z"
                                                                fill="#F05071" p-id="1759"></path>
                                                            <path
                                                                d="M299.056063 223.491004a73.695958 73.695958 0 0 1 74.764015 74.497001A75.03103 75.03103 0 0 1 299.056063 373.820078a73.428944 73.428944 0 0 1-74.229987-74.764015 75.03103 75.03103 0 0 1 74.229987-75.565059m0-53.402868a128.166884 128.166884 0 1 0 128.166884 127.899869 128.166884 128.166884 0 0 0-128.166884-127.899869z"
                                                                fill="#5C2D51" p-id="1760"></path>
                                                            <path
                                                                d="M198.124641 827.744459v-93.45502l186.91004-186.910039 70.224771 69.957758a51.266754 51.266754 0 0 0 72.360887 0l305.731421-302.794264 252.328553 252.061539v260.605997z"
                                                                fill="#FDCA89" p-id="1761"></path>
                                                            <path
                                                                d="M833.35176 352.191917l224.559061 224.292046V801.043025H224.826076v-53.402869l160.208605-160.208605 51.266753 51.266754a77.968188 77.968188 0 0 0 110.276923 0l286.773403-286.506388m0-65.1515a24.565319 24.565319 0 0 0-17.355932 7.209387L509.196349 599.714211a24.565319 24.565319 0 0 1-34.711864 0l-71.559844-71.559843a24.565319 24.565319 0 0 0-34.711864 0l-189.847197 189.580182a23.230248 23.230248 0 0 0-6.942373 16.554889v95.591135a23.230248 23.230248 0 0 0 23.497262 24.565319h893.162973a23.230248 23.230248 0 0 0 23.230248-23.230248v-267.014341a23.230248 23.230248 0 0 0-6.942373-16.554889L850.707692 293.715776a24.565319 24.565319 0 0 0-17.355932-7.209387z"
                                                                fill="#5C2D51" p-id="1762"></path>
                                                        </svg>
                                                    <?php } ?>

                                                </td>
                                                <td style="text-align: right;">

                                                    <div class="userProcessing">
                                                        <a href="update_imglist.php?id=<?php echo $photo['id'] ?>"
                                                            class="mr-1">
                                                            <button style="white-space: nowrap;" type="button"
                                                                class="btn btn-outline-dark btn-rounded">
                                                                <i class="mdi mdi-calendar-edit mr-1"></i>编辑
                                                            </button>
                                                        </a>

                                                        <a
                                                            onclick="PublicPopUpLayer(1,'Photolist','<?php echo htmlspecialchars(json_encode($photo, JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>')">
                                                            <button type="button" class="btn btn-danger btn-rounded">
                                                                <i class=" mdi mdi-delete-empty mr-1"></i>删除
                                                            </button>
                                                        </a>
                                                    </div>

                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- end card-->

                </div> <!-- end custom accordions-->


            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>


<script>
    function del(id, photo_text) {
        if (confirm('您确认要删除标题为 ' + photo_text + ' 的图片吗')) {
            location.href = 'delphoto.php?id=' + id;
        }
    }
    $("#diy_name").hide('slow', 'linear');
    $('#byname').change(() => {
        var by_name = $('select option:selected').val();
        if (by_name == 0) {
            $("#diy_name").hide('slow', 'linear');
            $("#diy_name").show('slow', 'linear');
        } else {
            $("#diy_name").hide('slow', 'linear');
        }
    });
    $("#day_n").blur(function () {
        var name = $("#day_n").val();
        $('#byname option:selected').val(name)
    });
</script>
<?php
include_once 'Footer.php';
?>

</body>

</html>