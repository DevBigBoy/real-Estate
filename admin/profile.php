<?php
include 'layouts/top.php';

if (!isset($_SESSION['admin'])) {
    header('location:' . ADMIN_URL . 'login.php');
}

if (isset($_POST['form_update'])) {
    try {
        if ($_POST['f_name'] == '') {
            throw new Exception("First Name can not be empty");
        }
        if ($_POST['l_name'] == '') {
            throw new Exception("Last Name can not be empty");
        }
        if ($_POST['email'] == '') {
            throw new Exception("Email Name can not be empty");
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception();
        }

        $statement = $pdo->prepare("UPDATE users set Fname=?, Lname=?, email=? WHERE id=?");
        $statement->execute([$_POST['f_name'], $_POST['l_name'], $_POST['email'], $_SESSION['admin']['id']]);

        $success_message = 'profile data is updated successfully!';

        $_SESSION['admin']['Fname'] = $_POST['f_name'];
        $_SESSION['admin']['Lname'] = $_POST['l_name'];
        $_SESSION['admin']['email'] = $_POST['email'];
    } catch (Exception $e) {
        $erro_message = $e->getMessage();
    }
}


?>


<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Profile</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if (isset($erro_message)) { ?>
                                <script>
                                    alert("<?php echo $erro_message; ?>")
                                </script>
                            <?php }
                            if (isset($success_message)) { ?>
                                <script>
                                    alert("<?php echo $success_message; ?>");
                                </script>
                            <?php }   ?>

                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-3">

                                        <?php if ($_SESSION['admin']['photo'] == '') : ?>
                                            <img src="<?php echo BASE_URL; ?>uploads/default.png" alt="" class="profile-photo w_100_p">
                                        <?php else : ?>

                                            <img src="<?php echo BASE_URL; ?>uploads/<?= $_SESSION['admin']['photo']; ?>" alt="" class="profile-photo w_100_p">

                                        <?php endif; ?>

                                        <input type="file" class="mt_10" name="photo">
                                    </div>
                                    <div class="col-md-9">
                                        <div class="mb-4">
                                            <label class="form-label">First Name *</label>
                                            <input type="text" class="form-control" name="f_name" value="<?= $_SESSION['admin']['Fname']  ?>">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Last Name *</label>
                                            <input type="text" class="form-control" name="l_name" value="<?= $_SESSION['admin']['Lname']  ?>">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Email *</label>
                                            <input type="text" class="form-control" name="email" value="<?= $_SESSION['admin']['email']  ?>">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Password</label>
                                            <input type="password" class="form-control" name="new_password">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label">Retype Password</label>
                                            <input type="password" class="form-control" name="retype_password">
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label"></label>
                                            <button type="submit" class="btn btn-primary" name="form_update">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'layouts/footer.php'; ?>