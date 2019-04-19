<?php
//incluir function
require_once './include/functions.php';
require_once './include/connect.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>WebSQL</title>
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
            <h3 class="breathe-div">Gerenciador de veículo</h3>
            <hr>
            <?php
            //Se $edicao for FALSE então carrega página cadastro, quando $edicao for TRUE muda para Alterar
            $edicao = FALSE;
            //Válida dados vindo POST
            if ($_POST) {
                //Extrair $_POST 從陣列中將 Variável 導入到當前的指令碼
                extract($_POST);
                //Validação Insert
                if ($acao == 'cadastrar') {
                    //empty() 函式用來檢查變量是否為空
                    //Validar se é vazio ou não
                    if (!empty($modelo) && !empty($preco) && !empty($marca_id) && $status != '') {
                        //Criar variável $imagem para armazena imagem na pasta upload 
                        $imagem = '';
                        //Se $upload_error for FALSE então inserir os dados se for TRUE exibir mensagem
                        $upload_error = false;
                        /*
                            No caso imagme é file
                            $_FILES["file"]["name"]：上傳檔案的原始名稱。
                            $_FILES["file"]["type"]：上傳的檔案類型。
                            $_FILES["file"]["size"]：上傳的檔案原始大小。
                            $_FILES["file"]["tmp_name"]：上傳檔案後的暫存資料夾位置。
                            $_FILES["file"]["error"]：如果檔案上傳有錯誤，可以顯示錯誤代碼。
                        */
                        if (!empty($imagem = $_FILES['imagem']['name'])) {
                            //md5 函式的功能可以將字串打散並重新計算出 md5 雜湊函數
                            //md5(date('YmdHis')) 根據date 時間將字串打散並重新計算
                            $imagem = md5(date('YmdHis')) . $_FILES['imagem']['name'];
                            //in_array 用來判斷某個值是否存在陣列中
                            /*
                            pathinfo() 取得檔案名稱、副檔名、根目錄相對路徑、資料夾名
                            // 參數 (選用)
                                PATHINFO_DIRNAME   //完整路徑
                                PATHINFO_BASENAME  //完整檔名
                                PATHINFO_EXTENSION //副檔名
                                PATHINFO_FILENAME  //檔名
                            */
                            //Validar extensão da imagem           //Chama extensoes() na pasta functions.php 
                            if (in_array(pathinfo($imagem)['extension'], extensoes())) {
                                //Valida tamanho da imagem se for menor que 100KB
                                //$_FILES["file"]["size"]：上傳的檔案原始大小。
                                if ($_FILES['imagem']['size'] < 100000) { // <---tamanho em byte
                                    //$_FILES["file"]["type"]：上傳的檔案類型。 //Chama mimes() na pasta functions.php 
                                    if (in_array($_FILES['imagem']['type'], mimes())) {
                                        //$_FILES["file"]["tmp_name"]：上傳檔案後的暫存資料夾位置。
                                        //move_uploaded_file --> 將上傳的檔案搬移到資料夾中 EX: 'upload/'  
                                        move_uploaded_file($_FILES['imagem']['tmp_name'], 'upload/' . $imagem);
                                    } else {
                                        alert('danger', 'Veículo não cadastrado. Envie imagem correcto como image/jpg, image/png, image/jpeg');
                                        $upload_error = true;
                                    }
                                } else {
                                    alert('danger', 'Veículo não cadastrado. Envie arquivos com no tamanho máximo de 100KB');
                                    $upload_error = true;
                                }
                            } else {
                                alert('danger', 'Veículo não cadastrado. Envie apenas arquivos da imagem');
                                $upload_error = true;
                            }
                        }
                        //$upload_error for FALSE executa mysqli_query
                        //Insert
                        if ($upload_error === false) {
                            //mysqli_query 執行數據庫
                            mysqli_query($dbconn, "INSERT INTO veiculo(modelo,preco,marca_id,status,imagem) VALUES('$modelo','$preco','$marca_id','$status','$imagem')");
                            //mysqli_affected_rows 函式可以用來統計 "前一次＂執行 MySQL 語法所影響的記錄行數
                            if (mysqli_affected_rows($dbconn) > 0) {
                                alert('success', 'Veículo cadastrada com sucesso! ! !');
                            } else {
                                alert('danger', 'Veículo não cadastrada');
                                //unlink()函數刪除文件。
                                unlink('upload/' . $imagem);
                            }
                        }
                    } else {
                        alert('danger', 'Preencha todos os campos ! ! !');
                    }
                }
                //Validação UPDATE 
                if ($acao == "alterar") {
                     //Validar se é vazio ou não
                    if (!empty($modelo) && !empty($preco) && !empty($marca_id) && $status != "" && !empty($id)) {
                        //Criar variável $imagem para armazena imagem na pasta upload
                        //Se $upload_error for FALSE então update os dados se for TRUE exibir mensagem
                        $imagem = '';
                        $upload_error = false;
                        //$_FILES["file"]["name"]：上傳檔案的原始名稱。
                        ////empty() 函式用來檢查變量是否為空
                        if (!empty($_FILES['imagem']['name'])) {
                            //md5(date('YmdHis')) 根據date 時間將字串打散並重新計算 como random
                            $imagem = md5(date('YmdHis')) . $_FILES['imagem']['name'];
                            if (in_array(pathinfo($imagem)['extension'], extensoes())) {
                                if ($_FILES['imagem']['size'] < 100000) {
                                    if (in_array($_FILES['imagem']['type'], mimes())) {
                                        //move_uploaded_file --> 將上傳的檔案搬移到資料夾中
                                                                 //$_FILES["file"]["tmp_name"]：上傳檔案後的暫存資料夾位置。
                                        if (move_uploaded_file($_FILES['imagem']['tmp_name'], 'uploads/' . $imagem)) {
                                            $imagem = ',imagem="' . $imagem . '"';
                                            $sqlimagem = mysqli_query($dbconn, 'SELECT imagem FROM veiculo WHERE id=' . $id);
                                            //mysqli_fetch_object : Retorna a linha atual do conjunto de resultados como um objeto
                                            $rowimagem = mysqli_fetch_object($sqlimagem);
                                                                            //file_exists() 函數檢查文件或目錄是否存在。
                                            if (!empty($rowimagem->imagem) && file_exists('uploads/' . $rowimagem->imagem)) {
                                                //Se for TRUE //unlink()函數刪除文件。
                                                unlink('uploads/' . $rowimagem->imagem);
                                            }
                                        }
                                    } else {
                                        alert('danger', 'Veículo não atualizado. Envie apenas arquivos de imagem.');
                                        $upload_error = true;
                                    }
                                } else {
                                    alert('danger', 'Veículo não atualizado. Envie arquivos com no máximo 100KB.');
                                    $upload_error = true;
                                }
                            } else {
                                alert('danger', 'Veículo não atualizado. Envie apenas arquivos de imagem.');
                                $upload_error = true;
                            }
                        }
                        //Update
                        if ($upload_error === false) {
                            mysqli_query($dbconn, "UPDATE veiculo SET modelo='$modelo',preco='$preco',marca_id='$marca_id',status='$status' $imagem WHERE id=" . $id);
                            //mysqli_affected_rows 函式可以用來統計 "前一次＂執行 MySQL 語法所影響的記錄行數
                            if (mysqli_affected_rows($dbconn) > 0) {
                                alert('success', 'Veículo alterado com sucesso.');
                            } else {
                                alert('danger', 'Veículo não sofreu alterações.');
                            }
                        }
                    } else {
                        alert('danger', 'Preecha todos os campos obrigatórios');
                    }
                }
            }
            //DELETE
            if (!empty($_GET['acao'])) {
                //Validar se é vazio ou não
                if ($_GET['acao'] == 'delete' && !empty($_GET['id']) && is_numeric($_GET['id'])) {
                    $veiculo = mysqli_query($dbconn, 'SELECT imagem FROM veiculo WHERE id=' . $_GET['id']);
                    //mysqli_fetch_object : Retorna a linha atual do conjunto de resultados como um objeto
                    $veiculo = mysqli_fetch_object($veiculo);
                    mysqli_query($dbconn, 'DELETE FROM veiculo WHERE id=' . $_GET['id']);
                    //mysqli_affected_rows 函式可以用來統計 "前一次＂執行 MySQL 語法所影響的記錄行數
                    if (mysqli_affected_rows($dbconn) > 0) {
                        alert('success', 'Veículo removido com sucesso! ! !');
                        //file_exists() 函數檢查文件或目錄是否存在。
                        if (file_exists('upload/' . $veiculo->imagem) && !empty($veiculo->imagem)) {
                            //unlink()函數刪除文件。
                            unlink('upload/' . $veiculo->imagem);
                        }
                    } else {
                        alert('danger', 'Veículo não removido');
                    }
                }
                //UPDATE GET_ID
                                                                    //檢查variável是否為數字或數字字符串
                if ($_GET['acao'] == 'edit' && !empty($_GET['id']) && is_numeric($_GET['id'])) {
                    $veiculo = mysqli_query($dbconn, 'SELECT * FROM veiculo WHERE id=' . $_GET['id']);
                    //mysqli_num_rows para conta quanto linha for affectado
                    if (mysqli_num_rows($veiculo) > 0) {
                        //mysqli_fetch_object : Retorna a linha atual do conjunto de resultados como um objeto
                        $veiculo_alterar = mysqli_fetch_object($veiculo);
                        //Se $edicao for FALSE então carrega página cadastro, quando $edicao for TRUE muda para Alterar
                        $edicao = TRUE;
                    } else {
                        alert('danger', 'Veículo não encontrado');
                    }
                }
            }
            ?>
            <div class="row">
                <div class="col-md-4">
                    <!---Card--->
                    <div class="card">
                                                                             <!----Se $edicao for FALSE então carrega página cadastro, quando $edicao for TRUE muda para Alterar------->
                        <h3 id="label-form" class="card-header bg-transparent"><i class="fas fa-edit"></i><?= $edicao === TRUE ? 'Alteração' : 'Cadastro' ?> de Veículo</h3>
                        <div class="card-body">
                            <!---Form--->
                                                                        <!---form要加上 enctype="multipart/form-data"才能上傳檔案---->
                            <form method="POST" action="veiculo.php" name="cadastrar" enctype="multipart/form-data">
                            <!----Se $edicao for FALSE então carrega página cadastro, quando $edicao for TRUE muda para Alterar------->
                                <input type="hidden" name="acao" value="<?= $edicao == TRUE ? 'alterar' : 'cadastrar' ?>">
                                <!--campo status--->
                                <!--campo input--->
                                <!--Campo modelo--->
                                <label for="modelo">Modelo:</label>
                                <input type="text" class="form-control" id="modelo" name="modelo" value="<?= $edicao === TRUE ? $veiculo_alterar->modelo : NULL ?>">
                                <!--R$ icone--->
                                <div>
                                    <label for="preco">Preço:</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">R$</div>
                                        </div>
                                        <input type="text" id="preco" class="form-control" name="preco" value="<?= $edicao === TRUE ? $veiculo_alterar->preco : NULL ?>">
                                    </div>
                                </div>
                                <!--Campo Marca-->
                                <label for="marca">Marca: <a href="marca.php">[ Gerenciar Marcas ]</a></label>
                                <select id="marca_id" name="marca_id" class="form-control">
                                    <?php
                                    $marcas = mysqli_query($dbconn, 'SELECT * FROM marca ORDER BY nome ASC');
                                    //mysqli_num_rows para conta quanto linha for affectado
                                    if (mysqli_num_rows($marcas) > 0) {
                                        echo ' <option value="">Seleciona uma Marca</option>';
                                        while ($marca = mysqli_fetch_object($marcas)) {
                                            $marca_alterar = $edicao === true && $veiculo_alterar->marca_id == $marca->id ? 'selected' : NULL;
                                            echo '<option ' . $marca_alterar . ' value="' . $marca->id . '">' . $marca->nome . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">Nenhuma marca cadastrada.</option>';
                                    }
                                    ?>
                                </select>
                                <!---campo  select Status---->
                                <div>
                                    <label for="status">Status:</label>
                                    <select id="status" name="status" class="form-control">
                                        <option selected>Seleciona Status</option>
                                        <option value="0"<?= ($edicao === TRUE && $veiculo_alterar->status == 0 ? 'selected' : NULL) ?> >Inativo</option>
                                        <option value="1" <?= ($edicao === TRUE && $veiculo_alterar->status == 1 ? 'selected' : NULL) ?> >Ativo</option>
                                    </select>
                                    <br>
                                    <!---Campo file--->
                                    <div class="custom-file">
                                        <label class="custom-file-label" for="image">Escolha arquivo</label>
                                        <input type="file" class="custom-file-input" id="imagem" name="imagem" accept="image/jpg, image/jpeg, image/png">
                                    </div>
                                    <?php
                                    //GET IMG
                                    if ($edicao === true && !empty($veiculo_alterar->imagem) && file_exists('upload/' . $veiculo_alterar->imagem)) {
                                        echo '<img src="upload/' . $veiculo_alterar->imagem . '" width="307px">';
                                    }
                                    ?>
                                    <br>
                                    <hr>
                                    <!---campo botão--->
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Salvar</button>
                                        <?php
                                        if ($edicao === true) {
                                            ?>
                                            <a href="veiculo.php" class="btn btn-warning"><i class="fas fa-trash-alt"></i> Cancelar</a>
                                            <input type="hidden" value="<?= $veiculo_alterar->id ?>" name="id">
                                            <?php
                                        } else {
                                            ?>
                                            <button type="reset" class="btn btn-warning"><i class="fas fa-trash-alt"></i> Limpar</button>
                                            <?php
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
                        <h3 class="card-header bg-transparent"><i class="fas fa-truck"></i>Veículo cadastrados</h3>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">Imagem</th>
                                            <th scope="col">Modelo</th>
                                            <th scope="col">Preço</th>
                                            <th scope="col">Marca</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Opção</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //Lista
                                        $veiculos = mysqli_query($dbconn, 'SELECT veiculo.*,marca.nome FROM veiculo INNER JOIN marca ON marca.id=veiculo.marca_id');
                                        if (mysqli_num_rows($veiculos) > 0) {
                                            while ($veiculo = mysqli_fetch_object($veiculos)) {
                                                echo '<tr ' . ($veiculo->status == 0 ? 'class= inativo' : NULL) . '>';
                                                if (!empty($veiculo->imagem)) {
                                                    echo '<td><img src="upload/' . $veiculo->imagem . '" width="50"></td>';
                                                } else {
                                                    echo '<td><img src="https://encurtador.com.br/wzJK3" width="50"></td>';
                                                }
                                                echo '<td>' . $veiculo->modelo . '</td>';
                                                echo '<td>' . $veiculo->preco . '</td>';
                                                echo '<td>' . $veiculo->nome . '</td>';
                                                echo '<td>' . ($veiculo->status == 0 ? 'Inativo' : 'Ativo') . '</td>';
                                                echo '<td>';
                                                echo '<a href="veiculo.php?acao=edit&id=' . $veiculo->id . '"class="btn btn-primary"><i class="fas fa-edit"></i> Editar</a>' . ' ';
                                                echo '<a href="veiculo.php?acao=delete&id=' . $veiculo->id . '"class="btn btn-danger"><i class="fas fa-trash-alt"></i> Apagar</a>';
                                                echo '</td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="6">Nenhum carro cadastrado</td></tr>';
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