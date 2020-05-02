
<?php 
class VideoProcessor {

    private $con;
    private $sizeLimit = 50000000;
    private $supportedTypes = ["mp4", "flv", "webm", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg"];

    public function __construct($con) {
        $this->con = $con;
    }

    public function upload($videoUploadData) {
        $targetDir = "uploads/videos/";
        $videoData = $videoUploadData->getVideoDataArray();
        $tempFilePath = $targetDir . uniqid() . basename($videoData["name"]);
        $tempFilePath = str_replace(" ", "_", $tempFilePath);

        // This makes a variable information readable by human
        //print_r($videoData);

        $isValidData = $this->processData($videoData, $tempFilePath);

        if(!$isValidData) {
            return false;
        } 
        
        // tmp_name   Filename of the uploaded file on the server
        if (move_uploaded_file($videoData["tmp_name"], $tempFilePath)) {
            echo 'File moved succesfully';
        }

    }

    private function processData($videoData, $filePath) {
        $videoType = pathinfo($filePath, PATHINFO_EXTENSION);

        if (!$this->isValidSize($videoData)) {
            echo "The file size (" . $videoData["size"] . " bytes) is too big, the limit is (" . $this->sizeLimit . ") bytes";
            return false;
        } else if (!$this->isValidType($videoType)) {
            echo "Invalid type file";
            return false;
        } else if ($this->hasError($videoData)) {
            echo "Error code: " . $videoData["error"];
            return false;
        }

        return true;
    }

    private function isValidSize($data) {
        return $data["size"] <= $this->sizeLimit;
    }

    private function isValidType($type) {
        $lowercasedType = strtolower($type);
        return in_array($lowercasedType, $this->supportedTypes);
    }

    private function hasError($data) {
        return $data["error"] != 0;
    }


}
?>