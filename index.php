<?php
    require_once('class/Conexao.class.php'); 
    require('class/Usuario.class.php');        
    Conexao::conexao();
    
    ob_start();
    if (isset($_SESSION['usuario'])) {        
        unset($_SESSION['usuario']);
    }        
    // inicia o manipulador de sessão
    session_start();   

    // elimina todas as informações relacionadas à sessão atual
    session_destroy();

    // encerra o manipulador de sessão
    session_write_close();

    $objLogin = new Login();       
    
    ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="css/geral.css" />
        <title></title>
    </head>
    <body>
        <div class="header">
            <img src="img/logo.gif" class="logo"/>
        </div>
        <div id="login">
            <?php 
                if(isset($_POST['acao']) && $_POST['acao'] == 'entrar'){                  
                  $username = strip_tags(filter_input(INPUT_POST, 'username'));
                  $senha = strip_tags(filter_input(INPUT_POST, 'senha')); 
                  
                  if($username == '' || $senha == '') { // Validação de Preenchimento
                      echo '<script> alert("Preencha todos os campos!")</script>';
                  } else {
                      if($objLogin->entrar($username, $senha)){ // Login Correto
                            session_start();                          
                            $_SESSION['usuario'] = $username;                                                                                  
                            header("Location: home.php");                                                                              
                      } else { 
                          echo '<script> alert("Login ou senha inválida!");</script>'; // Login Incorreto
                      }
                  }             
                }
              ?>                        
            <form action="" method="post" enctype="multipart/form-data">                
                <label> 
                    <span> Username ou E-mail </span><br/>
                    <input type="text" name="username" class="text"/><br/>
                </label>
                <label> 
                    <span> Senha </span><br/>
                    <input type="password" name="senha" class="text"/><br/>
                </label>
                <input type="hidden" name="acao" value="entrar" />
                <input type="submit" value="Entrar" style="float:right;" class="btn"/> <br />
                <a href="cadastro_usuario.php" class="link2"> Não possui uma conta? Cadastre-se! </a>
                
            </form>
        </div>        
    </body>
</html>
