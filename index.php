<?php

$host = "localhost";
$username = "root";
$password = "";
$dbName = "company";

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if (isset($_POST["send"])) {
    // Collect form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    // Handle image upload
    $imgName = $_FILES['image']['name'];
    $imgTmp = $_FILES["image"]["tmp_name"];
    $imgPath = "images/" . $imgName;
    move_uploaded_file($imgTmp, $imgPath);

    // SQL query to insert data into database
    $sql = "INSERT INTO `employees` (name, phone, email, image) VALUES ('$name', '$phone', '$email', '$imgPath')";

    mysqli_query($conn, $sql);
}


#delete row
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $delete = " DELETE FROM `employees` WHERE `id` = $id ";
    mysqli_query($conn, $delete);
    header("Location: index.php");
}
#ubdate row
$mode = 'create';
$name = "";
$phone = "";
$email = "";
$userId = NULL;
if (isset($_GET["edit"])) {
    $id = $_GET["edit"];
    $selectOne = " SELECT * FROM `employees` WHERE `id` = $id ";
    $getOne = mysqli_query($conn, $selectOne);
    $row = mysqli_fetch_assoc($getOne);
    $name = $row['name'];
    $email = $row['email'];
    $phone = $row['phone'];
    $userId =  $id;
    $mode = "update";
}
if (isset($_POST["update"])) {
    // Collect form data
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    // Handle image upload
    $imgName = $_FILES['image']['name'];
    $imgTmp = $_FILES["image"]["tmp_name"];
    $imgPath = "images/" . $imgName;
    move_uploaded_file($imgTmp, $imgPath);

    // SQL query to update data in the database
    $update = "UPDATE `employees` SET `name` = '$name', `phone` = '$phone', `email` = '$email', `image` = '$imgPath' WHERE `id` = $userId";
    // Execute the update query
    mysqli_query($conn, $update);
    $mode = "create";
    $name = "";
    $phone = "";
    $email = "";
}

##select item for read 

$selectItem = " SELECT * FROM `employees`";
$result = mysqli_query($conn, $selectItem);
// $row = mysqli_fetch_assoc($result);
$count = 0;


?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>Document</title>
</head>

<body class="bg-secondary  text-white">
    <div class="container mt-2">
        <div class="row justify-content-center ">
            <h1 class="text-center m-5">employees form</h1>
            <div class="col-xl-8">
                <form action="" class="bg-dark  text-white  form-control" method="post" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label class="form-label" for="name">
                            Name
                        </label>
                        <input type="text" name="name" id="name" value="<?= $name ?>" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="email">
                            email
                        </label>
                        <input type="email" name="email" id="email" value="<?= $email ?>" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="phone">
                            phone
                        </label>
                        <input type="text" name="phone" id="phone" value="<?= $phone ?>" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="image">
                            image
                        </label>
                        <input type="file" name="image" id="image" class="form-control">
                    </div>
                    <div class="form-group mb-2  text-center ">
                        <?php if ($mode == 'create') : ?>
                            <button class="btn btn-success" name="send">Submit</button>
                        <?php else : ?>
                            <button class="btn btn-warning" name="update">Edit</button>
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                        <?php endif; ?>

                    </div>
                </form>
                <hr>
                <table class="table  table-dark text-white text-center">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">Email</th>
                            <th scope="col">image</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $item) : ?>
                            <tr>
                                <td><?= ++$count ?></td>
                                <td><?= $item['name'] ?></td>
                                <td><?= $item['phone'] ?></td>
                                <td><?= $item['email'] ?></td>
                                <td>
                                    <img src="<?= $item['image'] ?>" width="70" alt="">
                                </td>
                                <td>
                                    <div class="d-flex text-center  ">
                                        <a class="btn m-2 btn-warning" href="?edit=<?= $item['id'] ?>" name="edit">Update</a>
                                        <a class="btn m-2 btn-danger" href="?delete=<?= $item['id'] ?>" name="delete">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>