<?php 
   session_start();
   use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';
   if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
       $email = $_POST['email'];
       $_SESSION['email'] = $email;
       $password = $_POST['password'];
       include_once (__DIR__ . '/../../controller/user_controller.php');
       $usr = new User(null, null, null, null, null, null, $password, $email,null,null);
       $userC = new User_controller();
       $userC->login_user($usr);
   }
    if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['name'])) {
   
            $name=$_POST['name'];
            $lastname=$_POST['lastName'];
            $email=$_POST['email2'];
            $password=$_POST['password2'];
            $day=$_POST['day'];
            $month=$_POST['month'];
            $year=$_POST['year'];
            include_once(__DIR__ . '/../../controller/user_controller.php');
            // im using 5 as a place holder it doesn effect anything
            $usr=new user(5,$name,$lastname,$day,$month,$year,$password,$email,0,null); 
            $userC=new user_controller();
            $userC->add_user($usr);
            
   
        }
   


$emailExist = null;
$showMessage = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emailBtn'])) {
    require_once(__DIR__ . "/../../controller/user_controller.php");
    $email = $_POST['email3'];
    $userC = new User_controller();
    $emailExist = $userC->checkEmail($email);
    if ($emailExist===false) {
      $showMessage = true;

    }
    else{
      $code = random_int(100000, 999999);
      $_SESSION['reset_code'] = $code;
      $_SESSION['reset_email'] = $email;
      sendPasswordResetEmail($email, $code);

       header("Location: login2.php?verify=1");

    }
   

    // TODO: Send reset link with token if $emailExist is true
}
 $verify=0;
 if(isset($_GET['verify']))
 {
    $verify=1;

 }   
    
 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codeBtn'])) {
    $enteredCode = $_POST['code'];

    if ($enteredCode == $_SESSION['reset_code']) {
        // Code is valid
        header("Location: login2.php?newpass=1"); // You can create this page next
        exit();
    } else {
        $error = "Code invalide. Réessayez.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newbtn'])) {
    $newPassword = $_POST['password22'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($newPassword) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        // TODO: Hash the password and update in the database
        // Example:
        require_once('../../controller/user_controller.php');
        $userC = new User_controller();
        
        $userC->updatePassword($_SESSION['reset_email'], $confirmPassword);

        // Clean session data
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_code']);

        $success = "Mot de passe mis à jour avec succès.";
        header("Location: login2.php");
        exit();
    }
}
$newpass=0;
 if(isset($_GET['newpass']))
 {
    $newpass=1;
 }
$twofac=0;
if(isset($_GET['twofactor']))
{
    $twofac=1;
}
$phoneconfirm=0;
if(isset($_GET['email']))
{
    $phoneconfirm=1;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verification_type_Btn'])) {
        $method = $_POST['verification_method'];
        $code = random_int(100000, 999999);
        $_SESSION['2fa_code'] = $code;

        // Store the selected method in session if needed
        $_SESSION['2fa_method'] = $method;

        if ($method === 'email') {
          $emailvalue=$_POST["verification_method_value"];
            sendPasswordResetEmail($_SESSION['email'], $code);
            header("Location: login2.php?email=1");
            exit();
        } elseif ($method === 'phone') {
          sendSMSCode($code);
            header("Location: phoneconfirm.php");
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['phoneBtn'])) {
    require_once('../../controller/user_controller.php');
    $Userc = new User_controller();
    $code = $_POST["phone"];
    if ($_SESSION['2fa_code'] == $code) {
      $getid = $Userc->getUserIdByEmail($_SESSION['email']);
      $_SESSION['user_id'] = $getid;



      header("Location: index.php");
    }
  }
}
function sendPasswordResetEmail($to, $code) {
   
  
  $mail = new PHPMailer();

   

     

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'natsubakahappy@gmail.com'; // Ton email Gmail
        $mail->Password = 'ixur zkyd qxjb jbwj'; // Mot de passe Application Gmail (PAS ton mot de passe normal)
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('stelliferous@gmail.com', 'STELLIFEROUS ACCOUNT Password Reset');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
$mail->Subject = 'STELLIFEROUS TEAM HERE $code - this is your password Reset code';



$mail->Body = "

    <div style='font-family: Arial, sans-serif; background-color: #ffffff; padding: 20px; color: #333333;'>
        <h2>Password Reset Request</h2>
        <p>We received a request to reset your password.</p>
        <p>Your verification code is: </p>
        <div style='font-size: 24px; font-weight: bold; color: #007BFF; margin: 20px 0;'>$code</div>
        <p>This code will expire in 1 hour.</p>
        <p>If you did not request a password reset, please ignore this email.</p>
    </div>


    <div style='font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;'>
        <div style='max-width: 600px; margin: auto; background: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);'>
            <div style='text-align: center; margin-bottom: 20px;'>
                <img src='cid:logoimg' alt='Stelliferous Logo' style='width: 150px;'>
            </div>
            <h2 style='color: #333333;'>Password Reset Request</h2>
            <p style='font-size: 16px; color: #555555;'>We received a request to reset your Stelliferous account password. Here's your verification code:</p>
            
            <div style='background-color: #f9f9f9; border: 1px solid #FFC107; border-radius: 5px; padding: 15px; margin: 25px 0; text-align: center;'>
                <p style='font-size: 28px; font-weight: bold; color: #FFC107; letter-spacing: 3px; margin: 0;'>$code</p>
            </div>
            
            <p style='font-size: 15px; color: #666666;'>
                Enter this code in the password reset form to verify your identity.<br>
                <span style='color: #FFC107; font-weight: bold;'>This code will expire in 1 hour.</span>
            </p>
            
            <p style='font-size: 14px; color: #888888; margin-top: 25px;'>
                If you didn't request this, please ignore this email or contact support if you have concerns.
            </p>
            
            <p style='font-size: 12px; color: #aaaaaa; text-align: center; margin-top: 40px; border-top: 1px solid #eeeeee; padding-top: 20px;'>
                © " . date('Y') . " Stelliferous. All rights reserved.
            </p>
        </div>
    </div>
";

$mail->AltBody = "Welcome to STELLIFEROUS!\n\nReset your password using this Code: $code\n\nReset your password using this Code: $code \n\n If you did not request this, please ignore this email.";


        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";

        return false;
    }
}


?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>EntrepreneurHub | Login & Register</title>
      <!-- Boxicons -->
      <link
         href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
         rel="stylesheet"
         />
      <!-- Style -->
      <link rel="stylesheet" href="assets/css/style.css" />
   </head>
   <body>
      <div class="form-container">
         <div class="col col-1">
            <div class="image-layer">
               <img
                  src="assets/img/white-outline.png"
                  alt="entrepreneurhub"
                  class="form-image-main"
                  />
               <img src="assets/img/dots.png" class="form-image dots" />
               <img src="assets/img/coin.png" class="form-image coin" />
               <img src="assets/img/rocket.png" class="form-image rocket" />
               <img src="assets/img/cloud.png" class="form-image cloud" />
               <img src="assets/img/stars.png" class="form-image stars" />
            </div>
            <p class="featured-words">
               You are Few Steps Away from Joining Us <span>EntrepreneurHub</span>
            </p>
         </div>
         <div class="col col-2">
            <div class="buton-box">
               <button class="btn btn-1" id="login">Sign In</button>
               <button class="btn btn-2" id="register">Sign Up</button>
            </div>
            <form method="POST" id="loginForm" oninput="validateForm()" action="login2.php">
               <div class="login-form">
                  <div class="form-title">
                     <span>Sign In</span>
                  </div>
                  <div class="form-inputs">
                     <div class="input-box">
                        <input
                           type="email" id="email" name="email"
                           class="input-field"
                           placeholder="username"
                          
                           />
                        <i class="bx bxs-user icon"></i>
                     </div>
                     <div class="input-box">
                        <input
                           type="password" id="password" name="password"
                           class="input-field"
                           placeholder="password"
                           
                           />
                        <i class="bx bxs-lock-alt icon"></i>
                     </div>
                     <div class="forget-pass">
                        <button type="button" id="forget" >Forget Password?</button>
                     </div>
                     <div class="input-box">
                        <button type="submit" class="input-submit">
                        <span>Sign In</span><i class="bx bx-right-arrow-alt"></i>
                        </button>
                     </div>
                  </div>
                  <div class="social-login">
                     <a href="#"><i class="bx bxl-facebook"></i></a>
                     <a href="#"><i class="bx bxl-twitter"></i></a>
                     <a href="#"><i class="bx bxl-google"></i></a>
                  </div>
               </div>
            </form>
            <form method="POST" oninput="validateForm2()">
               <div class="register-form">
                  <div class="form-title">
                     <span>Create Account</span>
                  </div>
                  <div class="form-inputs">
                     <div class="row">
                        <div class="input-box">
                           <input type="text" id="firstName" placeholder="John" name="name" class="input-field" required />
                           <i class="bx bxs-user icon"></i> <!-- Utilisateur -->
                        </div>
                        <div class="input-box">
                           <input type="text" id="lastName" placeholder="Doe" name="lastName" class="input-field" required />
                           <i class="bx bxs-user icon"></i>
                        </div>
                        <div class="input-box">
                           <select id="role" name="role" class="input-field" required>
                              <option value="" disabled selected></option>
                              <option value="1">Investisseur</option>
                              <option value="2">Innovateur</option>
                              <option value="3">Collaborateur</option>
                           </select>
                           <i class="bx bx-briefcase icon"></i> <!-- Rôle / métier -->
                        </div>
                     </div>
                     <div class="input-box">
                        <input type="email" id="email2" placeholder="john.doe@example.com" name="email2" class="input-field" required />
                        <i class="bx bxs-envelope icon"></i> <!-- Email -->
                     </div>
                     <div class="row">
                        <div class="input-box">
                           <input type="password" id="password2" placeholder="••••••••" name="password2" class="input-field" required />
                           <i class="bx bxs-lock-alt icon"></i> <!-- Mot de passe -->
                        </div>
                        <div class="input-box">
                           <input type="password" id="Cpassword" placeholder="••••••••" name="Cpassword" class="input-field" required />
                           <i class="bx bxs-lock-alt icon"></i> <!-- Confirmation mot de passe -->
                        </div>
                     </div>
                     <div class="row">
                        <div class="input-box">
                           <select id="day" name="day" class="input-field" required>
                              <option value="" disabled selected>Day</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                              <option value="9">9</option>
                              <option value="10">10</option>
                              <option value="11">11</option>
                              <option value="12">12</option>
                              <option value="13">13</option>
                              <option value="14">14</option>
                              <option value="15">15</option>
                              <option value="16">16</option>
                              <option value="17">17</option>
                              <option value="18">18</option>
                              <option value="19">19</option>
                              <option value="20">20</option>
                              <option value="21">21</option>
                              <option value="22">22</option>
                              <option value="23">23</option>
                              <option value="24">24</option>
                              <option value="25">25</option>
                              <option value="26">26</option>
                              <option value="27">27</option>
                              <option value="28">28</option>
                              <option value="29">29</option>
                              <option value="30">30</option>
                              <option value="31">31</option>
                           </select>
                           <i class="bx bx-calendar icon"></i> <!-- Date -->
                        </div>
                        <div class="input-box">
                           <select id="month" name="month" class="input-field" required>
                              <option value="" disabled selected>Month</option>
                              <option value="1">January</option>
                              <option value="2">February</option>
                              <option value="3">March</option>
                              <option value="4">April</option>
                              <option value="5">May</option>
                              <option value="6">June</option>
                              <option value="7">July</option>
                              <option value="8">August</option>
                              <option value="9">September</option>
                              <option value="10">October</option>
                              <option value="11">November</option>
                              <option value="12">December</option>
                           </select>
                           <i class="bx bx-calendar icon"></i>
                        </div>
                        <div class="input-box">
                           <select id="year" name="year" class="input-field" required>
                              <option value="" disabled selected>Year</option>
                              <option value="2003">2003</option>
                              <option value="2002">2002</option>
                              <option value="2001">2001</option>
                              <option value="2000">2000</option>
                              <option value="1999">1999</option>
                              <option value="1998">1998</option>
                              <option value="1997">1997</option>
                              <option value="1996">1996</option>
                              <option value="1995">1995</option>
                              <option value="1994">1994</option>
                              <option value="1993">1993</option>
                              <option value="1992">1992</option>
                              <option value="1991">1991</option>
                              <option value="1990">1990</option>
                              <option value="1989">1989</option>
                              <option value="1988">1988</option>
                              <option value="1987">1987</option>
                              <option value="1986">1986</option>
                              <option value="1985">1985</option>
                              <option value="1984">1984</option>
                              <option value="1983">1983</option>
                              <option value="1982">1982</option>
                              <option value="1981">1981</option>
                              <option value="1980">1980</option>
                           </select>
                           <i class="bx bx-calendar icon"></i>
                        </div>
                     </div>
                  </div>
                  <div class="input-box">
                     <button type="submit" class="input-submit">
                     <span>Sign up</span><i class="bx bx-right-arrow-alt"></i>
                     </button>
                  </div>
               </div>
         
         </form>
         <div class="forget-form">
            <div class="form-title">
               <span>forget password</span>
            </div>
        <form method="POST">
     
   
            <div class="form-inputs">
               <div class="input-box">
                  <input
                     type="email" id="email3" name="email3"
                     class="input-field"
                     placeholder="email"
                     required
                     />
                  <i class="bx bxs-user icon"></i>
               </div>
               <div class="input-box">
                  <button type="submit" class="input-submit" name="emailBtn">
                  <span>send</span><i class="bx bx-right-arrow-alt"></i>
                  </button>
               </div>
            </div>
             </form>
         </div>


        <div class="verify-form">
            <div class="form-title">
               <span>enter code</span>
            </div>
            <form method="POST">
     
   
            <div class="form-inputs">
               <div class="input-box">
                  <input
                     type="text" id="code" name="code"
                     class="input-field"
                     placeholder="code"
                     required
                     />
               </div>
               <div class="input-box">
                  <button type="submit" class="input-submit" name="codeBtn">
                  <span>enter</span><i class="bx bx-right-arrow-alt"></i>
                  </button>
               </div>
            </div>
             </form>
         </div>
        <div class="newpass-form">
            <div class="form-title">
               <span>enter your new password</span>
            </div>
            <form method="POST">
     
   
            <div class="form-inputs">
                <div class="input-box">
                           <input type="password" placeholder="••••••••" name="password22" class="input-field" required />
                           <i class="bx bxs-lock-alt icon"></i> <!-- Mot de passe -->
                        </div>
                        <div class="input-box">
                           <input type="password"  placeholder="••••••••" name="confirm_password" class="input-field" required />
                           <i class="bx bxs-lock-alt icon"></i> <!-- Confirmation mot de passe -->
                        </div>
               <div class="input-box">
                  <button type="submit" class="input-submit" name="newbtn">
                  <span>validate</span><i class="bx bx-right-arrow-alt"></i>
                  </button>
               </div>
         </div>
             </form>
         </div>



             <div class="twofac-form">
            <div class="form-title">
               <span>choose </span>
            </div>
            <form method="POST">
     
   
            <div class="form-inputs">
                <div class="input-box">
               <div class="verification-options">
            <label class="option">
                <input type="radio" name="verification_method" value="email" checked>
                <div class="option-content">
                <div class="option-icon">
                    <i class="bx bx-envelope"></i>
                </div>
                <div class="option-text">
                    <div class="option-label">Email Verification</div>
                    <div class="option-desc">We'll send a code to your registered email address</div>
                </div>
                <div class="option-check">
                    <div class="check-circle"></div>
                </div>
                </div>
            </label>
            
            <label class="option">
                <input type="radio" name="verification_method" value="phone">
                <div class="option-content">
                <div class="option-icon">
                    <i class="bx bx-mobile-alt"></i>
                </div>
                <div class="option-text">
                    <div class="option-label">Phone Verification</div>
                    <div class="option-desc">We'll send an SMS with a code to your registered phone</div>
                </div>
                <div class="option-check">
                    <div class="check-circle"></div>
                </div>
                </div>
            </label>
            </div>
            
               <div class="input-box">
                  <button type="submit" class="input-submit" name="verification_type_Btn">
                  <span>continue</span><i class="bx bx-right-arrow-alt"></i>
                  </button>
               </div>
         </div>
             </form>
         </div>
         </div>
          <div class="phoneconfirm-form">
            <div class="form-title">
               <span>enter code</span>
            </div>
            <form method="POST">
     
   
            <div class="form-inputs">
               <div class="input-box">
                  <input type="phone" name="phone" placeholder="enter the code" class="input-field" required>
               </div>
               <div class="input-box">
                  <button type="submit" class="input-submit" name="phoneBtn">
                  <span>envoyer</span><i class="bx bx-right-arrow-alt"></i>
                  </button>
               </div>
            </div>
             </form>
         </div>





           

    



      </div>
      </div>
       <script>
            
           
            const loginForm22 = document.querySelector(".login-form");
           
           
            const verifyForm22 = document.querySelector(".verify-form"); 
             const newpassform = document.querySelector(".newpass-form");
             const twofacform = document.querySelector(".twofac-form");
             const phoneform = document.querySelector(".phoneconfirm-form");
            // PHP -> JS
            let phpVerify = <?php echo $verify; ?>;
            let verifnew = <?php echo $newpass; ?>;
            let twofacverif=<?php echo $twofac; ?>;
            let phoneconfirm=<?php echo $phoneconfirm; ?>;
           // console.log('phpverify'+phpVerify);
            if (phpVerify==1) {
                loginForm22.style.display = 'none';
                verifyForm22.style.display = 'block';
            }
            if(verifnew==1)
            {loginForm22.style.display = 'none';
                newpassform.style.display = 'block';

            }
            if(twofacverif==1)
            {
                    loginForm22.style.display = 'none';
               twofacform.style.display = 'block';
            }
            if(phoneconfirm==1)
            {
                
                    loginForm22.style.display = 'none';
               phoneform.style.display = 'block';
            }


    </script>
      <script src="assets/js/main.js"></script>
      <script src="assets/login.js"></script>
      <script src="assets/signup.js"></script>
                       

   </body>
</html>