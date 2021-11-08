<?php
// TODO: Take the info from the database and send it to the 'View Past Orders' div.
require('header.php');
require('html/orders.html');

$target_dir = "uploads/";

$errorstr = 'Error: ';
if(isset($_POST['makeOrderSubmit'])){
	if($_FILES['image']['error']){ // If no image was selected
		$errorstr = $errorstr . 'Please select an image, ';
	}
	else {
		// Make sure the image is an actual image
		$target_file = $target_dir . basename($_FILES["image"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  		$check = getimagesize($_FILES["image"]["tmp_name"]);
  		if($check !== false) {
    		//Upload the image to the uploads/ folder
  			if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
  			} 
  			else {
    			$errorstr = $errorstr . 'There was an error uploading your image, ';
  			}
  		}
  		else {
    		$errorstr = $errorstr . 'File uploaded not an image, ';
  		}
  	}

	foreach($_POST as $name => $value){
		if($name != 'makeOrderSubmit'){
			if($value == ''){
				$errorstr = $errorstr . $name . ' missing field, '; // Send an error if any of the values are empty. 
			}
		}
	}
	
	// If there are errors, print them
	if($errorstr != 'Error: '){
		$errorstr = rtrim($errorstr, ", ");
		echo("<div class='orderMessage'>$errorstr</div>");
	}
	else{
	
		// Enter the data into the database
		$name = sanitize_input($_POST['name']);
		$price = sanitize_input($_POST['price']);
		$description = sanitize_input($_POST['description']);
		$quantity = sanitize_input($_POST['quantity']);
		$imgPath = sanitize_input('uploads/' . basename($_FILES['image']['name']));
	
		$stmt = $db->prepare("INSERT INTO shop (userID, item, price, description, quantity, imgPath) VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("ssdsis", $_SESSION['id'], $name, $price, $description, $quantity, $imgPath);
		$stmt->execute();
	
		echo("<div class='orderMessage'>Your product has been entered into our system!</div>");
	
	}
}

echo("</div>
	<div class='split right'>
		<h3 style='text-align: center'>View past Orders:</h3>");
// TODO: Show orders that the user has purchased in the past and if there are none, send a message saying so
echo("</div>
</body>
</html>");
?>
