<?php
	if(isset($_GET))
	{
		require 'connect.php';
		if($_GET['id'])
		{
			$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

			$query = "DELETE FROM user WHERE id = :id";

			$statement = $db->prepare($query);
			$statement->bindValue(':id', $id);
			$statement->execute();
		}
		header("Location: userinfo.php");
	}	
?>