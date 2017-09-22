<?php
/*
 * @uses:help center
 * @time:2015-04-09
 */
namespace app\modules\controllers;
use Yii;
use app\assets\JsonAsset;
use app\models\YiiSystem;
use app\models\YiiServer;
use app\models\YiiServerIps;
use app\models\YiiSpublish;
use app\models\YiiGroup;
use app\models\YiiUser;
use yii\web\Controller;
use yii\web\session;
use yii\data\Pagination;
use yii\filters\AccessControl;
class HelpController extends Controller{
    //put your code here
    public $layout="layout";
    public $active =3;
    public $enableCsrfValidation = false; 
    /*
     * @uses:authorze
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index','addsystem',"addserver","addserverips","serverips","addip","org","adduser","roles","addroles","roleslist","permission"
                            ,"svnsets",'addsvncmd','jsonips','jsonsvns','delsvncmd','infosvncmd','serverlist'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    /*
     * @uses:view
     */
    public function actionIndex() {
        
        $res = YiiSystem::find()->orderBy('id asc')->all();
        
        $ipsArr = YiiServerIps::find()->asArray()->all();
        
        $datas = array();
        foreach ($res as $val){
            $datas[]=array(
                "id"=>$val->id,
                "sysname"=>$val->sysname
            );
        }
//        $this->mid = $id;
        return $this->render("index",array("active"=>"busyconf","datas"=>$datas,"ipsarr"=>$ipsArr));
    }
    
    /*
     * @uses:add system datas
     */
    public function actionAddsystem() {
        $model = new YiiSystem();
        
        if ($model->load(Yii::$app->request->post())&& $model->validate()) {
            $datas = Yii::$app->request->post();
            $model->sysname = $datas['YiiSystem']['sysname'];
            $model->ename = $datas['YiiSystem']['ename'];
            $model->ins_name = $datas['YiiSystem']['ins_name'];
            if($model->save()){ 
                Yii::$app->session->setFlash('success_sys','save success！');
            }else{
                Yii::$app->session->setFlash('error_sys','save fail！');
            }
        }
        return $this->redirect("index");
    }
    
    /*
     * @uses: add server datas of system
     */
    public function actionAddserver(){
        header("content-type:text/html;charset=utf-8");
        $model = new YiiServer();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $datas = Yii::$app->request->post('YiiServer');
            
            if ($model->find()->where(array("sys_id"=>$datas['sys_id'],"si_id"=>$datas['si_id']))->one()){
                echo JsonAsset::encode(array("rs"=>"fail","info"=>"fail"));
                exit();
            }  
            foreach ($datas as $k=>$val){
                $model->$k=$val;
            }
            if($model->save()) {
                $sb_obj = new YiiSpublish();
                $datas = array(
                    "sys_id"=>$datas['sys_id'],
                    "ser_id"=>$datas['si_id'],
                    "pv_v"  =>0,
                    "sp_uid"=>Yii::$app->user->getId(),
                    "sp_creater" =>Yii::$app->user->identity->user,
                    "sp_status" =>1,
                    "sp_create_time" =>date("Y-m-d H:i:s")
                );
                foreach($datas as $key=>$val){
                    $sb_obj->$key = $val;
                }
                $sb_obj->save();
                echo JsonAsset::encode(array("rs"=>"success","info"=>"{$datas['sys_id']}"));
                exit();
            }else{
                echo JsonAsset::encode(array("rs"=>"fail","info"=>"fail"));
                exit();
            }
        }
    }
    
    /**
     * @uses: 显示没有添加过的servers
     */
    public function actionServerlist() {
        $model = new YiiServer();
        $mserverips = new YiiServerIps();
        if(Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $temp = $model->find()->where(array("sys_id"=>$id))->asArray()->all();
            $serverids = array();
            foreach( $temp as $v){
                $serverids[] = $v['si_id'];
            }
            if(empty($serverids)) 
                $datas = $mserverips->find()->asArray()->all();
            else{
                $datas = $mserverips->find()->where(['not in','si_id',$serverids])->asArray()->all();
            }
//            print_r($datas);exit;
            $html ="";
            foreach($datas as $val){
                $html .= ' <label class="btn btn-default">';
                $html .="<input type='radio' name='YiiServer[si_id]'  value=\"{$val['si_id']}\" /> {$val['si_name']} ";
                $html .="</label>";
            }
            echo $html;
        }
    }
    
    /*
     * @uses:add server_ips
     */
    public function actionAddserverips() {
        $model = new YiiServerIps();
        if ($model->load(Yii::$app->request->post())&& $model->validate()) {
            $datas = Yii::$app->request->post();
            $model->sysname = $datas['YiiSystem']['sysname'];
            $model->ename = $datas['YiiSystem']['ename'];
            if($model->save()) 
                Yii::$app->session->setFlash('success_sys','save success！');
            else
                Yii::$app->session->setFlash('error_sys','save fail！');
        }
        return $this->redirect("index");
    }
    
    /*
     * @uses:servers list
     */
    public function actionServerips() {

        return $this->render("serverips",array("active"=>"server_ip","model"=>new YiiServerIps()));
    }
    
    public function actionJsonips() {
        
        $res = YiiServerIps::find()->asArray()->all();
        echo json_encode(array("ret"=>1,"data"=>$res));
        Yii::$app->end();
    }

    /*
     * @uses: add server
     */
    public function actionAddip() {
        $model = new YiiServerIps();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $datas = Yii::$app->request->post('YiiServerIps');
            foreach ($datas as $k=>$val){
                $model->$k = $val;
            }
            if($model->save()) {
                echo JsonAsset::encode(array("rs"=>"success","info"=>"success"));
                exit();
            }else{
                echo JsonAsset::encode(array("rs"=>"fail","info"=>"fail"));
                exit();
            }
        }
    }
    
    /*
     * @uses: user list
     */
    public function actionOrg($page=null) {
        $model = new YiiUser();
        $page =$page==null ? 1 : intval($page);
        $pagesize = 10;
        $offset = ($page-1)*$pagesize;
        $query = YiiUser::find();
        $models = $query->offset($offset)
            ->limit($pagesize)
            ->all();
        $pagination = new Pagination(['totalCount' => $query->count(), 'pageSize'=>$pagesize]);
        $groups = YiiGroup::find()->asArray()->all();
        return $this->render("org",array("active"=>"org","pagination"=>$pagination,"models"=>$models,"model"=>$model,"groups"=>$groups));
    }
    
    /*
     * @uses: add user
     */
    public function actionAdduser() {
        $model = new YiiUser();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $datas = Yii::$app->request->post('YiiUser');
            if (YiiUser::findBySql("select * from {{%user}} where user = '{$datas['user']}'")->asArray()->one() || YiiUser::findBySql("select * from {{%user}} where user = '{$datas['email']}'")->asArray()->one()){
                echo JsonAsset::encode(array("rs"=>"fail","info"=>"用户名或邮箱已存在"));
                exit;
            }
            $datas['pwd'] = md5('1qaz2wsx');
            $datas['authKey'] = "test100key";
            $datas['accessToken'] ='100-token';
            $datas['created_at'] = time();     
            foreach ($datas as $k=>$val){
                $model->$k = $val;
            }
            if($model->save()) {
                echo JsonAsset::encode(array("rs"=>"success","info"=>"success"));
                exit();
            }else{
                echo JsonAsset::encode(array("rs"=>"fail","info"=>"fail"));
                exit();
            }
        }
    }
    
    /**
     * @uses: role list
     */
    public function actionRoles($page=null){
        $model = new YiiGroup();
        $page =$page==null ? 1 : intval($page);
        $pagesize = 10;
        $offset = ($page-1)*$pagesize;
        $query = YiiGroup::find();
        $models = $query->offset($offset)
            ->limit($pagesize)
            ->all();
        $pagination = new Pagination(['totalCount' => $query->count(), 'pageSize'=>$pagesize]);
        return $this->render("roles",array("active"=>"roles","pagination"=>$pagination,"models"=>$models,"model"=>$model));
    }
    
    /**
     * @uses: add role
     */
    public function actionAddroles() {
        $model = new YiiGroup();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $datas = Yii::$app->request->post('YiiGroup');
            foreach ($datas as $k=>$val){
                $model->$k = $val;
            }
            if($model->save()) {
                echo JsonAsset::encode(array("rs"=>"success","info"=>"success"));
                exit();
            }else{
                echo JsonAsset::encode(array("rs"=>"fail","info"=>"fail"));
                exit();
            }
        }
    }
    
    /**
     * @uses: permission roles list
     */
    public function actionRoleslist() {
        $this->layout="base";
        $datas = Yii::$app->request->post();
        
//        $gmenus = \app\models\YiiGmenus::find()->where(array("g_id"=>$datas['gid']))->asArray()->all();
        
        $menus = \app\models\YiiMenus::find()->where(array("m_fid"=>0))->asArray()->all();
        
        $temp = array();
        foreach ($menus as $val){
            $temp[$val['mid']] =$val;
            $temp[$val['mid']]['children'] = \app\models\YiiMenus::find()->where(array("m_fid"=>$val['mid']))->asArray()->all();
        }
        
        $sys   = YiiSystem::find()->asArray()->all();
        
        return $this->render("roleslist",array("datas"=>$datas,"menus"=>$temp,"sys"=>$sys));
    }
    
    /**
     * @uses: do permission
     */
    public function actionPermission() {
        $datas = Yii::$app->request->post();
        if ($datas['gid'] ==NULL){
            echo \app\assets\JsonAsset::encode(array("rs"=>"fail","info"=>"permiss fail"));
            exit();
        }
        if(!empty($datas['mids']) && $datas['gid']!=NULL) {
            foreach ($datas['mids'] as $v) {
                $model = new \app\models\YiiGmenus();
                $ret = $model->find()->where(array("m_id"=>$v,"g_id"=>$datas['gid'],"code"=>"menus"))->asArray()->one();
                if(!empty($ret))                   continue;
                $model->m_id = $v;
                $model->g_id = $datas['gid'];
                $model->code ="menus";
                $model->insert();
                unset($model);
            }
        }
        if(!empty($datas['sids']) && $datas['gid']!=NULL) {
            foreach ($datas['sids'] as $vs) {
                $model = new \app\models\YiiGmenus();
                $res = $model->find()->where(array("m_id"=>$vs,"g_id"=>$datas['gid'],"code"=>"sys"))->asArray()->one();
                if(!empty($res))                    continue;
                $model->m_id = $vs;
                $model->g_id = $datas['gid'];
                $model->code ="sys";
                $model->insert();
                unset($model);
            }
        }
        echo \app\assets\JsonAsset::encode(array("rs"=>"success","info"=>"success"));
        exit;
    }
    
    /**
     * @uses: svn list
     */
    public function actionSvnsets() {
        $model = new \app\models\YiiPublishSvn();
        
        $systems = YiiSystem::find()->asArray()->all();
        return $this->render("svnsets",array("active"=>"svnsets","model"=>$model,"systems"=>$systems));
//        return $this->render("svnsets",array("active"=>"svnsets","pagination"=>$pagination,"models"=>$models,"model"=>$model,"systems"=>$systems));
    }
    
    /**
     * @uses : ajax push datas
     */
    public function actionJsonsvns() {
        $model = new \app\models\YiiPublishSvn();
        $res = $model->find()->asArray()->all();
        $datas = array();
        foreach($res as $k=>$val){
            $datas[$k] = $val;
            $datas[$k]['sysname'] = YiiSystem::findOne($val['sys_id'])->sysname ;
            $datas[$k]['acts']    ="<a href='javascript:;' onclick='edit_svns(".$val['id'].")'>修改</a>&nbsp;&nbsp;<a href='javascript:;' onclick='del_svns(".$val['id'].")'>删除</a>";  
        }   
        echo json_encode(array("ret"=>1,"data"=>$datas));
        Yii::$app->end();
    }
    
    /**
     * @uses: add svn cmd
     */
    public function actionAddsvncmd() {
        $model = new \app\models\YiiPublishSvn();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            $datas = Yii::$app->request->post('YiiPublishSvn');
            foreach ($datas as $k=>$val){
                $model->$k = $val;
            }
            if(isset($model->id) && $model->id !=false) {
                $obj = $model->findOne($model->id);
                foreach ($datas as $k=>$val){
                    if($k =='id')                        continue;
                    $obj->$k = $val;
                }
                $res = $obj->save();
            }else
                $res = $model->save();
            if($res) {
                echo JsonAsset::encode(array("rs"=>"success","info"=>"success"));
                exit();
            }else{
                echo JsonAsset::encode(array("rs"=>"fail","info"=>"fail"));
                exit();
            }
        }
    }
    
    /**
     * @uses: del svn cmd
     */
    public function actionDelsvncmd() {
        $model = new \app\models\YiiPublishSvn();
        if (Yii::$app->request->isAjax ) {
            $id = Yii::$app->request->post('id');
            $res = $model->findOne($id);
            if($res->delete()){
                echo JsonAsset::encode(array("rs"=>"success","info"=>"success"));
                exit();
            }else{
                echo JsonAsset::encode(array("rs"=>"fail","info"=>"fail"));
                exit();
            }
        }
    }
    
    /**
     * @uses: svn cmd info
     */
    public function actionInfosvncmd() {
        $this->layout = 'base';
        $model = new \app\models\YiiPublishSvn();
        if(Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            $res = $model->findOne($id);
        }
        $systems = YiiSystem::find()->asArray()->all();
        return $this->render('_svncmdinfo',array("res"=>$res,"model"=>$model,"systems"=>$systems));
    }
}
