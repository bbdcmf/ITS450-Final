<?php
require('header.php');
// If the admin is checking the orders page
if(isset($_SESSION['username'], $_SESSION['isLoggedInToLemonShop']) and $_SESSION['username'] == 'vendor' and $_SESSION['isLoggedInToLemonShop'] == true){
	require('html/ordersAdmin.html');
	// Get the user's userID from the database (used for SQL below)
	$stmt = $db->prepare("SELECT id FROM users WHERE username=?");
	$stmt->bind_param("s", $_SESSION['username']);
	$stmt->execute();
	$result = $stmt->get_result();
	$user = $result->fetch_assoc();
	$userID = $user['id'];
	// Add the CSRF token by hashing the data 'orders.php' using SHA256 with the session token as the secret key
	echo('			<input type="hidden" name="csrf-token" value="' . hash_hmac("sha256", "orders.php", $_SESSION["token"]) . '" />
				<input type="submit" name="makeOrderSubmit" value="Submit" style="margin-left: 40%;" />
			</form>
		</div>');
	// If the admin has pressed the 'Submit' button
	if(isset($_POST['makeOrderSubmit'], $_FILES['image']) and !empty($_POST['csrf-token'])){
		$errorstr = 'Error: ';
		// Hash the data 'orders.php' using SHA256 with the session token as the secret key
		$token = hash_hmac('sha256', 'orders.php', $_SESSION['token']);
		// If the CSRF token on our end does not equal the CSRF token they provided
		if (!hash_equals($token, $_POST['csrf-token'])) {
			$errorstr = $errorstr . 'Cross-site request forgery detected, ';
		}
		
		// Check if any of the fields entered are blank
		foreach($_POST as $name => $value){
			if($name != 'makeOrderSubmit'){
				// Add an error if any are
				if($value == ''){
					$errorstr = $errorstr . $name . ' missing field, ';
				}
			}
		}
		
		$target_dir = "uploads/"; // The folder we will store the image in
		$target_file = $target_dir . basename($_FILES["image"]["name"]); // Getting the path of the file and its name
		$uploadOk = 0;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); // Get the path.extension
  		
  		// Check if a file with that name already exists
  		if(file_exists($target_file)){
  			$uploadOk = 0;
  			$errorstr = $errorstr . 'File name alrady exists.';
  		}
		
		 // Check file size
		if ($_FILES["image"]["size"] > 500000) {
  			$errorstr = $errorstr . 'File is too large.';
  			$uploadOk = 0;
 		}

		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
  			$errorstr = $errorstr . 'Only JPG, JPEG, & PNG files are allowed.';
  			$uploadOk = 0;
		}
		
		$check = getimagesize($_FILES["image"]["tmp_name"]);
		
  		if($check !== false) {
  			$uploadOk = 1;
  		}
  		else {
  			$uploadOk = 0;
  			$errorstr = $errorstr . 'File is not an image.';
  		}
		
		// If there are errors, print them
		if($errorstr != 'Error: '){
			$errorstr = rtrim($errorstr, ", "); // Format $errorstr
			echo("<div class='orderMessage'>$errorstr</div>");
			unset($_POST); // clear $_POST
		}
		else{
			if($uploadOk == 1){
				move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
				chmod($target_file, 0777);
				// Sanitize the user's input
				$name = sanitize_input($_POST['name']);
				$price = sanitize_input($_POST['price']);
				$description = sanitize_input($_POST['description']);
				$quantity = sanitize_input($_POST['quantity']);
				$imgPath = sanitize_input($target_file);
		
				// Enter the product into the shop table
				$stmt = $db->prepare("INSERT INTO shop (userID, item, price, description, quantity, imgPath) VALUES (?, ?, ?, ?, ?, ?)");
				$stmt->bind_param("ssdsis", $userID, $name, $price, $description, $quantity, $imgPath);
				$stmt->execute();
				echo("<div class='orderMessage'>Your product has been entered into our system!</div>");
			}
			unset($_POST); // clear $_POST
		}

	}
echo("</div>
		<div class='split right'>
			<h3 style='text-align: center'>View Past Orders:</h3>
			<table class='pastOrders'>");
		
			// Get all the data for each order placed
			$stmt = $db->query("SELECT * FROM orders");
			while($order = $stmt->fetch_assoc()){
				$stmt2 = $db->prepare("SELECT * FROM shop WHERE productID = ?");
				$stmt2->bind_param("i", $order['productID']);
				$stmt2->execute();
				$result2 = $stmt2->get_result();
				$row = $result2->fetch_assoc();
				
				// Get the name of the user who placed each order
				$stmt3 = $db->prepare("SELECT username FROM users WHERE id = ?");
				$stmt3->bind_param("i", $order['userID']);
				$stmt3->execute();
				$result3 = $stmt3->get_result();
				$buyer = $result3->fetch_assoc();
				echo("<tr><td class='pastOrderstd'><img src='" . $row['imgPath'] . "' /><div class='pastOrdersItem'>" . "Product: " .  $row['item'] . " | Price: $" . $row['price'] .  " | Quantity: " . $row['quantity'] . " | Description: ". $row['description'] . " | Buyer: " . $buyer['username'] . "</td></tr></div>");	
			}
			echo("</table>");
		
	echo("</div>
</body>
</html>");
}

// If a regular user is checking their orders
else{
	require('html/orders.html');
	$stmt = $db->prepare("SELECT * FROM orders WHERE userID = ?");
	$stmt->bind_param("i", $userID);
	$stmt->execute();
	$result = $stmt->get_result();
	while($order = $result->fetch_assoc()){
		$stmt2 = $db->prepare("SELECT * FROM shop WHERE productID = ?");
		$stmt2->bind_param("i", $order['productID']);
		$stmt2->execute();
		$result2 = $stmt2->get_result();
		$row = $result2->fetch_assoc();
		echo("<tr><td class='pastOrderstd'><img src='" . $row['imgPath'] . "' /><div class='pastOrdersItem'>" . "Product: " .  $row['item'] . " | Price: $" . $row['price'] .  " | Quantity: " . $row['quantity'] . " | Description: ". $row['description'] . "</div><a href='" . ENC_URL . "purchase.php?id=" . $row['productID'] . "' style='margin-left:40%;'/></td></tr>");	
	}
	echo("</table></div></body></html>");
}
?>

