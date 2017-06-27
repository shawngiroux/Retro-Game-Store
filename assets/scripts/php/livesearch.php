<?php

$q = $_GET['q'];
$titles = [];

// If q is empty, no point in searching
if (strlen($q) > 0) {
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
    // Move to error page? Database isn't working
  }

  $query = preg_split('/[\s]+/', $q); // split spaces from user input
  $sql = "SELECT * FROM INVENTORY WHERE"; // base string for sql statement
  $count = 0; // Used to format our sql statement properly by adding AND after the first loop

  // Change all words to wildcards for searching database
  for ($i = 0; $i < count($query); $i++) {
    $original = $query[$i];
    $query[$i] = "%" . $original . "%";
  }

  // Create a proper sql statement for searching
  foreach($query as $x) {
    if ($count == 0) {
      $sql .= " TITLE LIKE ?";
    } else {
      $sql .= " AND TITLE LIKE ?";
    }
    $count++;
  }
  $search = $pdo->prepare($sql);
  $search->execute($query);

  // Create elements for displaying
  while ($row = $search->fetch()) {
    array_push($titles, ucwords(strtolower($row['TITLE'])));
  }
  
}

//output the response
echo json_encode($titles);
?>