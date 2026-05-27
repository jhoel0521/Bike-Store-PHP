<?php include("../../bd.php");
if($_POST){
    //Validacion del nombre categoria
    $category_name=(isset($_POST['category_name'])?$_POST['category_name']:"");
    //Preparar la insercion de datos
    $sentencia = $conexion->prepare("INSERT INTO categories (category_id,category_name) 
    VALUES (NULL, :category_name)");
    //Asignar los valores que devueven el metodo POST (los que vienen del formulario)
    $sentencia->bindParam(":category_name", $category_name);
    $sentencia->execute();
    $mensaje = "Registro agregado";
    //redireccionar a la pagina index.php
    header("Location: index.php?mensaje=" . $mensaje);
}
?>
<?php include("../../templates/header.php"); ?>
<br>
<div class="card">
    <div class="card-header">Informaci&oacute;n de categorias</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <input
                type="text"
                class="form-control"
                name="category_name"
                id="category_name"
                placeholder="Describa la categoria"
                aria-describedby="HelpId" 
            />
            <small id="HelpId" class="form-text text-muted">Describa el nombre de la categoria</small>
        </div>
        <button type="submit" class="btn btn-success">Agregar</button>
        <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
    </div>
    <div class="card-footer text-body-secondary">Footer</div>
</div>

<?php include("../../templates/footer.php"); ?>