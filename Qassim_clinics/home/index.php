<?php
session_start();
require_once('../config/config.php');
?>

<?php require_once('../components/header.php'); ?>
<div id="banner-area" class="banner-area" style="background-image: linear-gradient(rgba(0, 0, 0, 0.527),rgba(0, 0, 0, 0.5)),url(https://le-de.cdn-website.com/f3d3fb83be72493d9969d9eec6767316/dms3rep/multi/opt/666-2a3656be-961dc24f-1920w.jpg);position: relative; height: 400px; background-position: 50% 50%; background-size: cover;">
    <div class="banner-text">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="banner-heading">
                        <br><br><br><br>
                        <h1 class="banner-title" style="text-align: center;color :white;font-size: 40px;">Qassim Clinics</h1>
                        <h1 class="banner-text" style="text-align: center;color :white;font-size: 20px;">A web site that collects all Qassim clinics.

</h1>

                        <nav aria-label="breadcrumb">

                        </nav>
                    </div>
                </div><!-- Col end -->
            </div><!-- Row end -->
        </div><!-- Container end -->
    </div><!-- Banner text end -->
</div><!-- Banner area end -->
<br>
<div class="content">

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
            $query = "  approved = 1  ";
            if (isset($_POST['do']) && $_POST['do'] == 'search') {

                $query = "  approved = 1  ";
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

    </form>

</div>
<?php require_once('../components/footer.php'); ?>