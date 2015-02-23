<?php 
@session_start();
if (isset($_SESSION['user']) && isset($_SESSION['password'])) {
	header("Location: index.php");
}

include_once 'parts/header.php';

require_once 'lib/password.php';
require_once 'database/User.php';

$err = '';

//check if postback
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$db = new Database();
	$err = User::testSignIn();
}

if (($_SERVER["REQUEST_METHOD"] == "POST") && empty($err)) {
	//insert user to session
	$user = cleanInput($_POST['username']);
	$result = User::getUser($user);
	$_SESSION['user'] = $result[0]['USERNAME'];
	$_SESSION['password'] = $result[0]['PASSWORD'];
	$_SESSION['id'] = $result[0]['USER_ID'];
	header('Location: index.php');
} else {
?>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Login Page</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="POST" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>>
                            <fieldset>
                                <div class="form-group">
                                		<?php if(!empty($err)) { ?>
                                    	<div class="alert alert-danger" role="alert">
                                    	<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                    		<?php echo $err?>
                                    	</div> 
                                    	<?php }?>
                                    <input class="form-control" placeholder="Username" name="username" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>

                                <div>
                                	<input type="submit" value="Login" class="btn-lg btn-block btn btn-success"/>
                               		<input type="button" value="Sign-Up" class="btn-lg btn-block btn btn-danger" onclick="location.href = 'signup.php';"/>
                              	</div>
                                
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
<?php 
include_once 'parts/bottom.php';
}
include_once 'parts/footer.php';
?>
