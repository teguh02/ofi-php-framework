

<style>
    .tengah {
        display: flex;
        height: 100vh;
        align-items: center;
        justify-content: center;
    }

    body {background-color: #eeeeee;}
</style>

<div class="container-fluid tengah">
    <div class="container col-md-4">
        <?php
            $flash->display();
        ?>
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Login Page</h5>

                <form action="/Auth/auth/deteksiLogin" method="POST">
                    <?= CSRF ?>
                    
                    <small>Email or Username</small>
                    <input class="form-control mb-2" required name="emailorusername" type="text">

                    <small>Password</small>
                    <input class="form-control mb-3" required name="password" type="password">

                    <button type="submit" class="btn btn-light border">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>