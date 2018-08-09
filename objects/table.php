<?php
class Table {
    private $conn;

    public function __construct( $db ) {
        $this->conn = $db;
    }

    function read_table( $id = 0, $table, $push = array(), $table_left, $table_left_param ) {
        if( $table_left AND $table_left_param ) {
            if( $id ) $where = " WHERE {$table}.ID = :id";
            $query = "SELECT {$table}.*, $table_left_param
                        FROM {$table}
                        LEFT JOIN {$table_left} ON {$table}.id = {$table_left}.id{$where}";
        } else {
            if( $id ) $where = " WHERE ID = :id";
            $query = "SELECT * FROM " . $table . $where;
        }

        $stmt = $this->conn->prepare($query);

        if( $id ) {
            $id = intval($id);
            $stmt->bindParam(":id", $id);
        }

        $stmt->execute();

        while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
            $product_item = array();

            extract($row);

            foreach ( $row as $name => $value ) {
                if( ($table_left AND $table_left_param) AND strpos($name, $table_left) !== false ) {
                    $name = str_replace($table_left, "", $name);
                    $item = array( $name => $value );
                    if( !is_array($product_item[$table_left]) ) $product_item[$table_left] = array();
                    $product_item[$table_left] = array_merge($product_item[$table_left], $item);
                } else {
                    $item = array( $name => $value );
                    $product_item = array_merge($product_item, $item);
                }
            }

            array_push($push, $product_item);
        }

        return $push;
    }

    function select_Session( $userEmail, $sessionId ) {
        $query = "SELECT a.id, a.max_users, b.id as user_id, b.email, c.session_id, COALESCE(count_session, 0) AS count_session
                    FROM Session a
                    left join Users b on b.email = :userEmail
                    left join Session_record c on c.user_id = b.id AND c.session_id = :sessionId
                    left join
                         (
                          SELECT id, session_id, COUNT(*) AS count_session
                          FROM Session_record
                         ) cou ON cou.session_id = :sessionId
                    WHERE a.id = :sessionId";

        $stmt = $this->conn->prepare($query);

        $sessionId = intval($sessionId);
        $userEmail = htmlspecialchars(strip_tags(trim($userEmail)));

        $stmt->bindParam(':sessionId', $sessionId);
        $stmt->bindParam(':userEmail', $userEmail);

        $stmt->execute();

        $stmt = $stmt->fetch(PDO::FETCH_ASSOC);

        return $stmt;
    }

    function create_session_record( $user_id, $sessionId ) {
        $query = "INSERT INTO Session_record SET user_id = :user_id, session_id = :sessionId";

        $stmt = $this->conn->prepare($query);

        $user_id   = intval($user_id);
        $sessionId = intval($sessionId);

        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":sessionId", $sessionId);

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function select_user( $userEmail ) {
        $query = "SELECT
                id
                    FROM
                Participant
                   WHERE
                Email = :userEmail LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $userEmail = htmlspecialchars(strip_tags(trim($userEmail)));

        $stmt->bindParam(":userEmail", $userEmail);

        $stmt->execute();

        $stmt = $stmt->fetch(PDO::FETCH_ASSOC);

        return $stmt;
    }

    function duplicate_check( $user_id, $newsTitle, $newsMessage ) {
        $query = "SELECT ID FROM News WHERE ParticipantId = :user_id AND newsTitle = :newsTitle AND newsMessage = :newsMessage LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $user_id     = intval($user_id);
        $newsTitle   = htmlspecialchars(strip_tags(trim($newsTitle)));
        $newsMessage = htmlspecialchars(trim($newsMessage));

        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":newsTitle", $newsTitle);
        $stmt->bindParam(":newsMessage", $newsMessage);

        $stmt->execute();

        $stmt = $stmt->fetch(PDO::FETCH_ASSOC);

        return $stmt;
    }

    function create_news( $user_id, $newsTitle, $newsMessage ) {
        $query = "INSERT INTO News SET ParticipantId = :user_id, newsTitle = :newsTitle, newsMessage = :newsMessage";

        $stmt = $this->conn->prepare($query);

        $user_id     = intval($user_id);
        $newsTitle   = htmlspecialchars(strip_tags(trim($newsTitle)));
        $newsMessage = htmlspecialchars(trim($newsMessage));

        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":newsTitle", $newsTitle);
        $stmt->bindParam(":newsMessage", $newsMessage);

        if($stmt->execute()){
            return true;
        }
        return false;
    }
}
?>