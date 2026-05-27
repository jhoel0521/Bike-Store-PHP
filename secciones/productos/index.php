<?php require_once __DIR__ . '/../../bd.php';

//Envio de parametros en la URLo en el metodo GET
if (isset($_GET["txtID"])) {
    $txtID = (isset($_GET["txtID"])) ? $_GET['txtID'] : "";
    //Buscar el archivo relacionadocon el producto
    $registro_recuperado = \DB::getRegistro("SELECT foto FROM products WHERE product_id=:id", [":id" => $txtID]);
    //Buscar Archivo foto y borralo
    if (isset($registro_recuperado["foto"]) && $registro_recuperado["foto"] != "") {
        if (file_exists("./img/" . $registro_recuperado["foto"])) {
            unlink("./img/" . $registro_recuperado["foto"]);
        }
    }
    //Borra los datos del producto
    \DB::ejecutarConsulta("DELETE FROM products WHERE product_id=:id", [":id" => $txtID]);
    $mensaje = "Registro eliminado";
    redirigir_con_mensaje('index.php', $mensaje);
}
//Consulta de productos y categorias para visualizar como unico registro
$lista_productos = \DB::getTabla("SELECT *,
(SELECT category_name FROM categories WHERE category_id=products.category_id limit 1) as categoria
FROM products ORDER BY product_id DESC");
//print_r($lista_categorias);
?>
<?php include("../../templates/header.php"); ?>
<h2>Lista de Productos: </h2>
<div class="card">
    <div class="card-header">
        <!--bs5-card-head-a -->
        <a name="" id="" class="btn btn-outline-primary" href="crear.php" role="button"> Nuevo </a>
    </div>
    <div class="card-body">
        <!-- bs5--->
        <div
            class="table-responsive-sm">
            <table
                class="table table-primary" id="tabla_id">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Imagen</th>
                        <th scope="col">Año Modelo</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_productos as $registro) { ?>
                        <tr class="">
                            <td scope="row"><?php echo $registro['product_id']; ?></td>
                            <td><?php echo $registro['product_name']; ?></td>
                            <td>
                                <img widht="50" src="img/<?php echo $registro['foto']; ?>"
                                    class="img-fluid rounded" alt="Imagen producto" />
                            </td>
                            <td><?php echo $registro['model_year']; ?></td>
                            <td><?php echo $registro['price']; ?></td>
                            <td><?php echo $registro['categoria']; ?></td>
                            <td><a class="btn btn-outline-info"
                                    href="editar.php?txtID=<?php echo $registro['product_id']; ?>" role="button">
                                    <i class="bi bi-pencil"></i>editar</a>
                                <a class="btn btn-outline-danger"
                                    href="javascript:borrar(<?php echo $registro['product_id']; ?>);" role="button">
                                    <i class="bi bi-trash3"></i>eliminar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include("../../templates/footer.php"); ?>