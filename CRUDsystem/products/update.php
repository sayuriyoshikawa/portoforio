<?php
session_start();

if (isset($_SESSION['user']) != "") {
    header("Location: ../home.php");
    exit;
}

if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

require_once '../components/db_connect.php';

if ($_GET['id']) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id = {$id}";
    $result = mysqli_query($connect, $sql);
    if (mysqli_num_rows($result) == 1) {
        $data = mysqli_fetch_assoc($result);
        $name = $data['name'];
        $price = $data['price'];
        $picture = $data['picture'];
        $description = $data["description"];
        $supplier = $data['fk_supplierId'];
        $resultSup = mysqli_query($connect, "SELECT * FROM supplier");
        $supList = "";
        if (mysqli_num_rows($resultSup) > 0) {
            while ($row = $resultSup->fetch_array(MYSQLI_ASSOC)) {
                if ($row['supplierId'] == $supplier) {
                    $supList .= "<option selected value='{$row['supplierId']}'>{$row['sup_name']}</option>";
                } else {
                    $supList .= "<option value='{$row['supplierId']}'>{$row['sup_name']}</option>";
                }
            }
        } else {
            $supList = "<li>There are no suppliers registered</li>";
        }
    } else {
        header("location: error.php");
    }
    //    mysqli_close($connect);
    $connect->close();
} else {
    header("location: error.php");
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Edit Product</title>
    <?php require_once '../components/boot.php' ?>
    <style type="text/css">
        fieldset {
            margin: auto;
            margin-top: 100px;
            width: 60%;
        }

        .img-thumbnail {
            width: 70px !important;
            height: 70px !important;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar">
        <div class="container-fluid">
            <a class="navbar-brand text-white fw-bold" href="index.php">Restaurant booking system</a>
        </div>
    </nav>
    <fieldset>
        <legend class='h2'>Update request <img class='img-thumbnail rounded-circle' src='pictures/<?php echo $picture ?>' alt="<?php echo $name ?>"></legend>
        <form action="actions/a_update.php" method="post" enctype="multipart/form-data">
            <table class="table">
                <tr>
                    <th>Name</th>
                    <td><input class="form-control" type="text" name="name" placeholder="Product Name" value="<?php echo $name ?>" /></td>
                </tr>
                <tr>
                    <th>Avarage price</th>
                    <td><input class="form-control" type="number" name="price" step="any" placeholder="Price" value="<?php echo $price ?>" /></td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td><textarea class="form-control" type="text" name="description" placeholder="Description" rows="3"><?php echo $description ?></textarea></td>
                </tr>
                <tr>
                    <th>Picture</th>
                    <td><input class="form-control" type="file" name="picture" /></td>
                </tr>
                <tr>
                    <th>Supplier</th>
                    <td>
                        <select class="form-select" name="supplier" aria-label="Default select example">
                            <?php echo $supList; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <input type="hidden" name="id" value="<?php echo $data['id'] ?>" />

                    <input type="hidden" name="picture" value="<?php echo $data['picture'] ?>" />
                    <td><button class="btn btn-success" type="submit">Save Changes</button></td>
                    <td><a href="index.php"><button class="btn btn-warning" type="button">Back</button></a></td>
                </tr>
            </table>
        </form>
    </fieldset>
</body>

</html>