<?php
session_start();
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
<head><meta charset="utf-8"></head>
<?php
$stmt = $pdo->prepare("SELECT * FROM Product WHERE id_product = ?");
$stmt->bindParam(1, $_GET["id_product"]); 
$stmt->execute(); 
$row = $stmt->fetch(); 
?>
<div style="display:flex">
<div>
<img src='product_photo/<?=$row["id_product"]?>.jpg?<?=time()?>' width='200'>
</div>
    <div style="padding: 15px">
        <h2><?=$row["name_product"]?></h2>
        รายละเอียดสินค้า  : <?=$row["detail_product"]?><br>
        ราคาขาย <?=$row["price_product"]?> บาท<br><br>
        <form action="cart.php" method="get">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="id_product" value="<?=$row["id_product"]?>">
            จำนวน: <input type="number" name="quantity" min="1" max="<?=$row["num_product"]?>" value=1>
            <button type="submit">หยิบใส่ตะกร้า</button>
            <!-- formaction="cart.php?id_product=<?=$row["id_product"]?>&action=add" -->
        </form>
    </div>
</div>

</body>
</html>