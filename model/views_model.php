<?php
class viewModel{
    protected static function get_view($view){
        $white_list = ["products", "new-products","edit-products", "users","new-user","edit-user","category","new-category","cliente","new-cliente","proveedor","new-proveedor","home"];
        if (in_array($view, $white_list)) {
            if (is_file("./view/".$view.".php")) {
                $content = "./view/".$view.".php";
            }else{
                $content = "404";
            }
            
        }elseif($view == "login"){ 
            $content = "login";
        }else{
            $content = "404";
        }
        return $content;
    }
}
