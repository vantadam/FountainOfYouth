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
        $sql = "INSERT INTO recepie (name, calories, prep, cook, serves, description, ingredients, steps)
                VALUES (:name, :calories, :prep, :cook, :serves, :description, :ingredients, :steps)";
                
        $stmt = $this->conn->prepare($sql);
        
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
}