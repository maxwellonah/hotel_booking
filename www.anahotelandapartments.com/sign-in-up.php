<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in || Sign up from</title>
     <!-- font awesome icons -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- css stylesheet -->
    <link rel="stylesheet" href="../www.anahotelandapartments.com/css/sign-in-up-style.css">
    
</head>
<body>
<div class="container" id="container">
    <?php
    // Check for URL parameters and display corresponding error messages
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        if ($error == 'invalid_password') {
            echo '<div class="alert alert-danger" role="alert">Invalid password. Please try again.</div>';
        } elseif ($error == 'user_not_found') {
            echo '<div class="alert alert-danger" role="alert">User not found. Please check your email or register.</div>';
        }
    }
    ?>

<div class="container-fluid" id="container">
    <div class="form-container sign-up-container" id="form-container">
        <form action="register.php" method="POST">
            <h1>Create Account</h1>
            <div class="social-container">
                <a href="https://facebook.com/anahotelandapartments" class="social"><i class="fab fa-facebook"></i></a>
                <a href="https://instagram.com/anahotelandapartments" class="social"><i class="fa fa-instagram"></i></a>
            </div>
            <span>or use your email for registration</span>
            <div class="infield">
                <input type="text" placeholder="Name" name="name" required />
                <label></label>
            </div>
            <div class="infield">
                <input type="email" placeholder="Email" name="email" required />
                <label></label>
            </div>
            <div class="infield">
                <input type="password" placeholder="Password" name="password" required />
                <label></label>
            </div> 
            <div class="infield">
                <input type="text" placeholder="Address" name="address" required />
                <label></label>
            </div>
            <div class="infield">
                <input type="text" placeholder="Phone" name="phone" required />
                <label></label>
            </div>  
            <button type="submit">Sign Up</button>
            <a href="index.php"><i class="fa fa-arrow-circle-o-left" aria-hidden="true">Go Back</i>
            </a>
        </form>
    </div>

    <div class="form-container sign-in-container">
        <form action="login.php" method="POST">
            <h1>Sign in</h1>
            <div class="social-container">
                <a href="https://facebook.com/anahotelandapartments" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="https://instagram.com/anahotelandapartments" class="social"><i class="fab fa-google-plus-g"></i></a>
                <a href="https://instagram.com/anahotelandapartments" class="social"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <span  class="forgot">or use your account</span>
            <div class="infield">
                <input type="email" placeholder="Email" name="email" required />
                <label></label>
            </div>
            <div class="infield">
                <input type="password" placeholder="Password" name="password" required />
                <label></label>
            </div>
            <div>
                <a href="index.phpx" class="forgot">Go Bcak To Home?</a>
                <button type="submit">Sign In</button>   
            </div>
            
        </form>
    </div>
        <div class="overlay-container" id="overlayCon">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button style="margin-left: 60px;">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button>Sign Up</button>
                    
                </div>
            </div>
            <button id="overlayBtn1"></button>
     </div>
    </div>

    
    
    <!-- js code -->
    <script>
        const container = document.getElementById('container');
        const overlayBtn1 = document.getElementById('overlayBtn1');
    
        overlayBtn1.addEventListener('click', () => {
            container.classList.toggle('right-panel-active');
            overlayBtn1.classList.remove('btnscaled');
            window.requestAnimationFrame( ()=>{
                overlayBtn1.classList.add('btnscaled');
            })
        });
    </script>
    

</body>
</html>
