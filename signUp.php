<?php require 'pages/header.php'; ?>
<?php require 'classes/UserClass.php'; ?>
<div class="container">
    <?php
        $u = new User();

        if(isset($_POST['email'])){
            $name = addslashes($_POST['name']);
            $email = addslashes($_POST['email']);
            $password = md5($_POST['password']);
            $phone = addslashes($_POST['phone']);

            if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password'])){
                if($u->cadastrar($name,$email,$password,$phone)): ?>
                    <div class="alert alert-success">
                        Congratulations! Please make login <a href="login.php">Click here to login</a>
                    </div> 
                <?php else: ?>
                    <div class="alert alert-danger">
                        Already exists an user related to this email address
                    </div> 
                <?php endif;
            }else{
             ?>
               <div class="alert alert-danger">
                    You need to fill all the inputs to continue!
               </div> 
            <?php };
        }
    ?>
    <h1>Sign Up</h1>
    <form method="POST">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Type your name here!">
        </div>
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="text" class="form-control" name="email" id="email" placeholder="Type your e-mail here!">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Type your password here!">
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" class="form-control" name="phone" id="phone" placeholder="Type your phone here!">
        </div>

        <input type="submit" class="btn btn-success"value="Go">
    </form>
</div>

<?php require 'pages/footer.php'; ?>