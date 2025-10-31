<?php include "connect.php" ?>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<form>
    <input type="text" name="kw">
    <input type="submit" value="ค้นหา">
</form>

    <?php
    $stmt = $pdo->prepare("SELECT * FROM member WHERE name LIKE ?");
    if(!empty($_GET))
        $value = '%'.$_GET["kw"].'%';
    $stmt->bindParam(1,$value);
    $stmt->execute();
    ?>
<?php while($row = $stmt->fetch()): ?>
        ชื่อสมาชิก: <?=$row["name"]?><br>
        ที่อยู่:  <?=$row["address"]?><br>
        อีเมล์: <?=$row["email"]?><br>
        <img src='member_photo/<?=$row["username"]?>'.jpg width='100'>  
        <hr>   
<?php endwhile; ?>
</body>
</html>