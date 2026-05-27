<?php include("../../bd.php");
if (isset($_GET['txtId'])) {
    $txtID = (isset($_GET['txtId'])) ? $_GET['txtId'] : "";
    $sentencia = $conexion->prepare("SELECT * FROM categories WHERE category_id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();
    //creamos la variable
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);
    $category_name = $registro["category_name"];
}
//el codigo siguiente copio de crear.php
if ($_POST) {
    //Validacion del nombre categoria
    $txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
    $category_name = (isset($_POST['category_name']) ? $_POST['category_name'] : "");
    //Preparar la insercion de datos
    $sentencia = $conexion->prepare("UPDATE categories SET category_name=:category_name 
    WHERE category_id=:id");
    //Asignar los valores que devueven el metodo POST (los que vienen del formulario)
    $sentencia->bindParam(":id", $txtID);
    $sentencia->bindParam(":category_name", $category_name);
    $sentencia->execute();
    $mensaje = "Registro actualizado";
    //redireccionar a la pagina index.php
    header("Location: index.php?mensaje=" . $mensaje);
}
?>
<?php include("../../templates/header.php"); ?>
<h3> Editar categorias </h3>
<div class="card">
    <div class="card-header">Informaci&oacute;n de categorias</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <input
                    type="text"
                    value="<?php echo $txtID; ?>"
                    class="form-control"
                    readonly
                    name="txtID"
                    id="xtID"
                    aria-describedby="HelpId" />
            </div>
            <div class="mb-3">
                <input
                    type="text"
                    class="form-control"
                    name="category_name"
                    id="category_name"
                    placeholder="Describa la categoria"
                    aria-describedby="HelpId" />
                <small id="HelpId" class="form-text text-muted">Describa el nombre de la categoria</small>
            </div>
            <button type="submit" class="btn btn-success">Actualizar</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
    <div class="card-footer text-body-secondary">Footer</div>
</div>
<?php include("../../templates/footer.php"); ?>