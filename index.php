<?php 
	require_once "class.pessoa.php";
	$p = new Pessoa("pdo","127.0.0.1:3308","root","");
	 // "mysql:host=127.0.0.1:3308;dbname=pdo","root",""	

?>
<!DOCTYPE html>
<html>
<head>
	<title>Cadastrar Pessoa</title>
	<link rel="stylesheet" type="text/css" href="estilo.css">
</head>
<body>
	<?php
		if (isset($_POST['nome'])) {
			//CLICOU NO BOTAO CADASTRAR OU ATUALIZAR

			// EDITAR DADOS
			if (isset($_GET['id_up']) && !empty($_GET['id_up'])) {
					
				$nome = addslashes($_POST['nome']);
				$telefone = addslashes($_POST['telefone']);
				$email = addslashes($_POST['email']);
				
				if (!empty($nome) && !empty($telefone) && !empty($email)) {

					$p->atualizarDados($nome,$telefone,$email,$_GET['id_up']);

					header("location: /crud");					
				}else
				{
					echo '<h2>Preencha todos os campos!!!</h2>';
				}
		}else {
			// CADASTRAR DADOS
			$nome = addslashes($_POST['nome']);
			$telefone = addslashes($_POST['telefone']);
			$email = addslashes($_POST['email']);
			if (!empty($nome) && !empty($telefone) && !empty($email)) {
				//Cadastrar
				if(!$p->cadastrarPessoa($nome,$telefone,$email))
				{
					echo "Email já está cadastrado!!!";
				}
			}
			else
			{
				echo '<h2>Preencha todos os campos!!!</h2>';
			}
		}
	}
	?>

<?php 
		
		if (isset($_GET["id_up"])) //CLICOU NO BOTAO EDITAR
		{
				
			$id_update= addslashes($_GET["id_up"]);
			$res = $p->buscarPessoa($id_update);
				
		}
	?>

	<section id="esquerda">
		<form method="POST">
			<h2>CADASTRAR PESSOA</h2>
			<label for="nome">Nome</label>
			<input type="text" name="nome" id="nome" value="<?php if(isset($res)){ echo $res['nome'];} ?>">
			<label for=telefone>Telefone</label>
			<input type="tel" name="telefone" id="telefone" value="<?php if(isset($res)){ echo $res['telefone'];} ?>" >
			<label for="email">Email</label>
			<input type="email" name="email" id="email" value="<?php if(isset($res)){ echo $res['email'];} ?>">
			<input type="submit" name="cadastrar" value="<?php if(isset($res)){ echo "Atualizar";}else{echo "Cadastrar";} ?>">
		</form>
	</section>


		<section id="direita">
		
		<table >
			<thead>
				<tr id="titulo">
				<th>Nome</th>
				<th>Telefone</th>
				<th>Email</th>
				<th>Ações</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$dados = $p->buscarDados();
						if (count($dados)> 0 ) {
							foreach ($dados as $key => $value) {

						echo '<tr><td>'. $value["nome"] .'</td><td>'. $value["telefone"].'</td><td>'. $value["email"] .'</td><td><a href="index.php?id_up='.$value["id"].'">Editar</a><a href="index.php?id='.$value["id"].'">Excluir</a></td></tr>';	

							}
					}
					else {
						echo "<td colspan=4 style='text-align:center;'><span>Não há nada para mostrar!!!</span></td></tr>";
					}
				?>	
			</tbody>
		</table>
	</section>
</body>
</html>

<?php 
	

	if (isset($_GET["id"])) {
			
		$id_pessoa= addslashes($_GET["id"]);
		$p->excluirPessoa($id_pessoa);
		header("location: /crud");

	}
?>

