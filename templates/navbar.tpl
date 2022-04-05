<nav class="navbar navbar-expand-md navbar-light bg-light" style="margin-bottom: 24px 0;">
	<a class="navbar-brand" href="https://www.mutiny-guild.com"><img src="https://www.mutiny-guild.com/images/discord-shield.png" alt="Mutiny Home"></a>
	<ul class="navbar-nav mr-auto">
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Information
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
				<a class="dropdown-item" href="index.php?section=about">About Mutiny</a>
				<div class="dropdown-divider"></div>
				<!--<a class="dropdown-item" href="index.php?section=recruitment">Recruitment</a>-->
				<a class="dropdown-item" href="index.php?section=progression">Progression</a>
				<!--<div class="dropdown-divider"></div>-->
				<!--<a class="dropdown-item" href="#">Contact</a>-->
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" href="https://classic.warcraftlogs.com/guild/reports-list/510738/" target="_blank">Raid History</a>
        	</div>
      	</li>
		{if $logged_in}
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Guild Resources
			</a>
			<div class="dropdown-menu" aria-labelledby="navbarDropdown">
				<a class="dropdown-item" href="https://www.mutiny-guild.com/index.php?section=ksk">KSK Loot</a>
        	</div>	
      	</li>
		{/if}
	</ul>
	<ul class="navbar-nav navbar-right">
		{if $logged_in}
			<form class="form-inline my-2 my-sm-0" role="form" method="POST" action="index.php">
			<input type="hidden" name="do" value="logout" />
			<div class="text-dark" style="padding-right: 15px;">Logged in as: <strong>{$member_name}</strong></div>
			<button class="btn btn-success my-2 my-md-0" type="submit">Logout</button>
			</form>
		{else}
			<form class="form-inline my-2 my-sm-0" role="form" method="POST" action="index.php">
			<input type="hidden" name="do" value="login" />
			<button class="btn btn-success my-2 my-md-0" type="submit">Login</button>
			</form>
		{/if}
	</ul>
</nav>