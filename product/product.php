<?php
try {
$pdo = new PDO("mysql:host=localhost;dbname=168DB_49;charset=utf8","168DB49","gIHgCeBX");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "connected";

} catch (PDOException$e){
    echo "error".$e->getMessage();
}

?>

<?php 
    session_start();

    //สร้างตะกร้าว่าง
    if(isset($_SESSION["cart"])){
        $_SESSION["cart"] = array();
    }

    //ถ้ามีaction=addจากdetail.php
    if(isset($_GET["action"]) && $_GET["action"] == "add"){
        $id_product = $_GET["id_product"];
        $num_product = $_GET["num_product"];

        //ถ้ามีสินค้าในตะกร้าแล้วให้บวกเพิ่ม
        if(isset($_SESSION["cart"][$id_product])){
            $_SESSION["cart"][$id_product] += $num_product; //เพิ่มจำนวน
        } else {
            $_SESSION["cart"][$id_product] = $num_product; //เพิ่มสินค้าใหม่
        }
        
        //ถ้ากดลบสินค้าออก
        if (isset($_GET["action"]) && $_GET["action"] == "remove"){
            $id_product = $_GET["id_product"];
            unset($_SESSION["cart"][$id_product]);
        }
    }
?>

<html>
<head>
    <meta charset="utf-8">
    <script>
        function alertOut(){
            alert("สินค้าหมด");
        }
    </script>
</head>

<body>
    <h1>Product</h1>
    <div style="display:flex">
        <?php
          $stmt = $pdo->prepare("SELECT * FROM Product");
          $stmt->execute();
        ?>
        <?php while($row = $stmt->fetch()) : ?>
            <?php if ($row["num_product"] > 0): ?>
                <a href="details.php?id_product=<?=$row["id_product"]?>" style="display:flex; width: 200px; height: 200px; background-color: blue;">
                    <img src="product_photo/<?=$row["id_product"]?>.jpg?<?=time()?>" style="width: 200px; height: 200px;">
                </a>
            <br>
                <div style="padding:15px; text-align:center">
                    <?=$row["name_product"]?><br>
                    <?=$row["price_product"]?> บาท
                </div>
            <?php else: ?>
                <a onclick="alertOut()" style="display:flex; width: 200px; height: 200px; background-color: gray;">
                    <img src="product_photo/<?=$row["id_product"]?>.jpg?<?=time()?>" style="width: 200px; height: 200px;">
                </a>
            <br>
                <div style="padding:15px; text-align:center">
                    <?=$row["name_product"]?><br>
                    <?=$row["price_product"]?> บาท
                </div>
            <?php endif; ?>
        <?php endwhile; ?>


</body>
</html>