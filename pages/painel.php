<?php
include_once '../crud/conexao.php';
session_start();
$nome = $_SESSION['nome'];
$id = $_SESSION['id'];
if (!isset($id)) { // se não tiver o id na sessão, não está logado
    header("location: login.php");
} else {
    // se estiver logado, tudo certinhp
    $listLivrosQuery = $conexao->prepare("SELECT * FROM tb_livros");
    $listLivrosQuery->execute();
    $listLivros = $listLivrosQuery->fetchAll(PDO::FETCH_ASSOC);

    $favoritosQuery = $conexao->prepare("SELECT * FROM tb_meus_livros WHERE id_usuario = :id_usuario");
    $favoritosQuery->bindValue(":id_usuario", $id);
    $favoritosQuery->execute();
    $favoritos = $favoritosQuery->fetchAll(PDO::FETCH_ASSOC);
}
?>
<div style="display: flex; justify-content: space-around">
    <h1>
        Painel
    </h1>
    <h2>
        <?= $nome ?>
    </h2>
    <h3>
        (<?= $id ?>)
    </h3>
    <a href="logout.php">
        Sair
    </a>
</div>

<div style="display: flex; flex-direction: row; justify-content: space-around">
    <?php
    foreach ($listLivros as $livro) {
        $favorito = false;
        foreach ($favoritos as $fav) {
            if ($fav['id_livro'] == $livro['id']) {
                $favorito = true;
            }
        }
    ?>
    <div
        style="display: flex; flex-direction: column; align-items: center; padding: 30px; border: 10px; border-style: groove ">
        <p>
            Titulo: <?= $livro['titulo'] ?>
        </p>
        <p>
            Autor: <?= $livro['autor'] ?>
        </p>
        <p>
            Editora: <?= $livro['editora'] ?>
        </p>
        <p>
            Ano: <?= $livro['ano'] ?>
        </p>
        <?= $favorito ? "<p> Favorito </p>" : "" ?>
            <button onClick="favoritar(<?= $livro['id'] ?>)">
                <?= $favorito ? "Desfavoritar </3" : "Favoritar <3" ?>
            </button>
    </div>
    <?php
    }
    ?>
</div>

<script>
    function favoritar(id) {
        const PHPSESSID = document.cookie.split(';').find(row => row.includes('PHPSESSID')).split('=')[1];
        const formData = new FormData();
        formData.append('livroId', id);
        fetch('../crud/favoritarLivro.php', {
            method: 'POST',
            headers: {
                'Cookie': `PHPSESSID=${PHPSESSID}`
            },
            body: formData
        }).then(response => response.json()).then(data => {
            console.log(data);
        });
    }
</script>