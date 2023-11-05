<?php
require_once '../models/Database.php';
require_once '../models/Authentication.php';
session_start();

$db = new Database();
$authentication = new Authentication($db->getConnection());

if (empty($_SESSION['user_id']) && empty($_SESSION['admin_id'])) {
  $authentication->navigation('login.php');
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BadasFilm</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
  <div class="xl:container mx-auto px-4 sm:px-10 py-10">
    <div class="space-y-4">
      <p>
        Hello to <?php echo $_SESSION['name']; ?>
      </p>
      <a
        href="logout.php"
        class="inline-block focus:outline-none text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2"
      >
        Logout
      </a>
    </div>
  </div>
</body>
</html>