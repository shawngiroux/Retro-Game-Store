<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Retro Store | Catalog</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<!-- tablesorter 2.0 -->
	<script type="text/javascript" src="assets/js/jquery.tablesorter.js"></script>
	<script src="assets/js/jquery.tablesorter.widgets.js"></script>
	<!-- For tablesorter unsorted icon -->
	<link href="assets/css/theme.bootstrap.css" rel="stylesheet">
	<!-- jquery ui -->
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<!-- jquery ui css -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<!-- Custom styles for this template -->
	<link href="assets/css/catalog.css" rel="stylesheet">
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
	<?php
		$data = $pdo->prepare("SELECT * FROM INVENTORY WHERE CONSOLE LIKE :console");
		$data->bindParam(':console', $_GET['submit_param']);
		$data->execute();

		echo "<table class='table table-striped table_data'>
			  <caption>".$_GET['submit_param']."</caption>
			  	<thead>
			  		<tr>
					  <th>Title</th>
					  <th>Quantity</th>
					  <th>Price</th>
					  <th>Notes</th>
			  		</tr>
			 	 </thead>
			 	 <tbody>";

		while($row = $data->fetch()) {
			echo "<tr>
					  <td>".$row['TITLE']."</td>
					  <td>".$row['QUANTITY']."</td>
					  <td>$".str_pad($row['PRICE'], strlen($row['PRICE'])+3, ".00", STR_PAD_RIGHT)."</td>
					  <td>".$row['NOTES']."</td>
				  </tr>";
		}
		echo "	</tbody>
			  </table>";
	?>
	</div>
	<script>
		$(document).ready(function() {
			$.tablesorter.themes.bootstrap = {
			    iconSortNone : 'bootstrap-icon-unsorted',
			    iconSortAsc  : 'glyphicon glyphicon-chevron-up',
			    iconSortDesc : 'glyphicon glyphicon-chevron-down', 
		  	};
			$(".table_data").tablesorter({
				// This can all be customized, check documentation:
				// https://mottie.github.io/tablesorter/docs/example-option-theme-bootstrap-v3.html
				theme : "bootstrap",
			    widthFixed: true,
			    headerTemplate : '{content} {icon}',
			    widgets : [ "uitheme"]
			});
		});

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