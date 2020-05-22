
<?php 
class VideoProcessor {

    // Class Attributes
    private $con; // Instance of the DB connection
    private $sizeLimit = 50000000; // Video size limit in bytes
    private $supportedTypes = ["mp4", "flv", "webm", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg"]; // Suported file types.
    private $ffmpegPath = "ffmpeg/bin/ffmpeg";
    private $ffprobePath = "ffmpeg/bin/ffprobe";


    public function __construct($con) {  
        $this->con = $con;
    } // function constructor


    public function upload($videoUploadData) {
        $targetDir = "uploads/videos/";
        $videoData = $videoUploadData->getVideoDataArray();

        // Create a temporary file path which will contain the video file with the original format and then if it's not mp4 will convert the file to mp4.
        $tempFilePath = $targetDir . uniqid() . basename($videoData["name"]);
        $tempFilePath = str_replace(" ", "_", $tempFilePath);

        // This "print_r" makes a variable information readable by human
        //print_r($videoData); // outputs props like name, size, type, error etc.

        // Check validity (size, type, error)
        $isValidData = $this->processData($videoData, $tempFilePath);

        if(!$isValidData) {
            return false;
        } 
        
        // tmp_name   Returns the path of the uploaded file on the server
        // Line below moves the file from the original server path to the specified temporary one
        if (move_uploaded_file($videoData["tmp_name"], $tempFilePath)) {

            $finalFilePath = $targetDir . uniqid() . ".mp4";

            // Try to insert the video data to the DB
            if (!$this->insertVideoData($videoUploadData, $finalFilePath)) {
                echo "Insert query failed";
                return false;
            }

            // Convert if possible the video to mp4 and save it to the final path
            if (!$this->convertVideoToMp4($tempFilePath, $finalFilePath)) {
                echo "Convert failed";
                return false;
            } 

            // Delete the temporary file
            if (!$this->deleteFile($tempFilePath)) {
                return false;
            } 

            // Generate the thumbnails for the video
            if (!$this->generateThumbnails($finalFilePath)) {
                echo 'Could not generate thumbnails';
                return false;
            }

            return true;

        }

    } // function upload


    // function to check file validation
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
    } // function processData


    private function isValidSize($data) {
        // Compare the actual file size with the allowed size limit
        return $data["size"] <= $this->sizeLimit;
    } // function isValidSize


    private function isValidType($type) {
        $lowercasedType = strtolower($type);
        //Check if the video file type is in the supportedFileTypes
        return in_array($lowercasedType, $this->supportedTypes);
    } // function isValidType


    private function hasError($data) {
        // If error is different than 0 then it has an error
        return $data["error"] != 0;
    } // funcion hasError


    private function insertVideoData($uploadData, $filePath) {

        // 1. Prepare the query statement
    
        $query = $this->con->prepare("INSERT INTO videos(title, uploadedBy, description, privacy, category, filePath)
                                      VALUES(:title, :uploadedBy, :description, :privacy, :category, :filePath)");

        
        $title = $uploadData->getTitle();
        $uploadedBy = $uploadData->getUploadedBy();
        $description = $uploadData->getDescription();
        $privacy = $uploadData->getPrivacy();
        $category = $uploadData->getCategory();

        // 2. Bind the placeholders
        
        $query->bindParam(':title', $title);
        $query->bindParam(':uploadedBy', $uploadedBy);
        $query->bindParam(':description', $description);
        $query->bindParam(':privacy', $privacy);
        $query->bindParam(':category', $category);
        $query->bindParam(':filePath', $filePath);

        // 3. Execute the query
        return $query->execute();

    } // function insertVideoData


    public function convertVideoToMp4($tempFilePath, $finalFilePath) {
        
        // Command to execute the conversion of the file to the final mp4 file. The 2>&1 expression is necessary for error output
        $cmd = "$this->ffmpegPath -i $tempFilePath $finalFilePath 2>&1";

        // The 3 lines below fixes a dyld_library_path bug encountered
        exec('unset DYLD_LIBRARY_PATH ;');
        putenv('DYLD_LIBRARY_PATH');
        putenv('DYLD_LIBRARY_PATH=/usr/bin');

        $outputLog = Array();

        // Execute the command to convert the file to mp4. Save the output log and the error
        exec($cmd, $outputLog, $returnCode);

        if ($returnCode != 0) {
            // Command execution failed
            foreach($outputLog as $line) {
                echo $line . "<br>";
            }
            return false;
        }

        return true;
        
    } // function convertVideoToMp4


    private function deleteFile($filePath) {
        if (!unlink($filePath)) {
            echo 'Could not delete file \n';
            return false;
        }

        return true;
    } // function deleteFile


    public function generateThumbnails($filePath) {
        $thumbnailSize = "210x118";
        $numThumbnails = 3;
        $pathToThumbnail = "uploads/videos/thumbnails";

        $videoDuration = $this->getVideoDuration($filePath);
        $videoId = $this->con->lastInsertId();

        $this->updateDuration($videoDuration, $videoId);

        // Loop to generate the thumbnails
        for($num=1; $num <= $numThumbnails; $num++) {

            $imageName = uniqid() . ".jpg";
            $interval = ($videoDuration * 0.8) / $numThumbnails * $num; // Interval in seconds which to get the thumbnail from
            $fullThumbnailPath = "$pathToThumbnail/$videoId-$imageName";

            // Command to generate the thumbnail
            $cmd = "$this->ffmpegPath -i $filePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbnailPath 2>&1";

            $outputLog = Array();
            exec($cmd, $outputLog, $returnCode);

            if ($returnCode != 0) {
                // Command execution failed
                foreach($outputLog as $line) {
                    echo $line . "<br>";
                } // foreach
            } // if

            // 1. Prepare the query statement

            $query = $this->con->prepare("INSERT INTO thumbnails(videoId,filePath,selected) 
                                  VALUES (:videoId,:filePath,:selected)");
            
            // 2. Bind the placeholders

            $query->bindParam(':videoId', $videoId);
            $query->bindParam(':filePath', $filePath);

            $selected = ($num == 1) ? 1 : 0; // By default selected one will be the first thumbnail
            $query->bindParam(":selected", $selected);

            // 3. Execute the query

            $success = $query->execute();

            if (!$success) {
                echo "Error inserting the thumbnail";
                return false;
            }

        } // for

        return true;

    } // function generateThumbnails


    private function getVideoDuration($filePath) {
        return (int)shell_exec("$this->ffprobePath -v error -select_streams v:0 -show_entries stream=duration -of default=noprint_wrappers=1:nokey=1 $filePath");
    } // function getVideoDuration


    private function updateDuration($duration, $videoId) {

        // Code to format the duration in seconds to an ouput with hours minutes and seconds like "1:03:08"
        $hours = floor($duration / 3600);
        $minutes = floor(($duration - ($hours * 3600)) / 60);
        $seconds = floor($duration % 60);

        $hours = ($hours<1) ? "" : "$hours:";
        if ($minutes < 10) $minutes = "0$minutes";
        if ($seconds < 10) $seconds = "0$seconds";

        $duration = "$hours$minutes:$seconds";

        $query = $this->con->prepare("UPDATE videos SET duration = :duration WHERE id = :id ");

        $query->bindParam(':duration', $duration);
        $query->bindParam(':id', $videoId);

        $query->execute();
        
    } // function updateDurationf

}

?>