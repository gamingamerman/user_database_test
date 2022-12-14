<?php
    $link = new mysqli('db', 'root', 'test', 'Usuario'); 

    $error = $link -> connect_errno;

    if ($error != null) {
        echo '<p>El error dice: ' . $link->connect_error . '</p>';
        die(); //Stop de exec
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table {
            border-collapse: collapse;
            text-align: center;
        }

        th {
            background-color: navy;
            color: white;
        }

        td, th {
            border: 1px solid navy;
            padding: 2px 8px;
        }

        td:nth-child(5) {
            text-align: right;
        }

    </style>
</head>
<body>
    <h1>Usuarios:</h1>
    <table>
        <thead>
            <th>ID</th>
            <th>Nombre</th>
            <th>Fecha</th>
            <th>Activo</th>
        </thead>
        <tbody>
            <?php
                $sql = "SELECT * FROM usuario";
                $result = $link->query($sql);
                $row = $result->fetch_array();
                while ($row != null) {
                    $formated_date = date_create_from_format('Y-m-d', $row['Fecha']);
            ?>

            <tr>
                <td>
                    <?=$row["Id"]?>
                </td>
                <td>
                    <?=$row["Nombre"]?>
                </td>
                <td>
                    <?=strftime('%A, %d de %B de %Y', date_timestamp_get($formated_date));?>
                </td>
                <td>
                    <? if ($row["Activo"] == 1) {
                        echo "Sí";
                    } else {
                        echo "No";
                    }    
                    ?>
                </td>
            </tr>

            <?php
                    $row = $result->fetch_array();
                }            
            ?>
        </tbody>
    </table>
    <br>  
    <form action="" method="post">
                Nombre: <input type="text" name="nombre_user">
                <br>
                <br>
                Fecha de Nacimiento: <input type="date" name="fecha_user" value="<?=date('Y-m-d')?>">
                <br>
                <br>
                Activo? <input type="checkbox" name="activo_user">
                <br>
                <br>
                <input type="submit" name="insert_user" value="Enviar">
    </form>

    <br>

    <?php
        if (isset($_POST['insert_user'])) {
            $nameError = "";
            $dateError = "";
            if(empty($_POST["nombre_user"])) $nameError =  "El nombre está vacio";
            if(empty($_POST["fecha_user"])) $dateError = "La fecha está vacía";
            if (strlen($nameError) > 0 ||  strlen($dateError) > 0) {
                echo "<h3> No se puede crear usuario </h3>";
            } else {
                $nombre = $_REQUEST["nombre_user"];
                $fecha = $_REQUEST["fecha_user"];
                $activo = 0;

                $sql = "INSERT INTO usuario(Nombre, Fecha, Activo) VALUES ('$nombre', '$fecha', '$activo')";
                if ($link -> query($sql) == TRUE) {
                    echo "Se ha creado un nuevo usuario";
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($link);
                }
            }
            
        }
    ?>
</body>
</html>
<?php
    $link->close();
?>