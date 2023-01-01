<?php
session_start();
require_once('../config/config.php');
if (isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $formErrors = array();
    if (isset($_POST['email']) && isset($_POST['password'])) {

        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $con->prepare("SELECT 
        *
    FROM 
        users 
    WHERE
        email = ? 
    AND 
        Password = ?   ");

        $stmt->execute(array($email, $password));

        $get = $stmt->fetch();

        $count = $stmt->rowCount();

        //If Count > 0 This mean the Database Contain Record about This Username

        if ($count > 0) {
            $_SESSION['user'] = $get;

            $_SESSION['id'] = $get['id'];
            header('Location: ../index.php');
            exit();
        } else {

            $formErrors[] = "These credentials do not match our records.";
        }
    }
}
?>

<?php require_once('../components/header.php'); ?>
<style>
    body {

        align-items: center;

        background-color: #f5f5f5;
    }

    .form-signin {
        width: 100%;
        max-width: 630px;
        padding: 15px;
        margin: auto;
    }

    .form-signin .checkbox {
        font-weight: 400;
    }

    .form-signin .form-floating:focus-within {
        z-index: 2;
    }

    .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
</style>
<div class="content">
    <div class="row text-center">

        <main class="form-signin">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <img class="mb-4 img-fluid" src="../theme/images/logo.png" alt="">
                <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
                <div class="the-errors text-center alert-danger">
                    <?php

                    if (!empty($formErrors)) {

                        foreach ($formErrors as $error) {

                            echo '<div class="msg-error">' . $error . '</div>';
                        }
                    }

                    ?>
                </div>
                <div class="form-floating">
                    <label for="floatingInput">Email address</label>
                    <input type="email" name="email" class="form-control" id="floatingInput" required placeholder="name@example.com">

                </div>
                <div class="form-floating">
                    <label for="floatingPassword">Password</label>
                    <input type="password" name="password" class="form-control" required id="floatingPassword" placeholder="Password">

                </div>


                <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>

            </form>
            <br><br>
            Not a Member Yet <a href="register.php" class="btn btn-secondary">Register</a>
        </main>



    </div>

</div>

<?php require_once('../components/footer.php'); ?>