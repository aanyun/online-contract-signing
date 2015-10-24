<nav class="navbar navbar-default">
	<div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">
        Electronic Contract
      </a>
    </div>
    <ul class="nav navbar-nav navbar-right">
      <?php
        if (stripos($_SERVER['REQUEST_URI'], 'new.php') || stripos($_SERVER['REQUEST_URI'], 'edit.php')){
          echo '<li><a href="contractList.php" class="navlink">Contract List&nbsp;&nbsp;<span class="glyphicon glyphicon-list"></span></a></li>';
        } else if (stripos($_SERVER['REQUEST_URI'], 'contractList.php')){
          echo '<li><a href="new.php" class="navlink">Create New Contract &nbsp;&nbsp;<span class="glyphicon glyphicon-plus"></span></a></li>';
        }
      ?>
    </ul>
  </div>
</nav>