<?php
session_start();
include_once 'Nav.php';
$love_day = "select * from loveday order by day_Weights desc";
$res_day = mysqli_query($connect, $love_day);
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="header-title mb-3 size_18">恋爱纪念日
                    <a class="fabu" href="/admin/day_add.php">
                        <button type="button" class="btn btn-success btn-sm right_10">
                            <i class="mdi mdi-circle-edit-outline"></i>新增
                        </button>
                    </a>
                </h4>

                <?php if ($day_num <= 0): ?>
                    <div class="NoDataCard-warp">
                        <div class="NoDataCard">
                            <img src="../Style/img/NoData.svg" alt="">
                        </div>
                        <div class="NoDataInfo">
                            <h3>当前页面暂无数据！</h3>
                        </div>
                    </div>
                <?php endif ?>

                <div class="<?php echo ($day_num <= 0) ? ' HiddenElements' : '' ?>">
                    <table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>标题</th>
                                <th>排序权重</th>
                                <th>时间</th>
                                <th>类型</th>
                                <th style="width:150px;">操作</th>
                            </tr>
                        </thead>

                        <form class="needs-validation" action="littleupda.php" method="post">
                            <tbody>
                                <?php
                                while ($day_info = mysqli_fetch_array($res_day)) {
                                ?>
                                    <tr>
                                        <td><span class="badge badge-danger badge-pill" style="font-size:0.8rem;">
                                                <i class="ri-at-line rihjt-0"></i>
                                                <?php echo getNextSequentialNumber(); ?>

                                            </span></td>
                                        <td><?php echo $day_info['day_title'] ?></td>
                                        <td>
                                            <span
                                                class="badge badge-warning-lighten font-14"><?php echo $day_info['day_Weights'] ?></span>
                                        </td>
                                        <td><?php echo $day_info['day'] ?></td>
                                        <td><?php if ($day_info['day_type']) {
                                                echo "纪念日";
                                            } else {
                                                echo "倒计时";
                                            } ?></td>
                                        <td>
                                            <div class="userProcessing">
                                                <a href="up_day.php?id=<?php echo $day_info['id'] ?>" class="mr-1">
                                                    <button style="white-space: nowrap;" type="button"
                                                        class="btn btn-outline-dark btn-rounded">
                                                        <i class="mdi mdi-calendar-edit mr-1"></i>编辑
                                                    </button>
                                                </a>
                                                <a
                                                    onclick="PublicPopUpLayer(1,'loveday','<?php echo htmlspecialchars(json_encode($day_info, JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>')">
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

                        </form>
                        </tbody>
                    </table>
                </div>
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>


<script>
    function del(id, title) {
        if (confirm('您确认要删除标题为 ' + title + ' 的纪念内容吗')) {
            location.href = 'delday.php?id=' + id;
        }
    }
</script>
<?php
include_once 'Footer.php';
?>

</body>

</html>