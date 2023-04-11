<?php
class RecepiesGateway 
{
   
    private PDO $conn;
    
    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }
    
    public function getAll(): array
    {
        $sql = "SELECT *
                FROM recepie";
                
        $stmt = $this->conn->query($sql);
        
        $data = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            
            $data[] = $row;
        }
        
        return $data;
    }


    public function create(array $data): string
    {
        $sql = "INSERT INTO recepie (category, name, calories, prep, cook, serves, description, ingredients, steps)
                VALUES (:category, :name, :calories, :prep, :cook, :serves, :description, :ingredients, :steps)";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":category", $data["category"], PDO::PARAM_STR);
        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(":calories", $data["calories"] ?? 0, PDO::PARAM_INT);
        $stmt->bindValue(":prep", $data["prep"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":cook", $data["cook"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":serves", $data["serves"] ?? 0, PDO::PARAM_INT);
        $stmt->bindValue(":description", $data["description"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":ingredients", $data["ingredients"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":steps", $data["steps"] ?? "", PDO::PARAM_STR);
        
        $stmt->execute();
        
        return $this->conn->lastInsertId();
    }
    public function get(string $id): array | false
    {
        $sql = "SELECT *
                FROM recepie
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
       
        
        return $data;
    }
    
    public function update(array $current, array $new): int
    {
        $sql = "UPDATE recepie
                SET category= :category, name = :name, calories = :calories, prep = :prep, cook = :cook, serves = :serves, description = :description, ingredients = :ingredients, steps = :steps
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":category", $new["category"] ?? $current["category"], PDO::PARAM_STR);
        $stmt->bindValue(":name", $new["name"] ?? $current["name"], PDO::PARAM_STR);
        $stmt->bindValue(":calories", $new["calories"] ?? $current["calories"], PDO::PARAM_INT);
        $stmt->bindValue(":prep", $new["prep"] ?? $current["prep"], PDO::PARAM_STR);
        $stmt->bindValue(":cook", $new["cook"] ?? $current["cook"], PDO::PARAM_STR);
        $stmt->bindValue(":serves", $new["serves"] ?? $current["serves"], PDO::PARAM_INT);
        $stmt->bindValue(":description", $new["description"] ?? $current["description"], PDO::PARAM_STR);
        $stmt->bindValue(":ingredients", $new["ingredients"] ?? $current["ingredients"], PDO::PARAM_STR);
        $stmt->bindValue(":steps", $new["steps"] ?? $current["steps"], PDO::PARAM_STR);
        
        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->rowCount();
    }
    
    public function delete(string $id): int
    {
        $sql = "DELETE FROM recepie
                WHERE id = :id";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        
        $stmt->execute();
        
        return $stmt->rowCount();
    }
    public function getCat(string $category): array 
    {
        $sql = "SELECT *
                FROM recepie
                WHERE category = :category";
                
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":category", $category, PDO::PARAM_STR);
        
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            
            $data[] = $row;
        }
        
       
        
        return $data;
    }
}