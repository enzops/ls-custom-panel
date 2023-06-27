<?php
include 'config/config.php';

// ADD PRODUCT
if (isset($_POST['submitadd'])) {
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();
    $nomsProduits = $_POST['nom_produit'];
    $prixProduits = $_POST['prix_produit'];
    $produits = [];
    foreach ($nomsProduits as $index => $nomProduit) {
        $prixProduit = $prixProduits[$index];
        // Vérifier si le produit a un nom et un prix valides
        if (!empty($nomProduit) && !empty($prixProduit)) {
            $produits[] = [
                'nom_produit' => $nomProduit,
                'prix_produit' => $prixProduit
            ];
        }
    }
    $ProductsJSON = json_encode($produits);

    $InvoiceAdd = [
        "invoice_customerfirstname"          => $_POST['invoice_customerfirstname'],
        "invoice_customerLastName"              => $_POST['invoice_customerLastName'],
        "invoice_employee"       => $_POST['invoice_employee'],
        "invoice_description"             => $_POST['invoice_description'],
        "invoice_price"             => $_POST['invoice_price'],
    ];

    $sqlAddProduct = "INSERT INTO invoices (customerFirstName, customerLastName, employee, description, price, products) VALUES (:invoice_customerfirstname, :invoice_customerLastName, :invoice_employee, :invoice_description, :invoice_price, '$ProductsJSON')";

    $statement = $DB_DSN->prepare($sqlAddProduct);
    if ($statement->execute($InvoiceAdd)) {
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
// EDIT PRODUCT
if (isset($_GET['edit']) and isset($_GET['id'])) {
    $DataRow = LoadDataRow('invoices', 'id', $_GET['id']);

    if (isset($_POST['submitedit'])) {
        if (!hash_equals($_SESSION['csrf'], $_POST['csrf'])) die();

        $Product = [
            "invoice_customerfirstname"     => $_POST['invoice_customerfirstname'],
            "invoice_customerLastName"      => $_POST['invoice_customerLastName'],
            "invoice_employee"              => $_POST['invoice_employee'],
            "invoice_description"           => $_POST['invoice_description'],
            "invoice_price"                 => $_POST['invoice_price'],
        ];

        $sql = "UPDATE products
        SET category = :product_category,
            name = :product_name,
            description = :product_description,
            price = :product_price
        WHERE id = :product_id";

        $statement = $DB_DSN->prepare($sql);
        if ($statement->execute($Product)) {
            // Update successful
            LoadDataRow('products', 'id', $_GET['id']);
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
// DELETE PRODUCT
if (isset($_GET['delete']) and isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "DELETE FROM invoices WHERE id = :id";

    $statement = $DB_DSN->prepare($sql);
    $statement->bindValue(':id', $id);
    $statement->execute();

    if ($statement) {
        LoadDataRow('invoices', 'id', $_GET['id']);
        $message = "User deleted.";
        $messagetype = "success";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'include/head.php' ?>
    <title><?= $WebsiteSettings['name'] ?> - Index</title>

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
                    <h1 class="h3 mb-2 text-gray-800">Invoices</h1>

                    <!-- Add view -->
                    <?php if (isset($_GET['add'])) : ?>
                        <div class="col-xl-8">
                            <!-- Account details card-->
                            <div class="card mb-4">
                                <div class="card-header">Edit product</div>
                                <div class="card-body">
                                    <form method="post">
                                        <div class="row gx-3 mb-3">
                                            <!-- Form Group (first name)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="invoice_customerfirstname">Customer first name</label>
                                                <input class="form-control" type="text" id="invoice_customerfirstname" name="invoice_customerfirstname" required>
                                            </div>
                                            <!-- Form Group (last name)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="invoice_customerLastName">Customer last name</label>
                                                <input class="form-control" id="invoice_customerLastName" type="text" name="invoice_customerLastName" required>
                                            </div>
                                            <!-- Form Group (last name)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="invoice_employee">Employee</label>
                                                <select class="form-select" name="invoice_employee" required>
                                                    <option selected>Selectionnez l'employé</option>
                                                    <?php
                                                    $AllUsers = LoadAllUsers();
                                                    foreach ($AllUsers as $User) :
                                                    ?>
                                                        <option value="<?php echo $User['firstName']; ?> <?php echo $User['lastName']; ?>"><?php echo $User['firstName']; ?> <?php echo $User['lastName']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Form Group (email address)-->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="invoice_description">Description</label>
                                            <textarea class="form-control" id="invoice_description" type="textarea" name="invoice_description" rows="2"></textarea>
                                        </div>
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-3">
                                            <!-- Form Group (phone number)-->
                                            <div class="col-md-2">
                                                <label class="small mb-1" for="invoice_price">Price</label>
                                                <input class="form-control" id="invoice_price" type="number" name="invoice_price">
                                            </div>
                                            <div class="col-md-6">
                                                <input name="csrf" type="hidden" value="<?= $_SESSION['csrf'] ?>">
                                            </div>
                                        </div>

                                        <h2>Produits</h2>

                                        <div id="produits">
                                            <div class="row gx-3 mb-3">
                                                <div class="col-md-2">
                                                    <label for="nom_produit_1" class="form-label">Nom du produit</label>
                                                    <input type="text" class="form-control" id="nom_produit_1" name="nom_produit[]" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="prix_produit_1" class="form-label">Prix du produit</label>
                                                    <input type="number" class="form-control" id="prix_produit_1" name="prix_produit[]" required>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-primary mb-3" onclick="ajouterProduit()">Ajouter un produit</button>
                                        <button type="submit" name="submitadd" class="btn btn-success">Créer la facture</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Edit view -->
                    <?php elseif (isset($_GET['edit'])) : ?>
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
                                                <input class="form-control" id="product_name" type="text" placeholder="product name" name="product_name" value="<?php echo $DataRow['name']; ?>">
                                            </div>
                                            <!-- Form Group (last name)-->
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="product_category">Category</label>
                                                <input class="form-control" id="product_category" type="text" placeholder="product category" name="product_category" value="<?php echo $DataRow['category']; ?>">
                                            </div>
                                        </div>
                                        <!-- Form Group (email address)-->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="product_description">Description</label>
                                            <textarea class="form-control" id="product_description" type="description" placeholder="product description" name="product_description" value="<?php echo $DataRow['description']; ?>" rows="2"></textarea>
                                        </div>
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-3">
                                            <!-- Form Group (phone number)-->
                                            <div class="col-md-2">
                                                <label class="small mb-1" for="product_price">Price</label>
                                                <input class="form-control" id="product_price" type="price" placeholder="product price" name="product_price" value="<?php echo $DataRow['price']; ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" class="form-control" id="product_id" type="int" placeholder="product id" name="product_id" value="<?php echo $DataRow['id']; ?>">
                                                <input name="csrf" type="hidden" value="<?= $_SESSION['csrf'] ?>">
                                            </div>
                                        </div>
                                        <!-- Save changes button-->
                                        <button class="btn btn-primary" name="submitedit" type="submit"><i class="fas fa-save"></i> Save changes</button>
                                        <a class="btn btn-warning" href="products.php" role="button"><i class="fa fa-times"></i> Abort</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Main view -->
                    <?php else : ?>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Liste factures</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="<?php $_SERVER['PHP_SELF']; ?>?add" role="button">
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
                                                <th>Client</th>
                                                <th>Employé</th>
                                                <th>Description</th>
                                                <th>Prix</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>ID</th>
                                                <th>Client</th>
                                                <th>Employé</th>
                                                <th>Description</th>
                                                <th>Prix</th>
                                                <th>Actions</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            LoadAllRows('invoices');
                                            foreach ($AllRows as $product) :
                                            ?>
                                                <tr>
                                                    <td><?php echo $product["id"]; ?></td>
                                                    <td><?php echo $product["customerFirstName"]; ?> <?php echo $product["customerLastName"]; ?></td>
                                                    <td><?php echo $product["employee"]; ?></td>
                                                    <td><?php echo $product["description"]; ?></td>
                                                    <td><?php echo $product["price"]; ?></td>
                                                    <td>
                                                        <a href="invoices.php?edit&id=<?php echo ($product["id"]); ?>" class="btn btn-outline-info btn-sm"><i class="fas fa-pen"></i> Edit</a>
                                                        <a href="invoices.php?delete&id=<?php echo ($product["id"]); ?>" class="btn btn-outline-danger btn-sm"><i class="fas fa-times"></i> Delete</a>
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

    <script>
        let compteurProduits = 1;

        function ajouterProduit() {
            compteurProduits++;

            const produitsDiv = document.getElementById('produits');

            const produitHTML = `
      <div class="mb-3">
        <label for="nom_produit_${compteurProduits}" class="form-label">Nom du produit</label>
        <input type="text" class="form-control" id="nom_produit_${compteurProduits}" name="nom_produit[]" required>
      </div>
      <div class="mb-3">
        <label for="prix_produit_${compteurProduits}" class="form-label">Prix du produit</label>
        <input type="number" class="form-control" id="prix_produit_${compteurProduits}" name="prix_produit[]" required>
      </div>
    `;

            const produitDiv = document.createElement('div');
            produitDiv.innerHTML = produitHTML;

            produitsDiv.appendChild(produitDiv);
        }
    </script>

</body>

</html>