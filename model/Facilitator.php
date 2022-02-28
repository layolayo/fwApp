<?php
    /*
        This is repersents a facilitator model
    */
    class Facilitator
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

        /**
         * This will read content of user email
         */
        public function read($email)
        {
            $stmt = $this->conn->prepare("SELECT * FROM 
            facilitator WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            return $stmt->get_result();
        }

        /**
         * This will insert data into the facilitator email
         */
        public function insert($email, $userpasword, $disableuser = 0, $superuser = 0)
        {
            $stmt = $this->conn->prepare("INSERT INTO 
            `facilitator`(`email`, `userpassword`, `disableuser`, `superuser`)
            VALUES (?,?,?,?)");
            $stmt->bind_param("ssss", $email, $userpasword, $disableuser, $superuser);
            return $stmt->execute();
        }

        /**
         * This will update the user emails
         */
        public function update($email, $userpasword, $disableuser = 0, $superuser = 0)
        {
            $stmt = $this->conn->prepare("UPDATE `facilitator`
            SET email = ?, userpassword = ?, disableuser = ?, superuser = ?
            WHERE email = ?");
            $stmt->bind_param("sssss", $email, $userpasword, $disableuser, $superuser, $email);
            return $stmt->execute();
        }

        /**
         * This will get the user qs
         */
        public function getUserQs($email)
        {
            $stmt = $this->conn->prepare("SELECT * FROM amend_qs, question_set
            WHERE amend_qs.questionSetID = question_set.ID AND email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            return $stmt->get_result();
        }
    }
