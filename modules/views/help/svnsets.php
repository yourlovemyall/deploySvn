<?php 
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
    
?>
<div class="container-fluid">
    <div class="row">
        <?php echo $this->render("_sidebar",array("active"=>$active ))?>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <p>
                <div class="btn-group">
                    <span id="btn_new_vn"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-new-user">添加操作</button></span>
                </div>
            </p>
            <div class="modal fade" id="add-new-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title">添加操作</h4>
                        </div>
                        <div class="modal-body">
                            <?php $form=ActiveForm::begin(['id'=>'server-form','enableAjaxValidation'=>true,'action'=>false]); ?>
                                
                                <?=$form->field($model,'id')->Input("hidden")->label(''); ?>
                                <?=$form->field($model,'ps_note')->textInput(["placeholder"=>"如 开发机发版"])->label('说明'); ?>
                                <?=$form->field($model,'ps_cmd')->textInput(['placeholder'=>'salt command'])->label("操作命令"); ?>
                                <label class="control-label" for="group">关联系统</label>
                                <div class="rowa">
                                        <div class="btn-group" data-toggle="buttons">
                                            <?php foreach($systems as $val){ ?>
                                                <label class="btn btn-default">
                                                    <input type="radio"  name="YiiPublishSvn[sys_id]" value="<?=$val['id']?>" /> <?=$val['sysname']?>
                                                </label> 
                                            <?php }?>
                                            
                                        </div>
                                </div>
                                
                                <?php ActiveForm::end()?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="add_publishsvn('server-form');">保存</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="cmdinfo_perms" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                
            </div>
            <div class="tab-content">
                <table class="table table-striped table-bordered table-responsive" id="vcs-table">
                    
                </table>
                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function add_publishsvn (f) {
        $.ajax({
            url : "<?php echo Yii::$app->urlManager->createUrl("admin/help/addsvncmd")?>",
            data : $("#"+f).serialize(),
            type : "post",
            dataType: "json",
            success : function (msg){
                if(msg.rs==='success') {
                    alert(msg.info);
                    window.location.reload();
                }else{
                    alert(msg.info);
                    $("#add-new-user").modal('hide');
                }
            }
        });
    };
    
    function init_vcs_table()
    {
        var table = $('#vcs-table').DataTable({
            "columns": [
                {"title": "ID", "data": "id",type:"num"},
                {"title": "说明", "data": "ps_note"},
                {"title": "命令","data":'ps_cmd'},
                {"title": "关联系统", "data": "sysname"},
                {"title": "系统操作", "data": "acts"},
            ],
            "order": [[0, "desc"]],
            "ajax": {
                "url": "<?= Yii::$app->urlManager->createUrl(array('admin/help/jsonsvns')) ?>",
                "dataSrc": function(json)
                {
                    if(json.ret != 1)
                    {
                        return false;
                    }
                    var data = json.data;
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
        
    }
    
    function edit_svns (id) {
        $.ajax({
            url:"<?php echo Yii::$app->urlManager->createUrl("admin/help/infosvncmd")?>",
            data: {id:id},
            type:"post",
            dataType:"html",
            success : function (msg){
                $("#cmdinfo_perms").html(msg);
                $("#cmdinfo_perms").modal('show');
            }
        });
    }
    
    function del_svns (id) {
        $.ajax({
            url:"<?php echo Yii::$app->urlManager->createUrl("admin/help/delsvncmd")?>",
            data: {id:id},
            type:"post",
            dataType:"json",
            success : function (msg){
                if(msg.rs==='success') {
                    alert(msg.info);
                    window.location.reload();
                }else{
                    alert(msg.info);
                }
            }
        });
    }
    
    
    $(document).ready(function(){
        init_vcs_table();
    });
</script>

