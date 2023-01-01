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
    if (isset($_POST['full_name']) && isset($_POST['email']) && isset($_POST['role']) && isset($_POST['password']) && $do == 'add') {


        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $role = $_POST['role'];

        $stmt = $con->prepare("INSERT into   users ( full_name , email  , role  , password  ) value(?,?,?,?)  ");
        try {
            //code...

            $stmt->execute(array($full_name, $email, $role, $password));
        } catch (\Throwable $th) {

            $formErrors[] = "Something wrong please try again later.error : $th";
        }
    }
    if (isset($_POST['full_name']) && isset($_POST['email']) && isset($_POST['roletoedit']) && isset($_POST['id']) && $do == 'update') {

        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $roletoedit = $_POST['roletoedit'];

        $id = $_POST['id'];


        try {
            if (empty($password)) {


                $stmt = $con->prepare("UPDATE  users set full_name = ? , email = ? , role = ? where id = ?  ");
                $stmt->execute(array($full_name, $email, $roletoedit, $id));
            } else {
                $stmt = $con->prepare("UPDATE  users set full_name = ? , email = ? , role = ? , password = ? where id = ?  ");
                $stmt->execute(array($full_name, $email, $roletoedit, $password, $id));
            }
        } catch (\Throwable $th) {

            $formErrors[] = "Something wrong please try again later.error : $th";
        }
    }
    if (isset($_POST['id']) && $do == 'delete') {

        $id = $_POST['id'];

        $stmt = $con->prepare("DELETE from   users where id = ?  ");
        try {
            //code...

            if ($stmt->execute(array($id))) {
            }
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
                Add New User
            </button>

            <div class="modal fade" id="Editmodal" tabindex="-1" role="dialog" aria-labelledby="Editmodal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                            <input type="hidden" name="do" value="update">
                            <input type="hidden" name="id" id="editid">
                            <div class="modal-body">
                                <div class="form-floating">
                                    <label>Name</label>
                                    <input type="text" name="full_name" class="form-control" id="nametoedit" required placeholder="name">
                                </div>
                                <div class="form-floating">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" id="emailtoedit" required placeholder="name">
                                </div>
                                <div class="form-floating">
                                    <label>Password <span style="color: red;">Leave the field blank to not change the password</span></label>
                                    <input type="password" name="password" class="form-control" id="passwordtoedit" placeholder="password">
                                </div>
                                <div class="form-floating">
                                    <label>role</label>
                                    <select name="roletoedit" id="roletoedit" class="form-control">
                                        <option value="admin">admin</option>
                                        <option value="user">user</option>
                                    </select>
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
                            <h5 class="modal-title" id="exampleModalLabel">Add New User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                            <input type="hidden" name="do" value="add">
                            <div class="modal-body">
                                <div class="form-floating">
                                    <label>Name</label>
                                    <input type="text" name="full_name" class="form-control" required placeholder="name">
                                </div>
                                <div class="form-floating">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required placeholder="name">
                                </div>
                                <div class="form-floating">
                                    <label>Password </label>
                                    <input type="password" name="password" class="form-control" required placeholder="name">
                                </div>
                                <div class="form-floating">
                                    <label>role</label>
                                    <select name="role" class="form-control">
                                        <option value="admin">admin</option>
                                        <option value="user">user</option>
                                    </select>
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
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rows = getrows('select * from users');
                    foreach ($rows as $row) {
                        $full_name  = $row['full_name'];
                        $email = $row['email'];
                        $role = $row['role'];
                        $id = $row['id']; ?>
                        <tr>
                            <form id="deleteform<?php echo $id ?>" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                <input type="hidden" name="do" value="delete">
                                <input type="hidden" name="id" value="<?php echo $id ?>">
                                <th><?php echo $id ?></th>
                                <th><?php echo $full_name; ?></th>
                                <th><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a> </th>
                                <th><?php echo $role; ?></th>
                                <th>
                                    <ul class="list-inline mb-0">
                                        <li class="list-inline-item"><a href="#" onclick='document.getElementById("nametoedit").value="<?php echo $full_name ?>";   document.getElementById("emailtoedit").value="<?php echo $email ?>";     let role = document.getElementById("roletoedit"); role.value="<?php echo $role ?>" ;    document.getElementById("editid").value="<?php echo $id ?>";' data-toggle="modal" data-target="#Editmodal"><i class="ti-marker-alt"></i></a></li>
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