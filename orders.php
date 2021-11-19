<?php
// TODO: Take the info from the database and send it to the 'View Past Orders' div.

require('header.php');
require('html/orders.html');
// Add the CSRF token by hashing the data 'orders.php' using SHA256 with the session token as the secret key
echo('			<input type="hidden" name="csrf-token" value="' . hash_hmac("sha256", "orders.php", $_SESSION["token"]) . '" />
				<input type="submit" name="makeOrderSubmit" value="Submit" style="margin-left: 40%;" />
			</form>
		</div>');
		
$target_dir = "uploads/"; // The folder where the images will be stored in the webserver
$errorstr = 'Error: '; // Init. the error string in case there are errors

// Get the user's userID from the database (used for SQL below)
$stmt = $db->prepare("SELECT id FROM users WHERE username=?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$userID = $user['id'];

// If the user has pressed the 'Submit' button
if(isset($_POST['makeOrderSubmit']) and !empty($_POST['csrf-token'])){
	// Hash the data 'orders.php' using SHA256 with the session token as the secret key
	$token = hash_hmac('sha256', 'orders.php', $_SESSION['token']);
	// If the CSRF token on our end does not equal the CSRF token they provided
	if (!hash_equals($token, $_POST['csrf-token'])) {
		$errorstr = $errorstr . 'Cross-site request forgery detected, ';
	}
	// If no image was selected
	if($_FILES['image']['error']){
		$errorstr = $errorstr . 'Please select an image, ';
	}
	// If an image was selected
	else {
	
		// Make sure the image is an actual image
		$target_file = $target_dir . basename($_FILES["image"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  		$check = getimagesize($_FILES["image"]["tmp_name"]);
  		
  		// If the image seems good:
  		if($check !== false) {
    		// Upload the image to the uploads/ folder
  			if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
  			} 
  			// If theres an error uploading the file, send the error
  			else {
    			$errorstr = $errorstr . 'There was an error uploading your image, ';
  			}
  		}
  		// If the file is not a known image file
  		else {
    		$errorstr = $errorstr . 'File uploaded not an image, ';
  		}
  	}
  	
	// Loop through each of the name:value in POST
	foreach($_POST as $name => $value){
		if($name != 'makeOrderSubmit'){
			// Add an error if any of the values are empty. 
			if($value == ''){
				$errorstr = $errorstr . $name . ' missing field, ';
			}
		}
	}
	
	// If there are errors, print them
	if($errorstr != 'Error: '){
		$errorstr = rtrim($errorstr, ", "); // Format $errorstr
		echo("<div class='orderMessage'>$errorstr</div>");
		unset($_POST); // clear $_POST
	}
	else{
		// Sanitize the user's input
		$name = sanitize_input($_POST['name']);
		$price = sanitize_input($_POST['price']);
		$description = sanitize_input($_POST['description']);
		$quantity = sanitize_input($_POST['quantity']);
		$imgPath = sanitize_input('uploads/' . basename($_FILES['image']['name']));
	
		// Enter the product into the shop table
		$stmt = $db->prepare("INSERT INTO shop (userID, item, price, description, quantity, imgPath) VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("ssdsis", $userID, $name, $price, $description, $quantity, $imgPath);
		$stmt->execute();
		unset($_POST); // clear $_POST
		echo("<div class='orderMessage'>Your product has been entered into our system!</div>");
	
	}
}

echo("</div>
	<div class='split right'>
		<h3 style='text-align: center'>View Past Orders:</h3>");
		
		// Get all of the orders that the user bought or sold
		$stmt = $db->prepare("SELECT * FROM orders WHERE userID = ? OR vendorID = ?");
		$stmt->bind_param("ii", $userID, $userID);
		$stmt->execute();
		$result = $stmt->get_result();
		while($order = $result->fetch_assoc()){
			echo('orderID = ' . $order['orderID'] . ' productID = ' . $order['productID'] . ' userID = ' . $order['userID'] . ' vendorID = ' . $order['vendorID']);
		}
		
// TODO: Show orders that the user has purchased in the past and if there are none, send a message saying so
echo("</div>
</body>
</html>");
?>
