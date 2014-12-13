<?php

//deletar registros

function DBremove($tabela, $where = null){

	$tabela 	= DB_PREFIX.'_'.$tabela; // pega pré-fixo tabela
	$where 		= ($where) ? " WHERE {$where}" : null; //verifica se a where tem valor, se não retorna nulo

	$query 		= "DELETE FROM {$tabela}{$where}";

	return DBexecute($query);

}

//atualizar registros

function DBupdate($tabela, $data, $where = null, $insertID){

	foreach ($data as $key => $value) {
		$campos[] 	= "{$key} = '{$value}'"; //percorre a chave e o valor alinhando e adicionando o sinal de igual
	}
	
	$campos 	= implode(', ', $campos); //separa os campos por virgula
	$tabela 	= DB_PREFIX.'_'.$tabela; // pega pré-fixo tabela
	$where 		= ($where) ? " WHERE {$where}" : null; //verifica se a where tem valor, se não retorna nulo
	$query = "UPDATE {$tabela} SET {$campos} {$where}"; //query
	return DBexecute($query, $insertID); //executa query bonitinha

}

//ler registros

function DBread($tabela, $params = null, $campos = '*'){
	$tabela 	= DB_PREFIX.'_'.$tabela; // pega pré-fixo tabela
	$params		= ($params) ? " {$params}" : null;//remove o espaço se a variavel for nula
	$query 		= "SELECT {$campos} FROM {$tabela} {$params}"; //execução da query
	$result		= DBexecute($query); //execução da query

	if(!mysqli_num_rows($result)) //SE AS COLUNAS DO RESUTADO FOR 0, RETORNA NULO, SE NÃO, EXIBE TODOS OS DADOS
		return false;
	else{
		while ($res = mysqli_fetch_assoc($result)) {
			$data[] = $res; //A VARIÁVEL DATA FICA EM BRANCO PARA PASSAR TODOS OS DADOS DA ARRAY
		}
		return $data;
	}
}

//grava registros

function DBcreate($tabela, array $data, $insertID = false){
$tabela 	= DB_PREFIX.'_'.$tabela; // pega pré-fixo tabela
$data		= DBescape($data);

$campos		= implode(', ', array_keys($data));//separa os campos por virgula
$valores	= "'".implode("', '", $data)."'"; //separa os valores das arrays por virgula e adiciona aspas simples
$query 	= "INSERT INTO {$tabela} ( {$campos} ) VALUES ( {$valores} )"; //execução da query
return DBexecute($query, $insertID);
}


//executa query

function DBexecute($query, $insertID = false){
	$link	= DBconnect(); //pega conexão com banco de dados
	$result	= @mysqli_query($link, $query) or die(mysqli_error($link));

	if($insertID)
		$result = mysqli_insert_id($link);
	DBclose($link);

	return $result;
}