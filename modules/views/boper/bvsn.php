<p>
<div class="btn-group">
    <span id="btn_new_vn"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-new-version">生成新版本</button></span>
    <span><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#sign-bug-dialog"  id="sign-bug-btn" >标记BUG</button></span>
</div>
</p>
<table class="table table-striped table-bordered table-responsive" id="vcs-table"></table>
<div class="modal fade" id="add-new-version" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">添加注释</h4>
            </div>
            <div class="modal-body">
                <div class="form-group" id="new-version-form-group">
                    <textarea class="form-control" rows="3" id="new-version-comment" name="explain"></textarea>
                    <label class="control-label" id="new-version-comment-label"></label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="add_new_version();">生成新版本</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="img-run-dialog" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-img"  style="width: 216px; height:15px; margin: 129px auto">
        <img src='/Images/run_01.gif' alt='onloading'/>
    </div>
</div>
<div class="modal fade" id="sign-bug-dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">添加注释</h4>
            </div>
            <div class="modal-body">
                <div class="form-group" id="bug-form-group">
                    <textarea class="form-control" rows="3" id="bug-comment"></textarea>
                    <label class="control-label" id="bug-comment-label"></label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="sign_vcs_bug();">Save</button>
            </div>
        </div>
    </div>
</div>
<script>
    function init_vcs_table()
    {
        var table = $('#vcs-table').DataTable({
            "columns": vcs_table_header,
            "order": [[0, "desc"]],
            "ajax": {
                "url": "<?= Yii::$app->urlManager->createUrl(array('admin/boper/jsonbvsn', "id" => $id)) ?>",
                "dataSrc": function(json)
                {
                    if(json.ret != 1)
                    {
                        return false;
                    }
                    var data = json.data;
                    var i;
                    for(i in data)
                    {
                        data[i].desc = data[i].pv_explain;
                        data[i].pv_explain = '<a class="btn btn-default btn-xs" data-toggle="popover" data-placement="top" data-content="<pre>' + data[i].pv_explain + '</pre>">显示</a>';
                        var status = data[i].pv_status;
                        if(status == 0)
                        {
                            data[i].pv_status = '<span class="label label-success" value="' + status + '">正常</span>';
                        }
                        else if(status == 1)
                        {
                            data[i].pv_status = '<span class="label label-danger" value="' + status + '">文件不存在</span>';
                        }
                        else if(status == 2)
                        {

                            data[i].pv_status = '<span class="label label-warning" value="' + status + '">存在BUG</span>';
                        }
                        else
                        {
                            data[i].pv_status = '<span class="label label-default" value="' + status + '">未知异常</span>';
                        }
                    }
                    return data;
                }
            },
            "drawCallback": function()
            {
                $('a[data-toggle=popover]').popover({html: true});
            }
        });
        $('#vcs-table tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('info') ) {
                $(this).removeClass('info');
            }
            else {
                table.$('tr.info').removeClass('info');
                $(this).addClass('info');
            }
        } );
        
        $('#sign-bug-btn').click(function(){
            var data = table.rows('.info').data();
            var length = data.length;
            if(length === 0)
            {
                alert('未选中版本');
                return false;
            }
            console.log(data);
            $('#bug-comment').val(data[0].desc);
            $('#bug_version').val(data[0].pv_v);
            $('#bug-comment').data('version', data[0].pv_v);
        });
        
    }
    function add_new_version()
    {
        var comment = $("#new-version-comment").val();
        if(comment.length === 0)
        {
            $("#new-version-form-group").addClass('has-error');
            $("#new-version-comment-label").text('注释内容不能为空');
            return false;
        }
        
        
        console.log($("#add-new-version"));
        $('#add-new-version').modal('hide');
        $("#img-run-dialog").modal('show');
        
        $.post(
            '<?= Yii::$app->urlManager->createUrl(array('admin/boper/newbvsn', "id" => $id)) ?>',
            {comment: comment},
            function(data){
                if(data.ret == 0)
                {
                    alert(data.msg);
                    location.reload();
                }
                else
                {
                    $("#img-run-dialog").modal('hide');
                    alert(data.msg);
                }
            },
            "json"
        );
        return false;
    }
    function sign_vcs_bug()
    {
        var comment = $("#bug-comment").val();
        if(comment.length === 0)
        {
            $("#bug-form-group").addClass('has-error');
            $("#bug-comment-label").text('注释内容不能为空');
            return false;
        }
        $.post(
            '<?= Yii::$app->urlManager->createUrl(array('admin/boper/upbvsn', "id" => $id)) ?>',
            {comment: comment,version:$('#bug-comment').data('version')},
            function(data){
                if(data.ret == 0)
                {
                    location.reload();
                }
                else
                {
                    alert(data.info);
                }
            },
            "json"
        );
        return false;
    }
    
    function version_status(){
        $.ajax({
            url :"<?= Yii::$app->urlManager->createUrl(array('admin/boper/versionst', "id" => $id)) ?>",
            type:"post",
            dataType:"json",
            success: function (msg){
                if(msg.v_status==1) {
                    var btn_group = $("#btn_new_vn");
                    
                    var htmlstr = "<button type='button' class='btn btn-warning' >版本生成中</button>";
                    btn_group.html("");
                    btn_group.html(htmlstr);
                    return;
                }else{
                    var btn_group = $("#btn_new_vn");
                    var htmlstr = "<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#add-new-version'>生成新版本</button>";
                    btn_group.html("");
                    btn_group.html(htmlstr);
                    return;
                }
            }
        });
    }
    
    $(document).ready(function(){
        init_vcs_table();
        setInterval(version_status,3000);
        
    });
</script>
