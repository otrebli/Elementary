<?php
/**
 *   Copyright 2013 Vimeo
 *
 *   Licensed under the Apache License, Version 2.0 (the "License");
 *   you may not use this file except in compliance with the License.
 *   You may obtain a copy of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
 *
 *   Unless required by applicable law or agreed to in writing, software
 *   distributed under the License is distributed on an "AS IS" BASIS,
 *   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *   See the License for the specific language governing permissions and
 *   limitations under the License.
 */
ini_set('display_errors', 'On');
error_reporting(E_ALL);

require_once('../vimeo.php');
$config = json_decode(file_get_contents('./config.json'), true);

$lib = new Vimeo($config['client_id'], $config['client_secret']);
$categoria='fashion';
if (!empty($config['access_token'])) {
    $lib->setToken($config['access_token']);
    $user = $lib->request('/categories/'.$categoria.'/videos');
    //$user = $lib->request('/me');
} else {
    $user = $lib->request('/users/dashron');
}
$conexion = pg_connect("
        host=127.0.0.1
        port=5432
        user=postgres
        password=jasiel:)
        dbname=ApiVimeo");
$numero=count($user['body']['data']);
for($i=0;$i<$numero;$i++){
$query="insert into  categoriavideo (uri,nombre,descripcion,link,duracion,ancho,alto,fecha_creacion,fecha_modificacion,categoria
	) values('".$user['body']['data'][$i]['uri']."','".$user['body']['data'][$i]['name']."','".$user['body']['data'][$i]['description']."','".$user['body']['data'][$i]['link']."'
	,'".$user['body']['data'][$i]['duration']."','".$user['body']['data'][$i]['width']."','".$user['body']['data'][$i]['height']."','".$user['body']['data'][$i]['created_time']."'
	,'".$user['body']['data'][$i]['modified_time']."','".$categoria."');";
    $consulta = pg_query_params($conexion, $query, array());
}
echo $numero;
print_r($user['body']['data'][16]);
