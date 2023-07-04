<?php
include 'config/config.php';
LimitToAdmins('index.php');

// ADD USER
if (isset($_POST['submitadd'])) {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf']))
        die();

    $ProductAdd = [
        "product_category" => $_POST['product_category'],
        "product_name" => $_POST['product_name'],
        "product_description" => $_POST['product_description'],
        "product_price" => $_POST['product_price']
    ];

    $sqlAddProduct = "INSERT INTO products (category, name, description, price) VALUES (:product_category, :product_name, :product_description, :product_price)";

    $statement = $DB_DSN->prepare($sqlAddProduct);
    if ($statement->execute($ProductAdd)) {
        // Update successful
        $message = "User information edited.";
        $messagetype = "success";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        // Error occurred
        $errorInfo = $statement->errorInfo();
        $errorMessage = $errorInfo[2];
        $message = "Error updating user information: " . $errorMessage;
        $messagetype = "error";
    }
}
// EDIT USER
if (isset($_GET['edit']) and isset($_GET['id'])) {
    $DataRow = LoadDataRow('users', 'id', $_GET['id']);

    if (isset($_POST['submitedit'])) {
        if (!hash_equals($_SESSION['csrf'], $_POST['csrf']))
            die();

        $userStatus = isset($_POST['user_status']) ? intval($_POST['user_status']) : 0;

        $userAdmin = isset($_POST['user_admin']) ? intval($_POST['user_admin']) : 0;


        $User = [
            "user_id" => $_POST['user_id'],
            "user_email" => $_POST['user_email'],
            "user_firstname" => $_POST['user_firstname'],
            "user_lastname" => $_POST['user_lastname'],
            "user_level" => $_POST['user_level'],
            "user_status" => $userStatus,
            "user_admin" => $userAdmin,
            "user_phone" => $_POST['user_phone']
        ];

        $sql = "UPDATE users
        SET email = :user_email,
            firstName = :user_firstname,
            lastName = :user_lastname,
            level = :user_level,
            phone = :user_phone,
            active = :user_status,
            admin = :user_admin
        WHERE id = :user_id";

        $statement = $DB_DSN->prepare($sql);
        if ($statement->execute($User)) {
            // Update successful
            LoadDataRow('users', 'id', $_GET['id']);
            $message = "User information edited.";
            $messagetype = "success";
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            // Error occurred
            $errorInfo = $statement->errorInfo();
            $errorMessage = $errorInfo[2];
            $message = "Error updating user information: " . $errorMessage;
            $messagetype = "error";
        }
    }
}
// EDIT USER PW
if (isset($_GET['editpw']) and isset($_GET['id'])) {
    $DataRow = LoadDataRow('users', 'id', $_GET['id']);
    if (isset($_POST['submiteditpw'])) {
        if (!hash_equals($_SESSION['csrf'], $_POST['csrf']))
            die();

        $user = [
            "user_id" => $_POST['user_id'],
            "user_email" => $_POST['user_email'],
            "user_password" => password_hash($_POST['user_password'], PASSWORD_BCRYPT)
        ];

        $sql = "UPDATE users 
                SET password = :user_password,
                email = :user_email
                WHERE id = :user_id";

        // Check if password is empty
        if (empty(trim($_POST["user_password"]))) {
            $message = "Please enter password.";
            $messagetype = "danger";
        } else {
            $statement = $DB_DSN->prepare($sql);
            $statement->execute($user);
        }

        if ($statement) {
            LoadDataRow('users', 'id', $_GET['id']);
            $message = "User password edited.";
            $messagetype = "success";
        }
    }
}
// DELETE PRODUCT
if (isset($_GET['delete']) and isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "DELETE FROM users WHERE id = :id";

    $statement = $DB_DSN->prepare($sql);
    $statement->bindValue(':id', $id);
    $statement->execute();

    if ($statement) {
        LoadDataRow('users', 'id', $_GET['id']);
        $message = "User deleted.";
        $messagetype = "success";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'include/head.php' ?>
    <title>
        <?= $WebsiteSettings['name'] ?> - Users
    </title>

    <!-- Custom styles for this page -->
    <link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include 'include/sidebar.php' ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'include/topbar.php' ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-2 text-gray-800">Utilisateurs</h1>

                    <!-- Add view -->
                    <?php if (isset($_GET['add'])): ?>
                        <div class="col-xl-8">
                            <!-- Account details card-->
                            <div class="card mb-4">
                                <div class="card-header">Edit product</div>
                                <div class="card-body">
                                    <form method="post">
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-3">
                                            <!-- Form Group (first name)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="product_name">Name</label>
                                                <input class="form-control" id="product_name" type="text"
                                                    placeholder="product name" name="product_name" value="">
                                            </div>
                                            <!-- Form Group (last name)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="product_category">Category</label>
                                                <input class="form-control" id="product_category" type="text"
                                                    placeholder="product category" name="product_category" value="">
                                            </div>
                                        </div>
                                        <!-- Form Group (email address)-->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="product_description">Description</label>
                                            <textarea class="form-control" id="product_description" type="description"
                                                placeholder="product description" name="product_description" value=""
                                                rows="2"></textarea>
                                        </div>
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-3">
                                            <!-- Form Group (phone number)-->
                                            <div class="col-md-2">
                                                <label class="small mb-1" for="product_price">Price</label>
                                                <input class="form-control" id="product_price" type="price"
                                                    placeholder="product price" name="product_price" value="">
                                                <input name="csrf" type="hidden" value="<?= $_SESSION['csrf'] ?>">
                                            </div>
                                        </div>
                                        <!-- Save changes button-->
                                        <button class="btn btn-primary" name="submitadd" type="submit"><i
                                                class="fas fa-save"></i> Save changes</button>
                                        <a class="btn btn-warning" href="products.php" role="button"><i
                                                class="fa fa-times"></i> Abort</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Edit view -->
                    <?php elseif (isset($_GET['edit'])): ?>
                        <div class="col-xl-8">
                            <!-- Account details card-->
                            <div class="card mb-4">
                                <div class="card-header"></div>
                                <div class="card-body">
                                    <form method="post">
                                        <div class="row gx-3 mb-3">
                                            <!-- Email -->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="user_email">Email</label>
                                                <input class="form-control" id="user_email" type="text" placeholder="Email"
                                                    name="user_email" value="<?php echo $DataRow['email']; ?>">
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <!-- First Name -->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="user_firstname">First name</label>
                                                <input class="form-control" id="user_firstname" type="text"
                                                    placeholder="First name" name="user_firstname"
                                                    value="<?php echo $DataRow['firstName']; ?>">
                                            </div>
                                            <!-- Last Name -->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="user_lastname">Last name</label>
                                                <input class="form-control" id="user_lastname" type="text"
                                                    placeholder="Last name" name="user_lastname"
                                                    value="<?php echo $DataRow['lastName']; ?>">
                                            </div>
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <!-- Level -->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="user_level">Level</label>
                                                <input class="form-control" id="user_level" type="text" placeholder="Level"
                                                    name="user_level" value="<?php echo $DataRow['level']; ?>">
                                            </div>
                                            <!-- Phone -->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="user_phone">Phone</label>
                                                <input class="form-control" id="user_phone" type="text" placeholder="Phone"
                                                    name="user_phone" value="<?php echo $DataRow['phone']; ?>">
                                            </div>
                                        </div>
                                        <!-- Account Active -->
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input class="custom-control-input" type="checkbox" id="user_status"
                                                    name="user_status" value="1" <?php if ($DataRow['active']): ?> checked
                                                    <?php endif; ?>>
                                                <label class="custom-control-label" for="user_status">Account active
                                                </label>
                                            </div>


                                            <!-- Admin Status -->
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox small">
                                                    <input class="custom-control-input" type="checkbox" id="user_admin"
                                                        name="user_admin" value="1" <?php if ($DataRow['admin']): ?> checked
                                                        <?php endif; ?>>
                                                    <label class="custom-control-label" for="user_admin">Admin Status
                                                    </label>
                                                </div>

                                            </div>
                                            <!-- Form Row-->
                                            <div class="row gx-3 mb-3">

                                                <div class="col-md-6">
                                                    <input type="hidden" class="form-control" id="user_id" type="int"
                                                        placeholder="ID" name="user_id"
                                                        value="<?php echo $DataRow['id']; ?>">
                                                    <input name="csrf" type="hidden" value="<?= $_SESSION['csrf'] ?>">
                                                </div>
                                            </div>
                                            <!-- Save changes button-->
                                            <button class="btn btn-primary" name="submitedit" type="submit"><i
                                                    class="fas fa-pen"></i> Edit</button>
                                            <a class="btn btn-warning"
                                                href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                                                role="button"><i class="fa fa-times"></i> Abort</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php elseif (isset($_GET['editpw'])): ?>
                            <div class="col-xl-8">
                            <!-- Account details card-->
                            <div class="card mb-4">
                                <div class="card-header"><?php echo $DataRow['email']; ?> - Change password</div>
                                <div class="card-body">
                                    <form method="post">
                                        <div class="row gx-3 mb-3">
                                            <!-- Email -->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="user_email">Email</label>
                                                <input class="form-control" id="user_email" type="text" placeholder="Email"
                                                    name="user_email" value="<?php echo $DataRow['email']; ?>" readonly>
                                            </div>
                                            <!-- Last Name -->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="user_password">New password</label>
                                                <input class="form-control" id="user_password" type="password"
                                                    placeholder="New password" name="user_password">
                                            </div>
                                        </div>

                                            <!-- Form Row-->
                                            <div class="row gx-3 mb-3">

                                                <div class="col-md-6">
                                                    <input type="hidden" class="form-control" id="user_id" type="int"
                                                        placeholder="ID" name="user_id"
                                                        value="<?php echo $DataRow['id']; ?>">
                                                    <input name="csrf" type="hidden" value="<?= $_SESSION['csrf'] ?>">
                                                </div>
                                            </div>
                                            <!-- Save changes button-->
                                            <button class="btn btn-primary" name="submiteditpw" type="submit"><i
                                                    class="fas fa-pen"></i> Edit</button>
                                            <a class="btn btn-warning"
                                                href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                                                role="button"><i class="fa fa-times"></i> Abort</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary"></h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle"
                                            href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?add" role="button">
                                            <i class="fas fa-plus fa-sm fa-fw"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Email</th>
                                                    <th>Name</th>
                                                    <th>Level</th>
                                                    <th>Phone</th>
                                                    <th>Active</th>
                                                    <th>Admin</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Email</th>
                                                    <th>Name</th>
                                                    <th>Level</th>
                                                    <th>Phone</th>
                                                    <th>Active</th>
                                                    <th>Admin</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php
                                                LoadAllRows('users');
                                                foreach ($AllRows as $user):
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $user["id"]; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $user["email"]; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $user["firstName"]; ?>
                                                            <?php echo $user["lastName"]; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $user["level"]; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $user["phone"]; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($user["active"] == true): ?> ✅
                                                            <?php else: ?>❌
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($user["admin"] == true): ?> ✅
                                                            <?php else: ?>❌
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <a href="users.php?edit&id=<?php echo ($user["id"]); ?>"
                                                                class="btn btn-outline-warning btn-sm"><i
                                                                    class="fas fa-pen"></i></a>
                                                            <a href="users.php?editpw&id=<?php echo ($user["id"]); ?>"
                                                                class="btn btn-outline-primary btn-sm"><i
                                                                    class="fas fa-key"></i></a>
                                                            <a href="users.php?delete&id=<?php echo ($user["id"]); ?>"
                                                                class="btn btn-outline-danger btn-sm"><i
                                                                    class="fas fa-times"></i></a>
                                                        </td>
                                                    </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                    <?php endif ?>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->


    <!-- Bootstrap core JavaScript-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="assets/js/demo/datatables-demo.js"></script>

</body>

</html>