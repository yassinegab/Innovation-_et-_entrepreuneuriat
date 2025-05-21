<?php ob_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Signup Form</title>
  <link rel="stylesheet" href="assets/signup.css">
</head>
<body>
    <div style="position: absolute; top: 20px; left: 20px;">
    <a href="javascript:history.back()" class="custom-button">Retour</a>
</div>
  
  <div class="form-container">
  <p id="formError" class="error-message"></p>
    <h2>Sign Up</h2>
    <form method="POST" oninput="validateForm()">
      <div class="form-group">
        <label for="firstName">First Name</label>
        <input type="text" id="firstName" placeholder="John"  name="name" required>
      </div>

      <div class="form-group">
        <label for="lastName">Last Name</label>
        <input type="text" id="lastName" placeholder="Doe" name="lastName" required>
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" placeholder="john.doe@example.com" name="email" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" placeholder="••••••••" name="password" required>
      </div>
      <div class="form-group">
        <label for="confirm_password">confirmer votre Password</label>
        <input type="password" id="Cpassword" placeholder="••••••••" name="Cpassword" required>
      </div>

      <div class="form-group">
          <label for="role">Month</label>
          <select id="role" name="role" required>
            <option value="" disabled selected>votre Role</option>
            <option value="1">Investisseur</option>
            <option value="2">Innovateur</option>
            <option value="3">Collaborateur</option>
          </select>
        </div>

      <div class="birthdate">
        <div class="form-group">
          <label for="day">Day</label>
          <select id="day" name="day" required>
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
        </div>
        <div class="form-group">
          <label for="month">Month</label>
          <select id="month" name="month" required>
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
        </div>
        <div class="form-group">
          <label for="year">Year</label>
          <select id="year" name="year" required>
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
        </div>
      </div>

      <button type="submit" onclick="" id="add_account" name="add_account" disabled>Create Account</button>
      <p>j'ai une compte ? <a href="login.php">login</a></p>
    </form>
    <?php

        if ($_SERVER['REQUEST_METHOD']==='POST') {

            $name=$_POST['name'];
            $lastname=$_POST['lastName'];
            $email=$_POST['email'];
            $password=$_POST['password'];
            $day=$_POST['day'];
            $month=$_POST['month'];
            $year=$_POST['year'];
            include_once(__DIR__ . '/../../controller/user_controller.php');
            // im using 5 as a place holder it doesn effect anything
            $usr=new user(5,$name,$lastname,$day,$month,$year,$password,$email,0,null); 
            $userC=new user_controller();
            $userC->add_user($usr);
            

        }


      ?>
  </div>
  <script src="assets/signup.js">
 
</script>
</body>
</html>
<?php ob_end_flush(); ?>