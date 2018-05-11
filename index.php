<?php
 require_once "config.php";
 $dirs = array("ASC", "DESC");
 $page = isset($_GET['page'])? (int)$_GET['page'] : 0;
 $page = isset($_GET['jump']) && $_GET['jump'] != ''? (int)$_GET['jump'] : $page;
 $order = isset($_GET['order'])? (int)$_GET['order'] : 2;
 $dir = isset($_GET['dir'])? (int)$_GET['dir'] : 0;
 $uri = "page=$page";
 $current_tab = isset($_GET['tab'])? $_GET['tab'] : "people";
 $tabs = array("people","contacts","departments");
 $idx = isset($_GET['idx'])? $_GET['idx'] : 0;
?>
<!DOCTYPE html>
<html>
<head>
 <title> USER DATABASE</title>
 <style>
 <?php echo '.'.$current_tab."{background-color:lightblue;}";?>
 table {min-width: 800px; border: 5px solid black;}
 th { background-color: lightblue; }
 tr:nth-child(even) td { background-color: lightgreen; }
 td:first-child, tr:nth-child(even) td:first-child { background-color: lightgray; }
 .top_tab{width:800px;min-width:800px;}
 .people,.contacts,.departments{
   text-decoration: none;
   padding:5px 5px;text-align:center;width:17%;display: inline-block;border; border:1px solid black;}
 </style>

</head>
<body>

<div class="top_tab">
  <?php
  foreach($tabs as $tab){
    echo "<a href='?order=$idx&page=$page&dir=$d&tab=$tab' class='$tab'>$tab</a>";
  }
  ?>
</div>
<table>
 <thead>
  <tr>
    <?php
      $headers = getColumns($current_tab);

      $cols = $headers;
      foreach($headers as $heading) {
        $d = $idx == $order? !$dir : 0;
        $cr = $idx == $order? ($dir == 1? "&utrif;" : "&dtrif;") : "&dtrif;";
        echo "<th><a href='?order=$idx&page=$page&dir=$d'> $heading </a> $cr</th>";
        $idx++;
      }

    ?>
  </tr>
</thead>
<tbody>
<?php
$res = requestQuery("SELECT count(*) FROM $current_tab");
$row = $res->fetch_row();
$total = (int)$row[0];
$res->free();
$totpages = (int)(($total+29)/30)-1;
$page = max(0, min($page, $totpages));
$offset = $page * 30;
$idx = $offset;
$res = requestQuery("SELECT * FROM $current_tab ORDER BY {$cols[$order]}
   {$dirs[$dir]} LIMIT $offset,30");

while($row = $res->fetch_assoc()) {
  foreach($row as $k => $v) {
    echo "<td> $v </td>";
  }
  echo "</tr>";
  $idx++;
}
?>
</tbody>
<tfoot>
 <tr><td colspan=8>
  <form method="GET">
<?php
 if (isset($_GET['order'])) echo "<input type=hidden name='order' value='$order'>\n";
 if (isset($_GET['dir'])) echo "<input type=hidden name='dir' value='$dir'>\n";
 if ($page) echo "<button style='float:left;' name='page' value='". ($page-1)."'> Prev </button>\n";
 if ($page < $totpages) echo "<button style='float:right;' name='page' value='". ($page+1)."'> Next </button>\n";
 echo "<span style='float: right;'> Displaying page $page of $totpages
   <input type='number' name='jump' size=5></span>\n";
?>
  </form>
</tfoot>
</table>

</body>
</html>
