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

    if (isset($_POST['id']) && $do == 'approve') {


        $id = $_POST['id'];


        try {
            //code...
            $stmt = $con->prepare("UPDATE  comments set approved = 1  where id = ?  ");

            $stmt->execute(array($id));
        } catch (\Throwable $th) {

            $formErrors[] = "Something wrong please try again later.error : $th";
        }
    }
    if (isset($_POST['id']) && $do == 'delete') {

        $id = $_POST['id'];

        $stmt = $con->prepare("DELETE from   comments where id = ?  ");
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



            <br>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Clinic</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Comment</th>
                        <th>Created at</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rows = getrows('select * from comments');
                    foreach ($rows as $row) {
                        $name_user = $row['name_user'];
                        $message = $row['message'];
                        $email = $row['email'];
                        $created_at = $row['created_at'];

                        $id = $row['id']; ?>
                        <tr>
                            <form id="approveform<?php echo $id ?>" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                <input type="hidden" name="do" value="approve">
                                <input type="hidden" name="id" value="<?php echo $id ?>">

                            </form>
                            <form id="deleteform<?php echo $id ?>" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                <input type="hidden" name="do" value="delete">
                                <input type="hidden" name="id" value="<?php echo $id ?>">
                                <th><?php echo $id ?></th>
                                <th><a href="../clinics/show.php?id=<?php echo $row['id_clinic'] ?>"><i class="ti-eye"></i></a></th>
                                <th><?php echo $name_user ?></th>
                                <th><a href="mailto:<?php echo $email ?>"><?php echo $email ?></a> </th>

                                <th><?php echo $message; ?></th>
                                <th><?php echo $created_at; ?> </th>
                                <th>
                                    <ul class="list-inline mb-0">
                                        <li class="list-inline-item">
                                            <?php if ($row['approved'] == 1) echo "Approved";
                                            else { ?>
                                                <a href="javascript:{}" onclick="document.getElementById('approveform<?php echo $id ?>').submit();">Click to Approve</a>
                                            <?php } ?>

                                        </li>
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