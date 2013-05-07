<?php
    require_once('class/Conexao.class.php'); 
    require('class/Usuario.class.php');        
    Conexao::conexao();      
    $objUsuario = new Usuario();
    $objFoto = new Foto();        
    
    session_start();   
     if (!isset($_SESSION['usuario'])) {  // Se não estiver logado, redireciona para pagina de login      
         echo "<script>location.href = 'index.php';</script>";
     }
    $username = $_SESSION['usuario'];    
    $dadosUsuario = $objUsuario->dadosUsuario($username);
    
    $fotoUltimoID = $objFoto->selecionarUltimoID($dadosUsuario[0]);    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="css/geral.css" type="text/css" rel="stylesheet">
        <link href="css/jPages.css" type="text/css" rel="stylesheet">        
        
        <script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="js/jPages.min.js"></script>        
        <script type="text/javascript" src="js/jsExterno.js"></script>        
        <title></title>
    </head>
    <body>
        <div class="header">
            <img src="img/logo.gif" class="logo"/>
            <a href="index.php">Sair</a>
        </div>
        <div id="content">            
            <div id="perfil">                
                <?php 
                    if(file_exists('img/'.$username.'/'.$username.'.jpg')){
                        printf('<img src="img/'.$username.'/'.$username.'.jpg" style="max-width:100px; max-height:100px;"/>'); // Foto do perfil do usuario
                    } else {
                        printf('<img src="img/default.jpg" style="max-width:100px; max-height:100px;" />'); // Caso usuario não possua foto, usa default
                    } 
                    echo '<br /> <h1>'.$dadosUsuario[1].'</h1>';                                        
                ?>                     
            </div>            
            <div id="upload">
                <?php 
                if(isset($_POST['acao']) && $_POST['acao'] == 'enviar'){                           
                    $permitido = array('image/jpg','image/jpeg','image/pjpeg'); // Formatos aceitos para upload.
                    
                    $img = $_FILES['img'];
                    $tmp = $img['tmp_name'];
                    $name = $img['name'];
                    $type = $img['type'];                                
                    $nome_foto = $username.'_'.($fotoUltimoID+1).'.jpg';
                    $pasta = 'img/'.$username;                        
                    $descricao = strip_tags(filter_input(INPUT_POST, 'descricao')); 
                    $dados = array($descricao, $dadosUsuario[0], $nome_foto);
                    
                    if(!empty($name) && in_array($type,$permitido)){
                        if($objFoto->cadastrarFoto($dados)){    // Cadastra nome da foto no banco
                            $objUsuario->upload($tmp, $nome_foto , 600, $pasta); // Faz upload da foto para pasta                                                                                     
                            echo "<script>location.href = 'foto.php?foto=$nome_foto';</script>"; // Mostra página da foto                                                   
                        } else {
                            echo '<script> alert("Erro ao cadastrar foto.")</script>';
                        }
                    } else {
                        echo "<script> alert('Tipo de arquivo inválido. Envie JPEG!');</script>";                    
                    }                        
                }
                ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <a href="#" id="mostrar">
                        Envie uma foto
                    </a>
                    <div id="enviar_foto">
                        <span><input type="file" name="img" /></span>
                        <br/>
                        <label> 
                            <span> Descrição </span><br/>
                            <textarea name="descricao" class="textarea"/></textarea>
                        </label>
                        <input type="hidden" name="acao" value="enviar" />
                        <input type="submit" value="Enviar" class="btn"/> 
                        <input type="button" value="Cancelar" id="ocultar" class="btn"/>                                                                                             
                    </div>
                </form>
            </div>
            <div id="galeria">
                <div class="holder">
                </div>
                <ul id="itemContainer">
                <?php 
                    $arquivos = glob('img/'.$username.'/*.*');  // Pega todas as fotos de dentro da pasta
                    foreach($arquivos as $valor){                        
                        $nome_foto_min = str_replace('img/'.$username.'/', '', $valor); // Remove o começo do caminho, ficando apenas o nome da foto                     
                        if(file_exists($valor)){                            
                            echo '<li><a href="foto.php?foto='.$nome_foto_min.'"><img src="'.$valor.'" class="imgMin" /></a></li>'; // Cria imagens miniatura com links                               
                        }                        
                    }                    
                ?>
                </ul>                           
        </div>    
    </body>
</html>