<?php
session_start();
include_once 'Nav.php';
$article = "SELECT id, articletitle,articletime, articlename, encryptionSwitch FROM article ORDER BY id DESC";
$resarticle = mysqli_query($connect, $article);
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="header-title mb-3 size_18">点点滴滴
                    <a class="fabu" href="/admin/littleAdd.php">
                        <button type="button" class="btn btn-success btn-sm right_10">
                            <i class="mdi mdi-circle-edit-outline"></i>新增
                        </button>
                    </a>
                </h4>

                <?php if ($diannub <= 0): ?>
                    <div class="NoDataCard-warp">
                        <div class="NoDataCard">
                            <img src="../Style/img/NoData.svg" alt="">
                        </div>
                        <div class="NoDataInfo">
                            <h3>当前页面暂无数据！</h3>
                        </div>
                    </div>
                <?php endif ?>

                <div class="<?php echo ($diannub <= 0) ? ' HiddenElements' : '' ?>">

                    <table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>标题</th>
                                <th>发布时间</th>
                                <th>发布者</th>
                                <th>加密状态</th>
                                <th style="width:150px;">操作</th>
                            </tr>
                        </thead>

                        <form class="needs-validation" action="littleupda.php" method="post">
                            <tbody>
                                <?php
                                while ($info = mysqli_fetch_array($resarticle)) {
                                ?>
                                    <tr>
                                        <td><span class="badge badge-danger badge-pill" style="font-size:0.8rem;">
                                                <i class="ri-at-line rihjt-0"></i>
                                                <?php echo getNextSequentialNumber(); ?>
                                            </span></td>
                                        <td><?php echo $info['articletitle'] ?></td>
                                        <td><?php if (is_numeric($info['articletime'])) {
                                                echo date('Y-m-d H:i', $info['articletime']);
                                            } else {
                                                echo $info['articletime'];
                                            } ?></td>
                                        <td><?php echo $info['articlename'] ?></td>
                                        <td>
                                            <?php if ($info['encryptionSwitch']): ?>
                                                <svg t="1714272150832" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="5119" id="mx_n_1714272150833" width="100" height="100">
                                                    <path d="M799.999359 384.001425H328.00041c-4.39999 0-7.999982-3.599992-7.999983-7.999982V256.00171c0-51.299886 19.999955-99.499778 56.199875-135.799698C412.500222 84.002093 460.700114 64.002137 512 64.002137s99.499778 19.999955 135.799698 56.199875C683.999617 156.501932 703.999573 204.701824 703.999573 256.00171v1.999996c0 16.599963 13.39997 29.999933 29.999933 29.999933h3.999991c16.599963 0 29.999933-13.39997 29.999933-29.999933 0-70.499843-28.799936-134.999699-75.199833-181.899595C646.199701 29.002215 581.899844-0.297719 511.000002 0.00228 370.100316 0.502279 256.00057 114.902024 256.00057 256.00171v119.999733c0 4.39999-3.599992 7.999982-7.999982 7.999982h-23.999947c-52.999882 0-95.999786 42.999904-95.999786 95.999786v447.999003c0 52.999882 42.999904 95.999786 95.999786 95.999786h575.998718c52.999882 0 95.999786-42.999904 95.999786-95.999786V480.001211c0-52.999882-42.999904-95.999786-95.999786-95.999786zM569.699872 748.700613c-15.999964 12.099973-25.699943 30.699932-25.699943 50.799887V864.000356c0 17.499961-14.199968 31.799929-31.59993 31.999929-17.79996 0.2-32.399928-14.799967-32.399928-32.699927V800.000499c0-20.199955-9.599979-39.099913-25.799942-51.299886C431.00018 731.200652 416.000214 703.300714 416.000214 672.000784c0-52.199884 42.399906-95.299788 94.599789-95.999787 53.69988-0.799998 97.399783 42.499905 97.399783 95.999787 0 31.29993-14.999967 59.199868-38.299914 76.699829z" p-id="5120" fill="#515151"></path>
                                                    <path d="M512 672.000784m-31.999929 0a31.999929 31.999929 0 1 0 63.999858 0 31.999929 31.999929 0 1 0-63.999858 0Z" p-id="5121" fill="#515151"></path>
                                                </svg>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="userProcessing">
                                                <a href="modlitt.php?id=<?php echo $info['id'] ?>" class="mr-1">
                                                    <button style="white-space: nowrap;" type="button"
                                                        class="btn btn-outline-dark btn-rounded">
                                                        <i class="mdi mdi-calendar-edit mr-1"></i>编辑
                                                    </button>
                                                </a>
                                                <a onclick="PublicPopUpLayer(1,'article','<?php echo htmlspecialchars(json_encode($info, JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>')">
                                                    <button type="button" class="btn btn-danger btn-rounded">
                                                        <i class=" mdi mdi-delete-empty mr-1"></i>删除
                                                    </button>
                                                </a>
                                            </div>
                                            <input name="id" value="<?php echo $info['id']; ?>" type="hidden">
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                        </form>
                        </tbody>
                    </table>

                </div>

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