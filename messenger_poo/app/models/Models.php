<?php

namespace messenger\Models;


class Models extends BaseModel
{
    //============================================================================================================
    public function register_user($username, $email, $password, $token, $secure)
    {
        $params = [
            ':username' => $username,
            ':email' => $email,
            ':password' => $password,
            ':token' => $token,
            ':secure' => $secure
        ];
        $this->db_connection();
        $this->non_query(
            " INSERT INTO users(`username`, `email`, `password`, `status`, `token`, `secure`, `created_at`) 
            VALUES(
                :username, :email, :password, NOW(), :token, :secure, NOW()
            )  
        ",
            $params
        );
    }

    //============================================================================================================
    public function check_exists_username($username)
    {
        $params = [
            ':username' => $username
        ];
        $this->db_connection();
        $results = $this->query(
            "SELECT id FROM users WHERE :username = username",
            $params
        );

        if ($results->affected_rows != 0) {
            return false;
        } else {
            return true;
        }
    }

    //============================================================================================================
    public function check_exists_email($email)
    {
        $params = [
            ':email' => $email
        ];
        $this->db_connection();
        $results = $this->query(
            "SELECT id FROM users WHERE :email = email",
            $params
        );

        if ($results->affected_rows != 0) {
            return false;
        } else {
            return true;
        }
    }

    //============================================================================================================
    public function data_user($username)
    {
        $params = [
            ':username' => $username
        ];
        $this->db_connection();
        $results = $this->query(
            "SELECT id, username, email, picture, status, token, secure, created_at 
                FROM users
                WHERE :username = username
                OR :username = email",
            $params
        );

        if ($results->affected_rows == 0) {
            return [
                'status' => false
            ];
        } else {
            return [
                'status' => true,
                'data' => $results->results[0]
            ];
        }
    }

    //============================================================================================================
    public function check_login($email, $password)
    {
        $params = [
            ':email' => $email,
            ':password' => $password
        ];
        $this->db_connection();
        $results = $this->query(
            "SELECT id, password, token, secure
            FROM users
            WHERE :email = email 
            OR :email = username",
            $params
        );

        if ($results->affected_rows == 0) {
            return false;
        }


        if (password_verify($password, $results->results[0]->password)) {
            return true;
        }
    }

    //============================================================================================================
    public function search($search, $id)
    {
        $params = [':id' => $id];

        $this->db_connection();
        $results = $this->query(
            "SELECT id, username, picture
                FROM users
                WHERE username LIKE '%$search%'
                AND :id <> id
                ORDER BY username DESC",
            $params
        );

        return $results;
    }

    //============================================================================================================
    public function select_conversation($uid)
    {
        $params = [':id' => $uid];
        $this->db_connection();
        $results = $this->query(
            "SELECT * FROM conversations WHERE main_user = :id ORDER BY modification DESC",
            $params
        );

        return $results;
    }

    //============================================================================================================
    public function update_profile_picture($picture, $id)
    {
        $params = [
            ':id' => $id,
            ':picture' => $picture
        ];
        $this->db_connection();
        $this->non_query(
            "UPDATE users SET
                picture = :picture
                WHERE :id = id",
            $params
        );
    }

    //============================================================================================================
    public function data_friend($friend_id)
    {
        $params = [
            ':friend_id' => $friend_id
        ];
        $this->db_connection();
        $results = $this->query(
            "SELECT id, username, picture, status, created_at
                FROM users
                WHERE (id LIKE :friend_id)
                LIMIT 1",
            $params
        );

        if ($results->affected_rows == 0) {
            return false;
        }

        return $results;
    }

    //============================================================================================================
    public function check_conversation($uid, $friend_id)
    {
        $params = [
            ':id' => $uid,
            ':friend_id' => $friend_id
        ];
        $this->db_connection();
        $results = $this->query(
            "SELECT id FROM conversations WHERE main_user = :id AND friend_user = :friend_id",
            $params
        );
        return $results;
    }

    //============================================================================================================
    public function create_conversation($uid, $friend_id, $unread)
    {
        $params = [
            ':id' => $uid,
            ':friend_id' => $friend_id,
            ':unread' => $unread
        ];
        $this->db_connection();
        $this->non_query(
            "INSERT INTO conversations(id, main_user, friend_user, unread, created_at)
                VALUES(
                    0,
                    :id,
                    :friend_id,
                    :unread,
                    NOW()                   
                )",
            $params
        );
    }

    //============================================================================================================
    public function create_conversation_friend($uid, $friend_id, $unread)
    {
        $params = [
            ':friend_id' => $friend_id,
            ':id' => $uid,
            ':unread' => $unread
        ];
        $this->db_connection();
        $this->non_query(
            "INSERT INTO conversations(id, main_user, friend_user, unread, created_at)
                VALUES(
                    0,
                    :friend_id,
                    :id,
                    :unread,
                    NOW()
                )",
            $params
        );
    }

    //============================================================================================================
    public function update_conversation($uid, $friend_id, $unread)
    {
        $params = [
            ':id' => $uid,
            ':friend_id' => $friend_id,
            ':unread' => $unread
        ];
        $this->db_connection();
        $this->non_query(
            "UPDATE conversations SET 
                unread = :unread
                WHERE main_user = :id
                AND friend_user = :friend_id",
            $params
        );
    }

    //============================================================================================================
    public function create_chat($uid, $friend_id, $message, $image)
    {
        $params = [
            ':id' => $uid,
            ':friend_id' => $friend_id,
            ':message' => $message,
            ':image' => $image
        ];
        $this->db_connection();
        $this->non_query(
            "INSERT INTO chat VALUES(
                0,
                :id,
                :friend_id,
                :message,
                :image,
                NOW()
                )",
            $params
        );
    }

    //============================================================================================================
    public function select_chat($friend_id, $uid)
    {
        $params = [
            ':friend_id' => $friend_id,
            ':id' => $uid
        ];
        $this->db_connection();
        $results = $this->query(
            "SELECT `sender`, `message`, `image` FROM chat WHERE (sender = :id AND reciever = :friend_id) OR (reciever = :id AND sender = :friend_id) ORDER BY id",
            $params
        );

        if ($results->affected_rows == 0) {
            return [
                'status' => false
            ];
        } else {
            return [
                'status' => true,
                'chat' => $results->results
            ];
        }
    }
}
