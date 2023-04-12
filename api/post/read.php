<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include '../../config/Database.php';
include '../../models/Post.php';

$database = new Database();
$db = $database->connect();

$post = new Post($db);

$result = $post->read();
$num = $result->rowCount();

if ($num > 0) {
    $post_arr = [];

    while ($row = $result->fetch(PDO::FETCH_ASSOC))
    {
        extract($row);

        $post_item = [
            'id' => $id,
            'title' => $title,
            'body' => html_entity_decode($body),
            'author' => $author,
            'category_id' => $category_id,
//            'category_name' => $category_name
        ];

        $post_arr[] = $post_item;
    }
    echo json_encode($post_arr);

} else {
    echo json_encode(
        ['message' => 'No Posts Found']
    );
}
