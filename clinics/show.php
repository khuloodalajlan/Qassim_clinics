<?php
session_start();
require_once('../config/config.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {



    $do = $_POST['do'];

    $formErrors = array();
    if ($do = 'comment' && isset($_POST['id_clinic'])) {
        $id_clinic = $_POST['id_clinic'];
        $comment = $_POST['comment'];
        $name = $_POST['name'];
        $mail = $_POST['mail'];
        $stmt = $con->prepare("INSERT into comments  (id_clinic,name_user,message,email,approved) values (?,?,?,?,?)");
        $approved = 0;
        if (isset($_SESSION['id']) && $_SESSION['user']['role'] == 'admin') {
            $approved = 1;
        }
        $stmt->execute(array($id_clinic, $name, $comment, $mail, $approved));
        if ($approved == 0)
            $formErrors[] = "  Comment Saved waiting For Admin Approval";
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

            <div class="row">

                <div class="form-group col-sm-12">
                    <?php if (isset($get)) { ?> <img src="../uploads/<?php echo $get['image'] ?>" style="width: 100%;" height="400px" alt="" srcset=""><?php } ?>
                    <h2 style="text-align: center;"><?php if (isset($get)) { ?> <?php echo $get['name'] ?><?php } ?></h2>
                    <h4 style="text-align: left;">city :<b> <?php if (isset($get)) { ?> <?php echo $get['city_name'] ?><?php } ?></b></h4>
                    <h4 style="text-align: left;">Addresse :<b> <?php if (isset($get)) { ?> <?php echo $get['addresse'] ?> <?php } ?></b></h4>
                    <h4 style="text-align: left;">Addresse Link:<b><a class="btn btn-primary" href="<?php if (isset($get)) { ?>value=" <?php echo $get['addresse_link'] ?>" <?php } ?>" target="_blank" rel="noopener noreferrer"><i class="ti-location-pin"></i>Click Here</a>
                        </b>
                    </h4>
                    <h4 style="text-align: left;">categories :<b>
                            <ul>

                                <?php
                                if (isset($get)) {
                                    $rows_to_be_selected = getrows('select cc.*,c.name from clinc_categorys cc join categorys c on c.id = cc.id_category   where id_clinic = ' . $get['id']);

                                    foreach ($rows_to_be_selected as $row) { ?>
                                        <?php echo '<li>' . $row['name'] . '</li>' ?>
                                <?php     }
                                }
                                ?>
                            </ul>
                        </b></h4>
                    <h4 style="text-align: left;">Contact info :<b> <?php if (isset($get)) { ?> <?php echo $get['contact_info'] ?><?php } ?></b></h4>
                    <h2 style="text-align: center;">services List </h2>
                    <table class="table table-striped" id="my_table">
                        <thead>

                            <tr>
                                <th>Name</th>
                                <th>price</th>

                            </tr>
                        </thead>
                        <tbody id="services_body">
                            <?php if (isset($get)) {
                                $rows = getrows('select * from clinc_services where id_clinic = ' . $get['id']);
                                foreach ($rows as $row) { ?>
                                    <tr>
                                        <th><input type='text' class='form-control' name='service_name[]' value="<?php echo $row['name'] ?>"></th>
                                        <th><input type='text' class='form-control' name='service_price[]' value="<?php echo $row['price'] ?>"></th>

                                    </tr>
                            <?php }
                            }  ?>
                        </tbody>
                    </table>
                </div>


                <div class="form-group col-sm-8 comment-area">
                    <?php if (isset($get)) {
                        $rows = getrows('select * from comments where  approved = 1 and id_clinic = ' . $get['id']); ?>
                        <div class="comment-area my-5">
                            <h3 class="mb-4 text-center"> Comments</h3>
                            <?php foreach ($rows as $key) { ?>

                                <div class="comment-area-box media">
                                    <img width="150" height="150" alt="" src="../theme/images/blank-profile-picture-973460_1280.png" class="img-fluid float-left mr-3 mt-2">

                                    <div class="media-body ml-4">
                                        <h4 class="mb-0"><?php echo $key['name_user'] ?> </h4>
                                        <span class="date-comm font-sm text-capitalize text-color"><i class="ti-time mr-2"></i><?php echo $key['created_at'] ?> </span>

                                        <div class="comment-content mt-3">
                                            <p><?php echo $key['message'] ?> .</p>
                                        </div>

                                    </div>
                                </div>
                            <?php     } ?>

                        </div>
                    <?php } ?>

                    <form action="<?php echo $_SERVER['PHP_SELF'] ?>?id=<?php echo $get['id'] ?>" method="POST" class="comment-form mb-5 gray-bg p-5" id="comment-form">
                        <h3 class="mb-4 text-center">Leave a comment</h3>
                        <input type="hidden" name="do" value="comment">
                        <input type="hidden" name="id_clinic" value="<?php echo $get['id'] ?>">
                        <div class="row">
                            <div class="col-lg-12">
                                <textarea class="form-control mb-3" name="comment" id="comment" cols="30" rows="5" placeholder="Comment"></textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="name" id="name" required placeholder="Name:">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="form-control" type="email" name="mail" id="mail" required placeholder="Email:">
                                </div>
                            </div>
                        </div>

                        <input class="btn btn-primary" type="submit" name="submit-contact" id="submit_contact" value="Submit Message">
                    </form>

                </div>






            </div>


        </div>




    </div>

</div>

<?php require_once('../components/footer.php'); ?>