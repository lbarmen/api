<?php
class Utilities {

    public function error( $massage ) {
        $text = array(
            "status"   =>  "error",
            "message"  =>  $massage
        );

        die(json_encode($text));
    }

}
?>