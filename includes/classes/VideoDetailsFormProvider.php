<?php 
class VideoDetailsFormProvider {

    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function createUploadForm() {
        $fileInput = $this->createFileInput();
        $titleInput = $this->createTitleInput();
        $descriptionInput = $this->createDescriptionInput();
        $privacyInput = $this->createPrivacyInput();
        $categoriesInput = $this->createCategoriesInput();
        $uploadButton = $this->createUploadButton();


        // enctype='multipart/form-data'  is required when using forms that have a file upload control (in this case the video upload)
        return "<form action='processing.php' method='POST' enctype='multipart/form-data'>
                    $fileInput
                    $titleInput
                    $descriptionInput
                    $privacyInput
                    $categoriesInput
                    $uploadButton
                </form>";
    }

    private function createFileInput() {
        return " <div class='form-group'>
                    <input type='file' class='form-control-file' id='exampleFormControlFile1' name='fileInput' required>
                 </div>";
    }

    private function createTitleInput() {
        return "<div class='form-group'>
                    <input class='form-control' type='text' placeholder='Title' name='titleInput' required>
                </div>";
    }

    private function createDescriptionInput() {
        return "<div class='form-group'>
                    <textarea class='form-control' type='text' placeholder='Description' name='descriptionInput' rows='3'></textarea>
                </div>";
    }

    private function createPrivacyInput() {
        return "<div class='form-group'>
                    <select class='form-control' name='privacyInput'>
                        <option value='0'>Private</option>
                        <option value='1'>Public</option>
                    </select>
                </div>";
    }

    private function createCategoriesInput() {
        $query = $this->con->prepare("SELECT * FROM categories");
        $query->execute();

        $optionTags = "";

        // Row will contain the row in the categories tables you're in
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $id = $row["id"];
            $name = $row["name"];
            $optionTags .= "<option value='$id'>$name</option>";
        };

        return "
            <div class='form-group'>
                    <select class='form-control' name='categoryInput'>
                        $optionTags
                    </select>
                </div>
        ";
    }

    private function createUploadButton() {
        return "<button type='submit' class='btn btn-primary' name='uploadButton'>Upload</button>";
    }

    

}

?>