<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";


    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        $connection = new \PDO("mysql:host=localhost;dbname=zen_chat","root","");
        $value = $msg;
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        $query='INSERT into  chat(sender,msg) VALUES(:sender,:msg)';
        $statement = $connection->prepare($query);
        $username = "" ;
        parse_str($from->httpRequest->getUri()->getQuery(),$username);
        $statement->bindParam('sender' , $username["token"]);
        $statement->bindParam('msg' , $value);
        $statement->execute();

        //image
        $response = [];
        $response['sender'] = $username["token"];
        $response['msg'] = $msg;
        $response['created_at'] = date("H:i");
        $stmt = $connection->prepare("SELECT image FROM users WHERE username =:username limit 1");
        $stmt->bindParam('username', $username["token"]);
        //execute
        $stmt->execute();
        $fetchy = $stmt->fetchAll();
        $image = $fetchy[0];
        $response["image"] = $image[0];
        $message = json_encode($response);
        foreach ($this->clients as $client) {
            if ($from !== $client ) {
                // The sender is not the receiver, send to each client connected
                $client->send($message);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
        
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
