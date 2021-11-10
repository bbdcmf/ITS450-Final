<?php
// TODO: Take the info from the database and send it to the 'View Past Orders' div.

require('header.php');
require('html/orders.html');
echo('				</table><br />
				<input type="hidden" name="csrf-token" value="' . $token . '" />
				<input type="submit" name="makeOrderSubmit" value="Submit" style="margin-left: 40%;" />
			</form>
		</div>');
		
$target_dir = "uploads/";

$errorstr = 'Error: ';
if(isset($_POST['makeOrderSubmit'])){

	// If the CSRF token is not the same as the one given
	if($_POST['csrf-token'] != $_SESSION['token']){
		$errorstr = $errorstr . 'Cross site request forgery detected, ';
	}
	
	// If no image was selected
	if($_FILES['image']['error']){
		$errorstr = $errorstr . 'Please select an image, ';
	}
	
	else {
	
		// Make sure the image is an actual image
		$target_file = $target_dir . basename($_FILES["image"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  		$check = getimagesize($_FILES["image"]["tmp_name"]);
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
			if($value == ''){
				$errorstr = $errorstr . $name . ' missing field, '; // Send an error if any of the values are empty. 
			}
		}
	}
	
	// If there are errors, print them
	if($errorstr != 'Error: '){
		$errorstr = rtrim($errorstr, ", "); // Format $errorstr
		echo("<div class='orderMessage'>$errorstr</div>");
	}
	else{
	
		// Sanitize and enter the data into the database
		$name = sanitize_input($_POST['name']);
		$price = sanitize_input($_POST['price']);
		$description = sanitize_input($_POST['description']);
		$quantity = sanitize_input($_POST['quantity']);
		$imgPath = sanitize_input('uploads/' . basename($_FILES['image']['name']));
	
		$stmt = $db->prepare("SELECT id FROM users WHERE username=?");
		$stmt->bind_param("s", $_SESSION['username']);
		$stmt->execute();
		$result = $stmt->get_result();
		$user = $result->fetch_assoc();
	
		$stmt = $db->prepare("INSERT INTO shop (userID, item, price, description, quantity, imgPath) VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("ssdsis", $user['id'], $name, $price, $description, $quantity, $imgPath);
		$stmt->execute();
	
		echo("<div class='orderMessage'>Your product has been entered into our system!</div>");
	
	}
}

echo("</div>
	<div class='split right'>
		<h3 style='text-align: center'>View Past Orders:</h3>");
// TODO: Show orders that the user has purchased in the past and if there are none, send a message saying so
echo("</div>
</body>
</html>");
?>
