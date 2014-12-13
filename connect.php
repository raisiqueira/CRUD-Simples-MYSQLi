<?php

//Proteção contra SQL Injection

function DBescape($dados){
	$link = DBconnect();

	if(!is_array($dados))
		$dados = mysqli_real_escape_string($link, $dados);

	else{
		$arr = $dados;

		foreach ($arr as $key => $value) {
			$key = mysqli_real_escape_string($link, $key);
			$value = mysqli_real_escape_string($link, $value);

			$dados[$key] = $value;
		}
	}

	DBclose($link);
	return $dados;
}


//fecha conexão

function DBclose($mysqli){
@mysqli_close($mysqli) or die(mysqli_errror($mysqli));
}

//Abrir conexão

function DBconnect(){
	$mysqli = @mysqli_connect(DB_HOSTNAME, DB_USER, DB_PASSWORD, DB_DATABASE) or die(mysqli_connect_error());
	mysqli_set_charset($mysqli, DB_CHARSET) or die(mysqli_errror($mysqli)); //definir charset

	return $mysqli;
}