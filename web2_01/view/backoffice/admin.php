<?php
session_start();
if (!isset($_SESSION['user_id'])|| (!($_SESSION['user_id'])==0)) {
    header('Location: ../frontoffice/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration des Événements</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="theme-custom.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
</head>
<body>
<header>
    <h1>Gérer les utilisateurs</h1>
    <button id="addUserBtn">+ Ajouter un utilisateur</button>
    <a href="admin_dashboard.php" class="btn-back">← Retour</a>
  </header>

  <main>
    <table id="usersTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Prénom</th>
          <th>Nom</th>
          <th>Email</th>
          <th>Naissance</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        include_once(__DIR__ .'/../../controller/user_controller.php');
        $userC=new User_controller();
        $users=$userC->getAllUsers() ;
        
        foreach($users as $u): ?>
        <tr>
          <td><?= htmlspecialchars($u['id']) ?></td>
          <td><?= htmlspecialchars($u['name']) ?></td>
          <td><?= htmlspecialchars($u['lastName']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= htmlspecialchars($u['birthdate']) ?></td>
          <td>
            <!-- Ici tu pourrais ajouter des liens Modifier / Supprimer -->
            <button onclick="window.location.href='edit.php?id=<?= $u['id'] ?>'">✎</button>
            <button onclick="confirmDelete(<?= $u['id'] ?>); window.location.href='delete.php?id=<?= $u['id'] ?>'">🗑</button>
          </td>
        </tr>
        <?php endforeach; ?>

        <!-- Ligne d'ajout, cachée par défaut -->
        <input type="text"  type="hidden"  name="myInput" id="myInput" style="display: none;" />
        <div class="form-group">
        
        <tr id="newUserRow" >
        <form method="POST"  id="registerForm">
        <p id="formError" class="error-message" style="color: red;"></p>
            <td>—</td>
            <td><input type="text" id="firstName" name="name" placeholder="name" required></td>
            <td><input type="text" id="lastName" name="lastName" placeholder="last name" required></td>
            <td><input type="email" id="email" name="email" placeholder="email" required></td>
            <td><input type="password" id="password" name="password" placeholder="*******" required></td>
            
            <td><select id="day" name="day" required>
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
          </select></td>
          <td><select  id="month" name="month" required>
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
          </select></td>
          <td><select id="year" name="year" required>
            <option value="" disabled selected>Year</option>
            <option value="2023">2023</option>
            <option value="2022">2022</option>
            <option value="2021">2021</option>
            <option value="2020">2020</option>
            <option value="2019">2019</option>
            <option value="2018">2018</option>
            <option value="2017">2017</option>
            <option value="2016">2016</option>
            <option value="2015">2015</option>
            <option value="2014">2014</option>
            <option value="2013">2013</option>
            <option value="2012">2012</option>
            <option value="2011">2011</option>
            <option value="2010">2010</option>
            <option value="2009">2009</option>
            <option value="2008">2008</option>
            <option value="2007">2007</option>
            <option value="2006">2006</option>
            <option value="2005">2005</option>
            <option value="2004">2004</option>
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
          </select></td>
            <td>
              <button type="submit" id="addButton" name="addButton" disabled>Enregistrer</button>
              <button type="button" id="cancelBtn">Annuler</button>
            </td>
            </div>
          </form>
        </tr>
      </tbody>
    </table>
  </main>

  <script src="assets/admin.js"></script>
    <?php 
    if (($_SERVER['REQUEST_METHOD']==='POST') && isset($_POST['addButton'])){
        $name=$_POST['name'];
            $lastname=$_POST['lastName'];
            $email=$_POST['email'];
            $password=$_POST['password'];
            $day=$_POST['day'];
            $month=$_POST['month'];
            $year=$_POST['year'];
            include_once(__DIR__ .'/../../controller/user_controller.php');
            $usr=new user(5,$name,$lastname,$day,$month,$year,$password,$email); 
            $userC=new user_controller();
            $userC->add_user2($usr);
            echo '<script>window.location.href = "'.$_SERVER['PHP_SELF'].'";</script>';
    }else if (($_SERVER['REQUEST_METHOD']==='POST') && isset($_POST['deleteButton'])) {
        $idToDelete=$_POST['myInput'];
        echo'<h1>'.htmlspecialchars($idToDelete).'</h1>';
        $userC->delete_user($idToDelete);
        echo '<script>window.location.href = "'.$_SERVER['PHP_SELF'].'";</script>';
    }
    ?>
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-copyright">
                    <p>&copy; 2025 EventHub Admin. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="../js/admin.js"></script>
</body>
</html>
