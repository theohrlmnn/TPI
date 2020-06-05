<div class="uk-offcanvas-content">
  <!-- menu position. delete .uk-light to change black navbar to white (also you should change logo to dark one)-->
  <nav class="uk-navbar-container color" uk-navbar="mode: click">
    <div class="uk-navbar-left nav-overlay">
      <div class="uk-navbar-flip">
        <ul class="uk-navbar-nav">
          <li><a href="#">
              <img src="./img/LogoExpertsDev.svg" width="30" height="30" class="" alt="">
              &nbsp;CdEIG</a></li>
          <li><a class="uk-navbar-toggle" uk-navbar-toggle-icon uk-toggle="target: #mobile-navbar"></a></li>

        </ul>
      </div>
    </div>
    <!-- menu-->
    <div class="uk-navbar-right nav-overlay">
      <div class="uk-navbar-flip">
        <ul class="uk-navbar-nav">
          <div class="uk-margin-right uk-navbar-nav uk-navbar-right"><a class="uk-navbar-item uk-logo" href="#">
              <li class="uk-navbar-right ">
                <a href="home.php">A propos&nbsp;</a>
              </li>
              <li class="uk-navbar-right ">
                <a href="#">Administration&nbsp;</a>
              </li>
              <li class="uk-navbar-right ">
                <a href="redactionTPI.php">Rédaction&nbsp;</a>
              </li>
              <li class="uk-navbar-right ">
                <a href="#">Répartition&nbsp;</a>
              </li>
              <li class="uk-navbar-right ">
                <a href="#">Validation&nbsp;</a>
              </li>
              <li class="uk-navbar-right ">
                <a href="#">Evaluation&nbsp;</a>
              </li>
              <li>
                <span uk-icon="icon: user; ratio: 2"></span>
                <div class="uk-navbar-dropdown">
                  <ul class="uk-nav uk-navbar-dropdown-nav">
                    <li><a href="#">Paramètre</a></li>
                    <li><a href="logout.php">Deconnexion</a></li>
                  </ul>
                </div>
                <span uk-icon="icon: chevron-down"></span>
              </li>
            </a>
          </div>

        </ul>
      </div>
    </div>
    <!-- endmenu-->
    <!-- logo or title-->


    <!-- end logo or title-->

  </nav>
  <!-- end menu position-->

</div>
<!-- off-canvas menu-->
<div id="mobile-navbar" uk-offcanvas="mode: push; flip: false">
  <div class="uk-offcanvas-bar">
    <!-- off-canvas close button-->

    <!-- off-canvas close button-->
    <ul uk-nav>
      <!-- menu-->
      <li class="uk-text-center">
        <h4><a href="">Administration</a></h4>
      </li>

      <li><a href="index.php"> Action non implémentée</a></li>
      <li class="uk-text-center">
        <h4 class="uk-margin-top"><a href="redactionTPI.php">Rédaction</a></h4>
      </li>
      <?php
      $arrRight = getRightUserSession();
      foreach ($arrRight as $r) {
        switch ($r) {
          case 'createTPI':
            echo "<li><a href=\"createTPI.php\">Créer un TPI</a></li>";
            break;
          case 'listTPI':
            echo "<li><a href=\"listTPI.php\">Liste TPI</a></li>";
            break;
          case 'viewPDF':
            echo "<li><a href=\"viewPDF.php\">Voir l'énoncé</a></li>";
            break;
        }
      } ?>
      <li class="uk-text-center">
        <h4 class="uk-margin-top">Répartition</h4>
      </li>
      <li><a href="index.php">Action non implémentée</a></li>
      <li class="uk-text-center">
        <h4 class="uk-margin-top">Validation</h4>
      </li>
      <li><a href="index.php">Action non implémentée</a></li>
      <li class="uk-text-center">
        <h4 class="uk-margin-top">Evaluation</h4>
      </li>
      <li><a href="index.php">Action non implémentée</a></li>
    </ul>
    <div class="uk-margin uk-margin-medium-top">
      <p class="uk-margin-remove">Connecté en tant que :</p>
      <p class="uk-margin-remove">
        <?= getLastNameUserSession() ?>
        <?= getFirstNameUserSession() ?>
      </p>
      <?php
       foreach(getRoleNameUserSession() as $r)
       {
        echo "<p class=\"uk-margin-remove\">";
        echo $r;
        echo "</p>";
       }
       ?>
      
    </div>
  </div>

</div>