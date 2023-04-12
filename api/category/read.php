<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include '../../config/Database.php';
include '../../models/Category.php';

$database = new Database();
$db = $database->connect();

$category = new Category($db);

$result = $category->read();
$num = $result->rowCount();

if ($num > 0) {
    $category_arr = [];

    while ($row = $result->fetch(PDO::FETCH_ASSOC))
    {
        extract($row);

        $category_item = [
            'id' => $id,
            'name' => $name
        ];

        $category_arr[] = $category_item;
    }
    echo json_encode($category_arr);

} else {
    echo json_encode(
        ['message' => 'No Categories Found']
    );
}
