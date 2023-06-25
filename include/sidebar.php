<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3"><?= $WebsiteSettings['name'] ?></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?php if ($currentFile === 'index.php') echo 'active'; ?>">
        <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Administration
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item <?php if ($currentFile === 'websiteconfig.php') echo 'active'; ?>">
        <a class="nav-link" href="websiteconfig.php">
            <i class="fas fa-fw fa-cog"></i>
            <span>Configuration du site</span></a>
    </li>
    <li class="nav-item <?php if ($currentFile === 'users.php') echo 'active'; ?>">
        <a class="nav-link" href="users.php">
            <i class="fas fa-fw fa-users"></i>
            <span>Utilisateurs</span></a>
    </li>
    <li class="nav-item <?php if ($currentFile === 'products.php') echo 'active'; ?>">
        <a class="nav-link" href="products.php">
            <i class="fas fa-fw fa-shopping-basket"></i>
            <span>Products</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Outils
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item <?php if ($currentFile === 'invoices.php') echo 'active'; ?>">
        <a class="nav-link" href="invoices.php">
            <i class="fas fa-fw fa-file-invoice"></i>
            <span>Création facture</span></a>
    </li>

    <!-- Nav Item - Charts -->
    <li class="nav-item">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Charts</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-table"></i>
            <span>Tables</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Sidebar Message -->
    <div class="sidebar-card d-none d-lg-flex">
        <img class="sidebar-card-illustration mb-2" src="assets/img/undraw_rocket.svg" alt="...">
        <p class="text-center mb-2"><strong>SB Admin Pro</strong> is packed with premium features, components, and more!</p>
        <a class="btn btn-success btn-sm" href="https://startbootstrap.com/theme/sb-admin-pro">Upgrade to Pro!</a>
    </div>

</ul>