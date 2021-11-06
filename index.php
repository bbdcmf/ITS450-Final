<?php
require('header.php');
require('html/index.html');
if(isset($_POST['searchBar'])){
	echo($_POST['searchBar']);
}

$stmt = $db->query("SELECT * FROM shop");
while($row = $stmt->fetch_assoc()){
	echo("<tr><td class='shoptd'><img src='" . $row['imgPath'] . "' /><div class='shopItem'>" . "Product: " .  $row['item'] . " | Price: $" . $row['price'] .  " | Quantity: " . $row['quantity'] . " | Description: ". $row['description'] . "</div></td></tr>"); // TODO: Add an image that the user can click to add the item to their cart, and some way the user can view their cart and check out
}

echo("		</table>
		</div>
	</body>
</html>")
?>
