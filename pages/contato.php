<?php
//variaveis que irão receber os dados
$aviso = "Preencha o formulário";
$nome = "";
$email = "";
$msg = "";

//verificar se os dados estão chegando no BD
//os nomes devem estar iguais ao do banco criado 

if (isset($_POST["nome"], $_POST["email"], $_POST['msg'])){
    //iniciando a conexao 

    //atribuindo os valores dos inputs para as variáveis   
    //o filter_input, limpa os dados depois de inseridos 
	$nome = filter_input(INPUT_POST, "nome", FILTER_UNSAFE_RAW);
	$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
	$msg = filter_input(INPUT_POST, "msg", FILTER_UNSAFE_RAW);

	//verificar se o usuário digitou dados invalidos  
    if(!$nome || !$email || !$msg){
		$aviso = "Dados Inválidos";
	} else {
		$conexao = new PDO("mysql:host=localhost;dbname=site1", "root", "");

        //vai inserir os dados na tabela lá do BD
		$stm = $conexao->prepare('INSERT INTO contato (nome, email, msg) VALUES (:nome, :email, :msg)');
        //bindParam = informar valores dinamicamente para uma requisição 
		//SQL usando PHP, através de uma variável ou constante.
		$stm->bindParam('nome', $nome);
		$stm->bindParam('email', $email);
		$stm->bindParam('msg', $msg);
		$stm->execute();

		$aviso = "Mensagem Enviada com Sucesso";
	
		//limpar campos qndo a msg for enviada
		$nome = "";
		$email = "";
		$msg = "";

	}

}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Projeto 01 - Contato</title>
	<script src="https://kit.fontawesome.com/ac3ebf4168.js" crossorigin="anonymous"></script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https: //fonts.googleapis.com/css2? family= Roboto:wght@100 & display=swap" rel="stylesheet"><!--importando fontes do google fontes--> 
	<link href="./css/style.css" rel="stylesheet"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"> <!--tag para site responsivo-->
	<meta name="description" content="Descrição do meu web site"> <!--para o google encontrar o site-->
	<meta name="keywords" content="palavras-chave,do,meu,site">
	<meta charset="utf-8"/>
</head>

<body>
<div>
	<div class="center">
		<form method="POST">
			<input required type="text" name="nome" value="<?=$nome ?>" placeholder="Nome..." >
			<div></div>
			<input required type="text" name="email" value="<?=$email ?>"  placeholder="E-mail.." >
			<div></div>
			<textarea required placeholder="Sua mensagem..." name="msg"><?=$msg ?></textarea>
			<div></div>
			<input type="submit" name="acao" value="Enviar">
		</form>
		<div class="mensagem">
			<?=$aviso?>
		</div>
	</div><!--center-->
</div><!--contato-container-->
</body>
</html>

