<?php
require_once("includes/config.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/FormSanitizer.php");

    $account = new Account($con);

    if(isset($_POST["submitButton"])) {

        $firstName = FormSanitizer::sanitizeFormNames($_POST["firstName"]);
        $lastName = FormSanitizer::sanitizeFormNames($_POST["lastName"]);

        $username = FormSanitizer::sanitizeFormUsername($_POST["username"]);

        $email = FormSanitizer::sanitizeFormEmail($_POST["email"]);
        $email2 = FormSanitizer::sanitizeFormEmail($_POST["email2"]);

        $password = FormSanitizer::sanitizeFormPassword($_POST["password"]);
        $password2 = FormSanitizer::sanitizeFormPassword($_POST["password2"]);

        $success = $account->register($firstName, $lastName, $username, $email, $email2,
                            $password, $password2);

        if($success) {
            // Store session
            header("Location: index.php");
        }

    }

?>
<!DOCTYPE html>
<html>

    <head>
        <title>Welcome to Nesli</title>
        <link rel="stylesheet" type="text/css" href="assets/style/style.css" />
    </head>

    <body>

        <div class="signInContainer">

            <div class="column">

                <div class="header">
                    <img src="assets/images/nesli-logo.png" title="Logo" alt="Site logo"  />
                    <h3>Sign Up</h3>
                    <span>to continue to Nesli</span>
                </div>

                <form method="post">

                    <?php echo $account->getError(Constants::$firstNameCharacters); ?>
                    <input type="text" name="firstName" placeholder="First name" required>

                    <?php echo $account->getError(Constants::$lastNameCharacters); ?>
                    <input type="text" name="lastName" placeholder="Last name" required>

                    <?php echo $account->getError(Constants::$usernameCharacters); ?>
                    <?php echo $account->getError(Constants::$usernameRepeated); ?>
                    <input type="text" name="username" placeholder="Username" required>

                    <?php echo $account->getError(Constants::$emailsNotMatch); ?>
                    <?php echo $account->getError(Constants::$emailNotValid); ?>
                    <?php echo $account->getError(Constants::$emailRepeated); ?>
                    <input type="email" name="email" placeholder="Email" required>

                    <input type="email" name="email2" placeholder="Confirm email" required>

                    <?php echo $account->getError(Constants::$passwordsNotMatch); ?>
                    <?php echo $account->getError(Constants::$passwordCharacters); ?>
                    <input type="password" name="password" placeholder="Password" required>

                    <input type="password" name="password2" placeholder="Confirm password" required>

                    <input type="submit" name="submitButton" value="Submit">
                </form>

                <a href="login.php" class="signInMessage">Already have an account? Sign in here.</a>

            </div>

        </div>

    </body>

</html>
