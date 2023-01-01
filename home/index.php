<?php
session_start();
require_once('../config/config.php');
?>

<?php require_once('../components/header.php'); ?>

<div class="content">
    <div class="row">
        <?php
        $rows = getrows('SELECT c.*,ci.name as city_name from clinics c join citys ci on ci.id=c.city_id where approved = 1');
        foreach ($rows as $row) {
            $name = $row['name'];
            $image = $row['image'];

            $id = $row['id']; ?>
            <div class="col-lg-4 col-md-4 col-sm-6">
                <article class="post-grid mb-5 ">
                    <a class="post-thumb mb-4 d-block" href="../clinics/show.php?id=<?php echo $id ?>">
                        <img src="../uploads/<?php echo $image; ?>" alt="" class="img-fluid w-100">
                    </a>

                    <div class="post-content-grid">
                        <div class="label-date">

                            <span class="month text-uppercase"><?php echo $row['city_name'] ?></span>
                        </div>

                        <h3 class="post-title mt-1"><a href="../clinics/show.php?id=<?php echo $id ?>"><?php echo $row['name'] ?></a></h3>

                    </div>
                </article>
            </div>


        <?php } ?>
    </div>

</div>
<?php require_once('../components/footer.php'); ?>