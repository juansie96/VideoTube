
<?php 
class VideoProcessor {

    private $con; // Instance of the DB connection
    private $sizeLimit = 50000000; // Video size limit in bytes
    private $supportedTypes = ["mp4", "flv", "webm", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg"]; // Suported file types.
    private $ffmpegPath = "ffmpeg/bin/ffmpeg";
    private $ffprobePath = "ffmpeg/bin/ffprobe";

    public function __construct($con) {  
        $this->con = $con;
    }

    public function upload($videoUploadData) {
        $targetDir = "uploads/videos/";
        $videoData = $videoUploadData->getVideoDataArray();

        // We create a temporary file path which will contain the video file with the original format and then if it's not mp4 will convert the file to mp4.
        $tempFilePath = $targetDir . uniqid() . basename($videoData["name"]);
        $tempFilePath = str_replace(" ", "_", $tempFilePath);

        // This "print_r" makes a variable information readable by human
        //print_r($videoData); // outputs props like name, size, type, error etc.

        // Check validity (size, type, error)
        $isValidData = $this->processData($videoData, $tempFilePath);

        // Can't continue the function if invalid
        if(!$isValidData) {
            return false;
        } 
        
        // tmp_name   Returns the path of the uploaded file on the server
        // Line below moves the file from the original server path to the specified temporary one
        if (move_uploaded_file($videoData["tmp_name"], $tempFilePath)) {

            $finalFilePath = $targetDir . uniqid() . ".mp4";

            // We try to insert the video data to the DB
            if (!$this->insertVideoData($videoUploadData, $finalFilePath)) {
                echo "Insert query failed";
                return false;
            }

            if (!$this->convertVideoToMp4($tempFilePath, $finalFilePath)) {
                echo "Convert failed";
                return false;
            } 

            if (!$this->deleteFile($tempFilePath)) {
                return false;
            } 

            if (!$this->generateThumbnails($finalFilePath)) {
                echo 'Could not generate thumbnails';
                return false;
            }

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
        // Compare the actual file size with the allowed size limit
        return $data["size"] <= $this->sizeLimit;
    }

    private function isValidType($type) {
        $lowercasedType = strtolower($type);
        // We check if the video file type is in the supportedFileTypes
        return in_array($lowercasedType, $this->supportedTypes);
    }

    private function hasError($data) {
        // If error is different than 0 then it has an error
        return $data["error"] != 0;
    }

    private function insertVideoData($uploadData, $filePath) {
        $query = $this->con->prepare("INSERT INTO videos(title, uploadedBy, description, privacy, category, filePath)
                                      VALUES(:title, :uploadedBy, :description, :privacy, :category, :filePath)");

        $title = $uploadData->getTitle();
        $uploadedBy = $uploadData->getUploadedBy();
        $description = $uploadData->getDescription();
        $privacy = $uploadData->getPrivacy();
        $category = $uploadData->getCategory();

        $query->bindParam(':title', $title);
        $query->bindParam(':uploadedBy', $uploadedBy);
        $query->bindParam(':description', $description);
        $query->bindParam(':privacy', $privacy);
        $query->bindParam(':category', $category);
        $query->bindParam(':filePath', $filePath);

        return $query->execute();
    }

    public function convertVideoToMp4($tempFilePath, $finalFilePath) {
        $cmd = "$this->ffmpegPath -i $tempFilePath $finalFilePath 2>&1";

        exec('unset DYLD_LIBRARY_PATH ;');
        putenv('DYLD_LIBRARY_PATH');
        putenv('DYLD_LIBRARY_PATH=/usr/bin');

        $outputLog = Array();
        exec($cmd, $outputLog, $returnCode);

        if ($returnCode != 0) {
            // Command execution failed
            foreach($outputLog as $line) {
                echo $line . "<br>";
            }
            return false;
        }

        return true;
        
    }

    private function deleteFile($filePath) {
        if (!unlink($filePath)) {
            echo 'Could not delete file \n';
            return false;
        }

        return true;
    }

    public function generateThumbnails($filePath) {
        $thumbnailSize = "210x118";
        $numThumbnails = 3;
        $pathToThumbnail = "uploads/videos/thumbnails";

        $videoDuration = $this->getVideoDuration($filePath);

        echo $videoDuration;
    }

    private function getVideoDuration($filePath) {
        return shell_exec("$this->ffprobePath -v error -select_streams v:0 -show_entries stream=duration -of default=noprint_wrappers=1:nokey=1 $filePath");
    }

}

?>