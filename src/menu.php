<nav class="navbar navbar-default navbar-static-top" role="navigation">
  <ul class="nav navbar-nav">
  <li <?php if(!empty($actor_nav)){echo 'class="active"';} ?>><a href="actors">Actors</a></li>
  <li <?php if(!empty($movie_nav)){echo 'class="active"';} ?>><a href="movies">Movies</a></li>
  <li <?php if(!empty($director_nav)){echo 'class="active"';} ?>><a href="directors">Directors</a></li>
  <li <?php if(!empty($producer_nav)){echo 'class="active"';} ?>><a href="producers">Producers</a></li>
  <li <?php if(!empty($song_nav)){echo 'class="active"';} ?>><a href="songs">Songs</a></li>
  <li <?php if(!empty($award_nav)){echo 'class="active"';} ?>><a href="awards">Awards</a></li>
  <li>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp </li>
  <?php
	if(isset($_SESSION['valid']) && $_SESSION['valid'])
	{ ?>
		<li><a href="#">Logged in as: <?php echo $_SESSION['username']; ?></a></li>
		<li><a href='logout'>Logout</a></li>
	<?php } 
	else
	{ ?>
		<li <?php if(!empty($login_nav)){echo 'class="active"';} ?>><a href="login">Login</a></li>
	<?php } ?>
  </ul>
</nav>

<?php

if($message_type == "fail")
{ ?>
<div class="alert alert-danger">
  <?php echo $message; ?>
</div>
<?php }
else if($message_type == "success")
{ ?>
<div class="alert alert-success">
  <?php echo $message; ?>
</div>
<?php } ?>