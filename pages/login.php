<?php
include_once '../crud/conexao.php';
session_start();
if(isset($_SESSION['id'])) {
    // se já estiver logado, redireciona para o painel  
    header('Location: painel.php');
}
$aviso = "";

if (isset($_POST['email'], $_POST['senha'])) {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_UNSAFE_RAW);

    //verificar se o campo email está em branco
    if (!$email) {
        echo "Preencha seu email";

    } else if (!$senha) {
        echo "Preencha sua senha";
    } else {

        $stm = $conexao->prepare("SELECT * FROM tb_usuarios WHERE email = :email");
        $stm->bindValue(":email", $email);
        $stm->execute();
        $usuario = $stm->fetch(PDO::FETCH_ASSOC);

        var_dump($usuario);
        if (password_verify($senha, $usuario['senha'])) {
            //se não existir uma sessão, inicia uma
            if (!isset($_SESSION)) {
                session_start();
            }

            //variável continuar válida, por um determinado período
            //de tempo mesmo quando a pessoa troca de pagina
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];

            //redirecionar  o usuario para outa pagina
            header("location: painel.php");

        } else {
            echo "Falha ao logar! E-mail ou senha incorretos";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Projeto 01 - Login</title>
	<script src="https://kit.fontawesome.com/ac3ebf4168.js" crossorigin="anonymous"></script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https: //fonts.googleapis.com/css2? family= Roboto:wght@100 & display=swap" rel="stylesheet"><!--importando fontes do google fontes--> 
	<link href="./pages/css/style.css" rel="stylesheet"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"> <!--tag para site responsivo-->
	<meta name="description" content="Descrição do meu web site"> <!--para o google encontrar o site-->
	<meta name="keywords" content="palavras-chave,do,meu,site">
	<meta charset="utf-8"/>
</head>
<body>
    <h1>Acesse sua conta para utilizar nossos serviços</h1>
    <form method="POST">
        <p>
            <label>Email</label>
            <input type="text" name="email">
        </p>
        <p>
            <label>Senha</label>
            <input type="password" name="senha">
        </p>
        <p>
            <button type="submit">Entrar</button>
        </p>
    </form>
</body>
</html>