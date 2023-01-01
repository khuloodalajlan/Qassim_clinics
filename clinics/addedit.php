<?php
session_start();
require_once('../config/config.php');
if (!isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {



    $do = $_POST['do'];

    $formErrors = array();
    if (isset($_POST['name']) && $do == 'add') //we can check the existanse of all the field but as long as they are not required we won't check
    {

        $name = $_POST['name'];
        $date = md5(date('H:i:s'));
        $filename = $_FILES["image"]["name"];

        $tempname = $_FILES["image"]["tmp_name"];

        $folder = "../uploads/" . $date . $filename;
        move_uploaded_file($tempname, $folder);

        $city_id = $_POST['city_id'];
        $addresse = $_POST['addresse'];
        $addresse_link = $_POST['addresse_link'];

        $contact_info = $_POST['contact_info'];
        $approved = 0;
        if ($_SESSION['user']['role'] == 'admin') {
            $approved = 1;
        }
        try {
            $stmt = $con->prepare("INSERT into   clinics ( name,image,city_id,addresse_link,addresse,contact_info,user_id,approved  ) value(?,?,?,?,?,?,?,?)  ");
            $stmt->execute(array($name, $date . $filename, $city_id, $addresse_link, $addresse, $contact_info, $_SESSION['id'], $approved));
            $id = $con->lastInsertId();

            $i = 0;
            foreach ($_POST['service_name'] as $name) {
                $price =  $_POST['service_price'][$i];
                $stmt = $con->prepare("INSERT into   clinc_services ( id_clinic,name,price ) value(?,?,?)  ");
                $stmt->execute(array($id, $name, $price));
                $i++;
            }
            foreach ($_POST['categorys'] as $category) {

                $stmt = $con->prepare("INSERT into   clinc_categorys ( id_clinic,id_category ) value(?,?)  ");
                $stmt->execute(array($id, $category));
            }
            header('Location: index.php');
            exit();
        } catch (\Throwable $th) {

            $formErrors[] = "Something wrong please try again later.error : $th";
        }
    }
    if (isset($_POST['name']) && isset($_POST['id_clinic']) && $do == 'update') {

        $id_clinic = $_POST['id_clinic'];
        $name = $_POST['name'];
        $date = md5(date('H:i:s'));



        $city_id = $_POST['city_id'];
        $addresse = $_POST['addresse'];
        $addresse_link = $_POST['addresse_link'];

        $contact_info = $_POST['contact_info'];
        $approved = 0;
        if ($_SESSION['user']['role'] == 'admin') {
            $approved = 1;
        }
        try {
            if (!empty($_FILES['image']['name'])) {
                $filename = $_FILES["image"]["name"];

                $tempname = $_FILES["image"]["tmp_name"];

                $folder = "../uploads/" . $date . $filename;
                move_uploaded_file($tempname, $folder);

                $stmt = $con->prepare("UPDATE    clinics  set  name = ? ,image= ? ,city_id = ? ,addresse_link= ? ,addresse= ? ,contact_info= ?,approved = $approved  where id = ? ");
                $stmt->execute(array($name, $date . $filename, $city_id, $addresse_link, $addresse, $contact_info, $id_clinic));
            } else {
                $stmt = $con->prepare("UPDATE    clinics  set  name = ? , city_id = ? ,addresse_link= ? ,addresse= ? ,contact_info= ?,approved = $approved  where id = ? ");
                $stmt->execute(array($name,   $city_id, $addresse_link, $addresse, $contact_info, $id_clinic));
            }
            //we delete the last service and category selected by the clinic then we add new 

            $stmt = $con->prepare("DELETE from   clinc_services  where  id_clinic = ?  ");
            $stmt->execute(array($id_clinic));
            $stmt = $con->prepare("DELETE from   clinc_categorys  where  id_clinic = ?  ");
            $stmt->execute(array($id_clinic));
            $id = $id_clinic;

            $i = 0;
            foreach ($_POST['service_name'] as $name) {
                $price =  $_POST['service_price'][$i];
                $stmt = $con->prepare("INSERT into   clinc_services ( id_clinic,name,price ) value(?,?,?)  ");
                $stmt->execute(array($id, $name, $price));
                $i++;
            }
            foreach ($_POST['categorys'] as $category) {

                $stmt = $con->prepare("INSERT into   clinc_categorys ( id_clinic,id_category ) value(?,?)  ");
                $stmt->execute(array($id, $category));
            }
            header('Location: index.php');
            exit();
        } catch (\Throwable $th) {

            $formErrors[] = "Something wrong please try again later.error : $th";
        }
    }
}
if (isset($_GET['id'])) {


    $stmt = $con->prepare("SELECT c.*,ci.name as city_name from clinics c join citys ci on ci.id=c.city_id where c.id = " . $_GET['id']);
    $stmt->execute();

    $get = $stmt->fetch();
}
?>

<?php require_once('../components/header.php'); ?>
<div class="content" style="overflow: auto;">

    <div class="row text-center">

        <div class="col-12">
            <div class="the-errors text-center alert-danger">
                <?php

                if (!empty($formErrors)) {

                    foreach ($formErrors as $error) {

                        echo '<div class="msg-error">' . $error . '</div>';
                    }
                }


                ?>
            </div>
            <br>
        </div>
        <div class="col-12">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="do" <?php if (!isset($get)) { ?> value="add" <?php } else { ?> value="update" <?php } ?> <div class="form-group col-sm-4">
                <div class="row">
                    <?php if (isset($get)) { ?>
                        <input type="hidden" name="id_clinic" value="<?php echo $get['id'] ?>">
                    <?php } ?>
                    <div class="form-group col-sm-4">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="name" <?php if (isset($get)) { ?>value="<?php echo $get['name'] ?>" <?php } ?>>
                    </div>
                    <div class="form-group col-sm-4">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control" <?php if (!isset($get)) { ?> required <?php } ?> placeholder="Image">
                        <?php if (isset($get)) { ?> <img src="../uploads/<?php echo $get['image'] ?>" width="250" height="250" alt="" srcset=""><?php } ?>
                    </div>
                    <div class="form-group col-sm-4">
                        <label>City </label>

                        <select name="city_id" id="city_id" class="form-control">
                            <?php $rows = getrows('select * from citys');
                            foreach ($rows as $row) { ?>
                                <option value="<?php echo $row['id'] ?>" <?php if (isset($get) && $get['city_id'] == $row['id']) { ?> selected <?php } ?>><?php echo $row['name'] ?></option>
                            <?php     }
                            ?>

                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                        <label>Addresse </label>
                        <input type="text" name="addresse" class="form-control" required placeholder="Addresse  " <?php if (isset($get)) { ?>value="<?php echo $get['addresse'] ?>" <?php } ?>>
                    </div>
                    <div class="form-group col-sm-4">
                        <label>Addresse Link</label>
                        <input type="text" name="addresse_link" class="form-control" required placeholder="Addresse Link" <?php if (isset($get)) { ?>value="<?php echo $get['addresse_link'] ?>" <?php } ?>>
                    </div>
                    <div class="form-group col-sm-4">
                        <label>categories </label>

                        <select name="categorys[]" id="categorys" multiple class="form-control">
                            <?php $rows = getrows('select * from categorys');
                            if (isset($get)) {
                                $rows_to_be_selected = getrows('select * from clinc_categorys where id_clinic = ' . $get['id']);
                            }
                            foreach ($rows as $row) { ?>
                                <option value="<?php echo $row['id'] ?>" <?php if (isset($get)) {
                                                                                foreach ($rows_to_be_selected as $key) {
                                                                                    if ($key['id_category'] == $row['id']) {

                                                                            ?> selected <?php }
                                                                                }
                                                                            } ?>><?php echo $row['name'];
                                                                                    ?></option>
                            <?php     }
                            ?>

                        </select>
                    </div>
                    <div class="form-group col-sm-4">
                        <label>Contact info</label>
                        <input type="text" name="contact_info" class="form-control" required placeholder="Contact info" <?php if (isset($get)) { ?>value="<?php echo $get['contact_info'] ?>" <?php } ?>>
                    </div>




                    <div class="form-group col-sm-10">
                        <label>Services</label>
                        <div class="row">
                            <div class="col-sm-5">
                                <label>Name</label>
                                <input type="text" id="service_name" class="form-control" placeholder="Services name">
                            </div>
                            <div class="col-sm-5">
                                <label>Price</label>
                                <input type="number" id="service_price" class="form-control" placeholder="Services name">
                            </div>
                            <div class="col-sm-2">
                                <label></label>
                                <button class="btn btn-primary" type="button" style="height: 100%;" onclick="addtotable()">Add</button>
                            </div>
                        </div><br>
                        <script>
                            function addtotable() {
                                var service_name = document.getElementById('service_name').value;
                                var service_price = document.getElementById('service_price').value;
                                if (service_name && service_price) {
                                    document.getElementById('services_body').innerHTML += "<tr><td><input type='text'   class='form-control' name='service_name[]' value='" + service_name + "'></td> <td><input type='text'   class='form-control' name='service_price[]' value='" + service_price + "'></td> <td><button type='button'  class='btn btn-primary' onclick='deleterow(this)'>Delete</button></td> </tr>";
                                }
                            }
                        </script>

                        <div class="card">

                            <h2>services List </h2>

                            <div class="card-body">
                                <table class="table table-striped" id="my_table">
                                    <thead>

                                        <tr>
                                            <th>Name</th>
                                            <th>price</th>
                                            <th>delete</th>
                                        </tr>
                                    </thead>
                                    <tbody id="services_body">
                                        <?php if (isset($get)) {
                                            $rows = getrows('select * from clinc_services where id_clinic = ' . $get['id']);
                                            foreach ($rows as $row) { ?>
                                                <tr>
                                                    <td><input type='text' class='form-control' name='service_name[]' value="<?php echo $row['name'] ?>"></td>
                                                    <td><input type='text' class='form-control' name='service_price[]' value="<?php echo $row['price'] ?>"></td>
                                                    <td><button type='button' class='btn btn-primary' onclick='deleterow(this)'>Delete</button></td>
                                                </tr>
                                        <?php }
                                        }  ?>
                                    </tbody>
                                </table>
                                <script>
                                    function deleterow(r) {
                                        var i = r.parentNode.parentNode.rowIndex;
                                        document.getElementById("my_table").deleteRow(i);
                                    }
                                </script>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </div>
            </form>

        </div>




    </div>

</div>

<?php require_once('../components/footer.php'); ?>