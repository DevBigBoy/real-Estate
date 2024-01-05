<?php
include 'layouts/top.php';

if (isset($_SESSION['admin'])) {
    header('location: ' . ADMIN_URL . 'dashboard.php');
}

if (isset($_POST['form_login'])) {
    try {
        # check that email not be empty
        if ($_POST['email'] == '') {
            throw new Exception("Email can not be empty");
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email is invalid");
        }
        if ($_POST['password'] == '') {
            throw new Exception("Password can not be empty");
        }

        $query = $pdo->prepare("SELECT * FROM users WHERE email=? AND role=?");
        $query->execute([$_POST['email'], 'admin']);
        $total = $query->rowCount();

        if (!$total) {
            throw new Exception("Email is not found");
        } else {
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $password = $row['password'];
                if (!password_verify($_POST['password'], $password)) {
                    throw new Exception("Password does not match");
                }
            }
        }
        $_SESSION['admin'] = $row;
        header('location: ' . ADMIN_URL . 'dashboard.php');
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

?>

<section class="section">
    <div class="container container-login">
        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                <div class="card card-primary border-box">
                    <div class="card-header card-header-auth">
                        <h4 class="text-center">Admin Panel Login</h4>
                    </div>
                    <div class="card-body card-body-auth">
                        <?php
                        if (isset($error_message)) {
                        ?><script>
                        alert("<?php echo $error_message; ?>")
                        </script><?php
                                    }
                                        ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email Address"
                                    value="" autofocus>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg w_100_p" name="form_login">
                                    Login
                                </button>
                            </div>
                            <div class="form-group">
                                <div>
                                    <a href="<?Php echo ADMIN_URL; ?>forget-password.php">
                                        Forget Password?
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
include 'layouts/footer.php';
?>