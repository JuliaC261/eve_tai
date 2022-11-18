<?php
session_start(); // sempre quando se for usar $_SESSION precisa iniciar
$id = $_SESSION['id'];
if (isset($id)) { // se ele estiver logado e queira fazer logout
    session_destroy(); // destroi os dados da sessão
} else {
    header("Location: login.php"); // caso nao esteja logado ele vai pro login
}
?>

<h1>Você saiu!</h1>
<a href='login.php'>Voltar para Login</a>