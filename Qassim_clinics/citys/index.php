<?php
session_start();
require_once('../config/config.php');
if (!isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SESSION['user']['role'] != 'admin') {
    header('Location: ../index.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $do = $_POST['do'];

    $formErrors = array();
    if (isset($_POST['name']) && $do == 'add') {

        $name = $_POST['name'];

        $stmt = $con->prepare("INSERT into   citys ( name ) value(?)  ");
        try {
            //code...

            $stmt->execute(array($name));
        } catch (\Throwable $th) {

            $formErrors[] = "Something wrong please try again later.error : $th";
        }
    }
    if (isset($_POST['name']) && isset($_POST['id']) && $do == 'update') {

        $name = $_POST['name'];
        $id = $_POST['id'];

        $stmt = $con->prepare("UPDATE  citys set name = ?  where id = ?  ");
        try {
            //code...

            $stmt->execute(array($name, $id));
        } catch (\Throwable $th) {

            $formErrors[] = "Something wrong please try again later.error : $th";
        }
    }
    if (isset($_POST['id']) && $do == 'delete') {

        $id = $_POST['id'];

        $stmt = $con->prepare("DELETE from   citys where id = ?  ");
        try {
            //code...

            $stmt->execute(array($id));
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
            <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#exampleModal" style="float: right;">
                Add New City
            </button>

            <div class="modal fade" id="Editmodal" tabindex="-1" role="dialog" aria-labelledby="Editmodal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit City</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                            <input type="hidden" name="do" value="update">
                            <input type="hidden" name="id" id="editid">
                            <div class="modal-body">
                                <div class="form-floating">
                                    <label for="floatingPassword">Name</label>
                                    <input type="text" name="name" class="form-control" id="nametoedit" required placeholder="name">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add New City</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                            <input type="hidden" name="do" value="add">
                            <div class="modal-body">
                                <div class="form-floating">
                                    <label for="floatingPassword">Name</label>
                                    <input type="text" name="name" class="form-control" id="name" required placeholder="name">

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <br>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rows = getrows('select * from citys');
                    foreach ($rows as $row) {
                        $name = $row['name'];
                        $id = $row['id']; ?>
                        <tr>
                            <form id="deleteform<?php echo $id ?>" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                <input type="hidden" name="do" value="delete">
                                <input type="hidden" name="id" value="<?php echo $id ?>">
                                <th><?php echo $id ?></th>
                                <th><?php echo $name;
                                    ?></th>
                                <th>
                                    <ul class="list-inline mb-0">
                                        <li class="list-inline-item"><a href="#" onclick='document.getElementById("nametoedit").value="<?php echo $name ?>";document.getElementById("editid").value="<?php echo $id ?>";' data-toggle="modal" data-target="#Editmodal"><i class="ti-marker-alt"></i></a></li>
                                        <li class="list-inline-item"><a href="javascript:{}" onclick="document.getElementById('deleteform<?php echo $id ?>').submit();"><i class="ti-trash"></i></a></li>
                                    </ul>

                                </th>

                            </form>
                        </tr>

                    <?php  }  ?>
                </tbody>

            </table>
        </div>


    </div>

</div>

<?php require_once('../components/footer.php'); ?>