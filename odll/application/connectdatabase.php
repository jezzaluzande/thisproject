<?php
class Database
{
    // Connection holds MySQLi resource
    private $connection;

    // Query to perform
    private $query;

    // Result holds data retrieved from server
    private $result;

    // Create new connection to database
    public function connect()
    {
        //connection parameters
        $host = '';
        $user = '';
        $password = '';
        $database = '';

        //your implementation may require these...
        $port = NULL;
        $socket = NULL;    
    
        //create new mysqli connection
        $this->connection = new mysqli
        (
            $host , $user , $password , $database , $port , $socket
        );
        return TRUE;
    }
	
	public function prepare($query)
    {
        //store query in query variable
        $this->query = $query;    
    
        return TRUE;
    }

    // Execute a prepared query
    public function query()
    {
        if (isset($this->query))
        {
            //execute prepared query and store in result variable
            $this->result = $this->connection->query($this->query);
    
            return TRUE;
        }
        return FALSE;        
    }

    /* Fetch a row from the query result
     * @param $type */
    public function fetch($type = 'object')
    {
        if (isset($this->result))
        {
            switch ($type)
            {
                case 'array':
                    //fetch a row as array
                    //$row = $this->result->fetch_array();
					while($row = $this->result->fetch_array()){
						$items[] = $row;
					}
                break;
            
                case 'object':
                //fall through...
                default:
                    //fetch a row as object
                    $row = $this->result->fetch_object();    

					$items = $row;
                break;
            }
            //return $row;
			return $items;
        }
        return FALSE;
    }
}
?>