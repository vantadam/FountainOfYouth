<?php
class RecepiesController
{
    public function __construct(private RecepiesGateway $gateway)
    {

    }
    public function processRequest(string $method, ?string $id): void
    {
        if ($id) {
            $this->processRsourceRequest($method,$id);
        }
        else {
            $this->processCollectionRequest($method);
        }
    }

    private function processRsourceRequest(string $method, string $id): void
    {

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