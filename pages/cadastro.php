<?php
include_once '../crud/conexao.php';
session_start();
if (isset($_SESSION['id'])) {
    header('Location: painel.php');
}
$aviso = '';
$nome = '';
$email = '';
if (isset($_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['confirmaSenha'])) {
//    $conexao = new PDO('mysql:host=localhost;dbname=login', 'root', '');

    $nome = filter_input(INPUT_POST, 'nome', FILTER_UNSAFE_RAW);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_UNSAFE_RAW);
    $confirmaSenha = filter_input(INPUT_POST, 'confirmaSenha', FILTER_UNSAFE_RAW);

    if (!isset($nome, $email, $senha, $confirmaSenha)) {
        $aviso = 'Preencha todos os campos';
    } else {
        if ($senha != $confirmaSenha) {
            $aviso = 'Senhas não conferem';
        } else {
            $stmt = $conexao->prepare('SELECT * FROM tb_usuarios WHERE email = :email OR nome = :nome');
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':nome', $nome);
            $stmt->execute();

            $usuarioExistente = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($usuarioExistente['email'] == $email) {
                $aviso = 'Email já cadastrado';
            } else if($usuarioExistente['nome'] == $nome) {
                $aviso = 'Nome já cadastrado';
            } else {
                $senhacripto = password_hash($senha, PASSWORD_DEFAULT);

                $sql = 'INSERT INTO tb_usuarios (nome, email, senha) VALUES (:nome, :email, :senha)';
                $stmt = $conexao->prepare($sql);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':senha', $senhacripto); // salva senha com criptografia
                $stmt->execute();

                $aviso = 'Usuário cadastrado com sucesso';

                header('Location: login.php');
            }
        }
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Projeto 01 - Cadastro</title>
    <script src='https://kit.fontawesome.com/ac3ebf4168.js' crossorigin='anonymous'></script>
    <link rel='preconnect' href='https://fonts.googleapis.com'>
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https: //fonts.googleapis.com/css2? family= Roboto:wght@100 & display=swap' rel='stylesheet'>
    <!--importando fontes do google fontes-->
    <link href='./pages/css/style.css' rel='stylesheet' />
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <!--tag para site responsivo-->
    <meta name='description' content='Descrição do meu web site'>
    <!--para o google encontrar o site-->
    <meta name='keywords' content='palavras-chave,do,meu,site'>
    <meta charset='utf-8' />
</head>

<body>
    <h1>Acesse sua conta para utilizar nossos serviços</h1>
    <?= $aviso ?>
        <form method='POST'>
            <p>
                <label>Nome completo</label>
                <input type='text' name='nome'>
            </p>
            <p>
                <label>Email</label>
                <input type='email' name='email'>
            </p>
            <p>
                <label>Senha</label>
                <input type='password' name='senha'>
            </p>
            <p>
                <label>Confirme a senha</label>
                <input type='password' name='confirmaSenha'>
            </p>
            <p>
                <button type='submit'>Cadastrar</button>
            </p>
        </form>
</body>

</html>