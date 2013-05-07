<?php 
    require_once('class/Conexao.class.php'); 
    require('class/Usuario.class.php');        
    Conexao::conexao();
    $objUsuario = new Usuario();    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link href="css/geral.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <?php 
        if(isset($_POST['acao']) && $_POST['acao'] == 'cadastrar'){
            $nome = strip_tags(filter_input(INPUT_POST, 'nome'));
            $email = strip_tags(filter_input(INPUT_POST, 'email'));
            $username = strip_tags(filter_input(INPUT_POST, 'username'));
            $senha = strip_tags(filter_input(INPUT_POST, 'senha')); 

            $pasta = 'img/'.$username;
            $permitido = array('image/jpg','image/jpeg','image/pjpeg');

            $img = $_FILES['img'];
            $tmp = $img['tmp_name'];
            $name = $img['name'];
            $type = $img['type'];                                
            $nome_foto = $username .'.jpg';

            $dados = array($nome, $email, $username, $senha, $nome_foto);          
            if($nome == '' || $email == '' || $username == '' || $senha == '') {
                echo '<script> alert("Preencha todos os campos!")</script>'; // Validação de Preenchimento
            } else {
                if(!empty($name) && in_array($type,$permitido)){ 
                    if($objUsuario->cadastrarUser($dados)){       
                        mkdir("./img/".$username);  // Cria pasta com as fotos do usuario
                        $objUsuario->upload($tmp, $nome_foto , 200, $pasta); // Faz o upload da foto                                                
                        echo '<script> alert("Usuario cadastrado com sucesso!");
                                       location.href = "index.php"</script>';
                    } else {
                        echo '<script> alert("Erro ao cadastrar usuario, ou já existente no banco")</script>';
                    }
                } else {
                    echo '<script> alert("Tipo de arquivo inválido. Envie JPEG!");</script>';                    
                }
            } 
          
        }
        ?>
        <div class="header">
            <img src="img/logo.gif" class="logo"/>
        </div>
        <div id="cadastro_usuario">
            <form action="" method="post" enctype="multipart/form-data">
                <label> 
                    Nome:<br/>
                    <input type="text" name="nome" class="text"/>
                </label>
                <label> 
                    Email:<br/>
                    <input type="text" name="email" class="text"/>
                </label>
                <label> 
                    Username:<br/>
                    <input type="text" name="username" class="text"/>
                </label>
                <label> 
                    Senha:<br/>
                    <input type="password" name="senha" class="text"/>
                </label>
                <label> 
                    Foto:<br/>
                    <input type="file" name="img" />
                </label>
                <input type="hidden" name="acao" value="cadastrar" />
                <input type="submit" value="Cadastrar" class="btn"/>                 
            </form>
        </div>
    </body>
</html>
