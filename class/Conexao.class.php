<?php        
class Conexao
{
    private static $conexao;
    public function __construct() {}
    
    public static function conexao(){
        if(is_null(self::$conexao)){
            self::$conexao = new PDO('mysql:host=localhost;dbname=mkt_instagram;', 'root','');            
        }
        return self::$conexao;
    }
    
}   
    
?>