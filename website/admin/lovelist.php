<?php
session_start();
include_once 'Nav.php';
$lovelist = "select * from lovelist order by id desc";
$reslist = mysqli_query($connect, $lovelist);
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3 size_18">恋爱清单<a href="/admin/lovelistAdd.php">
                        <button type="button" class="btn btn-success btn-sm right_10">
                            <i class="mdi mdi-circle-edit-outline"></i>新增
                        </button>
                    </a>
                </h4>

                <?php if ($listnub <= 0): ?>
                    <div class="NoDataCard-warp">
                        <div class="NoDataCard">
                            <img src="../Style/img/NoData.svg" alt="">
                        </div>
                        <div class="NoDataInfo">
                            <h3>当前页面暂无数据！</h3>
                        </div>
                    </div>
                <?php endif ?>

                <div class="<?php echo ($listnub <= 0) ? ' HiddenElements' : '' ?>">
                    <table id="basic-datatable" class="table dt-responsive nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>事件标题</th>
                                <th>完成状态</th>
                                <th>图片状态</th>
                                <th style="width:150px;">操作</th>
                            </tr>
                        </thead>


                        <tbody>
                            <?php
                            while ($list = mysqli_fetch_array($reslist)) {
                            ?>
                                <tr>
                                    <td><span class="badge badge-danger badge-pill" style="font-size:0.8rem;">
                                            <i class="ri-at-line rihjt-0"></i>
                                            <?php echo getNextSequentialNumber(); ?>
                                        </span></td>
                                    <td>
                                        <?php echo $list['eventname'] ?>
                                    </td>
                                    <td>
                                        <?php if ($list['icon']) { ?> <span class="badge badge-success-lighten">已完成 </span>
                                        <?php } ?>
                                        <?php if (!$list['icon']) { ?> <span class="badge badge-danger-lighten">未完成</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($list['imgurl']) {
                                            echo '<svg t="1682777006316" class="icon" viewBox="0 0 1281 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="1756" width="30" height="30"><path d="M106.805737 997.298566a80.104302 80.104302 0 0 1-80.104303-80.104303V106.805737a80.104302 80.104302 0 0 1 80.104303-80.104303h1068.057366a80.104302 80.104302 0 0 1 80.104302 80.104303v825.074315a80.104302 80.104302 0 0 1-80.104302 65.418514z" fill="#9FDBAD" p-id="1757"></path><path d="M1174.863103 53.402868a53.402868 53.402868 0 0 1 53.402868 53.402869v822.404172a53.402868 53.402868 0 0 1-53.402868 41.387223H106.805737a53.402868 53.402868 0 0 1-53.402869-53.402869V106.805737a53.402868 53.402868 0 0 1 53.402869-53.402869h1068.057366m0-53.402868H106.805737a106.805737 106.805737 0 0 0-106.805737 106.805737v810.388526a106.805737 106.805737 0 0 0 106.805737 106.805737h1068.057366a106.805737 106.805737 0 0 0 106.805737-106.805737h-1.335072V106.805737a106.805737 106.805737 0 0 0-106.805737-106.805737z" fill="#5C2D51" p-id="1758"></path><path d="M299.056063 400.521512a99.863364 99.863364 0 0 1-100.931422-101.465449A100.931421 100.931421 0 1 1 299.056063 400.521512z" fill="#F05071" p-id="1759"></path><path d="M299.056063 223.491004a73.695958 73.695958 0 0 1 74.764015 74.497001A75.03103 75.03103 0 0 1 299.056063 373.820078a73.428944 73.428944 0 0 1-74.229987-74.764015 75.03103 75.03103 0 0 1 74.229987-75.565059m0-53.402868a128.166884 128.166884 0 1 0 128.166884 127.899869 128.166884 128.166884 0 0 0-128.166884-127.899869z" fill="#5C2D51" p-id="1760"></path><path d="M198.124641 827.744459v-93.45502l186.91004-186.910039 70.224771 69.957758a51.266754 51.266754 0 0 0 72.360887 0l305.731421-302.794264 252.328553 252.061539v260.605997z" fill="#FDCA89" p-id="1761"></path><path d="M833.35176 352.191917l224.559061 224.292046V801.043025H224.826076v-53.402869l160.208605-160.208605 51.266753 51.266754a77.968188 77.968188 0 0 0 110.276923 0l286.773403-286.506388m0-65.1515a24.565319 24.565319 0 0 0-17.355932 7.209387L509.196349 599.714211a24.565319 24.565319 0 0 1-34.711864 0l-71.559844-71.559843a24.565319 24.565319 0 0 0-34.711864 0l-189.847197 189.580182a23.230248 23.230248 0 0 0-6.942373 16.554889v95.591135a23.230248 23.230248 0 0 0 23.497262 24.565319h893.162973a23.230248 23.230248 0 0 0 23.230248-23.230248v-267.014341a23.230248 23.230248 0 0 0-6.942373-16.554889L850.707692 293.715776a24.565319 24.565319 0 0 0-17.355932-7.209387z" fill="#5C2D51" p-id="1762"></path></svg>' ?> <?php } else { ?>暂无图片 <?php } ?>
                                    </td>
                                    <td>
                                        <div class="userProcessing">
                                            <a href="modlist.php?id=<?php echo $list['id'] ?>" class="mr-1">
                                                <button style="white-space: nowrap;" type="button"
                                                    class="btn btn-outline-dark btn-rounded">
                                                    <i class="mdi mdi-calendar-edit mr-1"></i>编辑
                                                </button>
                                            </a>
                                            <a onclick="PublicPopUpLayer(1,'lovelist','<?php echo htmlspecialchars(json_encode($list, JSON_UNESCAPED_UNICODE), ENT_QUOTES); ?>')">
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
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>

<script>
    function del(id, eventname) {
        if (confirm('您确认要删除内容为 ' + eventname + ' 的事件吗')) {
            location.href = 'dellist.php?id=' + id + '&title' + eventname;
        }
    }
</script>
<?php
include_once 'Footer.php';
?>
</body>

</html>