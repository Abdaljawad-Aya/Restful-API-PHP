<?php

require_once './vendor/autoload.php';
require_once './config/Database.php';

class Seeder {

    private $conn;

    public function __construct(Database $db)
    {
        $this->conn = $db->connect();
    }
    public function seed() {

            $faker = Faker\Factory::create();

            for ($i = 1; $i <= 10; $i++) {
                $name = $faker->name;
                $stmt = $this->conn->prepare('INSERT INTO categories (name) VALUES (?)');
                $stmt->execute([$name]);
            }

            // This code first retrieves a list of existing category ids from the categories
            // table using a SELECT statement. It then uses the array_rand() function to generate a random category id
            // from this list for each post that is inserted
            // into the posts table. This ensures that the category_id values being inserted into the
            // posts table already exist in the id column of the categories table.

            $stmt = $this->conn->query('SELECT id FROM categories');
            $category_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

            for ($i = 1; $i <= 10; $i++) {
                $title = $faker->sentence(2);
                $body = implode('<p></p>', $faker->paragraphs(5));
                $author = $faker->name;
                $category_id = $category_ids[array_rand($category_ids)];

                $stmt = $this->conn->prepare('INSERT INTO posts (title, body, author, category_id) VALUES (?, ?, ?, ?)');
                $stmt->execute([$title, $body, $author, $category_id]);
            }
    }
}

$db = new Database();
$seeder = new Seeder($db);

// getopt to handle command line argument and create command just like laravel but without using artisan
// which is php Seeder.php --seed or php Seeder.php -s
$option = getopt('s', ['seed']);
if (array_key_exists('s', $option) || array_key_exists('seed', $option))
{
    $seeder->seed();
    echo "\033[32mData seeded successfully";
} else {
    echo "\033[32mNo data seeded";
}
