<?
  try {
      //$mongo = new Mongo("mongodb://localhost:27017");
      //$testDB = $mongo->localmongo;
      
      $mongo = new MongoClient("mongodb://app.intopet.co.kr:27017" );
      $db = $mongo->intopet1;
      
      $collections = $db->listCollections();

      foreach ($collections as $collection) {
          echo "amount of documents in $collection: ";
          echo $collection->count(), "\n";
      }
//      exit;

      $docs = $db->topics->find();
      foreach ($docs as $document) {
        //var_dump($document);
        //echo $document["topic_type"]."  | ";
        echo $document["sns_id"]."\n";
      }
/*
      $docs = $db->content->find();
      foreach ($docs as $document) {
        var_dump($document);
      }
*/
      echo "5";
      exit;
  //      foreach ($testDB->listCollections() as $collectionInfo) {
//        //var_dump($collectionInfo);
//        var_dump($collectionInfo);
//        echo $collectionInfo->getCollectionName();
//      }
      
      echo "3";
      exit;
      
      //db.getCollection('sns').find({"publish_datetime":{"$gt": ISODate("2020-01-01T00:00:00.000Z"), "$lt": ISODate("2020-08-01T00:00:00.000Z")}}).count()
      //9464 건
      $i = 0;
      //$startnum = 131001; //500씩
      // 0~800 X / 120001 ~ 121136
      $startnum = $_GET[startnum];
    //   echo "[".$startnum."]";
      //$snss = $testDB->sns->find();
      $query = array("_id"=>array('$gt'=>new MongoId('5f2bcfc2a21841652753519b'))); // 2020-08-07
      // $query = array("_id"=>array('$gt'=>new MongoId('5f2a1767c660eb2613a1f1e8'))); //5f06752372c29d4d3becbf5c, 5f0d541040002f647e2375e5
      //$query = array("_id"=>array('$lt'=>new MongoId('5e255e45fee977cb084d0a0b')));  //작업중이던 부분
//      $query = array("_id"=>new MongoId('5f06752372c29d4d3becbf5c')); //임시로 한번 받아 봄
      //$query = array("publish_datetime"=>array('$gt'=>new MongoDate(strtotime("2020-01-01T00:00:00.000Z"))), "publish_datetime"=>array('$lt'=>new MongoDate(strtotime("2020-08-01T00:00:00.000Z"))));
      //$query = array("publish_datetime"=>array('$gt'=>new MongoDate(strtotime("2016-01-01T00:00:00.000Z"))), "publish_datetime"=>array('$lt'=>new MongoDate(strtotime("2017-01-01T00:00:00.000Z"))));
        $snss = $testDB->sns->find($query)->skip(($startnum -1))->limit(1000);

        $json_arr = array();
        foreach ($snss as $document) {
            array_push($json_arr, $document);
        }

        print_r(json_encode($json_arr));

        exit;
    } catch (Exception $e) {
        echo "eee";
          echo $e->getMessage();
    }
?>