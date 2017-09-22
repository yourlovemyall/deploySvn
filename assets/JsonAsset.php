<?php
namespace app\assets;
/*
 * @uses: echo json 
 */

class JsonAsset {
    //put your code here
    
    public static function encode ($arr) {
        return json_encode(array(
            "rs"=>$arr['rs'],
            "info" =>$arr['info']
        ));
    }
}
