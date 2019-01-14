<?php
  require_once("dbTask.php");
  $age;
  // 変数定義
  $TaskCD = "";
  $ProjectCD = "";
  $Task = "";
  $TaskDetails = "";
  $User = "";
  $PlanFrom = "";
  $PlanTo = "";
  $Status = "";
  $Due = "";

  if(isset($_POST['details'])){

    // タスクデータの検索
    $dbTask = new DBTask();
    $array = array('TaskCD'=>$_POST['TaskCD']);
    $arrayTasks = $dbTask->searchTask(1,$array);
    $array2 = $arrayTasks[0];
    $TaskCD = $arrayTasks[0]['TaskCD'];
    $ProjectCD = $arrayTasks[0]["ProjectCD"];
    $Task = $arrayTasks[0]['Task'];
    $TaskDetails = $arrayTasks[0]['TaskDetails'];
    $User = $arrayTasks[0]['UserName'];
    $PlanFrom = $arrayTasks[0]['PlanFrom'];
    $PlanTo = $arrayTasks[0]['PlanTo'];
    $Status = $arrayTasks[0]['Status'];
    $Due = $arrayTasks[0]['Due'];

  } else {
    // 新規登録の場合は初期値を登録
    $PlanFrom = date("Y-m-d");
    $PlanTo = date("Y-m-d");
    $Due = date("Y-m-d");
  }

  if(isset($_POST['execute'])){

    $dbTask = new DBTask();

    $mode = $_POST['mode'];
    echo "モード：".$mode."<br>";

    switch($mode){
      case "insert":

        // echo "デバッグ：新規登録<br>";
        // TascCDの最大値を取得
        $TascCD = $dbTask->getTascCDMax() + 1;

        $array = array(
          "TascCD" => $TascCD,
          "ProjectCD" => $_POST['ProjectCD'],
          "Task" => $_POST['Task'],
          "TaskDetails" => $_POST['TaskDetails'],
          "User" => "koyama",
          "PlanFrom" => $_POST['PlanFrom'],
          "PlanTo" => $_POST['PlanTo'],
          "Status" => $_POST['status'],
          "Due" => $_POST['Due']
        );

        $dbTask->insertTask($array);
        break;
      case "update":

        $array = array(
          "TascCD" => $_POST['TaskCD'],
          "ProjectCD" => $_POST['ProjectCD'],
          "Task" => $_POST['Task'],
          "TaskDetails" => $_POST['TaskDetails'],
          "User" => "koyama",
          "PlanFrom" => $_POST['PlanFrom'],
          "PlanTo" => $_POST['PlanTo'],
          "Status" => $_POST['status'],
          "Due" => $_POST['Due']
        );

        $dbTask->updateTask($array);
        break;
      case "delete" :
        $array = array(
          "TascCD" => $_POST['TaskCD']
        );

        $dbTask->deleteTask($array);

        // echo "デバッグ:削除完了";

        break;

    }
    header( "Location: task.php" ) ;
  }

  // ステータスの検索
  $dbTask = new DBTask();
  $arrayStatus = $dbTask->searchStatus();
?>

<DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>タスク管理システム</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
  <h3>検索条件</h3>
  <div class="container">

    <form action="" method="post">
      <input type="hidden" name="TaskCD" value="<?php echo $TaskCD ?>">
      <?php if($TaskCD != "") {
        echo "#".$TaskCD."<br>";
      } else {
        echo "新規登録<br>";
      }
      ?>
      <table class="formTable">
        <tr>
          <th class="formTH">プロジェクト</th>
          <td class="formTD">
            <input type="text" width=1000px class="formText" name="ProjectCD" value="<?php echo $ProjectCD; ?>">
          </td>
        </tr>
        <tr>
          <th class="formTH">タスク</th>
          <td class="formTD">
            <input type="text" class="formText" name="Task" value="<?php echo $Task; ?>">
          </td>
        </tr>
        <tr>
          <th class="formTH">詳細</th>
          <td class="formTD"><textarea class="formTextArea" name="TaskDetails"><?php echo $TaskDetails; ?></textarea></td>
        </tr>
        <tr>
          <th class="formTH">開始日</th>
          <td class="formTD">
            <input type="date" class="formTextDate" name="PlanFrom" value="<?php echo $PlanFrom ?>">　～　
            <input type="date" class="formTextDate" name="PlanTo" value="<?php echo $PlanTo ?>">
          </td>
        </tr>
        <tr>
          <th class="formTH">ステータス</th>
          <td>
            <select name="status" class="formText">
              <?php foreach ($arrayStatus as $arrayValue) { ?>
                <option value='<?php echo $arrayValue["StatusCD"]?>'><?php echo $arrayValue["Status"] ?></option>
              <?php }?>
            </select >
          </td>
        </tr>
        <tr>
          <th class="formTH">期限</th>
          <td class="formTD"><input type="date" class="formText" name="Due" value="<?php echo $Due; ?>"></td>
        </tr>
      </table>
      <?php if(isset($_POST['details'])){ ?>
        <br>モード
        <input type="radio" name="mode" value="update" checked="checked">更新
        <input type="radio" name="mode" value="insert">コピーして新規登録
        <input type="radio" name="mode" value="delete">削除
      <?php } else {?>
        <input type="hidden" name="mode" value="insert">
      <?php } ?>

      <br><br>
      <input type="submit" name="execute" class="square_btn" value="実行">　
    </form>
  </div>
</body>
</html>
