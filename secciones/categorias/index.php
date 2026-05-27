<?php
include("../../bd.php");
//Envio de parametros en la URL o en el metodo GET
if (isset($_GET['txtID'])) {
    $txtID = $_GET['txtID'];

    $verificarProductos = $conexion->prepare("SELECT COUNT(*) FROM products WHERE category_id = :id");
    $verificarProductos->bindParam(":id", $txtID);
    $verificarProductos->execute();
    $productosAsociados = $verificarProductos->fetchColumn();

    if ($productosAsociados > 0) {
        $mensaje = "No se puede eliminar esta categoría porque tiene productos asociados.";
    } else {
        try {
            $sentencia = $conexion->prepare("DELETE FROM categories WHERE category_id=:id");
            $sentencia->bindParam(":id", $txtID);
            $sentencia->execute();
            $mensaje = "Registro eliminado";
        } catch (PDOException $ex) {
            $mensaje = "Error al eliminar la categoría.";
        }
    }

    header("Location: index.php?mensaje=" . urlencode($mensaje));
    exit;
}

//Consulta de categorias
$sentencia = $conexion->prepare("SELECT * FROM categories ORDER BY category_id ASC");
$sentencia->execute();
$lista_categorias = $sentencia->fetchAll(PDO::FETCH_ASSOC);
//print_r($lista_categorias);
?>
<?php include("../../templates/header.php"); ?>
<h3> categorias </h3>
<?php if (isset($_GET['mensaje']) && $_GET['mensaje'] != "") { ?>
    <div class="alert alert-info" role="alert">
        <?php echo htmlspecialchars($_GET['mensaje'], ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php } ?>
<div class="card">
    <div class="card-header"></div>
    <a name="" id="" class="btn btn-outline-primary"
        href="crear.php" role="button">
        <i class="bi bi-plus-circle me-2"></i>Nueva</a>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table table-primary" id="tabla_id">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_categorias as $registro) { ?>
                        <tr class="">
                            <td scope="row"><?php echo $registro['category_id']; ?></td>
                            <td><?php echo $registro['category_name']; ?></td>
                            <td><a class="btn btn-outline-info"
                                    href="editar.php?txtId=<?php echo $registro['category_id']; ?>" role="button">
                                    <i class="bi bi-pencil">Edit</i></a>
                                <a class="btn btn-outline-danger" href="#" role="button"
                                    onclick="borrar( '<?php echo $registro['category_id']; ?>')">
                                    <i class="bi bi-trash3">Eliminar</i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function borrar( id) {
        Swal.fire({
            title: '¿Deseas eliminar esta categoría?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'index.php?txtID=' + id;
            }
        });
    }
</script>

<?php include("../../templates/footer.php"); ?>