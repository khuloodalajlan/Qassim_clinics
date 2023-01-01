<?php
session_start();
require_once('../config/config.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $do = $_POST['do'];

    $formErrors = array();

    if (isset($_POST['id']) && $do == "approve") {
        $id_clinic = $_POST['id'];

        try {

            $stmt = $con->prepare("UPDATE clinics set approved  = 1  where id = ?  ");
            $stmt->execute(array($id_clinic));
        } catch (\Throwable $th) {

            $formErrors[] = "Something wrong please try again later.error : $th";
        }
    }

    if (isset($_POST['id']) && $do == 'delete') {

        $id_clinic = $_POST['id'];

        try {
            //code...
            $stmt = $con->prepare("DELETE from   clinc_services  where  id_clinic = ?  ");
            $stmt->execute(array($id_clinic));
            $stmt = $con->prepare("DELETE from   clinc_categorys  where  id_clinic = ?  ");
            $stmt->execute(array($id_clinic));
            $stmt = $con->prepare("DELETE from   clinics where id = ?  ");
            $stmt->execute(array($id_clinic));
        } catch (\Throwable $th) {

            $formErrors[] = "Something wrong please try again later.error : $th";
        }
    }
}
?>

<?php require_once('../components/header.php'); ?>

<div class="content">

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
            <?php if (isset($_SESSION['id'])) { ?>
                <a href="addedit.php" class="btn btn-primary pull-right" style="float: right;">
                    Add New Clinic
                </a>

            <?php if ($_SESSION['user']['role'] == 'admin') {

                    $rows = getrows('SELECT c.*,ci.name as city_name from clinics c join citys ci on ci.id=c.city_id ');
                    $rowcount = 100;
                } else if ($_SESSION['user']['role'] == 'user') {
                    $rows = getrows('SELECT c.*,ci.name as city_name from clinics c join citys ci on ci.id=c.city_id where user_id = ' . $_SESSION['id']);
                    $rowcount = getrowscount('SELECT count(*) from clinics   where user_id = ' . $_SESSION['id']);
                }
            } else
                $rows = getrows('SELECT c.*,ci.name as city_name from clinics c join citys ci on ci.id=c.city_id where approved = 1');
            ?>

            <?php if ($rows > 0) {
                if (isset($_SESSION['id']) && $rowcount > 0) {   ?>
                    <div class="table-responsive">
                        <table class="table table-striped ">
                            <thead>

                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <?php if ($_SESSION['user']['role'] == 'admin') { ?> <th>Approved</th> <?php } ?>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                foreach ($rows as $row) {
                                    $name = $row['name'];
                                    $image = $row['image'];

                                    $id = $row['id']; ?>
                                    <tr>
                                        <form id="approveform<?php echo $id ?>" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                            <input type="hidden" name="do" value="approve">
                                            <input type="hidden" name="id" value="<?php echo $id ?>">

                                        </form>
                                        <form id="deleteform<?php echo $id ?>" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                            <input type="hidden" name="do" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $id ?>">
                                            <th> <a href="show.php?id=<?php echo $id ?>"><i class="ti-eye"></i></a> <?php echo $id ?></th>
                                            <th><?php echo $name; ?></th>
                                            <th><img src="../uploads/<?php echo $image; ?>" width="250" height="250" alt="" srcset=""> </th>
                                            <?php if ($_SESSION['user']['role'] == 'admin') { ?> <th><?php if ($row['approved'] == 1) echo "Approved";
                                                                                                        else { ?>
                                                        <a href="javascript:{}" onclick="document.getElementById('approveform<?php echo $id ?>').submit();">Click to Approve</a>
                                                    <?php } ?>
                                                </th><?php } ?>
                                            <th>
                                                <ul class="list-inline mb-0">
                                                    <li class="list-inline-item"><a href="addedit.php?id=<?php echo $id ?>"><i class="ti-marker-alt"></i></a></li>
                                                    <li class="list-inline-item"><a href="javascript:{}" onclick="document.getElementById('deleteform<?php echo $id ?>').submit();"><i class="ti-trash"></i></a></li>
                                                </ul>

                                            </th>

                                        </form>
                                    </tr>

                                <?php  }  ?>
                            </tbody>

                        </table>
                    </div> <?php } ?>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                    <input type="hidden" name="do" value="search">
                    <div class="row">

                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <div class="form-floating">
                                <label>Name</label>
                                <input type="text" name="name" id="name" class="form-control">

                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <div class="form-floating">
                                <label>Category</label>
                                <select name="category_id" id="category_id" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $citys = getrows('SELECT * from   categorys   ');

                                    foreach ($citys as $key) { ?>
                                        <option value="<?php echo $key['id'] ?>"><?php echo $key['name'] ?></option>
                                    <?php  } ?>
                                </select>

                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <div class="form-floating">
                                <label>City</label>
                                <select name="city_id" id="city_id" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $citys = getrows('SELECT * from   citys   ');

                                    foreach ($citys as $key) { ?>
                                        <option value="<?php echo $key['id'] ?>"><?php echo $key['name'] ?></option>
                                    <?php  } ?>
                                </select>

                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <div class="form-floating">
                                <button type="submit" class="btn btn-primary" style="height: 80px;">Search</button>


                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <hr>
                        </div>
                        <?php
                        $query = "1=1  ";
                        if (isset($_POST['do']) && $_POST['do'] == 'search') {

                            $query = "1=1  ";
                            if (isset($_POST['name']) && !empty($_POST['name'])) {


                                $query = " $query  and c.name like '%" . $_POST['name'] . "%'  ";
                            }
                            if (isset($_POST['category_id']) && !empty($_POST['category_id']))
                                $query = " $query  and c.id  in (select id_clinic from clinc_categorys where id_category = " . $_POST['category_id'] . " )";
                            if (isset($_POST['city_id']) && !empty($_POST['city_id']))
                                $query = " $query  and city_id  = " . $_POST['city_id'] . "   ";
                            $rows = getrows('SELECT c.*,ci.name as city_name from clinics c join citys ci on ci.id=c.city_id  where ' . $query);
                        } else
                            $rows = getrows('SELECT c.*,ci.name as city_name from clinics c join citys ci on ci.id=c.city_id  where ' . $query);

                        foreach ($rows as $row) {
                            $name = $row['name'];
                            $image = $row['image'];

                            $id = $row['id']; ?>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <article class="post-grid mb-5 ">
                                    <a class="post-thumb mb-4 d-block" href="show.php?id=<?php echo $id ?>">
                                        <img src="../uploads/<?php echo $image; ?>" alt="" class="img-fluid w-100">
                                    </a>

                                    <div class="post-content-grid">
                                        <div class="label-date">

                                            <span class="month text-uppercase"><?php echo $row['city_name'] ?></span>
                                        </div>

                                        <h3 class="post-title mt-1"><a href="show.php?id=<?php echo $id ?>"><?php echo $row['name'] ?></a></h3>

                                    </div>
                                </article>
                            </div>


                        <?php } ?>
                    </div>

                </form>

            <?php     } ?>




            <br>
        </div>





    </div>

</div>

<?php require_once('../components/footer.php'); ?>