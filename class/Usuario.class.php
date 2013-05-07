<?php
    class Usuario extends Conexao{
        
        private function criptografar($senha){
            return md5($senha);            
        }
        
        private function existeUsuario($email, $username){
            $selecionar = self::conexao()->prepare("SELECT * FROM usuarios WHERE email = '$email' OR username = '$username'");
            $selecionar->execute();
            
            if($selecionar->rowCount()>=1){
                return true;
            } else {
                return false;
            }
        }
        
        public function cadastrarUser($dados = array()){
            
            if($this->existeUsuario($dados[1], $dados[2])) {
                return false;
            } else {    
                $dados[3] = $this->criptografar($dados[3]);
                $sqlInserir = "INSERT INTO usuarios (nome, email, username, senha, foto) VALUES(?,?,?,?,?)";
                $stmt = self::conexao()->prepare($sqlInserir);
                if($stmt->execute($dados)){
                    return true;
                } else {
                    return false;
                } 
            }// Fim da Verificaçao de usuario existente
        } //Fim função Cadastra
        
        public function upload($tmp, $nome, $largura, $pasta){
            $foto = imagecreatefromjpeg($tmp);
            $x = imagesx($foto);
            $y = imagesy($foto);
            $altura = ($largura * $y) / $x;
            
            $novaimg = imagecreatetruecolor($largura, $altura);
            imagecopyresampled($novaimg,$foto, 0, 0, 0, 0, $largura, $altura, $x, $y);
            imagejpeg($novaimg, "$pasta/$nome");
            imagedestroy($novaimg);
            imagedestroy($foto);
            return $nome;                        
        }
        
        public function dadosUsuario($username) {
            $sqlBuscar = "SELECT * FROM usuarios WHERE username = '$username'";
                $stmt = self::conexao()->prepare($sqlBuscar);
                $stmt->execute();
                $row = $stmt->fetch();                 
                return $row;                                                 
        }
        
        public function sair() {

            //se sair destruir a sessao                
            if (isset($_SESSION['usuario'])) {        
                unset($_SESSION['usuario']);
            }        
            session_unset();
            session_destroy();                
        }
        
    }
    
    class Login extends Conexao{
        public function entrar($username, $senha){
            $selecionar = self::conexao()->prepare("SELECT * FROM usuarios WHERE (username = '$username' AND senha = md5('$senha')) OR (email = '$username' AND senha = md5('$senha'))");
            $selecionar->execute();
            
            if($selecionar->rowCount()==1){
                return true;
            } else {
                return false;
            }            
        }
        
    }
    
    class Foto extends Conexao{
        public function dadosFoto($id_usuario) {
            $sqlBuscar = "SELECT * FROM foto WHERE id_usuario = '$id_usuario'";
            $stmt = self::conexao()->prepare($sqlBuscar);
            $stmt->execute();
            $row = $stmt->fetch();                 
            return $row;                                                 
        }
        
        public function dadosFotoNome($nome_foto) {
            $sqlBuscar = "SELECT * FROM foto WHERE nm_foto = '$nome_foto'";
            $stmt = self::conexao()->prepare($sqlBuscar);
            $stmt->execute();
            $row = $stmt->fetch();                 
            return $row;                                                 
        }
        
        public function cadastrarFoto($dados = array()){            
                
                $sqlInserir = "INSERT INTO foto (dt_upload, ds_foto, id_usuario, nm_foto) VALUES(CURDATE(),?,?,?)";
                $stmt = self::conexao()->prepare($sqlInserir);
                if($stmt->execute($dados)){
                    return true;
                } else {
                    return false;
                } 
        } //Fim função Cadastra
        
        public function selecionarUltimoID($id_usuario){
                $sqlBuscar = "SELECT MAX(id_foto) FROM foto WHERE id_usuario = '$id_usuario'";
                $stmt = self::conexao()->prepare($sqlBuscar);
                $stmt->execute();
                $row = $stmt->fetch();                 
                return $row[0];  
        }
    }
?>