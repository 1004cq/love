<?php
session_start();
include_once 'Nav.php';
$L_Behavior = "select * from Behavior order by id desc";
$L_info = mysqli_query($connect, $L_Behavior);
?>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">登录行为详情</h4>
                <table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>管理员账号</th>
                            <th>登录时间</th>
                            <th>管理员昵称</th>
                            <th>登录IP/归属地</th>
                            <th>登录设备/浏览器</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $num_L = 0;
                        while ($LoginInfo = mysqli_fetch_array($L_info)) {
                            $num_L = $num_L + 1;
                            ?>
                            <tr>
                                <td><span class="badge badge-danger badge-pill" style="font-size:0.8rem;">
                                        <i class="ri-at-line rihjt-0"></i>
                                        <?php echo $num_L ?>
                                    </span></td>
                                <td>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-rounded"><?php echo $LoginInfo['L_user'] ?></button>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo date("Y-m-d H:i:s", $LoginInfo['L_date']) ?>
                                    </small>
                                    <div class="color">
                                        <?php echo time_tran($LoginInfo['L_date']) . "-登录小站后台" ?>
                                    </div>
                                </td>
                                <td>
                                    <h5><span class="badge badge-success-lighten">
                                            <?php echo $LoginInfo['L_name'] ?>
                                        </span></h5>
                                </td>
                                <td>
                                    <h5>
                                        <span class="badge badge-danger-lighten">
                                            <?php echo $LoginInfo['L_ip'] ?>
                                        </span>
                                    </h5>
                                    <i>
                                        <?php echo $LoginInfo['L_city'] ?>
                                    </i>
                                </td>
                                <td>
                                    <button type="button" style="margin-top: 0.2rem;"
                                        class="btn btn-outline-dark btn-rounded btn-sm">
                                        <?php echo GetOsIcon($LoginInfo['L_os']) . $LoginInfo['L_os'] . " ｜ " . GetBrIcon($LoginInfo['L_br']) . $LoginInfo['L_br'] ?>
                                    </button>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>


<script>
    function del(id, State) {
        if (confirm('您确认要删除IP为 ' + State + ' 吗')) {
            location.href = 'delip.php?id=' + id + '&State' + State;
        }
    }
</script>


<?php
include_once 'Footer.php';
?>

</body>

</html>