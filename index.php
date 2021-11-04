<?php
// TODO: Finish all the login/design for this file
require('header.php');
require('html/index.html');
if(isset($_POST['searchBar'])){
	echo($_POST['searchBar']);
}

$stmt = $db->query("SELECT * FROM shop");
while($row = $stmt->fetch_assoc()){
	echo("<tr><td class='shoptd'><img src='" . $row['imgPath'] . "' /><div class='shopItem'>" . "Product: " .  $row['item'] . " | Price: $" . $row['price'] .  " | Quantity: " . $row['quantity'] . " | Description: ". $row['description'] . "</div></td></tr>");
}

echo("		</table>
		</div>
	</body>
</html>")
?>
