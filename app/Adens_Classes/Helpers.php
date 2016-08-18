<?php
 
namespace App;
   
class Helpers {

   // Function to check the given directory 
   // for a file name and continue incrementing
   // counter until a unique name has been generated
   
   public static function makeUniqueName($file,$directory)
   {
      $final_filename = '';
      
      $counter = 0;
      
      while(true)
      {
         $final_filename = $counter.$file->getClientOriginalName();
         
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
