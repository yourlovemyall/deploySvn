<?php
namespace yii\helpers;

class Cmd {
    //put your code here
    static $root = "/data/source";
    static $v_root = "/data/salt";
    static $r_root = "/data/zip";
    
    static $s_root = "/data/install";
    static $sys_root = "/data/web";

    public static function create_version($sys_name="jfzoss",$version){
        $log_dir =  self::$root ."/logs/$sys_name"."_version/";
        if(!file_exists($log_dir))      self::mkdirs($log_dir);
        $svn_cmd = "cd " . self::$root .'/'. $sys_name . "_version/$sys_name".";export LANG=zh_CN.UTF-8; svn up --username=read  --password=readjinfuzi2015 --no-auth-cache 2>&1";
        exec($svn_cmd, $output, $return_var);
        if($return_var != 0)
        {
            self::writelog($log_dir,array('svn output:',$output) );
            return array("pv_name"=>0,"data"=> "$sys_name" . $version . " svn up failed");
        }
        unset($output);
//        $svn_version = "cd " . self::$root .'/'. $sys_name . "_version/$sys_name". "; svn info | awk '/^Revision:/{print $2}';";
        exec($svn_cmd, $output, $return_var);
        
        if($return_var != 0)
        {
            self::writelog($log_dir,array('svn output:',$output));
            return array("pv_name"=>0,"data"=> "$sys_name" . $version . "get svn version failed");
        }
        $reversion = preg_replace('/[^(\d+)]/','', $output[0]);
        
        $file_name = $sys_name."_" . $version . ".zip";
        $v_file  = self::$v_root."/".$sys_name."_version";
        if(!file_exists($v_file))       self::mkdirs($v_file);
        $full_name = self::$v_root ."/$sys_name"."_version/$file_name";
        
        $v_file = self::$root."/".$sys_name."_version".'/'.$sys_name."/VERSION";
        
        file_put_contents($v_file,$version);      
        $zip_cmd = "cd ".self::$root."/$sys_name".'_version'." ; zip -r $full_name $sys_name  install.sh -x '*/.svn/*' -x '*/runtime/*'";
        exec($zip_cmd, $output, $return_var);
        if($return_var != 0)
        {
            self::writelog($log_dir,array('zip output:',$output));
            return array("pv_name"=>0,"data"=>"$sys_name" . $version . " zip $file_name failed");
        }
        
        $file_info = array(
            'pv_name'               => $file_name,
            "pv_v"                  => $version,
            "pv_svn_version"        => $reversion,
            'pv_mtime'              => date("Y-m-d H:i:s",filemtime($full_name)),
            'pv_create_time'        => date('Y-m-d H:i:s'),
            'pv_md5'                => md5_file($full_name),
            'pv_size'               => sprintf("%.2f", filesize($full_name)/(1024*1024)),
            'pv_status'             =>0,
        );
        return $file_info;
    }
    
    public static function publish_version($sys_name="jfz",$version,$ips) {
        $log_dir =  self::$root ."/logs/$sys_name"."_version/publish/";
        if(!file_exists($log_dir))      self::mkdirs($log_dir);
        $v_zip  = self::$v_root ."/$sys_name"."_version/$sys_name"."_".$version.".zip";
        if(!file_exists($v_zip)){
            self::writelog($log_dir,array("$sys_name" . $version . ' file not exist'));
            return array("ret"=>0,"data"=>"$sys_name" . $version . " file not exist");
        }
        $server_names = implode(",", $ips);

	$remote_dir = self::$r_root."/".$sys_name."_".$version;
        $mk_cmd ="mkdir -p $remote_dir";

        $mkdir_cmd = "salt -L '".$server_names."' cmd.run_all '{$mk_cmd}'";
	exec($mkdir_cmd,$output,$return_var);
        self::writelog($log_dir, $output);
        $salt_cmd = "salt -L '".$server_names."' cp.get_file salt://$sys_name"."_version/"."$sys_name"."_".$version.".zip  ".$remote_dir."/$sys_name"."_".$version.".zip";
        exec($salt_cmd, $output, $return_var);
        $str_out = '';
        foreach ($output as $i=>$val){
            if (strpos($val,'-'))    continue;
            if ($i>=10)	continue;
	    $str_out.= $val."\r\n";
	}
        $arr_out = yaml_parse($str_out);
        self::writelog($log_dir,$arr_out);
	foreach($arr_out as $key=>$val){
	    if($val['retcode'] !=0){
	        self::writelog($log_dir,array($key.': salt output:failed'));
                return array("ret"=>0,"data"=>'salt:'.$key.':'.$val['stderr']. " salt failed");
	    }
	}
        
        unset($str_out);
        
        $cmd = "cd ".$remote_dir.";export LC_ALL=en_US.UTF-8;  unzip ".$sys_name."_".$version.".zip ; ./install.sh";
        $unzip_cmd = "salt -L '".$server_names."' cmd.run_all  '{$cmd}'";
        exec($unzip_cmd,$output,$return_var);
        foreach ($output as $i=>$val){   
	    if (strpos($val,'-'))    continue;
            if ($i>=10)	continue;
	    $str_out .=$val."\r\n";
	}
        $ret_arr = yaml_parse($str_out) ;
        foreach ($ret_arr as $key=>$val){
            if ($val['retcode'] !=0) {
                self::writelog($log_dir,array($key.': salt output:' , $output));
                return array("ret"=>0,"data"=>'salt:'.$key.':'.$sys_name . $version . " salt install failed");
            }
        }
        unset($ret_arr);
        
        return array("ret"=>1,"data"=>"");
    }
    
    /**
     * @uses:back version
     */
    public static function back_version($sys_name="jfz",$version,$ips,$sys_call = "jfz") {
        $log_dir =  self::$root ."/logs/$sys_name"."_version/backversion/";
        if(!file_exists($log_dir))      self::mkdirs($log_dir);
        $output = array();
        $server_names = implode(",", $ips);
        
        $shell_cmd ='ls ' .self::$s_root."/".$sys_call."/".$sys_name.'-'.$version;
        
        $isdir_cmd = "salt -L '".$server_names."' cmd.run_all '{$shell_cmd}'";
        exec($isdir_cmd,$output,$return_var);
        
        foreach ($output as $i=>$val){   
	    if (strpos($val,'-'))    continue;
            if ($i>=10)	continue;
	    $str_out .=$val."\r\n";
	}
        $ret_arr = yaml_parse($str_out) ;
        self::writelog($log_dir, $output);
        if(empty($ret_arr))            return   array("ret"=>0,"data"=>"SALT没有验证到系统版本，请单台发布");
        foreach ($ret_arr as $key=>$val){
            if ($val['retcode'] !=0 ) {
                return self::publish_version($sys_name,$version,$ips);
            }
        }
        unset($ret_arr);
        unset($output);
        
        $sh_cmd = "rm ".self::$sys_root.'/'.$sys_name .' -rf && ln -s '.self::$s_root."/".$sys_call."/".$sys_name.'-'.$version." ".self::$sys_root.'/'.$sys_name ."&& kill -USR2 `cat /usr/local/webserver/php-5.4.27/var/run/php-fpm.pid`";
        $link_cmd = "salt -L '".$server_names."' cmd.run_all '{$sh_cmd}'";
        exec($link_cmd,$output,$return_var);
        foreach ($output as $i=>$val){   
	    if (strpos($val,'-'))    continue;
            if ($i>=10)	continue;
	    $str_out .=$val."\r\n";
	}
        $ret_arr = yaml_parse($str_out) ;
        foreach ($ret_arr as $key=>$val){
            if ($val['retcode'] !=0) {
                self::writelog($log_dir,array($key.': salt output:',$output));
                return array("ret"=>0,"data"=>'salt:'.$key.':'.$sys_name . $version . " back system fail");
            }
        }
        unset($ret_arr);
        unset($output);
        return array("ret"=>1,"data"=>"");
        
    }
    
    protected static function writelog($dir,$result,$type =TRUE){
        $mode = '';
        if ($type ==false){
            $logfile = $dir;
            $mode = "w";
        }else{
            $logfile =$dir.'/'.date("Ymd").'.log';
            $mode = "a";
        }
        $handle = fopen($logfile,"{$mode}+");
        $ret = self::arrsToArr($result);
        foreach($ret as $val){
            fwrite($handle,date("Y-m-d H:i:s").$val."\r\n");
        }
        
        fclose($handle);
    }
    /**
     * @uses: arrays To array
     */
    protected static function arrsToArr($arr){
        static $datas = array();
        if (is_array($arr)){
            foreach ($arr as $val){
                if (!is_array($val)) {
                    array_push($datas, $val);
                }else{
                    self::arrsToArr($val);
                }
            }
        }else{
            return $arr;
        }
        return $datas;
    }

    protected static function mkdirs($dir){
        return is_dir($dir) or (self::mkdirs(dirname($dir)) and mkdir($dir,0777));
    }
}
