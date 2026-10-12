<?php
session_start();
include_once 'Nav.php';
$id = $_GET['id'];
include_once 'connect.php';
$day_data = "SELECT * FROM loveday WHERE id= $id limit 1";
$res_day = mysqli_query($connect, $day_data);
$day_info = mysqli_fetch_array($res_day);

?>

<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">修改 — <?php echo $day_info['day_title'] ?></h4>

                <form class="needs-validation" m action="littleAddPost.php" method="post" onsubmit="return check()"
                    novalidate>
                    <div class="row">
                        <div class="form-group col-lg-4">
                            <label for="validationCustom01">排序设置</label>
                            <input type="number" class="form-control" placeholder="请输入1～999范围内的数值" name="day_Weights"
                                value="<?php echo $day_info['day_Weights'] ?>" required>
                            <span class="help-block"><small>数值越大 当前卡片排序即越靠前</small></span>
                        </div>

                        <div class="form-group col-lg-4">
                            <label for="validationCustom01">类型</label>
                            <select class="form-control" id="example-select" name="day_type">
                                <option value="1" <?php if ($day_info['day_type']) {
                                    echo "selected";
                                } ?>>纪念日</option>
                                <option value="0" <?php if (!$day_info['day_type']) {
                                    echo "selected";
                                } ?>>倒计时</option>
                            </select>
                        </div>

                        <div class="form-group col-lg-4">
                            <label for="validationCustom01">日期</label>
                            <input class="form-control" id="example-date" type="date" name="day" class="form-control"
                                placeholder="日期" value="<?php echo $day_info['day'] ?>" required>
                        </div>

                    </div>

                    <script>
                        function OnDateHidden(obj) {
                            let input = document.getElementById("isDateHidden");
                            input.setAttribute("value", obj.checked ? 1 : 0);
                        }
                    </script>

                    <div class="form-group mb-3 isDateHidden">
                        <label>是否倒计时结束后自动隐藏</label>
                        <input type="checkbox" name="isDateHidden" id="isDateHidden" <?php echo $day_info['isDateHidden'] ? "checked" : ""; ?> data-switch="success"
                            value="<?php echo $day_info['isDateHidden'] ? '1' : '0'; ?>" onclick="OnDateHidden(this)">
                        <label style="display:block;" for="isDateHidden" data-on-label="Yes"
                            data-off-label="No"></label>
                        <span class="help-block"><small>开启后如倒计时结束则自动隐藏当前卡片 不设置则自动增加一年</small></span>
                    </div>

                    <div class="form-group mb-3">
                        <label for="validationCustom01"><b class="required_marker">*</b>标题</label>
                        <input type="text" class="form-control" placeholder="请输入标题" name="day_title"
                            value="<?php echo $day_info['day_title'] ?>" required>
                    </div>

                    <input name="id" value="<?php echo $day_info['id'] ?>" type="hidden">
                    <div class="form-group mb-3 text_right">
                        <button class="btn btn-primary" type="button" id="upDayPost">修改日期</button>
                    </div>
                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>


<script>

    if ($('select[name=day_type]').val() == 1) {
        $('.isDateHidden').hide();
    }

    $('select[name=day_type]').change(function () {
        const selectedValue = parseInt($(this).val());
        if (selectedValue) {
            $('.isDateHidden').fadeOut();
        } else {
            $('.isDateHidden').fadeIn(200);
        }
    });
</script>


<?php
include_once 'Footer.php';
?>
</body>

</html>