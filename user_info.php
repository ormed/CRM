<?php 
include_once 'connection/checkUser.php';
include_once 'database/User.php';

include_once 'parts/header.php';
?>
<body>

    <div id="wrapper">

        <?php include_once 'parts/nav.php';?>

        <!-- Page Content -->
        <div id="page-wrapper">
        
            <div class="container-fluid">
            
            
            
            
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">User Info</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
       
                <div class="panel panel-default">
                        <div class="panel-heading">
                            Information
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form">
                                    	<?php 
                                    	debug($_SESSION['user']);
                                    	$results = User::getUser($_SESSION['user']);
                                    	?>
                                    	
                                        <div class="form-group">
                                            <label>Username:</label>
                                            <input class="form-control" id="disabledInput" type="text" placeholder="<?php echo $results[0]["USERNAME"];?>" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input class="form-control" id="disabledInput" type="text" placeholder="<?php echo $results[0]["FIRST_NAME"];?>" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input class="form-control" id="disabledInput" type="text" placeholder="<?php echo $results[0]["LAST_NAME"];?>" disabled>
                                        </div>

                                        <input type=button class="btn btn-default" onClick="location='user_edit.php'" value='Edit'>
                                        
                                    </form>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                         
                
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    
<?php 
include_once 'parts/bottom.php';
include_once 'parts/footer.php';
?>
