<?php
session_start();
include_once 'Nav.php';
$AvatarFramesSQL = "SELECT * FROM CoupleAvatarFrames ORDER BY id DESC";
$AvatarFramesData = mysqli_query($connect, $AvatarFramesSQL);
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="header-title mb-3 size_18">情侣头像框列表
                    <button type="button" class="btn btn-success btn-sm right_10" 
                     onclick="PublicPopUpLayer(3,'','<?php echo htmlspecialchars(json_encode($AdminAvatarAddress, JSON_UNESCAPED_UNICODE),ENT_QUOTES) ?>')">
                        <i class="mdi mdi-circle-edit-outline"></i>新增
                    </button>
                </h4>
                <table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>头像框标题</th>
                            <th>上偏移量</th>
                            <th>左偏移量</th>
                            <th>放大倍率</th>
                            <th>添加时间</th>
                            <th>头像框预览</th>
                            <th style="width:150px;">操作</th>
                        </tr>
                    </thead>

                    <form class="needs-validation" method="post">
                        <tbody>
                            <?php
                            while ($info = mysqli_fetch_array($AvatarFramesData)) {

                                $AdminAvatarAddress['title'] = $info['title'];
                                $AdminAvatarAddress['top_offset'] = $info['top_offset'];
                                $AdminAvatarAddress['left_offset'] = $info['left_offset'];
                                $AdminAvatarAddress['scale'] = $info['scale'];
                                $AdminAvatarAddress['url'] = $info['url'];
                                $AdminAvatarAddress['id'] = $info['id'];
                                $AdminAvatarAddress['created_at'] = $info['created_at'];
                                ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-danger badge-pill" style="font-size:0.8rem;">
                                            <i class="ri-at-line rihjt-0"></i>
                                            <?php echo getNextSequentialNumber(); ?>
                                        </span>
                                    </td>

                                    <td><?php echo $info['title'] ?></td>
                                    <td><?php echo $info['top_offset'] ?> PX</td>
                                    <td><?php echo $info['left_offset'] ?> PX</td>
                                    <td><?php echo $info['scale'] ?> 倍</td>
                                    <td><?php echo $info['created_at'] ?></td>
                                    <td>
                                        <div class="AvatarFramesPreview">
                                            <img class="lazy" data-src="<?php echo $info['url'] ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <button style="white-space: nowrap;" type="button" class="btn btn-outline-dark btn-rounded"
                                            onclick="PublicPopUpLayer(3,'UP','<?php echo htmlspecialchars(json_encode($AdminAvatarAddress, JSON_UNESCAPED_UNICODE),ENT_QUOTES) ?>')">
                                                    <i class="mdi mdi-calendar-edit mr-1"></i>编辑
                                        </button>
                                        <a
                                            onclick="PublicPopUpLayer(1,'CoupleAvatarFrames','<?php echo htmlspecialchars(json_encode($info, JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>')">
                                            <button type="button" class="btn btn-danger btn-rounded">
                                                <i class=" mdi mdi-delete-empty mr-1"></i>删除
                                            </button>
                                        </a>
                                        <input name="id" value="<?php echo $info['id']; ?>" type="hidden">
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                </table>
                </form>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>


<script>
    function del(id, articletitle) {
        if (confirm('您确认要删除标题为 ' + articletitle + ' 的文章吗')) {
            location.href = 'dellitt.php?id=' + id + '&title' + articletitle;
        }
    }
</script>
<?php
include_once 'Footer.php';
?>

</body>

</html>