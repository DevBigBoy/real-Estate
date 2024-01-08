<?php
include 'layouts/top.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

if (isset($_POST['form_forget_password'])) {
    try {
        if ($_POST['email'] == '') {
            throw new Exception("Email can not be empty");
        }
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email is invalid");
        }

        $query = $pdo->prepare("SELECT * FROM admins WHERE email=?");
        $query->execute([$_POST['email']]);
        $total = $query->rowCount();
        if (!$total) {
            throw new Exception("Email is Not found");
        }

        $token = time();
        $statement = $pdo->prepare("UPDATE admins SET token=? WHERE email=?");
        $statement->execute([$token, $_POST['email']]);

        $email_message = "Please click on the following link in order to reset the password: ";
        $email_message .= '<a href="' . ADMIN_URL . 'reset-password.php?email=' . $_POST['email'] . '&token=' . $token . '">Reset Password</a>';
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_ENCRYPTION;
            $mail->Port       = SMTP_PORT;

            // Recipients
            $mail->setFrom(SMTP_FROM);
            $mail->addAddress($_POST['email']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Reset password';
            $mail->Body    = $email_message;
            $mail->send();
            $success_message = 'Please check your email and follow the steps.';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        $success_message = "Please check your email and follow the instruction to reset the password.";
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
                        <h4 class="text-center">Reset Password</h4>
                    </div>
                    <div class="card-body card-body-auth">
                        <?php if (isset($error_message)) { ?>
                            <script>
                                alert("<?php echo $error_message; ?>")
                            </script>
                        <?php   }
                        if (isset($success_message)) { ?>
                            <script>
                                alert("<?php echo $success_message; ?>")
                            </script>
                        <?php   } ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email Address" value="" autofocus>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg w_100_p" name="form_forget_password">
                                    Send Password Reset Link
                                </button>
                            </div>
                            <div class="form-group">
                                <div>
                                    <a href="<?php echo ADMIN_URL; ?>login.php">
                                        Back to login page
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
<?php include 'layouts/footer.php'; ?>