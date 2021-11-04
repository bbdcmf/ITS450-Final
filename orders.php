<?php
// TODO:
// Take the info from the database and send it to the 'View Past Orders' div.
// Make a function for changing the size of images before uploading them, ensuring they're all the same size.
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
  		} else {
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
	
	if($errorstr != 'Error: '){
		$errorstr = rtrim($errorstr, ", ");
		echo("<div class='orderMessage'>$errorstr</div>");
	}
	else{
	
		// Enter the data into the database
		$name = $_POST['name'];
		$price = $_POST['price'];
		$description = $_POST['description'];
		$quantity = $_POST['quantity'];
		$imgPath = 'uploads/' . basename($_FILES['image']['name']);
	
		$stmt = $db->prepare("INSERT INTO shop (userID, item, price, description, quantity, imgPath) VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("ssdsis", $_SESSION['id'], $name, $price, $description, $quantity, $imgPath);
		$stmt->execute();
	
		echo("<div class='orderMessage'>Your product has been entered into our system!</div>");
	
	}
}

echo("</div>
	<div class='split right'>
		<h3 style='text-align: center'>View past Orders:</h3>
	</div>
</body>
</html>");
?>
