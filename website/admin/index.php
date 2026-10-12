<?php
session_start();
include_once 'Nav.php';

$Behavior_sql = "select * from Behavior order by id desc limit 6";
$resBehavior = mysqli_query($connect, $Behavior_sql);
?>
<div class="center_alert" style="text-align: center">
    <?php if ($login['user'] == $default_info['admin']) { ?>
        <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert"
            style="display: inline-block;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Warning - </strong> 当前账号为默认账号 请尽快修改！
        </div>
    <?php } ?>
    <?php if (password_verify($default_info['pw'], $login['pw'])) { ?>
        <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert"
            style="display: inline-block;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Warning - </strong> 当前密码为默认密码 请尽快修改！
        </div>
    <?php } ?>
</div>

<div class="row">

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="AminIndexCardIcon">
                    <img draggable="false" src="../Style/img/AdminIcon/card4.png" alt="留言数量">
                </div>
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="text-muted font-weight-normal mt-0 text-truncate">留言数量</h5>
                        <h3 class="my-2 py-1">
                            <?php echo $shu ?><i>条</i>
                        </h3>
                    </div>
                    <div class="col-6">
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="AminIndexCardIcon">
                    <img draggable="false" src="../Style/img/AdminIcon/card1.png" alt="点点滴滴">
                </div>
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="text-muted font-weight-normal mt-0 text-truncate" title="New Leads">点点滴滴</h5>
                        <h3 class="my-2 py-1">
                            <?php echo $diannub ?><i>条</i>
                        </h3>
                    </div>
                    <div class="col-6">
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="AminIndexCardIcon">
                    <img draggable="false" src="../Style/img/AdminIcon/card3.png" alt="恋爱清单">
                </div>
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="text-muted font-weight-normal mt-0 text-truncate" title="Deals">恋爱清单</h5>
                        <h3 class="my-2 py-1">
                            <?php echo $listnub ?><i>条</i>
                        </h3>
                    </div>
                    <div class="col-6">
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->

    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="AminIndexCardIcon">
                    <img draggable="false" src="../Style/img/AdminIcon/card2.png" alt="相册数量">
                </div>
                <div class="row align-items-center">
                    <div class="col-6">
                        <h5 class="text-muted font-weight-normal mt-0 text-truncate" title="Campaign Sent">相册数量</h5>
                        <h3 class="my-2 py-1">
                            <?php echo $imgnub ?><i>个</i>
                        </h3>
                    </div>
                    <div class="col-6">
                    </div>
                </div> <!-- end row-->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->

</div>
<!-- end row -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-lg-4">
                        <div class="text-lg-right">
                            <button type="button" class="btn btn-danger mb-2 mr-2"><i class="mdi mdi-basket mr-1"></i>
                                登录行为
                            </button>
                        </div>
                    </div><!-- end col-->
                </div>

                <div class="table-responsive">
                    <table class="table table-centered mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>管理员昵称</th>
                                <th>登录时间</th>
                                <th style="white-space: nowrap;">登录地址</th>
                                <th style="white-space: nowrap;">设备/浏览器</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($info = mysqli_fetch_array($resBehavior)) {
                            ?>
                                <tr>
                                    <td>
                                        <h5><span class="badge badge-success-lighten"><i
                                                    class="mdi mdi-account-circle mr-1 rihjt-0"></i>
                                                <?php echo $info['L_name'] ?>
                                            </span>
                                        </h5>

                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo date('Y-m-d H:i:s', $info['L_date']) ?>
                                            <div class="color">
                                                <?php echo time_tran($info['L_date']) . "登录" ?>
                                            </div>
                                        </small>
                                    </td>
                                    <td>
                                        <?php echo $info['L_city'] ?>
                                    </td>
                                    <td>
                                        <button type="button" style="margin-top: 0.2rem;white-space: nowrap;"
                                            class="btn btn-outline-dark btn-rounded btn-sm">
                                            <?php echo GetOsIcon($info['L_os']) . $info['L_os'] . " ｜ " . GetBrIcon($info['L_br']) . $info['L_br'] ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
include_once 'Footer.php';
?>

</body>

</html>