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
                <h5 class="mb-3">Register Page</h5>

               <form action="/Auth/auth/saveRegister" method="post">
                   <small>Fullname</small>
                   <input class="form-control mb-2" required name="fullname" type="text">

                   <small>Username</small>
                   <input class="form-control mb-2" required name="username" type="text">

                   <small>Email</small>
                   <input class="form-control mb-2" required name="email" type="email">

                   <small>Password</small>
                   <input class="form-control mb-3" required name="password" type="password">

                   <button type="submit" class="btn btn-light border">
                    Register
                   </button>
               </form>
            </div>
        </div>
    </div>
</div>