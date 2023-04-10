<?php
class RecepiesController
{
    public function __construct(private RecepiesGateway $gateway)
    {

    }
    public function processRequest(string $method, ?string $category, ?string $id): void
    {
        if ($id) {
            $this->processResourceRequest($method,$id);
        }
        else if($category) {
            $this->processCategoryRequest($method,$category);
        }
        else {
            $this->processCollectionRequest($method);
        }
    }

        
    private function processResourceRequest(string $method, string $id): void
    {
        $recepie = $this->gateway->get($id);
        
        if ( ! $recepie) {
            http_response_code(404);
            echo json_encode(["message" => "recepie not found"]);
            return;
        }
        
        switch ($method) {
            case "GET":
                echo json_encode($recepie);
                break;
                
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                
                $errors = $this->getValidationErrors($data, false);
                
                if ( ! empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    break;
                }
                
                $rows = $this->gateway->update($recepie, $data);
                
                echo json_encode([
                    "message" => "recepie $id updated",
                    "rows" => $rows
                ]);
                break;
                
            case "DELETE":
                $rows = $this->gateway->delete($id);
                
                echo json_encode([
                    "message" => "recepie $id deleted",
                    "rows" => $rows
                ]);
                break;
                
            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE");
        }
    }

    private function processCategoryRequest(string $method, string $category): void
    {
        $recepie = $this->gateway->getCat($category);   
        if (! $recepie) {
            http_response_code(404);
            echo json_encode(["message" => "recepie not found"]);
            return;
        }
        else 
        {
            echo json_encode($recepie);
        }

    }
    private function processCollectionRequest(string $method): void {
        switch ($method){
            case "GET" :
                echo json_encode($this->gateway->getAll());
                break;
                case "POST":
                    $data = (array) json_decode(file_get_contents("php://input"), true);
                    
                    $errors = $this->getValidationErrors($data);
                    
                    if ( ! empty($errors)) {
                        http_response_code(422);
                        echo json_encode(["errors" => $errors]);
                        break;
                    }
                    
                    $id = $this->gateway->create($data);
                    
                    http_response_code(201);
                    echo json_encode([
                        "message" => "recepie created",
                        "id" => $id
                    ]);
                    break;
        }
    }

    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];
        
        if ($is_new && empty($data["name"])) {
            $errors[] = "name is required";
        }
        
        if (array_key_exists("calories", $data)) {
            if (filter_var($data["calories"], FILTER_VALIDATE_INT) === false) {
                $errors[] = "calories must be an integer";
            }
        }
        
        return $errors;
    }
}