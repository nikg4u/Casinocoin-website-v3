<?php

class Registration
{

    private $db_conn = null;
    public $reg_success = false;
    public $veri_success = false;
    public $hide_register = 0;
    public $messages = array();


    public function __construct()
    {
        //a user has requested to register lets fire off the newUser method with the data passed through
        if (isset($_POST['register'])) {

            $this->newUser($_POST['user_name'], $_POST['user_email'], $_POST['user_email_repeat'], $_POST['password'], $_POST['password_repeat'], $_POST['pass_hint'], $_POST['pass_hint_question'], $_POST['first_name'], $_POST['last_name']);

        }


    }
    //this method will start a database connection. conf/config.php contains the
    //constansts needed to connect/
    //toDo: create a separate class since this is now redundant with the separation of the login and registration classes
    private function databaseConnection()
    {
        // if connection already exists
        // else start a new connection with the details from conf
        if ($this->db_conn != null) {
            return true;
        } else {
            try {
                $this->db_conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
                return true;
            } catch (PDOException $e) {
                $this->messages[] = MESSAGE_DATABASE_ERROR . $e->getMessage();
                return false;
            }
        }
    }

    //This method is fired off from the constructor. We are checking for numerous errors with the data before we process the registration.
    //Any errors simply exists the method and returns a message to the user.
    private function newUser($user_name, $user_email, $user_email_repeat, $password, $password_repeat, $pass_hint, $pass_hint_question, $first_name, $last_name)
    {
        //we trim on insert so lets trim on check.
        //emails are incase sensitive anyways
        $user_name = trim($user_name);
        $user_email = trim($user_email);

        //Lets check for a number of errors with the registrartion form
        //there is a number of possibilities.
        //These are self explananatory just check out the returned message.
        //The last one is checking for minimum of
        if(empty($user_name)) {
            $this->messages[] = 'Username is empty please enter a valid username';
        } else if(empty($password) || empty($password_repeat)) {
            $this->messages[] = 'Both password fields must be filled in';
        } else if($password != $password_repeat) {
            $this->messages[] = 'Your passwords do not match, please try again';
        } else if(strlen($password) < 8) {
            $this->messages[] = 'Your Password Must be longer than or equal to 8 characters';
        } else if(strlen($user_name) > 32 || strlen($user_name) < 3) {
            $this->messages[] = 'Your username must be less than 32 characters and more than two';
        } else if(empty($user_email)) {
            $this->messages[] = 'Your email cannot be empty';
        } elseif($user_email != $user_email_repeat) {
            $this->messages[] = 'Your emails must match. Please try again';
        } else if(strlen($user_email) > 64) {
            $this->messages[] = 'Emails cannot be longer then 64 characters by default';
        } else if(empty($first_name) || empty($last_name)){
            $this->messages[] = 'Firstname or Lastname cannot be empty.';
        } else if(empty($pass_hint) || empty($pass_hint_question)){
            $this->messages[] = 'Password hint or question cannot be empty';
        }  else if(!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/", $password)) {
            $this->messages[] = 'Your password must contain 8 characters. Atleast one lowercase and uppercase letter. A number and a special character';
        } else if($this->databaseConnection()) {

            //Lets check if there is already a username
            $user_check = $this->db_conn->prepare('SELECT user_name FROM accounts WHERE user_name = :user_name');
            $user_check->bindValue(':user_name', $user_name, PDO::PARAM_STR);
            $user_check->execute();
            $user_check_result = $user_check->fetchAll();

            //Lets check if there is already a users email
            $email_check = $this->db_conn->prepare('SELECT user_email FROM accounts WHERE user_email = :user_email');
            $email_check->bindValue(':user_email', $user_email, PDO::PARAM_STR);
            $email_check->execute();
            $email_check_result = $email_check->fetchAll();

            //first check for a username
            if(count($user_check_result) > 0) {
                $this->messages[] = 'Username already exists, please try again';
            }
            //now check if there is an email
            else if(count($email_check_result) > 0) {
                $this->messages[] = 'Email already exists, please try again';
            }
            //no email or username exist? We should be good to process the registration!
            else {
                //Pre defined hash cost factor.
                //toDO: Move this into a constant
                $hash_cost = 10;
                //Instead of using MD5 We are going to use bcrypt for various security reasons. PASSWORD_DEFAULT check that it has not already been defined.
                //I have been using bcrypt more and more recently and realized it is better then a simple round of md5 hashing.
                //If you have not used PHP's password API check out this link http://php.net/manual/en/function.password-hash.php
                //good explantion on stack excahnge http://security.stackexchange.com/questions/61385/the-brute-force-resistence-of-bcrypt-versus-md5-for-password-hashing
                $user_password_hash = password_hash($password, PASSWORD_DEFAULT, array('cost' => $hash_cost));

                //process the registration with the database
                $new_user = $this->db_conn->prepare('INSERT INTO accounts (user_name, user_email, password_hash, pass_hint, first_name, last_name, reg_ip, reg_datetime, pass_hint_question) VALUES (:user_name, :user_email, :pass_hash, :pass_hint, :first_name, :last_name,  :reg_ip, now(), :pass_hint_question)');
                $new_user->bindValue(':user_name', $user_name, PDO::PARAM_STR);
                $new_user->bindValue(':user_email', $user_email, PDO::PARAM_STR);
                $new_user->bindValue(':pass_hash', $user_password_hash, PDO::PARAM_STR);
                $new_user->bindValue(':pass_hint', $pass_hint, PDO::PARAM_STR);
                $new_user->bindValue(':first_name', $first_name, PDO::PARAM_STR);
                $new_user->bindValue(':last_name', $last_name, PDO::PARAM_STR);
                $new_user->bindValue(':reg_ip', $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
                $new_user->bindValue(':pass_hint_question', $pass_hint_question, PDO::PARAM_STR);
                $new_user->execute();

                if($new_user) {
                    $this->messages[] = 'Your account was created successfully. Please sign in.';
                    $_SESSION['hide_reg'] = 1; //we don't need users registering multiple times in one session thats annoying

                }

            }

        }
    }

}

?>