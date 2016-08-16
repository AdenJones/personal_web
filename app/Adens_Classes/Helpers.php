<?php
 
   
class Helpers {

   // Function to check the given directory 
   // for a file name and continue incrementing
   // counter until a unique name has been generated
   
   public static function makeUniqueName($filename,$directory)
   {
      $file_exists = true;
      $final_filename = '';
      
      $counter = 0;
      
      while($file_exists)
      {
         $final_filename = $counter.$filename;
         
         if(file_exists($directory.$final_filename))
         {
          $counter++;
         } else {
            return $final_filename;
         }
         
      }
   }
}
?>
