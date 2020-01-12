<?php

class Pessoa
{
	private $pdo;
	// 6 funçoes
	// conexao com o banco de dados	
	public function __construct($dbname,$host, $user, $password)
	{
		try {
		$this->pdo = new PDO("mysql:dbname=".$dbname .";host=".$host,$user,$password);
							


		} catch (PDOException $e) {
			echo "Erro com banco de dados! " . $e->getMessage();
			exit();
		}catch(Exception $e){
			echo "Erro genérico! " . $e->getMessage();
		}

		
	}
	//FUNCAO PARA BUSCAR DADOS E ADICIONAR NA TABELA.
	public function BuscarDados()
	{
		$res = array();
		$cmd = $this->pdo->query("SELECT * FROM pessoa ORDER BY nome");
		$res = $cmd->fetchALL(PDO::FETCH_ASSOC);
		return $res;
	}

	public function cadastrarPessoa($nome,$telefone,$email)
	{
		//ANTES DE CADASTRAR VERIFICAR SE JÁ POSSUI O EMAIL CADASTRADO

		$cmd = $this->pdo->prepare("SELECT id FROM pessoa WHERE email = :e");
		$cmd->bindValue(":e",$email);
		$cmd->execute();
		if ($cmd->rowCount() > 0 ) //email já existe no banco
		{
			return false;
		}else //não foi encontrado
		 {
		 	$cmd = $this->pdo->prepare("INSERT INTO PESSOA (nome,telefone,email) VALUES(:n,:t,:e)");
		 	$cmd->bindValue(":n",$nome);
		 	$cmd->bindValue(":t",$telefone);
		 	$cmd->bindValue(":e",$email);

		 	$cmd->execute();

		 	return true;
		}


	}

	//BUSCAR DADOS DE UMA PESSOA
	public function buscarPessoa($id)
	{
		$res = array();
		$cmd = $this->pdo->prepare("SELECT * FROM pessoa WHERE id = :id");
		$cmd->bindValue(":id",$id);
		$cmd->execute();
		$res = $cmd->fetch(PDO::FETCH_ASSOC);
		return $res;
	}

	//ATUALIZAR DADOS DA PESSOA
	public function atualizarDados($nome,$telefone, $email,$id)
	{
		$cmd = $this->pdo->prepare("UPDATE pessoa SET nome = :n, telefone = :t, email = :e WHERE id = :id");
		$cmd->bindValue(":n",$nome);
		$cmd->bindValue(":t",$telefone);
		$cmd->bindValue(":e",$email);
		$cmd->bindValue(":id",$id);

		$cmd->execute();
	}


	public function excluirPessoa($id)
	{
		$cmd = $this->pdo->prepare("DELETE FROM PESSOA WHERE id=:id");

		$cmd->bindValue(":id",$id);
		$cmd->execute();
	}
}