<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Retro Store | Search Game</title>
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
	<link href="assets/css/search.css" rel="stylesheet">
</head>
<body>
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
									echo "<li><a href='#'><button onclick='prepare_data(\"submit_param\",\"".$row['CONSOLE']."\")'>".$row['CONSOLE']."</button></a></li>";
								}
							?>
							<input type="hidden" id="submit_param" name="submit_param" value="">
						</form>
					</ul>
				</li>
				<li><a href="home.php">Home</a></li>
				<li><a href="#">About Us</a></li>
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

	<!-- <div class="wrapper"> -->
		<?php
			$search = $pdo->prepare("SELECT * FROM INVENTORY WHERE TITLE = ?");
			$search->bindParam(1, $_GET['title']);
			$search->execute();

			if ($search->rowCount() < 1) {
				$search = $pdo->prepare("SELECT * FROM INVENTORY WHERE TITLE LIKE ?");
				$title = "%" . $_GET['title'] . "%";
				$search->bindParam(1, $title);
				$search->execute();
				echo "<div class='wrapper' style='margin-top: 70px;'>
					  <div class='container'>
					  <form action='search.php' method='get'>
					  <p>Sorry, this game does not exist in our database!</p>
					  <p>Did you mean...</p>";

				while ($row = $search->fetch()) {
					echo "<p><button onclick='prepare_data(\"game_selection\", \"" . $row['TITLE'] . "\")'>" . $row['TITLE'] . " (". $row['CONSOLE'] .")</button><p>";
				}

				echo "<input type='hidden' id='game_selection' name='title' value=''>
					  </form>
					  </div>
					  </div>";
			} else {
				// Mario bros will mess up display
				while ($row = $search->fetch()) {
					// boxart
					echo "<div class='wrapper'>
						  <div class='container'>
						  <div class='col-xs-6 col-md-6' id='boxart'>
						  	<img src='boxart.png' alt=''>
						  	<p>No images for any games</p>
						  </div>";

					// Game description and info
					echo "<div class='col-xs-6 col-md-6' id='game_details'>
						  <p><strong>Title:</strong> " . capfl($row['TITLE']) . "</p>
						  <p><strong>Console:</strong> " . capfl($row['CONSOLE']) . "</p>
						  <p><strong>Quantity:</strong> " . $row['QUANTITY'] . "</p>
						  <p><strong>Price:</strong> $" . str_pad($row['PRICE'], strlen($row['PRICE'])+3, ".00", STR_PAD_RIGHT) . "</p>
						  <p><strong>Notes:</strong> " . $row['NOTES'] . "</p>
						  </div>
						  </div>
						  </div>";
				}
			}
			// capfl = capitalize first letter
			function capfl($string) {
				return ucwords(strtolower($string));
			}
		?>	
		<!-- </div> -->
	<script>
		function prepare_data(name, data) {
			$("#" + name).val(data);
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