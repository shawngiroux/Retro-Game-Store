<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Retro Store | Home</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<!-- jquery ui -->
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<!-- jquery ui css -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<!-- Custom styles for this template -->
	<link href="assets/css/home.css" rel="stylesheet">
</head>
<body>
	<img id="cover" src="assets/img/cover.png" alt="">

	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="home.php">Retro Game Store</a>
			</div>
			<ul class="nav navbar-nav navbar-left">
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">Our Catalog
					<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<form action="catalog.php" method="get">
							<?php
								$host = '';
								$db   = '';
								$user = '';
								$pass = '';

								$dsn = "mysql:host=$host;dbname=$db;charset=utf8";
								$opt = [
										PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
										PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
										PDO::ATTR_EMULATE_PREPARES => false,
								];

								try {
									$pdo = new PDO($dsn, $user, $pass, $opt);
								} catch (PDOException $e) {
									// Move to error page?
								}
								$sql = "SELECT DISTINCT CONSOLE FROM INVENTORY ORDER BY CONSOLE ASC";
								foreach($pdo->query($sql) as $row) {
									// Populated the consoles for the catalog
									// echo "<li><button>".$row['CONSOLE']."</button></li>";
									echo "<li><a href='#'><button onclick='prepare_data(\"".$row['CONSOLE']."\")'>".$row['CONSOLE']."</button></a></li>";
								}
							?>
							<input type="hidden" id="submit_param" name="submit_param" value="">
						</form>
					</ul>
				</li>
				<li><a href="home.php">Home</a></li>
				<li><a href="sorry.php">About Us</a></li>
				<li><a href="../projects.php">Portfolio</a></li>
			</ul>
            <form class="navbar-form navbar-right" action="search.php" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" id="autocomplete" name="title" placeholder="Search Game">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="submit">
                        <span class="glyphicon glyphicon-search"></span>
                        </button>
                    </div>  
                </div>
            </form>
		</div>
	</nav>

	<div class="container">
		<h1>Welcome!</h1>
		<p>Here are Retro Game Store, we sell a number of different retro games to service all of your nostalgia needs. You can see our catalog in the link above, or search for a game by the title!</p>
	</div>
	<hr>
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h2>Rental</h2>
				<p>Don't want to buy? Relive your Blockbuster and Movie Gallery days by renting games! You can rack up late fees just like you used to!</p>
				<p><a class="btn btn-default" href="sorry.php" role="button">View Details &raquo;</a></p>
			</div>
			<div class="col-md-6">
				<h2>About Us</h2>
				<p>Curious about who we are? Check out here to learn a little more about the staff at the Retro Game Store!</p>
				<p><a class="btn btn-default" href="sorry.php" role="button">View Details &raquo;</a></p>
			</div>
		</div>
	</div>
	<footer class="footer">
		<div class="container">
			<span class="text-muted">This is not a real company, just a database demonstration</span>
		</div>
	</footer>
	<script>
		function prepare_data(data) {
			$("#submit_param").val(data);
		}

		var game_list; // Holds list of likely games from database based off user input
        
        $("#autocomplete").keyup(function(){
        	if ($(this).val().length > 0){
	        	$.ajax({
	                url: "assets/scripts/php/livesearch.php",
	                type: "get",
	                data: "q="+this.value,
	                datatype: 'json',
	                cache: false,
	                success: function(data) {
	                	loadData(JSON.parse(data))
	                }
	            });
	        	$("#autocomplete").autocomplete({
	        		source: game_list
	        	});
        	}
        	$("#autocomplete").autocomplete("widget").addClass("fixedHeight");
        });
        
        function loadData(data) {
        	game_list = data;
        }

	</script>
</body>
</html>