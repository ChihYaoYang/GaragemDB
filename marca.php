<?php
//incluir function/DB
require_once './include/functions.php';
require_once './include/connect.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Marca</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!---bootstrap-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <!--Fontawesome--->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.2/css/all.css" integrity="sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns" crossorigin="anonymous">
        <!---CSS---->
        <link rel="stylesheet" type="text/css" href="css/custom.css">
    </head>
    <body>
        <div class="container">
            <!---Gerenciador de veículo--->
            <h3 class="breathe-div2"><a href="veiculo.php">< Gerenciador de veículo</a> | Gerenciador de Marca</h3> <hr>
            <div id="message"></div>
            <?php
            //Se $edicao for FALSE então carrega página cadastro, quando $edicao for TRUE muda para Alterar
            $edicao = false;
            //INSERT
             //Válida dados vindo POST
            if ($_POST) { 
                extract($_POST);
                if ($acao === 'cadastrar') {
                    if (!empty($descricao)) {
                        mysqli_query($dbconn, "INSERT INTO marca (nome) VALUES ('$descricao')");
                        //mysqli_affected_rows 函式可以用來統計 "前一次＂執行 MySQL 語法所影響的記錄行數
                        if (mysqli_affected_rows($dbconn) > 0) {
                            alert('success', 'Marca cadastrada com sucesso! ! !');
                        } else {
                            alert('danger', 'Marca não cadastrada');
                        }
                    } else {
                        alert('danger', 'Preencha o campo descrição');
                    }
                }
                //UPDATE
                if ($acao == 'alterar') {
                        if (!empty($descricao) && !empty($id)) {
                            mysqli_query($dbconn, "UPDATE marca SET nome='$descricao' WHERE id='$id'");
                            if (mysqli_affected_rows($dbconn) > 0) {
                                alert('success', 'Marca alterada com sucesso! ! !');
                            } else {
                                alert('danger', 'Marca não alterada');
                            }
                        } else {
                            alert('danger', 'Preencha o campo descrição');
                        }
                    }
                }
            
            //DELETE
            if (!empty($_GET['acao'])) {
                if ($_GET['acao'] == 'delete' && !empty($_GET['id']) && is_numeric($_GET['id'])) {
                    mysqli_query($dbconn, 'DELETE FROM marca WHERE id=' . $_GET['id']);
                    if (mysqli_affected_rows($dbconn) > 0) {
                        alert('success', 'Marca deletado com sucesso! ! !');
                    } else {
                        alert('danger', 'Falha ao deletar');
                    }
                }
                //UPDATE_GET_ID
                if ($_GET['acao'] == 'edit' && !empty($_GET['id']) && is_numeric($_GET['id'])) {
                    $sql = mysqli_query($dbconn, 'SELECT * FROM marca WHERE id=' . $_GET['id']);
                    if (mysqli_num_rows($sql) > 0) {
                        $row = mysqli_fetch_object($sql);
                        $edicao = true;
                    }
                }
            }
            ?>
            <div class="row">
                <div class="col-md-4">
                    <!---Card--->
                    <div class="card">
                        <h3 id="label-form" class="card-header bg-transparent"><i class="fas fa-edit"></i><?= $edicao == TRUE ? 'Alterar' : 'Cadastrar' ?> de Marca</h3>
                        <div class="card-body">
                            <!---Form--->
                            <form method="POST" action="marca.php">
                                <input type="hidden" name="acao" value="<?= $edicao == TRUE ? 'alterar' : 'cadastrar' ?>">

                                <!--campo input--->
                                <label for="descricao">Descrição</label>
                                <input type="text" class="form-control" id="descricao" name="descricao" <?= $edicao == TRUE ? 'value="' . $row->nome . '"' : NULL ?>>
                                <div>
                                    <hr>
                                    <!---campo botão--->
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Salvar</button>
                                        <?php
                                        if ($edicao === true) {
                                            echo '<input type="hidden" name="id" value="' . $row->id . '">';
                                            echo '<a href="marca.php" class="btn btn-primary">Cancelar</a>';
                                        }
                                        ?>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <!--Veículo cadastrados--->
                <div class="col-md-8">
                    <!---Card--->
                    <div class="card">
                        <h3 class="card-header bg-transparent"><i class="fas fa-truck"></i>Marca cadastrados</h3>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">Marca</th>
                                            <th scope="col">Produtos</th>
                                            <th scope="col">Opção</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = mysqli_query($dbconn, "SELECT marca.*,(SELECT COUNT(marca_id) FROM veiculo WHERE marca_id=marca.id) as produtos FROM marca ORDER BY nome ASC");
                                        if (mysqli_num_rows($sql) > 0) {
                                            while ($row = mysqli_fetch_object($sql)) {
                                                echo '<tr>';
                                                echo '<td>' . $row->nome . '</td>';
                                                echo '<td>' . $row->produtos . '</td>';
                                                echo '<td>';
                                                echo '<a href="marca.php?acao=edit&id=' . $row->id . '" class="btn btn-info"><i class="fas fa-edit"></i> Alterar</a>' . ' ';
                                                
                                                if ($row->produtos < 1) {
                                                    echo '<a href="marca.php?acao=delete&id=' . $row->id . '" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Apagar</a>';
                                                }
                                                echo '</td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="3">Nenhum marca cadastrado</td></tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>