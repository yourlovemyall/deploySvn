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
                    <span id="btn_new_vn"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-new-server">添加服务器</button></span>
                </div>
            </p>
            <div class="modal fade" id="add-new-server" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title">添加服务器</h4>
                        </div>
                        <div class="modal-body">
                            <?php $form=ActiveForm::begin(['id'=>'server-form','enableAjaxValidation'=>true,'action'=>false]); ?>
                                <br/>
                                <?=$form->field($model,'si_local_ip')->textInput(["placeholder"=>"ip"])->label('内网IP'); ?>
                                <?=$form->field($model,'si_remote_ip')->textInput(['placeholder'=>'ip'])->label("外网IP"); ?>
                                <?=$form->field($model,'si_name')->textInput(['placeholder'=>'salt别称'])->label("salt别称"); ?>
                                <?=$form->field($model,'si_backup')->textInput(['placeholder'=>'别名'])->label("别名"); ?>

                                <?php ActiveForm::end()?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="add_new_server();">保存</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="modal-img" id="onloading"  style="width: 216px; height:15px; margin: 129px auto; display: none;">
                    <img src='/Images/run_01.gif' alt='onloading'/>
                </div>
                <table class="table table-striped table-bordered table-responsive" id="vcs-table">
                    
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function add_new_server () {
        $.ajax({
            url : "<?php echo Yii::$app->urlManager->createUrl("admin/help/addip")?>",
            data : $("#server-form").serialize(),
            type : "post",
            dataType: "json",
            success : function (msg){
                if(msg.rs==='success') {
                    alert(msg.info);
                    window.location.reload();
                }else{
                    alert(msg.info);
                    $("#add-new-server").modal('hide');
                }
            }
        });
    };
    function init_vcs_table()
    {
        var table = $('#vcs-table').DataTable({
            "columns": [
                {"title": "ID", "data": "si_id",type:"num"},
                {"title": "内网IP", "data": "si_local_ip"},
                {"title": "外网IP","data":'si_remote_ip'},
                {"title": "salt别称", "data": "si_name"},
                {"title": "别名", "data": "si_backup"},
            ],
            "order": [[0, "desc"]],
            "ajax": {
                "url": "<?= Yii::$app->urlManager->createUrl(array('admin/help/jsonips')) ?>",
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
    
    $(document).ready(function(){
        init_vcs_table();
    });
</script>

