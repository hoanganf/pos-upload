<?php
class FileUploadUtil{
  public static $ALLOW_FILE=array("jpg","png","jpeg","gif");
  public static function isEmptyString($data){
      return (trim($data) === "" or $data === null);
  }
  public static function deleteFile($file){
      return unlink($file);
  }
  public static function getRandomBase64String(){
    return base64_encode(openssl_random_pseudo_bytes(32));
  }
  public static function paste($file,$destination){
    // Check if image file is a actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if($check !== false) {
        //echo "File is an image - " . $check["mime"] . ".";
    } else {
        echo toJson('false',E_FILE_NOT_IMAGE,"File is not an image.");
        return;
    }

    // Check file size
    if ($file["size"] > 500000) {
      echo toJson('false',E_FILE_TOO_LARGE,"Sorry, your file is too large.");
      return;
    }
    //get destination image name
    /*
    print_r(pathinfo("/testweb/test.txt"));
    ->Array(
        [dirname] => /testweb
        [basename] => test.txt
        [extension] => txt
      )*/
      //TODO can using get random name but not now
    $destination.=basename($file['name']);
    $desPathInfo=pathinfo($destination);
    // Allow certain file formats
    if(!in_array(strtolower($desPathInfo['extension']),self::$ALLOW_FILE)) {
        echo toJson('false',E_FILE_NOT_ALLOW,"Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        return;
    }
    // Check if file already exists
    if (file_exists($destination)) {
      if (!unlink($destination)){
        echo toJson('false',E_FILE_ALREADY_EXITS,"Sorry, file already exists.");
        return;
      }
    }else{
      //if not have folder then create
      if (!is_dir($desPathInfo['dirname'])) {
        mkdir($desPathInfo['dirname']);
      }
    }
    // begin to upload
    if (move_uploaded_file($file["tmp_name"], $destination)) {
      echo json_encode(array(
        'status' => true,
        'file_path' => $destination,
        'message' => 'The file '. $desPathInfo['basename'].' has been uploaded.'
      ));
    } else {
      echo toJson('false',E_NI,'Sorry, there was an error uploading your file.');
    }

  }
}
?>
