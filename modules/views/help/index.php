<div class="container-fluid">
        <div class="row">
            <?php echo $this->render("_sidebar",array("active"=>$active ))?>
            <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#deploy" role="tab" data-toggle="tab">项目名称</a>
                    </li>
                    
                    <li>
                        <a href="#vcs" role="tab" data-toggle="tab">项目服务器配置</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="deploy">
                        <?php echo $this->render('deploy');?>
                    </div>
                    
                    <div class="tab-pane" id="vcs">
                        <?php echo $this->render('vcs',array("datas"=>$datas,"apsarr"=>$ipsarr));?>
                    </div>
                </div>
                
            </div>
        </div>
    </div>