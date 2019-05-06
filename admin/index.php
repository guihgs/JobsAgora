<?php
session_start();

try{
$pdo = new PDO('mysql:host=localhost;dbname=joblister', 'guilherme', 'adb55kts');
}catch(PDOExeption $e){
	exit('Data base error.');
}

if (isset($_SESSION['logged_in'])) {
	//display index
	?>
	<html>
	<head>
		<title>JobsAgora</title>
		<link rel="stylesheet" type="text/css" href="https://bootswatch.com/3/flatly/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../css/styles.css"> 
	</head>
	<body>
		<div class="container">
<a href="index.php" id="logo">JobsAgora</a>
		<br /><br />
		<ol>
			<li><a href="add.php">Criar Vage</a></li>
			<li><a href="delete.php">Deletar Vaga</a></li>
			<li><a href="logout.php">Sair</a></li>
		</ol>
		</div>
	</body>
</html>
<?php
}else{
	//display login
	if (isset($_POST['username'], $_POST['password'])) {
		$username = $_POST['username'];
		$password = md5($_POST['password']);

		if (empty($username) or empty($password)) {
			
			$error = 'Todos os campos são obrigatórios';
		}else{
			$query = $pdo->prepare("SELECT * FROM users WHERE user_name = ? AND password = ? ");

			$query->bindValue(1, $username);
			$query->bindValue(2, $password);
		
			$query->execute();

			$num = $query->rowCount();

			if($num == 1){
				// user entered correct details
				$_SESSION['logged_in'] = true;
				header('Location: index.php');
				exit();

			}else{
				//user entered false details
				$error = 'Dados Incorretos';
			}

		}
	}
	?>
<html>
	<head>
		<title>JobsAgora</title>
		<link rel="stylesheet" href="style.css" /> 
	</head>
	<body>
		<div class="container">
<a href="index.php" id="logo">JobsAgora</a>
		<br /><br />
	<div class="wrapper fadeInDown">
  <div id="formContent">

    <!-- Icon -->
    <div class="fadeIn first">
     <h3 class="text-muted">JobsAgora</h3>
    </div>
<?php if (isset($error)) { ?>
			<small style="color: #aa0000;"><?php echo $error; ?></small>
		<br /><br />
		<?php	
		} ?>
    <!-- Login Form -->
    <form action="index.php" method="post" autocomplete="off">
      <input type="text" id="login" class="fadeIn second" name="username" placeholder="Usuario">
      <input type="password" id="password" class="fadeIn third" name="password" placeholder="Senha">
      <input type="submit" class="fadeIn fourth" value="LogIn">
    </form>
  </div>
</div>
	</body>
</html>
	<?php
}
?>