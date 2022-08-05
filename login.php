<?php require 'pages/header.php'; ?>
<?php require 'classes/UserClass.php'; ?>
<div class="container">
    <?php
        $u = new User();

        if(isset($_POST['email'])){
            $email = addslashes($_POST['email']);
            $password = md5($_POST['password']);

            if(!empty($_POST['email']) && !empty($_POST['password'])){
                if($u->login($email, $password)){
                    ?>
                        <script type="text/javascript">
                            window.location.href = './'
                        </script>
                    <?php
                }else{ 
                    ?>
                        <div class="alert alert-danger">
                            E-mail or password incorrect! Please try again.
                        </div> 
                    <?php 
                }
            }else{
             ?>
               <div class="alert alert-danger">
                    You need to fill all the inputs to continue!
               </div> 
            <?php };
        }
    ?>
    <h1>Login</h1>
    <form method="POST">
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="text" class="form-control" name="email" id="email" placeholder="Type your e-mail here!">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Type your password here!">
        </div>

        <input type="submit" class="btn btn-success"value="Go">
    </form>
</div>

<?php require 'pages/footer.php'; ?>