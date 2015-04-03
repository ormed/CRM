<!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">CRM System</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">

                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="user_info.php"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> Menu<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
<!--                                 <li> -->
<!--                                     <a href="blank.php">Blank Page</a> -->
<!--                                 </li> -->
                                <li>
                                    <a href="index.php">Lobby</a>
                                </li>
<!--                                 <li> -->
<!--                                     <a href="login.php">Login Page</a> -->
<!--                                 </li> -->
                                <li>
                                    <a href="order.php">Add Order</a>
                                </li>
                                <li>
                                    <a href="customer.php">Add Customer</a>
                                </li>
                                <li>
                                    <a href="product.php">Add/Edit Product</a>
                                </li>
                                <li>
                                    <a href="all_orders.php">All orders</a>
                                </li>
                                <li>
                                    <a href="all_invoices.php">All invoices</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>