<?php
require('header.php');
require('html/index.html');
// If the user has searched for an item, display the items that match the user's search:
if(isset($_POST['searchBar'])){
	$search = $_POST['searchBar'];
	$search = '%' . $search . '%';
	$stmt = $db->prepare("SELECT * FROM shop WHERE item LIKE ?");
	$stmt->bind_param("s", $search);
	$stmt->execute();
	$result = $stmt->get_result();
	while($row = $result->fetch_assoc()){
		echo("<tr><td class='shoptd'><img src='" . $row['imgPath'] . "' /><div class='shopItem'>" . "Product: " .  $row['item'] . " | Price: $" . $row['price'] .  " | Quantity: " . $row['quantity'] . " | Description: ". $row['description'] . "</div><a href='" . ENC_URL . "purchase.php?id=" . $row['productID'] . "' style='margin-left:40%;'/>Purchase</td></tr>");
	}
	
}
// If the user has not searched anything, show them everything:
else{
	$stmt = $db->query("SELECT * FROM shop");
	while($row = $stmt->fetch_assoc()){
		echo("<tr><td class='shoptd'><img src='" . $row['imgPath'] . "' /><div class='shopItem'>" . "Product: " .  $row['item'] . " | Price: $" . $row['price'] .  " | Quantity: " . $row['quantity'] . " | Description: ". $row['description'] . "</div><a href='" . ENC_URL . "purchase.php?id=" . $row['productID'] . "' style='margin-left:40%;'/>Purchase</td></tr>");
	}
}
$stmt->close();
// Close the html:
echo("		</table>
		</div>
	</body>
</html>")
?>
