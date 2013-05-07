<?php    
    require_once('class/Conexao.class.php'); 
    require('class/Usuario.class.php');        
    Conexao::conexao();          
    $objFoto = new Foto();
    session_start();   
    
    if (!isset($_SESSION['usuario'])) {  // Se não estiver logado, redireciona para pagina de login      
         echo "<script>location.href = 'index.php';</script>";
     }
    
    $username = $_SESSION['usuario'];        
    $foto = $_GET['foto'];
        
    $dadosFoto = $objFoto->dadosFotoNome($foto);        
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
        <div id="foto">     
            <a href="home.php" class="link2"><< Voltar</a><br/>
            <?php
               echo "<img src='img/".$username."/".$foto."' class='img' /> <br /><span>" . $dadosFoto[2] . "</span>"; // Foto Exibida
            ?>

            <div class="holder2">
            </div>
            <ul id="itemContainer">
            <?php 
                $arquivos = glob('img/'.$username.'/*.*'); // Pega todas as fotos de dentro da pasta
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
