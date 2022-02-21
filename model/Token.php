<?php
    /*
        This is repersents a tokens model
    */
    class Token
    {
        private $conn;

        public function __construct($db = null)
        {
            $this->conn = $db;
            if ($db == null) {
                $database = new Database();
                $this->conn = $database->connect();
            }
        }

        public function read($token)
        {

            $stmt = $this->conn->prepare("SELECT * FROM token WHERE id = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            return $stmt->get_result();
        }

        public function delete($token)
        {
            $stmt = $this->conn->prepare("DELETE FROM `token` WHERE ID = ?");
            $stmt->bind_param("s", $token);
            return $stmt->execute();
        }
    }
