<?
echo "11";

  try {
        //$mongo = new MongoDB\Client('mongodb://intovet_user:into7898ab!@172.27.0.119/intovet');
  		//$mongo = new MongoDB\Client('mongodb://intovet_user:into7898ab!@172.27.0.119:27017');
  		$manager = new MongoDB\Driver\Manager("mongodb://172.27.0.119:27017");
        print_r($mongo->listDatabases());
  } catch (Exception $e) {
        echo $e->getMessage();
  }
?>