<?php
session_start();
include_once 'connect.php';
$absql = "SELECT * FROM about";
$resab = mysqli_query($connect, $absql);
$about = mysqli_fetch_array($resab);
?>
<?php
include_once 'Nav.php';
?>
<form class="needs-validation" action="aboutPost.php" method="post" novalidate>
    <div class="row">

        <div class="col-lg-9">

            <div id="accordion" class="custom-accordion mb-4">

                <div class="card mb-0">
                    <div class="card-header" id="headingOne">
                        <h5 class="m-0">
                            <a class="custom-accordion-title d-block pt-2 pb-2" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                对话配置 #1 <span class="float-right"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                            </a>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">

                            <div class="form-group mb-3">
                                <label for="validationCustom01">对话标题</label>
                                <input type="text" class="form-control" id="validationCustom01" placeholder="请输入标题" name="title"
                                       value="<?php echo $about['title'] ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom01">对话模块背景图片地址</label>
                                <input type="text" class="form-control" id="validationCustom01" placeholder="请输入图片URL地址"
                                       name="aboutimg" value="<?php echo $about['aboutimg'] ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom02">对话1文本</label>
                                <input type="text" class="form-control" id="validationCustom02" placeholder="请填写对话内容"
                                       name="info1" value="<?php echo $about['info1'] ?>" required>

                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom03">对话2文本</label>
                                <input type="text" class="form-control" id="validationCustom03" placeholder="请填写对话内容"
                                       name="info2" value="<?php echo $about['info2'] ?>" required>

                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom04">对话3文本</label>
                                <input type="text" class="form-control" id="validationCustom04" placeholder="请填写对话内容"
                                       name="info3" value="<?php echo $about['info3'] ?>" required>

                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom05">对话1按钮确认文本</label>
                                <input type="text" class="form-control" id="validationCustom05" name="btn1"
                                       value="<?php echo $about['btn1'] ?>" placeholder="请填写确认按钮文本">
                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom05">对话1按钮取消文本</label>
                                <input type="text" class="form-control" id="validationCustom05" name="btn2"
                                       value="<?php echo $about['btn2'] ?>" placeholder="请填写取消按钮文本">
                            </div>


                        </div>
                    </div>
                </div> <!-- end card-->

                <div class="card mb-0">
                    <div class="card-header" id="headingTwo">
                        <h5 class="m-0">
                            <a class="custom-accordion-title collapsed d-block pt-2 pb-2" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                对话配置 #2 <span class="float-right"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                            </a>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">对话2-1文本</label>
                                <input type="text" class="form-control" id="validationCustom01" placeholder="请填写对话内容"
                                       name="infox1" value="<?php echo $about['infox1'] ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom02">对话2-2文本</label>
                                <input type="text" class="form-control" id="validationCustom02" placeholder="请填写对话内容"
                                       name="infox2" value="<?php echo $about['infox2'] ?>" required>

                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom03">对话2-3文本</label>
                                <input type="text" class="form-control" id="validationCustom03" placeholder="请填写对话内容"
                                       name="infox3" value="<?php echo $about['infox3'] ?>" required>

                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom04">对话2-4文本</label>
                                <input type="text" class="form-control" id="validationCustom04" placeholder="请填写对话内容"
                                       name="infox4" value="<?php echo $about['infox4'] ?>" required>

                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom05">对话2-5文本</label>
                                <input type="text" class="form-control" id="validationCustom05" name="infox5"
                                       value="<?php echo $about['infox5'] ?>" placeholder="请填写对话内容">
                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom05">对话2-6文本</label>
                                <input type="text" class="form-control" id="validationCustom05" name="infox6"
                                       value="<?php echo $about['infox6'] ?>" placeholder="请填写对话内容">
                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom05">对话2-1按钮文本</label>
                                <input type="text" class="form-control" id="validationCustom05" name="btnx2"
                                       value="<?php echo $about['btnx2'] ?>" placeholder="请填写按钮文本">
                            </div>


                        </div>
                    </div>
                </div> <!-- end card-->

                <div class="card mb-0">
                    <div class="card-header" id="headingThree">
                        <h5 class="m-0">
                            <a class="custom-accordion-title collapsed d-block pt-2 pb-2" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                对话配置 #3 <span class="float-right"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                            </a>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">对话3-1文本</label>
                                <input type="text" class="form-control" id="validationCustom01" placeholder="请填写对话内容"
                                       name="infof1" value="<?php echo $about['infof1'] ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom02">对话3-2文本</label>
                                <input type="text" class="form-control" id="validationCustom02" placeholder="请填写对话内容"
                                       name="infof2" value="<?php echo $about['infof2'] ?>" required>

                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom03">对话3-3文本</label>
                                <input type="text" class="form-control" id="validationCustom03" placeholder="请填写对话内容"
                                       name="infof3" value="<?php echo $about['infof3'] ?>" required>

                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom04">对话3-4文本</label>
                                <input type="text" class="form-control" id="validationCustom04" placeholder="请填写对话内容"
                                       name="infof4" value="<?php echo $about['infof4'] ?>" required>

                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom05">对话3-1按钮文本</label>
                                <input type="text" class="form-control" id="validationCustom05" name="btnf3"
                                       value="<?php echo $about['btnf3'] ?>" placeholder="请填写按钮文本">
                            </div>


                        </div>
                    </div>
                </div> <!-- end card-->


                <div class="card mb-0">
                    <div class="card-header" id="headingsi">
                        <h5 class="m-0">
                            <a class="custom-accordion-title collapsed d-block pt-2 pb-2" data-toggle="collapse" href="#collapsesi" aria-expanded="false" aria-controls="collapseThree">
                                对话配置 #4 <span class="float-right"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                            </a>
                        </h5>
                    </div>
                    <div id="collapsesi" class="collapse" aria-labelledby="headingsi" data-parent="#accordion">
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="validationCustom01">对话4-1文本</label>
                                <input type="text" class="form-control" id="validationCustom01" placeholder="请填写对话内容"
                                       name="infod1" value="<?php echo $about['infod1'] ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom02">对话4-2文本</label>
                                <input type="text" class="form-control" id="validationCustom02" placeholder="请填写对话内容"
                                       name="infod2" value="<?php echo $about['infod2'] ?>" required>

                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom03">对话4-3文本</label>
                                <input type="text" class="form-control" id="validationCustom03" placeholder="请填写对话内容"
                                       name="infod3" value="<?php echo $about['infod3'] ?>" required>

                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom04">对话4-4文本</label>
                                <input type="text" class="form-control" id="validationCustom04" placeholder="请填写对话内容"
                                       name="infod4" value="<?php echo $about['infod4'] ?>" required>

                            </div>
                            <div class="form-group mb-3">
                                <label for="validationCustom05">对话4-5文本</label>
                                <input type="text" class="form-control" id="validationCustom05" name="infod5"
                                       value="<?php echo $about['infod5'] ?>" placeholder="请填写对话内容">
                            </div>
                    </div>
                </div> <!-- end card-->
                </div>
            </div> <!-- end custom accordions-->
        </div>

    </div>


    <div class="form-group mb-3 text_right">
        <button class="btn btn-primary" type="button" id="aboutPost">提交修改</button>
    </div>
</form>
<style>
    .row{
        justify-content: center;
    }
</style>

<?php
include_once 'Footer.php';
?>

</body>
</html>