<?php
// TODO: Add a purchase.php where the user can purchase a product
//       When the user clicks 'Purcahse', it should take them to purchase.php as well as send purchase.php with the data
//       about the product the user would like to purchase
require('header.php');
require('html/index.html');
if(isset($_POST['searchBar'])){
	$search = $_POST['searchBar'];
	$search = '%' . $search . '%';
	$stmt = $db->prepare("SELECT * FROM shop WHERE item LIKE ?");
	$stmt->bind_param("s", $search);
	$stmt->execute();
	$result = $stmt->get_result();
	while($row = $result->fetch_assoc()){
		echo("<tr><td class='shoptd'><img src='" . $row['imgPath'] . "' /><div class='shopItem'>" . "Product: " .  $row['item'] . " | Price: $" . $row['price'] .  " | Quantity: " . $row['quantity'] . " | Description: ". $row['description'] . "</div><a href='" . BASE_URL . "purchase.php?id=" . $row['productID'] . "' style='margin-left:40%;'/>Purchase</td></tr>");
	}
	
}
else{
	$stmt = $db->query("SELECT * FROM shop");
	while($row = $stmt->fetch_assoc()){
		echo("<tr><td class='shoptd'><img src='" . $row['imgPath'] . "' /><div class='shopItem'>" . "Product: " .  $row['item'] . " | Price: $" . $row['price'] .  " | Quantity: " . $row['quantity'] . " | Description: ". $row['description'] . "</div><a href='" . BASE_URL . "purchase.php?id=" . $row['productID'] . "' style='margin-left:40%;'/>Purchase</td></tr>");
	}
}
$stmt->close();
echo("		</table>
		</div>
	</body>
</html>")
?>
