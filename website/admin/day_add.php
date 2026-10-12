<?php
session_start();
include_once 'Nav.php';
$time = date("Y-m-d");
?>

<div class="row">

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">新增恋爱日期</h4>

                <form class="needs-validation" m action="littleAddPost.php" method="post" onsubmit="return check()"
                    novalidate>
                    <div class="form-group mb-3">
                        <label for="validationCustom01"><b class="required_marker">*</b>标题</label>
                        <input type="text" class="form-control" placeholder="请输入标题" name="day_title" required>
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

                    <div class="row">
                        <div class="form-group col-lg-4">
                            <label for="validationCustom01">排序设置
                                <span class="badge badge-primary-lighten">数值越大 排序即越前</span></label>
                            <input type="number" class="form-control" placeholder="请输入1～999范围内的数值" name="day_Weights"
                                required>
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="validationCustom01"><b class="required_marker">*</b>类型</label>
                            <select class="form-control" id="example-select" name="day_type">
                                <option value="1">纪念日</option>
                                <option value="0">倒计时</option>
                            </select>
                        </div>

                        <div class="form-group col-lg-4">
                            <label for="validationCustom01"><b class="required_marker">*</b>日期</label>
                            <input class="form-control" id="example-date" type="date" name="day" class="form-control"
                                placeholder="日期" value="<?php echo $time ?>" required>
                        </div>
                    </div>

                    <div class="form-group mb-3 text_right">
                        <button class="btn btn-primary" type="button" id="dayAddPost">新增日期</button>
                    </div>
                </form>

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>

<script>
    $('.isDateHidden').hide();
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