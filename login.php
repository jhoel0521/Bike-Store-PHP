<?php
    //inicia la sesion
    session_start();
    if($_POST){
        //importar la conexion a la base de datos
        include("bd.php");
        $sentencia = $conexion->prepare("SELECT *, COUNT(*) as n_usuario FROM usuarios WHERE usuario=:usuario AND clave=:clave");
        
        $usuario = $_POST['usuario'];
        $clave = $_POST['clave'];

        $sentencia->bindParam(":usuario",$usuario);
        $sentencia->bindParam(":clave",$clave);
        $sentencia->execute();
        Sregistro=$sentencia->fetch(PDO::FETCH_LAZY);

        if($registro){
            $_SESSION['usuario'] = $registro['usuario'];
            $_SESSION['n_usuario'] = $registro['n_usuario'];    
            header("Location: index.php");
        }else{
            $mensaje = "Error: El usuario o la contrase&ntilde;a son incorrectos.";
            $errorMessage = true;
        }
    }

<!doctype html>
<html lang="es" data-bs-theme="light">
    <head>
        <title>Identificaci&oacute;n de Usuario</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- Bootstrap CSS v5.3.8 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
            crossorigin="anonymous"
        />
    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <main> class="container">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Iniciar Sesi&oacute;n</div>
                        <div class="card-body">
                            <!-- Agregar alerta para mostrar mensajes de error -->
                            <?php if (isset($errorMessage)) :{ ?>
                                <div class="alert alert-danger" role="alert">
                                    <strong><?php echo $mensaje; ?></strong>
                                </div>
                            <?php } ?>
                           <form action="" method="post">
                                <div class="mb-3">
                                    <label for="usuario" class="form-label" >Usuario: </label>
                                    <input type="text" class="form-control" name="usuario" id="usuario"
                                    placeholder="Ingrese su usuario">
                                </div>
                                <div class="mb-3">
                                    <label for="clave" class="form-label" >Contrase&ntilde;a: </label>
                                    <input type="password" class="form-control" name="clave" id="clave"
                                    placeholder="Ingrese su contrase&ntilde;a del usuario">
                                </div>
                                <div class="mb-3">
                                    <a href="#">Recordar contrase&ntilde;a</a>
                                </div>
                                <button type="submit" class="btn btn-outline-primary">Ingresar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Bundle (includes Popper) -->
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
