    <nav class="navbar navbar-expand-md border-bottom bg-light navbar-light">
        <!-- Brand -->
        <a class="navbar-brand" href="/">OFI PHP Framework</a>

        <!-- Toggler/collapsibe Button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $route -> generatePath('loginPage') ?>">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $route -> generatePath('registerPage') ?>">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $route -> generatePath('get-method', 'id:1') ?>">Route with parameter</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $route -> generatePath('get-method', ['id:1', 'name:ofi', 'age:20']) ?>">Route with 2 parameter</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid text-center d-flex justify-content-center align-items-center" style="height: 90vh;">
        <div class="container">
            <h1>Hello World!</h1>
            <h3>Welcome in OFI PHP Framework, I Hope You Can Enjoy This Project</h3>

            <br>

            <a href="https://github.com/teguh02/ofi-php-framework">Open Github</a>
        </div>
    </div>