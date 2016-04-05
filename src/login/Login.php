<?php

class Login
{
    private $db_conn = null;
    private $user_id = null;
    private $logged_in = false;
    public $messages = array();

    public function __construct()
    {
        //fix this rubbish
        if (!isset($_GET['userlog'])) {
            $doLog = null;
        } else {
            if ($_GET['userlog'] == "out") {
                $doLog = "out";
            } else {
                $doLog = null;
            }

        }
        //if we want to log out then fire off the userLogout method
        if ($doLog !== null) {

            $this->userLogout();

        } //lets first use the cookie to signin since it will be the quickest and easiest to check for security breaches.
        else if (isset($_COOKIE['rememberme'])) {
            $this->cookieLogin();
        } //does a user have an active session? with the loggin_in boolen as true (1)
        else if (!empty($_SESSION['user_name']) && $_SESSION['logged_in'] == 1) {

            //Log the user in if he is not already
            $this->sessionLogin();

        } //are we trying to login from the form located at not_logged_home.php ?
        else if (isset($_POST['login'])) {

            //user does not want to set the remember me cookie
            if (!isset($_POST['remember_me'])) {
                $_POST['remember_me'] = null;
            }

            //either way cookie or no cookie fire off the login method formLogin()
            $this->formLogin($_POST['user_name'], $_POST['user_password'], $_POST['remember_me']);

        } //the user has requested their password hint question
        else if (isset($_POST['forgot_password'])) {

            //run the passwordHint() method checking if the username and email exist.
            $this->passwordHint($_POST['user_name'], $_POST['user_email']);

        }
        //user has envoked the above and gotten his question and now wants to login with his hint answer.
        //OR the user didn't need his question and is logging in with the hint.
        else if (isset($_POST['forgot_password_login'])) {
            $this->passwordHintLogin($_POST['user_name'], $_POST['user_email'], $_POST['pass_hint_answer']);
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
                $this->messages[] = 'Database Error' . $e->getMessage();
            }
        }
        return false;
    }

    //Will pull a users information from the DB after some validation.
    //Validation is in the method that calls this.
    private function pullUserInfo($user_name)
    {
        //if the connection exists
        if ($this->databaseConnection()) {

            //get all info for the user from the accounts table
            $query_user = $this->db_conn->prepare('SELECT * FROM accounts WHERE user_name = :user_name');
            $query_user->bindValue(':user_name', $user_name, PDO::PARAM_STR);
            $query_user->execute();

            //return the results as an object to be used further
            return $query_user->fetchObject();
        } else {
            return false;
        }
    }

    //We have already logged in so let's refrain from logging in again and just use the session data
    private function sessionLogin()
    {
        $this->user_name = $_SESSION['user_name'];
        $this->user_email = $_SESSION['user_email'];
        $this->logged_in = true;
    }

    //the user has tried to login from the main form lets check several variables and the captcha.
    private function formLogin($user_name, $user_password, $remember_me)
    {
        //These are self explananatory just check out the returned message.
        if (empty($user_name)) {

            $this->messages[] = 'Username cannot be empty';

        }  //we need a password..
        else if (empty($user_password)) {

            $this->messages[] = 'Password cannot be empty';

            //proceed if neither username or password is empty as well captcha is correct.
        } else {
            //no errors envoked above? Lets get the users info so we can start the login process
            if ($this->databaseConnection()) {

                $result_row = $this->pullUserInfo(trim($user_name));

            }
            //if the users doesn't exist then login has failed.
            if (!isset($result_row->user_id)) {

                $this->messages[] = 'Username does not exist';

            }
            //lets now check for the password match with the hash
            //we will use the php api library to process this.
            //if your php ver is below 5.5 then we include the required library through the index
            else if (!password_verify($user_password, $result_row->password_hash)) {

                $this->messages[] = 'Password is Incorrect';
            }
            //the above passed the check so the passwords match we can now process the login request.
            //toDO: I feel like the login with the hint answer is super not secure maybe we should encrypt the value as well
            else {

                //Store relevant information into the session to be used further
                $_SESSION['user_id'] = $result_row->user_id;
                $_SESSION['user_name'] = $result_row->user_name;
                $_SESSION['user_email'] = $result_row->user_email;
                $_SESSION['first_name'] = $result_row->first_name;
                $_SESSION['last_name'] = $result_row->last_name;
                $_SESSION['logged_in'] = 1; //to be used further to check if user is logged in without processing the function again.

                //lets set the user id and logged in to true. The index file will use these next two lines.
                $this->user_id = $result_row->user_id;
                $this->logged_in = true;


                //if the remember me checkbox is checked. I set the value to 1 so it is always set it only makes sense to.
                //if someone really doesn't want to use cookies then it will not work anyways.
                //if someone wanted to they could just edit the html (inspect) and pass 0 and it wouldn't.
                if (isset($remember_me)) {
                    $this->newRememberMe();
                } else {
                    // Reset remember-me token
                    $this->deleteRememberMe();
                }

            }
        }
    }

    //Lets create a new remember me token to be stored in the accounts table that will be used by the login with cookie data method
    private function newRememberMe()
    {
        //if the connection exists
        if ($this->databaseConnection()) {

            //we will create a sha256 random string to be used for the remember me token
            $random_token = hash('sha256', mt_rand());
            $sth = $this->db_conn->prepare("UPDATE accounts SET user_rememberme_token = :user_rememberme_token WHERE user_id = :user_id");

            $sth->execute(array(':user_rememberme_token' => $random_token, ':user_id' => $_SESSION['user_id']));

            // generate cookie string that consists of userid, randomstring and combined hash of both
            $cookie_start = $_SESSION['user_id'] . ':' . $random_token;
            $cookie_hash = hash('sha256', $cookie_start . 'CPD1234aMs33{dd.aA');
            $cookie_string = $cookie_start . ':' . $cookie_hash;

            // set cookie
            setcookie('rememberme', $cookie_string, time() + 1209600, "/", ".127.0.0.1");
        }
    }

    //we created the cookie above now we can use this method to delete if from the db and the actual cookie session
    private function deleteRememberMe()
    {
        //if the connection exists
        if ($this->databaseConnection() && isset($_SESSION['user_id'])) {
            //delete the token from the database always setting it to null
            $sth = $this->db_conn->prepare("UPDATE accounts SET user_rememberme_token = NULL WHERE user_id = :user_id");
            $sth->execute(array(':user_id' => $_SESSION['user_id'])); //we use the session id incase the user_id has been erased
        }

        setcookie('rememberme', false, time() - (3600 * 3650), '/', ".127.0.0.1");
    }

    private function cookieLogin()
    {
        //has the user clicked the rememberme button on a previous login?
        //ifso this will fire off.
        if (isset($_COOKIE['rememberme'])) {

            //list the cookie data as its relevant varaible.
            //we stored it as a unique string above.
            list ($user_id, $token, $hash) = explode(':', $_COOKIE['rememberme']);

            //does the cookie check out as a valid cookie created by this class?
            if ($hash == hash('sha256', $user_id . ':' . $token . 'CPD1234aMs33{dd.aA') && !empty($token)) {

                //if the connection exists
                if ($this->databaseConnection()) {

                    //select the users info by the cookie data.
                    //cookie cannot be empty
                    $sth = $this->db_conn->prepare("SELECT user_id, user_name, user_email, first_name, last_name FROM accounts WHERE user_id = :user_id
                                                      AND user_rememberme_token = :user_rememberme_token AND user_rememberme_token IS NOT NULL");

                    $sth->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                    $sth->bindValue(':user_rememberme_token', $token, PDO::PARAM_STR);
                    $sth->execute();

                    //we return the results as an object so we can store the relevant information below.
                    $result_row = $sth->fetchObject();
                    //does the user exist with that cookie ?
                    if (isset($result_row->user_id)) {

                        //Store relevant information into the session to be used further
                        $_SESSION['user_id'] = $result_row->user_id;
                        $_SESSION['user_name'] = $result_row->user_name;
                        $_SESSION['user_email'] = $result_row->user_email;
                        $_SESSION['first_name'] = $result_row->first_name;
                        $_SESSION['last_name'] = $result_row->last_name;
                        $_SESSION['logged_in'] = 1; //to be used further to check if user is logged in without processing the function again.

                        //lets set the user id and logged in to true. The index file will use these next two lines.
                        $this->user_id = $result_row->user_id;
                        $this->logged_in = true;

                        // Cookie token usable only once
                        $this->newRememberMe();
                        return true;
                    }
                }
            }
            //cookie attempt but not valid? delete it and provide the something went wrong cookie message.
            //if someone changes the cookie this will be processed.
            $this->deleteRememberMe();
            $this->messages[] = 'Something went wrong with the cookie.';
        }
        return false;
    }

    //user has requested his password hint
    private function passwordHint($user_name, $user_email)
    {
        //if the connection exists
        if ($this->databaseConnection()) {

            //These are self explananatory just check out the returned message.
            if (empty($user_name)) {

                $this->messages[] = 'Username cannot be empty';

            } else if (empty($user_email)) {

                $this->messages[] = 'Email cannot be empty';
            } else {

                //we should first check if the user actually exists.
                if ($this->databaseConnection()) {

                    $result_row = $this->pullUserInfo(trim($user_name));

                }
                if (!isset($result_row->user_id)) {

                    $this->messages[] = 'That username does not exist.';

                } else {


                    //if we have valid data from above lets pull the question to display to the user
                    //but first we need to check if the email exists as well.
                    //toDo:Create another method that returns the details via email
                    $query_hint_email_check = $this->db_conn->prepare('SELECT pass_hint_question FROM accounts WHERE user_name = :user_name AND user_email = :user_email');
                    $query_hint_email_check->bindValue(':user_name', $user_name, PDO::PARAM_STR);
                    $query_hint_email_check->bindValue(':user_email', $user_email, PDO::PARAM_STR);
                    $query_hint_email_check->execute();
                    $resultCheck = $query_hint_email_check->fetchAll();

                    //if there is an email in the DB (above check)
                    //fire off the hint question finder.
                    if (count($resultCheck) > 0) {

                        $query_hint = $this->db_conn->prepare('SELECT pass_hint_question FROM accounts WHERE user_name = :user_name AND user_email = :user_email');
                        $query_hint->bindValue(':user_name', $user_name, PDO::PARAM_STR);
                        $query_hint->bindValue(':user_email', $user_email, PDO::PARAM_STR);
                        $query_hint->execute();
                        $result = $query_hint->fetch();

                        $this->messages[] = 'Password Hint Question - ' . $result['pass_hint_question'];

                    } else {
                        $this->messages[] = 'Your email does not exist or it does not match our records for that username.';
                    }
                }
            }

        } //DB connection did not connect
        else {
            $this->messages[] = 'Sorry there is a problem with the database';
        }
    }

    //Above we asked for the question here we will check to see if the answers matches the value saved in the database.
    private function passwordHintLogin($user_name, $user_email, $hint_answer)
    {
        //These are self explananatory just check out the returned message.
        if (empty($user_name)) {

            $this->messages[] = 'Username cannot be empty';

        } else if (empty($user_email)) {

            $this->messages[] = 'Email cannot be empty';

        } else if (empty($hint_answer)) {

            $this->messages[] = 'Hint cannot be empty';

        } else {

            //no errors envoked above? Lets get the users info so we can start the login process
            if ($this->databaseConnection()) {

                $result_row = $this->pullUserInfo(trim($user_name));

            }

            //is there an actual user that exists with that username ?
            if (!isset($result_row->user_id)) {

                $this->messages[] = 'Username does not exist';

            } //We check if the password hint answer passed to the method matches the value in the DB
            else if ($result_row->pass_hint != $hint_answer) {

                $this->messages[] = 'That is NOT your hints question answer';

            }
            //everything above checks out lets do the same thing we do when we login with the form.
            //Store the data into a session.
            else {

                //Store relevant information into the session to be used further
                $_SESSION['user_id'] = $result_row->user_id;
                $_SESSION['user_name'] = $result_row->user_name;
                $_SESSION['user_email'] = $result_row->user_email;
                $_SESSION['first_name'] = $result_row->first_name;
                $_SESSION['last_name'] = $result_row->last_name;
                $_SESSION['logged_in'] = 1; //to be used further to check if user is logged in without processing the function again.

                //lets set the user id and logged in to true. The index file will use these next two lines.
                $this->user_id = $result_row->user_id;
                $this->logged_in = true;


                //if the remember me checkbox is checked. I set the value to 1 so it is always set it only makes sense to.
                //if someone really doesn't want to use cookies then it will not work anyways.
                //if someone wanted to they could just edit the html (inspect) and pass 0 and it wouldn't.
                if (isset($remember_me)) {
                    $this->newRememberMe();
                } else {
                    // Reset remember-me token
                    $this->deleteRememberMe();
                }

            }
        }
    }

    //process the logout
    public function userLogout()
    {
        //envoke the above method to destory the cookie even if there is none.
        $this->deleteRememberMe();
        $_SESSION = array(); //set the session to empty
        session_destroy(); //destory the session
        $this->logged_in = false; //this will enovke the not_logged_in page

        //because we are using GET lets redirect to the login page to remove that ugly url
        $header = WEBSITE_PATH;
        header('Location: ' . $header);
        $this->messages[] = 'You are now logged out';
    }

    //get the login state
    public function usersLoggedIn()
    {
        return $this->logged_in;
    }

}

?>