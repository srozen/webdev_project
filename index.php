<?php
/***********************
 * MAIN CONTAINER PAGE *
 ***********************/

  session_start();

  $config = parse_ini_file('config.ini', true);

  // Include functions and classes files
  include('functions.index.php');
  include('functions.input.php');
  include('functions.register.php');
  include('class.page.php');

  // Creating the Page object
  if(isset($_GET['page']))  $page = create_page($_GET['page']);
  else $page = create_page('index');

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
    <title><?php echo $page->getTabTitle(); ?></title>
  </head>

  <body>

    <header>
      <nav><span>Menu : </span><?php create_menu(); ?> </nav>
    </header>

    <section>
      <h1>Corps de page</h1>
      <?php
        echo '<h2>' . $page->getTitle() . '</h2>';
        include($page->getUrl());
      ?>
    </section>

    <footer>
      <span>Copyright Samuel Monroe 2014 - 2015 <a href="mailto:spat.monroe@gmail.com">Contact</a></span>
    </footer>

  </body>

</html>