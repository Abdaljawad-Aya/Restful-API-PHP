<?php

class Post {
    private $conn;
    private $table = 'posts';
    public $id;
    public $category_id;
    public $title;
    public $body;
    public $author;
    public $created_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read() {
        // Create query
        $query = 'SELECT c.name as category_name, p.id,p.category_id,
        p.title, p.body, p.author, p.created_at FROM ' .$this->table . ' p 
        LEFT JOIN categories c on p.category_id = c.id 
        ORDER BY p.created_at DESC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read_single()
    {
        $query = 'SELECT c.name as category_name, p.id,p.category_id,
        p.title, p.body, p.author, p.created_at FROM ' . $this->table . ' p 
        LEFT JOIN categories c on p.category_id = c.id 
        WHERE p.id = ? LIMIT 0,1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->title = $row['title'];
        $this->body = $row['body'];
        $this->author = $row['author'];
        $this->category_id = $row['category_id'];
    }

    public function create()
    {
        // Create query
        $query = 'INSERT INTO ' . $this->table .
            ' SET title = :title, body = :body, author
           = :author, category_id = :category_id';

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        // Bind data
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':category_id', $this->category_id);

        // Execute query
        if ($stmt->execute()){
            return true;
        }

        printf('Error: %s . \n ', $stmt->error);
        return false;
    }

    public function update() {
        // Create query
        $query = 'UPDATE ' . $this->table . '
          SET title = :title, body = :body, 
          author = :author, category_id = :category_id
          WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()){
            return true;
        }

        printf('Error: %s . \n ', $stmt->error);
        return false;
    }

    public function delete()
    {
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);
        // Execute query
        if($stmt->execute()) {
            return true;
        }

        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }
}