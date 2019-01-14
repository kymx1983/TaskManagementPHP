<?php
require_once('dbTask.php');

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

$isSearch = false;
$taskCount = 0;

// 本日の日付を取得
$today = date("Y-m-d");

if(isset($_POST['search'])){
  $today = $_POST['searchDate'];
}

//echo "検索を開始します";
// タスクデータの検索
$dbTask = new DBTask();
// 検索条件を設定
// echo "デバッグ:".$_POST['searchDate']."<br>";
$array = array(
  "searchDate" => $today,
);
$arrayTasks = $dbTask->searchTask(0,$array);
$taskCount = count($arrayTasks);
$isSearch = true;

$TaskCD = $arrayTasks[0]['TaskCD'];
$ProjectCD = $arrayTasks[0]["ProjectCD"];
$Task = $arrayTasks[0]['Task'];
$TaskDetails = $arrayTasks[0]['TaskDetails'];
$User = $arrayTasks[0]['UserName'];
$Status = $arrayTasks[0]['Status'];
$Due = $arrayTasks[0]['Due'];
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
  <form action="" method="post">
    日付　<input type="date" name="searchDate" value="<?php echo $today; ?>">
    <input type="submit" name="search" value="検索" class="square_btn">
  </form>

  <hr>
  <!-- <form action="insertTask.php" target="_blank" method="post">
    <input type="submit" name="new" value="タスクを新規登録" class="square_btn" >
  </form> -->
  <?php if($isSearch) {
    if ($taskCount > 0){
  ?>
      <h3>タスク一覧</h3>
      <table><tr>
      <th>#</th>
      <th>プロジェクト</th>
      <th>タスク</th>
      <th>開始日</th>
      <th>終了日</th>
      <th>ステータス</th>
      <th>期限</th>
      <th></th>
      </tr>
      <?php foreach ($arrayTasks as $arrayTask) { ?>
        <form action="insertTask.php" method="post">

          <tr>
          <input type="hidden" name="TaskCD" value="<?php echo $arrayTask['TaskCD']; ?>">
          <td><?php echo $arrayTask['TaskCD']; ?></td>
          <td><?php echo $arrayTask['ProjectCD']; ?>
          <td>
            <?php echo $arrayTask['Task']; ?>
          </td>
          <td><?php echo $arrayTask['PlanFrom'] ?></td>
          <td><?php echo $arrayTask['PlanTo'] ?></td>
          <td><?php echo $arrayTask['Status'] ?></td>
          <td><?php echo $arrayTask['Due']; ?>
          <td>
            <input type="submit" name="details" value="詳細" class="square_btn" >
          </td>
          </tr>
        </form>
      <?php }?>
      </table>
    <?php } else {
      echo "対象データがありませんでした";
      }
    }
  ?>
  <br>
  <form action="insertTask.php" method="post">
    <input type="submit" name="new" value="タスクを新規登録" class="square_btn" >
  </form>
  <br>
</table>
</body>
</html>
