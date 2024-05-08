<?php

namespace bng\Models;

use bng\Models\BaseModel;

class Agents extends BaseModel
{
    // =======================================================
    public function check_login($username, $password)
    {
        // check if the login is valid
        $params = [
            ':username' => $username
        ];

        // check if there is a user in the database
        $this->db_connect();
        $results = $this->query(
            "SELECT id, passwrd FROM agents " .
            "WHERE AES_ENCRYPT(:username, '" .MYSQL_AES_KEY."') = name " . 
            "AND deleted_at IS NULL"
        , $params);
        
        // if there is no user, returns false
        if($results->affected_rows == 0){
            return [
                'status' => false
            ];
        }
        
        // there is a user with that name (username)
        // check if the password is correct
        if(!password_verify($password, $results->results[0]->passwrd)){
            return [
                'status' => false
            ];
        }

        // login is ok
        return [
            'status' => true
        ];
    }

    // =======================================================
    public function get_user_data($username)
    {
        // get all necessary user data to insert in the session
        $params = [
            ':username' => $username
        ];
        $this->db_connect();
        $results = $this->query(
            "SELECT " .
                "id, " .
                "AES_DECRYPT(name, '" . MYSQL_AES_KEY . "') name, " .
                "profile " .
                "FROM agents " .
                "WHERE AES_ENCRYPT(:username, '" . MYSQL_AES_KEY . "') = name",
            $params
        );

        return [
            'status' => 'success',
            'data' => $results->results[0]
        ];
    }

    // =======================================================
    public function set_user_last_login($id)
    {
        // updates the user's last login
        $params = [
            ':id' => $id
        ];
        $this->db_connect();
        $results = $this->non_query(
            "UPDATE agents SET " . 
            "last_login = NOW() " . 
            "WHERE id = :id"
        ,$params);
        return $results;
    }

    // =======================================================
    public function get_agent_clients($id_agent)
    {
        // get all clientes from the agent with the specified id_agent
        $params = [
            ':id_agent' => $id_agent
        ];
        $this->db_connect();
        $results = $this->query(
            "SELECT " .
                "id, " .
                "AES_DECRYPT(name, '" . MYSQL_AES_KEY . "') name, " .
                "gender, " .
                "birthdate, " .
                "AES_DECRYPT(email, '" . MYSQL_AES_KEY . "') email, " .
                "AES_DECRYPT(phone, '" . MYSQL_AES_KEY . "') phone, " .
                "interests, " .
                "created_at, " .
                "updated_at " .
                "FROM persons " .
                "WHERE id_agent = :id_agent " .
                "AND deleted_at IS NULL",
            $params
        );

        return [
            'status' => 'success',
            'data' => $results->results
        ];
    }

    // =======================================================
    public function check_if_client_exists($post_data)
    {
        // check if there is already a client with the same name
        $params = [
            ':id_agent' => $_SESSION['user']->id,
            ':client_name' => $post_data['text_name']
        ];

        $this->db_connect();
        $results = $this->query(
            "SELECT id FROM persons " . 
            "WHERE AES_ENCRYPT(:client_name, '" . MYSQL_AES_KEY . "') = name " . 
            "AND id_agent = :id_agent",
            $params
        );

        if($results->affected_rows == 0){
            return [
                'status' => false
            ];
        } else {
            return [
                'status' => true
            ];
        }
    }

    // =======================================================
    public function add_new_client_to_database($post_data)
    {
        // add new client to database

        $birthdate = new \DateTime($post_data['text_birthdate']);

        $params = [
            ':name' => $post_data['text_name'],
            ':gender' => $post_data['radio_gender'],
            ':birthdate' => $birthdate->format('Y-m-d H:i:s'),
            ':email' => $post_data['text_email'],
            ':phone' => $post_data['text_phone'],
            ':interests' => $post_data['text_interests'],
            ':id_agent' => $_SESSION['user']->id
        ];

        $this->db_connect();
        $this->non_query(
            "INSERT INTO persons VALUES(" .
                "0, " .
                "AES_ENCRYPT(:name, '" . MYSQL_AES_KEY . "'), " .
                ":gender, " .
                ":birthdate, " .
                "AES_ENCRYPT(:email, '" . MYSQL_AES_KEY . "'), " .
                "AES_ENCRYPT(:phone, '" . MYSQL_AES_KEY . "'), " .
                ":interests, " .
                ":id_agent, " .
                "NOW(), " .
                "NOW(), " .
                "NULL" .
                ")",
            $params
        );
    }

    // =======================================================
    public function get_client_data($id_client)
    {
        // get client data by id
        $params = [
            ':id_client' => $id_client
        ];

        $this->db_connect();
        $results = $this->query(
            "SELECT " . 
            "id, " . 
            "AES_DECRYPT(name, '" . MYSQL_AES_KEY . "') name, " . 
            "gender, " . 
            "birthdate, " . 
            "AES_DECRYPT(email, '" . MYSQL_AES_KEY . "') email, " . 
            "AES_DECRYPT(phone, '" . MYSQL_AES_KEY . "') phone, " . 
            "interests " . 
            "FROM persons " . 
            "WHERE id = :id_client"
        , $params);

        if($results->affected_rows == 0){
            return [
                'status' => 'error'
            ];
        }

        return [
            'status' => 'success',
            'data' => $results->results[0]
        ];
    }

    // =======================================================
    public function check_other_client_with_same_name($id, $name)
    {
        // check if exists another agent's client with the same name
        $params = [
            ':id' => $id,
            ':name' => $name,
            ':id_agent' => $_SESSION['user']->id
        ];
        $this->db_connect();
        $results = $this->query(
            "SELECT id " .
                "FROM persons " .
                "WHERE id <> :id " .
                "AND id_agent = :id_agent " .
                "AND AES_ENCRYPT(:name, '" . MYSQL_AES_KEY . "') = name",
            $params
        );

        if ($results->affected_rows != 0) {
            return ['status' => true];
        } else {
            return ['status' => false];
        }
    }

    // =======================================================
    public function update_client_data($id, $post_data)
    {
        // updates the agent's client data in the database
        $birthdate = new \DateTime($post_data['text_birthdate']);
        $params = [
            ':id' => $id,
            ':name' => $post_data['text_name'],
            ':gender' => $post_data['radio_gender'],
            ':birthdate' => $birthdate->format('Y-m-d H:i:s'),
            ':email' => $post_data['text_email'],
            ':phone' => $post_data['text_phone'],
            ':interests' => $post_data['text_interests'],
        ];
        $this->db_connect();
        $this->non_query(
            "UPDATE persons SET " . 
            "name = AES_ENCRYPT(:name, '" . MYSQL_AES_KEY . "'), " .
            "gender = :gender, " . 
            "birthdate = :birthdate, " . 
            "email = AES_ENCRYPT(:email, '" . MYSQL_AES_KEY . "'), " .
            "phone = AES_ENCRYPT(:phone, '" . MYSQL_AES_KEY . "'), " .
            "interests = :interests, " . 
            "updated_at = NOW() " . 
            "WHERE id = :id"
        , $params);
    }

    // =======================================================
    public function delete_client($id_client)
    {
        // deletes the client from the database (hard delete)
        $params = [
            ':id' => $id_client
        ];
        $this->db_connect();
        $this->non_query("DELETE FROM persons WHERE id = :id", $params);
    }

    // =======================================================
    public function check_current_password($current_password)
    {
        // check if the current_password is equal to the one in the database
        $params = [
            ':id_user' => $_SESSION['user']->id
        ];
        $this->db_connect();
        $results = $this->query(
            "SELECT passwrd " .
                "FROM agents " .
                "WHERE id = :id_user",
            $params
        );

        if (password_verify($current_password, $results->results[0]->passwrd)) {
            return [
                'status' => true
            ];
        } else {
            return [
                'status' => false
            ];
        }
    }

    // =======================================================
    public function update_agent_password($new_passwrd)
    {
        // updates the current user password
        $params = [
            ':passwrd' => password_hash($new_passwrd, PASSWORD_DEFAULT),
            ':id' => $_SESSION['user']->id
        ];

        $this->db_connect();
        $this->non_query(
            "UPDATE agents SET " . 
            "passwrd = :passwrd, " . 
            "updated_at = NOW() " . 
            "WHERE id = :id"
        , $params);
    }

    // =======================================================
    public function check_new_agent_purl($purl)
    {
        // check if there is a new agent with this purl
        $params = [
            ':purl' => $purl
        ];
        $this->db_connect();
        $results = $this->query(
            "SELECT id FROM agents WHERE purl = :purl"
        , $params);

        if($results->affected_rows == 0){
            return [
                'status' => false
            ];
        } else {
            return [
                'status' => true,
                'id' => $results->results[0]->id
            ];
        }
    }

    // =======================================================
    public function set_agent_password($id, $new_passwrd)
    {
        // updates the current user password
        $params = [
            ':passwrd' => password_hash($new_passwrd, PASSWORD_DEFAULT),
            ':id' => $id
        ];

        $this->db_connect();
        $this->non_query(
            "UPDATE agents SET " . 
            "passwrd = :passwrd, " . 
            "purl = NULL, " .
            "updated_at = NOW() " . 
            "WHERE id = :id"
        , $params);
    }

    // =======================================================
    public function set_code_for_recover_password($username)
    {
        // sets a code to recover the password, if the account exists
        $params = [
            ':username' => $username
        ];
        $this->db_connect();
        $results = $this->query(
            "SELECT id FROM agents " . 
            "WHERE AES_ENCRYPT(:username, '" . MYSQL_AES_KEY . "') = name " . 
            "AND passwrd IS NOT NULL " .
            "AND deleted_at IS NULL"
        , $params);

        // check if no agent was found
        if($results->affected_rows == 0){
            return [
                'status' => 'error'
            ];
        }

        // the agent was found

        // generate code
        $code = rand(100000, 999999);
        $id = $results->results[0]->id;
        $params = [
            ':id' => $id,
            ':code' => $code
        ];

        $results = $this->non_query(
            "UPDATE agents SET " . 
            "code = :code " . 
            "WHERE id = :id"
        , $params);

        return [
            'status' => 'success',
            'id' => $id,
            'code' => $code
        ];
    }

    // =======================================================
    public function check_if_reset_code_is_correct($id, $code)
    {
        // check if the reset code is equal to the code stored in the agent row
        $params = [
            ':id' => $id,
            ':code' => $code
        ];
        $this->db_connect();
        $results = $this->query(
            "SELECT id FROM agents " . 
            "WHERE id = :id AND code = :code"
        , $params);
        
        if($results->affected_rows == 0){
            return [
                'status' => false
            ];
        } else {
            return [
                'status' => true
            ];
        }
    }

    // =======================================================
    public function change_agent_password($id, $new_passwrd)
    {
        // updates the current user password
        $params = [
            ':id' => $id,
            ':passwrd' => password_hash($new_passwrd, PASSWORD_DEFAULT)
        ];

        $this->db_connect();
        $this->non_query(
            "UPDATE agents SET " . 
            "passwrd = :passwrd, " . 
            "updated_at = NOW() " . 
            "WHERE id = :id"
        , $params);
    }
}