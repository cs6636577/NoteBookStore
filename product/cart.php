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

<?php
    //แสดงสินค้า
    $total = 0;
    echo "<h1>ตะกร้าสินค้า</h1>";

    if (!empty($_SESSION["cart"])) {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>สินค้า</th><th>ราคา</th><th>จำนวน</th><th>ราคารวม</th><th>ลบ</th></tr>";

        foreach($_SESSION["cart"] as $id => $num_product) {
            $stmt = $pdo->prepare("SELECT * FROM Product WHERE id_product=?");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $row = $stmt->fetch();

            $sum = $row["price_product"] * $num_product;
            $total += $sum;

            echo "<tr>";
            echo "<td>".$row["name_product"]."</td>";
            echo "<td>".$row["price_product"]."</td>";
            echo "<td>".$num_product."</td>";
            echo "<td>".$sum."</td>";
            echo "<td><a href='cart.php?action=remove&id_product=".$id."'>ลบ</a></td>";
            echo "</tr>";
        }

        echo "<tr><td colspan='3' align='right'>รวมทั้งหมด</td><td colspan='2'>".$total." บาท</td></tr>";
        echo "</table>";

        echo "<br><a href='checkout.php'>สรุปและยืนยันคำสั่งซื้อ</a>";

    } else {
        echo "ยังไม่มีสินค้าในตะกร้า";
    }
?>