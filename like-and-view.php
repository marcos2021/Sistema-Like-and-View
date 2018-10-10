<?php 

function getVisitaPort() {
		try {
			$id = filter_input(INPUT_GET, 'p', FILTER_VALIDATE_INT);

			if(! isset($_SESSION['login']['usuario_id'][$id])){

			Transaction::open();
			$conexao = Transaction::getInstance();
			$query = $conexao->prepare("SELECT * FROM portfolios WHERE id = :id");
			$query->bindValue(':id', $id, PDO::PARAM_INT);
			$query->execute();
			$result = $query->fetch();
			Transaction::close();

			$contar = $result['visitas'] + 1;


			Transaction::open();
			$conexao = Transaction::getInstance();
			$query = $conexao->prepare("UPDATE portfolios SET visitas = :visitas WHERE id = :id");
			$query->bindValue(':visitas', $contar, PDO::PARAM_INT);
			$query->bindValue(':id', $id, PDO::PARAM_INT);
			$query->execute();
			Transaction::close();

			session_start();
			$_SESSION['login']['usuario_id'][$id] = $id;

			}

			
		} catch (Exception $e) {
			
		}

}


function getLikePort() {
		try {



		$id = filter_input(INPUT_GET, 'p', FILTER_VALIDATE_INT);

		$botaofinal = '<input class="btn btn-avaliar-projeto" type="submit" value="Avaliar Projeto" name="like" />';

		session_start();


		Transaction::open();
		$conexao = Transaction::getInstance();
		$query = $conexao->prepare("SELECT * FROM portfolios WHERE id = :id");
		$query->bindValue(':id', $id, PDO::PARAM_INT);
		$query->execute();
		$resultlike = $query->fetch();
		Transaction::close();

		$likearray = explode(',', $resultlike['likeuser']);

		$idlike = $_SESSION['login']['usu_id'];

		if(!isset($_SESSION['login']['usu_nome'])){
			$botaofinal = '<a href="login.php" class="btn btn-avaliar-projeto"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Avaliar Projeto</a>';
		}

		elseif(in_array($idlike, $likearray)){
		$botaofinal = '<div class="btn btn-avaliar-ok"><i class="fa fa-thumbs-o-up"></i> Projeto Avaliado</div>';

		}

		if(isset($_POST['like'])) {

			Transaction::open();
			$conexao = Transaction::getInstance();
			$query = $conexao->prepare("SELECT * FROM portfolios WHERE id = :id");
			$query->bindValue(':id', $id, PDO::PARAM_INT);
			$query->execute();
			$result = $query->fetch();
			Transaction::close();

			$likes = $result['likes'] + 1;


			Transaction::open();
			$conexao = Transaction::getInstance();
			$query = $conexao->prepare("UPDATE portfolios SET likes = :likes WHERE id = :id");
			$query->bindValue(':likes', $likes, PDO::PARAM_INT);
			$query->bindValue(':id', $id, PDO::PARAM_INT);
			$query->execute();
			Transaction::close();

			$contar .= $result['likeuser'];
			$contar .= ',';
			$contar .= $idlike;

			Transaction::open();
			$conexao = Transaction::getInstance();
			$query = $conexao->prepare("UPDATE portfolios SET likeuser = :likeuser WHERE id = :id");
			$query->bindValue(':likeuser', $contar, PDO::PARAM_STR);
			$query->bindValue(':id', $id, PDO::PARAM_INT);
			$query->execute();
			Transaction::close();

			echo '<script>
					window.location.href = "' . HTTP . '/portfolio.php?p='.$id.'";
				  </script>';

		}

		return $botaofinal;

					

			
		} catch (Exception $e) {
			echo $e->getMessage();
		}

}
