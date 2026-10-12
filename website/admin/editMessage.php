<?php
session_start();
include_once 'Nav.php';
$id = max(1, (int)($_GET['id'] ?? 0));
include_once 'connect.php';
$Msg_data = "SELECT * FROM leaving WHERE id= $id limit 1";
$res_Msg = mysqli_query($connect, $Msg_data);
$MsgInfo = mysqli_fetch_array($res_Msg);

?>


<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">修改 — <?php echo $MsgInfo['name'] ?> 游客留言信息</h4>

                <form class="needs-validation" method="post" novalidate>
                    <div class="form-group">
                        <label>留言内容</label>
                        <textarea name="MsgText" class="form-control" rows="5"
                            placeholder="请输入游客留言内容"><?php echo $MsgInfo['text'] ?></textarea>
                    </div>

                    <div class="row">

                        <div class="form-group col-lg-4">
                            <label>昵称</label>
                            <input type="text" class="form-control" placeholder="请输入游客昵称" name="MsgName"
                                value="<?php echo $MsgInfo['name'] ?>" required>
                        </div>

                        <?php if ($MsgInfo['QQ'] <> 'anon'): ?>
                            <div class="form-group col-lg-4">
                                <label>QQ号码</label>
                                <input class="form-control" type="text" name="MsgQQ" class="form-control"
                                    placeholder="请输入游客QQ号码" value="<?php echo $MsgInfo['QQ'] ?>" required>
                            </div>
                        <?php endif; ?>

                        <div class="form-group col-lg-4">
                            <label>留言时间</label>
                            <input type="datetime-local" class="form-control" name="MsgTime"
                                value="<?php echo date("Y-m-d H:i", $MsgInfo['time']) ?>" required>
                        </div>

                    </div>

                    <div class="row">

                        <div class="form-group col-lg-6">
                            <label>IP地址</label>
                            <input type="text" class="form-control" placeholder="请输入游客IP地址" name="MsgIP"
                                value="<?php echo $MsgInfo['ip'] ?>" required>
                        </div>

                        <div class="form-group col-lg-6">
                            <label>IP归属地</label>
                            <input class="form-control" type="text" name="MsgCity" class="form-control"
                                placeholder="请输入游客IP归属地" value="<?php echo $MsgInfo['city'] ?>" required>
                        </div>

                    </div>

                    <div class="row">

                        <div class="form-group col-lg-6">
                            <label>留言操作系统</label>
                            <select class="form-control" name="Os_info">
                                <option value="Windows" <?php echo $MsgInfo['Os_info'] == 'Windows' ? 'selected' : '' ?>>
                                    Windows</option>
                                <option value="Android" <?php echo $MsgInfo['Os_info'] == 'Android' ? 'selected' : '' ?>>
                                    Android</option>
                                <option value="iOS" <?php echo $MsgInfo['Os_info'] == 'iOS' ? 'selected' : '' ?>>iOS
                                </option>
                                <option value="Mac" <?php echo $MsgInfo['Os_info'] == 'Mac' ? 'selected' : '' ?>>Mac
                                </option>
                                <option value="Linux" <?php echo $MsgInfo['Os_info'] == 'Linux' ? 'selected' : '' ?>>Linux
                                </option>
                            </select>
                        </div>

                        <div class="form-group col-lg-6">
                            <label>留言浏览器</label>
                            <select class="form-control" name="Br_info">
                                <option value="Chrome" <?php echo $MsgInfo['Br_info'] == 'Chrome' ? 'selected' : '' ?>>
                                    Chrome</option>
                                <option value="Firefox" <?php echo $MsgInfo['Br_info'] == 'Firefox' ? 'selected' : '' ?>>
                                    Firefox</option>
                                <option value="Safari" <?php echo $MsgInfo['Br_info'] == 'Safari' ? 'selected' : '' ?>>
                                    Safari</option>
                                <option value="360_Browser" <?php echo $MsgInfo['Br_info'] == '360_Browser' ? 'selected' : '' ?>>360_Browser</option>
                                <option value="QQ_Browser" <?php echo $MsgInfo['Br_info'] == 'QQ_Browser' ? 'selected' : '' ?>>QQ_Browser</option>
                                <option value="Quark" <?php echo $MsgInfo['Br_info'] == 'Quark' ? 'selected' : '' ?>>Quark
                                </option>
                                <option value="Opera" <?php echo $MsgInfo['Br_info'] == 'Opera' ? 'selected' : '' ?>>Opera
                                </option>
                            </select>
                        </div>

                    </div>

                    <input name="id" value="<?php echo $MsgInfo['id'] ?>" type="hidden">
                    <div class="form-group mb-3 text_right">
                        <button class="btn btn-primary" type="button" id="upMsgPost">修改留言信息</button>
                    </div>
                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>


<?php
include_once 'Footer.php';
?>
</body>

</html>
