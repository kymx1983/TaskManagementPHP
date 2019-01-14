<?php
require_once('db.php');

class DBTask {

  // 検索機能
  // 検索モード selectMode
  // 0:条件なし
  // 1:TaskCD
  public Function searchTask($selectMode,$array){

    $db = new DB();
    $sql = "SELECT";
    $sql .= "	TaskCD,";
    $sql .= "	ProjectCD,";
    $sql .= "	Task,";
    $sql .= "	TaskDetails,";
    $sql .= "	s.StatusCD as StatusCD,";
    $sql .= "	s.Status as Status,";
    $sql .= "	PlanFrom,";
    $sql .= "	PlanTo,";
    $sql .= "	Due,";
    $sql .= "	u.UserCD,UserName ";
    $sql .= "FROM ";
    $sql .= "	task t ";
    $sql .= "INNER JOIN usermst u ";
    $sql .= "	ON t.User=u.UserCD ";
    $sql .= "INNER JOIN statusmst s ";
    $sql .= "	ON t.Status = s.StatusCD";

    $arrayWhere = null;
    switch($selectMode){
      case 0:
        // echo "デバッグ".$array['searchDate']."<br>";
        $searchDate = $array['searchDate'];
        $sql .= " WHERE (PlanFrom >= '".$searchDate."' and PlanTo >= '".$searchDate."') ";
        $sql .= " OR (PlanFrom >= '".$searchDate."' and t.Status in (1)) ";
        $sql .= " OR (PlanTo <= '".$searchDate."' and t.Status in(1,2,3))";
        $sql .= " OR (Due <= '".$searchDate."' and t.Status in (1,2,3))";

        // PlanFrom 2018-12-10
        // PlanTo 2018-12-13
        // Roday 2018-12-14
        break;
      case 1:
        $TaskCD = $array['TaskCD'];
        $arrayWhere[0] = $TaskCD;
        $sql .= " WHERE TaskCD=?";
    }

    // ソート
    $sql .= " order by Due,PlanFrom,PlanTo,TaskCD";

    // echo "デバッグ:".$sql;

    $res = $db->executeSQL($sql,$arrayWhere);
    $arrayTasks = null;
    while($row = $res->fetch(PDO::FETCH_ASSOC)){
      try{
        $arrayTasks[] = array(
          "TaskCD" => $row['TaskCD'],
          "ProjectCD" => $row['ProjectCD'],
          "Task" => $row['Task'],
          "TaskDetails" => $row['TaskDetails'],
          "UserName" => $row['UserName'],
          "StatusCD" => $row['StatusCD'],
          "Status" => $row['Status'],
          "PlanFrom" => $row['PlanFrom'],
          "PlanTo" => $row['PlanTo'],
          "Due" => $row['Due']
          );
        } catch (Exception $ex) {
          echo $ex;
        }
    }
    return $arrayTasks;
  }

  // TascCDの最大値を取得
  public Function getTascCDMax(){
    $db = new DB();

    // TascCDの最大値を取得
    $sql = "SELECT MAX(TaskCD) AS TascCDMax FROM task";
    $res = $db->executeSQL($sql,null);
    $row = $res->fetch(PDO::FETCH_ASSOC);
    return $row['TascCDMax'];
  }

  // タスクの登録
  public Function insertTask($array){
    $db = new DB();

    $selectArray = array(
      $array['TascCD'],
      $array['ProjectCD'],
      $array['Task'],
      $array['TaskDetails'],
      $array['User'],
      $array['PlanFrom'],
      $array['PlanTo'],
      $array['Status'],
      $array['Due']
    );

    $sql = "INSERT INTO task(`TaskCD`, `ProjectCD`, `Task`, `TaskDetails`, `User`, `PlanFrom`, `PlanTo`, `Status`, `Due`) VALUES (?,?,?,?,?,?,?,?,?)";
    $res = $db->executeSQL($sql,$selectArray);

    echo "登録しました";
  }

  // タスクの更新
  public Function updateTask($array){
    $db = new DB();

    $selectArray = array(
      $array['TascCD'],
      $array['ProjectCD'],
      $array['Task'],
      $array['TaskDetails'],
      $array['User'],
      $array['PlanFrom'],
      $array['PlanTo'],
      $array['Status'],
      $array['Due'],
      $array['TascCD']
    );

    $sql = "update task set ";
    $sql .= "TaskCD = ?, ";
    $sql .= "ProjectCD = ?, ";
    $sql .= "Task = ?, ";
    $sql .= "TaskDetails = ?,";
    $sql .= "User = ?, ";
    $sql .= "PlanFrom = ?,";
    $sql .= "PlanTo = ?, ";
    $sql .= "Status = ?, ";
    $sql .= "Due = ? ";
    $sql .= "where " ;
    $sql .= "TaskCD = ?";
    $res = $db->executeSQL($sql,$selectArray);

    echo $sql."<br>";
    echo "更新しました";

  }

  // タスクの削除
  public Function deleteTask($array){
    $db = new DB();

    $selectArray = array(
      $array['TascCD']
    );

    $sql = "delete from task where TaskCD = ?";
    $res = $db->executeSQL($sql,$selectArray);

    echo $sql."<br>";
    echo "削除しました";

  }

  // 検索機能
  public Function searchStatus() {
    $db = new DB();
    $sql = "SELECT * FROM statusmst";
    $res = $db->executeSQL($sql,null);

    $arrayStatus = null;
    while($row = $res->fetch(PDO::FETCH_ASSOC)){
      $arrayStatus[] = array(
        "StatusCD" => $row['StatusCD'],
        "Status" => $row['Status']
      );
    }
        return $arrayStatus;
  }
}
?>
